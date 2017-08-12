<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */
$this->title = Yii::t('app', 'down_index') . '-' . Yii::$app->params['appName'];
?>
<section id="content">
    <section class="vbox">
        <section class="scrollable padder">
            <!--            面包屑开始           -->
            <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="/site/index"><i class="fa fa-home"></i>首页</a></li>
                <li><a href="#">上下分管理</a></li>
                <li class="active">上下分记录</li>
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
                                    <input type="radio" name="options" id="statusOk">上分</label>
                                <label class="btn btn-default <?php if (Yii::$app->request->get('show') == 0) {
                                    echo "active";
                                } ?> " onclick="setStatus(0)">
                                    <input type="radio" name="options" id="statusColose">下分</label>
                                    <input type="text" disabled="disabled" style="height: 33px;margin-left: 20px; width: 200px" value="总上分金币:<?=$rows['sum(gold)']?>">
                                    <input type="text" disabled="disabled" style="height: 33px;margin-left: 20px; width: 200px" value="总上分钻石:<?=$rows['sum(diamond)']?>">
                                    <input type="text" disabled="disabled" style="height: 33px;margin-left: 20px; width: 200px" value="总下分金币:<?=$row['sum(gold)']?>">
                                    <input type="text" disabled="disabled" style="height: 33px;margin-left: 20px; width: 200px" value="总下分钻石:<?=$row['sum(diamond)']?>">
                            </div>
                            
                            <input type="hidden" name="Agency[searchstatus]" value="" id="status">
                            <!--筛选状态 全部|正常|封停 结束-->
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
                                <th class="text-center">族员昵称</th>
                                <th class="text-center">上下</th>
                                <th class="text-center">金币</th>
                                <th class="text-center">钻石</th>
                                <th class="text-center">鱼币</th>
                                <th class="text-center">时间</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php $i = 1; ?>
                            <?php foreach ($data as $key => $value): ?>
                                <tr>
                                    <td class="text-center" style="border-left: 0px;"><?= $i ?></td>
                                    <td class="text-center"><?=$value->users->name?></td>
                                    <td class="text-center"><?=$value->type==5?'下分':'上分'?></td>
                                    <td class="text-center"><?= $value->gold?></td>
                                    <td class="text-center"><?= $value->diamond?></td>
                                    <td class="text-center"><?= $value->fishgold?></td>
                                   <!-- <td class="text-center"><?/*=\common\models\Updowninfo::getCount($value->type,$value->id,$value->updown,$value->playerid)*/?></td>-->
                                    <td class="text-center"><?= $value->time ?></td>
                                </tr>
                                <?php $i++ ?>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php if(empty($data)):?>
                            <div class="text-center m-t-lg clearfix wrapper-lg animated fadeInRightBig" id="galleryLoading">
                                <h1><i class="fa fa-warning" style="color: red;font-size: 40px"></i></h1>
                                <h4 class="text-muted">对不起、未能找到"<?=Yii::t('app','down_index')?>"相关的任何数据</h4>
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
        window.location = '<?php echo \yii\helpers\Url::to(['family/up-and-down','show'=>''],true)?>' + val;
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