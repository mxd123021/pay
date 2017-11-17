<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>订单列表 - 网站管理后台</title>
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

<link href="/Static/SX/css/dataTables/datepicker3.css" rel="stylesheet">
<link href="/Static/SX/css/footable.core.css" rel="stylesheet">
<link href="/Static/SX/css/cashier.css" rel="stylesheet">
<link href="/Static/SX/css/plugins/ui.jqgrid.css" rel="stylesheet">
<!--<link href="/Static/SX/css/plugins/bootstrap-datetimepicker.min.css" rel="stylesheet">
<link href="/Static/SX/css/bootstrap.min.css" rel="stylesheet">-->

	<style>
		ul.pull-right li{
			float: left;
			margin-right: 5px;
		}
		.ibox-title h5 {
  			margin: 10px 0 0px;
		}
		select.input-sm {
  			height: 35px;
  			line-height: 35px;
		}
		.float-e-margins .btn-info{
			margin-bottom:0px;
		}
		.fa-paste{
			margin-right:7px;
			padding: 0px;
		}
		.dz-preview{
			display:none;
		}
		.ibox-title ul{ list-style: outside none none !important; margin: 0; padding: 0;}
		.ibox-title li:nth-child(1) { float: left;width: 30%; }
		.ibox-title li:nth-child(2) { float: left;width: 32%; }
		.ibox-title li:nth-child(3){width: 35%; }
		#commonpage {float: right;margin-bottom: 10px;}
		#table-list-body .btn-st{background-color: #337ab7;border-color: #2e6da4;cursor:auto;}
		#oderinfo{overflow-y: scroll;}
		.float-e-margins .ibox-content{border-style:none;}
		.nav-tabs > li > a:hover,
		.nav-tabs > li > a:focus {
		 background-color: #FFF;
		}
		.nav-tabs li.active  a {border-color:#dddddd #dddddd #fff}
		.nav-tabs li.active  a:hover,.nav-tabs li.active a:focus {border-color:#dddddd #dddddd #fff;background-color:#FFF;}

		.page-cashier tbody tr{height:34px;}
		.ui-jqgrid .ui-jqgrid-title{margin:5px; font-weight: 700;}
		.ui-jqgrid tr.jqgfirstrow{height:0px;}
		.ui-jqgrid .ui-jqgrid-pager, .ui-jqgrid .ui-jqgrid-toppager{border-top:none;}
	</style>
	<script src="/Static/SX/js/footable.all2.min.js"></script>

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
                    <h2>订单列表</h2>
                    <ol class="breadcrumb">
                        <li><a href="<?php echo U("SX/Index/index");?>">后台首页</a></li><li>数据统计</li>
                        <li class="active">
                            <strong>订单列表</strong>
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
				<div class="ibox-content">
				   <div class="app__content js-app-main page-cashier">
				    <div>
				      <!-- 实时交易信息展示区域 -->
				      <div class="cashier-realtime"> 
				       <div class="realtime-title-block clearfix"> 
				       		<ul class="pull-left" style="height: 30px; padding: 0;">
				       			<li style="width: 200px;">
				       				<input type="text" class="form-control input-sm m-b-xs" id="filter" placeholder="订单号" onkeydown="doSearch(arguments[0]||event)">
				       			</li>
				       		</ul>
					        <ul class="pull-right" style="height: 30px;">
						        <li>
									<div data-toggle="buttons" class="btn-group" style="margin-bottom: 30px;">
                                    <label class="btn btn-sm btn-white <?php if(($datetype) == "tdy"): ?>active<?php endif; ?>" onclick="datebtn('tdy')">
                                        <input type="radio" >今日</label>
                                    <label class="btn btn-sm btn-white <?php if(($datetype) == "ydy"): ?>active<?php endif; ?>" onclick="datebtn('ydy')">
                                        <input type="radio">昨日</label>
                                    <label class="btn btn-sm btn-white <?php if(($datetype) == "wk"): ?>active<?php endif; ?>" onclick="datebtn('wk')">
                                        <input type="radio">最近一周</label>
                                	</div>
	                            </li>
	                            <li>
	                            	<div class="input-daterange input-group" id="datepicker">
		                            	<div class="input-group date">
			                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
			                                <input id="stime" type="text" class="input-sm form-control" name="start" value="<?php echo ($starttime); ?>" style="border-radius:0px;">
			                            </div>
		                                <span class="input-group-addon" style="background-color: #FBFBFB"> 至 </span>
										<div class="input-group date">
			                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
			                                <input id="etime" type="text" class="input-sm form-control" name="end" value="<?php echo ($endtime); ?>" style="border-radius:0px;">
			                            </div>
                            		</div>
	                            </li>
	                            <li>
	                            	<button id="datesearch" type="button" class="btn btn-primary btn-sm" style="margin-bottom: 15px;">查询</button>
	                            	<button id="downdetail" type="button" class="btn btn-danger btn-sm" style="margin-bottom: 15px;">下载</button>
	                            </li>
					        </ul>
				       </div> 
				      </div> 
					<div class="js-real-time-region realtime-list-box loading">
				     	<div class="widget-list">
					        <div class="js-list-filter-region clearfix ui-box" style="position: relative;">
					        	<div class="widget-list-filter"></div>
					        </div> 
					        <div class="ui-box">
								<div class="jqGrid_wrapper">
		                            <table id="table_list_1"></table>
		                            <div id="pager_list_1"></div>
		                        </div>
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

<div class="modal inmodal" tabindex="-1" role="dialog"  id="oderinfo">
		<div class="modal-dialog">
			<div class="modal-content animated bounceInRight">
				<div class="modal-header">
                    <button type="button" class="close _close"><span style="font-size: 35px;">×</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">支付详情</h4>
                </div>
				<div class="modal-body">
				</div>
				<div class="modal-footer">
                    <button type="button" class="btn btn-white _close">关闭</button>
                </div>
			</div>
		</div>
	</div>

<script src="/Static/SX/js/plugins/bootstrap-datepicker.js"></script>
<script src="/Static/SX/js/plugins/grid.locale-cn.js"></script>
<script src="/Static/SX/js/plugins/jquery.jqGrid.min.js"></script>
<!--<script src="/Static/SX/js/plugins/bootstrap-datetimepicker.js"></script>
<script src="/Static/SX/js/plugins/bootstrap-datetimepicker.zh-CN.js"></script>
<script src="/Static/SX/js/jquery.min.js"></script>
<script src="/Static/SX/js/bootstrap.min.js"></script>-->
 <script>
 	if(is_mobile()){
	  $('.row .col-lg-12').css('padding','1px');
	  $('.float-e-margins .ibox-content').css('padding','15px 5px 20px 5px');
	  $('.nav-tabs li a').css('padding','10px');
  }
 $(document).ready(function(){
//	$('.ui-table-list').footable();

	$('#datesearch').click(function(){
		var stime = $("#stime").val();
		var etime = $("#etime").val();
		window.location.href="<?php echo U('SX/Statistics/orderLists');?>?stime="+stime+"&etime="+etime;
	});

	$('#downdetail').click(function(){
		var stime = $("#stime").val();
		var etime = $("#etime").val();
		window.open("<?php echo U('SX/Statistics/downdetail');?>?stime="+stime+"&etime="+etime);
	});

	$("#datepicker.input-daterange").datepicker({
		keyboardNavigation: false,
		forceParse: false,
		format: "yyyy-mm-dd",
		autoclose: true
	});
        
//        $("#datepicker.input-daterange").datetimepicker({
////		language: 'zh-CN',//显示中文
////                format: 'yyyy-mm-dd hh:ii',//显示格式
////                minView: "month",//设置只显示到月份
////                initialDate: new Date(),//初始化当前日期
////                autoclose: true,//选中自动关闭
////                todayBtn: true//显示今日按钮
//		weekStart: 1,
//        todayBtn:  1,
//		autoclose: 1,
//		todayHighlight: 1,
//		startView: 2,
//		forceParse: 0,
//        showMeridian: 1
//		
//	});

    $.jgrid.defaults.styleUI = "Bootstrap";
    var a = [
		<?php  if(!empty($orders)){ foreach($orders as $ovv){ echo "{orderid:'".$ovv['order_id']."',"; if(!empty($ovv['truename'])){ $name = $ovv['truename']; }elseif(!empty($ovv['openid'])){ $name = $ovv['openid']; }else{ $name = '未知客户'; } echo "name:'".$name."',"; $paytime=$ovv['paytime'] > 0 ? $ovv['paytime'] : $ovv['add_time']; echo "paytime:'".date('Y-m-d H:i:s',$paytime)."',"; echo "price:'".$ovv['goods_price']."元',"; if($ovv['pay_way'] == "weixin"){ $source = '微信'; }elseif($ovv['pay_way'] == "alipay"){ $source = '支付宝'; }else{ $source = '其它'; } echo "source:'".$source."',"; if($ovv['refund']==1){ $refund = "<font>退款中</font>"; }elseif($ovv['refund']==2){ $refund = "<font color=\"#2e6da4\">已退款</font>"; }elseif($ovv['refund']==3){ $refund = "<font color=\"#ed5565\">退款失败</font>"; }else{ $refund = "<font color=\"#44b549\">已支付</font>"; } echo "refund:'".$refund."',"; echo "caozuo:'<a class=\"btn btn-white btn-bitbucket\" onclick=\"GetDetail(".$ovv['id'].");\"><i class=\"fa fa-list\"></i></a>'},"; } } ?>
	];
    $("#table_list_1").jqGrid({
        data: a,
        datatype: "local",
        height: "100%",
        footerrow:true, 
        autowidth: true,
        shrinkToFit: true,
        rowNum: 30,
        rowList: [10, 20, 30],
        colNames: ["订单号", "付款人", "付款时间", "付款金额", "来源", "退款情况", "详细"],
        colModel: [{
            name: "orderid",
            index: "orderid",
            width: 115,
            sorttype: "int",
        }, {
            name: "name",
            index: "name",
            width: 150
        }, {
            name: "paytime",
            index: "paytime",
            width: 90
        }, {
            name: "source",
            index: "price",
            width: 30,
        }, {
            name: "price",
            index: "price",
            width: 70,
            align: "right",
            sorttype: "float"
        }, {
            name: "refund",
            index: "refund",
            width: 70,
            align:"center"
        }, {
            name: "caozuo",
            index: "caozuo",
            width: 34,
            sortable: false
        }],
        pager: "#pager_list_1",
        viewrecords: true,
        caption: "收款情况",
        hidegrid: false,
        gridComplete:function(){
        	var rowNum=parseInt($(this).getGridParam("records"),10);
            if(rowNum>0){
                $(".ui-jqgrid-sdiv").show();
                var price=$(this).getCol("price",false,"sum");
                 var searchFiler = $("#filter").val();
                 if (searchFiler.length === 0) {
                 	var countIncome = "总计(不含退款)：<?php echo ($countIncome); ?>";
                 }else{
                 	var countIncome = "总计";
                 }
                $(this).footerData("set",{"orderid":countIncome,"price":price.toFixed(2)+"元"});
            }else{
                $(".ui-jqgrid-sdiv").hide();
            }
        }
    });

});

 	function datebtn(type){
 		window.location.href="<?php echo U('SX/Statistics/orderLists');?>?datetype="+type;
 	}

	var timeoutHnd;
	function doSearch(ev) {
	  if (timeoutHnd)
	    clearTimeout(timeoutHnd);
	    timeoutHnd = setTimeout(gridReload, 500);
	}

	function gridReload(ev) {
		 var searchFiler = $("#filter").val(), grid = $("#table_list_1"), f;
		 if (searchFiler.length === 0) {
		  grid[0].p.search = false;
		  $.extend(grid[0].p.postData,{filters:""});
		 }
		 f = {groupOp:"OR",rules:[]};
		 f.rules.push({field:"orderid",op:"cn",data:searchFiler});
		 grid[0].p.search = true;
		 $.extend(grid[0].p.postData,{filters:JSON.stringify(f)});
		 grid.trigger("reloadGrid",[{page:1,current:true}]);
	}


	var screenH=$(window).height();
	screenH=  screenH-20;
	$('#oderinfo').css('height',screenH);

	function is_mobile(){
		var ua = navigator.userAgent.toLowerCase();
		if ((ua.match(/(iphone|ipod|android|ios|ipad|mobile)/i))){
				return true;
		}else{
			return false;
		}
	}

	var odurl="<?php echo U('SX/Statistics/odetail');?>";
 </script>
 <script src="/Static/SX/js/cashier/lhsw.js"></script>

</html>