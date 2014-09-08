<?php
// This is global bootstrap for autoloading
putenv('APP_ENV=testing');

//\App::call('php artisan migrate --env=testing --database=testing');


exec('rm ' . __DIR__ . '/../../database/bbms_testing.sqlite');
exec('cp ' . __DIR__ . '/../../database/bbms_testing_clean.sqlite ' . __DIR__ .'/../../database/bbms_testing.sqlite');
