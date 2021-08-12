module.exports = {
	entry: './assets/js/admin/frontend.js',
	output: {
		path: __dirname,
		filename: 'assets/js/admin/frontend.min.js',
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
