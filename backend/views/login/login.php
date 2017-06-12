<?php use yii\bootstrap\ActiveForm;?>
<!DOCTYPE html>
<html lang="en" class="bg-dark">
<head>
    <meta charset="utf-8" />
    <title><?php echo sprintf(Yii::t('app','login_form_title'),Yii::$app->params['appName']);?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel="stylesheet" href="/css/app.v2.css" type="text/css" />
    <link rel="stylesheet" href="/css/font.css" type="text/css" cache="false" />
    <!--[if lt IE 9]> <script src="/js/ie/html5shiv.js" cache="false"></script> <script src="/js/ie/respond.min.js" cache="false"></script> <script src="/js/ie/excanvas.js" cache="false"></script> <![endif]-->
</head>
<body>
<section id="content" class="m-t-lg wrapper-md animated fadeInUp" style="margin-top: 100px;">
    <div class="container aside-xxl"> <a class="navbar-brand block" href="<?=\yii\helpers\Url::to(['login/login'])?>"><?php echo sprintf(Yii::t('app','login_form_title'),Yii::$app->params['appName']);?></a>
        <section class="panel panel-default bg-white m-t-lg">
            <header class="panel-heading text-center" style="padding: 15px;background-color: #f5f5f5">
                <strong><?php echo sprintf(Yii::t('app','login_form_title'),Yii::$app->params['appName']);?></strong>
            </header>

            <?php $form = ActiveForm::begin([
                'options' => ['class' => 'panel-body wrapper-lg'],
                'fieldConfig' => [
                    'template' => "{label}{input}{error}",
                    'labelOptions' => ['class' => 'control-label'],
                ],
            ])?>
                <div class="form-group">
                    <?php echo $form->field($model,'name')->textInput(['class'=>'form-control input-lg','placeholder'=>Yii::t('app','login_input_name')]);?>
                </div>
                <div class="form-group">
                    <?php echo $form->field($model,'password')->passwordInput(['class'=>'form-control input-lg','placeholder'=>Yii::t('app','login_input_password')]);?>
                </div>
                <div class="checkbox">

                </div>
                <?= \yii\helpers\Html::submitButton(Yii::t('app','login_input_submit'),['class'=>'btn btn-primary btn-lg btn-block'])?>
            <?php ActiveForm::end()?>

        </section>
    </div>
</section>
<script src="/js/app.v2.js"></script>
</body>
</html>
