<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=192.168.2.117;dbname=game_2',
            'username' => 'root',
            'password' => '123456',
            'charset' => 'utf8',
            'tablePrefix' =>'g_',
        ],
       /* 'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=39.108.86.157;dbname=game_2',
            'username' => 'root',
            'password' => '90c0lv6hu4VFkmON',
            'charset' => 'utf8',
            'tablePrefix' =>'g_',
        ],*/
        'secondDb' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=39.108.86.157;dbname=fishing',
            'username' => 'back',
            'password' => '123456',
            'charset' => 'utf8',
            'tablePrefix' =>'',
        ],
        /*'commondb' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=192.168.2.140;dbname=fishing',
            'username' => 'root',
            'password' => '123456',
            'charset' => 'utf8',
            'tablePrefix' =>'',
        ],*/
       'commondb' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=192.168.2.117;dbname=fishing',
            'username' => 'root',
            'password' => '123456',
            'charset' => 'utf8',
            'tablePrefix' =>'',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
//            'urlSuffix'      =>'html',
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            //'hostname' => '192.168.2.235',
            'hostname' => '39.108.86.157',
            //'hostname' => '127.0.0.1',
            'port' => 6379,
            'database' => 0,
        ],
       
    ],
    'timeZone'=>'Asia/Chongqing',
];
