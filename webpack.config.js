module.exports = {
	entry: './assets/js/admin/gutenberg.js',
	output: {
		path: __dirname,
		filename: 'assets/js/admin/gutenberg.min.js',
	},
	module: {
		loaders: [
			{
				test: /.js$/,
				loader: 'babel-loader',
				exclude: /node_modules/,
			},
		],
	},
};
