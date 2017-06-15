<?php
$this->title = "代理商列表-".\Yii::$app->params['appName'];
?>
<section id="content">
    <section class="vbox">
        <section class="scrollable padder">
            <!--            面包屑开始           -->
            <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="/site/index"><i class="fa fa-home"></i>首页</a></li>
                <li><a href="#">代理管理</a></li>
                <li class="active">代理商列表</li>
            </ul>
            <!--            面包屑结束            -->
            <section class="panel panel-default">
                <div class="panel-heading">
                <!--                搜索开始          -->
                    <div class="row text-sm wrapper" style="padding-left: 30px;">
                        <form id="agencyForm" action="<?=\yii\helpers\Url::to(['pay/pay'])?>" method="get" role="form">
                            <div class="form-inline">
                                <div class="form-group">
                                    <div class="form-group">
                                        <div class="input-group">

                                            <div class="form-group field-agency-keyword">
                                                <input type="text" id="agency-keyword" class="form-control" name="game_id" value="<?php if(isset($game_id))echo $game_id;?>" placeholder="请输入玩家提供的ＩＤ">
                                            </div>
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="submit"><i class="fa fa-search"></i>&nbsp;搜索</button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                <!--                搜索结束          -->
                </div>
                
                <div class="panel-body">
                    <!--                表格开始          -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th class="text-center">编号</th>
                                <th class="text-center">ID</th>
                                <th class="text-center">手机号</th>
                                <th class="text-center">昵称</th>

                                <!--                            多货币修改代码-->
                                <?php
                                $item = \common\models\GoldConfigObject::find()->all();
                                foreach ($item as $key=>$value){
                                    echo "<th class=\"text-center\">".$value['name']."</th>";
                                }
                                ?>
                                <!--                            多货币修改代码-->

                                <th class="text-center">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(!empty($data)):?>
                                <td class="text-center">1</td>
                                <td class="text-center"><?=$data['game_id']?></td>
                                <td class="text-center"><?=$data['phone']?></td>
                                <td class="text-center"><?=$data['nickname']?></td>
                                <!--                                多货币修改-->
                                <?php foreach ($data['gold'] as $keys=>$values):?>
                                    <td class="text-center"><?= $values ?></td>
                                <?php endforeach;?>
                                <!--                                多货币修改-->

                                <td class="text-center">
                                    <a href="<?php echo \yii\helpers\Url::to(['pay/pay1','id'=>$data['id']])?>" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#myModal">充值</a>
                                </td>
                            <?php endif;?>
                            </tbody>
                        </table>
                        <!--                        未找到任何数据显示-->
                        <?php if(empty($data)):?>
                            <div class="text-center m-t-lg clearfix wrapper-lg animated fadeInRightBig" id="galleryLoading">
                                <h1><i class="fa fa-warning" style="color: red;font-size: 40px"></i></h1>
                                <h4 class="text-muted">非常抱歉,我们未能找到"指定的用户"相关的任何数据!</h4>
                                <p class="m-t-lg"></p>
                            </div>
                        <?php endif;?>
                        <!--                        为找到任何数据显示结束-->
                    </div>
                    <!--                表格结束          -->
                </div>
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
                //text: '',
                type: "input",
                showCancelButton: true,
                closeOnConfirm: false,
                animation: "slide-from-top",
                confirmButtonText: "充值",
                cancelButtonText: "取消",
                inputPlaceholder: title
            },
            function(inputValue){
                if (inputValue === false) return false;

                if (inputValue === "") {
                    swal.showInputError(title);
                    return false
                }
                $.ajax({
                    url:_this.href,
                    data:'gold='+inputValue,
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
                //swal("Nice!", "You wrote: " + inputValue, "success");
            });
//        swal({
//                title: title,
//                text: "请确认你的操作时经过再三是考虑!",
//                type: "warning",
//                showCancelButton: true,
//                confirmButtonText: "确认",
//                cancelButtonText: "取消",
//                closeOnConfirm: false,
//                showLoaderOnConfirm: true
//            },
//            function(){
//                console.log(_this.href);
//                $.ajax({
//                    url:_this.href,
//                    success:function (res) {
//                        if(res.code == 1)
//                        {
//                            swal(
//                                {
//                                    title: res.message,
//                                    type: "success",
//                                    showCancelButton: false,
//                                    confirmButtonText: "确认",
//                                    closeOnConfirm: false,
//                                    showLoaderOnConfirm: true
//                                },function () {
//                                    window.location.reload();
//                                }
//                            );
//                        }else{
//                            swal(
//                                {
//                                    title: res.message,
//                                    type: "error",
//                                    showCancelButton: false,
//                                    confirmButtonText: "确认",
//                                    closeOnConfirm: false,
//                                }
//                            );
//                        }
//                    }
//                });
//            });

return false;
}
</script>
