<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */
$this->title = Yii::t('app','users_blacklist').'-'.Yii::$app->params['appName'];
use yii\bootstrap\ActiveForm;
?>
<section id="content">
    <section class="vbox">
        <section class="scrollable padder">
<!--            面包屑开始           -->
            <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="<?=\yii\helpers\Url::to(['site/index'])?>"><i class="fa fa-home"></i>首页</a></li>
                <li><a href="#">用户管理</a></li>
                <li class="active">黑名单列表</li>
            </ul>
<!--            面包屑结束            -->
            <section class="panel panel-default">
<!--                搜索开始          -->
                <div class="row text-sm wrapper">
                    <?php $form = ActiveForm::begin([
                            'action'=>['users/blacklist'],
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
                                        ->dropDownList(["1"=>Yii::t('app','user_select_search_all'),
                                                        "game_id"=>Yii::t('app','user_select_search_game'),
                                                        "nickname"=>Yii::t('app','user_select_search_nickname')])?>
                            </div>

                            <div class="form-group">
                                <div class="input-group">
                                    <?php echo $form->field($model,'keyword')->textInput(['class'=>'form-control','placeholder'=>Yii::t('app','search_input')])?>
                                    <span class="input-group-btn">
                                         <button class="btn btn-default" type="submit"><i class="fa fa-search"></i>&nbsp;<?=Yii::t('app','search')?></button>
                                    </span>
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
                                <th  class="text-center">用户ID</th>
                                <th  class="text-center">用户昵称</th>
                                <th  class="text-center">金币</th>
                                <th  class="text-center">钻石</th>
                                <th  class="text-center">宝石</th>
                                <th  class="text-center">注册时间</th>
                                <th  class="text-center">操作</th>

                            </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1;?>
                        <?php foreach ($data as $key => $value):?>
                            <tr>
                                <td  class="text-center"><?=$i?></td>
                                <td  class="text-center"><?=$value['playerId']?></td>
                                <td  class="text-center"><?=$value->users->name?></td>
                                <td  class="text-center"><?=$value->users->gold?></td>
                                <td  class="text-center"><?=$value->users->diamond?></td>
                                <td  class="text-center"><?=$value->users->fishGold?></td>
                                <td  class="text-center"><?=$value->users->createdtime?></td>
                                <td class="text-center" width="300px;">
                                    <a onclick="return openAgency(this,'是否将该账号取消黑名单?')"
                                       href="<?php echo \yii\helpers\Url::to(['users/black', 'id' => $value['playerId'],'status'=>2]) ?>"
                                       class="btn btn-xs btn-danger">取消黑名单</a>
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