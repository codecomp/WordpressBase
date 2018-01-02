var gulp         = require('gulp'),
    gulpSequence = require('gulp-sequence'),
    source       = require('vinyl-source-stream'),
    buffer       = require('vinyl-buffer'),
    sass         = require('gulp-sass'),
    scssLint     = require('gulp-scss-lint'),
    sourceMaps   = require('gulp-sourcemaps'),
    cssNano      = require('gulp-cssnano'),
    jshint       = require('gulp-jshint'),
    browserify   = require('browserify'),
    uglify       = require('gulp-uglify'),
    autoPrefixer = require('gulp-autoprefixer'),
    browserSync  = require('browser-sync').create(),
    del          = require('del'),
    paths        = {
        sass: 'assets/sass',
        buildCss: 'dist/css',
        js: 'assets/js',
        buildJs: 'dist/js'
    };

// Browser sync tasks
gulp.task('browser:sync', function(done) {
    browserSync.init({
        proxy: "localhost:8888",
        open: false
    }, done);
});

gulp.task('browser:reload', function(done){
    browserSync.reload();
    done();
});

// CSS tasks
gulp.task('css:lint', function () {
    return gulp.src([paths.sass + '/**/*.scss', '!' + paths.sass + '/modules/_media-queries.scss'])
        .pipe(scssLint({'config': 'scss-lint.yml'}));
});

gulp.task('css:compile', ['css:lint'], function () {
    return gulp.src([paths.sass + '/main.scss', paths.sass + '/admin-editor-styles.scss'])
        .pipe(sourceMaps.init())
        .pipe(sass({
            includePaths: require('node-normalize-scss').includePaths
        }))
        .on('error', function (err) {
            console.log(err.toString());

            this.emit('end');
        })
        .pipe(autoPrefixer({
            browsers: ['last 2 versions'],
            cascade: false
        }))
        .pipe(sourceMaps.write('.'))
        .pipe(gulp.dest(paths.buildCss));
});

// JS tasks
gulp.task('js:lint', function () {
    return gulp.src([paths.js + '/**/*.js'])
        .pipe(jshint())
        .pipe(jshint.reporter('default'));
});

gulp.task('js:compile', ['js:lint'], function () {
    return browserify({
            entries: paths.js + '/main.js',
            debug: true
        })
        .bundle()
        .pipe(source('main.js'))
        .pipe(buffer())
        .pipe(gulp.dest(paths.buildJs));
});

// Optimisation tasks
gulp.task('optimise:css', function () {
    del(paths.buildCss + '/*.map');
    return gulp.src(paths.buildCss + '/*.css')
        .pipe(cssNano())
        .pipe(gulp.dest(paths.buildCss));
});

gulp.task('optimise:js', function() {
    return gulp.src(paths.buildJs + '/*.js')
        .pipe(uglify())
        .pipe(gulp.dest(paths.buildJs));
});

// Environment tasks
gulp.task('watch', ['browser:sync'], function () {
    gulp.watch(paths.sass + '/**/*.scss', ['css:compile']);

    gulp.watch(paths.buildCss + '/*', function(file){
        gulp.src(file.path)
            .pipe(browserSync.stream());
    });

    gulp.watch(paths.js + '/**/*.js', ['js:compile']);

    gulp.watch(paths.buildJs + '/*')
        .on('change', browserSync.reload);

    gulp.watch(['*.php', 'templates/**/*.twig'])
        .on('change', browserSync.reload);
});

gulp.task('deploy', function(done){
    gulpSequence(
        'browser:sync',
        [
            'css:compile',
            'js:compile'
        ],
        [
            'optimise:css',
            'optimise:js'
        ],
        'browser:reload',
        done
    );
});