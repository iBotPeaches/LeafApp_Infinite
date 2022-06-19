<?php

namespace Deployer;

require 'recipe/laravel.php';
require 'contrib/php-fpm.php';
require 'contrib/yarn.php';

set('application', 'Leafapp');
set('repository', 'git@github.com:iBotPeaches/LeafApp_Infinite.git');
set('php_fpm_service', 'ea-php81-php-fpm');
set('git_ssh_command', 'ssh -o StrictHostKeyChecking=no');

host('prod')
    ->set('remote_user', 'leafapp')
    ->set('port', 22774)
    ->set('hostname', 'leafapp.co')
    ->set('deploy_path', '/home/leafapp/deploy');

task('deploy', [
    'deploy:prepare',
    'artisan:optimize:clear',
    'deploy:vendors',
    'artisan:storage:link',
    'artisan:view:cache',
    'artisan:migrate',
    'artisan:storage:link',
    'yarn:install',
    'yarn:run:prod',
    'app:version:file',
    'deploy:publish',
    'artisan:optimize',
    'php-fpm:reload',
    'artisan:queue:restart',
    'app:sitemap',
]);

task('yarn:run:prod', function () {
    cd('{{release_or_current_path}}');
    run('yarn run prod');
});

task('app:version:file', function () {
    upload('VERSION', '{{release_or_current_path}}/VERSION');
});

task('app:sitemap', function () {
    artisan('sitemap:generate');
});

after('deploy:failed', 'deploy:unlock');
