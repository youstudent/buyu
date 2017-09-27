<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    'distribution'      => true,#是否开启分销
    'pageSize'          =>15,
    'backendPayUser' => true,//后台开启给用户充值功能
    'appName'           =>'猎宝寻鱼',//APP名称
    'startTime'         =>'2017-01-01 00:00:00',//时间组件开始时间
//    'ApiUserPay'        => 'http://120.77.155.126:4002',
    'ApiUserPay'        => 'http://120.25.205.109:8013',//游戏服务器地址
    'manyPay'           => true,//多货币开始、关闭状态
    'Api'               => 'http://192.168.2.235:8080/gameserver',//捕鱼游戏后台的数据
    //'Api'               => 'http://192.168.2.140:8080/gameserver',//捕鱼游戏后台的数据
    'Api'               => 'http://39.108.86.157:80',//捕鱼游戏后台的数据
    'redis'               => '192.168.2.235'//redis的地址
];
