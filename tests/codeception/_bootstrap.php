<?php
// This is global bootstrap for autoloading
//putenv('APP_ENV=testing');
//putenv('DB_DATABASE=bbms_acceptance_testing');
//Dotenv::load(__DIR__.'/../', '.testing.env');

/*
//Delete the test db and replace it with a known quantity
exec('rm ' . __DIR__ . '/../../database/bbms_testing_master.sqlite');
exec('rm ' . __DIR__ . '/../../database/bbms_testing.sqlite');
exec('cp ' . __DIR__ . '/../../database/bbms_testing_clean.sqlite ' . __DIR__ .'/../../database/bbms_testing_master.sqlite');


//Create a backup of the db to use - doesnt contain a lock
$command1 = 'sqlite3 ' . __DIR__ . '/../../database/bbms_testing_master.sqlite';
$command2 = '.backup ' . __DIR__ . '/../../database/bbms_testing.sqlite';
exec($command1.' "'.$command2.'"');
*/