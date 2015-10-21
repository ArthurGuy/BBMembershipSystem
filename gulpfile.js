
var gulp = require('gulp');
var concat = require('gulp-concat');
var minifyjs = require('gulp-uglify');
var minifycss = require('gulp-minify-css');
var less = require('gulp-less');
var rev = require('gulp-rev');
var rename = require('gulp-rename');
var del = require('del');
var browserify = require('gulp-browserify');
var babelify = require('babelify');

var elixir = require('laravel-elixir');



//JS
gulp.task('browserify', function() {
    elixir(function(mix) {
        mix.browserify('app.js', 'public/js/bundle.js');
    });
});



//LESS
var lessSources = [
    'resources/assets/less/application.less'
];

gulp.task('less', function() {
    return gulp.src(lessSources)
        .pipe(less())
        .pipe(concat('main.css'))
        .pipe(gulp.dest('public/css/'));
});



var cssSources = [
    //'/public/css/lib/*',
    '/public/css/application.css'
];

gulp.task('css', function() {
    return gulp.src(cssSources)
        .pipe(concat('main.css'))
        .pipe(minifycss())
        .pipe(gulp.dest('public/css/'));
});



//FONTS
var fontSources = [
    'node_modules/bootstrap/dist/fonts/*',
    'node_modules/material-design-icons/iconfont/*'
    //'public/src/fonts/*'
];

gulp.task('fonts', function() {
    return gulp.src(fontSources)
        .pipe(gulp.dest('public/fonts/'));
});



//turn the built assets into versioned assets
gulp.task('version-assets', function () {

    //clear the previous builds
    del.sync('./public/build/*', { force: true });

    return gulp.src(['./public/dist/css/*.css', './public/dist/js/*.js'], {base: './public/dist'})
        .pipe(gulp.dest('./public/build'))  // copy original assets to build dir
        .pipe(rev())
        .pipe(gulp.dest('./public/build'))  // write rev'd assets to build dir
        .pipe(rev.manifest())
        .pipe(gulp.dest('./public/build')) // write manifest to build dir
        .on('end', function() {
            // We'll get rid of the duplicated file that
            // usually gets put in the "build" folder,
            // alongside the suffixed version.
            //del(files.paths);
        });
});


gulp.task('default', ['less', 'browserify', 'fonts']);