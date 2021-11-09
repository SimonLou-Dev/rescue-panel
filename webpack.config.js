require('webpack');

module.exports = {
    context: __dirname + '/src',
    entry: {
        javascript: "./app.js",
        html: "./index.html"
    },
    output: {
        filename: 'app.js',
        path: __dirname + '/dist'
    },
    module: {
        loaders: [
            {
                test: /\.jsx?$/,
                exclude: /node_modules/,
                loaders: ['react-hot', 'babel-loader?presets[]=react,presets[]=es2015'],
                //loaders: ["react-hot", 'babel-loader'],
                //query: {
                //    presets : ['es2015', 'react']
                //}
            },
            {
                test: /\.html$/,
                loader: "file?name=[name].[ext]"
            }
        ]
    }
};
