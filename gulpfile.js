var gulp         = require('gulp'),
    gulpSequence = require('gulp-sequence'),
    sass         = require('gulp-sass'),
    scssLint     = require('gulp-scss-lint'),
    sourceMaps   = require('gulp-sourcemaps'),
    cssNano      = require('gulp-cssnano'),
    browserSync  = require('browser-sync').create(),
    del          = require('del'),
    paths        = {
        sass: 'assets/sass',
        buildCss: 'assets/css'
    };

// Browser sync tasks
gulp.task('browser:sync', function(done) {
    browserSync.init({
        proxy: "localhost:8888"
    }, done);
});

gulp.task('browser:reload', function(done){
    browserSync.reload();
    done();
});

// CSS tasks
gulp.task('scss-lint', function () {
    return gulp.src(paths.sass + '/**/*.scss')
        .pipe(scssLint());
});

gulp.task('sass', ['scss-lint'], function () {
    return gulp.src(paths.sass + '/**/*.scss')
        .pipe(sourceMaps.init())
        .pipe(sass({
            errLogToConsole: true
        }))
        .pipe(sourceMaps.write(''))
        .pipe(gulp.dest(paths.buildCss));
});

// Optimisation tasks
gulp.task('optimise:css', function () {
    del(paths.buildCss + '/*.map');
    return gulp.src(paths.buildCss + '/*.css')
        .pipe(cssNano())
        .pipe(gulp.dest(paths.buildCss));
});

// Environment tasks
gulp.task('watch', ['browser:sync'], function () {
    gulp.watch(paths.sass + '/**/*.scss', ['sass']);

    gulp.watch(paths.buildCss + '/*', function(file){
        gulp.src(file.path)
            .pipe(browserSync.stream());
    });

    gulp.watch("*.php")
        .on('change', browserSync.reload);
});

gulp.task('deploy', function(done){
    gulpSequence(
        'browser:sync',
        [
            'optimise:css'
        ],
        'browser:reload',
        done
    );
});