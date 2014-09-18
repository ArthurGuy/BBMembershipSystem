<?php
// This is global bootstrap for autoloading
putenv('APP_ENV=testing');

//Delete the test db and replace it with a known quantity
exec('rm ' . __DIR__ . '/../../database/bbms_testing.sqlite');
exec('cp ' . __DIR__ . '/../../database/bbms_testing_clean.sqlite ' . __DIR__ .'/../../database/bbms_testing.sqlite');
