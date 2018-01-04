<?php
/**
 * Created by PhpStorm.
 * User: matthieu
 * Date: 04/01/18
 * Time: 15:39
 */

$config = [
    'host' => '127.0.0.1',
    'db' => 'mydb',
    'user' => 'root',
    'password' => '',
    'charset' => 'utf8mb4'
    ];

$config['dsn'] = 'mysql:host='. $config['host'] .';dbname='.$config['db'].';charset='.$config['charset'];