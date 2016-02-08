module.exports = function(grunt) {


	grunt.initConfig({
		pkg : grunt.file.readJSON('package.json'),
		concat : {
			options : {
				separator : ';'
			},
			dist : {
				src : [ 'bower_components/console-polyfill/index.js', 
				        'bower_components/jquery/dist/jquery.js', 
				        'bower_components/picturefill/dist/picturefill.min.js', 
				        'bower_components/bootstrap-sass/assets/javascripts/bootstrap/modal.js', 
				        'bower_components/bootstrap-sass/assets/javascripts/bootstrap/modal.js', 
//				        'bower_components/zepto/zepto.js', 
				        'js/app.js' ],
				dest : 'dist/js/app.dist.js'
			}
		},
		uglify : {
			options : {
				banner : '/*! <%= pkg.name %> <%= grunt.template.today("dd-mm-yyyy") %> */\n'
			},
			dist : {
				files : {
					'dist/js/app.dist.min.js' : [ '<%= concat.dist.dest %>' ]
				}
			}
		},
		qunit : {
			files : [ 'test/**/*.html' ]
		},
		jshint : {
			files : [ 'Gruntfile.js', 'js/**/*.js' ],
			options : {
				// options here to override JSHint defaults
				globals : {
					jQuery : true,
					console : true,
					module : true,
					document : true
				}
			}
		},
		imagemin : {
			options : {
				optimizationLevel : 7
			},
			dynamic : { // Another target
				files : [ {
					expand : true, // Enable dynamic expansion
					src : [ 'images/*.{png,jpg}' ], // Actual patterns to match
					dest : 'dist/' // Destination path prefix
				} ]
			},
			all : { // Another target
				files : [ {
					expand : true,
					cwd : 'release/',
					src : [ '**/*.{png,jpg,gif}' ],
					dest : 'dist/'
				} ]
			}
		},
		responsive_images : {
			dev : {
				options : {
					engine : 'im',
					newFilesOnly : false,
					sizes : [ {
						name : "small",
						width : 120,
					}, {
						name : "medium",
						width : 240,
					}, {
						name : "large",
						width : 480,
					} ]
				},

				files : [ {
					expand : true,
					src : [ 'images/**/*.{jpg,gif,png}' ],
					cwd : './',
					dest : 'release/'
				} ]
			}
		},
		copy : {
			main : {
				files : [ {
					expand : true,
					cwd : 'bower_components/bootstrap/dist/',
					src : [ '**/*' ],
					dest : 'dist/'
				} ],
			},
		},
		cssmin : {
			//			options : {
			//				shorthandCompacting : true,
			//				roundingPrecision : -1
			//			},
			main : {
				files : {
					'dist/css/styles.min.css' : [ 'css/styles.css' ]
				}
			}
		},
		clean : {
			dist : [ "dist" ],
			release : [ "release" ]
		},
		watch : {
			js : {
				files : [ '<%= jshint.files %>' ],
				tasks : [ 'js' ]
			},
			css : {
				files : [ 'sass/*.*' ],
				tasks : [ 'css' ]
			},
			images : {
				files : [ 'images/*.*' ],
				tasks : [ 'images' ]
			}
		},
		sass : {
			dist : {
				options : {
					sourcemap : 'none'	
				},
				files : {
					'css/styles.css' : 'sass/styles.scss'
				}
			}
		}
	});

	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-jshint');
	grunt.loadNpmTasks('grunt-contrib-qunit');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-imagemin');
	grunt.loadNpmTasks('grunt-contrib-cssmin');
	grunt.loadNpmTasks('grunt-contrib-copy');
	grunt.loadNpmTasks('grunt-contrib-clean');
	grunt.loadNpmTasks('grunt-resize-crop');
	grunt.loadNpmTasks('grunt-responsive-images');
	grunt.loadNpmTasks('grunt-contrib-sass');

	grunt.registerTask('test', [ 'jshint' ]);

	grunt.registerTask('js', [ 'jshint', 'copy', 'concat', 'uglify' ]);
	
	grunt.registerTask('css', [ 'sass', 'cssmin' ]);
	grunt.registerTask('images', [ 'responsive_images', 'imagemin', 'clean:release' ]);

	grunt.registerTask('compile', [ 'js', 'css' ]);
	grunt.registerTask('default', [ 'clean', 'compile', 'images' ]);

};
