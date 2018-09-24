/*
    npm install gulp-ruby-sass gulp-imagemin gulp-newer gulp-autoprefixer gulp-minify-css gulp-jshint jshint-stylish gulp-concat gulp-uglify gulp-imagemin gulp-notify gulp-rename gulp-livereload gulp-cache del require-dir --save-dev
*/

var gulp = require('gulp'),
    runSequence = require('run-sequence'),
    sequence = require('gulp-watch-sequence');

var plugins = require('gulp-load-plugins')({
    scope: ['devDependencies']
});
plugins.pkg = require('./package.json');

function getTask(task) {
    return require('./gulp-tasks/' + task)(gulp, plugins);
}

//gulp.task('scripts', getTask('scripts'));
gulp.task('styles', getTask('styles')); 
//gulp.task('images', getTask('images'));
//gulp.task('jshint', getTask('jshint'));
//gulp.task('revision', getTask('revision'));

//var scripts = [plugins.pkg._themepath + '/assets/js/**/*.js', '!' + plugins.pkg._themepath + '/assets/js/plugins/**/*.js'];

gulp.task('watch', function() {
    var queue = sequence(300);

    // Watch .scss files
    gulp.watch(plugins.pkg._themepath + '/sass/**/*.scss', {
        name      : 'CSS',
        emitOnGlob: false
    }, queue.getHandler('styles'));

    // Watch .js files
//    gulp.watch(scripts, {
//        name      : 'JS',
//        emitOnGlob: false 
//    }, queue.getHandler('scripts'));

});

gulp.task('default', function() {
    runSequence('styles');
});