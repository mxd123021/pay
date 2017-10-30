<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>实名认证审核 - 网站管理后台</title>
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
    
    <link href="/Static/SX/css/footable.core.css" rel="stylesheet">
	<link href="/Static/SX/css/plugins/custom.css" rel="stylesheet">
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
                            <?php if(in_array('sys_wz',session('SX_STAFF.grant'))): ?><li <?php if((CONTROLLER_NAME) == "Index"): if((ACTION_NAME) == "toConfig"): ?>class="active"<?php endif; endif; ?>><a href="<?php echo U("SX/Index/toConfig");?>">网站配置</a></li><?php endif; ?>
                            <?php if(in_array('sys_zf',session('SX_STAFF.grant'))): ?><li <?php if((CONTROLLER_NAME) == "Index"): if((ACTION_NAME) == "payConfig"): ?>class="active"<?php endif; endif; ?>><a href="<?php echo U("SX/Index/payConfig");?>">支付配置</a></li><?php endif; ?>
                            <?php if(in_array('sys_wx',session('SX_STAFF.grant'))): ?><li <?php if((CONTROLLER_NAME) == "Index"): if((ACTION_NAME) == "wxConfig"): ?>class="active"<?php endif; endif; ?>><a href="<?php echo U("SX/Index/wxConfig");?>">微信配置</a></li><?php endif; ?>
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
                <?php if(in_array('market_ad',session('SX_STAFF.grant')) or in_array('market_re',session('SX_STAFF.grant'))): ?><li <?php if((CONTROLLER_NAME) == "Market"): ?>class="active"<?php endif; ?>>
                        <a href="#"><i class="fa fa-slideshare"></i> <span class="nav-label">营销功能</span><span class="label label-info pull-right">NEW</span></a>
                        <ul class="nav nav-second-level collapse <?php if((CONTROLLER_NAME) == "Market"): ?>in<?php endif; ?>">
                            <?php if(in_array('market_ad',session('SX_STAFF.grant'))): ?><li <?php if((CONTROLLER_NAME) == "Market"): if((ACTION_NAME) == "wxmarket"): ?>class="active"<?php endif; endif; ?>><a href="<?php echo U("SX/Market/wxmarket");?>">微信广告</a></li><?php endif; ?>
<!--                            <?php if(in_array('market_re',session('SX_STAFF.grant'))): ?><li <?php if((CONTROLLER_NAME) == "Market"): if((ACTION_NAME) == "rlist"): ?>class="active"<?php endif; endif; ?>><a href="<?php echo U("SX/Market/rlist");?>">预约留言</a></li><?php endif; ?>-->
                        </ul>
                    </li><?php endif; ?>
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
                    <a class="dropdown-toggle count-info" href="/merchants.php?m=Index&c=help&a=index" title="帮助文档" target="_blank">
                        <i class="fa  fa-question-circle"></i> <!--<span class="label label-warning">16</span>-->
                    </a>
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
                    <h2>实名认证审核</h2>
                    <ol class="breadcrumb">
                        <li><a href="<?php echo U("SX/Index/index");?>">后台首页</a></li><li>网站商家</li>
                        <li class="active">
                            <strong>实名认证审核</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>

<div class="wrapper wrapper-content animated fadeInRight">
    
            <div class="row">
				<form id="employersForm" class="form" action="<?php echo U('SX/Users/savegdStore');?>" method="post">
	            <input type="hidden" id="userId" name="id" value="<?php echo ($userinfo['userId']); ?>">
				<div class="col-lg-12">
					<div class="ibox">
            	    	<div class="ibox-title">
            	       		<h5>商户详细信息</h5>
            	    	</div>
						<div class="ibox-content">
							<?php if(($userinfo['userAudit'] == 3) and ($userinfo['reson'] != '')): ?><div class="alert alert-danger alert-dismissable" style="margin-bottom: 10px;">
		                        	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>失败原因：<?php echo ($userinfo['reson']); ?>
		            			</div><?php endif; ?>
							<div class="form-group">
								<label><span class="mustInput">*</span>店铺/商户简称</label>
								<input type="tel" id="zz_jc" placeholder="请输入店铺/商户简称" name="zz_jc" value="<?php echo ($userinfo['zz_jc']); ?>" class="form-control required" disabled>
							</div>
							<div class="form-group">
								<label><span class="mustInput">*</span>商户类型</label><font color="green">(个体/企业)</font><label>:</label>
								<select style="width: 20%" name="zz_jyxz" class="form-control" disabled>
									<option value="2" <?php if(($userinfo["zz_jyxz"]) == "2"): ?>selected<?php endif; ?>>个体</option>
									<option value="1" <?php if(($userinfo["zz_jyxz"]) == "1"): ?>selected<?php endif; ?>>企业</option>
								</select>
							</div>
							<div class="form-group">
								<label><span class="mustInput">*</span>行业类别</label><label>:</label>
								<div>
									<select style="width: 20%; float: left;" class="form-control" onchange="getGdCategory(this)" disabled>
										<option value="-1">请选择</option>
										<option value="个体工商户" <?php if(($incategory["namearr"]["0"]) == "个体工商户"): ?>selected<?php endif; ?>>个体工商户</option>
										<option value="企业" <?php if(($incategory["namearr"]["0"]) == "企业"): ?>selected<?php endif; ?>>企业</option>
										<option value="事业单位" <?php if(($incategory["namearr"]["0"]) == "事业单位"): ?>selected<?php endif; ?>>事业单位</option>
									</select>
									<div id="incatory" style="float: left; width: 80%;">
										<?php if($incategory != ''): ?><select style="float:left; width:20%" class="form-control" onchange="getGdCategory2(this);" disabled>
												<option value="-1">请选择</option>
												<?php foreach($incategory['level2'] as $in=>$iv){ $name = explode("-",$iv['name']); if(in_array($name[1],$names)==false) { $names[] = $name[1]; $sel = ""; if($name[1] == $incategory['namearr'][1]){ $sel = "selected"; } echo "<option value='$name[1]' $sel>$name[1]</option>"; } }?>
											</select>
											<div id="incatory2" style="float:left">
												<select class="form-control" name="incode" disabled>
													<option value="-1">请选择</option>
													<?php foreach($incategory['level3'] as $in=>$iv){ $name = explode("-",$iv['name']); $sel = ""; if($iv[code] == $incategory['code']){ $sel = "selected"; } echo "<option value='$iv[code]' $sel>$name[2]</option>"; }?>
												</select>
											</div><?php endif; ?>
									</div>
								</div>
							</div>
							
							<div class="clearfix"></div>
                                                        <div class="form-group">
								<label><span class="mustInput">*</span>是否渠道授权交易</label><font color="green">(渠道授权交易：选是则在好付云平台上能直接进行交易)</font><label>:</label>
								<select name="zz_sftype" class="form-control">
									<option value="1" <?php if(($userinfo["zz_payAuth"]) == "1"): ?>selected<?php endif; ?>>是</option>
									<option value="0" <?php if(($userinfo["zz_payAtuh"]) == "0"): ?>selected<?php endif; ?>>否</option>
								</select>
							</div>
							<div class="form-group" style="margin-top: 15px;">
								<label><span class="mustInput">*</span>开户人/开户名称</label><font color="green">(姓名需与证件上的姓名一致,对私账户填开户人名称/对公账户填公司名称)</font><label>:</label>
								<input type="tel" id="zz_name" placeholder="请输入证件持有人姓名" name="zz_name" value="<?php echo ($userinfo['zz_name']); ?>" class="form-control required" disabled>
							</div>
							
							<div class="form-group">
								<label><span class="mustInput">*</span>证件有效期</label><font color="green">(如 2035-4-1)</font><label>:</label>
								<input type="text" id="zz_yxq" placeholder="请输入正确的证件有效期" name="zz_yxq" value="<?php echo ($userinfo['zz_yxq']); ?>" class="form-control required" disabled>
							</div>

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

							<div class="form-group">
								<label>主体全称</label><font color="green">(商户名称需与营业执照登记公司名称一致)</font><label>:</label>
								<input type="tel" id="zz_shname" placeholder="请输入企业/商户名称" name="zz_shname" value="<?php echo ($userinfo['zz_shname']); ?>" class="form-control" disabled>
							</div>
							<div class="form-group">
								<label>注册/地址</label><font color="green">(与营业执照登记的注册地址一致)</font><label>:</label>
								<input type="tel" id="zz_zcdz" placeholder="注册/地址" name="zz_zcdz" value="<?php echo ($userinfo['zz_zcdz']); ?>" class="form-control" disabled>
							</div>
							<div class="form-group">
								<label>营业执照注册号:</label>
								<input type="tel" id="zz_license" placeholder="请输入营业执照注册号" name="zz_license" value="<?php echo ($userinfo['zz_license']); ?>" class="form-control" disabled>
							</div>
						</div>
            	    </div>
            	</div>

				<div class="col-lg-12">
					<div class="ibox">
            	    	<div class="ibox-title">
            	       		<h5>银行信息</h5>
            	    	</div>
            	    	<div class="ibox-content">
							<div class="form-group">
								<label><span class="mustInput">*</span>账户类型:</label>
								<select name="zz_banktype" class="form-control" disabled>
									<option value="2" <?php if(($userinfo["zz_banktype"]) == "2"): ?>selected<?php endif; ?>>个人账户</option>
									<option value="1" <?php if(($userinfo["zz_banktype"]) == "1"): ?>selected<?php endif; ?>>对公账户</option>
								</select>
							</div>
							<div class="form-group">
								<label><span class="mustInput">*</span>开户人/开户名称:</label><font color="green">(对私账户填开户人名称/对公账户填公司名称)</font><label>:</label>
								<input id="zz_accountname" placeholder="请输入银行开户时所填名称" name="zz_accountname" value="<?php echo ($userinfo['zz_accountname']); ?>" class="form-control required" disabled>
							</div>
							<div class="form-group">
								<label><span class="mustInput">*</span>开户银行:</label>
								<select name="zz_bank" class="form-control required" disabled>
		   						  <option value="" <?php if(($userinfo["zz_bank"]) == "0"): ?>selected<?php endif; ?>>请选择</option>
									<?php foreach($bank as $akk=>$avv){?>
									  <option value="<?php echo $avv['codeid']?>" <?php if(($userinfo["zz_bank"]) == $avv["codeid"]): ?>selected<?php endif; ?>><?php echo $avv['name']?></option>
									<?php }?>
								</select>
							</div>
							<div class="form-group">
								<label><span class="mustInput">*</span>开户银行全称</label><font color="green">(详细到支行)</font><label>:</label>
								<input onkeyup="getBankBranch(this,event)" onblur="hidebank()" autocomplete="off" type="tel" id="zz_bankqc" placeholder="开户银行全称" name="zz_bankqc" value="<?php echo ($userinfo['zz_bankqc']); ?>" class="form-control inp_unselected required" disabled>
								<div id="bankinfo" style="position: absolute; width: 50%; display: none;">
                                                        </div>
                                                        <input id="backcode" name="backcode" value="<?php echo ($userinfo['backcode']); ?>" type="hidden">
							</div>
                                                        <div class="clearfix"></div>
							<div class="form-group" style="margin-top: 15px;">
								<label><span class="mustInput">*</span>所在地区</label><font color="green">(开户行地址)</font><label>:</label>
								<div>
									<select style="width: 20%; float: left;" class="form-control" onchange="getGdCity(this)" name="fid" disabled>
										<option value="-1">请选择</option>
										<?php foreach($district as $akk=>$avv){ $sel = ""; if($avv['sid'] == $userinfo['fid']){ $sel = "selected"; } echo "<option value='$avv[sid]' $sel>$avv[name]</option>"; }?>
									</select>
									<div id="city" style="float: left; width: 80%;">
										<?php if(district2 != ''): ?><select style="float:left; width:20%" class="form-control" name="sid" disabled>
												<option value="-1">请选择</option>
												<?php foreach($district2 as $akk=>$avv){ $sel = ""; if($avv['sid'] == $userinfo['sid']){ $sel = "selected"; } echo "<option value='$avv[sid]' $sel>$avv[name]</option>"; }?>
											</select><?php endif; ?>
									</div>
								</div>
							</div>
                                                        <div class="form-group">
								<label><span class="mustInput">*</span>证件类型</label><font color="green">(身份证/护照)</font><label>:</label>
								<select name="zz_sftype" class="form-control" disabled>
									<option value="1" <?php if(($userinfo["zz_sftype"]) == "1"): ?>selected<?php endif; ?>>身份证</option>
									<option value="2" <?php if(($userinfo["zz_sftype"]) == "2"): ?>selected<?php endif; ?>>护照</option>
								</select>
							</div>
							<div class="form-group">
								<label><span class="mustInput">*</span>证件号码</label><font color="green">(如 51010719881111****)</font><label>:</label>
								<input type="tel" id="zz_sfz" placeholder="请输入企业法人/经办人证件号码" name="zz_sfz" value="<?php echo ($userinfo['zz_sfz']); ?>" class="form-control required" disabled>
							</div>
							<div class="form-group">
								<label><span class="mustInput">*</span>预留手机号:</label><font color="green">(在银行开卡所填手机号)</font><label>:</label>
								<input type="tel" id="bankPhone" placeholder="请输入银行预留手机号" name="bankPhone" value="<?php echo ($userinfo['bankPhone']); ?>" class="form-control required" disabled>
							</div>
							<div class="form-group">
								<label><span class="mustInput">*</span>结算账户</label><font color="green">(企业需对公账户，个体户提供法人名下4、5、6开头的个人银行卡号)</font><label>:</label>
								<input type="tel" id="zz_bankinfo" placeholder="请输入银行账号" name="zz_bankinfo" value="<?php echo ($userinfo['zz_bankinfo']); ?>" class="form-control required" disabled>
							</div>
            			</div>
					</div>
				</div>

				<div class="col-lg-12">
					<div class="ibox">
            	    	<div class="ibox-title">
            	       		<h5>结算费率</h5>
            	    	</div>
            	    	<div class="ibox-content">
            	    		<div class="alert alert-info alert-dismissable" style="margin-bottom: 10px;">
                        		<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>商户结算费率，参数单位为‰(千分比)，如商户费率为6‰(千分之6)，则费率为6
                        	</div>
							<div class="form-group">
								<label><span class="mustInput">*</span>微信扫码支付费率:</label><font color="green">(微信立即支付二维码)</font><label>:</label>                                                                                                                            
                                                            <div class="input-group">
                                                                <input placeholder="请输入微信扫码支付费率" id="wx_native" name="rate[wx_native]" value="<?php echo ($rate['wx_native']); ?>" class="form-control ckinput required"> 
                                                                <span class="input-group-btn"> <button id="addPayType" name="wx_native" type="button" class="btn btn-primary addPayType">开通</button> </span>
                                                            </div>
							</div>
							<div class="form-group">
								<label><span class="mustInput">*</span>微信刷卡支付费率:</label><font color="green">(微信条码枪支付)</font><label>:</label>								
                                                            <div class="input-group">
                                                                <input placeholder="请输入微信刷卡支付费率" id="wx_micropay" name="rate[wx_micropay]" value="<?php echo ($rate['wx_micropay']); ?>" class="form-control required"> 
                                                                <span class="input-group-btn"> <button id="addPayType" name="wx_micropay" type="button" class="btn btn-primary addPayType">开通</button> </span>
                                                            </div>
							</div>
							<div class="form-group">
								<label><span class="mustInput">*</span>微信公众号支付费率:</label><font color="green">(永久二维码支付和网页支付)</font><label>:</label>								 
                                                            <div class="input-group">
                                                                <input placeholder="请输入微信公众号支付费率" id="wx_jspay" name="rate[wx_jspay]" value="<?php echo ($rate['wx_jspay']); ?>" class="form-control required"> 
                                                                <span class="input-group-btn"> <button id="addPayType" name="wx_jspay" type="button" class="btn btn-primary addPayType">开通</button> </span>
                                                            </div>
							</div>
							<div class="form-group">
								<label><span class="mustInput">*</span>支付宝扫码支付费率:</label><font color="green">(支付宝立即支付二维码)</font><label>:</label>
<!--								 <button id="addPayType" type="button" class="btn btn-primary pull-right addPayType" style=" margin-bottom: 3px;">开通支付</button>
                                                                <input placeholder="请输入支付宝扫码支付费率" name="rate[ali_native]" value="<?php echo ($rate['ali_native']); ?>" class="form-control required">-->
                                                            <div class="input-group">
                                                                <input placeholder="请输入支付宝扫码支付费率" id="ali_native" name="rate[ali_native]" value="<?php echo ($rate['ali_native']); ?>" class="form-control required"> 
                                                                <span class="input-group-btn"> <button id="addPayType" name="ali_native" type="button" class="btn btn-primary addPayType">开通</button> </span>
                                                            </div>
							</div>
							<div class="form-group">
								<label><span class="mustInput">*</span>支付宝刷卡支付费率:</label><font color="green">(支付宝条码枪支付)</font><label>:</label>
<!--								 <button id="addPayType" type="button" class="btn btn-primary pull-right addPayType" style=" margin-bottom: 3px;">开通支付</button>
                                                                <input placeholder="请输入支付宝扫码支付费率" name="rate[ali_micropay]" value="<?php echo ($rate['ali_micropay']); ?>" class="form-control required">-->
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" placeholder="请输入支付宝刷卡支付费率" id="ali_micropay" name="rate[ali_micropay]" value="<?php echo ($rate['ali_micropay']); ?>" > 
                                                                <span class="input-group-btn"> <button id="addPayType" name="ali_micropay" type="button" class="btn btn-primary addPayType">开通</button> </span>
                                                            </div>
							</div>
							<div class="form-group">
								<label><span class="mustInput">*</span>支付宝公众号支付费率:</label><font color="green">(永久二维码支付和网页支付)</font><label>:</label>								
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" placeholder="请输入支付宝公众号支付费率" id="ali_jspay" name="rate[ali_jspay]" value="<?php echo ($rate['ali_jspay']); ?>"> 
                                                                <span class="input-group-btn"> <button id="addPayType" name="ali_jspay" type="button" class="btn btn-primary addPayType">开通</button> </span>
                                                            </div>
							</div>
                            
                                                        <div class="form-group">
                                                            <label><span class="mustInput">*</span>微信APP支付费率:</label><font color="green"></font><label>(暂时未开放)</label>
<!--                                                            <div class="input-group">
                                                                <input id="username" type="text" class="form-control" placeholder="6" value="<?php echo I('username');?>"> 
                                                                <span class="input-group-btn"> <button id="search" type="button" class="btn btn-primary">开通</button> </span>
                                                            </div>-->
                                                             <div class="input-group">
                                                                <input placeholder="请输入微信APP支付费率" name="" value="" class="form-control required"> 
                                                                <span class="input-group-btn"> <button id="addPayType" name="wx_app" type="button" class="btn btn-primary addPayType">开通</button> </span>
                                                            </div>
                                                        </div>
            			</div>
					</div>
				</div>

				<div id="reson" class="col-lg-12" style="display: none;">
					<div class="ibox">
            	    	<div class="ibox-title">
            	       		<h5>拒绝原因</h5>
            	    	</div>
            	    	<div class="ibox-content">
							<div class="form-group">
								<label><span class="mustInput">*</span>拒绝原因:</label>
								<div class="input-group">
                                    <input type="text" class="form-control" placeholder="请输入拒绝商户的原因" name="reson" value="<?php echo ($userinfo['reson']); ?>"><span class="input-group-btn"><button id="refused" type="button" class="btn btn-primary">提交
                                    </button></span>
                                </div>
							</div>
            			</div>
					</div>
				</div>
				
				<div class="col-lg-12">
					<?php if($userinfo['userAudit'] == 2): ?><!-- <button type="submit" class="btn btn-primary pull-right">进件</button> -->
               	 		<!--<button type="submit" class="btn btn-primary pull-right">兴业进件</button>-->
               	 	<?php else: ?>
               	 		<!--<button id="updaterate" type="button" class="btn btn-primary pull-right">修改费率</button>--><?php endif; ?>
               	 	<?php if($userinfo['userAudit'] != 1): ?><button id="success" type="button" style="margin-right: 10px;" class="btn btn-primary pull-right">通过</button><?php endif; ?>
					<?php if($userinfo['userAudit'] == 1 and $userinfo['gd_mchId'] != ''): ?><button id="rebank" type="button" style="margin-right: 10px;" class="btn btn-danger pull-right">拒绝银行信息</button><?php endif; ?>
               	 	<button id="showrefused" type="button" style="margin-right: 10px;" class="btn btn-danger pull-right">拒绝</button>
           		</div>
	           	
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

    
<div id="loadingToast" class="weui_loading_toast" style="display: none;">
<div class="weui_mask_transparent"></div>
    <div class="weui_toast">
        <div class="weui_loading">
            <div class="weui_loading_leaf weui_loading_leaf_0"></div>
            <div class="weui_loading_leaf weui_loading_leaf_1"></div>
            <div class="weui_loading_leaf weui_loading_leaf_2"></div>
            <div class="weui_loading_leaf weui_loading_leaf_3"></div>
            <div class="weui_loading_leaf weui_loading_leaf_4"></div>
            <div class="weui_loading_leaf weui_loading_leaf_5"></div>
            <div class="weui_loading_leaf weui_loading_leaf_6"></div>
            <div class="weui_loading_leaf weui_loading_leaf_7"></div>
            <div class="weui_loading_leaf weui_loading_leaf_8"></div>
            <div class="weui_loading_leaf weui_loading_leaf_9"></div>
            <div class="weui_loading_leaf weui_loading_leaf_10"></div>
            <div class="weui_loading_leaf weui_loading_leaf_11"></div>
        </div>
        <p class="weui_toast_content" id="toast_content">正在解除绑定</p>
    </div>
</div>
    
<script>
$(document).ready(function(){

    $("#employersForm").submit(function(){
        
        var self = $(this);
        $.ajax({
            type:     "POST",
            url:    "<?php echo U('SX/Users/savegdStore');?>",
            data:     self.serialize(),
            dataType:   "json",
            beforeSend: beforeSend,
            dataFilter: dataFilter,
            complete:   complete,
            success:  success,
        });
        return false;

        function beforeSend()
        {
            $.showLoading();
        }
        function dataFilter(data)
        {
            $.hideLoading();
            return data;
        }
        function success(data)
        {
            if(data.status)
            {
                $.toast(data.info, function(){
                    window.location.href = data.url;
                });
            }
            else
            {
                $.toast(data.info, 'forbidden');
            }
        }
        function complete(){}
    });


	$("#showrefused").click(function(){
	  $('#reson').show();
	});
        
        $(".addPayType").click(function(){
//            var type = $(this).next().attr('name');
//            var rate = $(this).prev().prev().val();
            var type = $(this).attr('name');
            var rate = $('#'+type).val();
            var userId = $('#userId').val();
//            swal('hello '+type + '  '+rate);
//            return;
             swal({  
            title: "确定开通",  
            text: "确定开通吗？开通后，会立即生效~!"+type+' '+rate+ ' '+userId,  
            type: "warning",  
            showCancelButton: true,  
            confirmButtonColor: "#DD6B55",  
            confirmButtonText: "开通",  
            cancelButtonText: "取消",  
        closeOnConfirm: false,  
        closeOnCancel: true },  
        function (isConfirm) {  
      if (isConfirm) {  
//               $('#toast_content').html('请耐心等待...');
                $.ajax({
                    type: "POST",
                    url: "<?php echo U('SX/Users/addPayType');?>",
                    data: {'type': type, 'rate':rate, 'userId':userId},
                    dataType: "json",
                    beforeSend: function(){
//                        $('#loadingToast').show();
                    },
                    success: function(data){
                            $('#loadingToast').hide();
                            if(data.status == 1){
                                swal(data.msg, 'success');
                            }else{
                                swal('开通支付失败！'+data.msg, 'fail');
                            }
                                                    
                              },
                    error:function(XMLHttpRequest, textStatus, errorThrown){
//                        $('#loadingToast').hide();
//                        $('#popPay').hide();
                        swal('请求失败， textStatus:'+textStatus, 'error');
                    }
                });  
       } else {  
                   
             }  
        });  

        });

	$("#updaterate").click(function(){
		$.ajax({
			url:'<?php echo U("SX/Users/updaterate");?>',
			type:"post",
			data:$('form').serialize(),
			dataType:"JSON",
			success:function(ret){
				if(ret.status == 1){
					swal({
					  title: "本地费率修改成功",
					  text: "请确认是否跟银行一致，修改功能只修改本地数据",
					  type: "success"
					 }, function () {
					   window.location.reload();
					});
				}else{
					swal({
					  title: "修改失败",
					  type: "error"
					 }, function () {
					});
			   }
			}
		});
	});

	$("#rebank").click(function(){
		$.ajax({
			url:'<?php echo U("SX/Users/rebank");?>',
			type:"post",
			data:$('form').serialize(),
			dataType:"JSON",
			success:function(ret){
				if(ret.status == 1){
					swal({
					  title: "拒绝成功",
					  type: "success"
					 }, function () {
					   window.location.reload();
					});
				}else{
					swal({
					  title: "拒绝失败！",
					  type: "error"
					 }, function () {
					});
			   }
			}
		});
	});

	$("#refused").click(function(){
		$.ajax({
			url:'<?php echo U("SX/Users/refused");?>',
			type:"post",
			data:$('form').serialize(),
			dataType:"JSON",
			success:function(ret){
				if(ret.status == 1){
					swal({
					  title: "拒绝成功",
					  type: "success"
					 }, function () {
					   window.location.reload();
					});
				}else{
					swal({
					  title: "拒绝失败！",
					  type: "error"
					 }, function () {
					});
			   }
			}
		});
	});

	$("#success").click(function(){
		$.ajax({
			url:'<?php echo U("SX/Users/successAudit");?>',
			type:"post",
			data:$('form').serialize(),
			dataType:"JSON",
			success:function(ret){
				if(ret.status == 1){
					swal({
					  title: "通过成功",
					  type: "success"
					 }, function () {
					   window.location.reload();
					});
				}else{
					swal({
					  title: "通过失败！",
					  type: "error"
					 }, function () {
					});
			   }
			}
		});
	});
});
</script>

</html>