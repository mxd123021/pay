<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>结算管理 - 网站管理后台</title>
        <meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
	<meta name="format-detection" content="telephone=no">
	<link href="/Static/SX/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Static/SX/css/font-awesome.css" rel="stylesheet">
	<link href="/Static/SX/css/sweetalert.css" rel="stylesheet">
    <link href="/Static/SX/css/animate_new.css" rel="stylesheet">
    <link href="/Static/SX/css/style.css" rel="stylesheet">
	<link href="/Static/SX/css/app.css" rel="stylesheet">
	<!-- Mainly scripts -->
	<script src="/Static/SX/js/jquery-2.1.1.js"></script>
	<script src="/Static/SX/js/bootstrap.min.js"></script>
    <script src="/Static/SX/js/jquery.metisMenu.js"></script>
    <script src="/Static/SX/js/jquery.slimscroll.min.js"></script>
	<!-- Custom and plugin javascript -->
    <script src="/Static/SX/js/inspinia.js"></script>
    <script src="/Static/SX/js/pace.min.js"></script>
	<script src="/Static/SX/js/sweetalert.min.js"></script>
	<script src="/Static/SX/js/jquery.qrcode.min.js"></script>
    <!----开放式头部，请在自己的页面加上--</head>-->
    
<link href="/Static/SX/css/wxCoupon.css" rel="stylesheet">

<link href="/Static/SX/css/dataTables/datepicker3.css" rel="stylesheet">
<link href="/Static/SX/css/footable.core.css" rel="stylesheet">
<link href="/Static/SX/css/cashier.css" rel="stylesheet">
<link href="/Static/SX/css/plugins/ui.jqgrid.css" rel="stylesheet">
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
    <?php $usId = session('SX_USERS.usId');?>
<nav role="navigation" class="navbar-default navbar-static-side">
        <div class="sidebar-collapse">
            <ul id="side-menu" class="nav metismenu">
                <li class="nav-header">
                    <div class="dropdown profile-element"> <span>
							
                                                        <i class="fa fa-pied-piper-alt" style="font-size:60px;color:#1ab394;"></i>
                         </span>
                        <a href="javascript:;" class="dropdown-toggle">
                            <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold"><?php echo session('SX_USERS.userName');?></strong>
                             </span> <span class="text-muted text-xs block">商户管理后台</span> </span> </a>
                    </div>
					<div class="logo-element" style="text-align: center;">
						<i class="fa fa-cogs" style="font-size:50px;color:#1ab394;"></i>
							
					</div>
                </li>
                <li <?php if((CONTROLLER_NAME) == "Index"): if((ACTION_NAME) == "index"): ?>class="active"<?php endif; endif; ?>>
                    <a href="<?php echo U("Manage/Index/index");?>"><i class="fa fa-home"></i> <span class="nav-label">首页</span><span class="label label-info pull-right"></span></a>
                </li>

                <?php if(empty($usId) or in_array('wx_zfsz',session('SX_USERS.grant'))): ?><li <?php if((CONTROLLER_NAME) == "Users"): if((ACTION_NAME) != "index"): ?>class="active"<?php endif; endif; ?>>
                        <!--<li <?php if(in_array('Think.const.CONTROLLER_NAME', array('Users', 'Xingye'))): ?>class="active"<?php endif; ?>>-->
	                    <a href="#"><i class="fa fa-gears"></i> <span class="nav-label">商家设置</span></a>
	                    <ul class="nav nav-second-level collapse <?php if((CONTROLLER_NAME) == "Users"): if((ACTION_NAME) != "index"): ?>in<?php endif; endif; ?>">
					<?php if(empty($usId)): ?><li <?php if((CONTROLLER_NAME) == "Users"): if((ACTION_NAME) == "loginLog"): ?>class="active"<?php endif; endif; ?>><a href="<?php echo U("Manage/Users/loginLog");?>">登陆日志</a></li><?php endif; ?>
	                    	<?php if(empty($usId)): ?><li <?php if((CONTROLLER_NAME) == "Users"): if((ACTION_NAME) == "storefront"): ?>class="active"<?php endif; endif; ?>><a href="<?php echo U("Manage/Users/storefront");?>">子商户管理</a></li><?php endif; ?>
	                        <!--<?php if(empty($usId)): ?>-->
	                        	<!--<li <?php if((CONTROLLER_NAME) == "Users"): if((ACTION_NAME) == "employers"): ?>class="active"<?php endif; endif; ?>><a href="<?php echo U("Manage/Users/employers");?>">员工管理</a></li>-->
	                        <!--<?php endif; ?>-->
	                        <?php if(empty($usId) or in_array('wx_zfsz',session('SX_USERS.grant'))): ?><li <?php if((CONTROLLER_NAME) == "Users"): if((ACTION_NAME) == "payConfig"): ?>class="active"<?php endif; endif; ?>><a href="<?php echo U("Manage/Users/payConfig");?>">支付配置</a></li><?php endif; ?>
	                        <?php if(empty($usId) or in_array('wx_usinfo',session('SX_USERS.grant'))): ?><li <?php if((CONTROLLER_NAME) == "Users"): if((ACTION_NAME) == "userInfo"): ?>class="active"<?php endif; endif; ?>><a href="<?php echo U("Manage/Users/userInfo");?>">个人信息</a></li><?php endif; ?>
	                        <?php if(empty($usId) or in_array('wx_usinfo',session('SX_USERS.grant'))): ?><!--<li <?php if((CONTROLLER_NAME) == "Users"): if((ACTION_NAME) == "realname"): ?>class="active"<?php endif; endif; ?>><a href="<?php echo U("Manage/Users/realname");?>">实名认证</a></li>--> 
                                <li <?php if((CONTROLLER_NAME) == "Xingye"): if((ACTION_NAME) == "realname"): ?>class="active"<?php endif; endif; ?>><a href="<?php echo U("Manage/Xingye/realname");?>">账户认证</a></li><?php endif; ?>
	                        <?php if(empty($usId)): ?><li <?php if((CONTROLLER_NAME) == "Users"): if((ACTION_NAME) == "modifypwd"): ?>class="active"<?php endif; endif; ?>><a href="<?php echo U("Manage/Users/modifypwd");?>">修改密码</a></li><?php endif; ?>
                                <?php if(empty($usId)): ?><li <?php if((CONTROLLER_NAME) == "Users"): if((ACTION_NAME) == "printerManage"): ?>class="active"<?php endif; endif; ?>><a href="<?php echo U("Manage/Users/printerManage");?>">打印机管理</a></li><?php endif; ?>
<!--                                <?php if(empty($usId)): ?><li <?php if((CONTROLLER_NAME) == "Users"): if((ACTION_NAME) == "accoutConfig"): ?>class="active"<?php endif; endif; ?>><a href="<?php echo U("Manage/Users/accoutConfig");?>">账号配置</a></li><?php endif; ?>-->
	                    </ul>
	                </li><?php endif; ?>

                <?php if(empty($usId) or in_array('wx_sksy',session('SX_USERS.grant')) or in_array('wx_smtk',session('SX_USERS.grant'))): ?><li <?php if((CONTROLLER_NAME) == "Xingye"): ?>class="active"<?php endif; ?>>
	                    <a href="#"><?php if(session('SX_USERS.wx_issp') == 3): ?><i class="fa fa-cny"></i> <span class="nav-label">收银台</span><?php else: ?><i class="fa fa-wechat"></i> <span class="nav-label">微信收银台</span><?php endif; ?><span class="label label-info pull-right">NEW</span></a>
	                    <ul class="nav nav-second-level collapse <?php if((CONTROLLER_NAME) == "Xingye"): if((ACTION_NAME) != "payment"): ?>in<?php endif; endif; ?>">

							<?php if(empty($usId) or in_array('wx_sksy',session('SX_USERS.grant'))): ?><li <?php if((CONTROLLER_NAME) == "Xingye"): if((ACTION_NAME) == "payment"): if(($type) != "2"): ?>class="active"<?php endif; endif; endif; ?>><a href="<?php echo U("Manage/Xingye/payment/type/1");?>">刷卡收银</a></li><?php endif; ?>
                            <?php if(empty($usId) or in_array('wx_ewmsy',session('SX_USERS.grant'))): ?><li <?php if((CONTROLLER_NAME) == "Xingye"): if((ACTION_NAME) == "ewmpay"): ?>class="active"<?php endif; endif; ?>><a href="<?php echo U("Manage/Xingye/ewmpay");?>">二维码收银</a></li><?php endif; ?>
<!--                                <?php if($usId == 41): ?><li <?php if((CONTROLLER_NAME) == "Wxcashier"): if((ACTION_NAME) == "payment"): if(($type) == "2"): ?>class="active"<?php endif; endif; endif; ?>><a href="<?php echo U("Manage/Xingye/payment/type/2");?>">扫码退款</a></li><?php endif; ?>-->
<!--	                        <?php if(empty($usId) or in_array('wx_smtk',session('SX_USERS.grant'))): ?><li <?php if((CONTROLLER_NAME) == "Wxcashier"): if((ACTION_NAME) == "payment"): if(($type) == "2"): ?>class="active"<?php endif; endif; endif; ?>><a href="<?php echo U("Manage/Xingye/payment/type/2");?>">扫码退款</a></li><?php endif; ?>-->
                                
	                    </ul>
	                </li>

                    <!-- <li <?php if((CONTROLLER_NAME) == "Wxcashier"): ?>class="active"<?php endif; ?>>
                        <a href="#"><?php if(session('SX_USERS.wx_issp') == 3): ?><i class="fa fa-cny"></i> <span class="nav-label">收银台</span><?php else: ?><i class="fa fa-wechat"></i> <span class="nav-label">微信收银台</span><?php endif; ?><span class="label label-info pull-right">NEW</span></a>
                        <ul class="nav nav-second-level collapse <?php if((CONTROLLER_NAME) == "Wxcashier     "): if((ACTION_NAME) != "payment"): ?>in<?php endif; endif; ?>">

                            <?php if(empty($usId) or in_array('wx_sksy',session('SX_USERS.grant'))): ?><li <?php if((CONTROLLER_NAME) == "Wxcashier"): if((ACTION_NAME) == "payment"): if(($type) != "2"): ?>class="active"<?php endif; endif; endif; ?>><a href="<?php echo U("Manage/Wxcashier/payment/type/1");?>">刷卡收银</a></li><?php endif; ?>
                            <?php if(empty($usId) or in_array('wx_ewmsy',session('SX_USERS.grant'))): ?><li <?php if((CONTROLLER_NAME) == "Wxcashier"): if((ACTION_NAME) == "ewmpay"): ?>class="active"<?php endif; endif; ?>><a href="<?php echo U("Manage/Wxcashier/ewmpay");?>">二维码收银</a></li><?php endif; ?>
                            <?php if(empty($usId) or in_array('wx_smtk',session('SX_USERS.grant'))): ?><li <?php if((CONTROLLER_NAME) == "Wxcashier"): if((ACTION_NAME) == "payment"): if(($type) == "2"): ?>class="active"<?php endif; endif; endif; ?>><a href="<?php echo U("Manage/Wxcashier/payment/type/2");?>">扫码退款</a></li><?php endif; ?>
                        </ul>
                    </li> --><?php endif; ?>

<!--				<?php if(empty($usId) or in_array('orderlists',session('SX_USERS.grant')) or in_array('rporderlists',session('SX_USERS.grant'))): ?><li <?php if((CONTROLLER_NAME) == "Members"): ?>class="active"<?php endif; ?>>
	                    <a href="#"><i class="fa fa-user"></i></i> <span class="nav-label">会员管理</span></a>
	                    <ul class="nav nav-second-level collapse <?php if((CONTROLLER_NAME) == "Members  "): endif; ?>">
							<?php if(empty($usId) or in_array('rporderlists',session('SX_USERS.grant'))): ?><li <?php if((CONTROLLER_NAME) == "Members"): if((ACTION_NAME) == "memberGrade"): if(($type) != "2"): ?>class="active"<?php endif; endif; endif; ?>><a href="<?php echo U("Manage/Members/memberGrade");?>">会员等级</a></li><?php endif; ?>
	                    </ul>
	                </li><?php endif; ?>-->

<!--                <?php if(empty($usId) or in_array('business',session('SX_USERS.grant')) or in_array('redpack',session('SX_USERS.grant'))): ?><li <?php if((CONTROLLER_NAME) == "Wxmarket"): ?>class="active"<?php endif; ?>>
	                    <a href="#"><i class="fa fa-comments-o"></i> <span class="nav-label">微信营销</span><span class="label badge-danger pull-right">HOT</span></a>
	                    <ul class="nav nav-second-level collapse <?php if((CONTROLLER_NAME) == "Wxcashier  "): ?>in<?php endif; ?>">
							<?php if(empty($usId) or in_array('wx_sksy',session('SX_USERS.grant'))): ?><li <?php if((CONTROLLER_NAME) == "Wxmarket"): if((ACTION_NAME) == "wxmemberCard"): if(($type) != "2"): ?>class="active"<?php endif; endif; endif; ?>><a href="<?php echo U("Manage/Wxmarket/wxmemberCard");?>">微信会员卡</a></li>
	                        	<li <?php if((CONTROLLER_NAME) == "Wxmarket"): if((ACTION_NAME) == "business"): if(($type) != "2"): ?>class="active"<?php endif; endif; endif; ?>><a href="<?php echo U("Manage/Wxmarket/business");?>">微信社区</a></li>
	                        	<li <?php if((CONTROLLER_NAME) == "Wxmarket"): if((ACTION_NAME) == "redpack"): if(($type) != "2"): ?>class="active"<?php endif; endif; endif; ?>><a href="<?php echo U("Manage/Wxmarket/redpack");?>">红包营销</a></li><?php endif; ?>
	                    </ul>
	                </li><?php endif; ?>-->

				<?php if(empty($usId) or in_array('orderlists',session('SX_USERS.grant')) or in_array('rporderlists',session('SX_USERS.grant'))): ?><li <?php if((CONTROLLER_NAME) == "Statistics"): ?>class="active"<?php endif; ?>>
	                    <a href="#"><i class="fa fa-line-chart"></i></i> <span class="nav-label">数据统计</span></a>
	                    <ul class="nav nav-second-level collapse <?php if((CONTROLLER_NAME) == "Statistics  "): endif; ?>">

							<?php if(empty($usId) or in_array('orderlists',session('SX_USERS.grant'))): ?><li <?php if((CONTROLLER_NAME) == "Statistics"): if((ACTION_NAME) == "orderLists"): if(($type) != "2"): ?>class="active"<?php endif; endif; endif; ?>><a href="<?php echo U("Manage/Statistics/orderLists");?>">收款订单列表</a></li><?php endif; ?>
                                
                                <!--<?php if(empty($usId) or in_array('orderlists',session('SX_USERS.grant'))): ?>-->
	                        	<!--<li <?php if((CONTROLLER_NAME) == "Statistics"): if((ACTION_NAME) == "balance"): if(($type) != "2"): ?>class="active"<?php endif; endif; endif; ?>><a href="<?php echo U("Manage/Statistics/balance");?>">结算管理</a></li>-->
	                        <!--<?php endif; ?>-->

<!--							<?php if(empty($usId) or in_array('rporderlists',session('SX_USERS.grant'))): ?><li <?php if((CONTROLLER_NAME) == "Statistics"): if((ACTION_NAME) == "rporderLists"): if(($type) != "2"): ?>class="active"<?php endif; endif; endif; ?>><a href="<?php echo U("Manage/Statistics/rporderLists");?>">红包领取列表</a></li><?php endif; ?>-->
	                    </ul>
	                </li><?php endif; ?>

<!--				<?php if(empty($usId) or in_array('orderlist',session('SX_USERS.grant'))): ?><li <?php if((CONTROLLER_NAME) == "Money"): ?>class="active"<?php endif; ?>>
	                    <a href="#"><i class="fa fa-money"></i></i> <span class="nav-label">财务管理</span></a>
	                    <ul class="nav nav-second-level collapse <?php if((CONTROLLER_NAME) == "Money  "): endif; ?>">
							<?php if(session('SX_USERS.wx_issp') == 3): ?><li <?php if((ACTION_NAME) == "account"): ?>class="active"<?php endif; ?>><a href="<?php echo U('Manage/Money/account');?>">账户管理</a></li><?php endif; ?>
							<?php if(session('SX_USERS.wx_issp') == 2): ?><li <?php if((ACTION_NAME) == "dsaccount"): ?>class="active"<?php endif; ?>><a href="<?php echo U('Manage/Money/dsaccount');?>">代收管理</a></li><?php endif; ?>
							<?php if(empty($usId) or in_array('orderlist',session('SX_USERS.grant'))): ?><li <?php if((CONTROLLER_NAME) == "Money"): if((ACTION_NAME) == "payment"): ?>class="active"<?php endif; endif; ?>><a href="<?php echo U("Manage/Money/payment");?>">在线支付预付款</a></li><?php endif; ?>

							<?php if(empty($usId) or in_array('orderlist',session('SX_USERS.grant'))): ?><li <?php if((CONTROLLER_NAME) == "Money"): if((ACTION_NAME) == "paylist"): ?>class="active"<?php endif; endif; ?>><a href="<?php echo U("Manage/Money/paylist");?>">财务明细</a></li><?php endif; ?>
	                    </ul>
	                </li><?php endif; ?>-->

                <?php if(session('SX_USERS.userType') == 1): ?><li <?php if((CONTROLLER_NAME) == "Agent"): ?>class="active"<?php endif; ?>>
	                    <a href="#"><i class="fa fa-group"></i> <span class="nav-label">受理商功能</span><span class="label label-info pull-right">NEW</span></a>
	                    <ul class="nav nav-second-level collapse <?php if((CONTROLLER_NAME) == "Agent  "): ?>in<?php endif; ?>">
	                        <li <?php if((CONTROLLER_NAME) == "Agent"): if((ACTION_NAME) == "merchants"): if(($type) != "2"): ?>class="active"<?php endif; endif; endif; ?>><a href="<?php echo U('Manage/Agent/merchants')?>">商户管理</a></li>

							<li <?php if((CONTROLLER_NAME) == "Agent"): if((ACTION_NAME) == "orderLists"): if(($type) != "2"): ?>class="active"<?php endif; endif; endif; ?>><a href="<?php echo U('Manage/Agent/orderLists')?>">商户订单列表</a></li>
	                    </ul>
	                </li><?php endif; ?>

<!--                <?php if(empty($usId)): ?><li <?php if((CONTROLLER_NAME) == "Salesman"): ?>class="active"<?php endif; ?>>
	                    <a href="#"><i class="fa fa-group"></i> <span class="nav-label">业务员功能</span><span class="label label-info pull-right">NEW</span></a>
	                    <ul class="nav nav-second-level collapse <?php if((CONTROLLER_NAME) == "Salesman"): ?>in<?php endif; ?>">
	                        <li <?php if((CONTROLLER_NAME) == "Salesman"): if((ACTION_NAME) == "merchants"): if(($type) != "2"): ?>class="active"<?php endif; endif; endif; ?>><a href="<?php echo U('Manage/Agent/merchants')?>">商户管理</a></li>

							<li <?php if((CONTROLLER_NAME) == "Agent"): if((ACTION_NAME) == "orderLists"): if(($type) != "2"): ?>class="active"<?php endif; endif; endif; ?>><a href="<?php echo U('Manage/Agent/orderLists')?>">商户订单列表</a></li>
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
<!--			   <li class="dropdown" id="help-link">
                    <a class="dropdown-toggle count-info" href="/merchants.php?m=Index&c=help&a=index" title="帮助文档" target="_blank">
                        <i class="fa  fa-question-circle"></i> <span class="label label-warning">16</span>
                    </a>
                </li>-->

                <li>
                    <a  href="<?php echo U("Manage/Index/logout");?>">
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
                    <h2>结算管理</h2>
                    <ol class="breadcrumb">
                        <li><a href="<?php echo U("Manage/Index/index");?>">后台首页</a></li><li>数据统计</li>
                        <li class="active">
                            <strong>结算管理</strong>
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
				       		<ul class="pull-left" style="height: 25px; padding: 0;">
				       			<li style="width: 150px;">
                                                            <select name="mendian" id="mendian" class="form-control">
                                                                <option value="0" data-cname="全部">全部门店</option>
                                                                <?php foreach($mendians as $ckk=>$cvv){?>
                                                                <option value="<?php echo $cvv['storeId']?>" data-cname="<?php echo $cvv['branch_name']?>" <?php if($cvv['storeId'] == $mendian){?>selected="selected"<?php }?>><?php echo $cvv['branch_name']?></option>
                                                                <?php }?>
                                                           </select>
                                                            <input type="text" class="form-control input-sm m-b-xs" id="filter" placeholder="门店/收银员" onkeydown="doSearch(arguments[0]||event)" style="display: none">
				       			</li>
				       		</ul>
                                                <ul class="pull-left" style="height: 30px; padding: 0;">
                                                                <li>
                                                                    <select name="paytype" id="paytype" class="form-control" onchange="">
                                                                        <option value="0" data-cname="全部类型" <?php if($paytype == 0){?>selected="selected"<?php }?>>全部类型</option>
                                                                            <option value="1" data-cname="微信支付"  <?php if($paytype == 1){?>selected="selected"<?php }?>>微信支付</option>
                                                                            <option value="2" data-cname="支付宝支付"  <?php if($paytype == 2){?>selected="selected"<?php }?>>支付宝支付</option>
                                                                       </select>
                                                                </li>
                                                                </ul>
					        <ul class="pull-right" style="height: 30px;">
<!--						        <li>
									<div data-toggle="buttons" class="btn-group" style="margin-bottom: 30px;">
                                    <label class="btn btn-sm btn-white <?php if(($datetype) == "tdy"): ?>active<?php endif; ?>" onclick="datebtn('tdy')">
                                        <input type="radio" >今日</label>
                                    <label class="btn btn-sm btn-white <?php if(($datetype) == "ydy"): ?>active<?php endif; ?>" onclick="datebtn('ydy')">
                                        <input type="radio">昨日</label>
                                    <label class="btn btn-sm btn-white <?php if(($datetype) == "wk"): ?>active<?php endif; ?>" onclick="datebtn('wk')">
                                        <input type="radio">最近一周</label>
                                	</div>
	                            </li>-->
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
	                            	<button id="balancesearch" type="button" class="btn btn-primary btn-sm" style="margin-bottom: 15px;">查询</button>
	                            	<button id="downbalancedetail" type="button" class="btn btn-danger btn-sm" style="margin-bottom: 15px;">下载</button>
	                            </li>
					        </ul>
				       </div> 
                                            <div>
                                                <div class="realtime-title">注意：此表中的数据是以本商户后台的数据统计生成，费率如无特别注明，均是以0.6%的费率计算。</div>               
                                                <div class="realtime-title">如商户使用了银行系统进行过退款等操作，会存在数据差异，存在数据差异时，均以银行系统为准。</div>               
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
 <script>
 	if(is_mobile()){
	  $('.row .col-lg-12').css('padding','1px');
	  $('.float-e-margins .ibox-content').css('padding','15px 5px 20px 5px');
	  $('.nav-tabs li a').css('padding','10px');
  }
 $(document).ready(function(){
	$('.ui-table-list').footable();

	$('#balancesearch').click(function(){
		var stime = $("#stime").val();
		var etime = $("#etime").val();
                if(Date.parse(new Date(etime)) - Date.parse(new Date(stime)) > 31*24*60*60*1000){
                    alert('只能查询最近一个月的数据！')
                    return;
                }
                var mendian = $("#mendian").val();
                var paytype = $("#paytype").val();
                searchtimedCount();//3秒间隔
		window.location.href="<?php echo U('Manage/Statistics/balance');?>?stime="+stime+"&etime="+etime+"&mendian="+mendian+"&paytype="+paytype;
	});

	$('#downbalancedetail').click(function(){
		var stime = $("#stime").val();
		var etime = $("#etime").val();
                downtimedCount();//5秒间隔
                
		window.open("<?php echo U('Manage/Statistics/downbalancedetail');?>?stime="+stime+"&etime="+etime);
	});
       
        function downtimedCount()
        {
            document.getElementById("downbalancedetail").disabled = true;
            setTimeout("document.getElementById('downbalancedetail').disabled = false;",5000);
        }
        
        function searchtimedCount()
        {
            document.getElementById("balancesearch").disabled = true;
            setTimeout("document.getElementById('balancesearch').disabled = false;",3000);
        }

        function reset()
        {
            document.getElementById('downbalancedetail').disabled = false;
        }

	$("#datepicker.input-daterange").datepicker({
		keyboardNavigation: false,
		forceParse: false,
		format: "yyyy-mm-dd",
		autoclose: true
	});

    $.jgrid.defaults.styleUI = "Bootstrap";
    var a = [
		<?php  if(!empty($balanceList)){ foreach($balanceList as $ovv){ echo "{balanceTime:'".date('Y-m-d',$ovv['balanceTime'])."',"; echo "startTime:'".date('Y-m-d',$ovv['startTime'])."',"; echo "totalMoney:'".$ovv['totalMoney']."',"; echo "count:'".$ovv['count']."',"; echo "refundMoney:'".$ovv['refundMoney']."',"; echo "refundCount:'".$ovv['refundCount']."',"; echo "haspayMoney:'".$ovv['haspayMoney']."',"; echo "fee:'".$ovv['fee']."',"; echo "incomeMoney:'".$ovv['incomeMoney']."'},"; } } ?>
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
        colNames: ["划账日期", "交易时间", "交易金额", "交易笔数", "退款金额", "退款笔数", "支付净额", "手续费金额", "划账金额"],
        colModel: [{
            name: "balanceTime",
            index: "balanceTime",
            width: 115,
            
        }, {
            name: "startTime",
            index: "startTime",
            width: 150
        }, {
            name: "totalMoney",
            index: "totalMoney",
            width: 90,
            formatter : "number",
            formatoptions : {
                decimalSeparator : ".",
                thousandsSeparator : ",",
                decimalPlaces : 2,
                defaulValue : 0
            }
        }, {
            name: "count",
            index: "count",
            width: 60,            
        }, {
            name: "refundMoney",
            index: "refundMoney",
            width: 60,
            formatter : "number",
            formatoptions : {
                decimalSeparator : ".",
                thousandsSeparator : ",",
                decimalPlaces : 2,
                defaulValue : 0
            }
        }, {
            name: "refundCount",
            index: "refundCount",
            width: 50,
        }, {
            name: "haspayMoney",
            index: "haspayMoney",
            width: 70,
            align: "right",
            formatter : "number",
            formatoptions : {
                decimalSeparator : ".",
                thousandsSeparator : ",",
                decimalPlaces : 2,
                defaulValue : 0
            }
        }, {
            name: "fee",
            index: "fee",
            width: 70,
            align:"center",
            formatter : "number",
            formatoptions : {
                decimalSeparator : ".",
                thousandsSeparator : ",",
                decimalPlaces : 2,
                defaulValue : 0
            }
        }, {
            name: "incomeMoney",
            index: "incomeMoney",
            width: 70,
            
            formatter : "number",
            formatoptions : {
                decimalSeparator : ".",
                thousandsSeparator : ",",
                decimalPlaces : 2,
                defaulValue : 0
            }
        }],
        pager: "#pager_list_1",
        viewrecords: true,
        caption: "收款情况",
        hidegrid: false,
        gridComplete:function(){
            
        	var rowNum=parseInt($(this).getGridParam("records"),10);
            if(rowNum>0){
                $(".ui-jqgrid-sdiv").show();
//                var price=$(this).getCol("price",false,"sum");
//                 var searchFiler = $("#filter").val();
//                 if (searchFiler.length === 0) {
//                 	var countIncome = "总计(不含退款)：<?php echo ($countIncome); ?>";
//                 }else{
//                 	var countIncome = "总计";
//                 }
//                $(this).footerData("set",{"orderid":countIncome,"price":price.toFixed(2)+"元"});
            }else{
                $(".ui-jqgrid-sdiv").hide();
            }
        }
    });

});

 	function datebtn(type){
                var mendian = $("#mendian").val();
                var paytype = $("#paytype").val();
 		window.location.href="<?php echo U('Manage/Statistics/orderLists');?>?datetype="+type+"&mendian="+mendian+"&paytype="+paytype;
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
		 f.rules.push({field:"sto",op:"cn",data:searchFiler});
		 f.rules.push({field:"ust",op:"cn",data:searchFiler});
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

	var odurl="<?php echo U('Manage/Statistics/odetail');?>";
 </script>
 <script src="/Static/SX/js/cashier/lhsw.js"></script>

</html>