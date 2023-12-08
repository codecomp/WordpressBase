var gulp         = require('gulp'),
    gulpSequence = require('gulp-sequence'),
    source       = require('vinyl-source-stream'),
    buffer       = require('vinyl-buffer'),
    sass         = require('gulp-sass'),
    postcss      = require('gulp-postcss'),
    gapProperties = require('postcss-gap-properties'),
    postcssLogical = require('postcss-logical'),
    transformShortcut = require('postcss-transform-shortcut'),
    sourceMaps   = require('gulp-sourcemaps'),
    cssNano      = require('gulp-cssnano'),
    eslint       = require('gulp-eslint'),
    sourcemaps   = require('gulp-sourcemaps'),
    babelify     = require('babelify'),
    browserify   = require('browserify'),
    uglify       = require('gulp-uglify'),
    imagemin      = require('gulp-imagemin'),
    autoPrefixer = require('gulp-autoprefixer'),
    browserSync  = require('browser-sync').create(),
    del          = require('del'),
    paths        = {
        favicons: 'assets/favicons',
        fonts: 'assets/fonts',
        image: 'assets/images',
        sass: 'assets/sass',
        js: 'assets/js',
        build: 'dist',
        buildFavicons: 'dist/favicons',
        buildFonts: 'dist/fonts',
        buildImage: 'dist/images',
        buildCss: 'dist/css',
        buildJs: 'dist/js'
    };

// Browser sync tasks
gulp.task('browser:sync', function(done) {
    browserSync.init({
        proxy: "wordpress.local",
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

gulp.task('css:compile', [/*'css:lint'*/], function () {
    return gulp.src([paths.sass + '/main.scss', paths.sass + '/admin-styles.scss', paths.sass + '/admin-editor-styles.scss'])
        .pipe(sourceMaps.init())
        .pipe(sass({
            includePaths: require('node-normalize-scss').includePaths
        }))
        .on('error', function (err) {
            console.log(err.toString());

            this.emit('end');
        })
        .pipe(postcss([gapProperties(), postcssLogical(), transformShortcut()]))
        .on('error', function (err) {
            console.log(err.toString());

            this.emit('end');
        })
        .pipe(autoPrefixer({
            browsers: ['last 5 versions', "safari >= 7"],
            cascade: false
        }))
        .pipe(sourceMaps.write('.'))
        .pipe(gulp.dest(paths.buildCss));
});

// JS tasks
gulp.task('js:lint', function () {
    return gulp.src([paths.js + '/**/*.js'])
        .pipe(eslint())
        .pipe(eslint.format())
        .pipe(eslint.failAfterError());
});

gulp.task('js:compile', ['js:lint'], function () {
    return browserify({
        entries: paths.js + '/main.js',
        debug: true
    })
    .transform(babelify, {
        global: true,
        ignore: /\/node_modules\/(?!@pageclip\/valid-form\/)/
    })
    .bundle()
    .on('error', function (err) { console.error(err); })
    .pipe(source('main.js'))
    .pipe(buffer())
    .pipe(sourcemaps.init({ loadMaps: true }))
    .pipe(sourcemaps.write('./'))
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
    del(paths.buildJs + '/*.map');
    return gulp.src(paths.buildJs + '/*.js')
        .pipe(uglify())
        .pipe(gulp.dest(paths.buildJs));
});

gulp.task('optimise:images', function() {
    return gulp.src(paths.image + '/**.*')
        .pipe(imagemin([
            imagemin.gifsicle({interlaced: true}),
            imagemin.jpegtran({progressive: true}),
            imagemin.optipng({optimizationLevel: 5}),
            imagemin.svgo({
                plugins: [
                    {removeViewBox: true},
                    {removeUnknownsAndDefaults: true},
                    {cleanupIDs: true},
                    {mergePaths: false}
                ]
            })
        ]))
        .pipe(gulp.dest(paths.buildImage));
});

// Misc tasks
gulp.task('move:favicons', function(){
    del(paths.buildFavicons).then(function(){
        return gulp.src([paths.favicons + '/**/*'])
            .pipe(gulp.dest(paths.buildFavicons));
    }).catch(function(error){
        console.log(error.message);
    });
});

gulp.task('move:fonts', function(){
    del(paths.buildFonts).then(function(){
        return gulp.src([paths.fonts + '/**/*'])
            .pipe(gulp.dest(paths.buildFonts));
    }).catch(function(error){
        console.log(error.message);
    });
});

gulp.task('fix', function(done){
    gulpSequence(
        [
            'move:favicons',
            'move:fonts',
            'optimise:images'
        ],
        done
    );
});

// Environment tasks
gulp.task('watch', ['browser:sync'], function () {
    gulp.watch(paths.sass + '/**/*.scss', ['css:compile']);

    gulp.watch(paths.buildCss + '/*', function(file){
        gulp.src(file.path)
            .pipe(browserSync.stream());
    });

    gulp.watch(paths.js + '/**/*.js', ['js:compile']);

    gulp.watch(paths.image + '/*', ['optimise:images']);

    gulp.watch(paths.favicons + '/*', ['move:favicons']);

    gulp.watch(paths.fonts + '/*', ['move:fonts']);

    gulp.watch(paths.buildJs + '/*')
        .on('change', browserSync.reload);

    gulp.watch(['templates/**/*.php', 'layouts/**/*.twig'])
        .on('change', browserSync.reload);
});

gulp.task('deploy', function(done){
    del(paths.build).then(function(){
        gulpSequence(
            [
                'css:compile',
                'js:compile'
            ],
            [
                'optimise:css',
                'optimise:js',
                'optimise:images'
            ],
            done
        );
    }).catch(function(error){
        console.log(error.message);
        done();
    });
});
