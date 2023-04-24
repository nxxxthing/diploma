<?php

namespace Deployer;

require 'recipe/laravel.php';
require 'deploy/recipe/rsync.php';

// prepare variables from environment CI_ENV
$CI_REPOSITORY_URL = getenv('CI_REPOSITORY_URL');
$CI_COMMIT_REF_NAME = getenv('CI_COMMIT_REF_NAME');
$BIN_PHP = getenv('BIN_PHP');
$BIN_COMPOSER = getenv('BIN_COMPOSER');
$DEPLOY_BASE_DIR = getenv('DEPLOY_BASE_DIR');
$DEPLOY_SERVER = getenv('DEPLOY_SERVER');
$DEPLOY_USER = getenv('DEPLOY_USER');
$SSH_PORT = getenv('SSH_PORT');
/*CI_ENV*/

set('rsync_src', __DIR__ . '/public');
set('rsync_dest', '{{release_path}}/public');

// Project name

set('application', 'Project name');

// Project repository
set('repository', $CI_REPOSITORY_URL);

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', false);
set('allow_anonymous_stats', false);
set('keep_releases', 1);

// Shared files/dirs between deploys
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server
add('writable_dirs', []);

//github token
set('github_oauth_token', '66ef4719f8d2c3894631d12cd9d71fab27300eae');
set('composer_options', 'install --verbose --prefer-dist --no-progress --no-interaction --optimize-autoloader');

set('rsync', [
    'exclude' => [],
    'exclude-file' => false,
    'include' => [],
    'include-file' => false,
    'filter' => [],
    'filter-file' => false,
    'filter-perdir' => false,
    'flags' => 'rz', // Recursive, with compress
    'options' => [],
    'timeout' => 60,
]);

// Hosts
host($DEPLOY_SERVER)
    ->set('branch', $CI_COMMIT_REF_NAME)
    ->set('deploy_path', $DEPLOY_BASE_DIR)
    ->stage($CI_COMMIT_REF_NAME)
    ->set('http_user', $DEPLOY_USER)
    ->user($DEPLOY_USER)
    ->port($SSH_PORT)
    ->set('bin/php', $BIN_PHP)
    ->set('bin/composer', $BIN_COMPOSER)
    ->addSshOption('UserKnownHostsFile', '/dev/null')
    ->addSshOption('StrictHostKeyChecking', 'no');

// Tasks
task('build', function () {
    run('cd {{release_path}} && build');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

before('deploy:symlink', 'artisan:migrate');

task('config:clear', function () {
    run('cd {{release_path}} && {{bin/php}} artisan config:clear');
});

task('cache:clear', function () {
    run('cd {{release_path}} && {{bin/php}} artisan cache:clear');
});

task('db:seed', function () {
    run('cd {{release_path}} && {{bin/php}} artisan db:seed');
});

task('fm:publish', function () use ($BIN_PHP) {
    run('cd {{release_path}} && ' . $BIN_PHP . ' artisan vendor:publish --tag=fm-assets');
});

after('artisan:migrate', 'db:seed');
after('db:seed', 'config:clear');
after('config:clear', 'cache:clear');
after('cache:clear', 'fm:publish');

task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'rsync',
    'deploy:shared',
    'deploy:vendors',
    'deploy:writable',
    'artisan:storage:link',
    'artisan:view:cache',
    'artisan:config:cache',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
]);
