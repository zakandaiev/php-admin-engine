import gulp from 'gulp';
import newer from 'gulp-newer';
import browserSync from 'browser-sync';
import { path } from '../config.js';

function php() {
  return gulp.src(path.php.src)
    .pipe(newer(path.php.dist))
    .pipe(gulp.dest(path.php.dist))
    .pipe(browserSync.stream());
}

export default php;
