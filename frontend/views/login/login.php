<?php use yii\bootstrap\ActiveForm;?>
<!DOCTYPE html>
<html lang="en" class="bg-dark">
<head>
    <meta charset="utf-8" />
    <title>代理登录界面</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel="stylesheet" href="/css/app.v2.css" type="text/css" />
    <link rel="stylesheet" href="/css/font.css" type="text/css" cache="false" />
    <!--[if lt IE 9]> <script src="/js/ie/html5shiv.js" cache="false"></script> <script src="/js/ie/respond.min.js" cache="false"></script> <script src="/js/ie/excanvas.js" cache="false"></script> <![endif]-->
</head>
<body>
<section id="content" class="m-t-lg wrapper-md animated fadeInUp" style="margin-top: 100px;">
    <div class="container aside-xxl"> <a class="navbar-brand block" href="<?=\yii\helpers\Url::to(['login/login'])?>"><?=Yii::$app->params['appName']?>代理后台登录</a>
        <section class="panel panel-default bg-white m-t-lg">
            <header class="panel-heading text-center" style="padding: 10px;"> <strong>用户登录</strong> </header>

            <?php $form = ActiveForm::begin([
                'options' => ['class' => 'panel-body wrapper-lg'],
                'fieldConfig' => [
                    'template' => "{label}{input}{error}",
                    'labelOptions' => ['class' => 'control-label'],
                ],
            ])?>
                <div class="form-group">
                    <?php echo $form->field($model,'phone')->textInput(['class'=>'form-control input-lg','placeholder'=>"手机号"]);?>
                </div>
                <div class="form-group">
                    <?php echo $form->field($model,'password')->passwordInput(['class'=>'form-control input-lg','placeholder'=>"用户密码"]);?>
                </div>
                <div class="checkbox">

                </div>
                <?= \yii\helpers\Html::submitButton("登&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;录",['class'=>'btn btn-info btn-lg btn-block'])?>
            <!--<a href="<?/*= \yii\helpers\Url::to(['register/index'])*/?>" class="btn btn-default btn-lg btn-block">注册账号</a>-->
<!--                <button type="submit" class="btn btn-primary btn-lg btn-block">Sign in</button>-->
            <?php ActiveForm::end()?>

        </section>
    </div>
</section>
<!-- footer -->
<!-- / footer --> <script src="/js/app.v2.js"></script> <!-- Bootstrap --> <!-- App -->
</body>
</html>
