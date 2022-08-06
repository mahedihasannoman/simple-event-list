module.exports = function( grunt ) {

	'use strict';

	// Project configuration
	grunt.initConfig( {

		pkg: grunt.file.readJSON( 'package.json' ),

		addtextdomain: {
			options: {
				textdomain: 'simple-event-list',
			},
			update_all_domains: {
				options: {
					updateDomains: true
				},
				src: [ '*.php', '**/*.php', '!\.git/**/*', '!bin/**/*', '!node_modules/**/*', '!tests/**/*' ]
			}
		},

		/*
         * CSS minify
         * Compress and Minify CSS files
         * Ref. https://github.com/gruntjs/grunt-contrib-cssmin
         */
        cssmin: {
            minify: {
                expand: true,
                cwd: 'assets/css/',
                src: [ '*.css', '!*.min.css'],
                dest: 'assets/css/',
                ext: '.min.css'
            }
        },

		wp_readme_to_markdown: {
			your_target: {
				files: {
					'README.md': 'readme.txt'
				}
			},
		},

		makepot: {
			target: {
				options: {
					domainPath: '/i18n/languages',
					exclude: [ '\.git/*', 'bin/*', 'node_modules/*', 'tests/*' ],
					mainFile: 'simple-event-list.php',
					potFilename: 'simple-event-list.pot',
					potHeaders: {
						poedit: true,
						'x-poedit-keywordslist': true
					},
					type: 'wp-plugin',
					updateTimestamp: true
				}
			}
		},

		// Clean up build directory
        clean: {
            main: ['build/']
        },

        // Copy the plugin into the build directory
        copy: {
            main: {
                src: [
                    '**',
                    '!node_modules/**',
                    '!build/**',
                    '!bin/**',
                    '!.git/**',
                    '!Gruntfile.js',
                    '!package.json',
                    '!package-lock.json',
                    '!phpcs.ruleset.xml',
					'!readme.txt',
					'!README.md',
                    '!phpunit.xml.dist',
                    '!webpack.config.js',
                    '!tmp/**',
                    '!views/assets/src/**',
                    '!src/**',
                    '!debug.log',
                    '!phpunit.xml',
                    '!export.sh',
                    '!.gitignore',
                    '!.env',
                    '!.gitmodules',
                    '!codeception.yml',
                    '!npm-debug.log',
                    '!plugin-deploy.sh',
                    '!readme.md',
                    '!composer.json',
                    '!composer.lock',
                    '!prev.json',
                    '!secret.json',
                    '!assets/src/**',
                    '!assets/less/**',
                    '!tests/**',
                    '!**/Gruntfile.js',
                    '!**/package.json',
                    '!**/customs.json',
                    '!nbproject',
                    '!phpcs.xml.dist',
                    '!phpcs-report.txt',
                    '!**/*~',
                    '!.eslintrc.js',
                    '!.editorconfig',
                    '!babel.config.js',
                    '!composer.phar',
                ],
                dest: 'build/'
            }
        },

		compress: {
			main: {
			  options: {
				archive: 'simple-event-list.zip'
			  },
			  files: [{
				expand: true,
				cwd: 'build/',
				src: ['**/*'],
				dest: '/'
			  }]
			}
		  },

		run: {
            options: {},

            removeDev:{
                cmd: 'composer',
                args: ['install', '--no-dev']
            },

            dumpautoload:{
                cmd: 'composer',
                args: ['dumpautoload', '-o']
            },

            composerInstall:{
                cmd: 'composer',
                args: ['install']
            },
        }

	} );

	grunt.loadNpmTasks( 'grunt-contrib-compress' );
	grunt.loadNpmTasks( 'grunt-contrib-clean' );
    grunt.loadNpmTasks( 'grunt-contrib-copy' );
	grunt.loadNpmTasks( 'grunt-run' );
	grunt.loadNpmTasks( 'grunt-contrib-cssmin' );
	grunt.loadNpmTasks( 'grunt-wp-i18n' );
	grunt.loadNpmTasks( 'grunt-wp-readme-to-markdown' );
	grunt.registerTask( 'default', [ 'i18n', 'cssmin', 'readme' ] );
	grunt.registerTask( 'i18n', ['addtextdomain', 'makepot'] );
	grunt.registerTask( 'readme', ['wp_readme_to_markdown'] );

	grunt.registerTask( 'release', [
		'default',
        'clean',
        'run:removeDev',
        'run:dumpautoload',
        'copy',
        'compress',
        'run:composerInstall',
        'run:dumpautoload',
		'clean',
    ])

};
