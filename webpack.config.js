module.exports = {
	entry: './assets/js/frontend.js',
	output: {
		path: __dirname,
		filename: 'assets/js/frontend.min.js',
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
