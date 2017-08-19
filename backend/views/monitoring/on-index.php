<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */
$this->title = Yii::t('app','users_list').'-'.Yii::$app->params['appName'];
use yii\bootstrap\ActiveForm;
?>
<section id="content">
    <section class="vbox">
        <section class="scrollable padder">
<!--            面包屑开始           -->
            <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li style="font-size: 20px"><a href="<?=\yii\helpers\Url::to(['site/index'])?>"><i class="fa fa-home"></i>首页</a></li>
                <li class="active" style="color: red;font-size: 20px">监控列表 :: 搜索用户请按键盘ctrl+F</li>
            </ul>
                    <section class="panel panel-default">
                    <!--                搜索开始          -->
<!--                搜索结束          -->
<!--                表格开始          -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr style="border-top: 1px solid #ebebeb;border-bottom: 1px solid #ebebeb">
                                <th class="text-center">编号</th>
                                <th class="text-center">房间号</th>
                                <th class="text-center">人数</th>
                                <th class="text-center">房间命中率</th>
                                <th class="text-center">用户名</th>
                                <th class="text-center">用户ID</th>
                                <th class="text-center">实时金币数量</th>
                                <th class="text-center">实时钻石数量</th>
                                <th class="text-center">实时宝石数量</th>
                                <th class="text-center">命中率</th>
                                <th class="text-center">金币预警值</th>
                                <th class="text-center">宝石预警值</th>
                                <th class="text-center">状态</th>
                                <th class="text-center">操作</th>
                            </tr>
                        </thead>
                        <tbody id="data-table">
                       
                        </tbody>
                        
                    </table>
                </div>
                <footer class="panel-footer">
                
                </footer>
        </section>
    </section>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"></div>
    <a href="" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a>
</section>
<script>
   /* $.ajax({
            url:'/monitoring/new',
            type:'html',
            success:function(res){
                $('#tb').remove();
                var h = $('#data-table');
                h.append(res);
            },
        }
    );*/



    window.setInterval(function(){
        $.ajax({
                url:'/monitoring/on-new',
                type:'html',
                success:function(res){
                   // $('#tb').remove();
                   // var h = $('#data-table');
                   // h.append(res);
                    $('#data-table').html(res)
                },
            }
        );
    },2000);
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