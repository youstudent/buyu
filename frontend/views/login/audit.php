<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */?>
<!DOCTYPE html>
<html lang="en" class="app">

<head>
    <meta charset="utf-8" />
    <title>提示！</title>
    <meta name="description" content="app, web app, responsive, admin dashboard, admin, flat, flat ui, ui kit, off screen nav" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel="stylesheet" href="/css/app.v2.css" type="text/css" />
    <!--[if lt IE 9]> <script src="/js/ie/html5shiv.js" cache="false"></script> <script src="/js/ie/respond.min.js" cache="false"></script> <script src="/js/ie/excanvas.js" cache="false"></script> <![endif]-->
</head>

<body>
<section class="vbox">
    <header class="bg-dark dk header navbar navbar-fixed-top-xs">
        <div class="navbar-header aside-md">
            <a class="btn btn-link visible-xs" data-toggle="class:nav-off-screen" data-target="#nav"> <i class="fa fa-bars"></i> </a>
            <a href="#" class="navbar-brand" data-toggle="fullscreen"><img src="/images/logo.png" class="m-r-sm"><?=Yii::$app->params['appName']?></a>
            <a class="btn btn-link visible-xs" data-toggle="dropdown" data-target=".nav-user"> <i class="fa fa-cog"></i> </a>
        </div>

    </header>
    <section id="content">
            <div class="col-sm-3"></div>
            <div class="col-sm-6" style="padding-top: 30px;">

                <section class="panel panel-default text-center" style="background-color: #ffffff;padding: 80px; margin-top: 40px;">
                    <?php if(\Yii::$app->request->get('id') == 2):?>
                        <img src="/images/封锁.png" alt="" width="150px;">
                    <h2>你的账号：<?=Yii::$app->session->get('agencyName')?>,已经被封停!</h2>
                    <?php elseif(\Yii::$app->request->get('id') == 3):?>
                        <img src="/images/auditing.png" alt="" width="150px;">
                    <h2>你的账号：<?=Yii::$app->session->get('agencyName')?>,还在审核当中!</h2>
                    <?php elseif(\Yii::$app->request->get('id') == 4):?>
                        <img src="/images/封锁.png" alt="" width="150px;">
                        <h2>你的账号：<?=Yii::$app->session->get('agencyName')?>,审核被拒绝!</h2>
                    <?php endif;?>
            </div>
            <div class="col-sm-3"></div>
    </section>
</section>
</body>

</html>
