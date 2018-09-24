module.exports = function (gulp, plugins) {
    return function () {
        return gulp.src(
                	plugins.pkg._themepath + '/assets/js/custom.js',
                    { base: plugins.pkg._themepath + '/assets/js' }
                )
                .pipe(plugins.concat('custom.js'))
                .pipe(plugins.include()).on('error', console.log)
                //.pipe(gulp.dest(plugins.pkg._themepath + '/assets/build/revisions/'))
                .pipe(plugins.rename({suffix: '.min'}))
                .pipe(plugins.uglify())
                .pipe(gulp.dest(plugins.pkg._themepath + '/assets/build/'))
                .pipe(plugins.notify({ message: 'Scripts task complete' }));
    };
};
