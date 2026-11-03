Welcome to the WP Frontend Delete Account repository on GitHub

## Documentation
* [WP Frontend Delete Account Documentation](https://miniplugins.com/wp-frontend-delete-account/)

## Support
Please use [WordPress.org forum](https://wordpress.org/support/plugin/wp-frontend-delete-account/). Support requests here in the repo might get closed at sight.


## Contributing Guidelines

Collaboration is cool. Any contribution is welcome! Please add details wherever relevant.

## Releases

Free and PRO are built from the same source. Freemius removes `src/Pro/` and the premium-only loader for the free build. Do not publish the unprocessed source zip to WordPress.org.

Before tagging, verify the version is not already present on Freemius and configure required reviewers for the GitHub `production` environment. Without reviewers, the release job publishes automatically. A version tag uploads the PRO archive as pending and downloads the free build; test both editions on a staging site before approving the production job. That job deploys the free build to WordPress.org and then releases the Freemius version. The workflow requires the `FREEMIUS_DEV_ID`, `FREEMIUS_PUBLIC_KEY`, `FREEMIUS_SECRET_KEY`, `SVN_USERNAME`, and `SVN_PASSWORD` secrets. Re-run from the same tag to resume a failed deployment.

Run `php tests/release-smoke.php` and `node tests/js-smoke.cjs` for isolated smoke tests. Build JavaScript with `npm ci` and `npm run build` (Node 18 or later).
