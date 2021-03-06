<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */
$this->title = Yii::t('app', 'config_index') . '-' . Yii::$app->params['appName'];
?>
<section id="content">
    <section class="vbox">
        <section class="scrollable padder">
            <!--            面包屑开始           -->
            <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="/site/index"><i class="fa fa-home"></i>首页</a></li>
                <li><a href="#">系统基础设置</a></li>
                <li class="active">系统基础设置列表</li>
            </ul>
            <!--            面包屑结束            -->
            <section class="panel panel-default">
                <div class="panel-heading">
                    <!--                搜索开始          -->
                    <div class="row text-sm wrapper">
                        <div class="col-sm-3 text-right">
                        </div>
                    </div>
                    <!--                搜索结束          -->
                </div>
                <div class="panel-body">
                    <!--                表格开始          -->
                    <div class="table-responsive" style="border-bottom: solid 1px #000;" >
                        <table class="table table-bordered table-hover" style="border: 0px; margin-bottom:100px;border-top: solid 1px #000;">
                            <thead>
                            <tr>
                                <th class="text-center" style="border-left: 0px;">编号</th>
                                <th class="text-center">刺激场</th>
                                <th class="text-center" style="border-right: 0px;">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 1; ?>
                                <tr>
                                    <td class="text-center" style="border-left: 0px;"><?= $i ?></td>
                                    <td class="text-center"><?=$data['cjuseable']==1?'开启':'关闭'?></td>
                                    <td class="text-center" style="width: 300px;">
                                        <?php if ($data['cjuseable']==1):?>
                                            <a onclick="return openAgency(this,'是否关闭该入口?')"
                                               href="<?php echo \yii\helpers\Url::to(['entryconfig/status','id'=>$data['id'],'type'=>1,'status'=>0]) ?>"
                                               class="btn btn-xs btn-danger">关闭</a>
                                        <?php else:?>
                                            <a onclick="return openAgency(this,'是否开启该入口?')"
                                               href="<?php echo \yii\helpers\Url::to(['entryconfig/status','id'=>$data['id'],'type'=>1,'status'=>1]) ?>"
                                               class="btn btn-xs btn-danger">开启</a>
                                        <?php endif;?>
                                </tr>
                                <?php $i++ ?>
                            </tbody>
                            <thead>
                            <tr>
                                <th class="text-center" style="border-left: 0px;">编号</th>
                                <th class="text-center">龙虎斗</th>
                                <th class="text-center" style="border-right: 0px;">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-center" style="border-left: 0px;"><?= $i ?></td>
                                <td class="text-center"><?=$data['lfduseable']==1?'开启':'关闭'?></td>
                                <td class="text-center" style="width: 300px;">
                                    <?php if ($data['lfduseable']==1):?>
                                        <a onclick="return openAgency(this,'是否关闭该入口?')"
                                           href="<?php echo \yii\helpers\Url::to(['entryconfig/status','id'=>$data['id'],'type'=>30,'status'=>0]) ?>"
                                           class="btn btn-xs btn-danger">关闭</a>
                                    <?php else:?>
                                        <a onclick="return openAgency(this,'是否开启该入口?')"
                                           href="<?php echo \yii\helpers\Url::to(['entryconfig/status','id'=>$data['id'],'type'=>30,'status'=>1]) ?>"
                                           class="btn btn-xs btn-danger">开启</a>
                                    <?php endif;?>
                            </tr>
                            </tbody>
                            <thead>
                            <tr>
                                <th class="text-center" style="border-left: 0px;">编号</th>
                                <th class="text-center">牛牛开关</th>
                                <th class="text-center" style="border-right: 0px;">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-center" style="border-left: 0px;"><?= $i ?></td>
                                <td class="text-center"><?=$data['nnuseable']==1?'开启':'关闭'?></td>
                                <td class="text-center" style="width: 300px;">
                                    <?php if ($data['nnuseable']==1):?>
                                        <a onclick="return openAgency(this,'是否关闭该入口?')"
                                           href="<?php echo \yii\helpers\Url::to(['entryconfig/status','id'=>$data['id'],'type'=>31,'status'=>0]) ?>"
                                           class="btn btn-xs btn-danger">关闭</a>
                                    <?php else:?>
                                        <a onclick="return openAgency(this,'是否开启该入口?')"
                                           href="<?php echo \yii\helpers\Url::to(['entryconfig/status','id'=>$data['id'],'type'=>31,'status'=>1]) ?>"
                                           class="btn btn-xs btn-danger">开启</a>
                                    <?php endif;?>
                            </tr>
                            </tbody>
                            <thead>
                            <tr>
                                <th class="text-center" style="border-left: 0px;">编号</th>
                                <th class="text-center">二人龙虎斗</th>
                                <th class="text-center" style="border-right: 0px;">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-center" style="border-left: 0px;"><?= $i ?></td>
                                <td class="text-center"><?=$data['erlfduseable']==1?'开启':'关闭'?></td>
                                <td class="text-center" style="width: 300px;">
                                    <?php if ($data['erlfduseable']==1):?>
                                        <a onclick="return openAgency(this,'是否关闭该入口?')"
                                           href="<?php echo \yii\helpers\Url::to(['entryconfig/status','id'=>$data['id'],'type'=>32,'status'=>0]) ?>"
                                           class="btn btn-xs btn-danger">关闭</a>
                                    <?php else:?>
                                        <a onclick="return openAgency(this,'是否开启该入口?')"
                                           href="<?php echo \yii\helpers\Url::to(['entryconfig/status','id'=>$data['id'],'type'=>32,'status'=>1]) ?>"
                                           class="btn btn-xs btn-danger">开启</a>
                                    <?php endif;?>
                            </tr>
                            </tbody>
                        </table>
                        <table class="table table-bordered table-hover" style="border: 0px; border-top: solid 1px #000;">
                            <thead>
                            <tr>
                                <th class="text-center" style="border-left: 0px;">编号</th>
                                <th class="text-center">鱼币场</th>
                                <th class="text-center" style="border-right: 0px;">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 1; ?>
                            <tr>
                                <td class="text-center" style="border-left: 0px;"><?= $i ?></td>
                                <td class="text-center"><?=$data['fishgolduseable']==1?'开启':'关闭'?></td>
                                <td class="text-center" style="width: 300px;">
                                    <?php if ($data['fishgolduseable']==1):?>
                                        <a onclick="return openAgency(this,'是否关闭该入口?')"
                                           href="<?php echo \yii\helpers\Url::to(['entryconfig/status','id'=>$data['id'],'type'=>2,'status'=>0]) ?>"
                                           class="btn btn-xs btn-danger">关闭</a>
                                    <?php else:?>
                                        <a onclick="return openAgency(this,'是否开启该入口?')"
                                           href="<?php echo \yii\helpers\Url::to(['entryconfig/status','id'=>$data['id'],'type'=>2,'status'=>1]) ?>"
                                           class="btn btn-xs btn-danger">开启</a>
                                    <?php endif;?>
                                    
                                </td>
                            </tr>
                            <?php $i++ ?>
                            </tbody>
                        </table>
                        <?php if(empty($data)):?>
                            <div class="text-center m-t-lg clearfix wrapper-lg animated fadeInRightBig" id="galleryLoading">
                                <h1><i class="fa fa-warning" style="color: red;font-size: 40px"></i></h1>
                                <h4 class="text-muted"><?php echo sprintf(Yii::t('app','search_null'),'聊天管理')?></h4>
                                <p class="m-t-lg"></p>
                            </div>
                        <?php endif;?>
                    </div>
                </div>
                <!--                表格结束          -->
                <!--                分页开始          -->
                <footer class="panel-footer">

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
        window.location = '<?php echo \yii\helpers\Url::to(['chat/index','show'=>''],true)?>' + val;
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