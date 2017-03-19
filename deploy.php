<?php
namespace Deployer;
require 'recipe/laravel.php';

// Configuration

set('ssh_type', 'native');
// with true fail, see https://github.com/deployphp/deployer/issues/1031
set('ssh_multiplexing', false);

set('repository', 'git@github.com:pilot114/clicker.git');

set('composer_options', 'install --ignore-platform-reqs --verbose --prefer-dist --no-progress --no-interaction --no-dev --optimize-autoloader');

add('shared_files', []);
add('shared_dirs', []);

add('writable_dirs', []);

// Servers

server('production', 'wshell.ru')
    ->user('pilot114')
    ->identityFile('~/.ssh/deploy.pub', '~/.ssh/deploy')
    ->set('deploy_path', '/home/pilot114/clicker_dep');

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.
// before('deploy:symlink', 'artisan:migrate');
