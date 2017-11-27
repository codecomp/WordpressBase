var gulp        = require('gulp');
var browserSync = require('browser-sync').create();

// Setup broser sync for standard local hosts
gulp.task('browser-sync', function() {
    browserSync.init({
        proxy: "localhost:8888"
    });
});

// Setup watch command
gulp.task('watch', ['browser-sync'], function () {
    gulp.watch("*.php").on('change', browserSync.reload);
});