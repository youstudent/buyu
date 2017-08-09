<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */
$this->title = Yii::t('app', 'agency_index') . '-' . Yii::$app->params['appName'];
use yii\bootstrap\ActiveForm;

?>
<section id="content">
    <section class="vbox">
        <section class="scrollable padder">
            <!--            面包屑开始           -->
            <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="<?= \yii\helpers\Url::to(['site/index']) ?>"><i class="fa fa-home"></i>首页</a></li>
                <li><a href="#">族长管理</a></li>
                <li class="active">族长列表</li>
            </ul>
            <!--            面包屑结束            -->
            <section class="panel panel-default">
                <div class="panel-heading">
                    <!--                搜索开始          -->
                    <div class="row text-sm wrapper">
                        <div class="col-sm-9">
                            <?php $form = ActiveForm::begin([
                                'id' => 'agencyForm',
                                'action' => ['agency/index'],
                                'method' => 'get',
                                'fieldConfig' => [
                                    'template' => "{input}",
                                ],
                            ]) ?>
                            <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>"
                                   value="<?= Yii::$app->request->getCsrfToken() ?>">

                            <div class="form-inline">
                                <div class="form-group">

                                    <!--筛选状态 全部|正常|封停 开始-->
                                    <div class="btn-group" data-toggle="buttons" style="margin-right: 8px;">
                                        <label class="btn btn-default <?php if ($model->searchstatus == '') {
                                            echo "active";
                                        } ?>" onclick="setStatus()">
                                            <input type="radio" name="options"
                                                   id="statusAll"><?= Yii::t('app', 'agency_select_search_all') ?>
                                        </label>
                                        <label class="btn btn-default <?php if ($model->searchstatus == 1) {
                                            echo "active";
                                        } ?>" onclick="setStatus(1)">
                                            <input type="radio" name="options"
                                                   id="statusOk"><?= Yii::t('app', 'agency_select_search_ok') ?></label>
                                        <label class="btn btn-default <?php if ($model->searchstatus == 2) {
                                            echo "active";
                                        } ?>" onclick="setStatus(2)">
                                            <input type="radio" name="options"
                                                   id="statusColose"><?= Yii::t('app', 'agency_select_search_colse') ?>
                                        </label>
                                        <label class="btn btn-default <?php if ($model->searchstatus == 3) {
                                            echo "active";
                                        } ?>" onclick="setStatus(3)">
                                            <input type="radio" name="options"
                                                   id="statusColose"><?= Yii::t('app', 'agency_select_search_audit') ?>
                                        </label>
                                        <label class="btn btn-default <?php if ($model->searchstatus == 4) {
                                            echo "active";
                                        } ?>" onclick="setStatus(4)">
                                            <input type="radio" name="options"
                                                   id="statusColose"><?= Yii::t('app', '拒绝申请') ?></label>
                                    </div>
                                    <input type="hidden" name="Agency[searchstatus]" value="<?= $model->searchstatus ?>"
                                           id="status">
                                    <!--筛选状态 全部|正常|封停 结束-->

                                    <div class="form-group">
                                        <div class="controls">
                                            <div id="reportrange" class="pull-left dateRange form-control">
                                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                                <span id="searchDateRange"></span>
                                                <b class="caret"></b>
                                            </div>
                                        </div>
                                    </div>

                                    <?= $form->field($model,'starttime')->hiddenInput(['id'=>'startTime'])?>
                                    <?= $form->field($model,'endtime')->hiddenInput(['id'=>'endTime'])?>

                                    <?= $form->field($model, 'select')
                                        ->dropDownList(["1" => Yii::t('app', 'user_select_search_all'),
                                            "name" => Yii::t('app', 'agency_select_search_game'),
                                            "phone" => Yii::t('app', 'agency_select_search_phone'),
                                            "identity" => Yii::t('app', 'agency_select_search_identity')]) ?>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">

                                        <?php echo $form->field($model, 'keyword')->textInput(['class' => 'form-control', 'placeholder' => Yii::t('app', 'search_input')]) ?>
                                        <span class="input-group-btn">
                                             <button class="btn btn-default" type="submit"><i class="fa fa-search"></i>&nbsp;<?= Yii::t('app', 'search') ?>
                                             </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <?php ActiveForm::end() ?>
                        </div>
                        <div class="col-sm-3 text-right">
                            <a href="<?= \yii\helpers\Url::to(['agency/add-new']) ?>" class="btn btn-primary"
                               data-toggle="modal" data-target="#myModal"><i
                                    class="fa fa-plus"></i>&nbsp;<?php echo Yii::t('app', 'agency_add_but') ?></a>
                        </div>
                    </div>
                    <!--                搜索结束          -->
                </div>
                <div class="panel-body">
                <!--                表格开始          -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" style="border: 0px;">
                        <thead>
                        <tr>
                            <th class="text-center" style="border-left: 0px;">编号</th>
                            <th class="text-center">手机号</th>
                            <th class="text-center">家族名称</th>
                            <th class="text-center">族长名字</th>
                            <th class="text-center">金币</th>
                            <th class="text-center">钻石</th>
                            <th class="text-center">鱼币</th>
                            <th class="text-center">组员人数</th>
                            <th class="text-center">保险箱总金币</th>
                            <th class="text-center">保险箱总钻石</th>
                            <th class="text-center">注册时间</th>

<!--                            多货币修改代码-->
                           <!-- --><?php
/*                                $item = \common\models\GoldConfigObject::find()->all();
                                foreach ($item as $key=>$value){
                                   echo "<th class=\"text-center\">".$value['name']."</th>";
                                  
                                }
                            */?>
<!--                            多货币修改代码-->

                            <th class="text-center">身份证号</th>
                            
                            <th class="text-center">状态</th>
                            <th class="text-center" style="border-right: 0px;">操作</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php $i = 1; ?>
                        <?php foreach ($data as $key => $value): ?>
                            <tr>
                                <td class="text-center" style="border-left: 0px;"><?= $i ?></td>
                               <!-- <?php /*if (Yii::$app->params['distribution']): */?>
                                    <td class="text-center"><?/*= $value['pid'] */?></td>
                                   
                                --><?php /*endif; */?>
                                <td class="text-center"><?= $value['phone'] ?></td>
                                <td class="text-center"><?= $value['name'] ?></td>
                                <td class="text-center"><?= $value['family_name'] ?></td>
                                <td class="text-center"><?= $value['gold']?></td>
                                <td class="text-center"><?= $value['diamond']?></td>
                                <td class="text-center"><?= $value['fishGold']?></td>
                                <td class="text-center"><?= $value['number_num']?></td>
                                <td class="text-center"><?= $value['gold_all']?></td>
                                <td class="text-center"><?= $value['diamond_all']?></td>
                                <td class="text-center"><?= date("Y-m-d H:i:s", $value['reg_time']) ?></td>
<!--                                多货币修改-->
                                <?php /*foreach ($value['gold'] as $keys=>$values):*/?><!--
                                   
                                   <td class="text-center"><?/*= $values */?></td>;
                                   
                                --><?php /*endforeach;*/?>
<!--                                多货币修改-->
                                <td class="text-center"><?= $value['identity'] ?></td>
                                
                                <td class="text-center">
                                    <?php if ($value['status'] == 1): ?>
                                        <span class="label bg-primary"><?= Yii::t('app', 'agency_normal') ?></span>
                                    <?php elseif ($value['status'] == 2): ?>
                                        <span class="label bg-danger"><?= Yii::t('app', 'agency_stop') ?></span>
                                    <?php elseif ($value['status'] == 3): ?>
                                        <span class="label bg-warning"><?= Yii::t('app', 'agency_audit') ?></span>
                                    <?php elseif ($value['status'] == 4): ?>
                                        <span class="label bg-danger"><?= Yii::t('app', '拒绝') ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center" style="width: 400px;border-right: 0px;">
                                    <?php if ($value['status'] == 1): ?>

                                        <a href="<?php echo \yii\helpers\Url::to(['agency/pay', 'id' => $value['id']]) ?>"
                                           data-toggle="modal" data-target="#myModal"
                                           class="btn btn-xs btn-success">&nbsp;充 值</a>
                                        <a href="<?php echo \yii\helpers\Url::to(['agency/deduct', 'id' => $value['id']]) ?>"
                                           data-toggle="modal" data-target="#myModal"
                                           class="btn btn-xs btn-info">&nbsp;扣&nbsp;除&nbsp;</a>
                                        <a href="<?=\yii\helpers\Url::to(['pay/agency-pay-log',
                                            'Agency'=>['select'=>'id','keyword'=>$value['id']]])?>" class="btn btn-xs btn-primary">充值记录</a>
                                        <a href="<?=\yii\helpers\Url::to(['pay/agency-out-log',
                                            'Agency'=>['select'=>'id','keyword'=>$value['id']]])?>" class="btn btn-xs btn-primary">扣除记录</a>

                                        <a onclick="return openAgency(this,'是否封锁该账号?')"
                                           href="<?php echo \yii\helpers\Url::to(['agency/status', 'id' => $value['id']]) ?>"
                                           class="btn btn-xs btn-danger">&nbsp;封&nbsp;号&nbsp;</a>
                                        
                                        
                                        <a href="<?php echo \yii\helpers\Url::to(['agency/edit', 'id' => $value['id']]) ?>"
                                           data-toggle="modal" data-target="#myModal"
                                           class="btn btn-xs btn-success">&nbsp;编 辑&nbsp;</a>
                                        
                                        <a href="<?php echo \yii\helpers\Url::to(['agency/edit', 'id' => $value['id']]) ?>"
                                           data-toggle="modal" data-target="#myModal"
                                           class="btn btn-xs btn-success">&nbsp;族员&nbsp;</a>
                                    <?php elseif ($value['status'] == 2 || $value['status'] == 4): ?>

                                        <a onclick="return openAgency(this,'是否开启账号?')"
                                           href="<?php echo \yii\helpers\Url::to(['agency/status', 'id' => $value['id']]) ?>"
                                           class="btn btn-xs btn-success">&nbsp;开&nbsp;启&nbsp;</a>

                                    <?php elseif ($value['status'] == 3): ?>

                                        <a onclick="return openAgency(this,'是否允许通过?')"
                                           href="<?php echo \yii\helpers\Url::to(['agency/audit', 'id' => $value['id'], 'audit' => 'yes']) ?>"
                                           class="btn btn-xs btn-success">&nbsp;通&nbsp;过&nbsp;</a>

                                        <a onclick="return openAgency(this,'是否拒绝通过?')"
                                           href="<?php echo \yii\helpers\Url::to(['agency/audit', 'id' => $value['id'], 'audit' => 'no']) ?>"
                                           class="btn btn-xs btn-danger">&nbsp;拒&nbsp;绝&nbsp;</a>

                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php $i++ ?>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php if(empty($data)):?>
                        <div class="text-center m-t-lg clearfix wrapper-lg animated fadeInRightBig" id="galleryLoading">
                            <h1><i class="fa fa-warning" style="color: red;font-size: 40px"></i></h1>
                            <h4 class="text-muted"><?php echo sprintf(Yii::t('app','search_null'),$model->keyword)?></h4>
                            <p class="m-t-lg"></p>
                        </div>
                    <?php endif;?>
                </div>
                <!--                表格结束          -->
                </div>
                <!--                分页开始          -->
                <footer class="panel-footer">
                    <div class="row">
                        <div class="col-sm-12 text-right text-center-xs">
                            <?= \yii\widgets\LinkPager::widget([
                                'pagination' => $pages,
                                'options' => [
                                    'class' => 'pagination pagination-sm m-t-none m-b-none',
                                ]
                            ]) ?>
                        </div>
                    </div>
                </footer>
                <!--                分页结束          -->
            </section>
        </section>
    </section>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"></div>
    <a href="" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a>
</section>
<script>

    //    设置封停的状态
    function setStatus(val) {
        $("#status").val(val);
        $("#agencyForm").submit();
        console.log($("#status").val());
    }
    function openAgency(_this, title) {
        swal({
                title: title,
                text: "请确认你的操作时经过再三是考虑!",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "确认",
                cancelButtonText: "取消",
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            },
            function () {
                console.log(_this.href);
                $.ajax({
                    url: _this.href,
                    success: function (res) {
                        if (res.code == 1) {
                            swal(
                                {
                                    title: res.message,
                                    type: "success",
                                    showCancelButton: false,
                                    confirmButtonText: "确认",
                                    closeOnConfirm: false,
                                    showLoaderOnConfirm: true
                                }, function () {
                                    window.location.reload();
                                }
                            );
                        } else {
                            swal(
                                {
                                    title: res.message,
                                    type: "error",
                                    showCancelButton: false,
                                    confirmButtonText: "确认",
                                    closeOnConfirm: false,
                                }
                            );
                        }
                    }
                });
            });

        return false;
    }
</script>