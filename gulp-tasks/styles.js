module.exports = function (gulp, plugins) {
    return function () {
        return gulp.src(plugins.pkg._themepath + '/sass/style.scss')
                .pipe( plugins.sass().on('error', plugins.sass.logError) )
                .pipe(plugins.autoprefixer('last 2 versions', {map: false }))
                .pipe(plugins.combineMq({beautify: false}))
                .pipe(plugins.rename('style.css'))
                //.pipe(gulp.dest(plugins.pkg._themepath + '/assets/build/revisions/'))
                .pipe(plugins.cleanCss())
                .pipe(plugins.rename({suffix: '.min'}))
                .pipe(gulp.dest(plugins.pkg._themepath + '/css'))
                .pipe(plugins.notify({ message: 'Styles task complete' }));
    };
};