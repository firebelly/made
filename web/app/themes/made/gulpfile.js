// Gulp setup for Start Here front end boilerplate

// Load required plugins
var gulp = require('gulp'),
    sass = require('gulp-ruby-sass'),
    autoprefixer = require('gulp-autoprefixer'),
    minifycss = require('gulp-minify-css'),
    jshint = require('gulp-jshint'),
    uglify = require('gulp-uglify'),
    rename = require('gulp-rename'),
    runSequence  = require('run-sequence'),
    imagemin = require('gulp-imagemin'),
    concat = require('gulp-concat'),
    browserSync = require('browser-sync').create(),
    svgstore = require('gulp-svgstore'),
    svgmin = require('gulp-svgmin')

// File path vars
var paths = {
    scssSrc: 'fbmods/sass/**/*.scss',
    imgSrc: 'fbmods/images/**/*',
    svgSrc: 'fbmods/svgs/*.svg'
}

// Setup browsersync
gulp.task('browser-sync', function() {
    browserSync.init({
        proxy: "made.dev"
    });
});

// Do the stuff!

// Sass compilation and output
gulp.task('styles', function() {
  return sass('fbmods/sass/main.scss', { style: 'expanded' })
    .pipe(autoprefixer())
    .pipe(gulp.dest('fbmods/css'))
    .pipe(rename({suffix: '.min'}))
    .pipe(minifycss())
    .pipe(gulp.dest('fbmods/css'))
    .pipe(browserSync.stream());
});

// Javascript concatenation
gulp.task('scripts', function() {
  return gulp.src(['fbmods/js/libs/*.js', 'fbmods/js/main.js'])
    .pipe(jshint())
    .pipe(jshint.reporter('default'))
    .pipe(concat('site.js'))
    .pipe(gulp.dest('fbmods/js/build'))
    .pipe(rename({suffix: '.min'}))
    .pipe(uglify())
    .pipe(gulp.dest('fbmods/js/build'))
    .pipe(browserSync.stream());
});

// Compress images (must run glulp images manually)
gulp.task('images', function() {
  return gulp.src(paths.imgSrc)
    .pipe(imagemin({ optimizationLevel: 3, progressive: true, interlaced: true }))
    .pipe(gulp.dest('images'))
    .pipe(browserSync.stream());
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
    proxy: 'made.dev',
    notify: false,
  });
  // Kick it off with a build
  gulp.start('build');
  // Watch sass files
  gulp.watch(paths.scssSrc, ['styles']);
  // Watch js files
  gulp.watch(['fbmods/js/libs/*.js', 'fbmods/js/main.js'], ['scripts']);
  // Watch SVGs
  gulp.watch(paths.svgSrc, ['svgs']);
});

// Make watch the default task
gulp.task('default', function() {
    gulp.start('build');
});
