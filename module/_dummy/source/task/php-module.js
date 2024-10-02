import gulp from 'gulp';
import newer from 'gulp-newer';
import browserSync from 'browser-sync';
import { path } from '../config.js';

function phpModule() {
  return gulp.src(path.phpModule.src)
    .pipe(newer(path.phpModule.dist))
    .pipe(gulp.dest(path.phpModule.dist))
    .pipe(browserSync.stream());
}

export default phpModule;
