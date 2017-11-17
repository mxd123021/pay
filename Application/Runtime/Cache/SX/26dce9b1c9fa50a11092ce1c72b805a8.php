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

    
    <link rel="stylesheet" type="text/css" href="/Static/weui/lib/weui.min.css"/>
    <link rel="stylesheet" type="text/css" href="/Static/weui/css/jquery-weui.min.css"/>
    <script type="text/javascript" src="/Static/weui/js/jquery-weui.min.js"></script>
    
    <link href="/Static/SX/css/plugins/custom.css" rel="stylesheet">
	<link href="/Static/SX/css/plugins/basic.css" rel="stylesheet">
    <link href="/Static/SX/css/plugins/dropzone.css" rel="stylesheet">
    <script src="/Static/SX/js/plugins/icheck.min.js"></script>
    <script src="/Static/SX/js/plugins/dropzone.js"></script>
	<style>
		.ibox-title h5 {
  			margin: 10px 0 0px;
		}
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
                    <h2>支付配置</h2>
                    <ol class="breadcrumb">
                        <li><a href="<?php echo U("SX/Index/index");?>">后台首页</a></li><li>系统配置</li>
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
            	<div class="col-lg-6">
            	    <div class="ibox float-e-margins">
            	        <div class="ibox-title clearfix">
            	            <h5 style="margin: 10px 0 0px;">支付配置</h5>
            	        </div>
            	        <div class="ibox-content">
							<div class="alert alert-warning">
                                                            请务必配置好您的银行渠道参数，线上系统运行时请勿随意改动此参数，避免出现运营事故。                               <!--1、请配置一个有特约商户管理功能的商户号相关微信支付信息，不然服务商模式不可用。-->
                               <br>
                               <!--2、发送红包和代收商户提现使用的是服务商配置，请保持服务商的可用余额足够使用。-->
                               <br>
							  <!--<strong style="color:red;">3、特别提醒：一旦配置好，请勿再改换配置。如果改换了配置，那么在原来的配置上所付款将不能退款，所发的卡券不能核销等等</strong>-->
                            </div>
							<table class="table table-striped">
            	                <tr>
            	                    <td><img style="margin-left: 15px" src="/Static/SX/images/pay_icon/guangda.png"></td>
            	                    <td style="padding-top: 14px;">银行通道</td>
            	                    <td><button class="btn btn-info " type="button" data-toggle="modal" data-target="#gdSetting"><i class="fa fa-paste"></i>配置信息</button></td>
            	                    <td></td>
            	                </tr>
<!--            	                <tr>
            	                    <td><img style="margin-left: 15px" src="/Static/SX/images/pay_icon/weixin.png"></td>
            	                    <td style="padding-top: 14px;">微信支付</td>
            	                    <td id="wxapiinfo1"><button class="btn btn-info " type="button" checked="checked" data-toggle="modal" data-target="#weixinSetting"><i class="fa fa-paste"></i>服务商配置</button></td>
            	                    <td id="wxapiinfo1"><button class="btn btn-info " type="button" checked="checked" data-toggle="modal" data-target="#weixinSetting2"><i class="fa fa-paste"></i>充值和代收配置</button></td>
									 <td id="wxapiinfo2"></td> 
            	                </tr>
								<tr>
            	                    <td><img style="margin-left: 15px" src="/Static/SX/images/pay_icon/alipay.png"></td>
            	                    <td style="padding-top: 14px;">支付宝支付</td>
            	                    <td><button class="btn btn-info " type="button" data-toggle="modal" data-target="#alipaySetting"><i class="fa fa-paste"></i>配置信息</button></td>
									<td></td>
            	                </tr>-->
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
                    <h4 class="modal-title">微信支付 服务商配置</h4>
                </div>
				<div class="modal-body">
					<div class="setting_rows">
						<div id="new_wxpay_box" class="wxpay_box">
							
							<div class="form-group">
								<label>Appid</label>
								<input type="text" placeholder="Appid" value="<?php echo ($configs['wx_appId']); ?>" name="wx_appId" class="form-control">
							</div>

							<div class="form-group">
								<label>AppSecret</label>
								<input type="text" placeholder="应用密钥" value="<?php echo ($configs['wx_appSecret']); ?>" name="wx_appSecret" class="form-control">
							</div>

							<div class="form-group">
								<label>微支付商户号</label>
								<input type="text" placeholder="商户号" value="<?php echo ($configs['wx_mchId']); ?>" name="wx_mchId" class="form-control">
							</div>
							<div class="form-group">
								<label>API密钥</label>
								<input type="text" placeholder="Api密钥" value="<?php echo ($configs['wx_apiSecret']); ?>" name="wx_apiSecret" class="form-control">
							</div>
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
	</div>

	<div class="modal inmodal" tabindex="-1" role="dialog" aria-hidden="true" id="weixinSetting2">
		<div class="modal-dialog">
			<div class="modal-content animated bounceInRight">
				<form action="" method="post" enctype="multipart/form-data">
				<div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">微信支付 充值和代收配置</h4>
                </div>
				<div class="modal-body">
					<div class="setting_rows">
						<div id="new_wxpay_box" class="wxpay_box">
							
							<div class="form-group">
								<label>Appid</label>
								<input type="text" placeholder="Appid" value="<?php echo ($configs['ds_wx_appId']); ?>" name="ds_wx_appId" class="form-control">
							</div>

							<div class="form-group">
								<label>AppSecret</label>
								<input type="text" placeholder="应用密钥" value="<?php echo ($configs['ds_wx_appSecret']); ?>" name="ds_wx_appSecret" class="form-control">
							</div>

							<div class="form-group">
								<label>微支付商户号</label>
								<input type="text" placeholder="商户号" value="<?php echo ($configs['ds_wx_mchId']); ?>" name="ds_wx_mchId" class="form-control">
							</div>
							<div class="form-group">
								<label>API密钥</label>
								<input type="text" placeholder="Api密钥" value="<?php echo ($configs['ds_wx_apiSecret']); ?>" name="ds_wx_apiSecret" class="form-control">
							</div>
							<div class="form-group uploade">
								<label>apiclient_cert私钥文件</label>
								<input type="text" placeholder="apiclient_cert私钥文件" <?php if( $configs['ds_wx_apiclientCert'] != '' ): ?>value="pem文件已上传" readonly="readonly" <?php else: ?> value=""<?php endif; ?> class="form-control" >
								<input type="hidden" placeholder="apiclient_cert私钥文件" value="<?php echo (urldecode($configs['ds_wx_apiclientCert'])); ?>" name="ds_wx_apiclientCert" class="hiddeninput">
								<div class="dropz" style="height: 34px;line-height: 34px;border: 1px solid #e5e6e7;width: 70px;text-align: center;position: relative;top: -34px;float: right;cursor: pointer;">文件上传</div>
							</div>
							<div class="form-group uploade">
								<label>apiclient_key公钥文件</label>
								<input type="text" placeholder="apiclient_key公钥文件" <?php if( $configs['ds_wx_apiclientKey'] != '' ): ?>value="pem文件已上传" readonly="readonly" <?php else: ?> value=""<?php endif; ?> class="form-control">
								<input type="hidden" placeholder="apiclient_key公钥文件" value="<?php echo (urldecode($configs['ds_wx_apiclientKey'])); ?>" name="ds_wx_apiclientKey" class="hiddeninput">
								<div class="dropz" style="height: 34px;line-height: 34px;border: 1px solid #e5e6e7;width: 70px;text-align: center;position: relative;top: -34px;float: right;cursor: pointer;">文件上传</div>
							</div>
								<div class="form-group uploade">
								<label>CA证书文件</label>
								<input type="text" placeholder="微信支付rootca文件" <?php if( $configs['ds_wx_apiclientCa'] != '' ): ?>value="pem文件已上传" readonly="readonly" <?php else: ?> value=""<?php endif; ?>  class="form-control">
								<input type="hidden" placeholder="微信支付rootca文件" value="<?php echo (urldecode($configs['ds_wx_apiclientCa'])); ?>" name="ds_wx_apiclientCa" class="hiddeninput">
								<div class="dropz" style="height: 34px;line-height: 34px;border: 1px solid #e5e6e7;width: 70px;text-align: center;position: relative;top: -34px;float: right;cursor: pointer;">文件上传</div>
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
	</div>

	<div class="modal inmodal" tabindex="-1" role="dialog" aria-hidden="true" id="gdSetting">
		<div class="modal-dialog">
			<div class="modal-content animated bounceInRight">
				<form action="" method="post" enctype="multipart/form-data">
				<div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">银行渠道配置</h4>
                </div>
				<div class="modal-body">
					<div class="setting_rows">
						<div id="new_wxpay_box" class="wxpay_box">
							<div class="form-group">
								<label>Appid</label>
								<input type="text" placeholder="Appid" value="<?php echo ($configs['gd_appId']); ?>" name="gd_appId" class="form-control">
							</div>
							<div class="form-group">
								<label>AppSecret</label>
								<input type="text" placeholder="应用密钥" value="<?php echo ($configs['gd_appSecret']); ?>" name="gd_appSecret" class="form-control">
							</div>
							<div class="form-group">
								<label>渠道号</label>
								<input type="text" placeholder="渠道号" value="<?php echo ($configs['gd_mchId']); ?>" name="gd_mchId" class="form-control">
							</div>
							<div class="form-group">
								<label>大商户号</label>
								<input type="text" placeholder="大商户号" value="<?php echo ($configs['gd_parentMid']); ?>" name="gd_parentMid" class="form-control">
							</div>
							<div class="form-group">
								<label>渠道密钥</label>
								<input type="text" placeholder="渠道密钥" value="<?php echo ($configs['gd_key']); ?>" name="gd_key" class="form-control">
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

	<div class="modal inmodal" tabindex="-1" role="dialog" aria-hidden="true" id="alipaySetting">
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
	</div>



	
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
				$.post('<?php echo U("SX/Index/savePayinfo");?>',{data:htmlToArray(payConfigData)},function(result){
					if(result.status == 1){
						swal({title: "温馨提示",text:'修改成功',type: "success"}, function () {
        					window.location.reload();
   						});
					}else{
						swal({title: "温馨提示",text:'修改失败！',type: "error"});
					}
				},'json');
			});
			/*$('.isOpen').on('ifChanged', function(){
				if($(this).is(':checked')){
					var isOpen = 1;
				}else{
					var isOpen = 0;
				}
				var payConfigData = {};
				typeof(payConfigData[$(this).attr('data-type')]) == 'undefined' && (payConfigData[$(this).attr('data-type')] = {}),payConfigData[$(this).attr('data-type')].isOpen = isOpen;
				$.post('?m=System&c=pay&a=config',{data:payConfigData});
			});*/
			$(".dropz").dropzone({
				url: "<?php echo U('SX/Index/pem_Upload');?>",
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