var elixir = require('laravel-elixir');
var gulp = require('gulp');
var concat = require('gulp-concat');
var minifyjs = require('gulp-uglify');
var minifycss = require('gulp-minify-css');
var less = require('gulp-less');
var rev = require('gulp-rev');
var del = require('del');

require('laravel-elixir-codeception');
require('laravel-elixir-bower');

//JS
var jsSources = [
    'resources/assets/bower/jquery/dist/jquery.js',
    'resources/assets/bower/bootstrap/dist/js/bootstrap.js',
    'resources/assets/bower/bootstrap-datepicker/js/bootstrap-datepicker.js',
    'public/js/lib/*',
    'public/js/partials/*'
];

gulp.task('js', function() {
    return gulp.src(jsSources)
        .pipe(minifyjs())
        .pipe(concat('main.js'))
        .pipe(gulp.dest('public/js/'));
});


//LESS
var lessSources = 'resources/assets/less/application.less';

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
    'resources/assets/bower/bootstrap/dist/fonts/*'
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


gulp.task('default', ['less', 'js', 'fonts']);