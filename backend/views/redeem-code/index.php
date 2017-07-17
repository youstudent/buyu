<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */
$this->title = Yii::t('app','redeem_list').'-'.Yii::$app->params['appName'];
use yii\bootstrap\ActiveForm;
?>
<section id="content">
    <section class="vbox">
        <section class="scrollable padder">
<!--            面包屑开始           -->
            <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="<?=\yii\helpers\Url::to(['site/index'])?>"><i class="fa fa-home"></i>首页</a></li>
                <li><a href="#">兑换码管理</a></li>
                <li class="active">兑换码列表</li>
            </ul>
<!--            面包屑结束            -->
            <section class="panel panel-default">
<!--                搜索开始          -->
                <div class="row text-sm wrapper">
                    <?php $form = ActiveForm::begin([
                            'action'=>['redeem-code/index'],
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
                                <?=$form->field($model,'select')
                                        ->dropDownList([
                                        "redeem_code"=>Yii::t('app','redeem_code_select_search_nickname')])?>
                            </div>

                            <div class="form-group">
                                <div class="input-group">
                                    <?php echo $form->field($model,'keyword')->textInput(['class'=>'form-control','placeholder'=>Yii::t('app','search_input')])?>
                                    <span class="input-group-btn">
                                         <button class="btn btn-default" type="submit"><i class="fa fa-search"></i>&nbsp;<?=Yii::t('app','search')?></button>
                                    </span>
                                </div>
                            </div>
                            <a href="<?= \yii\helpers\Url::to(['redeem-code/add']) ?>" class="btn btn-primary"
                               data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i>添加一次使用兑换码</a>
                            <a href="<?= \yii\helpers\Url::to(['redeem-code/add-one']) ?>" class="btn btn-primary"
                               data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i>添加无限制次数</a>
                            <a href="<?= \yii\helpers\Url::to(['redeem-code/export']) ?>" class="btn btn-primary"
                               data-toggle="modal"><i class="gglyphicon glyphicon-download-alt"></i>导出兑换码</a>
                        </div>
                    <?php ActiveForm::end()?>
                </div>
                <div class="col-sm-9">
                    <!--筛选状态 全部|正常|封停 开始-->
                    <div class="btn-group" data-toggle="buttons" style="margin-right: 8px;">
                        <label class="btn btn-default <?php if (Yii::$app->request->get('show') == '') {
                            echo "active";
                        } ?>" onclick="setStatus('')">
                            <input type="radio" name="options" id="statusAll">全部</label>
                        <label class="btn btn-default <?php if (Yii::$app->request->get('show') == 1) {
                            echo "active";
                        } ?> " onclick="setStatus(1)">
                            <input type="radio" name="options" id="statusOk">一次</label>
                        <label class="btn btn-default <?php if (Yii::$app->request->get('show') == 2) {
                            echo "active";
                        } ?> " onclick="setStatus(2)">
                            <input type="radio" name="options" id="statusColose">无限制</label>
                    </div>
                    <input type="hidden" name="Agency[searchstatus]" value="" id="status">
                    <!--筛选状态 全部|正常|封停 结束-->
                </div>
<!--                搜索结束          -->
<!--                表格开始          -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" >
                        <thead>
                            <tr style="border-top: 1px solid #ebebeb;border-bottom: 1px solid #ebebeb">
                                <th  class="text-center">编号</th>
                                <th  class="text-center">礼包等级</th>
                                <th  class="text-center">礼包类型</th>
                                <th  class="text-center">赠送范围</th>
                                <th  class="text-center">名称</th>
                                <th  class="text-center">兑换码</th>
                                <th  class="text-center">创建时间</th>
                                <th  class="text-center">开始时间</th>
                                <th  class="text-center">结束时间</th>
                                <th  class="text-center">描述</th>
                                <th  class="text-center">状态</th>
                                <th  class="text-center">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1;?>
                        <?php foreach ($data as $key => $value):?>
                            <tr>
                                <td  class="text-center"><?=$i?></td>
                                <td  class="text-center"><?=\common\models\RedeemCode::$type[$value['type']]?></td>
                                <td  class="text-center"><?=\common\models\RedeemCode::$add_type[$value['add_type']]?></td>
                                <td  class="text-center"><?=\common\models\RedeemCode::$scope_type[$value['scope_type']]?></td>
                                <td  class="text-center"><?=$value['name']?></td>
                                <td  class="text-center"><?=$value['redeem_code']?></td>
                                <td  class="text-center"><?=date('Y-m-d H:i:s',$value['created_at'])?></td>
                                <td  class="text-center"><?=$value['start_time']?></td>
                                <td  class="text-center"><?=$value['end_time']?></td>
                                <td  class="text-center"><?=$value['description']?></td>
                                <td  class="text-center">
                                    <?php if ($value['status']==0):?>
                                        <span class="badge bg-success">未使用</span>
                                    <?php elseif($value['status']==1):?>
                                        <span class="badge bg-danger">已使用</span>
                                    <?php else:?>
                                        <span class="badge bg-danger">无限制</span>
                                    <?php endif;?>
                                </td>
                                <td class="text-center" width="200px;">
                                    <a href="<?php echo \yii\helpers\Url::to(['redeem-code/prize', 'id' => $value['id']]) ?>"
                                       data-toggle="modal" data-target="#myModal" class="btn btn-xs btn-primary">查看奖品</a>
                                    <?php if ($value['status']==0 || $value['status']==2):?>
                                        <a onclick="return openAgency(this,'是否修改该兑换码?')"
                                           href="<?php echo \yii\helpers\Url::to(['redeem-code/del', 'id' => $value['id']]) ?>"
                                           class="btn btn-xs btn-danger">编 辑</a>
                                    <?php endif;?>
                                    <?php if ($value['status']!==1):?>
                                        <a onclick="return openAgency(this,'是否删除该兑换码?')"
                                           href="<?php echo \yii\helpers\Url::to(['redeem-code/del', 'id' => $value['id']]) ?>"
                                           class="btn btn-xs btn-danger">删 除</a>
                                    <?php endif;?>
                                    
                                </td>
                            </tr>
                        <?php $i++?>
                        <?php endforeach;?>
                        </tbody>
                    </table>
                    <?php if(empty($data)):?>
                        <div class="text-center m-t-lg clearfix wrapper-lg animated fadeInRightBig" id="galleryLoading">
                            <h1><i class="fa fa-warning" style="color: red;font-size: 40px"></i></h1>
                            <h4 class="text-muted">对不起、未能找到"<?=$model->keyword?>"相关的任何数据</h4>
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
        window.location = '<?php echo \yii\helpers\Url::to(['redeem-code/index','show'=>''],true)?>' + val;
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