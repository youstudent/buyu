<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */
$this->title = Yii::t('app', 'day_task_index') . '-' . Yii::$app->params['appName'];
?>
<section id="content">
    <section class="vbox">
        <section class="scrollable padder">
            <!--            面包屑开始           -->
            <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="/site/index"><i class="fa fa-home"></i>首页</a></li>
                <li><a href="#">基础任务捕鱼任务管理</a></li>
                <li class="active">任务列表</li>
            </ul>
            <!--            面包屑结束            -->
            <section class="panel panel-default">
                <div class="panel-heading">
                    <!--                搜索开始          -->
                    <div class="row text-sm wrapper">
                        <div class="col-sm-9">
                            <!--筛选状态 全部|正常|封停 结束-->
                        </div>
                        <div class="col-sm-3 text-right">
                        
                        </div>
                    </div>
                    <!--                搜索结束          -->
                </div>
                <div class="panel-body">
                    <!--                表格开始          -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" style="border: 0px">
                            <thead>
                            <tr>
                                <th class="text-center" style="border-left: 0px;">编号</th>
                                <th class="text-center">任务名字</th>
                                <th class="text-center">修改时间</th>
                                <th class="text-center">状态</th>
                                <th class="text-center" style="border-right: 0px;">操作</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php $i = 1; ?>
                            <?php foreach ($data as $key => $value): ?>
                                <tr>
                                    <td class="text-center" style="border-left: 0px;"><?= $i ?></td>
                                    <td class="text-center"><?= $value['name'] ?></td>
                                    <?php if (!empty($value['updated_at'])):?>
                                    <td class="text-center"><?= date("Y-m-d H:i:s", $value['updated_at']) ?></td>
                                    <?php else:?>
                                    <td class="text-center"><?= $value['updated_at'] ?></td>
                                    <?php endif;?>
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
                                    <td class="text-center" style="width: 300px;">
                                        <?php if ($value['status']==0):?>
                                            <a onclick="return openAgency(this,'是否开启该入口?')"
                                               href="<?php echo \yii\helpers\Url::to(['inle-porting/status', 'id' => $value['id'],'status'=>1]) ?>"
                                               class="btn btn-xs btn-success">开 启&nbsp;</a>
                                        <?php else:?>
                                            <a onclick="return openAgency(this,'是否关闭该入口?')"
                                               href="<?php echo \yii\helpers\Url::to(['inle-porting/status', 'id' => $value['id'],'status'=>0]) ?>"
                                               class="btn btn-xs btn-info">&nbsp;关 闭</a>
                                        <?php endif;?>
                                        <a href="<?php echo \yii\helpers\Url::to(['touch/edit', 'id' => $value['id']]) ?>"
                                           data-toggle="modal" data-target="#myModal" class="btn btn-xs btn-primary">查看任务</a>
                                        <a href="<?php echo \yii\helpers\Url::to(['touch/edit', 'id' => $value['id']]) ?>"
                                           data-toggle="modal" data-target="#myModal" class="btn btn-xs btn-primary">查看奖励</a>
                                        <a href="<?php echo \yii\helpers\Url::to(['day-task/land', 'id' => $value['id']]) ?>"
                                           data-toggle="modal" data-target="#myModal" class="btn btn-xs btn-primary">编辑</a>
                                    </td>
                                </tr>
                                <?php $i++ ?>
                            <?php endforeach; ?>
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