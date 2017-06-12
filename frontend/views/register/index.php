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
    <title>代理注册</title>
    <meta name="description" content="app, web app, responsive, admin dashboard, admin, flat, flat ui, ui kit, off screen nav" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel="stylesheet" href="/css/app.v2.css" type="text/css" />
    <link rel="stylesheet" href="/css/font.css" type="text/css" cache="false" />
    <link rel="stylesheet" href="/js/select2/select2.css" type="text/css" cache="false" />
    <link rel="stylesheet" href="/js/select2/theme.css" type="text/css" cache="false" />
    <link rel="stylesheet" href="/js/fuelux/fuelux.css" type="text/css" cache="false" />
    <link rel="stylesheet" href="/js/datepicker/datepicker.css" type="text/css" cache="false" />
    <link rel="stylesheet" href="/js/slider/slider.css" type="text/css" cache="false" />
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

                <section class="panel panel-default" style="background-color: #ffffff">
                    <header class="panel-heading font-bold" style="background-color: #ffffff">代理注册操作</header>
                    <div class="panel-body">
                        <div class="col-sm-1"></div>
                        <div class="col-sm-10">
                            <?php $form = \yii\bootstrap\ActiveForm::begin([
                                'action'=>['register/index'],
                                'options'=>['class'=>'form-horizontal'],
                                'fieldConfig' => [
                                    'template' => "{label}<div class=\"col-lg-9\">{input}<span class=\"help-block m-b-none\">{error}</span></div>",
                                    'labelOptions'  => ['class'=>'col-sm-2 control-label'],
                                ],
                            ])?>
                                <?=$form->field($model,'phone')->textInput()?>
                                <?=$form->field($model,'name')->textInput()?>
                                <?=$form->field($model,'identity')->textInput()?>
                                <?=$form->field($model,'password')->passwordInput()?>
                                <?=$form->field($model,'reppassword')->passwordInput()?>
                                <?=$form->field($model,'recode')->textInput()?>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="agency-password"></label>
                                <div class="col-lg-9"><?=\yii\bootstrap\Html::submitButton('提交注册申请',['class'=>'btn btn btn-primary'])?><span class="help-block m-b-none"></span></div>
                            </div>

                            <?php \yii\bootstrap\ActiveForm::end()?>
                        </div>
                        <div class="col-sm-1"></div>
                    </div>
                </section>
            </div>
            <div class="col-sm-3"></div>
    </section>
</section>
<script src="js/app.v2.js"></script>
</body>

</html>
