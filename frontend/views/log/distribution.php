<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */
$this->title = "代理列表-".\Yii::$app->params['appName'];
use yii\bootstrap\ActiveForm;

?>
<section id="content">
    <section class="vbox">
        <section class="scrollable padder">
            <!--            面包屑开始           -->
            <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="<?= \yii\helpers\Url::to(['site/index']) ?>"><i class="fa fa-home"></i>首页</a></li>
                <li class="active">代理列表</li>
            </ul>
            <!--            面包屑结束            -->
            <section class="panel panel-default">

                <div class="panel-heading" style="background-color: #ffffff;padding: 0px;">
                    <!--                搜索开始          -->
                    <div class="row text-sm wrapper">
                        <div class="col-sm-9">
                            <div class="form-inline">
                                <?php $form = ActiveForm::begin([
                                    'id' => 'agencyForm',
                                    'action' => ['log/agency'],
                                    'method' => 'get',
                                    'fieldConfig' => [
                                        'template' => "{input}",
                                    ],
                                ]) ?>
                                <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>"
                                       value="<?= Yii::$app->request->getCsrfToken() ?>">
                                <div class="form-inline">
                                    <div class="form-group">

                                        <?= $form->field($model, 'select')
                                            ->dropDownList(["1" => Yii::t('app', '智能查询'),
                                                "name" => Yii::t('app', '用户名称'),
                                                "phone" => Yii::t('app', '手机号码'),
                                            ]) ?>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">

                                            <?php echo $form->field($model, 'keyword')->textInput(['class' => 'form-control', 'placeholder' => '输入关键字']) ?>
                                            <span class="input-group-btn">
                                             <button class="btn btn-default" type="submit"><i class="fa fa-search"></i>搜索</button>
                                        </span>
                                        </div>
                                    </div>
                                </div>
                                <?php ActiveForm::end() ?>
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
                        <table class="table table-bordered table-hover"
                               style="border: 0px solid #f1f1f1 ; border-bottom: 1px solid #f1f1f1">
                            <thead>
                            <tr>
                                <th class="text-center" style="border-left: 0px">编号</th>
                                <th class="text-center">用户名</th>
                                <th class="text-center">代理等级</th>
                                <th class="text-center">手机号</th>
                                <th class="text-center">剩余金币</th>
                                <th class="text-center">累计消费</th>
                                <th class="text-center">累计返佣</th>
                                <th class="text-center">注册时间</th>
                                <th class="text-center" style="border-right: 0px">状态</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php $i = 1; ?>
                            <?php foreach ($data as $key => $value): ?>
                                <tr>
                                    <td class="text-center" style="border-left: 0px"><?= $i ?></td>
                                    <td class="text-center"><?= $value['name'] ?></td>
                                    <td class="text-center"><?= $value['dj'] ?></td>
                                    <td class="text-center"><?= $value['phone'] ?></td>
                                    <td class="text-center"><?= $value['gold'] ?></td>
                                    <td class="text-center"><?= $value['gold_all'] ?></td>
                                    <td class="text-center"><?= $value['fl_gold_num'] ?></td>
                                    <td class="text-center"><?= date("Y-m-d H:i:s", $value['reg_time']) ?></td>
                                    <td class="text-center" style="border-right: 0px;">
                                        <?php if ($value['status'] == 1): ?>
                                            <span class="badge  bg-success">正常</span>
                                        <?php elseif ($value['status'] == 2): ?>
                                            <span class="badge  bg-danger">封停</span>
                                        <?php elseif ($value["status"] == 3): ?>
                                            <span class="badge  bg-danger">审核中</span>
                                        <?php elseif ($value["status"] == 4): ?>
                                            <span class="badge  bg-danger">审核未过</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php $i++ ?>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        <!--                        未找到任何数据显示-->
                        <?php if (empty($data)): ?>
                            <div class="text-center m-t-lg clearfix wrapper-lg animated fadeInRightBig"
                                 id="galleryLoading">
                                <h1><i class="fa fa-warning" style="color: red;font-size: 40px"></i></h1>
                                <h4 class="text-muted">非常抱歉,我们未能找到"<?= $model->keyword ?>"相关的任何数据!</h4>
                                <p class="m-t-lg"></p>
                            </div>
                        <?php endif; ?>
                        <!--                        为找到任何数据显示结束-->
                    </div>
                    <!--                表格结束          -->
                </div>

                <!--                分页开始          -->
                <footer class="panel-footer">
                    <div class="row">
                        <div class="col-sm-12 text-right text-center-xs">
                            <?= \yii\widgets\LinkPager::widget([
                                'pagination' => $pages,
                                'options' => [
                                    'class' => 'pagination pagination-sm m-t-none m-b-none',
                                ]
                            ]) ?>
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