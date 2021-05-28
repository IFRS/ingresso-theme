const argv         = require('minimist')(process.argv.slice(2));
const autoprefixer = require('autoprefixer');
const babel        = require('gulp-babel');
const browserSync  = require('browser-sync').create();
const csso         = require('gulp-csso');
const del          = require('del');
const gulp         = require('gulp');
const path         = require('path');
const pixrem       = require('pixrem');
const PluginError  = require('plugin-error');
const postcss      = require('gulp-postcss');
const sass         = require('gulp-sass');
const sourcemaps   = require('gulp-sourcemaps');
const uglify       = require('gulp-uglify');
const webpack      = require('webpack');

gulp.task('clean', function() {
    return del(['css/', 'js/', 'dist/']);
});

sass.compiler = require('dart-sass');
gulp.task('sass', function() {
    let postCSS_plugins = [
        require('postcss-flexibility'),
        pixrem(),
        autoprefixer(),
    ];

    let sass_options = {
        includePaths: ['sass', 'node_modules'],
        outputStyle: 'expanded'
    };

    return gulp.src('sass/*.scss')
    .pipe(sourcemaps.init())
    .pipe(sass.sync(sass_options).on('error', sass.logError))
    .pipe(postcss(postCSS_plugins))
    .pipe(sourcemaps.write('./'))
    .pipe(gulp.dest('css/'))
    .pipe(browserSync.stream());
});

gulp.task('styles', gulp.series('sass', function css() {
    return gulp.src('css/*.css')
    .pipe(csso())
    .pipe(gulp.dest('css/'))
    .pipe(browserSync.stream());
}));

gulp.task('webpack', function(done) {
    webpack({
        mode: argv.production ? 'production' : 'development',
        devtool: 'source-map',
        entry: {
            ie: './src/ie.js',
            ingresso: './src/ingresso.js',
            oportunidades: './src/oportunidades.js',
        },
        output: {
            path: path.resolve(__dirname, 'js'),
            filename: '[name].js'
        },
        resolve: {
            alias: {
                jquery: 'jquery/dist/jquery',
                bootstrap: 'bootstrap/dist/js/bootstrap.bundle',
            }
        },
        plugins: [
            new webpack.ProvidePlugin({
                $: 'jquery',
            })
        ],
        optimization: {
            minimize: false,
            splitChunks: {
                chunks: 'all',
                cacheGroups: {
                    vendors: false,
                    commons: {
                        name: "commons",
                        chunks: "all",
                        minChunks: 1,
                    }
                }
            }
        },
    }, function(err, stats) {
        if (err) throw new PluginError('webpack', {
            message: err.toString({
                colors: true
            })
        });
        if (stats.hasErrors()) throw new PluginError('webpack', {
            message: stats.toString({
                colors: true
            })
        });
        browserSync.reload();
        done();
    });
});

gulp.task('scripts', gulp.series('webpack', function js() {
    return gulp.src('js/*.js')
    .pipe(babel({
        presets: [
            [
                "@babel/env",
                { "modules": false }
            ]
        ]
    }))
    .pipe(uglify({
        ie8: true,
    }))
    .pipe(gulp.dest('js/'))
    .pipe(browserSync.stream());
}));

gulp.task('dist', function() {
    return gulp.src([
        '**',
        '!dist{,/**}',
        '!node_modules{,/**}',
        '!sass{,/**}',
        '!src{,/**}',
        '!.**',
        '!gulpfile.js',
        '!package-lock.json',
        '!package.json',
    ])
    .pipe(gulp.dest('dist/'));
});

if (argv.production) {
    gulp.task('build', gulp.series('clean', gulp.parallel('styles', 'scripts'), 'dist'));
} else {
    gulp.task('build', gulp.series('clean', gulp.parallel('sass', 'webpack')));
}

gulp.task('default', gulp.series('build', function watch() {
    browserSync.init({
        ghostMode: false,
        notify: false,
        online: false,
        open: false,
        proxy: argv.URL || argv.url || 'localhost',
    });

    gulp.watch('sass/**/*.scss', gulp.series('sass'));

    gulp.watch('src/**/*.js', gulp.series('webpack'));

    gulp.watch('**/*.php').on('change', browserSync.reload);
}));
