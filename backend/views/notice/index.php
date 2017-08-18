<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */
$this->title = Yii::t('app', 'notice_index') . '-' . Yii::$app->params['appName'];
?>
<section id="content">
    <section class="vbox">
        <section class="scrollable padder">
            <!--            面包屑开始           -->
            <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="/site/index"><i class="fa fa-home"></i>首页</a></li>
                <li><a href="#">公告管理</a></li>
                <li class="active">公告管理详情</li>
            </ul>
            <!--            面包屑结束            -->
            <section class="panel panel-default">
                <div class="panel-heading">
                    <!--                搜索开始          -->
                    <div class="row text-sm wrapper">
                        <div class="col-sm-9">
                            <!--筛选状态 全部|正常|封停 开始-->
                            <div class="btn-group" data-toggle="buttons" style="margin-right: 8px;">
                                <label class="btn btn-default <?php if (Yii::$app->request->get('show') == 2) {
                                    echo "active";
                                } ?>" onclick="setStatus(2)">
                                    <input type="radio" name="options" id="statusAll">全部</label>
                                <label class="btn btn-default <?php if (Yii::$app->request->get('show') == 1) {
                                    echo "active";
                                } ?> " onclick="setStatus(1)">
                                    <input type="radio" name="options" id="statusOk">显示</label>
                                <label class="btn btn-default <?php if (Yii::$app->request->get('show') == 0) {
                                    echo "active";
                                } ?> " onclick="setStatus(0)">
                                    <input type="radio" name="options" id="statusColose">隐藏</label>
                            </div>
                            <input type="hidden" name="Agency[searchstatus]" value="" id="status">
                            <!--筛选状态 全部|正常|封停 结束-->
                            <a href="<?php echo \yii\helpers\Url::to(['notice/getnotice']) ?>"
                               onclick="return openAgency(this,'是否确认同步数据?')" class="btn btn-primary btn-info">一键同步数据</a>
                        </div>
                        <div class="col-sm-3 text-right">
                            <a href="<?= \yii\helpers\Url::to(['notice/add']) ?>" class="btn btn-primary"
                               data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i>&nbsp;添加新的公告</a>
                            
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
                                <th class="text-center">内容</th>
                                <th class="text-center">位置</th>
                                <th class="text-center">时间</th>
                                <th class="text-center">状态</th>
                                <th class="text-center" style="border-right: 0px;">操作</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php $i = 1; ?>
                            <?php foreach ($data as $key => $value): ?>
                                <tr>
                                    <td class="text-center" style="border-left: 0px;"><?= $i ?></td>
                                    <td class="text-center">
                                        <a href="<?php echo \yii\helpers\Url::to(['notice/content', 'id' => $value['id']]) ?>"
                                        data-toggle="modal" data-target="#myModal" class="btn btn-xs btn-info">查看内容</a>
                                    </td>
                                    <td class="text-center">
                                    <?php if ($value['location'] == 1): ?>
                                        <span class="text-center">登录公告</span>
                                    <?php elseif ($value['location'] == 2): ?>
                                        <span class="text-center">大厅公告</span>
                                    <?php else:?>
                                        <span class="text-center">滚动公告</span>
                                    <?php endif; ?>
                                    </td>
                                    <td class="text-center"><?= date("Y-m-d H:i:s", $value['time']) ?></td>
                                    <td class="text-center" style="border-right: 0px;">
                                        <?php if ($value['status'] == 1): ?>
                                            <span class="badge bg-success">显示</span>
                                        <?php elseif ($value['status'] == 0): ?>
                                            <span class="badge bg-danger">隐藏</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center" style="width: 300px;">
                                        <?php if ($value['location']==2):?>
                                        <a href="<?php echo \yii\helpers\Url::to(['notice/prize', 'id' => $value['id']]) ?>"
                                           data-toggle="modal" data-target="#myModal" class="btn btn-xs btn-primary">查看奖品</a>
                                        <?php endif?>
                                        <a href="<?php echo \yii\helpers\Url::to(['notice/edit', 'id' => $value['id']]) ?>"
                                           data-toggle="modal" data-target="#myModal" class="btn btn-xs btn-primary">编辑</a>
                                        <a href="<?php echo \yii\helpers\Url::to(['notice/del', 'id' => $value['id']]) ?>"
                                           onclick="return openAgency(this,'是否确认删除?')" class="btn btn-xs btn-danger">删除</a>
                                    </td>
                                </tr>
                                <?php $i++ ?>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php if(empty($data)):?>
                            <div class="text-center m-t-lg clearfix wrapper-lg animated fadeInRightBig" id="galleryLoading">
                                <h1><i class="fa fa-warning" style="color: red;font-size: 40px"></i></h1>
                                <h4 class="text-muted"><?php echo sprintf(Yii::t('app','search_null'),'公告管理')?></h4>
                                <p class="m-t-lg"></p>
                            </div>
                        <?php endif;?>
                    </div>
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
        window.location = '<?php echo \yii\helpers\Url::to(['notice/index','show'=>''],true)?>' + val;
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