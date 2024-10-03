import gulp from 'gulp';
import browserSync from 'browser-sync';
import { isProd, path } from './config.js';
import del from './task/del.js';
import font from './task/font.js';
import img from './task/img.js';
import js from './task/js.js';
import php from './task/php.js';
import phpModule from './task/php-module.js';
import publicFiles from './task/public-files.js';
import sass from './task/sass.js';
import server from './task/server.js';

function watch() {
  gulp.watch(path.font.watch, font).on('change', browserSync.reload);
  gulp.watch(path.img.watch, img).on('change', browserSync.reload);
  gulp.watch(path.js.watch, js).on('change', browserSync.reload);
  gulp.watch(path.php.watch, php).on('change', browserSync.reload);
  gulp.watch(path.phpModule.watch, phpModule).on('change', browserSync.reload);
  gulp.watch(path.public.watch, publicFiles).on('change', browserSync.reload);
  gulp.watch(path.sass.watch, sass);
}

function compileFiles() {
  return gulp.series(
    del,
    gulp.parallel(font, img, js, php, phpModule, publicFiles, sass),
  );
}

function startServer() {
  return gulp.parallel(server, watch);
}

function startDevServer() {
  return gulp.series(
    compileFiles(),
    startServer(),
  );
}

const dev = startDevServer();
const prod = compileFiles();
const build = compileFiles();
const preview = startServer();

export {
  del,
  font,
  img,
  js,
  php,
  phpModule,
  publicFiles,
  sass,
  server,

  dev,
  prod,
  build,
  preview,
};

export default isProd ? prod : dev;
