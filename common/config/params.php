<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    'distribution'      => true,#是否开启分销
    'pageSize'          =>21,
    'backendPayUser' => true,//后台开启给用户充值功能
    'appName'           =>'亲友棋牌游戏',//APP名称
    'startTime'         =>'2017-01-01 00:00:00',//时间组件开始时间
//    'ApiUserPay'        => 'http://120.77.155.126:4002',
    'ApiUserPay'        => 'http://120.25.205.109:8013',//游戏服务器地址
    'manyPay'           => true,//多货币开始、关闭状态
];
