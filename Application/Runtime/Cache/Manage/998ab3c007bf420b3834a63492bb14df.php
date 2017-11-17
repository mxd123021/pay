<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>实名认证 - 网站管理后台</title>
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
    
    <link href="/Static/SX/css/footable.core.css" rel="stylesheet">
    <link href="/Static/SX/css/plugins/custom.css" rel="stylesheet">
    <link href="/Static/SX/css/jquery.steps.css" rel="stylesheet">
    <style>
        .fl{float: left; margin-left: 5px;}
        .clearfix:after {
          content: " ";
          display: block;
          clear: both;
          height: 0;
        }
        .clearfix {
          zoom: 1;
        }
        .ibox{
            border: 1px solid #e7eaec;
        }
        .part_item {
            background: none repeat scroll 0 0 #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding-bottom: 15px;
            margin-bottom: 10px;
        }
        .form .part_item p {
            display: inline-block;
            font-size: 14px;
            overflow: hidden;
            padding: 10px 20px 0;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .part_item_b {
            border-top: 1px solid #ccc;
            margin-top: 10px;
        }
        .form .part_item p.active {
            color: #f87b00;
        }
        .part_item input {
            font-size: 14px;
            margin-bottom: 2px;
            margin-right: 5px;
        }
        .pagination{
            margin:0px;
        }
        .mustInput {
            color: red;
            margin-right: 5px;
        }
        @media (min-width: 768px){
            .form .part_item p {
                width: 37%;
            }
        }
        @media (min-width: 992px){
            .form .part_item p {
                width: 24%;
            }
        }
        .dz-preview{
            display:none;
        }
        .code-box{width:172px;height:172px;padding:3px 4px;position:relative;margin-top: 5px;background:#fff;}
        .codebox-tips{background: #44b549;margin: 5px 0 0 0;color: #fff;height:27px;line-height:16px;font-size:14px;text-align: center;padding:5px 6px;width:172px;}
        .codebox-tips i{ display:inline-block;background:url(/Static/SX/images/pay_icon/czzx_btn0723.png) no-repeat 0 -86px;_background:url(/Static/SX/images/pay_icon/czzx_btn0723_png8.png) no-repeat 0 -86px;width:18px;height:16px;margin-right:3px;vertical-align:middle}
        #qr-code img{vertical-align: middle; width: 164px;}
        #qr-code {line-height: 164px;}
        .inp_unselected{color: #B3B3B3;}
        .inp_selected{color: #555;}
    </style>

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
	                    	<!--<?php if(empty($usId)): ?>-->
	                        	<!--<li <?php if((CONTROLLER_NAME) == "Users"): if((ACTION_NAME) == "storefront"): ?>class="active"<?php endif; endif; ?>><a href="<?php echo U("Manage/Users/storefront");?>">子商户管理</a></li>-->
	                        <!--<?php endif; ?>-->
	                        <!--<?php if(empty($usId)): ?>-->
	                        	<!--<li <?php if((CONTROLLER_NAME) == "Users"): if((ACTION_NAME) == "employers"): ?>class="active"<?php endif; endif; ?>><a href="<?php echo U("Manage/Users/employers");?>">员工管理</a></li>-->
	                        <!--<?php endif; ?>-->
	                        <!--<?php if(empty($usId) or in_array('wx_zfsz',session('SX_USERS.grant'))): ?>-->
	                        	<!--<li <?php if((CONTROLLER_NAME) == "Users"): if((ACTION_NAME) == "payConfig"): ?>class="active"<?php endif; endif; ?>><a href="<?php echo U("Manage/Users/payConfig");?>">支付配置</a></li>-->
	                        <!--<?php endif; ?>-->
	                        <?php if(empty($usId) or in_array('wx_usinfo',session('SX_USERS.grant'))): ?><li <?php if((CONTROLLER_NAME) == "Users"): if((ACTION_NAME) == "userInfo"): ?>class="active"<?php endif; endif; ?>><a href="<?php echo U("Manage/Users/userInfo");?>">个人信息</a></li><?php endif; ?>
	                        <?php if(empty($usId) or in_array('wx_usinfo',session('SX_USERS.grant'))): ?><!--<li <?php if((CONTROLLER_NAME) == "Users"): if((ACTION_NAME) == "realname"): ?>class="active"<?php endif; endif; ?>><a href="<?php echo U("Manage/Users/realname");?>">实名认证</a></li>--> 
                                <li <?php if((CONTROLLER_NAME) == "Xingye"): if((ACTION_NAME) == "realname"): ?>class="active"<?php endif; endif; ?>><a href="<?php echo U("Manage/Xingye/realname");?>">账户认证</a></li><?php endif; ?>
	                        <?php if(empty($usId)): ?><li <?php if((CONTROLLER_NAME) == "Users"): if((ACTION_NAME) == "modifypwd"): ?>class="active"<?php endif; endif; ?>><a href="<?php echo U("Manage/Users/modifypwd");?>">修改密码</a></li><?php endif; ?>
                                <!--<?php if(empty($usId)): ?>-->
	                        	<!--<li <?php if((CONTROLLER_NAME) == "Users"): if((ACTION_NAME) == "printerManage"): ?>class="active"<?php endif; endif; ?>><a href="<?php echo U("Manage/Users/printerManage");?>">打印机管理</a></li>-->
	                        <!--<?php endif; ?>-->
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
                    <h2>实名认证</h2>
                    <ol class="breadcrumb">
                        <li><a href="<?php echo U("Manage/Index/index");?>">后台首页</a></li><li>商家设置</li>
                        <li class="active">
                            <strong>实名认证</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>

<div class="wrapper wrapper-content animated fadeInRight">
    
            <div class="row">
                <form id="employersForm" class="form" action="/index.php?s=/Manage/Xingye/realname.html" method="post" enctype="multipart/form-data">
                
                    <div id="wizard" class="wizard clearfix" style="margin-left: 9px;">
                        <div class="steps clearfix">
                            <ul role="tablist">
                                <li role="tab" class="first <?php if($userinfo['userStep'] == 1): ?>current<?php else: ?>done<?php endif; ?>" aria-disabled="false" aria-selected="true"><a id="wizard-t-0" href="#wizard-h-0" aria-controls="wizard-p-0"><span class="current-info audible">审核步骤： </span><span class="number">1.</span> 填写商户信息</a></li>
                                <li role="tab" class="<?php if($userinfo['userStep'] == 2): ?>current<?php elseif($userinfo['userStep'] == 3): ?>done<?php else: ?>disabled<?php endif; ?>" aria-disabled="true"><a id="wizard-t-1" href="#wizard-h-1" aria-controls="wizard-p-1"><span class="number">2.</span> 验证银行信息</a></li>
                                <li role="tab" class="<?php if($userinfo['userStep'] == 3): ?>current<?php else: ?>disabled<?php endif; ?> last" aria-disabled="true"><a id="wizard-t-2" href="#wizard-h-2" aria-controls="wizard-p-2"><span class="number">3.</span> 审核成功</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <?php if($userinfo['userAudit'] == 0): ?><div class="alert alert-info alert-dismissable" style="margin-bottom: 10px;">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>所有必填项(*)填完后将进入审核阶段
                            </div><?php endif; ?>
                        <?php if(($userinfo['userAudit'] == 3) and ($userinfo['reson'] != '')): ?><div class="alert alert-danger alert-dismissable" style="margin-bottom: 10px;">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>失败原因：<?php echo ($userinfo['reson']); ?>
                            </div><?php endif; ?>
                    </div>

                    <input type="hidden" name="step" value="<?php echo ($userinfo['userStep']); ?>">
                
                    <?php if(($userinfo['userStep'] == 1) OR ($userinfo['userStep'] == 3)): ?><div class="col-lg-12">
                            <div class="ibox">
                                <div class="ibox-title">
                                    <h5>商户详细信息</h5>
                                    <?php if($userinfo['userAudit'] == 0): ?><span class="label label-danger pull-right">审核通过后无法修改</span><?php elseif( $userinfo['userAudit'] == 1): ?><span class="label label-primary pull-right">审核通过</span><?php elseif( $userinfo['userAudit'] == 2): ?><span class="label label-warning pull-right">审核中</span><?php elseif( $userinfo['userAudit'] == 3): ?><span class="label label-danger pull-right">审核失败</span><?php endif; ?>
                                </div>
                                <div class="ibox-content">
                                    <div class="form-group">
                                        <label><span class="mustInput">*</span>店铺/商户简称</label>
                                        <input type="tel" id="zz_jc" placeholder="请输入店铺/商户简称" name="zz_jc" value="<?php echo ($userinfo['zz_jc']); ?>" class="form-control required mustInput" <?php if($userinfo['userAudit'] == 1 or $userinfo['userAudit'] == 2){ echo "disabled";}?>>
                                    </div>
                                    <div class="form-group">
                                        <label><span class="mustInput">*</span>商户类型</label><font color="green">(个体/企业)</font><label>:</label>
                                        <select style="width: 20%" name="zz_jyxz" class="form-control" <?php if($userinfo['userAudit'] == 1 or $userinfo['userAudit'] == 2){ echo "disabled";}?>>
                                            <option value="2" <?php if(($userinfo["zz_jyxz"]) == "2"): ?>selected<?php endif; ?>>个体</option>
                                            <option value="1" <?php if(($userinfo["zz_jyxz"]) == "1"): ?>selected<?php endif; ?>>企业</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label><span class="mustInput">*</span>行业类别</label><label>:</label>
                                        <div>
                                            <select style="width: 20%; float: left;" class="form-control" onchange="getGdCategory(this)" <?php if($userinfo['userAudit'] == 1 or $userinfo['userAudit'] == 2){ echo "disabled";}?>>
                                                <option value="-1">请选择</option>
                                                <option value="个体工商户" <?php if(($incategory["namearr"]["0"]) == "个体工商户"): ?>selected<?php endif; ?>>个体工商户</option>
                                                <option value="企业" <?php if(($incategory["namearr"]["0"]) == "企业"): ?>selected<?php endif; ?>>企业</option>
                                                <option value="事业单位" <?php if(($incategory["namearr"]["0"]) == "事业单位"): ?>selected<?php endif; ?>>事业单位</option>
                                            </select>
                                            <div id="incatory" style="float: left; width: 80%;">
                                                <?php if($incategory != ''): ?><select style="float:left; width:20%" class="form-control" onchange="getGdCategory2(this);" <?php if($userinfo['userAudit'] == 1 or $userinfo['userAudit'] == 2){ echo "disabled";}?>>
                                                        <option value="-1">请选择</option>
                                                        <?php foreach($incategory['level2'] as $in=>$iv){ $name = explode("-",$iv['name']); if(in_array($name[1],$names)==false) { $names[] = $name[1]; $sel = ""; if($name[1] == $incategory['namearr'][1]){ $sel = "selected"; } echo "<option value='$name[1]' $sel>$name[1]</option>"; } }?>
                                                    </select>
                                                    <div id="incatory2" style="float:left">
                                                        <select class="form-control" name="incode" <?php if($userinfo['userAudit'] == 1 or $userinfo['userAudit'] == 2){ echo "disabled";}?>>
                                                            <option value="-1">请选择</option>
                                                            <?php foreach($incategory['level3'] as $in=>$iv){ $name = explode("-",$iv['name']); $sel = ""; if($iv[code] == $incategory['code']){ $sel = "selected"; } echo "<option value='$iv[code]' $sel>$name[2]</option>"; }?>
                                                        </select>
                                                    </div><?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label><span class="mustInput">*</span>联系地址</label><font color="green">(与营业执照登记的注册地址一致)</font><label>:</label>
                                        <input type="tel" id="zz_zcdz" placeholder="注册/地址" name="zz_zcdz" value="<?php echo ($userinfo['zz_zcdz']); ?>" class="form-control mustInput" <?php if($userinfo['userAudit'] == 1 or $userinfo['userAudit'] == 2){ echo "disabled";}?>>
                                    </div>
                                    
<!--                                    <div class="form-group" style="margin-top: 15px;">
                                        <label><span class="mustInput">*</span>开户人/开户名称</label><font color="green">(姓名需与证件上的姓名一致,对私账户填开户人名称/对公账户填公司名称)</font><label>:</label>
                                        <input type="tel" id="zz_name" placeholder="请输入证件持有人姓名" name="zz_name" value="<?php echo ($userinfo['zz_name']); ?>" class="form-control required mustInput" <?php if($userinfo['userAudit'] == 1 or $userinfo['userAudit'] == 2){ echo "disabled";}?>>
                                    </div>
                                    <div class="form-group">
                                        <label><span class="mustInput">*</span>开户人证件类型</label><font color="green">(身份证/护照，个体工商户只能提供法人本人的证件)</font><label>:</label>
                                        <select name="zz_sftype" class="form-control mustInput" <?php if($userinfo['userAudit'] == 1 or $userinfo['userAudit'] == 2){ echo "disabled";}?>>
                                            <option value="1" <?php if(($userinfo["zz_sftype"]) == "1"): ?>selected<?php endif; ?>>身份证</option>
                                            <option value="2" <?php if(($userinfo["zz_sftype"]) == "2"): ?>selected<?php endif; ?>>护照</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label><span class="mustInput">*</span>证件号码</label><font color="green">(如 51010719881111****)</font><label>:</label>
                                        <input type="tel" id="zz_sfz" placeholder="请输入企业法人/经办人证件号码" name="zz_sfz" value="<?php echo ($userinfo['zz_sfz']); ?>" class="form-control required mustInput" <?php if($userinfo['userAudit'] == 1 or $userinfo['userAudit'] == 2){ echo "disabled";}?>>
                                    </div>-->
                                    <div class="form-group">
                                        <label><span class="mustInput">*</span>证件有效期</label><font color="green">(如 2035-4-1)</font><label>:</label>
                                        <input type="text" id="zz_yxq" placeholder="请输入正确的证件有效期" name="zz_yxq" value="<?php echo ($userinfo['zz_yxq']); ?>" class="form-control required mustInput" <?php if($userinfo['userAudit'] == 1 or $userinfo['userAudit'] == 2){ echo "disabled";}?>>
                                    </div>
                                    <?php if($userinfo['userAudit'] == 0 or $userinfo['userAudit'] == 3){?>
                                        <div class="form-group uploade">
                                            <label><span class="mustInput">*</span>身份证正面照片</label><span style="margin-left: 20px; color:green;">必须为彩色图片且小于500K,jpg格式</span>
                                            <input type="text" placeholder="身份证正面照片" <?php if( $userinfo['sfzz'] != '' ): ?>value="身份证正面已上传" readonly="readonly" <?php else: ?> value=""<?php endif; ?>  class="form-control mustInput">
                                            <input type="hidden" placeholder="身份证正面照片" value="<?php echo $userinfo['sfzz'];?>" name="sfzz" class="hiddeninput">
                                            <div class="dropz" style="height: 34px;line-height: 34px;border: 1px solid #e5e6e7;width: 70px;text-align: center;position: relative;top: -34px;float: right;cursor: pointer;">文件上传</div>
                                        </div>
                                        <div class="form-group uploade">
                                            <label><span class="mustInput">*</span>身份证反面照片</label><span style="margin-left: 20px; color:green;">必须为彩色图片且小于500K,jpg格式</span>
                                            <input type="text" placeholder="身份证反面照片" <?php if( $userinfo['sfzf'] != '' ): ?>value="身份证反面已上传" readonly="readonly" <?php else: ?> value=""<?php endif; ?>  class="form-control mustInput">
                                            <input type="hidden" placeholder="身份证反面照片" value="<?php echo $userinfo['sfzf'];?>" name="sfzf" class="hiddeninput">
                                            <div class="dropz" style="height: 34px;line-height: 34px;border: 1px solid #e5e6e7;width: 70px;text-align: center;position: relative;top: -34px;float: right;cursor: pointer;">文件上传</div>
                                        </div>
                                        <div class="form-group uploade">
                                            <label>营业执照</label><span style="margin-left: 20px; color:green;">需年检章齐全（当年成立公司除外），必须为彩色图片且不超过500K</span>
                                            <input type="text" placeholder="营业执照" <?php if( $userinfo['yyzz'] != '' ): ?>value="营业执照已上传" readonly="readonly" <?php else: ?> value=""<?php endif; ?> class="form-control" >
                                            <input type="hidden" placeholder="营业执照" value="<?php echo $userinfo['yyzz'];?>" name="yyzz" class="hiddeninput">
                                            <div class="dropz" style="height: 34px;line-height: 34px;border: 1px solid #e5e6e7;width: 70px;text-align: center;position: relative;top: -34px;float: right;cursor: pointer;">文件上传</div>
                                        </div>
                                        <div class="form-group uploade">
                                            <label>组织机构代码证</label><span style="margin-left: 20px; color:green;">必须为彩色图片且小于500K；若你的企业三证合一，此处请再次上传营业执照</span>
                                            <input type="text" placeholder="组织机构代码证" <?php if( $userinfo['zzjg'] != '' ): ?>value="组织机构代码证已上传" readonly="readonly" <?php else: ?> value=""<?php endif; ?> class="form-control">
                                            <input type="hidden" placeholder="组织机构代码证" value="<?php echo $userinfo['zzjg'];?>" name="zzjg" class="hiddeninput">
                                            <div class="dropz" style="height: 34px;line-height: 34px;border: 1px solid #e5e6e7;width: 70px;text-align: center;position: relative;top: -34px;float: right;cursor: pointer;">文件上传</div>
                                        </div>
                                        <div class="form-group uploade">
                                            <label>特殊资质</label><span style="margin-left: 20px; color:green;">特殊行业如许可证，授权书，备案表等，有则必填</span>
                                            <input type="text" placeholder="特殊资质(有则必填)" <?php if( $userinfo['tszz'] != '' ): ?>value="身份证反面已上传" readonly="readonly" <?php else: ?> value=""<?php endif; ?>  class="form-control">
                                            <input type="hidden" placeholder="特殊资质(有则必填)" value="<?php echo $userinfo['tszz'];?>" name="tszz" class="hiddeninput">
                                            <div class="dropz" style="height: 34px;line-height: 34px;border: 1px solid #e5e6e7;width: 70px;text-align: center;position: relative;top: -34px;float: right;cursor: pointer;">文件上传</div>
                                        </div>
                                    <?php }else{ ?>
                                        <div class="form-group uploade">
                                            <label><span class="mustInput">*</span>身份证正面照片</label><span style="margin-left: 20px; color:green;">必须为彩色图片且小于500K,jpg格式</span>
                                            <div><?php if( $userinfo['sfzz'] != '' ): ?><img width="50%" src="/<?php echo ($savePath); ?>/<?php echo ($userinfo['sfzz']); ?>"><?php else: ?>无<?php endif; ?></div>
                                        </div>
                                        <div class="form-group uploade">
                                            <label><span class="mustInput">*</span>身份证反面照片</label><span style="margin-left: 20px; color:green;">必须为彩色图片且小于500K,jpg格式</span>
                                            <div><?php if( $userinfo['sfzf'] != '' ): ?><img width="50%" src="/<?php echo ($savePath); ?>/<?php echo ($userinfo['sfzf']); ?>"><?php else: ?>无<?php endif; ?></div>
                                        </div>
                                        <div class="form-group uploade">
                                            <label>营业执照</label><span style="margin-left: 20px; color:green;">需年检章齐全（当年成立公司除外），必须为彩色图片且不超过500K</span>
                                            <div><?php if( $userinfo['yyzz'] != '' ): ?><img width="50%" src="/<?php echo ($savePath); ?>/<?php echo ($userinfo['yyzz']); ?>"><?php else: ?>无<?php endif; ?></div>
                                        </div>
                                        <div class="form-group uploade">
                                            <label>组织机构代码证</label><span style="margin-left: 20px; color:green;">必须为彩色图片且小于500K；若你的企业三证合一，此处请再次上传营业执照</span>
                                            <div><?php if( $userinfo['zzjg'] != '' ): ?><img width="50%" src="/<?php echo ($savePath); ?>/<?php echo ($userinfo['zzjg']); ?>"><?php else: ?>无<?php endif; ?></div>
                                        </div>
                                        <div class="form-group uploade">
                                            <label>特殊资质</label><span style="margin-left: 20px; color:green;">特殊行业如许可证，授权书，备案表等，有则必填</span>
                                            <div><?php if( $userinfo['tszz'] != '' ): ?><img width="50%" src="/<?php echo ($savePath); ?>/<?php echo ($userinfo['tszz']); ?>"><?php else: ?>无<?php endif; ?></div>
                                        </div>
                                    <?php } ?>
                                    <div class="form-group">
                                        <label>主体全称</label><font color="green">(商户名称需与营业执照登记公司名称一致)</font><label>:</label>
                                        <input type="tel" id="zz_shname" placeholder="请输入企业/商户名称" name="zz_shname" value="<?php echo ($userinfo['zz_shname']); ?>" class="form-control" <?php if($userinfo['userAudit'] == 1 or $userinfo['userAudit'] == 2){ echo "disabled";}?>>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>营业执照注册号:</label>
                                        <input type="tel" id="zz_license" placeholder="请输入营业执照注册号" name="zz_license" value="<?php echo ($userinfo['zz_license']); ?>" class="form-control" <?php if($userinfo['userAudit'] == 1 or $userinfo['userAudit'] == 2){ echo "disabled";}?>>
                                    </div>
                                </div>
                            </div>
                        </div><?php endif; ?>

                    <?php if(($userinfo['userStep'] == 2) OR ($userinfo['userStep'] == 3)): ?><div class="col-lg-12">
                            <div class="ibox">
                                <div class="ibox-title">
                                    <h5>银行信息</h5>                                    
                                    <?php if($userinfo['userAudit'] == 0): ?><span class="label label-danger pull-right">审核通过后无法修改</span>
                                    <?php elseif( $userinfo['userAudit'] == 1): ?>
                                        <span class="label label-primary pull-right">审核通过</span>
                                    <?php elseif( $userinfo['userAudit'] == 2): ?>
                                        <span class="label label-warning pull-right">审核中</span>
                                    <?php elseif( $userinfo['userAudit'] == 3): ?>
                                        <span class="label label-danger pull-right">审核失败</span><?php endif; ?>
                                </div>
                                <div class="ibox-content">
                                    <div class="form-group">
                                        <label><span class="mustInput">*</span>账户类型:</label>
                                        <select name="zz_banktype" class="form-control" <?php if($userinfo['userAudit'] == 1){ echo "disabled";}?>>
                                            <option value="2" <?php if(($userinfo["zz_banktype"]) == "2"): ?>selected<?php endif; ?>>个人账户</option>
                                            <option value="1" <?php if(($userinfo["zz_banktype"]) == "1"): ?>selected<?php endif; ?>>对公账户</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label><span class="mustInput">*</span>开户人/开户名称:</label><font color="green">(对私账户填开户人名称/对公账户填公司名称)</font><label>:</label>
                                        <input id="zz_accountname" placeholder="请输入银行开户时所填名称" name="zz_accountname" value="<?php echo ($userinfo['zz_accountname']); ?>" class="form-control required" <?php if($userinfo['userAudit'] == 1){ echo "disabled";}?>>
                                    </div>
                                    <div class="form-group">
                                        <label><span class="mustInput">*</span>开户银行:</label>
                                        <select id="khbank" name="zz_bank" class="form-control required" <?php if($userinfo['userAudit'] == 1){ echo "disabled";}?>>
                                          <option value="" <?php if(($userinfo["zz_bank"]) == "0"): ?>selected<?php endif; ?>>请选择</option>
                                            <?php foreach($bank as $akk=>$avv){?>
                                              <option value="<?php echo $avv['codeid']?>" <?php if(($userinfo["zz_bank"]) == $avv["codeid"]): ?>selected<?php endif; ?>><?php echo $avv['name']?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label><span class="mustInput">*</span>开户银行全称</label><font color="green">(详细到支行,没找到银行信息请检查所选择的开户地址和开户银行是否正确)</font><label>:</label>
                                        <input onkeyup="getBankBranch(this,event)" onblur="hidebank()" onfocus="areashow()" autocomplete="off" type="tel" id="zz_bankqc" placeholder="开户银行全称" name="zz_bankqc" value="<?php echo ($userinfo['zz_bankqc']); ?>" class="form-control inp_selected required" <?php if($userinfo['userAudit'] == 1){ echo "disabled";}?>>
                                        <div id="bankinfo" style="position: absolute; width: 50%; display: none;">
                                        </div>
                                        <div id="areashow" style="padding-top:5px;min-width:278px;">
                                        </div>
                                        <input id="backcode" name="backcode" value="<?php echo ($userinfo['backcode']); ?>" type="hidden" class="required">
                                    </div>
                                    <!--<div class="clearfix"></div>-->
                                    <div class="form-group" style="margin-top: 15px;">
                                        <label><span class="mustInput">*</span>银行卡开户行所在地区</label><font color="green">(填写结算银行卡开户行地址)</font><label>:</label>
                                        <div>
                                            <select id="gdcity" style="width: 20%; float: left;" class="form-control" onchange="getGdCity(this)" name="fid" <?php if($userinfo['userAudit'] == 1 or $userinfo['userAudit'] == 2){ echo "disabled";}?>>
                                                <option value="-1">请选择</option>
                                                <?php foreach($district as $akk=>$avv){ $sel = ""; if($avv['sid'] == $userinfo['fid']){ $sel = "selected"; } echo "<option value='$avv[sid]' $sel>$avv[name]</option>"; }?>
                                            </select>
                                            <div id="city" style="float: left; width: 80%;">
                                                <?php if(district2 != ''): ?><select id="gdcity2" style="float:left; width:20%" class="form-control" name="sid" <?php if($userinfo['userAudit'] == 1 or $userinfo['userAudit'] == 2){ echo "disabled";}?>>
                                                        <option value="-1">请选择</option>
                                                        <?php foreach($district2 as $akk=>$avv){ $sel = ""; if($avv['sid'] == $userinfo['sid']){ $sel = "selected"; } echo "<option value='$avv[sid]' $sel>$avv[name]</option>"; }?>
                                                    </select><?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label><span class="mustInput">*</span>开户人证件类型</label><font color="green">(身份证/护照，个体工商户只能提供法人本人的证件)</font><label>:</label>
                                        <select name="zz_sftype" class="form-control mustInput" <?php if($userinfo['userAudit'] == 1 or $userinfo['userAudit'] == 2){ echo "disabled";}?>>
                                            <option value="1" <?php if(($userinfo["zz_sftype"]) == "1"): ?>selected<?php endif; ?>>身份证</option>
                                            <option value="2" <?php if(($userinfo["zz_sftype"]) == "2"): ?>selected<?php endif; ?>>护照</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label><span class="mustInput">*</span>证件号码</label><font color="green">(如 51010719881111****)</font><label>:</label>
                                        <input type="tel" id="zz_sfz" placeholder="请输入证件号码" name="zz_sfz" value="<?php echo ($userinfo['zz_sfz']); ?>" class="form-control required mustInput" <?php if($userinfo['userAudit'] == 1 or $userinfo['userAudit'] == 2){ echo "disabled";}?>>
                                    </div>
                                    <div class="form-group">
                                        <label><span class="mustInput">*</span>预留手机号:</label><font color="green">(在银行开卡所填手机号)</font><label>:</label>
                                        <input type="tel" id="bankPhone" placeholder="请输入银行预留手机号" name="bankPhone" value="<?php echo ($userinfo['bankPhone']); ?>" class="form-control required" <?php if($userinfo['userAudit'] == 1){ echo "disabled";}?>>
                                    </div>
                                    <div class="form-group">
                                        <label><span class="mustInput">*</span>结算账户</label><font color="green">(企业需对公账户，个体户提供法人名下4、5、6开头的个人银行卡号)</font><label>:</label>
                                        <input type="tel" id="zz_bankinfo" placeholder="请输入银行账号" name="zz_bankinfo" value="<?php echo ($userinfo['zz_bankinfo']); ?>" class="form-control required" <?php if($userinfo['userAudit'] == 1){ echo "disabled";}?>>
                                    </div>
                                </div>
                            </div>
                        </div><?php endif; ?>

                    <?php if(($userinfo['userAudit'] == 0) OR ($userinfo['userAudit'] == 2) OR ($userinfo['userAudit'] == 3)): ?><div class="col-lg-12">
                            <button type="submit" class="btn btn-primary pull-right">
                                <?php if(($userinfo['userStep'] == 1)): ?>下一步
                                <?php elseif($userinfo['userStep'] == 2): ?>
                                    提交
                                <?php else: ?>
                                    修改<?php endif; ?>
                            </button>
                        </div><?php endif; ?>
                </form>
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

    <div class="modal inmodal" tabindex="-1"  id="bankbranchSet">
        <div class="modal-dialog">
            <div class="modal-content animated bounceInRight">
                <div class="modal-header">
                    <button type="button" class="close _close"><span>×</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">手动输入开户行全称</h4>
                </div>
                <div class="modal-body">
                    <div class="setting_rows">
                        <div id="wxActionBox" class="wxpay_box">
                            <div class="form-group">
                                <label><span class="mustInput">*</span>开户行全称</label>
                                <input id="sdbankname" type="text" placeholder="银行全称(详细到支行)" value="" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>联行号</label>
                                <input id="sdbankbranch" type="text" placeholder="联行号(不知道可不填)" value="" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary _sure">确定</button>
                    <button type="button" class="btn btn-default _close">关闭</button>
                </div>
            </div>
        </div>
    </div>
    <script src="/Static/SX/js/plugins/dropzone.js"></script>
    <script src="/Static/SX/js/plugins/icheck.min.js"></script>
    <script src="/Static/SX/js/jquery.validate.min.js"></script>
    <!-- Page-Level Scripts -->
    <script>
        $(document).ready(function() {
            employers.init();
            $(".dropz").dropzone({
                url: "<?php echo U('Manage/Xingye/img_upload');?>",
                addRemoveLinks: false,
                maxFilesize: 2,
                acceptedFiles: ".jpg,.png,.gif,.jpeg",
                uploadMultiple: false,
                init: function() {
                    this.on("success", function(file,responseText) {
                        if(responseText.status == 1){
                            /***这里的this.element 是 $(".dropz")****/
                            $(this.element).siblings('.form-control').val('文件已上传');
                            $(this.element).siblings('.form-control').attr('readonly','readonly');
                            $(this.element).siblings('.hiddeninput').val(responseText.savepath);
                        }else{
                            swal({title: "温馨提示",text:responseText.error,type: "error"});
                        }
                    });
                }
            });
        });
        !function(a,b){
            var employers = employers || {};
            employers.init = function(){
                var c = employers;
                b('.part_item .checkAll').click(function(){
                    var checkItems = b(this).parents('.part_item_t').siblings('.part_item_b').find('p').find('input[name="authority[]"]');
                    if (b(this).is(':checked') == false) {
                        checkItems.each(function(ke,el){
                            $(el).iCheck('uncheck');
                        });
                    }else{
                        checkItems.each(function(ke,el){
                            $(el).iCheck('check');
                        });
                    }
                });
                jQuery.extend(jQuery.validator.messages, {
                    required: "必填字段",
                    remote: "请修正该字段",
                    email: "请输入正确格式的电子邮件",
                    equalTo: "请再次输入相同的值",
                    maxlength: jQuery.validator.format("请输入一个长度最多是 {0} 的字符串"),
                    minlength: jQuery.validator.format("请输入一个长度最少是 {0} 的字符串"),
                });
                b('#employersForm').validate({
                    errorPlacement: function (error, element){
                            element.before(error);
                    },
                    rules: {
                        bankPhone: {
                            minlength: 11,
                            maxlength: 11
                        },
                        zz_bankinfo:{
                            minlength: 16,
                            maxlength: 20
                        }
                    },messages: {
                        bankPhone: {
                            minlength: "请输入正确的手机号码",
                            maxlength: "请输入正确的手机号码"
                        },
                        zz_bankinfo: {
                            minlength: "请输入正确的银行卡号",
                            maxlength: "请输入正确的银行卡号"
                        }
                    }
                });
            };
            a.employers = employers;
        }(window,jQuery);

    function getGdCategory(obj){
        if(obj.value == -1){
            $("#incatory").html("");
        }else{
            var html='<select style="float:left; width:20%" class="form-control" onchange="getGdCategory2(this);"><option value="-1">请选择</option>'
            $.post('<?php echo U("Manage/Users/getGdCategory");?>',{key:obj.value},function(ret){
                if(ret.data[0]){
                    var names = [];
                    $.each(ret.data,function(nn,vv){
                        var name = vv.name.split("-");
                        name = name[1];
                        if($.inArray(name,names)==-1) {
                            names.push(name);
                            html+='<option value="'+obj.value+'-'+name+'">'+name+'</option>';
                        }
                    });
                    html+='</select><div id="incatory2" style="float:left"></div>';
                    $("#incatory").html(html);
                }
                
            },'JSON');
        }
    }

    function getGdCategory2(obj){
        if(obj.value == -1){
            $("#incatory2").html("");
        }else{
            var html='<select class="form-control" name="incode"><option value="-1">请选择</option>'
            $.post('<?php echo U("Manage/Users/getGdCategory");?>',{key:obj.value},function(ret){
                if(ret.data[0]){
                    var names = [];
                    $.each(ret.data,function(nn,vv){
                        var name = vv.name.split("-");
                        name = name[2];
                        if($.inArray(name,names)==-1) {
                            names.push(name);
                            html+='<option value="'+vv.code+'">'+name+'</option>';
                        }
                    });
                    html+='</select>';
                    $("#incatory2").html(html);
                }
                
            },'JSON');
        }
    }

    function getGdCity(obj){
        if(obj.value == -1){
            $("#city").html("");
        }else{
            var html='<select id="gdcity2" style="float:left; width:20%" class="form-control" name="sid"><option value="-1">请选择</option>'
            $.post('<?php echo U("Manage/Users/getGdCity");?>',{fid:obj.value},function(ret){
                if(ret.data[0]){
                    $.each(ret.data,function(nn,vv){
                        html+='<option value="'+vv.sid+'">'+vv.name+'</option>';
                    });
                    html+='</select>';
                    $("#city").html(html);
                }
                
            },'JSON');
        }
    }

    function getBankBranch(obj,event){
        /*$('#zz_bankqc').removeClass("inp_selected");
        $('#zz_bankqc').addClass("inp_unselected");*/
        if(event.keyCode!=13&&event.keyCode!=38&&event.keyCode!=40){
            if(obj.value.length ==0){
                $('#bankinfo').html("");
                $('#bankinfo').hide();
            }else if(obj.value.length >=1){
                var gdcity = $('#gdcity').find("option:selected").text();
                var gdcity2 = $('#gdcity2').find("option:selected").text();
                var khbank = $('#khbank').find("option:selected").text();
                if(gdcity == "请选择"){
                    gdcity = "";
                }
                if(gdcity2 == "请选择"){
                    gdcity2 = "";
                }
                if(khbank == "请选择"){
                    khbank = "";
                }
                $.post('<?php echo U("Manage/Users/getGdBankBranch");?>',{key:obj.value,gdcity:gdcity,gdcity2:gdcity2,khbank:khbank},function(ret){
                    if(ret.data[0]){
                        var html = '<select id="bankinfo" class="form-control" multiple="">';
                        $.each(ret.data,function(nn,vv){
                            html+='<option onclick="setBankinfo(this,\''+vv.name+'\')" value="'+vv.code+'">'+vv.name+'</option>';
                        });
                        html+='</select>';
                        $("#bankinfo").html(html);
                        $('#bankinfo').show();
                    }else{
                        $('#bankinfo').html("");
                        $('#bankinfo').hide();
                        //html+='<option onclick="showBankbranch();">没找到银行信息点此手动输入</option>';
                    }
                },'JSON');
            }
        }
    }

    function setBankinfo(obj,name){
        $('#zz_bankqc').removeClass("inp_unselected");
        $('#zz_bankqc').addClass("inp_selected");
        $('#zz_bankqc').val(name);
        $('#backcode').val(obj.value);
        $('#bankinfo').hide();
    }

    function setBankstatus(){
        $('#zz_bankqc').removeClass("inp_unselected");
        $('#zz_bankqc').addClass("inp_selected");
    }

    function areashow(){
        var gdcity = $('#gdcity').find("option:selected").text();
        var gdcity2 = $('#gdcity2').find("option:selected").text();
        var khbank = $('#khbank').find("option:selected").text();
        if(gdcity == "请选择"){
            gdcity = "";
        }
        if(gdcity2 == "请选择" || gdcity2 == gdcity){
            gdcity2 = "";
        }
        if(khbank == "请选择"){
            khbank = "";
        }
        $('#areashow').html('<small class="label label-primary"><i class="fa fa-map-marker"></i> '+gdcity+' '+gdcity2+' '+khbank+'</small>');
    }

    function hidebank(){
        if($('#zz_bankqc').hasClass('inp_unselected')){
            $('#zz_bankqc').val("");
            if($('#bankinfo').hasClass('hover') == false){
                $('#bankinfo').html("");
                $('#bankinfo').hide();
            }
        }
    }

    $("#bankinfo").hover(
      function () {
        $(this).addClass("hover");
      },
      function () {
        $('#bankinfo').html("");
        $('#bankinfo').hide();
        $(this).removeClass("hover");
      }
    );

    function showBankbranch(){
        var winW=$(window).width();
        if(winW<750){
           $('#bankbranchSet .modal-dialog').css('width','92%');
        }else{
           $('#bankbranchSet .modal-dialog').width(730);
        }
        $('body').append('<div class="modal-backdrop in"></div>');
        $('#bankbranchSet').show();
    }

      $("#bankbranchSet ._sure").click(function(){
          $('#bankbranchSet').hide();
          $('#zz_bankqc').val('');
          $('#backcode').val('');
          $('#zz_bankqc').val($('#sdbankname').val());
          $('#backcode').val($('#sdbankbranch').val());
          $('#zz_bankqc').removeClass("inp_unselected");
          $('#zz_bankqc').addClass("inp_selected");
          $('.modal-backdrop').remove();
      });

      $("#bankbranchSet ._close").click(function(){
          $('#bankbranchSet').hide();
          $('#sdbankname').val('');
          $('#sdbankbranch').val('');
          $('.modal-backdrop').remove();
      });
    </script>
    <!--如果银行编码不为空，设置银行全称状态为已填写-->
    <?php if($userinfo['backcode'] != ''): ?><script>setBankstatus();</script><?php endif; ?>

</html>