var
    gulp         = require('gulp'),
    autoprefixer = require('gulp-autoprefixer'),
    cache        = require('gulp-cache'),
    concat       = require('gulp-concat'),
    copy         = require('gulp-copy'),
    imagemin     = require('gulp-imagemin'),
    less         = require('gulp-less'),
    livereload   = require('gulp-livereload'),
    minifycss    = require('gulp-minify-css'),
    size         = require('gulp-size'),
    uglify       = require('gulp-uglify'),
    watch        = require('gulp-watch')
;

var config = {
    srcCss: [
        'node_modules/bootstrap/dist/css/bootstrap.css',
        'node_modules/select2/dist/css/select2.css',
        'assets/less/*.less'
    ],
    srcFonts: [
        'node_modules/bootstrap/fonts/**'
    ],
    srcJs: [
        'node_modules/jquery/dist/jquery.js',
        'node_modules/bootstrap/dist/js/bootstrap.js',
        'node_modules/select2/dist/js/select2.js',
        'assets/prosemirror/build.js',
        'assets/js/*.js'
    ],
    destJs:    'web/assets/js',
    destCss:   'web/assets/css',
    destFonts: 'web/assets/fonts'
}

gulp.task('js', function () {

    return gulp
        .src(config.srcJs)
        .pipe(concat('all.js'))
        .pipe(gulp.dest(config.destJs))
        .pipe(size())
        .pipe(livereload())
    ;
});

gulp.task('jsMin', function () {
    return gulp
        .src(config.srcJs)
        .pipe(concat('all.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest(config.destJs))
        .pipe(size())
        .pipe(livereload())
    ;
});

gulp.task('css', function () {
    return gulp
        .src(config.srcCss)
        .pipe(concat('all.css'))
        .pipe(less())
        .pipe(gulp.dest(config.destCss))
        .pipe(size())
        .pipe(livereload())
    ;
});

gulp.task('cssMin', function () {
    return gulp
        .src(config.srcCss)
        .pipe(concat('all.min.css'))
        .pipe(less())
        .pipe(minifycss())
        .pipe(gulp.dest(config.destCss))
        .pipe(size())
        .pipe(livereload())
    ;
});

gulp.task('fonts', function () {
    gulp
        .src(config.srcFonts)
        .pipe(copy(config.destFonts, { prefix: 3 }))
        .pipe(size());
});

gulp.task('watch', function () {
    livereload.listen(
        35729,
        function (err) {
            gulp.watch(config.srcCss, ['css']);
            gulp.watch(config.srcJs, ['js']);
        }
    );
});

gulp.task('default', ['js', 'css', 'fonts']);
gulp.task('min', ['jsMin', 'cssMin', 'fonts']);
