var gulp = require("gulp");
var sass = require('gulp-sass');
var spritesmith = require("gulp.spritesmith");
var svgSprite = require("gulp-svg-sprite");
var imagemin = require("gulp-imagemin");
var pngquant = require("imagemin-pngquant");
var mozjpeg = require("imagemin-mozjpeg");
var merge = require("merge-stream");
var runSequence = require("run-sequence");
var gm = require("gulp-gm");
var rename = require("gulp-rename");
var del = require("del");

gulp.task("clean", function() {
    return del(["dist/*.html", "dist/assets"]);
});

gulp.task("normalimage", function() {
    return gulp.src("src/img/icons/*@2x.png")
        .pipe(gm(function(gmfile) {
            return gmfile.resize(50, 50, "%");
        }))
        .pipe(rename(function(path) {
            path.basename = path.basename.replace("@2x", "");
        }))
        .pipe(gulp.dest("src/img/icons/_normal"));
});

gulp.task("clean-normalimage", function() {
    return del(["src/img/sprite.png", "src/img/icons/_normal"]);
});

gulp.task("sprite", ["normalimage"], function() {
    var spriteData = gulp.src(["src/img/icons/**/*.png"]).pipe(spritesmith({
        imgName: "sprite.png",
        imgPath: "../img/sprite.png",
        retinaSrcFilter: ["src/img/icons/*@2x.png"],
        retinaImgName: "sprite@2x.png",
        retinaImgPath: "../img/sprite@2x.png",
        cssName: "_sprite.scss",
        padding: 2
    }));

    var imgStream = spriteData.img.pipe(gulp.dest("src/img"));
    var cssStream = spriteData.css.pipe(gulp.dest("src/scss"));

    return merge(imgStream, cssStream);
});

gulp.task("svg-sprite", function() {
    return gulp.src("src/img/svgs/*.svg")
        .pipe(svgSprite({
            shape: {
                dimension: {
                    maxWidth: 200,
                    maxHeight: 34
                },
                spacing: {
                    padding: 2
                }
            },
            mode: {
                css: {
                    dest: "./",
                    sprite: "img/sprite.svg",
                    bust: false,
                    render: {
                        scss: {
                            dest: "scss/_svg-sprite.scss",
                            template: "src/scss/svg-sprite-template.scss"
                        }
                    }
                }
            }
        }))
        .on("error", function(error) { console.log(error); })
        .pipe(gulp.dest("src"));
});

gulp.task("build-sprite", function(callback) {
    runSequence("sprite", "clean-normalimage", callback);
});

gulp.task("imagemin", function() {
    return gulp.src("dist/assets/img/**/*")
        .pipe(imagemin([pngquant(), mozjpeg({ progressive: true })]))
        .pipe(gulp.dest("dist/assets/img"));
});

gulp.task("landing-css", function() {
    return gulp.src("src/scss/landing.scss")
        .pipe(sass().on("error", sass.logError))
        .pipe(gulp.dest("dist/assets/css"));
});
gulp.task("landing-img", function() {
    return gulp.src("src/img/landing_*")
        .pipe(gulp.dest("dist/assets/img"));
});

gulp.task("build", function(callback) {
    runSequence("clean", "build-sprite", "landing-css", "landing-img", callback);
});
