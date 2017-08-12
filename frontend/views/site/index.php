<?php
$this->title = '控制台' . '-' . Yii::$app->params['appName'];
$agency = \backend\models\Agency::find();
?>
<section id="content" style="background-color: #ffffff">
    <section class="vbox">
        <section class="scrollable padder">
            <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="#"><i class="fa fa-home"></i> 首页 </a></li>
                <li class="active"> 控制台 </li>
            </ul>
            <section class="panel panel-default" style="margin-top: 10px;">
                <?php if(!empty($model)):?>
                <div class="row m-l-none m-r-none bg-light lter">
                    <div class="col-sm-6 col-md-3 padder-v b-r b-light">
                    <span class="fa-stack fa-2x pull-left m-r-sm"> <i
                                class="fa fa-circle fa-stack-2x text-info"></i> <i
                                class="fa fa-male fa-stack-1x text-white"></i> </span>
                        <a class="clear" href="#">
                                <span class="h3 block m-t-xs">
                                    <?=
                                        /*$data = $model->getGold();
                                        foreach ($data as $key=>$value){
                                            echo $key.":".$value."\t";
                                        }*/
                                     '金币'. $model->users->gold.'&nbsp'.
                                     '钻石'. $model->users->diamond.'&nbsp'.
                                     '鱼币'. $model->users->fishGold;
                                    ?>
                                </span>
                            <small class="text-muted text-uc">我的余额</small>
                        </a>
                    </div>
                    <div class="col-sm-6 col-md-3 padder-v b-r b-light lt">
                    <span class="fa-stack fa-2x pull-left m-r-sm"> <i
                                class="fa fa-circle fa-stack-2x text-warning"></i> <i
                                class="fa fa-briefcase fa-stack-1x text-white"></i> </span>
                        <a class="clear" href="#"> <span
                                    class="h3 block m-t-xs">
                                 <?='家族总金币'.$Family['gold']?>
                            </span>
                            <small class="text-muted text-uc">保险箱总金币</small>
                        </a>
                    </div>
                    <div class="col-sm-6 col-md-3 padder-v b-r b-light">
                    <span class="fa-stack fa-2x pull-left m-r-sm"> <i
                                class="fa fa-circle fa-stack-2x text-danger"></i> <i
                                class="fa fa-legal fa-stack-1x text-white"></i> </span>
                        <a class="clear"
                           href="#">
                            <span class="h3 block m-t-xs">
                                    <?='家族总钻石'.$Family['diamond']?>
                            </span>
                            <small class="text-muted text-uc">保险箱总钻石</small>
                        </a>
                    </div>
                    <div class="col-sm-6 col-md-3 padder-v b-r b-light lt">
                    <span class="fa-stack fa-2x pull-left m-r-sm"> <i
                                class="fa fa-circle fa-stack-2x icon-muted"></i> <i
                                class="fa fa-ban fa-stack-1x text-white"></i> </span>
                        <a class="clear">
                            <span class="h3 block m-t-xs"><span class="h3 block m-t-xs">
                                    <?='家族总鱼币'.$Family['fishgold']?>
                            </span></span>
                            <small class="text-muted text-uc">保险箱总鱼币</small>
                        </a>
                    </div>
                </div>
                <?php endif;?>
            </section>
            <div class="row">
                <div class="col-md-12">
                    <section class="panel panel-default">
                        <header class="panel-heading font-bold">本月金币曲线图</header>
                        <div class="panel-body">
                            <div id="char" style="height: 410px;"></div>

                            <script type="text/javascript">
                                window.onload = function () {
                                    var myChart = echarts.init(document.getElementById('char'));
                                    //app.title = '坐标轴刻度与标签对齐';
                                    var option = {
                                        color: ['#3398DB', '#fb6b5b'],
                                        tooltip: {
                                            trigger: 'axis',
                                            axisPointer: {            // 坐标轴指示器，坐标轴触发有效
                                                type: 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                                            }
                                        },
                                        grid: {
                                            left: '1%',
                                            right: '1%',
                                            bottom: '3%',
                                            containLabel: true
                                        },
                                        xAxis: [
                                            {
                                                type: 'category',
                                                data: [<?php for ($i = 1; $i <= count($monthOrderToDay); $i++) {
                                                    if ($i == 1) echo "'" . $i . "号'"; else echo ",'" . $i . "号'";
                                                }?>],
                                                axisTick: {
                                                    alignWithLabel: true
                                                }
                                            }
                                        ],
                                        yAxis: [
                                            {
                                                type: 'value'
                                            }
                                        ],
                                        series: [
                                            {
                                                name: '玩家上分(金币)',
                                                type: 'bar',
                                                barWidth: '45%',
                                                data: [<?php $i = 0; foreach ($monthOrderToDay as $item) {
                                                    if ($i == 0) {
                                                        $i++;
                                                        echo $item;
                                                    } else {
                                                        echo "," . $item;
                                                    }
                                                }?>]
                                            }
                                            ,
                                            {
                                                name: '玩家上分(钻石)',
                                                type: 'bar',
                                                barWidth: '45%',
                                                data: [<?php $i = 0; foreach ($userOrderToDay as $item) {
                                                    if ($i == 0) {
                                                        $i++;
                                                        echo $item;
                                                    } else {
                                                        echo "," . $item;
                                                    }
                                                }?>]
                                            }
                                        ]
                                    };

                                    myChart.setOption(option);
                                };
                            </script>

                        </div>
                        <footer class="panel-footer bg-white no-padder">

                        </footer>
                    </section>
                </div>
            </div>
        </section>
    </section>
</section>
