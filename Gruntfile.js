'use strict';

module.exports = function(grunt) {
    require('load-grunt-tasks')(grunt);
    require('time-grunt')(grunt);

    // Project configuration.
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        less: {
            dist: {
                options: {},
                files: {
                  'www/css/chivas.css': 'www/less/index.less'
                }
            },
            admin: {
                options: {},
                files: {
                  'www/css/admin.css': 'www/less/admin.less'
                }
            },
        },
        autoprefixer: {
            options: {
            },
            dist: {
                files: {
                    'www/css/chivas.css':'www/css/chivas.css',
                    'www/css/admin.css':'www/css/admin.css'
                }
            }
        },
        cssmin: {
            options: {
            },
            target: {
                files: {
                    'www/css/chivas.css':'www/css/chivas.css',
                    'www/css/admin.css':'www/css/admin.css'
                }
            }
        },
        concat: {
            front: {
                src: [
                    'bower_components/jquery/dist/jquery.js',
                    'bower_components/slick-carousel/slick/slick.js',
                    'bower_components/nette-forms/src/assets/netteForms.js',
                    'bower_components/slideout.js/dist/slideout.js',
                    'www/js/app.js'
                    ],
                dest: 'www/build/chivas.js'
            },
            admin: {
                src: [
                    'bower_components/jquery/dist/jquery.js',
                    'bower_components/bootstrap/dist/js/bootstrap.js',
                    'bower_components/nette-forms/src/assets/netteForms.js',
                    'bower_components/nette.ajax.js/nette.ajax.js',
                    'bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.js',
                    'vendor/ublaboo/datagrid/assets/dist/datagrid.js',
                    'vendor/ublaboo/datagrid/assets/dist/datagrid-instant-url-refresh.js',
                    'vendor/ublaboo/datagrid/assets/dist/datagrid-spinners.js',
                    'www/js/admin.js'
                ],
                dest: 'www/build/admin.js'
            },
        },
        uglify: {
            main: {
                files: {
                    'www/build/chivas.min.js': 'www/build/chivas.js',
                    'www/build/admin.min.js': 'www/build/admin.js',
                }
            }
		},
        watch: {
            less: {
                files: ['www/less/**/*.less'/*, 'css/**/],
                tasks: ['less']
            },
			js: {
                files: ['www/js/**/*.js'],
                tasks: ['concat']
			},
            livereload: {
                options: { livereload: true },
                files: [
                    '**/*.html',
                    'www/css/**/*.css',
                    'www/js/**/*.min.js',
                    'www/images/**/*'
                ]
            }
        }
    });

    grunt.registerTask('default', ['less', 'autoprefixer', /*'cssmin',*/, 'concat', 'uglify']);
    // grunt.registerTask('build', ['default']);
};
