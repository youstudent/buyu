<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */
$this->title = Yii::t('app','family_list').'-'.Yii::$app->params['appName'];
use yii\bootstrap\ActiveForm;
?>
<section id="content">
    <section class="vbox">
        <section class="scrollable padder">
<!--            面包屑开始           -->
            <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="<?=\yii\helpers\Url::to(['site/index'])?>"><i class="fa fa-home"></i>首页</a></li>
                <li><a href="#">族员列表管理</a></li>
                <li class="active">族员列表</li>
            </ul>
<!--            面包屑结束            -->
            <section class="panel panel-default">
<!--                搜索开始          -->
                <div class="row text-sm wrapper">
                    <?php $form = ActiveForm::begin([
                            'action'=>['family/get-son'],
                            'method'=>'get',
                            'id'    =>'lr_form',
                            'fieldConfig' => [
                                'template' => "{input}",
                            ],
                    ])?>
                        <input type="hidden" name="<?= Yii::$app->request->csrfParam?>" value="<?=Yii::$app->request->getCsrfToken()?>">
                        <div class="form-inline" style="margin-left: 20px; margin-right: 20px;">

                            <div class="form-group">
                                <div class="controls">
                                    <div id="reportrange" class="pull-left dateRange form-control">
                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                        <span   id="searchDateRange" ></span>
                                        <b class="caret"></b>
                                    </div>
                                </div>
                            </div>
                            <?= $form->field($model,'starttime')->hiddenInput(['id'=>'startTime'])?>
                            <?= $form->field($model,'endtime')->hiddenInput(['id'=>'endTime'])?>

                           

                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-btn">
                                         <button class="btn btn-default" type="submit"><i class="fa fa-search"></i>&nbsp;<?=Yii::t('app','搜索')?></button>
                                    </span>&nbsp;
                                    <a href="<?php echo \yii\helpers\Url::to(['family/dissolve']) ?>"
                                       data-toggle="modal" data-target="#myModal" class="btn btn-danger btn-primary">申请解散家族</a>
                                </div>
                            </div>
                        </div>
                    <?php ActiveForm::end()?>
                </div>
<!--                搜索结束          -->
<!--                表格开始          -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" >
                        <thead>
                            <tr style="border-top: 1px solid #ebebeb;border-bottom: 1px solid #ebebeb">
                                <th  class="text-center">编号</th>
                                <th  class="text-center">昵称</th>
                                <th  class="text-center">金币</th>
                                <th  class="text-center">钻石</th>
                                <th  class="text-center">总上分金币</th>
                                <th  class="text-center">总上分宝石</th>
                                <th  class="text-center">总下分金币</th>
                                <th  class="text-center">总下分宝石</th>
                                <th  class="text-center">保险箱金币</th>
                                <th  class="text-center">保险箱钻石</th>
                                <th  class="text-center">保险箱宝石</th>
                                <th  class="text-center">职位</th>
                                <th  class="text-center">状态</th>
                                <th  class="text-center">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1;?>
                        <?php foreach ($data as $key => $value):?>
                            <tr>
                                <td  class="text-center"><?=$i?></td>
                                <td  class="text-center"><?=$value->son->name?></td>
                                <td  class="text-center"><?=$value->son->gold?></td>
                                <td  class="text-center"><?=$value->son->diamond?></td>
                                <td  class="text-center"><?=\common\models\Familyrecord::GameGold($value->playerid,6)?></td>
                                <td  class="text-center"><?=\common\models\Familyrecord::GameDiamond($value->playerid,6) ?></td>
                                <td  class="text-center"><?=\common\models\Familyrecord::GameGold($value->playerid,5) ?></td>
                                <td  class="text-center"><?=\common\models\Familyrecord::GameDiamond($value->playerid,5) ?></td>
                                <td  class="text-center"><?=$value->gold?></td>
                                <td  class="text-center"><?=$value->diamond?></td>
                                <td  class="text-center"><?=$value->fishgold?></td>
                                <td  class="text-center"><?=$value->position==9?'族长':'族员'?></td>
                                <td class="text-center">
                                    <?php if($value['status'] == 1):?>
                                    <a href="#" class="active">
                                        <?php else:?>
                                        <a href="#" class="">
                                            <?php endif;?>
                                            <i class="fa fa-check text-success text-active"></i>
                                            <i class="fa fa-times text-danger text"></i>
                                        </a>
                                </td>
                                <?php if (!$value->position==9):?>
                                <td  class="text-center">
                                    <a onclick="return openAgency(this,'踢出该玩家?')"
                                       href="<?php echo \yii\helpers\Url::to(['family/kick', 'id' => $value['id'],'status'=>3]) ?>"
                                       class="btn btn-xs btn-danger">踢出</a>
                                    <a href="<?=\yii\helpers\Url::to(['family/up-and-down','playerid'=>$value->son->id,'updown'=>1,'show'=>1])?>" class="btn btn-xs btn-success">上分记录</a>
                                    <a href="<?=\yii\helpers\Url::to(['family/up-and-down','playerid'=>$value->son->id,'updown'=>0,'show'=>0])?>" class="btn btn-xs btn-success">下分记录</a>
                                </td>
                                <?php else:?>
                                <td class="text-center">
                                    <button class="btn btn-xs">族长</button>
                                </td>
                                <?php endif;?>
                            </tr>
                        <?php $i++?>
                        <?php endforeach;?>
                        </tbody>
                    </table>
                    <?php if(empty($data)):?>
                        <div class="text-center m-t-lg clearfix wrapper-lg animated fadeInRightBig" id="galleryLoading">
                            <h1><i class="fa fa-warning" style="color: red;font-size: 40px"></i></h1>
                            <h4 class="text-muted">对不起、未能找到"<?=Yii::t('app','family_list')?>"相关的任何数据</h4>
                            <p class="m-t-lg"></p>
                        </div>
                    <?php endif;?>
                </div>
<!--                表格结束          -->
<!--                分页开始          -->
                <footer class="panel-footer">
                    <div class="row">
                        <div class="col-sm-12 text-right text-center-xs">
                            <?=\yii\widgets\LinkPager::widget([
                                    'pagination'=>$pages,
                                    'firstPageLabel' => '首页',
                                    'lastPageLabel' => '尾页',
                                    'nextPageLabel' => '下一页',
                                    'prevPageLabel' => '上一页',
                                    'options'   =>[
                                     'class'=>'pagination pagination-sm m-t-none m-b-none',
                                ]
                            ])?>
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