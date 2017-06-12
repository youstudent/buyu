<?php
$this->title = Yii::t('app', 'site_index') . '-' . Yii::$app->params['appName'];
$agency = \backend\models\Agency::find();
?>
<section id="content" style="background-color: #ffffff">
    <section class="vbox">
        <section class="scrollable padder">
            <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="#"><i class="fa fa-home"></i> <?= Yii::t('app', 'index') ?></a></li>
                <li class="active"><?= Yii::t('app', 'site_index') ?></li>
            </ul>
            <section class="panel panel-default" style="margin-top: 10px;">
                <div class="row m-l-none m-r-none bg-light lter">
                    <div class="col-sm-6 col-md-3 padder-v b-r b-light">
                    <span class="fa-stack fa-2x pull-left m-r-sm"> <i
                                class="fa fa-circle fa-stack-2x text-info"></i> <i
                                class="fa fa-male fa-stack-1x text-white"></i> </span>
                        <a class="clear" href="<?= \yii\helpers\Url::to(["users/list"]) ?>"> <span
                                    class="h3 block m-t-xs"><strong><?php echo \backend\models\Users::find()->count() ?></strong></span>
                            <small class="text-muted text-uc"><?= Yii::t('app', 'index_game_num') ?></small>
                        </a>
                    </div>
                    <div class="col-sm-6 col-md-3 padder-v b-r b-light lt">
                    <span class="fa-stack fa-2x pull-left m-r-sm"> <i
                                class="fa fa-circle fa-stack-2x text-warning"></i> <i
                                class="fa fa-briefcase fa-stack-1x text-white"></i> </span>
                        <a class="clear" href="<?= \yii\helpers\Url::to(["agency/index"]) ?>"> <span
                                    class="h3 block m-t-xs"><strong><?php echo $agency->count()-1 ?></strong></span>
                            <small class="text-muted text-uc"><?= Yii::t('app', 'index_agency_num') ?></small>
                        </a>
                    </div>
                    <div class="col-sm-6 col-md-3 padder-v b-r b-light">
                    <span class="fa-stack fa-2x pull-left m-r-sm"> <i
                                class="fa fa-circle fa-stack-2x text-danger"></i> <i
                                class="fa fa-legal fa-stack-1x text-white"></i> </span>
                        <a class="clear"
                           href="<?= \yii\helpers\Url::to(["agency/index", "Agency" => ['searchstatus' => 3]]) ?>">
                            <span class="h3 block m-t-xs"><strong><?php echo $agency->where(['status' => 3])->count() ?></strong></span>
                            <small class="text-muted text-uc"><?= Yii::t('app', 'index_stay_agency_num') ?></small>
                        </a>
                    </div>
                    <div class="col-sm-6 col-md-3 padder-v b-r b-light lt">
                    <span class="fa-stack fa-2x pull-left m-r-sm"> <i
                                class="fa fa-circle fa-stack-2x icon-muted"></i> <i
                                class="fa fa-ban fa-stack-1x text-white"></i> </span>
                        <a class="clear"
                           href="<?= \yii\helpers\Url::to(["agency/index", "Agency" => ['searchstatus' => 2]]) ?>">
                            <span class="h3 block m-t-xs"><strong><?php echo $agency->where(['in', 'status', [2, 4]])->count() ?></strong></span>
                            <small class="text-muted text-uc"><?= Yii::t('app', 'index_exit_agency_num') ?></small>
                        </a>
                    </div>
                </div>
            </section>
            <div class="row">
                <div class="col-md-12">
                    <section class="panel panel-default">
                        <header class="panel-heading font-bold"><?= Yii::t('app', 'index_map_title') ?></header>
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
                                                    if ($i == 1) echo "'" . $i . Yii::t('app', 'index_map_time') . "'"; else echo ",'" . $i . Yii::t('app', 'index_map_time') . "'";
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
                                                name: '<?=Yii::t('app', 'index_agency_series')?>',
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
                                            <?php if(Yii::$app->params['backendPayUser']):?>
                                            ,
                                            {
                                                name: '<?=Yii::t('app', 'index_users_series')?>',
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
                                            <?php endif;?>
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
