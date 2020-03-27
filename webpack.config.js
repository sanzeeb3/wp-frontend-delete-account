module.exports = {
	entry: './assets/js/admin/wpfda-gutenberg-block.js',
	output: {
		path: __dirname,
		filename: 'assets/js/admin/wpfda-gutenberg-block.min.js',
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
