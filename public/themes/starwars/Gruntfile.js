module.exports = function(grunt) {


grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    concat: {
        options: {
            separator: ';'
        },
        dist: {
            src: ['../../bower_components/console-polyfill/index.js', '../../bower_components/jquery/dist/jquery.js', '../../bower_components/underscore/underscore.js', 'js/app.js'],
            dest: 'dist/js/app.dist.js'
        }
    },
    uglify: {
        options: {
            banner: '/*! <%= pkg.name %> <%= grunt.template.today("dd-mm-yyyy") %> */\n'
        },
        dist: {
            files: {
                'dist/js/app.dist.min.js': ['<%= concat.dist.dest %>']
            }
        }
    },
    qunit: {
        files: ['test/**/*.html']
    },
    jshint: {
        files: ['Gruntfile.js', 'js/**/*.js'],
        options: {
            // options here to override JSHint defaults
            globals: {
                jQuery: true,
                console: true,
                module: true,
                document: true
            }
        }
    },
    imagemin: {
        dynamic: { // Another target
            files: [{
                expand: true, // Enable dynamic expansion

                src: ['images/*.{png,jpg}'], // Actual patterns to match
                dest: 'dist/' // Destination path prefix
            }]
        }
    },

    copy: {
        main: {
            files: [
                {
                    expand: true,
                    cwd: '../../bower_components/bootstrap/dist/',
                    src: ['**/*'],
                    dest: 'dist/'
                }
            ],
        },
    },
    cssmin: {
        options: {
            shorthandCompacting: false,
            roundingPrecision: -1
        },
        main: {
            files: {
                'dist/css/styles.min.css': ['../../bower_components/bootstrap/dist/css/bootstrap.css', 'css/styles.css', 'css/typography.css']
            }
        }
    },
    clean: ["dist"],
    watch: {
        files: ['<%= jshint.files %>', 'css/*.css'],
        tasks: ['default']
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

grunt.registerTask('test', ['jshint']);

grunt.registerTask('default', ['jshint', 'clean', 'copy', 'concat', 'uglify', 'imagemin', 'cssmin']);

};
