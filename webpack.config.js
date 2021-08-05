module.exports = {
	entry: './assets/js/admin/settings.js',
	output: {
		path: __dirname,
		filename: 'assets/js/admin/settings.min.js',
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
