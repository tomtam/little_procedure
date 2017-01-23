<?php
/**
 * Created by PhpStorm.
 * User: Jenner
 * Date: 2015/8/5
 * Time: 21:23
 */

date_default_timezone_set('PRC');
define('DS', DIRECTORY_SEPARATOR);
//require dirname(dirname(__FILE__)) . DS . 'vendor' . DS . 'autoload.php';
require dirname(__FILE__).'/../../../..'. DS . 'vendor' . DS . 'autoload.php';

error_reporting(E_ALL);


$missions = [
    [
        'name' => 'ls1',
        'cmd' => "date ",
        'out' => 'file:///tmp/php_crontab.log',
        'err' => 'file:///tmp/php_crontab.log',
        'time' => '*/3 * * * *',
        'user' => 'www',
        'group' => 'www'
    ],
    [
        'name' => 'ls',
        'cmd' => "date ",
        'out' => 'file:///tmp/php_crontab.log',
        'err' => 'file:///tmp/php_crontab.log',
        'time' => '*/2 * * * *',
        'user' => 'www',
        'group' => 'www'
    ],
    [
        'name' => 'ls2',
        'cmd' => "date ",
        'out' => 'file:///tmp/php_crontab.log',
        'err' => 'file:///tmp/php_crontab.log',
        'time' => '*/1 * * * *',
        'user' => 'www',
        'group' => 'www'
	],
/*
    [
        'name' => 'hostname',
        'cmd' => "hostname",
        'out' => 'unix:///tmp/php_crontab.sock',
        'time' =>  '* * * * *',
    ],
*/
];

$daemon = new \Jenner\Crontab\Daemon($missions);
$daemon->start();
