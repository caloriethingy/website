<?php
namespace Deployer;

require 'recipe/yii.php';

// Config
set('repository', 'git@github.com:cgsmith/calorie.git');

add('shared_files', [
    //'yii',
    'common/config/main-local.php',
    'common/config/params-local.php',
    'frontend/config/main-local.php',
    'frontend/config/params-local.php',
]);
add('shared_dirs', [
    'frontend/web/uploads',
]);
add('writable_dirs', []);

// Hosts
host('calorie')
    ->set('remote_user', 'chris')
    ->set('deploy_path', '/var/www/calorie')
    ->set('environment', 'Production')
    ->setLabels([
        'env' => 'prod',
    ]);
host('test.calorie')
    ->set('composer_options', '--verbose --prefer-dist --no-progress --no-interaction')
    ->set('remote_user', 'chris')
    ->set('deploy_path', '/var/www/test.calorie')
    ->set('environment', 'Testing')
    ->setLabels([
        'env' => 'test',
    ]);

// Tasks
task('init-app', function () {
    run('cd {{release_or_current_path}} && {{bin/php}} init --env={{environment}} --overwrite=n');
});

desc('Restart yii queue workers');
task('yii:queue:restart', function () {
    run('systemctl restart calorie-queue@*');
});

task('deploy:prod', function() {
    invoke('yii:queue:restart');
})->select('env=prod');


// Hooks
after('deploy:vendors', 'init-app');
after('deploy:failed', 'deploy:unlock');
after('deploy', 'deploy:prod');
