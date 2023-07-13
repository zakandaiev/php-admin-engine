const pathSrc = './src';
const pathDist = '../view';

module.exports = {
	src: pathSrc,
	dist: pathDist,

	clear: pathDist,

	php: {
		src: `${pathSrc}/php/**/*.{php,sql}`,
		watch: `${pathSrc}/php/**/*.{php,sql}`,
		dist: pathDist
	},

	sass: {
		src: `${pathSrc}/sass/*.{sass,scss}`,
		watch: `${pathSrc}/sass/**/*.{sass,scss}`,
		dist: `${pathDist}/asset/css`
	},

	js: {
		src: `${pathSrc}/js/*.js`,
		watch: `${pathSrc}/js/**/*.js`,
		dist: `${pathDist}/asset/js`
	},

	img: {
		src: `${pathSrc}/img/**/*.*`,
		watch: `${pathSrc}/img/**/*.*`,
		dist: `${pathDist}/asset/img`
	},

	font: {
		src: `${pathSrc}/font/**/*.woff2`,
		watch: `${pathSrc}/font/**/*.woff2`,
		dist: `${pathDist}/asset/font`
	},

	rootFiles: {
		src: `${pathSrc}/root-files/**/*.*`,
		watch: `${pathSrc}/root-files/**/*.*`,
		dist: `${pathDist}/asset`
	}
};
