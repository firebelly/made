// Gulp setup for Start Here front end boilerplate

// Load required plugins
var argv         = require('minimist')(process.argv.slice(2));
var gulp         = require('gulp');
var gulpif       = require('gulp-if');
var sass         = require('gulp-sass');
var autoprefixer = require('gulp-autoprefixer');
var minifycss    = require('gulp-minify-css');
var jshint       = require('gulp-jshint');
var uglify       = require('gulp-uglify');
var rename       = require('gulp-rename');
var runSequence  = require('run-sequence');
var imagemin     = require('gulp-imagemin');
var concat       = require('gulp-concat');
var browserSync  = require('browser-sync').create();
var svgstore     = require('gulp-svgstore');
var svgmin       = require('gulp-svgmin');
var sourcemaps   = require('gulp-sourcemaps');

// File path vars
var paths = {
    scssSrc: 'fbmods/sass/**/*',
    jsSrc: ['fbmods/js/libs/*.js', 'fbmods/js/main.js'],
    imgSrc: 'fbmods/images/**/*',
    svgSrc: 'fbmods/svgs/*.svg'
}

// CLI options
var enabled = {
  // Disable source maps when `--production`
  maps: !argv.production
};

// Setup browsersync
gulp.task('browser-sync', function() {
    browserSync.init({
        proxy: "made.dev"
    });
});

// Do the stuff!

// Sass compilation and output
gulp.task('styles', function() {
  return gulp.src([
      'fbmods/sass/main.scss'
    ])
    .pipe(gulpif(enabled.maps, sourcemaps.init()))
    .pipe(sass())
    .pipe(autoprefixer())
    .pipe(gulp.dest('fbmods/css'))
    .pipe(rename({suffix: '.min-1563910490'}))
    .pipe(minifycss())
    .pipe(gulp.dest('fbmods/css'))
    .pipe(browserSync.stream())
    .pipe(gulpif(enabled.maps, sourcemaps.write('.', {
        sourceRoot: 'fbmods/css/'
     }))
    );

});

// Javascript concatenation
gulp.task('scripts', function() {
  return gulp.src(paths.jsSrc)
    .pipe(jshint())
    .pipe(jshint.reporter('default'))
    .pipe(concat('site.js'))
    .pipe(gulp.dest('fbmods/js/build'))
    .pipe(rename({suffix: '.min-1563910490'}))
    .pipe(uglify())
    .pipe(gulp.dest('fbmods/js/build'))
    .pipe(browserSync.stream());
});

// Compress images (must run glulp images manually)
gulp.task('images', function() {
  // return gulp.src(paths.imgSrc)
  //   .pipe(imagemin({ optimizationLevel: 3, progressive: true, interlaced: true }))
  //   .pipe(gulp.dest('images'))
  //   .pipe(browserSync.stream());
});

// SVG time!
gulp.task('svgs', function() {
  // return gulp.src(paths.svgSrc)
  //   .pipe(svgmin({
  //       plugins: [{
  //           removeViewBox: false
  //       }, {
  //           removeEmptyAttrs: false
  //       }]
  //   }))
  //   .pipe(gulp.dest('svgs'))
  //   .pipe(svgstore({ inlineSvg: true }))
  //   .pipe(rename({suffix: '-defs'}))
  //   .pipe(gulp.dest('svgs/build'))
  //   .pipe(browserSync.stream());
});

// Do the build
gulp.task('build', function(callback) {
  runSequence('styles',
              'scripts',
              ['images', 'svgs'],
              callback);
});

// Gulp watch
gulp.task('watch', function() {
  // Init BrowserSync
  browserSync.init({
    files: ['*.html', '*.php'],
    proxy: 'made.localhost',
    notify: false,
    open: false,
    snippetOptions: {
      whitelist: ['/wp-admin/admin-ajax.php'],
      blacklist: ['/wp-admin/**']
    }
  });
  // Kick it off with a build
  gulp.start('build');
  // Watch sass files
  gulp.watch(paths.scssSrc, ['styles']);
  // Watch js files
  gulp.watch(paths.jsSrc, ['scripts']);
  // Watch SVGs
  gulp.watch(paths.svgSrc, ['svgs']);
});

// Make watch the default task
gulp.task('default', function() {
    gulp.start('build');
});
