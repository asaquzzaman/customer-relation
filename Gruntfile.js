'use strict';
module.exports = function(grunt) {
    var pkg = grunt.file.readJSON('package.json');
    var stringReplace = {
        download_link: {
            src: ['config/app.php'],             // source files array (supports minimatch)
            dest: 'config/app.php',             // destination directory or file
            replacements: [
                {
                    from: '{github-download-version}',                   // string replacement
                    to:'v' + pkg.version
                },
            ]
        }
    };
    grunt.initConfig({
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
                    '!phpunit.xml.dist',
                    '!webpack.config.js',
                    '!tmp/**',
                    '!assets/src/**',
                    '!debug.log',
                    '!phpunit.xml',
                    '!export.sh',
                    '!.gitignore',
                    '!.gitmodules',
                    '!npm-debug.log',
                    '!plugin-deploy.sh',
                    '!readme.md',
                    '!composer.json',
                    '!composer.lock',
                    '!composer.phar',
                    '!secret.json',
                    '!assets/less/**',
                    '!tests/**',
                    '!**/Gruntfile.js',
                    '!**/package.json',
                    '!**/README.md',
                    '!**/customs.json',
                    '!nbproject',
                    '!**/*~'
                ],
                dest: 'build/'
            }
        },

        //Compress build directory into <name>.zip and <name>-<version>.zip
        compress: {
            main: {
                options: {
                    mode: 'zip',
                    archive: './build/customer-relation-v' + pkg.version + '.zip'
                },
                expand: true,
                cwd: 'build/',
                src: ['**/*'],
                dest: 'customer-relation'
            }
        },

        addtextdomain: {
            options: {
                textdomain: 'customer-relation',
            },
            update_all_domains: {
                options: {
                    updateDomains: true
                },
                src: [ '*.php', '**/*.php', '!node_modules/**', '!php-tests/**', '!bin/**', '!build/**', '!vendor/**', '!assets/**', '!views/src/**' ]
            }
        },

        replace: stringReplace,

        run: {
            autoload:{
                cmd: 'composer',
                args: ['dumpautoload', '-o']
            },
        }
    });


    // Load NPM tasks to be used here
    grunt.loadNpmTasks( 'grunt-text-replace' );
    grunt.loadNpmTasks( 'grunt-contrib-clean' );
    grunt.loadNpmTasks( 'grunt-contrib-copy' );
    grunt.loadNpmTasks( 'grunt-contrib-compress' );
    grunt.loadNpmTasks( 'grunt-run' );

    grunt.registerTask( 'release', [
        'clean',
        'replace',
        'run',
        'copy',
        'compress'
    ]);
};
