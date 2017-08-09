<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */
/*$item = \common\models\GoldConfigObject::find()->all();
                               foreach ($item as $key=>$value){
                                   echo "<th class=\"text-center\">".$value['name']."</th>";
                               }*/

$this->title = Yii::t('app','users_list').'-'.Yii::$app->params['appName'];
use yii\bootstrap\ActiveForm;
/*<?php foreach ($value['gold'] as $keys=>$values):*/?><!--
    <td class="text-center"><?/*= $values */?></td>
--><?php /*endforeach;*/
?>
<section id="content">
    <section class="vbox">
        <section class="scrollable padder">
<!--            面包屑开始           -->
            <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="<?=\yii\helpers\Url::to(['site/index'])?>"><i class="fa fa-home"></i>首页</a></li>
                <li><a href="#">监控管理</a></li>
                <li class="active">监控列表</li>
            </ul>
<!--            面包屑结束            -->
            <section class="panel panel-default">
<!--                搜索开始          -->
                <div class="row text-sm wrapper">
                
                </div>
<!--                搜索结束          -->
<!--                表格开始          -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" >
                        <thead>
                            <tr style="border-top: 1px solid #ebebeb;border-bottom: 1px solid #ebebeb">
                                <th class="text-center">序号</th>
                                <th class="text-center">房间号</th>
                                <th class="text-center">人数</th>
                                <th class="text-center">倍率</th>
                                <th class="text-center">用户名</th>
                                <th class="text-center">用户ID</th>
                                <th class="text-center">实时金币数量</th>
                                <th class="text-center">实时钻石数量</th>
                                <th class="text-center">实时宝石数量</th>
                                <th class="text-center">登录时间</th>
                                <th class="text-center">命中率</th>
                                <th class="text-center">金币预警值</th>
                                <th class="text-center">宝石预警值</th>
                                <th class="text-center">状态</th>
                                <th class="text-center">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1;?>
                        <?php foreach ($data as $key => $value):?>
                            <tr>
                                <td  class="text-center"><?=$i?></td>
                                <td  class="text-center"><?=$value->room_id?></td>
                                <td  class="text-center"><?=$value->number_num?></td>
                                <td  class="text-center">20%</td>
                                <td  class="text-center"><?=$value->users->nickname?></td>
                                <td  class="text-center"><?=$value->users->game_id?></td>
                                <td  class="text-center"><?=$value->users->gold?></td>
                                <td  class="text-center"><?=$value->users->jewel?></td>
                                <td  class="text-center"><?=$value->users->gem?></td>
                                <td  class="text-center"><?=$value->login_time?></td>
                                <td  class="text-center">1%</td>
                                <td  class="text-center">2</td>
                                <td  class="text-center">3</td>
                                <td  class="text-center">正常</td>
                                <td  class="text-center">
                                    <a onclick="return openAgency(this,'是否将该账号掉线?')"
                                       href="<?php echo \yii\helpers\Url::to(['users/black']) ?>"
                                       class="btn btn-xs btn-danger">掉 线</a>
                                    <a onclick="return openAgency(this,'是否将该账号停封?')"
                                       href="<?php echo \yii\helpers\Url::to(['users/black']) ?>"
                                       class="btn btn-xs btn-primary">停 封</a>
                                    <a href="<?=\yii\helpers\Url::to(['monitoring/robot','room_id'=>$value->room_id,'game_id'=>$value->users->game_id])?>" class="btn btn-xs btn-primary">机器人</a>
                                    <a href="<?=\yii\helpers\Url::to(['users/out-log',
                                        'Users'=>['select'=>'game_id','keyword'=>$value->game_id]])?>" class="btn btn-xs btn-success">详情</a>
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