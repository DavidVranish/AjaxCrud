// Include gulp
var gulp = require('gulp'); 

// Include Our Plugins
var jshint = require('gulp-jshint');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');

// Lint Task
gulp.task('lint', function() {
    return gulp.src('js/*.js')
        .pipe(jshint())
        .pipe(jshint.reporter('default'));
});

// Concatenate & Minify JS
gulp.task('scripts', function() {
    return gulp.src(['src/config.js', 'src/events.js', 'src/rows.js', 'src/modals.js', 'src/sorting.js', 'src/validation.js', 'src/end.js'])
        .pipe(concat('AjaxCrud.js'))
        .pipe(gulp.dest('dist/js'))
        .pipe(rename('AjaxCrud.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('dist/js'));
});

// Watch Files For Changes
gulp.task('watch', function() {
    gulp.watch('js/*.js', ['lint', 'scripts']);
});

// Default Task
gulp.task('default', ['lint', 'scripts', 'watch'], function() {
	process.exit(0);
});