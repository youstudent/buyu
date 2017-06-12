<?php
use yii\helpers\Url;
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">

    <title> hAdmin- 主页</title>

    <meta name="keywords" content="">
    <meta name="description" content="">

    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->

    <link rel="shortcut icon" href="favicon.ico"> <link href="/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
    <link href="/css/animate.css" rel="stylesheet">
    <link href="/css/style.css?v=4.1.0" rel="stylesheet">
</head>

<body class="fixed-sidebar full-height-layout gray-bg" style="overflow:hidden">
<div id="wrapper">
    <!--左侧导航开始-->
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="nav-close"><i class="fa fa-times-circle"></i>
        </div>
        <div class="sidebar-collapse">
            <ul class="nav" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="clear">
                                    <span class="block m-t-xs" style="font-size:20px;">
                                        <i class="fa fa-bar-chart-o"></i>
                                        <strong class="font-bold"><?=Yii::t('app','title_name')?></strong>
                                    </span>
                                </span>
                        </a>
                    </div>
                    <div class="logo-element"><?=Yii::t('app','title_name')?>
                    </div>
                </li>
                <li>
                    <a class="J_menuItem" href="<?php echo Url::to(['index/index'])?>">
                        <i class="fa fa-home"></i>
                        <span class="nav-label">首页</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fa fa-users"></i>
                        <span class="nav-label">玩家模块</span>
                        <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a class="J_menuItem" href="graph_echarts.html">玩家列列表</a>
                        </li>
                        <li>
                            <a class="J_menuItem" href="graph_flot.html">充值流水</a>
                        </li>
                        <li>
                            <a class="J_menuItem" href="graph_morris.html">对战流水</a>
                        </li>
                        <li>
                            <a class="J_menuItem" href="graph_rickshaw.html">在线人数</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a class="J_menuItem" href="<?php echo Url::to(['index/index'])?>">
                        <i class="fa fa-bullhorn"></i>
                        <span class="nav-label">公告管理</span>
                    </a>
                </li>
                <li>
                    <a class="J_menuItem" href="<?php echo Url::to(['index/index'])?>">
                        <i class="fa fa-credit-card"></i>
                        <span class="nav-label">充值管理</span>
                    </a>
                </li>
                <li>
                    <a class="J_menuItem" href="<?php echo Url::to(['index/index'])?>">
                        <i class="fa fa-paypal"></i>
                        <span class="nav-label">支付管理</span>
                    </a>
                </li>
                <li>
                    <a class="J_menuItem" href="<?php echo Url::to(['index/index'])?>">
                        <i class="fa fa-line-chart"></i>
                        <span class="nav-label">统计中心</span>
                    </a>
                </li>
                <li>
                    <a class="J_menuItem" href="<?php echo Url::to(['index/index'])?>">
                        <i class="fa fa-user-plus"></i>
                        <span class="nav-label">管理员管理</span>
                    </a>
                </li>

            </ul>
        </div>
    </nav>
    <!--左侧导航结束-->
    <!--右侧部分开始-->
    <div id="page-wrapper" class="gray-bg dashbard-1">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header"><a class="navbar-minimalize minimalize-styl-2 btn btn-info " href="#"><i class="fa fa-bars"></i> </a>
                    <span style="line-height: 50px;margin-left: 5px;font-size: 24px;"><?=Yii::t('app','title_name')?>游戏后台管理系统</span>
                </div>
            </nav>
        </div>
        <div class="row J_mainContent" id="content-main">
            <iframe id="J_iframe" width="100%" height="100%" src="<?=Url::to(['index/index'])?>" frameborder="0" data-id="index_v1.html" seamless></iframe>
        </div>
    </div>
    <!--右侧部分结束-->
</div>

<!-- 全局js -->
<script src="js/jquery.min.js?v=2.1.4"></script>
<script src="js/bootstrap.min.js?v=3.3.6"></script>
<script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="js/plugins/layer/layer.min.js"></script>

<!-- 自定义js -->
<script src="js/hAdmin.js?v=4.1.0"></script>
<script type="text/javascript" src="js/index.js"></script>

<!-- 第三方插件 -->
<script src="js/plugins/pace/pace.min.js"></script>
<div style="text-align:center;">
    <p>来源:<a href="http://www.mycodes.net/" target="_blank">源码之家</a></p>
</div>
</body>

</html>

