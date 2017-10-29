<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>创建微信门店 - 网站管理后台</title>
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
    

	<link href="/Static/SX/css/merchant/store_mangecss.css" rel="stylesheet">
	<link href="/Static/SX/css/merchant/baseshop.css" rel="stylesheet">
	<link href="/Static/SX/css/merchant/widget_add_img.css" rel="stylesheet">
	
	<link href="/Static/SX/css/plugins/basic.css" rel="stylesheet">
    <link href="/Static/SX/css/plugins/dropzone.css" rel="stylesheet">
	<link href="/Static/SX/css/dataTables/datepicker3.css" rel="stylesheet">
	<style type="text/css">
	  #dataselect .input-group-btn,#ym-select .input-group-btn{width: 12%;}
	  #dataselect .input-sm ,#ym-select .input-sm{ border-radius: 7px; height:40px;}
	  #dataselect .btn-primary ,#ym-select .btn-primary{ margin-left: 20px; border-radius:4px;margin-bottom: 0px;}
	  #dataselect .input-group-addon,#ym-select .input-group-addon{border-radius: 7px;}
	  .ibox-content{ min-height:800px;}
	  #js_pic_url .dz-image-preview{display:none;}
	  .img_upload_preview_box p{margin:0px;}
	  .js_category_container select{width:200px;float: left;}
	 #provinceS,#cityS,#districtS,#circleS{display:inline-block;width: auto;float:left;}
	 #js_latitude,#js_longitude{width:250px;display: inline;}
	 #js_store_build u{display:none;}
	 #bside_left {
		width: 260px;
		height: 500px;
		padding: 10px 10px 10px 10px;
		float: left;
		overflow: auto;
	}
	#mapfrm_controls::after{
	   content:" . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . "
	}
	#mapcontainer{width: 620px;}
.search_c {
    float: left;
	margin-left: 20px;
	width: 350px;
}
.search_c .form-control{width:70%;display:inline-block;}
#no_value{
    color:red;
    position: relative;
    width:200px;
}
.info_list {
    cursor: pointer;
    margin-bottom: 5px;
}
	</style>
	<!-- Data picker -->
    <script src="/Static/SX/js/plugins/bootstrap-datepicker.js"></script>
	<link href="/Static/SX/css/plugins/custom.css" rel="stylesheet">
	<script charset="utf-8" src="http://map.qq.com/api/js?v=2.exp&key=S6PBZ-D7BRQ-BNB5S-G2LBZ-PYAIO-DJF4K&libraries=drawing,geometry,autocomplete,convertor"></script>

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
                    <h2>创建微信门店</h2>
                    <ol class="breadcrumb">
                        <li><a href="<?php echo U("Manage/Index/index");?>">后台首页</a></li><li>商家设置</li>
                        <li class="active">
                            <strong>创建微信门店</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>

<div class="wrapper wrapper-content animated fadeInRight">
    
			   <div class="row" id="wrapper-content-list">
                    <div class="col-lg-12">
                        <div class="ibox float-e-margins">
						<div class="ibox-title">	
						 <div class="alert alert-warning" style="font-size: 16px;">
                                温馨提示：如果您输入的字数超过规定的字数，提交到微信服务器审核，会报错或审核不通过
								<br/>一但提交将不可修改，请如实填写
                            </div>
						</div>
                    <div class="ibox-content">
			<div class="group main_bd">
				<form novalidate="novalidate" class="store_build" id="js_store_build"> 
				<div class="frm_section"> 
				<h3 class="frm_title">基本信息<span class="frm_title_dec">基本信息提交后不可修改</span></h3> 
				<div class="frm_control_group"> 
				 <label for="" class="frm_label" style="width: 30px;">地址</label> 
				 <div class="frm_control_row"> 
				  <div class="frm_controls menu_controls" style="margin-right:10px;"> 
				   <select id="provinceS" class="form-control" onchange="GetCity();">
				   <option value="-1">请选择</option>
					<?php foreach($districts as $akk=>$avv){?>
					  <option value="<?php echo $avv['id']?>" data-fullname="<?php echo $avv['fullname']?>" data-lng="<?php echo $avv['lng']?>" data-lat="<?php echo $avv['lat']?>"><?php echo $avv['fullname']?></option>
					<?php }?>
				   </select>
				    <div class="search_c"><input autocomplete="off" class="form-control" onkeypress="if(event.keyCode==13) {btnSearch.click();return false;}" type="text" placeholder="输入地址搜索定位"> &nbsp;&nbsp;&nbsp;<span id="btn_search" class="btn btn-primary">搜 索</span></div>
				   <input name="provinceinfo" id="provinceinfo" type="hidden" value=""/>
				   <input name="cityinfo" id="cityinfo" type="hidden"  value=""/> 
				   <input name="districtinfo" id="districtinfo" type="hidden"  value=""/> 
				   <input name="circleinfo" id="circleinfo" type="hidden"  value=""/>
				   <input name="pos_id" id="pos_id" type="hidden"  value=""/>
				  </div>
				  <div class="frm_controls input_controls" style="margin:15px 0 0 5px"> 
				   <div class="input_btn group"> 
					<div>详细地址：<span class="frm_input_box" style="width:315px;"> <input placeholder="输入详细地址，请勿重复填写省市区信息" name="address" id="searchSubmit" class="frm_input" type="text" /></div>
					</span> 
					<div style="margin-top:15px;">经纬度：<input name="latitude" id="js_latitude" type="text" class="form-control" placeholder="纬度，点击地图获取" style="width: 160px;" />&nbsp; 
					<input name="longitude" id="js_longitude" type="text" class="form-control" placeholder="经度，点击地图获取" style="width: 160px;"/> 
					<!--<a class="btn btn_default l dn" id="js_remark" href="javascript:;">修改</a>--></div>
				   </div> 
				  </div> 
				 </div> 
				</div> 
				<div class="frm_control_group" id="js_mark_position"> 
				 <label for="" class="frm_label">定位</label> 
				 <div class="frm_controls" id="mapfrm_controls"> 

				  <div class="map_panel"> 

				    <div id="bside_left"><div> <p>在搜索框搜索关键词后，地图上会显示相应poi点，同时左侧显示对应该点的信息，点击某点或某信息，右上角会显示相应该点的坐标和地址。</p></div></div>

				   <div class="map crosspoint map_result" id="mapcontainer"> 
				   </div> 
				  </div> 
				 </div> 
				</div> 
				<div class="frm_control_group"> 
				 <label for="" class="frm_label">门店名</label> 
				 <div class="frm_controls"> 
				  <span class="frm_input_box"> <input class="frm_input ckinput" id="js_business_name" name="business_name" type="text" /><u>15</u></span> 
				  <p class="frm_msg fail" style="display: none;"><span for="js_business_name" class="frm_msg_content" style="display: inline;">门店名不能为空且长度不超过15个汉字或30个英文字母</span></p>
				  <p class="frm_tips">门店名不得含有区域地址信息（如，北京市XXX公司），不超过15个汉字</p> 
				 </div> 
				</div> 
				<div class="frm_control_group"> 
				 <label for="" class="frm_label">分店名</label> 
				 <div class="frm_controls"> 
				  <span class="frm_input_box"> <input id="js_branch_name" class="frm_input ckinput" name="branch_name" type="text" /> <u>10</u></span> 
				  <p class="frm_msg fail" style="display: none;"><span for="js_business_name" class="frm_msg_content" style="display: inline;">门店名不能为空且长度不超过10个汉字或20个英文字母</span></p>
				  <p class="frm_tips">分店名不得含有区域地址信息（如，“北京国贸店”中的“北京”），不超过10个字</p> 
				 </div> 
				</div> 
				<div class="frm_control_group"> 
				 <label for="" class="frm_label">类目</label> 
				 <div class="frm_controls"> 
				  <div id="js_category_dom"> 
				   <div class="js_category_container">
				    <select name="categoryid0" id="categoryid0" class="form-control" onchange="subCategory();">
					<?php foreach($categorys as $ckk=>$cvv){?>
					     <option value="<?php echo $cvv['id']?>" data-cname="<?php echo $cvv['name']?>"><?php echo $cvv['name']?></option>
					<?php }?>
				   </select>

				   </div>
				   <input name="categoryid0info" id="categoryid0info" type="hidden" value="1-美食"/>
				   <input name="categoryid1info" id="categoryid1info" type="hidden" value="2-江浙菜"/>
				  </div> 
				 </div> 
				</div> 
				</div> 
				<div class="frm_section service_info"> 
				<h3 class="frm_title">服务信息</h3> 
				<div class="frm_control_group"> 
				 <label for="" class="frm_label">门店图片</label> 
				 <div class="frm_controls"> 
				  <p class="frm_tips">像素要求必须为640*340像素，支持.jpg .jpeg .gif .png格式，大小不超过1M</p> 
				  <div id="js_upload_wrp">
				   <div class="img_upload_wrp group"> 
					<div id="js_pic_url_div" class="img_upload_box"> 
					 <a class="img_upload_box_oper js_upload" id="js_pic_url" href="javascript:"> <i class="icon20_common add_gray"> 上传 </i> </a> 
					</div>
					<div class="js_pager"></div> 
				   </div>
				  </div> 
				 </div> 
				</div> 
				<div class="frm_control_group"> 
				 <label for="" class="frm_label">电话</label> 
				 <div class="frm_controls"> 
				  <span class="frm_input_box"> <input class="frm_input" name="telephone" id="js_telephone" type="text" onkeyup="value=value.replace(/[^1234567890\-]+/g,'')" maxlength="25"/> </span> 
				  <p class="frm_tips">固定电话需加区号；区号、分机号均用“-”连接</p> 
				 </div> 
				</div> 
				<div class="frm_control_group"> 
				 <label for="" class="frm_label">人均价格</label> 
				 <div class="frm_controls with_hint"> 
				  <span class="frm_input_box"> <input id="js_avg_price" class="frm_input" name="avg_price" type="text" onkeyup="value=value.replace(/[^1234567890]+/g,'')" maxlength="7"/> </span> 
				  <span class="frm_hint">元</span> 
				  <p class="frm_tips">大于零的整数，须如实填写，默认单位为人民币</p> 
				 </div> 
				</div> 
				<div class="frm_control_group"> 
				 <label for="" class="frm_label">营业时间</label> 
				 <div class="frm_controls"> 
				  <span class="frm_input_box"> <input class="frm_input" id="js_open_time" name="open_time" type="text" onkeyup="value=value.replace(/[^1234567890\:\-]+/g,'')" maxlength="12"/> </span> 
				 </div> 
				 <p class="frm_tips">如，10:00-21:00</p> 
				</div> 
				<div class="frm_control_group"> 
				 <label for="" class="frm_label">推荐<br /><span class="frm_label_dec">(选填)</span></label> 
				 <div class="frm_controls"> 
				  <div class="frm_textarea_box"> 
				   <textarea class="frm_textarea ckinput" name="recommend" id="js_recommend" placeholder=""></textarea><u>100</u> 
				  </div> 
				  <p class="frm_msg fail" style="display: none;"><span for="js_business_name" class="frm_msg_content" style="display: inline;">推荐长度不超过100个汉字或200个英文字母</span></p>
				  <p class="frm_tips">如，推荐菜，推荐景点，推荐房间，不超过100个字</p> 
				 </div> 
				</div> 
				<div class="frm_control_group"> 
				 <label for="" class="frm_label">特色服务</label> 
				 <div class="frm_controls"> 
				  <div class="frm_textarea_box"> 
				   <textarea class="frm_textarea ckinput" name="special" id="js_special"></textarea><u>100</u>
				  </div> 
				  <p class="frm_msg fail" style="display: none;"><span for="js_business_name" class="frm_msg_content" style="display: inline;">特色服务不能为空且长度不超过100个汉字或200个英文字母</span></p>
				  <p class="frm_tips">如，免费停车，WiFi，不超过100个字，没有则填无</p> 
				 </div> 
				</div> 
				<div class="frm_control_group"> 
				 <label for="" class="frm_label">简介<br /><span class="frm_label_dec">(选填)</span></label> 
				 <div class="frm_controls"> 
				  <div class="frm_textarea_box"> 
				   <textarea class="frm_textarea ckinput" name="desc" id="js_desc"></textarea><u>100</u>
				  </div>
				  <p class="frm_msg fail" style="display: none;"><span for="js_business_name" class="frm_msg_content" style="display: inline;">简介长度不超过100个汉字或200个英文字母</span></p>
				  <p class="frm_tips">对品牌或门店的简要介绍，不超过100个字</p> 
				 </div> 
				</div> 
				<!-- <div class="frm_control_group"> 
				 <label for="" class="frm_label">门店签名<br /><span class="frm_label_dec">(选填)</span></label> 
				 <div class="frm_controls"> 
				  <span class="frm_input_box"> <input class="frm_input" id="js_signature" name="signature" type="text" /> </span> 
				  <p class="frm_tips">在“附近的人”展示，不超过6个字，如上方截图示例中的“满99送咖啡”字样。</p> 
				 </div> 
				</div> -->
				</div> 
				</form> 
	
		</div> 
          <div class="tool_bar border tc">
			<button id="sub_add_shop" class="btn btn-primary" style="height: 36px;"> 提交审核 </button>
			 <!--<span  class="btn btn_input btn_default" id="js_preview"><button>预览</button></span>-->
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

	<script src="/Static/SX/js/plugins/dropzone.js"></script>
	<script src="/Static/SX/js/plugins/icheck.min.js"></script>
    <script type="text/javascript">
     var picnum = 0;
     var  wxCouponType='<?php echo $typeid;?>';
	 /***计算字符串长度(英文占1个字符，中文汉字占2个字符)*****/
		String.prototype.gbLen = function() {    
			var len = 0;    
			for (var i=0; i<this.length; i++) {    
				if (this.charCodeAt(i)>127 || this.charCodeAt(i)==94) {    
					 len += 2;    
				 } else {    
					 len ++;    
				 }    
			 }    
			return len;    
		}
	      wxCouponType=parseInt(wxCouponType);
        $(document).ready(function() { 
			/*$('#ymdatepicker input').datepicker({
                keyboardNavigation: false,
                forceParse: false,
				format: "yyyy-mm-dd",
                autoclose: true
            });*/

		    	$("#js_pic_url,#js_pic_url .icon20_common").dropzone({
				url: "<?php echo U('Manage/Stores/uploadStoreImg');?>",
				addRemoveLinks: false,
				maxFilesize: 1,
				acceptedFiles: ".jpg,.png,.gif,.jpeg",
				uploadMultiple: false,
				init: function() {
					this.on("success", function(file,responseText) {
						/***这里的this.element 是 $("#js_pic_url")****/
					if(responseText.status == 1){
						var  imgHtml='<div class="img_upload_box img_upload_preview_box js_edit_pic_wrp"><img  src="/'+responseText.savepath+'"><input name="photo_list[]" class="imginput" type="hidden" value="'+responseText.savepath+'"><input name="photo_img[]" type="hidden" value="'+responseText.savepath+'"><p class="img_upload_edit_area js_edit_area"><a class="icon18_common del_gray js_delete" href="javascript:;" onclick="DelthisImg($(this));" ></a></p></div>';
					 	$('#js_upload_wrp .img_upload_wrp .js_pager').before(imgHtml);
					 	$(this.element).find('div').remove();
					 	picnum++;
					 	if(picnum == 4){
					 		$("#js_pic_url_div").hide();
					 	}
					}else{
						swal({
						  title: "上传失败",
						  text: responseText.error,
						  type: "error"
						}, function () {
						//window.location.reload();
						   });
						}
					});
				}
			});

	  $(document).on('mouseover mouseout','.img_upload_preview_box',function(event){
	   if(event.type == "mouseover"){
	     $(this).find('p').show();
	   }else if(event.type == "mouseout"){
		$(this).find('p').hide();
		}
	  });

$('#sub_add_shop').click(function(){
		var thisObj=$(this);
	    if(checkInPut()){
		thisObj.prop('disabled',true);
		$.ajax({
			url:'<?php echo U("Manage/Stores/addStore");?>',
			type:"post",
			data:$('form').serialize(),
			dataType:"JSON",
			success:function(ret){
				if(!ret.error){
					swal({
					  title: "保存成功！",
					  text: ret.msg,
					  type: "success"
					 }, function () {
					   window.location.href="<?php echo U('Manage/Users/storefront');?>";
					});
				}else{
					swal({
					  title: "保存失败！",
					  text: ret.msg,
					  type: "error"
					 }, function () {
					//window.location.reload();
					});
			   }
			   thisObj.prop('disabled',false);
			}
		});
	  }
	  return false;
       //document.js_editform.submit();
	});

  $('#js_store_build .ckinput').each(function(){
    $(this).keyup(function(){
	  var inputstr=$(this).val();
	  var strleng=inputstr.gbLen();
	  var limitnum=$(this).siblings('u').text();
	  limitnum=parseInt(limitnum);
	  var tmplen=0;
	  var tipsObj=$(this).parent().siblings('.frm_msg');
	  if(limitnum>0){
		  limitnum=limitnum*2;
		  if(strleng > limitnum){
			  tipsObj.show();
		  }else{
			  tipsObj.hide();
		  }
	  }
  });

  });

});

function checkInPut(){
	var provinceinfo=$('#provinceinfo').val();
	var cityinfo=$('#cityinfo').val();
	if(!provinceinfo || !cityinfo){
		swal({
			title: "温馨提示！",
			text: '请选择省份和城市',
			type: "error"
			});
		$('#provinceinfo').focus();
	   return false;
	}
	var daddress=$('#searchSubmit').val();
	daddress=$.trim(daddress);
	if(!daddress || (daddress.gbLen() >100)){
		swal({
			title: "温馨提示！",
			text: '详细地址不能为空且不要超过50汉字',
			type: "error"
			});
		$('#searchSubmit').focus();
	   return false;
	}
	var late=$.trim($('#js_latitude').val());
	var lnge=$.trim($('#js_longitude').val());
	if(!late || !lnge){
		swal({
		title: "温馨提示！",
		text: '请点击地图选择经纬度值！',
		type: "error"
		});
		return false;
	}
	var businessname=$.trim($('#js_business_name').val());
	if(!businessname || (businessname.gbLen() >30)){
		swal({
		title: "温馨提示！",
		text: '门店名必须填写且不得超过15个汉字',
		type: "error"
		});
		return false;
	}
	var branchname=$.trim($('#js_branch_name').val());
	if(!branchname || (branchname.gbLen() >20)){
		swal({
		title: "温馨提示！",
		text: '分店名必须填写且不得超过10个汉字',
		type: "error"
		});
		return false;
	}
	if(!($('#js_upload_wrp .imginput').size()>0)){
	  	swal({
		title: "温馨提示！",
		text: '请至少上传一张和店面相关的照片！',
		type: "error"
		});
		return false;
	}
	var telephone=$.trim($('#js_telephone').val());
	if(!telephone){
	  	swal({
		title: "温馨提示！",
		text: '电话号码没填写！',
		type: "error"
		});
		return false;
	}
	var avgprice=$.trim($('#js_avg_price').val());
	if(!avgprice){
	  	swal({
		title: "温馨提示！",
		text: '人均价格没填写！',
		type: "error"
		});
		return false;
	}
	var opentime=$.trim($('#js_open_time').val());
	if(!opentime){
	  	swal({
		title: "温馨提示！",
		text: '营业时间没填写！',
		type: "error"
		});
		return false;
	}
	var jsspecial=$.trim($('#js_special').val());
	if(!jsspecial || (jsspecial.gbLen() >200)){
	  	swal({
		title: "温馨提示！",
		text: '特色服务没填写！',
		type: "error"
		});
		return false;
	}
    return true;
}

function DelthisImg(obj){
  if(confirm('您确定删除图片！')){
    obj.parent('p').parent('.img_upload_preview_box').remove();
	picnum--;
 	$("#js_pic_url_div").show();
  }
}

     //直接加载地图
    //初始化地图函数  自定义函数名init
	var map=null,label=null;
    function init(lat,lng,tozoom) {
        //定义map变量 调用 qq.maps.Map() 构造函数   获取地图显示容器
          map = new qq.maps.Map(document.getElementById("mapcontainer"), {
            center: new qq.maps.LatLng(lat,lng),      // 地图的中心地理坐标。
            zoom:tozoom                               // 地图的中心地理坐标。
        });
    //给map绑定mousemove事件
    label = new qq.maps.Label({
		 map: map,
         offset:new qq.maps.Size(15,-10),
		 draggable: false,
         clickable: false
    });
	map.setOptions({
		draggableCursor: "crosshair"
	});
    //添加监听事件    监听鼠标移动 添加 当前鼠标的经纬度信息
    qq.maps.event.addListener(map,"mousemove",function(e){
		var latlng = e.latLng;
        //label.setContent(e.latLng.toString());
        label.setPosition(e.latLng);
		label.setContent(latlng.getLat().toFixed(10) + "," + latlng.getLng().toFixed(10));
    });
    //添加监听事件  当鼠标移到图层上面显示图层
    qq.maps.event.addListener(map,"mouseover",function(e){
        label.setMap(map);
    });
    //添加监听事件  当鼠标离开的时候 设置图层为空
    qq.maps.event.addListener(map,"mouseout",function(e){
        label.setMap(null);
    });
	var url3;
	qq.maps.event.addListener(map, "click", function (e) {
		document.getElementById("js_latitude").value = e.latLng.getLat().toFixed(10);
		document.getElementById("js_longitude").value = e.latLng.getLng().toFixed(10);
		url3 = encodeURI("http://apis.map.qq.com/ws/geocoder/v1/?location=" + e.latLng.getLat() + "," + e.latLng.getLng() + "&key=S6PBZ-D7BRQ-BNB5S-G2LBZ-PYAIO-DJF4K&output=jsonp&&callback=?");
		$.getJSON(url3, function (result) {
			if(result.result!=undefined){
				//document.getElementById("searchSubmit").value = result.result.address;
				var addressComponent=result.result.address_component;
				document.getElementById("searchSubmit").value = addressComponent.street+addressComponent.street_number;
				$('#pos_id').val(result.result.ad_info.adcode);
			}else{
				document.getElementById("searchSubmit").value = "";
			}

		})
	});
	}
   
    //调用初始化函数地图
    init(39.904690,116.407170,12); /***北京市*****/

var listener_arr = [];
var isNoValue = false;
var btnSearch = document.getElementById("btn_search");
var bside = document.getElementById("bside_left");
var query_city='',havecity=0;
var markerArray = [];
function each(obj, fn) {
    for (var n = 0, l = obj.length; n < l; n++) {
        fn.call(obj[n], n, obj[n]);
    }
}
qq.maps.event.addDomListener(btnSearch, 'click', function () {
    var value = this.parentNode.getElementsByTagName("input")[0].value;
    var latlngBounds = new qq.maps.LatLngBounds();
    for(var i= 0,l=listener_arr.length;i<l;i++){
        qq.maps.event.removeListener(listener_arr[i]);
    }
    listener_arr.length = 0;
    havecity =$('#cityS').size();
	if(havecity >0){
	   havecity=$.trim($('#cityS').val());
	   havecity=parseInt(havecity);
	   if(havecity>0){
	      query_city=$('#cityS').find("option:selected").data('fullname');
	   }else{
	      alert('请选择一个城市');
		  return false;
	   }
	}else{
		  alert('请选择省份和城市！');
		  return false;
	}
    url = encodeURI("http://apis.map.qq.com/ws/place/v1/search?keyword=" + value + "&boundary=region(" + query_city + ",0)&page_size=9&page_index=1&key=S6PBZ-D7BRQ-BNB5S-G2LBZ-PYAIO-DJF4K&output=jsonp&&callback=?");
    $.getJSON(url, function (result) {

        if (result.count) {
            isNoValue = false;
            bside.innerHTML = "";
            each(markerArray, function (n, ele) {
                ele.setMap(null);
            });
            markerArray.length = 0;
            each(result.data, function (n, ele) {
                var latlng = new qq.maps.LatLng(ele.location.lat, ele.location.lng);
                latlngBounds.extend(latlng);
                var left = n * 27;
                var marker = new qq.maps.Marker({
                    map: map,
                    position: latlng,
					adcode:ele.ad_info.adcode,
					district:ele.ad_info.district,
					province:ele.ad_info.province,
					city:ele.ad_info.city,
					title:ele.title,
					address:ele.address,
                    zIndex: 10
                });
                marker.index = n;
                marker.isClicked = false;
                setAnchor(marker, true);
                markerArray.push(marker);
                var listener1 = qq.maps.event.addDomListener(marker, "mouseover", function () {
                    var n = this.index;
                    setCurrent(markerArray, n, false);
                    setCurrent(markerArray, n, true);
                    label.setContent(this.position.getLat().toFixed(10) + "," + this.position.getLng().toFixed(10));
                    label.setPosition(this.position);
                    label.setOptions({
                        offset: new qq.maps.Size(15, -20)
                    })

                });
                listener_arr.push(listener1);
                var listener2 = qq.maps.event.addDomListener(marker, "mouseout", function () {
                    var n = this.index;
                    setCurrent(markerArray, n, false);
                    setCurrent(markerArray, n, true);
                    label.setOptions({
                        offset: new qq.maps.Size(15, -12)
                    })
                });
                listener_arr.push(listener2);
                var listener3 = qq.maps.event.addDomListener(marker, "click", function () {
                    var n = this.index;
                    setFlagClicked(markerArray, n);
                    setCurrent(markerArray, n, false);
                    setCurrent(markerArray, n, true);
                });
                listener_arr.push(listener3);
                map.fitBounds(latlngBounds);
                var div = document.createElement("div");
                div.className = "info_list";
                var order = document.createElement("div");
                var leftn = -54 - 17 * n;
                order.style.cssText = "width:17px;height:17px;margin:3px 3px 0px 0px;float:left;background:url(/Static/SX/images/merchant/marker_n.png) " + leftn + "px 0px";
                div.appendChild(order);
                var pannel = document.createElement("div");
                pannel.style.cssText = "width:200px;float:left;";
                div.appendChild(pannel);
                var name = document.createElement("p");
                name.style.cssText = "margin:0px;color:#0000CC";
                name.innerHTML = ele.title;
                pannel.appendChild(name);
                var address = document.createElement("p");
                address.style.cssText = "margin:0px;";
                address.innerHTML = "地址：" + ele.address;
                pannel.appendChild(address);
                if (ele.tel != undefined) {
                    var phone = document.createElement("p");
                    phone.style.cssText = "margin:0px;";
                    phone.innerHTML = "电话：" + ele.tel;
                    pannel.appendChild(phone);
                }
                var position = document.createElement("p");
                position.style.cssText = "margin:0px;";
                position.innerHTML = "坐标：" + ele.location.lat.toFixed(6) + "，" + ele.location.lng.toFixed(6);
                pannel.appendChild(position);
                bside.appendChild(div);
                div.style.height = pannel.offsetHeight + "px";
                div.isClicked = false;
                div.index = n;
                marker.div = div;
                div.marker = marker;
            });
            $("#bside_left").delegate(".info_list", "mouseover", function (e) {

                var n = this.index;

                setCurrent(markerArray, n, false);
                setCurrent(markerArray, n, true);
            });
            $("#bside_left").delegate(".info_list", "mouseout", function () {
                each(markerArray, function (n, ele) {
                    if (!ele.isClicked) {
                        setAnchor(ele, true);
                        ele.div.style.background = "#fff";
                    }
                })
            });
            $("#bside_left").delegate(".info_list", "click", function (e) {
                var n = this.index;
                setFlagClicked(markerArray, n);
                setCurrent(markerArray, n, false);
                setCurrent(markerArray, n, true);
                map.setCenter(markerArray[n].position);
            });
        } else {

            bside.innerHTML = "";
            each(markerArray, function (n, ele) {
                ele.setMap(null);
            });
            markerArray.length = 0;
            var novalue = document.createElement('div');
            novalue.id = "no_value";
            novalue.innerHTML = "对不起，没有搜索到你要找的结果!";
            bside.appendChild(novalue);
            isNoValue = true;
        }
    });
});

function setAnchor(marker, flag) {
    var left = marker.index * 27;
    if (flag == true) {
        var anchor = new qq.maps.Point(10, 30),
                origin = new qq.maps.Point(left, 0),
                size = new qq.maps.Size(27, 33),
                icon = new qq.maps.MarkerImage("/Static/SX/images/merchant/marker10.png", size, origin, anchor);
        marker.setIcon(icon);
    } else {
        var anchor = new qq.maps.Point(10, 30),
                origin = new qq.maps.Point(left, 35),
                size = new qq.maps.Size(27, 33),
                icon = new qq.maps.MarkerImage("/Static/SX/images/merchant/marker10.png", size, origin, anchor);
        marker.setIcon(icon);
    }
}
function setCurrent(arr, index, isMarker) {
    if (isMarker) {
        each(markerArray, function (n, ele) {
            if (n == index) {
                setAnchor(ele, false);
                ele.setZIndex(10);
            } else {
                if (!ele.isClicked) {
                    setAnchor(ele, true);
                    ele.setZIndex(9);
                }
            }
        });
    } else {
        each(markerArray, function (n, ele) {
            if (n == index) {
                ele.div.style.background = "#DBE4F2";
            } else {
                if (!ele.div.isClicked) {
                    ele.div.style.background = "#fff";
                }
            }
        });
    }
}
function setFlagClicked(arr, index) {
    each(markerArray, function (n, ele) {
        if (n == index) {
            ele.isClicked = true;
            ele.div.isClicked = true;
            var str = '<div style="width:250px;">' + ele.div.children[1].innerHTML.toString() + '</div>';
            var latLng = ele.getPosition();
			document.getElementById("js_latitude").value = latLng.getLat().toFixed(10);
		    document.getElementById("js_longitude").value = latLng.getLng().toFixed(10);
			$('#pos_id').val(ele.adcode);
			var d_address=ele.address+ele.title;
			d_address=d_address.replace(ele.province,'').replace(ele.city,'').replace(ele.district,'');
			$('#searchSubmit').val(d_address);
			var optfullname='',optdistrict=$.trim(ele.district),optv=0;
			$('#districtS option').each(function(mm){
			   optfullname=$(this).data('fullname');
			   optfullname=$.trim(optfullname);
			   if(optfullname==optdistrict){
				 $(this).attr("selected",true);
				 optv=$(this).attr('value');
			     $('#districtS').val(optv);
				 $('#districtinfo').val(optv+'-'+optfullname);
			   }
			});
        } else {
            ele.isClicked = false;
            ele.div.isClicked = false;
        }
    });
}
/*var url4;
$(".search_t").autocomplete({
    source:function(request,response){
        url4 = encodeURI("http://apis.map.qq.com/ws/place/v1/suggestion/?keyword=" + request.term + "&region=" + curCity.children[0].innerHTML + "&key=K76BZ-W3O2Q-RFL5S-GXOPR-3ARIT-6KFE5&output=jsonp&&callback=?");
        $.getJSON(url4,function(result){

            response($.map(result.data,function(item){
                return({
                    label:item.title

                })
            }));
        });
    }
});*/

     function GetCity(){
	 var obj= $('#provinceS');
     var provinceid=obj.val();
     if(provinceid == -1){
     	$('#cityS').remove();
     	return;
     }
	 var provincename=obj.find("option:selected").data('fullname');
	 var lng=obj.find("option:selected").data('lng');
	 var lat=obj.find("option:selected").data('lat');
	 $('#provinceinfo').val(provinceid+'-'+provincename);
	 init(lat,lng,13);
	 var cityHtml='<select name="city" id="cityS" class="form-control" onchange="GetDistrict();"><option value="-1">请选择</option>'
	 $.post('<?php echo U("Manage/Users/getDistrict");?>',{fid:provinceid},function(ret){
	   if(ret.data){
	      $.each(ret.data,function(nn,vv){
		     cityHtml+='<option value="'+vv.id+'" data-fullname="'+vv.fullname+'" data-lng="'+vv.lng+'" data-lat="'+vv.lat+'" >'+vv.fullname+'</option>';
		  });
		  cityHtml+='</select>';
		  $('#cityS').remove();
		  $('#districtS').remove();
		  $('#circleS').remove();
		  obj.after(cityHtml);
	   }
	 },'JSON');
  }

 function GetDistrict(){
 	 var obj= $('#cityS');
     var cityid=obj.val();
     if(cityid == -1){
     	$('#districtS').remove();
     	return;
     }
	 var cityname=obj.find("option:selected").data('fullname');
	 var lng=obj.find("option:selected").data('lng');
	 var lat=obj.find("option:selected").data('lat');
	 $('#cityinfo').val(cityid+'-'+cityname);
	 init(lat,lng,15);
	 var cityHtml='<select name="district" id="districtS" class="form-control" onchange="GetCircle();"><option value="-1">请选择</option>'
	 $.post('<?php echo U("Manage/Users/getDistrict");?>',{fid:cityid},function(ret){
	   if(ret.data[0]){
	      $.each(ret.data,function(nn,vv){
		     cityHtml+='<option value="'+vv.id+'" data-fullname="'+vv.fullname+'" data-lng="'+vv.lng+'" data-lat="'+vv.lat+'" >'+vv.fullname+'</option>';
		  });
		  cityHtml+='</select>';
		  $('#districtS').remove();
		  $('#circleS').remove();
		  obj.after(cityHtml);
	   }
		
	 },'JSON');
 }
 function GetCircle(){
	var obj= $('#districtS');
	var districtid=obj.val();
	var districtname=obj.find("option:selected").data('fullname');
	var lng=obj.find("option:selected").data('lng');
	var lat=obj.find("option:selected").data('lat');
   $('#districtinfo').val(districtid+'-'+districtname);
   init(lat,lng,17);
    
 }

function subCategory(){
   var Cobj= $('#categoryid0');
   var cid=Cobj.val();
   var cname=Cobj.find("option:selected").data('cname');
   $('#categoryid0info').val(cid+'-'+cname);
   var 	Chtml='<select name="categoryid1" id="categoryid1" class="form-control" onchange="GetSubCy();" style=" margin-left: 20px;">';
   	 $.post('<?php echo U("Manage/Users/getCategory");?>',{cid:cid},function(ret){
	   if(ret.data){
	      $.each(ret.data,function(nn,vv){
			 if(nn==0){
			  $('#categoryid1info').val(vv.id+'-'+vv.name);
			 }
		     Chtml+='<option value="'+vv.id+'" data-scname="'+vv.name+'" >'+vv.name+'</option>';
		  });
		  Chtml+='</select>';
		  $('#categoryid1').remove();
		  Cobj.after(Chtml);
	   }
		
	 },'JSON');

}
subCategory();

function GetSubCy(){
   var Cobj= $('#categoryid1');
   var scid=Cobj.val();
   var scname=Cobj.find("option:selected").data('scname');
   $('#categoryid1info').val(scid+'-'+scname);
}

  </script>

</html>