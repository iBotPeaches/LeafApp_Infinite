<?php

namespace Deployer;

require 'recipe/laravel.php';
require 'contrib/php-fpm.php';
require 'contrib/yarn.php';

set('application', 'Leafapp');
set('repository', 'git@github.com:iBotPeaches/LeafApp_Infinite.git');
set('php_fpm_version', '8.1');

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
    'artisan:config:cache',
    'artisan:migrate',
    'yarn:install',
    'yarn:run:prod',
    'deploy:publish',
    'php-fpm:reload',
    'artisan:queue:restart',
]);

task('yarn:run:prod', function () {
    cd('{{release_or_current_path}}');
    run('yarn run prod');
});

after('deploy:failed', 'deploy:unlock');
