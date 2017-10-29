<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>支付配置 - 网站管理后台</title>
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
    
    <link href="/Static/SX/css/plugins/custom.css" rel="stylesheet">
	<link href="/Static/SX/css/plugins/basic.css" rel="stylesheet">
    <link href="/Static/SX/css/plugins/dropzone.css" rel="stylesheet">
    <script src="/Static/SX/js/plugins/icheck.min.js"></script>
    <script src="/Static/SX/js/plugins/dropzone.js"></script>
	<style>
		select.input-sm {
  			height: 35px;
  			line-height: 35px;
		}
		.float-e-margins .btn-info{
			margin-bottom:0px;
			padding:3px;
		}
		.fa-paste{
			margin-right:7px;
			padding: 0px;
		}
		.dz-preview{
			display:none;
		}
		.red{color:red;}
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
                    <h2>支付配置</h2>
                    <ol class="breadcrumb">
                        <li><a href="<?php echo U("Manage/Index/index");?>">后台首页</a></li><li>商家设置</li>
                        <li class="active">
                            <strong>支付配置</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>

<div class="wrapper wrapper-content animated fadeInRight">
    
            <div class="row">
			    <?php if($configs['gd_mchId'] == ''): ?><div class="col-sm-12">
				        <div class="alert alert-danger alert-dismissable">
				            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>提示：当前模式下请先通过实名认证才能正常使用平台 <a href="<?php echo U("Manage/Users/realname");?>">查看详情</a>
				        </div>
			        </div><?php endif; ?>
            	<div class="col-lg-6">
            	    <div class="ibox float-e-margins">
            	        <div class="ibox-title clearfix">
            	            <h5>支付配置</h5>
            	            <div class="btn-group" style="float: right;">
<!--                                <button id="ordinary" class="btn <?php if( $configs['wx_issp'] == 0 or $configs['wx_issp'] == 2): ?>btn-primary<?php else: ?>btn-white<?php endif; ?>" type="button">普通模式</button>
                                <button id="special" class="btn <?php if( $configs['wx_issp'] == 1): ?>btn-primary<?php else: ?>btn-white<?php endif; ?>" type="button">特约模式</button>-->
                                <button id="channel" class="btn <?php if(1): ?>btn-primary<?php else: ?>btn-white<?php endif; ?>" type="button">受理模式</button>
                            </div>
            	        </div>
            	        <div class="ibox-content">
							<div class="alert alert-warning">
								<?php if($configs['wx_issp'] == 3){ ?>
										温馨提示：<br><br>1、受理模式下，您需通过实名认证后才能使用本平台。<br><br>2、受理模式下所产生的支付金额以及其他相关都会由银行进行管理和结算，如有疑问请联系平台客服。<br><br><br>
								<?php }else{ ?>
									<?php if( $configs['wx_issp'] != 1): ?>温馨提示：<br><br>1、如果平台代付被勾选上了，我们将代理您的支付，您可以无需配置任何微信相关配置即可使用本平台所有功能。<br><br>2、平台代付情况下所产生的支付金额以及其他相关都会由平台管理和结算，如有需要请联系管理员磋商。<br><br>3、如果使用独立配置支付或特约商户配置支付，请务必不勾选平台代付。<br><br><?php endif; ?>
									<strong style="color:red;">特别提醒：一旦配置好，请勿再改换配置。如果改换了配置，那么在原来的配置上所付款将不能退款，所发的卡券不能核销等等</strong>
								<?php } ?>
								
							</div>
							<table class="table table-striped">
								<?php if(empty($usId) or in_array('wx_zfsz',session('SX_USERS.grant'))): ?><tr>
	            	                    <td><img style="margin-left: 15px" src="/Static/SX/images/pay_icon/weixin.png"></td>
	            	                    <td style="padding-top: 14px;">支付参数配置</td>
	            	                    <td id="wxapiinfo1"><button class="btn btn-info " type="button" checked="checked" data-toggle="modal" data-target="#weixinSetting"><i class="fa fa-paste"></i>配置信息</button></td>
<!--										<?php if( $configs['wx_issp'] != 1 and $configs['wx_issp'] != 3): ?><td style="padding-top:12px;color:#44b549;font-weight: bold;"><input type="checkbox" <?php if(($configs["wx_issp"]) == "2"): ?>checked<?php endif; ?> class="i-checks" id="is_pfpay"> &nbsp;平台代收及制券</td><?php endif; ?>-->
	            	                </tr><?php endif; ?>
<!-- 								<tr>
            	                    <td><img style="margin-left: 15px" src="/Static/SX/images/pay_icon/alipay.png"></td>
            	                    <td style="padding-top: 14px;">支付宝支付</td>
            	                    <td><button class="btn btn-info " type="button" data-toggle="modal" data-target="#alipaySetting"><i class="fa fa-paste"></i>配置信息</button></td>
	<td></td>
            	                </tr> -->
            	            </table>
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

	<div class="modal inmodal" tabindex="-1" role="dialog" aria-hidden="true" id="weixinSetting">
		<div class="modal-dialog">
			<div class="modal-content animated bounceInRight">
				<form action="" method="post" enctype="multipart/form-data">
				<div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">支付配置</h4>
                </div>
				<div class="modal-body">
					<div class="setting_rows">
						<div id="new_wxpay_box" class="wxpay_box">
							<?php if( $configs['wx_issp'] != 3): ?><div class="form-group">
									<label>微信支付商户号</label>
									<input type="text" placeholder="商户号" value="<?php echo ($configs['wx_mchId']); ?>" name="wx_mchId" class="form-control">
								</div>
							<?php else: ?>
								<div class="form-group">
									<label>普通商户号</label>
									<input type="text" placeholder="实名认证后由系统分配" value="<?php echo ($configs['gd_mchId']); ?>" name="gd_mchId" class="form-control">
								</div>
								<div class="form-group">
									<label>商户秘钥</label>
									<input type="text" placeholder="实名认证后由系统分配" value="<?php echo ($configs['xy_key']); ?>" name="xy_key" class="form-control">
								</div>	
                                                                <div class="form-group">
									<label>Appid</label>
									<input type="text" placeholder="Appid" value="<?php echo ($configs['wx_appId']); ?>" name="wx_appId" class="form-control" >
								</div>
								<div class="form-group">
									<label>AppSecret</label>
									<input type="text" placeholder="应用密钥" value="<?php echo ($configs['wx_appSecret']); ?>" name="wx_appSecret" class="form-control">
								</div><?php endif; ?>
							<!--<?php if( $configs['wx_issp'] == 0): ?>-->
								
							<!--<?php endif; ?>-->
							<?php if( $configs['wx_issp'] == 0): ?><div class="form-group">
									<label>API密钥</label>
									<input type="text" placeholder="Api密钥" value="<?php echo ($configs['wx_apiSecret']); ?>" name="wx_apiSecret" class="form-control">
								</div>
								<div class="alert alert-warning">如需要退款功能，请上传以下文件</div>
								<div class="form-group uploade">
									<label>apiclient_cert私钥文件</label>
									<input type="text" placeholder="apiclient_cert私钥文件" <?php if( $configs['wx_apiclientCert'] != '' ): ?>value="pem文件已上传" readonly="readonly" <?php else: ?> value=""<?php endif; ?> class="form-control" >
									<input type="hidden" placeholder="apiclient_cert私钥文件" value="<?php echo (urldecode($configs['wx_apiclientCert'])); ?>" name="wx_apiclientCert" class="hiddeninput">
									<div class="dropz" style="height: 34px;line-height: 34px;border: 1px solid #e5e6e7;width: 70px;text-align: center;position: relative;top: -34px;float: right;cursor: pointer;">文件上传</div>
								</div>
								<div class="form-group uploade">
									<label>apiclient_key公钥文件</label>
									<input type="text" placeholder="apiclient_key公钥文件" <?php if( $configs['wx_apiclientKey'] != '' ): ?>value="pem文件已上传" readonly="readonly" <?php else: ?> value=""<?php endif; ?> class="form-control">
									<input type="hidden" placeholder="apiclient_key公钥文件" value="<?php echo (urldecode($configs['wx_apiclientKey'])); ?>" name="wx_apiclientKey" class="hiddeninput">
									<div class="dropz" style="height: 34px;line-height: 34px;border: 1px solid #e5e6e7;width: 70px;text-align: center;position: relative;top: -34px;float: right;cursor: pointer;">文件上传</div>
								</div>
								<div class="form-group uploade">
									<label>CA证书文件</label>
									<input type="text" placeholder="微信支付rootca文件" <?php if( $configs['wx_apiclientCa'] != '' ): ?>value="pem文件已上传" readonly="readonly" <?php else: ?> value=""<?php endif; ?>  class="form-control">
									<input type="hidden" placeholder="微信支付rootca文件" value="<?php echo (urldecode($configs['wx_apiclientCa'])); ?>" name="wx_apiclientCa" class="hiddeninput">
									<div class="dropz" style="height: 34px;line-height: 34px;border: 1px solid #e5e6e7;width: 70px;text-align: center;position: relative;top: -34px;float: right;cursor: pointer;">文件上传</div>
								</div><?php endif; ?>
						</div>
					</div>
				</div>
				<div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary btn-confirm">确定</button>
                </div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal inmodal" tabindex="-1"  id="wxApi_Setting">
		<div class="modal-dialog">
			<div class="modal-content animated bounceInRight">
				<div class="modal-header">
                    <button type="button" class="close _close"><span>×</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">微信服务器配置接口信息</h4>
                </div>
				<div class="modal-body">
					<div class="setting_rows">
						<div id="wxActionBox" class="wxpay_box">
							<div class="form-group">
								<label>URL：</label>
								<input type="text" placeholder="服务器推送事件地址" value="{pg:$SiteUrl}/merchants.php?m=Index&c=wxAction&a=index&mymid={pg:$_mid}&midtype=1" class="form-control" readonly="readonly">
							</div>
							<div class="form-group">
								<label>Token：</label>
								<input type="text" placeholder="Token令牌" value="" class="form-control wxtoken" readonly="readonly">
							</div>
							<div class="form-group">
								<label>EncodingAESKey：</label>
								<input type="text" placeholder="消息加解密密钥" value="" class="form-control aeskey" readonly="readonly">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
                    <button type="button" class="btn btn-primary _close">关闭</button>
                </div>
			</div>
		</div>
	</div>

<!-- 	<div class="modal inmodal" tabindex="-1" role="dialog" aria-hidden="true" id="alipaySetting">
	<div class="modal-dialog">
		<div class="modal-content animated bounceInRight">
			<form action="" method="post" enctype="multipart/form-data">
			<div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">支付宝支付 支付配置</h4>
                </div>
			<div class="modal-body">
				<div class="setting_rows">
					<div id="new_wxpay_box" class="wxpay_box">
						
						<div class="form-group">
							<label>Appid</label>
							<input type="text" placeholder="支付宝Appid(必填)" value="{pg:$payConfig.alipay.appid}" name="alipay[appid]" class="form-control">
						</div>
						<div class="form-group">
							<label>PID</label>
							<input type="text" placeholder="合作者身份Partner id(必填)" value="{pg:$payConfig.alipay.pid}" name="alipay[pid]" class="form-control">
						</div>
						<div class="form-group">
							<label>Key</label>
							<input type="text" placeholder="Key(选填)" value="{pg:$payConfig.alipay.key}" name="alipay[key]" class="form-control">
						</div>
						<div class="form-group">
							<label>卖家账号</label>
							<input type="text" placeholder="卖家账号(选填)" value="{pg:$payConfig.alipay.name}" name="alipay[name]" class="form-control">
						</div>
					  <div class="form-group">
						<label>公钥（服务窗）&nbsp;&nbsp;&nbsp;复制这个公钥到支付宝需要上传的地方</label>
						<input type="text" placeholder="公钥（服务窗）" value="{pg:$rsapublickey}" class="form-control">
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary btn-confirm">确定</button>
                </div>
			</form>
		</div>
	</div>
</div> -->



	
    <script>
	 var apihtml="<button class='btn btn-info api' type='button' {pg:if ($payConfig.alipay.isOpen eq 1)} checked='checked' {pg:/if} id='wxApiSetting'> API接口 </button>";
	 if(mobilecheck()){
	     $('#wxapiinfo1').append(apihtml);
		 $('#wxapiinfo1 .api').css('margin-top','15px');
		 $('#new_wxpay_box .uploade').css('display','none');
		 $('#new_wxpay_box').append('<div class="form-group noticee"><label>apiclient_cert私钥文件，apiclient_key公钥文件，CA证书文件等配置请登陆PC端修改</label></div>');
	 }else{
		 $('#new_wxpay_box .uploade').css('display','block');
		 $('#new_wxpay_box .noticee').remove();
	     $('#wxapiinfo2').html(apihtml);
	 }
        $(document).ready(function(){
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green'
            });
			/*$('.switch').change(function(){
				if($(this).val() == 1){
					$('.ibox-content').hasClass('hide') && ($('.ibox-content').removeClass('hide')),$('.ibox-content').show();
				}else if($(this).val() == 0){
					$('.ibox-content').hide();
				}
				$.post('?m=System&c=pay&a=field',{isOpen:$(this).val()});
			});*/
			$('.btn-confirm').click(function(){
				var payConfigData = $(this).parents('form').serialize();
				$.post('<?php echo U("Manage/Users/savePayinfo");?>',{data:htmlToArray(payConfigData)},function(result){
					if(result.status == 1){
						swal({title: "温馨提示",text:'修改成功',type: "success"}, function () {
        					window.location.reload();
   						});
					}else{
						swal({title: "温馨提示",text:'修改失败！',type: "error"});
					}
				},'json');
			});

			$('#ordinary').click(function(){
				$.post('<?php echo U("Manage/Users/SetFieldV");?>',{ispfpay:0},function(ret){
				   if(ret.status == -1){
				      swal("保存失败", '请重新尝试一次' , "error");
				   }else{
						swal({title: "保存成功",text:"将启用您自己独立的微信支付配置来进行支付",type: "success"}, function () {
							window.location.reload();
						});
				   }
				},'JSON');
			});

			$('#special').click(function(){
				$.post('<?php echo U("Manage/Users/SetFieldV");?>',{ispfpay:1},function(ret){
				   if(ret.status == -1){
				      swal("保存失败", '请重新尝试一次' , "error");
				   }else{
						swal({title: "保存成功",text:"将启用特约商户模式进行支付",type: "success"}, function () {
							window.location.reload();
						});
				   }
				},'JSON');
			});

			$('#channel').click(function(){
				$.post('<?php echo U("Manage/Users/SetFieldV");?>',{ispfpay:3},function(ret){
				   if(ret.status == -1){
				      swal("保存失败", '请重新尝试一次' , "error");
				   }else{
						swal({title: "保存成功",text:"将启用受理模式进行支付，请确保实名认证已通过",type: "success"}, function () {
							window.location.reload();
						});
				   }
				},'JSON');
			});

			$('#is_pfpay').on('ifChanged', function(){
				var title="";
				var text="";
				if($(this).is(':checked')){
					var is_pfpay = 2;
					title= "您确定要启用代收吗？";
					text= "启用后平台来代理您的支付！";
				}else{
					var is_pfpay = 0;
					title= "您确定要关闭代收吗？";
					text= "关闭后您需要配置好微信支付，否则收银等功能将无法使用！";
				}
		        swal({
		            title: title,
		            text: text,
		            type: "warning",
		            showCancelButton: true,
		            confirmButtonColor: "#DD6B55",
		            confirmButtonText: "确定",
		            cancelButtonText: "取消",
		            closeOnConfirm: false,
		            closeOnCancel: false
		        },
		        function(a) {
		            if (a) {
						$.post('<?php echo U("Manage/Users/SetFieldV");?>',{ispfpay:is_pfpay},function(ret){
						   if(ret.status == -1){
						      swal("保存失败", '请重新尝试一次' , "error");
						   }else{
						      if(is_pfpay==2){
							     swal("保存成功", '将启用平台管理总后台支付配置来代理您的支付' , "success");
							  }else{
							      swal("保存成功", '将启用您自己独立支付配置来进行支付' , "success");
							  }
						   }
						},'JSON');
		            } else {
		                window.location.reload();
		            }
		        })
			});


			$(".dropz").dropzone({
				url: "<?php echo U('Manage/Users/pem_Upload');?>",
				addRemoveLinks: false,
				maxFilesize: 1,
				acceptedFiles: ".pem",
				uploadMultiple: false,
				init: function() {
					this.on("success", function(file,responseText) {
						if(responseText.status == 1){
							/***这里的this.element 是 $(".dropz")****/
							$(this.element).siblings('.form-control').val('pem文件已上传');
							$(this.element).siblings('.form-control').attr('readonly','readonly');
							$(this.element).siblings('.hiddeninput').val(responseText.savepath);
						}else{
							swal({title: "温馨提示",text:responseText.error,type: "error"});
						}
					});
				}
			});
        });
		function htmlToArray(data){
			data = data.split('&');
			var info = {};
			$.each(data,function(k,v){
				v = v.replace('%5D','').split('=');
				var s = v[0].split('%5B');
				typeof(info[s[0]]) == 'undefined' && (info[s[0]] = {}),info[s[0]] = v[1];
			});
			return info;
		}

		 $("#wxApiSetting").click(function(){
			$.post('/merchants.php?m=System&c=pay&a=getApiData',function(ret){
			    $('#wxApi_Setting .wxtoken').val(ret.wxtoken);
				$('#wxApi_Setting .aeskey').val(ret.aeskey);
				
				var winW=$(window).width();
				if(winW<750){
				   $('#wxApi_Setting .modal-dialog').css('width','92%');
				}else{
				   $('#wxApi_Setting .modal-dialog').width(730);
				}
				$('body').append('<div class="modal-backdrop in"></div>');
				$('#wxApi_Setting').show();
			},'JSON');
		  });
		  $("#wxApi_Setting ._close").click(function(){
			  $('#wxApi_Setting').hide();
			  $('#wxApi_Setting .wxtoken').val('');
			  $('#wxApi_Setting .aeskey').val('');
			  $('.modal-backdrop').remove();
		  });
    </script>

</html>