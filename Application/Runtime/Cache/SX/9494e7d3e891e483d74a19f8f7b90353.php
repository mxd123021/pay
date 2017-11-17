<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商家列表 - 网站管理后台</title>
        <meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
	<meta name="format-detection" content="telephone=no">
	<link href="/Static/SX/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Static/SX/css/font-awesome.css" rel="stylesheet">
	<link href="/Static/SX/css/sweetalert.css" rel="stylesheet">
    <link href="/Static/SX/css/animate_new.css" rel="stylesheet">
    <link href="/Static/SX/css/style.css" rel="stylesheet">
	<link href="/Static/SX/css/app.css" rel="stylesheet">
    <link href="/Static/SX/js/layer/theme/default/layer.css" rel="stylesheet">
    <!-- Mainly scripts -->
	<script src="/Static/SX/js/jquery-2.1.1.js"></script>
    <script src="/Static/SX/js/layer/layer.js"></script>
    <script src="/Static/SX/js/bootstrap.min.js"></script>
    <script src="/Static/SX/js/jquery.metisMenu.js"></script>
    <script src="/Static/SX/js/jquery.slimscroll.min.js"></script>
	<!-- Custom and plugin javascript -->
    <script src="/Static/SX/js/inspinia.js"></script>
    <script src="/Static/SX/js/pace.min.js"></script>
	<script src="/Static/SX/js/sweetalert.min.js"></script>
	<script src="/Static/SX/js/jquery.qrcode.min.js"></script>
    <!----开放式头部，请在自己的页面加上--</head>-->

    
    <link rel="stylesheet" type="text/css" href="/Static/weui/lib/weui.min.css"/>
    <link rel="stylesheet" type="text/css" href="/Static/weui/css/jquery-weui.min.css"/>
    <script type="text/javascript" src="/Static/weui/js/jquery-weui.min.js"></script>
    
    <link href="/Static/SX/css/wxCoupon.css" rel="stylesheet">
    <link href="/Static/SX/css/dataTables/dataTables.bootstrap.css" rel="stylesheet">
    <link href="/Static/SX/css/footable.core.css" rel="stylesheet">
    <link href="/Static/SX/css/plugins/custom.css" rel="stylesheet">
    <style type="text/css">
        #table-list-body .fa-edit {
            color: #3DA142;
            font-size: 20px;
        }

        #table-list-body .tips {
            color: #3DA142;
            cursor: pointer;
        }

        #table-list-body .tips span {
            display: none;
        }

        #table-list-body .prelative .form-control {
            display: none;
            vertical-align: middle;
            width: auto;
            height: 30px;
            padding: 3px 10px;
        }
    </style>

</head>
<body>
    <div id="wrapper">
    <nav role="navigation" class="navbar-default navbar-static-side">
        <div class="sidebar-collapse">
            <ul id="side-menu" class="nav metismenu">
                <li class="nav-header">
                    <div class="dropdown profile-element"> <span>
							<i class="fa fa-pied-piper-alt" style="font-size:60px;color:#1ab394;"></i>
                         </span>
                        <a href="javascript:;" class="dropdown-toggle">
                            <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold"><?php echo session('SX_STAFF.staffName');?></strong>
                             </span> <span class="text-muted text-xs block">网站管理后台</span> </span> </a>
                    </div>
					<div class="logo-element" style="text-align: center;">
						<i class="fa fa-cogs" style="font-size:50px;color:#1ab394;"></i>
							
					</div>
                </li>
                <li <?php if((CONTROLLER_NAME) == "Index"): if((ACTION_NAME) == "index"): ?>class="active"<?php endif; endif; ?>>
                    <a href="<?php echo U("SX/Index/index");?>"><i class="fa fa-home"></i> <span class="nav-label">首页</span><span class="label label-info pull-right"></span></a>
                </li>
                <?php if(in_array('sys_wz',session('SX_STAFF.grant')) or in_array('sys_zf',session('SX_STAFF.grant')) or in_array('sys_wx',session('SX_STAFF.grant')) or in_array('sys_admin',session('SX_STAFF.grant')) or in_array('sys_pass',session('SX_STAFF.grant'))): ?><li <?php if((CONTROLLER_NAME) == "Index"): if((ACTION_NAME) != "index"): ?>class="active"<?php endif; endif; ?>>
                        <a href="#"><i class="fa fa-cog"></i> <span class="nav-label">系统配置</span></a>
                        <ul class="nav nav-second-level collapse <?php if((CONTROLLER_NAME) == "Index"): if((ACTION_NAME) != "index"): ?>class="active"<?php endif; endif; ?>">
                            <!--<?php if(in_array('sys_wz',session('SX_STAFF.grant'))): ?>-->
                                <!--<li <?php if((CONTROLLER_NAME) == "Index"): if((ACTION_NAME) == "toConfig"): ?>class="active"<?php endif; endif; ?>><a href="<?php echo U("SX/Index/toConfig");?>">网站配置</a></li>-->
                            <!--<?php endif; ?>-->
                            <!--<?php if(in_array('sys_zf',session('SX_STAFF.grant'))): ?>-->
                                <!--<li <?php if((CONTROLLER_NAME) == "Index"): if((ACTION_NAME) == "payConfig"): ?>class="active"<?php endif; endif; ?>><a href="<?php echo U("SX/Index/payConfig");?>">支付配置</a></li>-->
                            <!--<?php endif; ?>-->
                            <!--<?php if(in_array('sys_wx',session('SX_STAFF.grant'))): ?>-->
                                <!--<li <?php if((CONTROLLER_NAME) == "Index"): if((ACTION_NAME) == "wxConfig"): ?>class="active"<?php endif; endif; ?>><a href="<?php echo U("SX/Index/wxConfig");?>">微信配置</a></li>-->
                            <!--<?php endif; ?>-->
                            <?php if(in_array('sys_admin',session('SX_STAFF.grant'))): ?><li <?php if(ACTION_NAME == 'employers' or ACTION_NAME == 'roles') echo "class='active'"; ?>>
                                    <a href="index.html#">管理员配置<span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level collapse">
                                        <li <?php if((ACTION_NAME) == "employers"): ?>class="active"<?php endif; ?>>
                                            <a href="<?php echo U("SX/Index/employers");?>">管理员管理</a>
                                        </li>
                                        <li <?php if((ACTION_NAME) == "roles"): ?>class="active"<?php endif; ?>>
                                            <a href="<?php echo U("SX/Index/roles");?>">角色管理</a>
                                        </li>
                                    </ul>
                                </li><?php endif; ?>
                            <?php if(in_array('sys_pass',session('SX_STAFF.grant'))): ?><li <?php if((CONTROLLER_NAME) == "Index"): if((ACTION_NAME) == "modifypwd"): ?>class="active"<?php endif; endif; ?>><a href="<?php echo U("SX/Index/modifypwd");?>">修改密码</a></li><?php endif; ?>
                        </ul>
                    </li><?php endif; ?>
                <?php if(in_array('user_lists',session('SX_STAFF.grant'))): ?><li <?php if((CONTROLLER_NAME) == "Users"): ?>class="active"<?php endif; ?>>
    					<a href="#"><i class="fa fa-puzzle-piece"></i> <span class="nav-label">网站商家</span></a>
                        <ul class="nav nav-second-level collapse <?php if((CONTROLLER_NAME) == "Users"): ?>in<?php endif; ?>">
                            <?php if(in_array('user_lists',session('SX_STAFF.grant'))): ?><li <?php if((CONTROLLER_NAME) == "Users"): if((ACTION_NAME) == "merLists"): ?>class="active"<?php endif; endif; ?>><a href="<?php echo U("SX/Users/merLists");?>">商家列表</a></li><?php endif; ?>
<!--                            <?php if(in_array('user_lists',session('SX_STAFF.grant'))): ?><li <?php if((CONTROLLER_NAME) == "Users"): if((ACTION_NAME) == "auditrealname"): ?>class="active"<?php endif; endif; ?>><a href="<?php echo U("SX/Users/auditrealname");?>">实名认证审核</a></li><?php endif; ?>-->
                        </ul>
                    </li><?php endif; ?>
                <!--<?php if(in_array('market_ad',session('SX_STAFF.grant')) or in_array('market_re',session('SX_STAFF.grant'))): ?>-->
                    <!--<li <?php if((CONTROLLER_NAME) == "Market"): ?>class="active"<?php endif; ?>>-->
                        <!--<a href="#"><i class="fa fa-slideshare"></i> <span class="nav-label">营销功能</span><span class="label label-info pull-right">NEW</span></a>-->
                        <!--<ul class="nav nav-second-level collapse <?php if((CONTROLLER_NAME) == "Market"): ?>in<?php endif; ?>">-->
                            <!--<?php if(in_array('market_ad',session('SX_STAFF.grant'))): ?>-->
                                <!--<li <?php if((CONTROLLER_NAME) == "Market"): if((ACTION_NAME) == "wxmarket"): ?>class="active"<?php endif; endif; ?>><a href="<?php echo U("SX/Market/wxmarket");?>">微信广告</a></li>-->
                            <!--<?php endif; ?>-->
<!--&lt;!&ndash;                            <?php if(in_array('market_re',session('SX_STAFF.grant'))): ?>-->
                                <!--<li <?php if((CONTROLLER_NAME) == "Market"): if((ACTION_NAME) == "rlist"): ?>class="active"<?php endif; endif; ?>><a href="<?php echo U("SX/Market/rlist");?>">预约留言</a></li>-->
                            <!--<?php endif; ?>&ndash;&gt;-->
                        <!--</ul>-->
                    <!--</li>-->
                <!--<?php endif; ?>-->
                <?php if(in_array('user_lists',session('SX_STAFF.grant'))): ?><li <?php if((CONTROLLER_NAME) == "Statistics"): ?>class="active"<?php endif; ?>>
                        <a href="#"><i class="fa fa-line-chart"></i> <span class="nav-label">数据统计</span></a>
                        <ul class="nav nav-second-level collapse <?php if((CONTROLLER_NAME) == "Statistics"): ?>in<?php endif; ?>">
                            <?php if(in_array('user_lists',session('SX_STAFF.grant'))): ?><li <?php if((CONTROLLER_NAME) == "Statistics"): if((ACTION_NAME) == "orderLists"): ?>class="active"<?php endif; endif; ?>><a href="<?php echo U("SX/Statistics/orderLists");?>">收款订单列表</a></li><?php endif; ?>
                             
                        </ul>                        
                    </li><?php endif; ?>
<!--                <?php if(in_array('money_cz',session('SX_STAFF.grant'))): ?><li <?php if((CONTROLLER_NAME) == "Money"): ?>class="active"<?php endif; ?>>
                        <a href="#"><i class="fa fa-money"></i> <span class="nav-label">财务管理</span></a>
                        <ul class="nav nav-second-level collapse <?php if((CONTROLLER_NAME) == "Money"): ?>in<?php endif; ?>">
                            <?php if(in_array('money_dstx',session('SX_STAFF.grant'))): ?><li <?php if((CONTROLLER_NAME) == "Money"): if((ACTION_NAME) == "dsaccount"): ?>class="active"<?php endif; endif; ?>><a href="<?php echo U("SX/Money/dsaccount");?>">代收商户提现</a></li><?php endif; ?>
                            <?php if(in_array('money_cz',session('SX_STAFF.grant'))): ?><li <?php if((CONTROLLER_NAME) == "Money"): if((ACTION_NAME) == "paylist"): ?>class="active"<?php endif; endif; ?>><a href="<?php echo U("SX/Money/paylist");?>">商户充值列表</a></li><?php endif; ?>
                        </ul>
                    </li><?php endif; ?>-->
            </ul>

        </div>
    </nav>
        <div id="page-wrapper" class="gray-bg dashbard-1">
           <style>
	   .navbar-right{ margin-right:0px }
	   .navbar-top-links .dropdown-messages{ width: 250px;height:230px }
	   #myLoginUrlDiv .modal-body{ text-align: center; }
	   .navbar{ margin-bottom: 0; }
	   .dropdown-messages-box .media-body{ text-align: center;color: #f8ac59; font-size: 15px; }
	   </style>
	   <div class="row border-bottom">
        <nav class="navbar navbar-static-top" role="navigation">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
        </div>
            <ul class="nav navbar-top-links navbar-right">
<!--             {pg:if (defined('WxPay_CfgTips') && WxPay_CfgTips)}
                <li class="dropdown" id="CfgTips">
                    <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell"></i><span class="label label-warning">1</span>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts">
                        <li>
                           <div class="dropdown-messages-box">
                                <div class="media-body">
                                    <strong>{pg:$smarty.const.WxPay_CfgTips}</strong>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>
    {pg:/if} -->
			   <li class="dropdown" id="help-link">
                    <!--<a class="dropdown-toggle count-info" href="/merchants.php?m=Index&c=help&a=index" title="帮助文档" target="_blank">-->
                        <!--<i class="fa  fa-question-circle"></i> &lt;!&ndash;<span class="label label-warning">16</span>&ndash;&gt;-->
                    <!--</a>-->
                </li>

                <li>
                    <a  href="<?php echo U("SX/Index/logout");?>">
                        <i class="fa fa-sign-out"></i> 退出
                    </a>
                </li>
            </ul>
        </nav>
        </div>
		
		<div class="modal inmodal" tabindex="-1" role="dialog"  id="myLoginUrlDiv">
		<div class="modal-dialog" style="width: 500px;">
			<div class="modal-content animated bounceInRight">
				<div class="modal-header">
                    <button type="button" class="close _close"><span style="font-size: 35px;">×</span><span class="sr-only">Close</span></button>
                </div>
				<div class="modal-body">
				</div>
				<div class="modal-footer">
                    <button type="button" class="btn btn-white _close">关闭</button>
                </div>
			</div>
		</div>
	</div>
	
	<script>
   		 $("#myLoginUrlDiv ._close").click(function(){
			  $('#myLoginUrlDiv').hide();
			  $('#myLoginUrlDiv .modal-body').html('');
		  });
    </script>
         <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>商家列表</h2>
                    <ol class="breadcrumb">
                        
    <li><a href="<?php echo U(" SX/Index/index");?>">后台首页</a></li>
    <li>网站商家</li>

                        <li class="active">
                            <strong>商家列表</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>

<div class="wrapper wrapper-content animated fadeInRight">
    
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title clearfix">
                    <h1 class="realtime-title">商家信息列表&nbsp;&nbsp;(共：<?php echo ($Page['total']); ?> 条)</h1>
                    <div class="row">
                        <div class="col-lg-11">
                    <div class="input-group">
                        <input id="username" type="text" class="form-control" placeholder="输入商户名称"
                               value="<?php echo I('username');?>"> <span class="input-group-btn"> <button id="search"
                                                                                                type="button"
                                                                                                class="btn btn-primary">
                        搜索
                    </button> </span>
                    </div>
                        </div>
                        <div class="col-lg-1">
                            <button id="addMerchant" class="btn btn-primary pull-right">添加商户</button>
                        </div>
                    </div>
                </div>


                <div class="ibox-content">
                    <nav class="ui-nav clearfix">
                    </nav>
                    <div class="app__content js-app-main page-cashier">
                        <div>
                            <div class="cashier-realtime">
                                <div class="realtime-title-block clearfix">

                                </div>
                            </div>
                            <div class="js-real-time-region realtime-list-box loading">
                                <div class="widget-list">
                                    <div style="position: relative;" class="js-list-filter-region clearfix ui-box">
                                        <div class="widget-list-filter"></div>
                                    </div>
                                    <div class="ui-box">
                                        <table style="padding: 0px;" data-page-size="20"
                                               class="ui-table ui-table-list default no-paging footable-loaded footable">
                                            <thead class="js-list-header-region tableFloatingHeaderOriginal">
                                            <tr class="widget-list-header">
                                                <th>ID</th>
                                                <!--<th>选为特约商户</th>-->
                                                <!--<th>选为受理商</th>-->
                                                <th>商户名称</th>
                                                <th data-hide="phone">账号</th>
                                                <!--<th data-hide="phone">微信配置</th>-->
                                                <!--<th data-hide="phone">来源</th>-->
                                                <th data-hide="phone">审核状态</th>
                                                <th>操作</th>
                                            </tr>
                                            </thead>

                                            <tbody id="table-list-body" class="js-list-body-region">
                                            <?php if(!empty($Page['root'])): if(is_array($Page['root'])): $i = 0; $__LIST__ = $Page['root'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr class="widget-list-item">
                                                        <td class="uid"><?php echo ($vo['userId']); ?></td>
                                                        <!--<td style="padding-top:12px;" class="ptd"><input type="checkbox" <?php if(($vo['wx_issp']) == "1"): ?>checked="checked"<?php endif; ?> data-type='<?php echo ($vo['userId']); ?>' class="i-checks issp"></td>-->
                                                        <!--<td style="padding-top:12px;" class="ptd"><input type="checkbox"-->
                                                            <!--<?php if(($vo['userType']) == "1"): ?>checked="checked"<?php endif; ?>-->
                                                            <!--data-type='<?php echo ($vo['userId']); ?>' class="i-checks setType">-->
                                                        <!--</td>-->
                                                        <td class="prelative"><span
                                                                class="wxname"><?php echo ($vo['userName']); ?></span><input
                                                                type="text" class="form-control" placeholder="请输入商户名称">&nbsp;&nbsp;&nbsp;<span
                                                                class="tips"><i class="fa fa-edit"></i><span>保存修改</span></span>
                                                        </td>
                                                        <td><?php echo ($vo['loginName']); ?></td>

                                                        <td><?php
 switch($vo['userAudit']){ case 0: echo "<span class='label label-default'>未审核</span>"; break; case 1: echo "<span class='label label-primary'>已通过</span>"; break; case 2: echo "<span class='label label-warning'>审核中</span>"; break; case 3: echo "<span class='label label-danger'>未通过</span>"; break; } ?>
                                                        </td>
                                                        <td>
                                                            <!--<a title="查看商户资料"-->
                                                               <!--href="<?php echo U('SX/Users/auditdetail',array('id'=>$vo['userId']));?>"-->
                                                               <!--target="_blank"><i style="font-size: 16px;"-->
                                                                                  <!--class="fa fa-edit text-navy"></i></a>--><a
                                                                title="费率设置"
                                                                data-id="<?php echo ($vo['userId']); ?>" class="setWithdrawRate" data-rate="<?php echo ($vo['withdraw_rate']); ?>"><i style="font-size: 19px;"
                                                                                   class="fa fa-sign-in text-navy"></i><span class="label label-primary">费率设置</span></a>
                                                            &nbsp;&nbsp;
                                                            <a
                                                                title="登陆商户"
                                                                href="<?php echo U('SX/Users/logining',array('id'=>$vo['userId']));?>"
                                                                target="_blank"><i style="font-size: 19px;"
                                                                                   class="fa fa-sign-in text-navy"></i><span class="label label-primary">登陆商户</span></a>&nbsp;&nbsp;
                                                            <a
                                                                    title="子商家管理"
                                                                    href="<?php echo U('SX/RelationMerchant/getList',array('id'=>$vo['userId']));?>"><i style="font-size: 19px;"
                                                                                       class="fa fa-edit text-navy"></i><span class="label label-primary">子商家管理</span></a>
                                                        </td>
                                                        <!--<a onclick="showtopup('<?php echo ($vo['userName']); ?>','<?php echo ($vo['userId']); ?>')" title="充值" href="javascript:;" target="_blank"><i style="font-size: 19px;" class="fa fa-money text-navy"></i></a>-->
                                                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                                                <?php else: ?>
                                                <tr class="widget-list-item">
                                                    <td colspan="8">暂无商家信息</td>
                                                </tr><?php endif; ?>
                                            </tbody>
                                        </table>
                                        <div class="js-list-empty-region"></div>
                                    </div>
                                    <div class="js-list-footer-region ui-box">
                                        <div class="widget-list-footer"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo ($Page['pager']); ?>
        </div>
    </div>

</div>

            </div>
        </div>
        <div class="appfooter">

    </div>
<script type="text/javascript">
if(mobilecheck()){
$("#side-menu li").click(function () {
   $("#side-menu li").find('.nav-second-level').css('display','none');
   $(this).find('.nav-second-level').css('display','block').css('min-width','140px');
 });
}
</script>
</body>

    <div class="modal inmodal" tabindex="-1" id="top_up">
        <div class="modal-dialog">
            <div class="modal-content animated bounceInRight">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="modal-header">
                        <button type="button" class="close _close"><span>×</span><span class="sr-only">Close</span>
                        </button>
                        <h4 class="modal-title"></h4>
                    </div>
                    <div class="modal-body">
                        <div class="setting_rows">
                            <div id="wxActionBox" class="wxpay_box">
                                <div class="form-group">
                                    <label>充值金额：</label>
                                    <input type="number" name="price" placeholder="输入要充值的金额" value="0"
                                           class="form-control">
                                    <input type="hidden" name="id" value="0">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white _close" data-dismiss="modal">取消</button>
                        <button type="button" class="btn btn-primary btn-confirm">确定</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="/Static/SX/js/footable.all2.min.js"></script>
    <script src="/Static/SX/js/plugins/icheck.min.js"></script>
    <script type="text/javascript">
        $('.setWithdrawRate').on('click',function(e){
            var _this = this;
            var id = $(this).attr('data-id');
            var rate = $(this).attr('data-rate');
            console.log(rate);
            layer.prompt({
                value:rate,
                title:'商家提现费率设置'
            },function(val){
                layer.msg('正在修改商家提现费率', {
                    icon: 16,
                    shade: 0.5,
                    time:0
                });
                $.post('<?php echo U("/SX/Users/modifyWithDraw");?>',{
                    userId:id,
                    rate:val
                },function(resp){
                    setTimeout(function(){
                        layer.closeAll();
                        if(resp.code == 200){
                            layer.msg('修改成功');
                            $(_this).attr('data-rate',val);
                        }else{
                            layer.alert(resp.msg);
                        }
                    },500);
                });
            });
        });
        $('#table-list-body .prelative .tips').click(function () {
            if ($(this).hasClass('fedit')) {
                var uid = $(this).parent().siblings('.uid').text();
                uid = parseInt($.trim(uid));
                var vv = $(this).siblings('.form-control').val();
                vv = $.trim(vv);
                if (!vv) {
                    swal({title: "温馨提示", text: '没填写内容！', type: "error"});
                    return false;
                } else {
                    var _this = $(this);
                    $.post('<?php echo U("SX/Users/mdfyName");?>', {uid: uid, un: vv}, function (ret) {
                        if (ret.status == '1') {
                            _this.siblings('.wxname').text(vv);
                        } else {
                            swal({title: "温馨提示", text: '修改失败！', type: "error"});
                        }
                        _this.siblings('.wxname').show();
                        _this.siblings('.form-control').hide();
                        _this.find('span').hide();
                        _this.removeClass('fedit');
                    }, 'JSON');
                }
            } else {
                $(this).siblings('.wxname').hide();
                var wxname = $(this).siblings('.wxname').text();
                $(this).siblings('.form-control').val(wxname).show();
                $(this).find('span').show();
                $(this).addClass('fedit');
            }
        });

        function htmlToArray(data) {
            data = data.split('&');
            var info = {};
            $.each(data, function (k, v) {
                v = v.replace('%5D', '').split('=');
                var s = v[0].split('%5B');
                typeof(info[s[0]]) == 'undefined' && (info[s[0]] = {}), info[s[0]] = v[1];
            });
            return info;
        }

        function showtopup(name, id) {
            $('.modal-title').html(name);
            $('#top_up input[name=id]').val(id);
            $('#top_up').show();
        }

        $("#top_up ._close").click(function () {
            $('.modal-title').html("");
            $('#top_up input[name=id]').val("");
            $('#top_up').hide();
        });

        $('.btn-confirm').click(function () {
            var payConfigData = $(this).parents('form').serialize();
            $.post('<?php echo U("SX/Users/topup");?>', {data: htmlToArray(payConfigData)}, function (result) {
                if (result.status == 1) {
                    swal({title: "温馨提示", text: '充值成功', type: "success"}, function () {
                        window.location.reload();
                    });
                } else {
                    swal({title: "温馨提示", text: '充值失败', type: "error"});
                }
            }, 'json');
        });

        $(document).ready(function () {
            $('#search').click(function () {
                var username = $("#username").val();
                window.location.href = "<?php echo U('SX/Users/merLists');?>?username=" + username;
            });

            $('#listfootable').footable();
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });

            $('.issp').on('ifChanged', function () {
                var isselect = 0;
                var uid = $(this).attr("data-type");
                uid = parseInt($.trim(uid));
                if ($(this).is(':checked')) {
                    isselect = 1;
                } else {
                    isselect = 0;
                }
                $.post('<?php echo U("SX/Users/setIssp");?>', {uid: uid, isselect: isselect}, function (ret) {
                    if (ret.status == -1) {
                        swal({title: "温馨提示", text: '修改失败！', type: "error"});
                    }
                });
                return false;
            });

            $('.setType').on('ifChanged', function () {
                var isselect = 0;
                var uid = $(this).attr("data-type");
                uid = parseInt($.trim(uid));
                if ($(this).is(':checked')) {
                    isselect = 1;
                } else {
                    isselect = 0;
                }
                $.post('<?php echo U("SX/Users/setType");?>', {uid: uid, isselect: isselect}, function (ret) {
                    if (ret.status == -1) {
                        swal({title: "温馨提示", text: '修改失败！', type: "error"});
                    }
                });
                return false;
            });

        });
        $('#addMerchant').click(function(){
            window.location.href = '<?php echo U("SX/Index/showAddMerchant");?>';
        });
    </script>

</html>