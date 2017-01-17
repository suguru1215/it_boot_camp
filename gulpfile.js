var gulp = require("gulp");
var uglify = require("gulp-uglify");
var concat = require("gulp-concat");
var rename = require("gulp-rename");
var plumber = require("gulp-plumber");
var cssmin = require("gulp-cssmin");
var htmlmin = require("gulp-minify-html");

// jsファイルをまとめる
gulp.task("js.concat", function () {
    return gulp.src(["./public/js/pc/app/*.js", "!./public/js/pc/app/main.js"])
        .pipe(plumber())
        .pipe(concat("main.js"))
        .pipe(gulp.dest("./public/js/pc/app"));
});

// jsファイルをminify
gulp.task("js.uglify", ["js.concat"], function () {
    return gulp.src("./public/js/pc/app/main.js")
        .pipe(plumber())
        .pipe(uglify({
            // ! から始まるコメントを残すオプションを追加
            preserveComments: "some"
        }))
        // 出力するファイル名を変更
        .pipe(rename("main.min.js"))
        .pipe(gulp.dest("./public/js/pc/min"));
});

// cssファイルをminify
gulp.task("cssmin", function () {
    return gulp.src("./public/css/pc/app/*.css")
        .pipe(cssmin())
        // 出力するファイル名を変更
        .pipe(rename("main.min.css"))
        .pipe(gulp.dest("./public/css/pc/min/"));
});

// html(twig)ファイルを圧縮用にコピー
gulp.task("html.copy", function() {
    return gulp.src("./resources/views/**/*.twig")
        .pipe(gulp.dest("./resources/views_min/"));
});

// html(twig)ファイルをminify
gulp.task("html.htmlmin", ["html.copy"], function () {
    return gulp.src("./resources/views_min/**/*.twig")
        .pipe(htmlmin({empty: true}))
        .pipe(gulp.dest("./resources/views_min/"));
});

gulp.task("js", ["js.concat", "js.uglify"]);
gulp.task("css", ["cssmin"]);
gulp.task("html", ["html.copy", "html.htmlmin"]);

gulp.task("default", function () {
    gulp.watch(["./public/js/pc/app/*.js"], ["js"]);
    gulp.watch(["./public/css/pc/app/*.css"], ["css"]);
    gulp.watch(["./resources/views/**/*.twig"], ["html"]);
});
