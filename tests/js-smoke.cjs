// Verify the built scripts use WordPress globals and initialize without a bundler runtime.
const assert = require('assert');
const fs = require('fs');
const vm = require('vm');
const path = require('path');

const ready = [];
const rendered = [];
const blocks = [];
const containers = [{ id: 'first' }, { id: 'second' }];
const settings = { id: 'settings' };
const notice = { id: 'root' };
const context = {
  wp: {
    element: {
      Component: class {},
      createElement: (type, props, ...children) => ({ type, props, children }),
      render: (element, target) => rendered.push({ element, target }),
      useState: value => [value, () => {}],
    },
    i18n: { __: text => text },
    blocks: { registerBlockType: (name, args) => blocks.push({ name, args }) },
  },
  wpfda_plugins_params: {
    users: [], upgrade_notice_dismissed: 'no', security: 'password',
    password_text: 'Enter password', button: 'Confirm',
    require_acknowledgement: true, acknowledgement_text: 'Deletion is permanent',
  },
  document: {
    addEventListener: (event, callback) => { if (event === 'DOMContentLoaded') ready.push(callback); },
    getElementsByClassName: () => containers,
    getElementById: id => id === 'root' ? notice : id === 'wp-frontend-delete-account-settings-page' ? settings : null,
  },
  console,
};
vm.createContext(context);
for (const file of ['assets/js/frontend.min.js', 'assets/js/admin/settings.min.js', 'assets/js/admin/gutenberg.min.js']) {
  vm.runInContext(fs.readFileSync(path.join(__dirname, '..', file), 'utf8'), context, { filename: file });
}
ready.forEach(callback => callback());
assert.deepStrictEqual(rendered.map(entry => entry.target.id).sort(), ['first', 'root', 'second', 'settings']);
const form = rendered.find(entry => entry.target.id === 'first').element.type();
function flatten(element) {
  if (Array.isArray(element)) return element.flatMap(flatten);
  if (!element || typeof element !== 'object') return [];
  return [element, ...flatten(element.children || [])];
}
const fields = flatten(form);
assert(fields.some(field => field.type === 'input' && field.props.type === 'password'));
assert(fields.some(field => field.type === 'input' && field.props.type === 'checkbox' && field.props.required));
assert.strictEqual(blocks.length, 1);
assert.strictEqual(blocks[0].name, 'wp-frontend-delete-account/delete-account-content');
assert.strictEqual(typeof blocks[0].args.edit, 'function');
console.log('JavaScript smoke tests passed.');
