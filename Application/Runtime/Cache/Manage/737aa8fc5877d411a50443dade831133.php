<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>首页 - 网站管理后台</title>
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
    
<style>
.fl{float: left; margin-left: 5px;}
.clearfix:after {
  content: " ";
  display: block;
  clear: both;
  height: 0;
}
.iCheck-helper{position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);}
.code-box{width:172px;height:172px;padding:3px 4px;position:relative;margin-top: 5px;background:#fff;}
.codebox-tips{background: #44b549;margin: 5px 0 0 0;color: #fff;height:27px;line-height:16px;font-size:14px;text-align: center;padding:5px 6px;width:172px;}
.codebox-tips i{ display:inline-block;background:url(/Static/SX/images/pay_icon/czzx_btn0723.png) no-repeat 0 -86px;_background:url(/Static/SX/images/pay_icon/czzx_btn0723_png8.png) no-repeat 0 -86px;width:18px;height:16px;margin-right:3px;vertical-align:middle}
#qr-code img{vertical-align: middle; width: 164px;}
#qr-code {line-height: 164px;}
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
                    <h2>首页</h2>
                    <ol class="breadcrumb">
                        <li><a href="<?php echo U("Manage/Index/index");?>">后台首页</a></li>
                        <li class="active">
                            <strong>首页</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>

<div class="wrapper wrapper-content animated fadeInRight">
    
<div class="row">
            <?php if(($configs['userAudit'] == 3) and ($configs['reson'] != '')): ?><div class="col-sm-12">
                    <div class="alert alert-danger alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>通知：您提交的商户资料审核失败了 <a href="<?php echo U("Manage/Users/realname");?>">查看详情</a>
                    </div>
                </div><?php endif; ?>
            <div class="col-sm-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-success pull-right">今日</span>
                        <h5>收入</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins"><?php echo ($data['curmIncome']); ?></h1>
                        <div class="stat-percent font-bold <?php if($data['perIncome'] == 0): ?>text-success<?php elseif($data['perIncome'] > 0): ?>text-info<?php elseif($data['perIncome'] < 0): ?>text-danger<?php endif; ?>"><?php echo ($data['perIncome']); ?>% <i class="fa <?php if($data['perIncome'] == 0): ?>fa-bolt<?php elseif($data['perIncome'] > 0): ?>fa-level-up<?php elseif($data['perIncome'] < 0): ?>fa-level-down<?php endif; ?>"></i>
                        </div>
                        <small>总收入</small>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-info pull-right">今年</span>
                        <h5>订单</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins"><?php echo ($data['curTotal']); ?></h1>
                        <div class="stat-percent font-bold <?php if($data['lastTotal'] == 0): ?>text-success<?php elseif($data['lastTotal'] > 0): ?>text-info<?php elseif($data['lastTotal'] < 0): ?>text-danger<?php endif; ?>"><?php echo ($data['lastTotal']); ?>% <i class="fa <?php if($data['lastTotal'] == 0): ?>fa-bolt<?php elseif($data['lastTotal'] > 0): ?>fa-level-up<?php elseif($data['lastTotal'] < 0): ?>fa-level-down<?php endif; ?>"></i>
                        </div>
                        <small>订单数</small>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-primary pull-right">总计</span>
                        <h5>门店</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins"><?php echo ($data['Stores']); ?></h1>
                        <!-- <div class="stat-percent font-bold text-navy">0% <i class="fa fa-level-up"></i>
                        </div> -->
                        <small>门店数</small>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-primary pull-right">总计</span>
                        <h5>员工</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins"><?php echo ($data['Ustaffs']); ?></h1>
                        <!-- <div class="stat-percent font-bold text-danger">0% <i class="fa fa-level-down"></i>
                        </div> -->
                        <small>员工数</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
                        <div class="col-lg-8">
                            <div class="ibox float-e-margins">
                                <div class="ibox-content">
                                    <div>
                                        <span class="pull-right text-right">
                                        <div class="legend"><table style="position:absolute;top:9px;right:37px;;font-size:smaller;color:#838383"><tbody><tr><td class="legendColorBox"><div style="border:1px solid #000000;padding:1px"><div style="width:4px;height:0;border:5px solid RGB(220,220,220);overflow:hidden"></div></div></td><td class="legendLabel">总金额</td></tr><tr><td class="legendColorBox"><div style="border:1px solid #000000;padding:1px"><div style="width:4px;height:0;border:5px solid RGB(26,179,148);overflow:hidden"></div></div></td><td class="legendLabel">订单数</td></tr></tbody></table></div>
                                        </span>
                                        <h3 class="font-bold no-margins">
                                        半年收入利润率
                                    </h3>
                                        <small>明细表</small>
                                    </div>

                                    <div class="m-t-sm">

                                        <div class="row">
                                            <div class="col-md-8">
                                                <div>
                                                    <canvas id="lineChart" height="175" width="463" style="width: 463px; height: 175px;"></canvas>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <ul class="stat-list m-t-lg">
                                                    <li>
                                                        <h2 class="no-margins"><?php echo ($data["lastmOrder"]); ?></h2>
                                                        <small>上个月总订单</small>
                                                        <div class="progress progress-mini">
                                                            <div class="progress-bar" style="width:<?php echo $data['lastmOrder']/($data['lastmOrder']+$data['curmTotal'])*100; ?>%;"></div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <h2 class="no-margins "><?php echo ($data["curmTotal"]); ?></h2>
                                                        <small>当月订单</small>
                                                        <div class="progress progress-mini">
                                                            <div class="progress-bar" style="width:<?php echo $data['curmTotal']/($data['lastmOrder']+$data['curmTotal'])*100; ?>%;"></div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="m-t-md">
                                        <small class="pull-right">
                                        <i class="fa fa-clock-o"> </i>
                                        <?php echo date('Y.m.d');?>更新
                                    </small>
                                        <small>
                                        <strong>说明：</strong> 本期销售额比上期增长了<?php echo ($data['perIncome']); ?>%。
                                    </small>
                                    </div>

                                </div>
                            </div>
                        </div>
<!--                        <div class="col-lg-4">
                            <div class="ibox float-e-margins">
                                <div class="ibox-title">
                                    <?php if( $data['jd'] != 4): ?><span class="label label-danger pull-right">有配置尚未完成</span><?php else: ?><span class="label label-primary pull-right">完成平台所有配置</span><?php endif; ?>
                                    <h5>配置检测</h5>
                                </div>
                                <div class="ibox-content" style="padding: 10px;">
                                    <div class="progress progress-striped active" style="margin: 0">
                                        <div style="width: <?php if( $data['jd'] == 4): ?>100<?php elseif( $data['jd'] == 3): ?>80<?php elseif( $data['jd'] == 2): ?>40<?php elseif( $data['jd'] == 1): ?>20<?php else: ?>5<?php endif; ?>%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="100" role="progressbar" class="progress-bar <?php if( $data['jd'] == 4): ?>progress-bar-success<?php elseif( $data['jd'] >= 2 and $data['jd'] <= 3): ?>progress-bar-warning<?php else: ?>progress-bar-danger<?php endif; ?>">
                                            <span class="sr-only">配置进度</span>
                                        </div>
                                    </div>
                                    <ul class="todo-list m-t ui-sortable">
                                        <li>
                                            <div class="icheckbox_square-green" style="position: relative;"><input type="checkbox" value="" name="" class="i-checks" style="position: absolute; opacity: 0;"><ins class="iCheck-helper"></ins></div>
                                            <span class="m-l-xs"><a href="<?php echo U('Manage/Users/payConfig');?>">微信支付配置</a></span>
                                                <?php if( $data['weixin'] == 1): ?><small class="label label-primary">已完成</small>
                                                <?php else: ?>
                                                    <small class="label label-warning">没完成</small><?php endif; ?>
                                        </li>
                                        <li>
                                            <div class="icheckbox_square-green checked" style="position: relative;"><input type="checkbox" value="" name="" class="i-checks" checked="" style="position: absolute; opacity: 0;"><ins class="iCheck-helper"></ins></div>
                                            <span class="m-l-xs"><a href="<?php echo U('Manage/Users/storefront');?>">添加门店</a></span>
                                                <?php if( $data['Stores'] > 0): ?><small class="label label-primary">已完成</small>
                                                <?php else: ?>
                                                    <small class="label label-warning">没完成</small><?php endif; ?>
                                        </li>
                                        <li>
                                            <div class="icheckbox_square-green" style="position: relative;"><input type="checkbox" value="" name="" class="i-checks" style="position: absolute; opacity: 0;"><ins class="iCheck-helper"></ins></div>
                                            <span class="m-l-xs"><a href="<?php echo U('Manage/Users/employers');?>">添加员工</a></span>
                                                <?php if( $data['Ustaffs'] > 0): ?><small class="label label-primary">已完成</small>
                                                <?php else: ?>
                                                    <small class="label label-warning">没完成</small><?php endif; ?>
                                        </li>
                                        <li>
                                            <div class="icheckbox_square-green" style="position: relative;"><input type="checkbox" value="" name="" class="i-checks" style="position: absolute; opacity: 0;"><ins class="iCheck-helper"></ins></div>
                                            <span class="m-l-xs"><a id="bdwx" href="javascript:;">绑定微信</a></span>
                                                <?php if( $data['isbind'] == 1): ?><small class="label label-primary">已完成</small>
                                                <?php else: ?>
                                                    <small class="label label-warning">没完成</small><?php endif; ?>
                                        </li>
                                    </ul>
                                </div>
                            </div>-->
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

<div class="modal inmodal" tabindex="-1" role="dialog"  id="bdweixin">
        <div class="modal-dialog">
            <div class="modal-content animated bounceInRight">
                <div class="modal-header">
                    <button type="button" class="close _close"><span style="font-size: 35px;">×</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">微信绑定流程</h4>
                </div>
                <div class="modal-body">
                    <div class="fl">
                        <div class="code-box">
                            <div id="qr-code"><img src="http://open.weixin.qq.com/qr/code/?username=<?php echo ($sysconfigs["wxId"]); ?>"></div>
                        </div>
                        <div class="codebox-tips"><i>1</i>请使用微信扫描关注</div>
                    </div>
                    <div class="fl">
                        <div class="code-box">
                            <div id="qr-code"><img src="/Static/SX/images/lcbdwx1.png"></div>
                        </div>
                        <div class="codebox-tips"><i>2</i>点击账号绑定</div>
                    </div>
                    <div class="fl">
                        <div class="code-box">
                            <div id="qr-code"><img src="/Static/SX/images/lcbdwx2.png"></div>
                        </div>
                        <div class="codebox-tips"><i>3</i>输入账号进行绑定</div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white _close">关闭</button>
                </div>
            </div>
        </div>
    </div>
 <script src="/Static/SX/js/Chart.min.js"></script>
 <script>
        $(document).ready(function() {

            $('#bdwx').click(function(){
                $('#bdweixin').show();
            });

            $("#bdweixin ._close").click(function(){
              $('#bdweixin').hide();
              $('.modal-backdrop').remove();
            });

    var lineData = {
        labels: ["<?php echo ($months["month"]["0"]); ?>","<?php echo ($months["month"]["1"]); ?>","<?php echo ($months["month"]["2"]); ?>","<?php echo ($months["month"]["3"]); ?>","<?php echo ($months["month"]["4"]); ?>","<?php echo ($months["month"]["5"]); ?>","<?php echo ($months["month"]["6"]); ?>"],
        datasets: [{
            label: "总收入",
            fillColor: "rgba(220,220,220,0.5)",
            strokeColor: "rgba(220,220,220,1)",
            pointColor: "rgba(220,220,220,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php echo ($months["price"]["0"]); ?>, <?php echo ($months["price"]["1"]); ?>, <?php echo ($months["price"]["2"]); ?>, <?php echo ($months["price"]["3"]); ?>, <?php echo ($months["price"]["4"]); ?>, <?php echo ($months["price"]["5"]); ?>, <?php echo ($months["price"]["6"]); ?>]
        }, {
            label: "订单数",
            fillColor: "rgba(26,179,148,0.5)",
            strokeColor: "rgba(26,179,148,0.7)",
            pointColor: "rgba(26,179,148,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(26,179,148,1)",
            data: [<?php echo ($months["total"]["0"]); ?>, <?php echo ($months["total"]["1"]); ?>, <?php echo ($months["total"]["2"]); ?>, <?php echo ($months["total"]["3"]); ?>, <?php echo ($months["total"]["4"]); ?>, <?php echo ($months["total"]["5"]); ?>, <?php echo ($months["total"]["6"]); ?>]
        }]
    };

    var lineOptions = {
        scaleShowGridLines: true,
        scaleGridLineColor: "rgba(0,0,0,.05)",
        scaleGridLineWidth: 1,
        bezierCurve: true,
        bezierCurveTension: 0.4,
        pointDot: true,
        pointDotRadius: 4,
        pointDotStrokeWidth: 1,
        pointHitDetectionRadius: 20,
        datasetStroke: true,
        datasetStrokeWidth: 2,
        datasetFill: true,
        responsive: true,
    };
    var ctx = document.getElementById("lineChart").getContext("2d");
    var myNewChart = new Chart(ctx).Line(lineData, lineOptions)
});

    </script>

</html>