<?php
namespace Deployer;

require 'recipe/laravel.php';

set('application', 'Laravel App');
set('repository', 'git@github.com:your-username/your-repo.git');
set('branch', 'main');
set('deploy_path', '/app.reduanmasud.site');

host('104.156.254.130')
    ->set('remote_user', 'deployer')
    ->set('identity_file', './CICD/deployer_key');


// Laravel-specific settings
set('keep_releases', 5);

// Tasks
task('build', function () {
    run('cd {{release_path}} && npm install && npm run prod');
});

// Run database migrations
task('migrate', function () {
    run('cd {{release_path}} && php artisan migrate --force');
});

// Clear cache and optimize
task('optimize', function () {
    run('cd {{release_path}} && php artisan config:cache && php artisan route:cache && php artisan view:cache');
});

// After deploy tasks
after('deploy:failed', 'deploy:unlock');
after('deploy:symlink', 'migrate');
after('deploy:symlink', 'optimize');
after('deploy:symlink', 'build');

task('reload:php-fpm', function () {
    run('sudo systemctl reload php8.2-fpm');
});

after('deploy', 'reload:php-fpm');
