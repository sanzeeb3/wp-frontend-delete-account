module.exports = {
	entry: {
		gutenberg: { import: './assets/js/admin/gutenberg.js', filename:  'assets/js/admin/gutenberg.min.js' },
		settings: { import: './assets/js/admin/settings.js', filename: 'assets/js/admin/settings.min.js' },
		frontend: { import: './assets/js/frontend.js', filename: 'assets/js/frontend.min.js' },
	},
	output: {
		path: __dirname,
	},
	module: {
		rules: [
			{
				test: /.js$/,
				loader: 'babel-loader',
				exclude: /node_modules/,
			},
		],
	},
};
