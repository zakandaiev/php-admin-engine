const isProd = process.argv.includes('--prod');

module.exports = {
	// MODE
	isProd: isProd,
	isDev: !isProd,

	// DEL
	clear: {
		force: true
	},

	// IMAGE
	imagemin: {
		gifsicle: {interlaced: true},
		mozjpeg: {quality: 75, progressive: true},
		optipng: {optimizationLevel: 5},
		svgo: {
			plugins: [
				{removeViewBox: false},
				{convertShapeToPath: false},
				{convertEllipseToCircle: false}
			]
		}
	},

	// SASS
	sass: {
		includePaths: ['node_modules']
	},
	autoprefixer: {
		cascade: !isProd,
		grid: false
	},
	cleanCss: {
		level: 1
	},

	// JS
	terser: {
		mangle: true,
		keep_classnames: true,
		keep_fnames: false,
		ie8: false
	}
};