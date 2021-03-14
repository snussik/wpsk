'use strict';

let gulp         = require('gulp'),
	rename       = require('gulp-rename'),
	notify       = require('gulp-notify'),
	autoprefixer = require('gulp-autoprefixer'),
	sass         = require('gulp-sass'),
	minify       = require('gulp-minify'),
	uglify       = require('gulp-uglify'),
	plumber      = require('gulp-plumber' );

gulp.task('jet-reviews-public-css', () => {
	return gulp.src('./assets/scss/jet-reviews.scss')
		.pipe(
			plumber( {
				errorHandler: function ( error ) {
					console.log('=================ERROR=================');
					console.log(error.message);
					this.emit( 'end' );
				}
			})
		)
		.pipe(sass( { outputStyle: 'compressed' } ))
		.pipe(autoprefixer({
				browsers: ['last 10 versions'],
				cascade: false
		}))

		.pipe(rename('jet-reviews.css'))
		.pipe(gulp.dest('./assets/css/'))
		.pipe(notify('Compile Sass Done!'));
});

gulp.task('jet-reviews-admin-css', () => {
	return gulp.src('./assets/scss/admin.scss')
		.pipe(
			plumber( {
				errorHandler: function ( error ) {
					console.log('=================ERROR=================');
					console.log(error.message);
					this.emit( 'end' );
				}
			})
		)
		.pipe(sass( { outputStyle: 'compressed' } ))
		.pipe(autoprefixer({
				browsers: ['last 10 versions'],
				cascade: false
		}))

		.pipe(rename('admin.css'))
		.pipe(gulp.dest('./assets/css/'))
		.pipe(notify('Compile Sass Done!'));
});

//watch
gulp.task( 'watch', () => {
	gulp.watch( './assets/scss/**', gulp.series( 'jet-reviews-public-css' ) );
	gulp.watch( './assets/scss/**', gulp.series( 'jet-reviews-admin-css' ) );
});


