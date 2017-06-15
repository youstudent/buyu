<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */
$this->title = Yii::t('app', 'goods_index') . '-' . Yii::$app->params['appName'];
use yii\bootstrap\ActiveForm;

?>
<section id="content">
    <section class="vbox">
        <section class="scrollable padder">
            <!--            面包屑开始           -->
            <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="<?= \yii\helpers\Url::to(['site/index']) ?>"><i class="fa fa-home"></i>首页</a></li>
                <li><a href="#">商品管理</a></li>
                <li class="active">兑换列表</li>
            </ul>
            <!--            面包屑结束            -->
            <section class="panel panel-default">
                <div class="panel-heading">
                    <!--                搜索开始          -->
                    <div class="row text-sm wrapper">
                        <div class="col-sm-9">
                            <?php $form = ActiveForm::begin([
                                'id' => 'agencyForm',
                                'action' => ['goods/index'],
                                'method' => 'get',
                                'fieldConfig' => [
                                    'template' => "{input}",
                                ],
                            ]) ?>
                            <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>"
                                   value="<?= Yii::$app->request->getCsrfToken() ?>">

                            <div class="form-inline">

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


                                <div class="form-group">
                                    <?= $form->field($model, 'select')
                                        ->dropDownList(["1" => Yii::t('app', 'user_select_search_all'),
                                            "name" => Yii::t('app', 'agency_select_search_game'),
                                            "user_id" => Yii::t('app', 'agency_structure_name'),
                                          ]) ?>
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
                    </div>
                    <!--                搜索结束          -->
                </div>
                <div class="panel-body">
                    <!--                表格开始          -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" style="border: 0px;">
                            <thead>
                            <tr>
                                <th class="text-center" style="border-left: 0px;">ID</th>
                                <th class="text-center">玩家ID</th>
                                <th class="text-center">昵称</th>
                                <th class="text-center">手机号码</th>
                                <th class="text-center">兑换奖品</th>
                                <th class="text-center">提交时间</th>
                                <th class="text-center">处理时间</th>
                                <th class="text-center">备注</th>
                                <th class="text-center">状态</th>
                                <th class="text-center">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            
                            <?php foreach ($data as $key => $value): ?>
                                <tr>
                                    <td class="text-center"><?=$value['id']  ?></td>
                                    <td class="text-center"><?= $value['user_id'] ?></td>
                                    <td class="text-center"><?= $value['name'] ?></td>
                                    <td class="text-center"><?= $value['phone'] ?></td>
                                    <td class="text-center"><?= $value['exchange'] ?></td>
                                    <td class="text-center"><?= date("Y-m-d H:i:s", $value['created_at']) ?></td>
                                    <td class="text-center"><?= date("Y-m-d H:i:s", $value['updated_at']) ?></td>
                                    <td class="text-center"><?= $value['detail'] ?></td>
                                    <td class="text-center"><?= $value['status']==1?'未处理':'已处理'?></td>
                                    <td
                                    <td class="text-center" width="100px;">
                                        <a onclick="return openAgency(this,'是否处理该数据?')" href="<?=\yii\helpers\Url::to(['goods/status','id'=>$value['id']])?>" class="btn btn-xs btn-success">通 过</a>
                                        <a onclick="return openAgency(this,'是否处理该数据?')" href="<?=\yii\helpers\Url::to(['goods/status','id'=>$value['id']])?>" class="btn btn-xs btn-danger">拒 绝</a>
                                    </td>
                                </tr>
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
                                    closeOnConfirm: false
                                }
                            );
                        }
                    }
                });
            });

        return false;
    }
</script>