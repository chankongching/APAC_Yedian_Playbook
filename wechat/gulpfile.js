var gulp = require('gulp');
var spritesmith = require('gulp.spritesmith');
var imagemin = require('gulp-imagemin');
var pngquant = require('imagemin-pngquant');
var merge = require('merge-stream');
var runSequence = require('run-sequence');
var gm = require('gulp-gm');
var rename = require('gulp-rename');
var del = require('del');

gulp.task('clean', function() {
    return del(['dist/*.html', 'dist/assets']);
});

gulp.task('normalimage', function() {
    return gulp.src('src/img/icons/*@2x.png')
        .pipe(gm(function(gmfile) {
            return gmfile.resize(50, 50, '%');
        }))
        .pipe(rename(function(path) {
            path.basename = path.basename.replace('@2x', '');
        }))
        .pipe(gulp.dest('src/img/icons/_normal'));
});

gulp.task('clean-normalimage', function() {
    return del(['src/img/sprite.png', 'src/img/icons/_normal']);
});

gulp.task('sprite', ['normalimage'], function() {
    var spriteData = gulp.src(['src/img/icons/**/*.png']).pipe(spritesmith({
        imgName: 'sprite.png',
        imgPath: '../img/sprite.png',
        retinaSrcFilter: ['src/img/icons/*@2x.png'],
        retinaImgName: 'sprite@2x.png',
        retinaImgPath: '../img/sprite@2x.png',
        cssName: '_sprite.scss',
        padding: 2
    }));

    var imgStream = spriteData.img.pipe(gulp.dest('src/img'));
    var cssStream = spriteData.css.pipe(gulp.dest('src/scss'));

    return merge(imgStream, cssStream);
});

gulp.task('build-sprite', function(callback) {
    runSequence('sprite', 'clean-normalimage', callback);
});

gulp.task('imagemin', function() {
    return gulp.src(['dist/assets/img/**/*', '!dist/assets/img/banner.png'])
        .pipe(imagemin({
            progressive: true,
            use: [pngquant()]
        }))
        .pipe(gulp.dest('dist/assets/img'));
});

gulp.task('copy-assets', function(callback) {
    gulp.src('src/assets/**/*').pipe(gulp.dest('dist/assets'));
});

gulp.task('build', function(callback) {
    runSequence('clean', 'build-sprite', 'copy-assets', callback);
});