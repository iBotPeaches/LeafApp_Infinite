<?php

namespace Deployer;

require 'recipe/laravel.php';
require 'contrib/php-fpm.php';
require 'contrib/yarn.php';

set('application', 'Leafapp');
set('repository', 'git@github.com:iBotPeaches/LeafApp_Infinite.git');
set('php_fpm_service', 'ea-php82-php-fpm');
set('git_ssh_command', 'ssh -o StrictHostKeyChecking=no');
set('default_timeout', 1800);

host('prod')
    ->set('remote_user', 'leafapp')
    ->set('port', 22774)
    ->set('hostname', 'leafapp.co')
    ->set('deploy_path', '/home/leafapp/deploy');

task('deploy', [
    'deploy:prepare',
    'deploy:vendors',
    'artisan:storage:link',
    'artisan:view:cache',
    'artisan:migrate',
    'artisan:storage:link',
    'yarn:local:upload',
    'artisan:horizon:assets',
    'app:version:file',
    'app:sentry:version',
    'deploy:publish',
    'artisan:optimize',
    'php-fpm:reload',
    'artisan:horizon:terminate',
]);

task('yarn:local:upload', function () {
    upload('public/build', '{{release_or_current_path}}/public');
});

task('app:version:file', function () {
    upload('VERSION', '{{release_or_current_path}}/VERSION');
});

task('app:sentry:version', function () {
    cd('{{release_or_current_path}}');
    run("sed -i '/^SENTRY_LARAVEL_RELEASE/d' .env");
    run('echo -e "SENTRY_LARAVEL_RELEASE=$(cat VERSION)" >> .env');
});

task('app:sitemap', function () {
    cd('{{release_or_current_path}}');
    run('php artisan sitemap:generate');
});

task('artisan:horizon:assets', function () {
    cd('{{release_or_current_path}}');
    run('php artisan horizon:publish');
});

after('deploy:failed', 'deploy:unlock');
