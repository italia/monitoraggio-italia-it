var gulp = require('gulp');
var gulpCopy = require('gulp-copy');
var del = require('del');

gulp.task('default', ['copy_build']);

/**
 * Task used to update build folder
 * with the corresponding one present into the bower repository
 */
gulp.task('copy_build', function () {
    gulp.src('bower_components/ita-web-toolkit/build/**')
        .pipe(
            gulpCopy(
                'ita-web-toolkit-build',
                {prefix: 3}
            )
        );
});

/**
 * Task used to delete the IWT directory inside bower_components folder
 */
gulp.task('delete', function () {
    del('bower_components/ita-web-toolkit');
});

/**
 * Task used to update build folder
 * with the corresponding one created by the building of the IWT toolkit
 */
gulp.task('copy_overrides', function () {
    gulp.src('bower_components/ita-web-toolkit/build/**')
        .pipe(
            gulpCopy(
                'ita-web-toolkit-build',
                {prefix: 3}
            )
        );
});
