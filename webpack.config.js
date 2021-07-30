module.exports = {
	entry: './assets/js/admin/backend.js',
	output: {
		path: __dirname,
		filename: 'assets/js/admin/backend.min.js',
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
