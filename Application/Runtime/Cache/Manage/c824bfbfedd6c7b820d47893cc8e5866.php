<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>在线收银 - 网站管理后台</title>
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
    <style>
    #popPay .modal-footer{padding: 15px 0 0;
    text-align: center;}
    #popPay .modal-dialog{width: 530px;}
    #popPay .spiner-example{height: 170px;}
    #popPay .okk{height: 170px;color: #0ab85c;font-size: 45px;text-align: center;}
    #popPay .tip{color: #0ab85c;}
    #popPay .modal-footer button{font-size: 22px;}
    .inmodal .modal-header{padding: 30px 15px 15px 15px;}
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
                    <h2>在线收银</h2>
                    <ol class="breadcrumb">
                        <li><a href="<?php echo U("Manage/Index/index");?>">后台首页</a></li><li>微信收银台</li>
                        <li class="active">
                            <strong>在线收银</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>

<div class="wrapper wrapper-content animated fadeInRight">
    
                <div class="row">
                    <div class="col-lg-6">
                        <div class="tabs-container weixin">
                            <ul class="nav nav-tabs">
                                <?php if(empty($usId) or in_array('wx_sksy',session('SX_USERS.grant'))): ?><li class="active"><a data-toggle="tab" href="#tab-1">扫码收银</a></li><?php endif; ?>
<!--                                <?php if(empty($usId) or in_array('wx_smtk',session('SX_USERS.grant'))): ?><li class=""><a data-toggle="tab" href="#tab-2">扫码退款</a></li><?php endif; ?>-->
                            </ul>
                            <div class="tab-content">
                                <div id="tab-1" class="tab-pane active">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-sm-12 micropay"></div>
                                        </div>
                                    </div>
                                </div>
                                <div id="tab-2" class="tab-pane">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-sm-12 micropayRefund"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-12" style="margin-top: 20px;">
                    <div class="ibox float-e-margins">
                    <div class="ibox-title clearfix">
                        <!-- 实时交易信息展示区域 -->
                        <div class="ibox float-e-margins">
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
                                <td><?php if(!empty($ovv['truename'])){ echo htmlspecialchars_decode($ovv['truename'],ENT_QUOTES); }elseif(!empty($ovv['openid'])){ echo $ovv['openid']; }else{ echo '未知客户'; }?></td> 
                                <td>
                                    <?php echo $ovv['mch_id']?>
                                </td> 
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
                                 <?php } ?>
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
                        <!-- 实时交易信息展示区域 -->
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

    <div class="modal inmodal" tabindex="-1" role="dialog"  id="popPay">
        <div class="modal-dialog">
            <div class="modal-content animated bounceInRight">
                <div class="modal-header">
                    <h6 class="modal-title">请耐心等待用户支付完成....</h6>
                    <span>请耐心等待支付完成，不要点取消！</span>
                </div>
                <div class="modal-body">
                    <div class="spiner-example" style="padding-top: 30px;">
                        <div class="sk-spinner sk-spinner-circle" style="height: 100px;width: 100px;">
                            <div class="sk-circle1 sk-circle"></div>
                            <div class="sk-circle2 sk-circle"></div>
                            <div class="sk-circle3 sk-circle"></div>
                            <div class="sk-circle4 sk-circle"></div>
                            <div class="sk-circle5 sk-circle"></div>
                            <div class="sk-circle6 sk-circle"></div>
                            <div class="sk-circle7 sk-circle"></div>
                            <div class="sk-circle8 sk-circle"></div>
                            <div class="sk-circle9 sk-circle"></div>
                            <div class="sk-circle10 sk-circle"></div>
                            <div class="sk-circle11 sk-circle"></div>
                            <div class="sk-circle12 sk-circle"></div>
                        </div>
                    </div>
                    <div class="modal-footer">                       
                        <button id="btntxt" type="button" class="btn btn-white" onclick="window.location.reload();"> 取 消 </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/Static/SX/js/cashier/commonfunc.js"></script>
    <script>
    var Ttype=<?php echo ($type); ?>;
     if(Ttype==2){
        $('.nav-tabs li').removeClass('active');
        $('.nav-tabs li:last').addClass('active');
        $('#tab-1').removeClass('active');
        $('#tab-2').addClass('active');
     }
        !function(a,b,wx){
            function is_mobile(){
                var ua = navigator.userAgent.toLowerCase();
                if ((ua.match(/(iphone|ipod|android|ios|ipad)/i))){
                    if(navigator.platform.indexOf("Win") == 0 || navigator.platform.indexOf("Mac") == 0){
                        return false;
                    }else{
                        return true;
                    }
                }else{
                    return false;
                }
            }
            function is_weixin(){
                var ua = navigator.userAgent.toLowerCase();
                if(is_mobile() && ua.indexOf('micromessenger') != -1){  
                    return true;
                } else {  
                    return false;  
                }
            }
            function orderquery(orderid){
                $.post("<?php echo U('Manage/Wxcashier/orderquery');?>",{orderid:orderid},function(data){
                    return 123;
                },'JSON');
            }
            var c = c || {};
            c.config = {
                data : ['weixin_micropay','weixin_micropayRefund']
            }
            c.init = function(){
                c.tpl();
            }
            
            c.loadJs = function(d){
                var oHead = document.getElementsByTagName('head').item(0),
                    oScript= document.createElement("script");   
                oScript.type = "text/javascript";   
                oScript.src = d;   
                oHead.appendChild( oScript);  
            }
            c.tmpl = function(d){
                var e = {
                    weixin : {
                        micropay : '<h3 class="m-t-none m-b">收款</h3><p>只适用微信、支付宝条码枪刷卡支付</p><p></p><form role="form" action="<?php echo U("Manage/Xingye/payment");?>" id="micropay"><div class="form-group" style="display:none"><label>收款备注</label> <input type="text" placeholder="收款备注" name="goods_name"  id="goods_name" class="form-control" value="扫码枪刷卡支付"></div><div class="form-group"><label>支付金额</label> <input type="text" placeholder="支付金额(至少0.01元)" name="goods_price" id="goods_price" class="form-control"></div><div><button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit"><strong>扫码收款</strong></button></div></form>',
                        micropayRefund : '<h3 class="m-t-none m-b">退款</h3><p>只适用微信、支付宝退款</p><p>可扫支付交易详情页的条形码来退款.</p><form role="form" action="<?php echo U("Manage/Xingye/refund");?>" id="wxSmRefund"><div><button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit"><strong>扫码退款</strong></button></div></form>',
                    }
                }
                var f;
                $.each(d,function(g,h){
                    f = e = e[h];
                });
                return f;
            }
            c.tpl = function(){
                $.each(this.config.data,function(d,e){
                    c.create(e.split('_'));
                });
            }
            c.submit = function(d){
                var tmpStr='';
                var formType=$.trim(d.attr('id'));
                if(formType=='micropay'){
                    tmpStr=$.trim($('#goods_price').val());
                    tmpStr=parseFloat(tmpStr);
                    if(!(tmpStr>=0.01)){
                       swal("温馨提示",'支付金额必须填写一个大于0.01的数', "error");
                       $('#goods_price').focus();
                       return false;
                    }
                }
                tmpStr=d.find('.auth_code').val();
                tmpStr=$.trim(tmpStr);
                if(!tmpStr){
                   swal("温馨提示",'支付auth_code为空,请填写或扫码获取', "error");
                   d.find('.auth_code').focus();
                   return false;
                }
                 if(formType=='micropay'){
                      $('#popPay .modal-header').html('<h6 class="modal-title">请耐心等待用户支付完成....</h6><span>请耐心等待支付完成，不要点取消！</span>');
                    }else if(formType=='wxSmRefund'){
                       $('#popPay .modal-header').html('<h6 class="modal-title">请稍等，正在为您退款...</h6><span></span>');
                    }
                    $('body').append('<div class="modal-backdrop in"></div>');
                    $('#popPay').show();
                    var btntxt = '<div class="sk-spinner sk-spinner-circle" style="height: 100px;width: 100px;"><div class="sk-circle1 sk-circle"></div><div class="sk-circle2 sk-circle"></div><div class="sk-circle3 sk-circle"></div><div class="sk-circle4 sk-circle"></div><div class="sk-circle5 sk-circle"></div><div class="sk-circle6 sk-circle"></div><div class="sk-circle7 sk-circle"></div><div class="sk-circle8 sk-circle"></div><div class="sk-circle9 sk-circle">';
                    $('#popPay .spiner-example').html(btntxt);
                    $('#btntxt').html(" 取 消 ");
                    var e = d.serialize();
                    b.post(d.attr('action'),e, function(data){
                        if(data.status == 1){
                            c.tpl();
                            if(formType!='micropay'){
                                $('#popPay').hide();
                                $('.modal-backdrop').remove();
                                swal("成功!", data.msg, "success");
                            }else{
                                $('#popPay').show();
                                $('#popPay .spiner-example').addClass('okk').html('<span>'+data.price+'元,支付完成！</span>');
                                $('#btntxt').html(" 确 定 ");
                                $('#popPay .modal-header').html('<h6 class="modal-title">恭喜您交易成功</h6>');
                            }
                        }else if(data.status == 2){
                            $('#popPay .modal-header').html('<h6 class="modal-title tip">等待用户输入支付密码....</h6><span>请耐心等待支付完成，不要点取消！</span>');
                                var Interval = setInterval(function(){
                                    $.post("<?php echo U('Manage/Xingye/queryOrder');?>",{orderid:data.orderid},function(data){
                                        if(data.status == 1){
                                            window.clearInterval(Interval);
                                            $('#popPay').show();
                                            $('#popPay .spiner-example').addClass('okk').html('<span>'+data.price+'元,支付完成！</span>');
                                            $('#btntxt').html(" 确 定 ");
                                            $('#popPay .modal-header').html('<h6 class="modal-title">恭喜您交易成功</h6>');
                                        }else if(data.status == 2){
                                        }else{
                                            window.clearInterval(Interval);
                                            $('.modal-backdrop').remove();
                                            $('#popPay').hide();
                                            swal({title: "失败",text:data.msg,type: "error"}, function () {
                                                    window.location.reload();
                                            });
                                        }
                                    },'JSON');
                                },5000);
                        }else{
                            $('.modal-backdrop').remove();
                            $('#popPay').hide();
                            swal({title: "失败",text:data.msg,type: "error"}, function () {
                                window.location.reload();
                            });
                        }
                    },'JSON');
                
            }
            c.create = function(s){
                function d(e){
                    if(is_weixin()){
                        wx.scanQRCode({
                            needResult:1,
                            scanType:["qrCode","barCode"],
                            success:function (res){
                                var result = res.resultStr;
                                
                                if(result.indexOf(',')>0){
                                    var result = result.split(',');
                                    result = result[1];
                                }
                                
                                if(result && /^\d+$/g.test(result)){
                                    e.prepend('<input type="hidden" name="auth_code" class="auth_code" value="'+result+'">');
                                    c.submit(e);
                                    return false;
                                }else{
                                    swal("错误!", "不是有效的码，非法输入！", "error");
                                }   
                            }
                        });
                    }else{
                        swal("错误!", "您使用的不是微信浏览器，此功能无法使用！", "error");
                    }
                }
                var e = this.tmpl(s),
                    f,
                    i = b('body');
                $.each(s,function(g,h){
                    f = i = i.find('.'+h);
                });
                f.html(e);
    
                if(is_weixin()){
                    f.find('form').find('button[type="submit"]').click(function(){
                        d(f.find('form'));
                        return false;
                    });
                }else{
                    if(f.find('form').find('.form-group').size()){
                        f.find('form').find('.form-group').last().after('<div class="form-group"><label>刷卡授权码</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>请连接扫码枪扫码</span><input type="text" placeholder="微信、支付宝刷卡支付授权码(请连接扫码枪扫码)" name="auth_code" class="form-control auth_code"></div>');
                    }else{
                        f.find('form').prepend('<div class="form-group"><label>订单号</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>请连接扫码枪扫码</span> <input type="text" placeholder="需要退款的订单号(请连接扫码枪扫码)" name="auth_code" class="form-control auth_code"></div>');
                    }
                    f.find('form').find('button[type="submit"]').click(function(){
                        c.submit(f.find('form'));
                        return false;
                    });
                }
            }
            b(document).ready(function(){
                c.init();
            });
        }(window,jQuery);


     $(document).ready(function(){
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
                               datahtml+='<td id="datas">'+getLocalTime(vv.paytime)+'</td>';
                               datahtml+='<td>'+vv.goods_price+'</td><td>';
                                datahtml+='<img src="'+IMGSRC+'/pay_icon/'+vv.pay_way+'.png" height="20"></td><td>';
                               if(vv.pay_type == "NATIVE"){
                                 datahtml+='扫码';
                               }else if(vv.pay_type=="MICROPAY"){
                                  datahtml+='刷卡';
                               }else if(vv.pay_type=="JSAPI"){
                                  datahtml+='自助';   
                               }else{
                                   datahtml+='其他';
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
            confirmButtonColor: "#DD6B55",  
            confirmButtonText: "退款",  
            cancelButtonText: "取消",  
            inputValue: price,
        closeOnConfirm: false,  
        closeOnCancel: true }, 

            function(inputValue){
//                inputValue = Number(inputValue);
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
                    } 
        );     
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