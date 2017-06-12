<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */?>
<section id="content">
    <section class="vbox">
        <section class="scrollable padder">
            <!--            面包屑开始           -->
            <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="/site/index"><i class="fa fa-home"></i>首页</a></li>
                <li><a href="#">统计中心</a></li>
                <li class="active">用户统计</li>
            </ul>
            <!--            面包屑结束            -->
            <section class="panel panel-default">
                <!--                搜索开始          -->
                <div class="row text-sm wrapper">
                    <div class="col-sm-9">
                        <input type="date" value="2015-09-24"/>
                    </div>
                    <div class="col-sm-3 text-right">
                        <a href="/agency/add-new" class="btn btn-primary" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i>&nbsp;添加新的代理商</a>
                    </div>
                </div>
                <!--                搜索结束          -->
                <!--                表格开始          -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th class="text-center">编号</th>
                            <th class="text-center" style="width: 80px;">代理ID</th>
                            <th class="text-center">上级代理姓名</th>
                            <th class="text-center">手机号</th>
                            <th class="text-center">用户名</th>
                            <th class="text-center">注册时间</th>
                            <th class="text-center">剩余金币</th>
                            <th class="text-center">总消费金币</th>
                            <th class="text-center">身份证号</th>
                            <th class="text-center">推荐码</th>
                            <th class="text-center">状态</th>
                            <th class="text-center">操作</th>
                        </tr>
                        </thead>
                        <tbody>

                        <tr>
                            <td class="text-center">1</td>
                            <td class="text-center">0</td>
                            <td class="text-center">平台</td>
                            <td class="text-center">15682707130</td>
                            <td class="text-center">平台</td>
                            <td class="text-center">70-01-01 08:02:03</td>
                            <td class="text-center">0.00</td>
                            <td class="text-center">28</td>
                            <td class="text-center">513722199702046012</td>
                            <td class="text-center">2567</td>
                            <td class="text-center">
                                <span class="label bg-primary">正常</span>
                            </td>
                            <td class="text-center" style="width: 250px">

                                <a href="/agency/pay?id=1" data-toggle="modal" data-target="#myModal" class="btn btn-xs btn-success">&nbsp;充&nbsp;值&nbsp;</a>
                                <a href="/agency/deduct?id=1" data-toggle="modal" data-target="#myModal" class="btn btn-xs btn-info">&nbsp;扣&nbsp;除&nbsp;</a>

                                <a onclick="return openAgency(this,'是否封锁该账号?')" href="/agency/status?id=1" class="btn btn-xs btn-danger">&nbsp;封&nbsp;号&nbsp;</a>

                                <!--                                            <a href="--><!--"-->
                                <!--                                               class="btn btn-xs btn-primary">&nbsp;下&nbsp;级&nbsp;</a>-->
                                <a href="/rebate/index?Agency%5Bselect%5D=phone&amp;Agency%5Bkeyword%5D=15682707130" class="btn btn-xs btn-primary">查看分佣</a>

                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">2</td>
                            <td class="text-center">1</td>
                            <td class="text-center">平台</td>
                            <td class="text-center">15682707135</td>
                            <td class="text-center">321</td>
                            <td class="text-center">70-01-01 08:05:21</td>
                            <td class="text-center">7.00</td>
                            <td class="text-center">45</td>
                            <td class="text-center">513722199702046012</td>
                            <td class="text-center">567</td>
                            <td class="text-center">
                                <span class="label bg-danger">封停</span>
                            </td>
                            <td class="text-center" style="width: 250px">

                                <a onclick="return openAgency(this,'是否开启账号?')" href="/agency/status?id=2" class="btn btn-xs btn-success">&nbsp;开&nbsp;启&nbsp;</a>

                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <!--                表格结束          -->
                <!--                分页开始          -->
                <footer class="panel-footer">
                    <div class="row">
                        <div class="col-sm-12 text-right text-center-xs">
                            <ul class="pagination pagination-sm m-t-none m-b-none"><li class="prev disabled"><span>«</span></li>
                                <li class="active"><a href="/agency/index?id=1&amp;page=1&amp;per-page=2" data-page="0">1</a></li>
                                <li><a href="/agency/index?id=1&amp;page=2&amp;per-page=2" data-page="1">2</a></li>
                                <li><a href="/agency/index?id=1&amp;page=3&amp;per-page=2" data-page="2">3</a></li>
                                <li><a href="/agency/index?id=1&amp;page=4&amp;per-page=2" data-page="3">4</a></li>
                                <li><a href="/agency/index?id=1&amp;page=5&amp;per-page=2" data-page="4">5</a></li>
                                <li class="next"><a href="/agency/index?id=1&amp;page=2&amp;per-page=2" data-page="1">»</a></li></ul>                        </div>
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

    function openAgency(_this,title) {
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
            function(){
                console.log(_this.href);
                $.ajax({
                    url:_this.href,
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
            });

        return false;
    }
</script>
