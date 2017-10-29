<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>二维码收银 - 网站管理后台</title>
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
    
    <link href="/Static/SX/css/cashier.css" rel="stylesheet">
    <link href="/Static/SX/css/footable.core.css" rel="stylesheet">
    <style>
        .ibox-title h5 {
            margin: 10px 0 0px;
        }
        select.input-sm {
            height: 35px;
            line-height: 35px;
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
        #spantext{    display: inline-block;height: 32px;line-height: 29px;position: relative;top: -10px;}
        #qr-code-zone canvas{vertical-align: middle;}
        #qr-code-zone {line-height: 200px;padding-top: 4px;}
        #qr-code-forever canvas{vertical-align: middle;}
        #qr-code-forever {line-height: 200px;padding-top: 4px;}
        #qr-code-autopay canvas{vertical-align: middle;}
        #qr-code-autopay {line-height: 200px;padding-top: 4px;}
        #table-list-body .btn-st{background-color: #337ab7;border-color: #2e6da4;cursor:auto;}
        #oderinfo{overflow-y: scroll;}
        #lkewmdown{overflow-y: scroll;}
        #ewmdown{overflow-y: scroll;}
        .float-e-margins .ibox-content{border-style:none;}
        .nav-tabs > li > a:hover,
        .nav-tabs > li > a:focus {
         background-color: #FFF;
        }
        .nav-tabs li.active  a {border-color:#dddddd #dddddd #fff #fff}
        .nav-tabs li.active  a:hover,.nav-tabs li.active a:focus {border-color:#dddddd #dddddd #fff #fff;background-color:#FFF;}
        .mbform .controls{text-align: left;}
        .showSweetAlert input[type="text"]{
            display: inline;
        }
    </style>
    <script src="/Static/SX/js/footable.all.min.js"></script>

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
	                    	<?php if(empty($usId)): ?><li <?php if((CONTROLLER_NAME) == "Users"): if((ACTION_NAME) == "storefront"): ?>class="active"<?php endif; endif; ?>><a href="<?php echo U("Manage/Users/storefront");?>">门店管理</a></li><?php endif; ?>
	                        <?php if(empty($usId)): ?><li <?php if((CONTROLLER_NAME) == "Users"): if((ACTION_NAME) == "employers"): ?>class="active"<?php endif; endif; ?>><a href="<?php echo U("Manage/Users/employers");?>">员工管理</a></li><?php endif; ?>
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
                                
                                <?php if(empty($usId) or in_array('orderlists',session('SX_USERS.grant'))): ?><li <?php if((CONTROLLER_NAME) == "Statistics"): if((ACTION_NAME) == "balance"): if(($type) != "2"): ?>class="active"<?php endif; endif; endif; ?>><a href="<?php echo U("Manage/Statistics/balance");?>">结算管理</a></li><?php endif; ?>

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
                    <h2>二维码收银</h2>
                    <ol class="breadcrumb">
                        <li><a href="<?php echo U("Manage/Index/index");?>">后台首页</a></li><li>微信收银台</li>
                        <li class="active">
                            <strong>二维码收银</strong>
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
                    <!--<ul class="nav nav-tabs"> 
                    <li class="active"> <a href="#">收银台</a> </li> 
                    <li> <a href="<?php echo $this->SiteUrl;?>/merchants.php?m=User&c=cashier&a=payRecord">收款记录</a> </li> 
                    <li> <a href="<?php echo $this->SiteUrl;?>/merchants.php?m=User&c=cashier&a=ewmRecord">二维码记录</a> </li> 
                    </ul> -->
                    <strong>指定金额收款</strong> 
                </div>

                <div class="ibox-content"> 
                    <div class="app__content js-app-main carousel slide" id="carousel3" >
                        <div class="carousel-inner">
                            <div class="page-cashier-box"> 
                                <div class="cashier-desk clearfix"> 
                                    <!-- 实时收款二维码 --> 
                                    <div class="realtime-pay js-pay-code-region clearfix">
                                        <div style="text-align: center;">
                                            <div class="pay-config" style="margin-top: 7%;">
                                                <form class="form-horizontal">
                                                    <div class="control-group config-title"> 
                                                    <!--<div class="controls"> 
                                                     <span id="spantext">支付类型：</span>
                                                        <select name="paytype" id="paytype" class="input-sm form-control input-s-sm inline switch" style="width:251px;">
                                                          <option value="wxpay">微信支付</option>
                                                        </select>
                                                     <span class="clear-btn js-clear"></span> 
                                                    </div>  -->
                                                    </div>

                                                    <div class="control-group config-title"> 
                                                        <div class="controls"> 
                                                            <input type="text" name="cashier_name" class="js-cashier-name js-input" value="" placeholder="收款商品名称" style="margin-bottom: 20px;" /> 
                                                            <span class="clear-btn js-clear"></span> 
                                                        </div> 
                                                    </div> 
                                                   
                                                    <div class="control-group config-amount"> 
                                                        <div class="controls"> 
                                                            <input type="text" name="cashier_value" class="js-cashier-value js-input" value="" placeholder="输入金额(至少0.01元)" style="margin-bottom: 20px;" /> 
                                                            <a href="javascript:void(0)" class="btn btn-primary js-weixin-qrcode">生成微信二维码</a>
                                                            <a href="javascript:void(0)" class="btn btn-success js-alipay-qrcode">生成支付宝二维码</a>
                                                        </div> 
                                                    </div> 
                                                    <p class="gray tips fixed-tips"></p> 
                                                </form> 
                                            </div> 

                                            <div class="pay-code" id="immediately"> 
                                                <h5>立刻支付二维码</h5>
                                                <div class="qr-code-zone gray" id="qr-code-zone">二维码区域 </div> 
                                                <p class="gray tips" id="receivables">收款: &nbsp;-&nbsp; 元</p> 
                                                <p class="tips">&nbsp;&nbsp;</p> 
                                            </div>

<!--                                            <div class="pay-code f-pay-code"> 
                                                <h5>永久支付二维码</h5>
                                                <div class="qr-code-zone gray" id="qr-code-forever">二维码区域</div> 
                                                <p class="gray tips" id="receivablesforever">收款: &nbsp;-&nbsp; 元</p> 
                                                <p class="tips downLoadEwm"> <a href="javascript:void(0)" onclick="lkewmdown()">下载二维码</a> </p> 
                                            </div>-->

                                            <div class="pay-code" id="autopay-qrcode"> 
                                                <h5>自助付款</h5>
                                                <div class="qr-code-zone gray" id="qr-code-autopay"></div> 
                                                <p class="gray tips" id="receivables">买家可自助输入付款金额</p>
                                                <p class="tips downLoadEwm"> <a href="javascript:void(0)" onclick="ewmdown()">下载二维码</a></p>
                                            </div>
                                        </div>
                                    </div> 
                                </div> 

        <!-- 实时交易信息展示区域 --> 
        <div class="cashier-realtime"> 
            <div class="realtime-title-block clearfix"> 
                <h1 class="realtime-title">近期收款情况</h1> 
                <a href="javascript:void(0)" class="js-refresh-list refresh-list">刷新</a> 
            </div> 
        </div> 
      
        <div class="js-real-time-region realtime-list-box loading">
            <div class="widget-list">
                <div class="js-list-filter-region clearfix ui-box" style="position: relative;">
                    <div class="widget-list-filter"></div>
                </div> 
        
                <div class="ui-box"> 
                    <table class="ui-table ui-table-list" data-page-size="20" style="padding: 0px;"> 
                        <thead class="js-list-header-region tableFloatingHeaderOriginal">
                            <tr class="widget-list-header">
                                <th>订单号</th>
                                <th  data-hide="phone">付款人</th> 
                                 <th  data-hide="phone">收款账户</th>
                                <th  data-hide="phone">付款时间</th> 
                                <th  data-hide="phone">付款金额(元)</th>
                               
                                <th  data-hide="phone">方式</th>
                                <th  data-hide="phone">支付情况</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        
                        <tbody class="js-list-body-region" id="table-list-body">
            <?php if(!empty($order)){ foreach($order as $ovv){ ?>
            <tr class="widget-list-item">
                <td><?php echo $ovv['order_id'];?></td> 
                <td><?php if(!empty($ovv['nickname'])){ echo $ovv['nickname']; }elseif(!empty($ovv['truename'])){ echo htmlspecialchars_decode($ovv['truename'],ENT_QUOTES); }elseif(!empty($ovv['openid'])){ echo $ovv['openid']; }else{ echo '未知客户'; }?></td> 
                <td><?php echo $ovv['mch_id']?></td>
            <td><?php $paytime=$ovv['paytime'] > 0 ? $ovv['paytime'] : $ovv['add_time']; echo date('Y-m-d H:i:s',$paytime);?></td> 
            <td><?php echo $ovv['goods_price'];?></td>
            <td><img src="/Static/SX/images/pay_icon/<?php echo ($ovv["pay_way"]); ?>.png" height="20">-
                <?php
 if($ovv['pay_type']=='NATIVE'){ echo "扫码"; }elseif($ovv['pay_type']=='MICROPAY'){ echo "刷卡"; }elseif($ovv['pay_type']=='JSAPI'){ echo "自助"; } ?></td>
            <td><?php if($ovv['refund']==1){?>
                 <font>退款中...</font>
            <?php }elseif($ovv['refund']==2){?>
                 <font color="#2e6da4">已退款</font>
            <?php }elseif($ovv['refund']==3){?>
                 <font color="#ed5565">退款失败</font>
             <?php }else{?>
                <?php if($ovv['ispay']==1){?>
                 <font color="#44b549">已支付</font>
                <?php }else{ ?>
                <font color="#ed5565">未支付</font>
                <?php }?>
            <?php }?>
            </td> 
            <td>
                <button class="btn btn-sm btn-info" onclick="GetDetail(<?php echo ($ovv["id"]); ?>);"><strong>支付详情</strong></button>
                <button class="btn btn-sm btn-info" onclick="refund(<?php echo ($ovv["id"]); ?>, <?php echo ($ovv["goods_price"]); ?>);"><strong>退款</strong></button>
                <button class="btn btn-sm btn-info" onclick="queryRefund(<?php echo ($ovv["id"]); ?>);"><strong>查询退款</strong></button>
            </td>
           </tr>
           <?php }}else{?>
           <tr class="widget-list-item"><td colspan="7">暂无订单</td></tr>
           <?php }?>
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

    <div class="modal inmodal" tabindex="-1" role="dialog"  id="lkewmdown">
        <div class="modal-dialog">
            <div class="modal-content animated bounceInRight">
                <div class="modal-header">
                    <button type="button" class="close _close"><span style="font-size: 35px;">×</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">永久支付二维码下载</h4>
                </div>
                <div class="modal-body">
                    <table class="ui-table ui-table-simple goods-table footable-loaded footable no-paging">
                     <thead>
                      <tr>
                       <th class="footable-sortable">尺寸</th>
                       <th data-hide="phone" class="footable-sortable">备注</th>
                       <th data-hide="phone" class="footable-sortable">操作</th>
                      </tr>
                     </thead>
                     <tbody>
                      <tr class="test-item footable-even" style="display: table-row;">
                       <td><span>6cm x 6cm</span></td>
                       <td>L型扫码牌</td>
                       <td><a href="javascript:void(0)" id="downloadEwm11">下载二维码</a></td>
                      </tr>
                      <tr class="test-item footable-even" style="display: table-row;">
                       <td><span>28cm x 28cm</span></td>
                       <td>通用</td>
                       <td><a href="javascript:void(0)" id="downloadEwm12">下载二维码</a></td>
                      </tr>
                     </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white _close">关闭</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal inmodal" tabindex="-1" role="dialog"  id="ewmdown">
        <div class="modal-dialog">
            <div class="modal-content animated bounceInRight">
                <div class="modal-header">
                    <button type="button" class="close _close"><span style="font-size: 35px;">×</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">自助付款二维码下载</h4>
                </div>
                <div class="modal-body">
                    <table class="ui-table ui-table-simple goods-table footable-loaded footable no-paging">
                     <thead>
                      <tr>
                       <th class="footable-sortable">尺寸</th>
                       <th data-hide="phone" class="footable-sortable">备注</th>
                       <th data-hide="phone" class="footable-sortable">操作</th>
                      </tr>
                     </thead>
                     <tbody>
                      <tr class="test-item footable-even" style="display: table-row;">
                       <td><span>6cm x 6cm</span></td>
                       <td>L型扫码牌</td>
                       <td><a href="javascript:void(0)" id="downloadEwm21">下载二维码</a></td>
                      </tr>
                      <tr class="test-item footable-even" style="display: table-row;">
                       <td><span>28cm x 28cm</span></td>
                       <td>通用</td>
                       <td><a href="javascript:void(0)" id="downloadEwm22">下载二维码</a></td>
                      </tr>
                     </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white _close">关闭</button>
                </div>
            </div>
        </div>
    </div>

    <script src="/Static/SX/js/cashier/canvas2image.js"></script>
    <script src="/Static/SX/js/cashier/commonfunc.js"></script>
    <script>
    //$('.qr-code-zone').qrcode("http://www.helloweba.com"); //任意字符串 
    var qwidth=qheight=200;
    if(is_mobile()){
      $('.form-horizontal').addClass('mbform');
      $('.row .col-lg-12').css('padding','1px');
      $('.float-e-margins .ibox-content').css('padding','15px 5px 20px 5px');
      $('.cashier-desk .realtime-pay').css('float','none').css('padding','0 0 0 5px');
      $('.js-pay-code-region .pay-code').css('float','none').css('margin-left','0px').css('padding','0px');
      $('.js-cashier-name').css('width','95%');
      $('.js-fixed-code-region').css('width','auto').css('border-left','none');
      $('.qr-code-zone').css('width','251px').css('height','251px').css('padding-top','9px');
      $('.js-fixed-code-region').css('margin','0px').css('float','none');
      $('.self-pay-code').css('width','251px').css('height','251px');
      $('.self-pay-code img').css('width','251px').css('height','251px');
      $('#paytype').css('width','70%');
      $('.js-cashier-value').css('width','50%');
      $('.nav-tabs li a').css('padding','10px');
      $('#immediately').css('margin-top','40px');
      $('.downLoadEwm').hide();
      qwidth=qheight=230;
    }else{
      $('.form-horizontal').removeClass('mbform');
    }
     var topost=true;
     var thismoney=0;
        $(document).ready(function(){
             $('.ui-table-list').footable();
            $("#qr-code-autopay").html('').css('background-color','#FFF').qrcode({ 
                    //render: "table", //table方式 
                    width: qwidth, //宽度 
                    height: qheight, //高度
                    text:'<?php echo ($autopayewm); ?>' //任意内容 
                });
            $('.js-weixin-qrcode').click(function(){
                getEwm('weixin');
            });

            $('.js-alipay-qrcode').click(function(){
                getEwm('alipay');
            });

            $('.js-refresh-list').click(function(){
                if(is_mobile()){
                  window.location.reload();
                  return false;
                }
                var IMGSRC = "/Static/SX/images";
                 $.ajax({
                    url: "<?php echo U('Manage/Order/getajaxOrder');?>",
                    type: "POST",
                    dataType: "json",
                    /*async:true,
                    data:{cf:'index'},*/
                    success: function(res){
                        if(res.status != -1 && res.data){
                            var datahtml='';
                            $.each(res.data,function(kk,vv){
                               datahtml+='<tr class="widget-list-item">';
                               datahtml+='<td>'+vv.order_id+'</td>';
                               if(vv.truename == ""){
                                datahtml+='<td>'+vv.openid+'</td>';
                               }else{
                                datahtml+='<td>'+vv.truename+'</td>';
                               }
                               datahtml+='<td>'+vv.mch_id+'</td>';
                               datahtml+='<td id="datas">'+getLocalTime(vv.paytime)+'</td>';
                               datahtml+='<td>'+vv.goods_price+'</td><td>';
                                datahtml+='<img src="'+IMGSRC+'/pay_icon/'+vv.pay_way+'.png" height="20">-';
                               if(vv.pay_type == "NATIVE"){
                                 datahtml+='扫码';
                               }else if(vv.pay_type=="MICROPAY"){
                                  datahtml+='刷卡';
                               }else if(vv.pay_type=="JSAPI"){
                                  datahtml+='自助';   
                               }
                               datahtml+= '</td><td>';
                               if(vv.refund == 1){
                                 datahtml+='<font>退款中...</font>';
                               }else if(vv.refund==2){
                                  datahtml+='<font color="#2e6da4">已退款</font>';
                               }else if(vv.refund==3){
                                  datahtml+='<font color="#ed5565">退款失败</font>';
                               }else{
                                   if(vv.ispay==1){
                                       datahtml+='<font color="#44b549">已支付</font>';
                                   }else{
                                       datahtml+='<font color="#ed5565">未支付</font>';
                                   }                                 
                               }
                               datahtml+='</td><td><button class="btn btn-sm btn-info" onclick="GetDetail('+vv.id+');"><strong>支付详情</strong></button></td>';
                               datahtml+='</tr>';
                            });
                          $('.js-list-body-region').html(datahtml);
                        }else{
                            $('.js-list-body-region').html('<tr class="widget-list-item"><td colspan="6">暂无订单</td></tr>');
                        }
                         $('.ui-table-list').footable();
                        /*setTimeout(function(){
                        
                        }, 2000);*/
                    }
                });
            });
        });

     function getEwm(type){
         if(!topost) return false;
         var postdata={paytype:type};
         postdata.tname=$.trim($('input[name="cashier_name"]').val());
         if(!postdata.tname){
            swal({title:'付款理由必须填！',text:'', type:"error"});
            return false;
         }
         postdata.tprice=$.trim($('input[name="cashier_value"]').val());
         postdata.tprice=parseFloat(postdata.tprice);
         if(!(postdata.tprice > 0)){
            swal({title:'付款金额必须填！',text:'', type: "error"});
            return false; 
         }
         thismoney=postdata.tprice;
         topost=false;
         $.post('<?php echo U("Manage/Xingye/ewmpay");?>',postdata,function(ret){
            topost=true;
            if(ret.status == 1){
                $("#qr-code-zone").html('').css('background-color','#FFF').qrcode({ 
                    //render: "table", //table方式 
                    width: qwidth, //宽度 
                    height: qheight, //高度
                    text:ret.qrcode //任意内容 
                });
                var typename = "";
                if(type=="weixin"){
                    typename = "微信收款：";
                }else if(type=="alipay"){
                    typename = "支付宝收款：";
                }
                $('#receivables').html(typename+postdata.tprice+' 元');

                $("#qr-code-forever").html('').css('background-color','#FFF').qrcode({ 
                    //render: "table", //table方式 
                    width: qwidth, //宽度 
                    height: qheight, //高度 
                    text:ret.foreverpay //任意内容 
                });
                $('#receivablesforever').html('收款: '+postdata.tprice+' 元');
            }else{
                swal("失败", ret.msg , "error");
            }
        },'json');
     }

     function lkewmdown(){
       $('#lkewmdown').show();
     }

     function ewmdown(){
       $('#ewmdown').show();
     }

    function getLocalTime(uTime){
      var myDate = new Date(uTime*1000);
      var year = myDate.getFullYear();
      var month = myDate.getMonth() + 1;
      var day = myDate.getDate();
      var hours = myDate.getHours();
      var minutes = myDate.getMinutes();
      var second = myDate.getSeconds();
      if(parseInt(month)<10){
            month = '0'+month;
      }
      if(parseInt(day)<10){
            day = '0'+day;
      }
      if(parseInt(hours)<10){
            hours = '0'+hours;
      }
      if(parseInt(minutes)<10){
            minutes = '0'+minutes;
      }
      if(parseInt(second)<10){
            second = '0'+second;
      }
      return  year + '-' + month + '-' + day + ' ' + hours + ':' + minutes + ':' + second;
    }    

   var screenH=$(window).height();
    screenH=  screenH-20;
    $('#oderinfo').css('height',screenH);
    
    var odurl="<?php echo U('Manage/Statistics/odetail');?>";
    
    
    function refund(id, price){      
        swal({  
            title: "退款确认",  
            text: "请输入退款金额",  
            type: "input",  
            showCancelButton: true,
            closeOnConfirm: false,
//            confirmButtonColor: "#DD6B55",  
            confirmButtonText: "退款",  
            cancelButtonText: "取消", 
            inputValue: price, 
//        closeOnConfirm: true,  
         },  
        function(inputValue){
//                        inputValue = Number(inputValue);
                        if (inputValue === false) return false;
                        if (inputValue === "" || isNaN(inputValue)) {      
                            swal.showInputError("请正确输入退款金额！");
                            return false    
                        }       
                        $('#popPay .modal-header').html('<h6 class="modal-title">请耐心等待....</h6><span>请耐心等待，不要点取消！</span>');
                        $.ajax({
                            type: "POST",
                            url: "<?php echo U('Manage/Xingye/refund');?>",
                            data: {'id': id, 'refund_fee': inputValue},
                            dataType: "json",
                            beforeSend: function(){
                                $('#popPay').show();
                            },
                            success: function(data){
                                    $('#popPay').hide();
                                    swal(data.msg, 'success');                        
                                      },
                            error:function(XMLHttpRequest, textStatus, errorThrown){
                                $('#popPay').hide();
                                swal('请求失败， textStatus:'+textStatus, 'error');
                            }
                        });  
                    } );  
    }
    
     function queryRefund(id){
            $('#popPay .modal-header').html('<h6 class="modal-title">请耐心等待....</h6><span>请耐心等待，不要点取消！</span>');
             $.ajax({
            type: "POST",
            url: "<?php echo U('Manage/Xingye/queryRefund');?>",
            data: {'id': id},
            dataType: "json",
            beforeSend: function(){
                $('#popPay').show();
            },
            success: function(data){
                    $('#popPay').hide();
                    if(1 == data.status){
                        swal('订单正在退款中...', 'success');
                    }else if(2 == data.status){
                        swal('订单已退款成功！', 'success');
                    }else if(3 == data.status){
                        swal('退款失败！', 'success');
                    }else{
                        swal(data.msg, 'success');
                    }                    
                        
                      },
            error:function(XMLHttpRequest, textStatus, errorThrown){
                $('#popPay').hide();
                swal('请求失败， textStatus:'+textStatus, 'error');
            }
        });
    
    
    }
    
    </script>
  <script src="/Static/SX/js/cashier/lhsw.js"></script>

</html>