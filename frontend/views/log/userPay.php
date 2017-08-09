<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */
$this->title = "用户充值记录-".\Yii::$app->params['appName'];
use yii\bootstrap\ActiveForm;
?>
<section id="content">
    <section class="vbox">
        <section class="scrollable padder">
            <!--            面包屑开始           -->
            <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="<?=\yii\helpers\Url::to(['site/index'])?>"><i class="fa fa-home"></i>首页</a></li>
                <li class="active">用户充值记录</li>
            </ul>
            <!--            面包屑结束            -->
            <section class="panel panel-default">
                <div class="panel-heading" style="padding: 0px; background-color: #ffffff">
                    <!--                搜索开始          -->
                    <div class="row text-sm wrapper">
                        <div class="col-sm-9">
                            <div class="form-inline">
                                <div class="form-group">
                                    <form action="<?=\yii\helpers\Url::to(['log/user-pay'])?>" method="get" id="lr_form">
                                        <div class="form-group">
                                            <div class="controls">
                                                <div id="reportrange" class="pull-left dateRange form-control">
                                                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                                    <span id="searchDateRange"></span>
                                                    <b class="caret"></b>
                                                </div>
                                            </div>
                                        </div>
                                        <input class="form-control" name="startTime" value="<?=$startTime?>"  size="16" type="hidden" id="startTime">
                                        <input class="form-control" name="endTime" value="<?=$endTime?>" type="hidden" id="endTime">
                                        <input class="form-control" name="game_id" type="text" id="game_id" style="width: 120px" placeholder="玩家ID或昵称">
                                        <input type="submit" value="查询" class="btn btn-default">
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3 text-right">

                        </div>
                    </div>
                    <!--                搜索结束          -->
                </div>
                <div class="panel-body" style="padding: 0px;">
                    <!--                表格开始          -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" style="border: 0px solid #f1f1f1 ; border-bottom: 1px solid #f1f1f1" >
                            <thead>
                            <tr>
                                <th  class="text-center" style="border-left: 0px;">编号</th>
                                <th  class="text-center">玩家昵称</th>
                                <th  class="text-center">玩家ID</th>
                                <th  class="text-center">类型</th>
                                <th  class="text-center">数量</th>
                                <th  class="text-center">时间</th>
                                <!--<th  class="text-center">收款人民币</th>-->
                            </tr>
                            </thead>
                            <tbody>

                            <?php $i = 1;?>
                            <?php foreach ($data as $key => $value):?>
                                <tr>
                                    <td  class="text-center" style="border-left: 0px;"><?=$i?></td>
                                    <td  class="text-center"><?=$value['nickname']?></td>
                                    <td  class="text-center"><?=$value['game_id']?></td>
                                    <td  class="text-center"><?=$value['gold_config']?></td>
                                    <td  class="text-center"><?=$value['gold']?></td>
                                    <td  class="text-center"><?=date("Y-m-d H:i:s",$value['time'])?></td>
                                   <!-- <td  class="text-center"><?/*=$value['money']*/?></td>-->
                                </tr>
                                <?php $i++?>
                            <?php endforeach;?>
                            </tbody>
                        </table>
                        <!--                        未找到任何数据显示-->
                        <?php if(empty($data)):?>
                            <div class="text-center m-t-lg clearfix wrapper-lg animated fadeInRightBig" id="galleryLoading">
                                <h1><i class="fa fa-warning" style="color: red;font-size: 40px"></i></h1>
                                <h4 class="text-muted">非常抱歉,我们未能找到"用户充值记录"相关的任何数据!</h4>
                                <p class="m-t-lg"></p>
                            </div>
                        <?php endif;?>
                        <!--                        为找到任何数据显示结束-->
                    </div>
                    <!--                表格结束          -->
                </div>
                <!--                分页开始          -->
                <footer class="panel-footer">
                    <div class="row">
                        <div class="col-sm-12 text-right text-center-xs">
                            <?=\yii\widgets\LinkPager::widget([
                                'pagination'=>$pages,
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
    function openAgency(_this,title) {
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
            function(){
                console.log(_this.href);
                $.ajax({
                    url:_this.href,
                    success:function (res) {
                        if(res.code == 1)
                        {
                            swal(
                                {
                                    title: res.message,
                                    type: "success",
                                    showCancelButton: false,
                                    confirmButtonText: "确认",
                                    closeOnConfirm: false,
                                    showLoaderOnConfirm: true
                                },function () {
                                    window.location.reload();
                                }
                            );
                        }else{
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