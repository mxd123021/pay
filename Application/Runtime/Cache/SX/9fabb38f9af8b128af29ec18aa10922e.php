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
    
	<link href="/Static/SX/css/footable.core.css" rel="stylesheet">

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
		<div class="col-sm-7">
	        <div class="tabs-container">
	            <div class="tabs-left">
	                <ul class="nav nav-tabs">
	                    <li class="active"><a data-toggle="tab" href="tabs_panels.html#tab-1" aria-expanded="true">基本配置</a>
	                    </li>
	                    <li class=""><a data-toggle="tab" href="tabs_panels.html#tab-2" aria-expanded="false">消息推送</a>
	                    </li>
	                </ul>
	                <div class="tab-content ">
						<div id="tab-1" class="tab-pane active">
							<div class="panel-body">
								<form action enctype="multipart/form-data" onsubmit="return false">
									<div class="form-group"><label>微信公众号</label>
										<input type="text" placeholder="公众号码" name="wxId" class="form-control" value="<?php echo ($configs["wxId"]); ?>">
									</div>
	                                <div class="form-group">
	                                	<button id="saveWxconfig" class="btn btn-primary pull-right" type="submit">保存内容</button>
	                                </div>
	                            </form>
							</div>
	                	</div>
	                    <div id="tab-2" class="tab-pane">
							<div class="panel-body">
	                            <div class="panel-group" id="accordion">
	                                <div class="panel panel-default">
	                                    <div class="panel-heading">
	                                        <h5 class="panel-title">
	                                                <a data-toggle="collapse" data-parent="#accordion" href="tabs_panels.html#collapseOne">支付成功</a>
	                                            </h5>
	                                    </div>
	                                    <div id="collapseOne" class="panel-collapse collapse in">
	                                        <div class="panel-body panel-info" style="margin-left: 0; width: 100%; border: none; border-top: 1px solid #ddd;">
	                                        		<form action enctype="multipart/form-data" onsubmit="return false">
	                                        			<?php if(isset($paytemp)){ ?>
														<div class="form-group"><label>模板ID</label>
															<div class="input-group">
						                                        <input type="text" class="form-control" name="tempId" id="tempIdzf" value="<?php echo $paytemp['tempId'] ?>"> <span class="input-group-btn"> <button type="button" class="btn btn-primary" onclick="wxTemplateset('zf');">获取模板</button></span>
						                                    </div>
						                                    <div id="divtempzf" style="margin-top: 10px;">
						                                    	<div class="form-group"><label>标题</label><input type="text" placeholder="标题" name="first" class="form-control" value="<?php echo $paytemp['first'] ?>"></div>
						                                    	<?php
 $i=0; foreach($paytemp as $key=>$vo){ if(strpos($key,'keyword')===0){ $i++; ?>
																	<div class="form-group"><label><?php echo $paytemp[kname.$i]; ?></label><select class="form-control m-b" name="keyword<?php echo $i;?>"><option value="1" <?php if($paytemp[keyword.$i]==1){echo selected;}?>>商品金额</option><option value="2" <?php if($paytemp[keyword.$i]==2){ echo selected;}?>>商户名称</option><option value="3" <?php if($paytemp[keyword.$i]==3){ echo selected;}?>>支付方式</option><option value="4" <?php if($paytemp[keyword.$i]==4){ echo selected;}?>>交易单号</option><option value="5" <?php if($paytemp[keyword.$i]==5){ echo selected;}?>>交易时间</option></select><input name="keyname<?php echo $i;?>" type="hidden" value="<?php echo $paytemp[keyname.$i]; ?>"><input name="kname<?php echo $i;?>" type="hidden" value="<?php echo $paytemp[kname.$i]; ?>"></div>
						                                    	<?php }}?>
						                                    	<div class="form-group"><label>备注</label><input type="text" placeholder="备注" name="remark" class="form-control" value="<?php echo $paytemp['remark']; ?>"></div>
						                                    	<div class="form-group"><label>URL地址</label><input type="text" placeholder="点击跳转地址" name="url" class="form-control" value="<?php echo $paytemp['url']; ?>"></div>
						                                    	<div class="well"><h5>内容示例</h5><?php echo $paytemp['example']; ?></div><input name="example" type="hidden" value="<?php echo $paytemp['example']; ?>">
						                                    </div>
						                                    <button id="saveTempzf" class="btn btn-primary pull-right" type="submit">保存内容</button>
	                                                    </div>
	                                                    <?php }else{ ?>
														<div class="form-group"><label>模板ID</label>
															<div class="input-group">
						                                        <input type="text" class="form-control" name="tempId" id="tempIdzf"> <span class="input-group-btn"> <button type="button" class="btn btn-primary" onclick="wxTemplateset('zf');">获取模板</button></span>
						                                    </div>
						                                    <div id="divtempzf" style="margin-top: 10px;">
						                                    </div>
						                                    <button id="saveTempzf" class="btn btn-primary pull-right" type="submit" style="display: none;">保存内容</button>
	                                                    </div>
	                                                    <?php } ?>
                                                    </form>
	                                        </div>

	                                    </div>
	                                </div>
	                                <div class="panel panel-default">
	                                    <div class="panel-heading">
	                                        <h4 class="panel-title">
	                                                <a data-toggle="collapse" data-parent="#accordion" href="tabs_panels.html#collapseTwo">用户绑定</a>
	                                            </h4>
	                                    </div>
	                                    <div id="collapseTwo" class="panel-collapse collapse">
	                                        <div class="panel-body panel-info" style="margin-left: 0; width: 100%; border: none; border-top: 1px solid #ddd;">
	                                        		<form action enctype="multipart/form-data" onsubmit="return false">
	                                        			<?php if(isset($bindtemp)){ ?>
														<div class="form-group"><label>模板ID</label>
															<div class="input-group">
						                                        <input type="text" class="form-control" name="tempId" id="tempIdbind" value="<?php echo $bindtemp['tempId'] ?>"> <span class="input-group-btn"> <button type="button" class="btn btn-primary" onclick="wxTemplateset('bind');">获取模板</button></span>
						                                    </div>
						                                    <div id="divtempbind" style="margin-top: 10px;">
						                                    	<div class="form-group"><label>标题</label><input type="text" placeholder="标题" name="first" class="form-control" value="<?php echo $bindtemp['first'] ?>"></div>
						                                    	<?php
 $i=0; foreach($bindtemp as $key=>$vo){ if(strpos($key,'keyword')===0){ $i++; ?>
																	<div class="form-group"><label><?php echo $bindtemp[kname.$i]; ?></label><select class="form-control m-b" name="keyword<?php echo $i;?>"><option value="1" <?php if($bindtemp[keyword.$i]==1){echo selected;}?>>用户名</option><option value="2" <?php if($bindtemp[keyword.$i]==2){ echo selected;}?>>商品名称</option><option value="3" <?php if($bindtemp[keyword.$i]==3){ echo selected;}?>>用户类型</option><option value="4" <?php if($bindtemp[keyword.$i]==4){ echo selected;}?>>商品金额</option><option value="5" <?php if($bindtemp[keyword.$i]==5){ echo selected;}?>>时间</option></select><input name="keyname<?php echo $i;?>" type="hidden" value="<?php echo $bindtemp[keyname.$i]; ?>"><input name="kname<?php echo $i;?>" type="hidden" value="<?php echo $bindtemp[kname.$i]; ?>"></div>
						                                    	<?php }}?>
						                                    	<div class="form-group"><label>备注</label><input type="text" placeholder="备注" name="remark" class="form-control" value="<?php echo $bindtemp['remark']; ?>"></div>
						                                    	<div class="form-group"><label>URL地址</label><input type="text" placeholder="点击跳转地址" name="url" class="form-control" value="<?php echo $bindtemp['url']; ?>"></div>
						                                    	<div class="well"><h5>内容示例</h5><?php echo $bindtemp['example']; ?></div><input name="example" type="hidden" value="<?php echo $bindtemp['example']; ?>">
						                                    </div>
						                                    <button id="saveTempbind" class="btn btn-primary pull-right" type="submit">保存内容</button>
	                                                    </div>
	                                                    <?php }else{ ?>
														<div class="form-group"><label>模板ID</label>
															<div class="input-group">
						                                        <input type="text" class="form-control" name="tempId" id="tempIdbind"> <span class="input-group-btn"> <button type="button" class="btn btn-primary" onclick="wxTemplateset('bind');">获取模板</button></span>
						                                    </div>
						                                    <div id="divtempbind" style="margin-top: 10px;">
						                                    </div>
						                                    <button id="saveTempbind" class="btn btn-primary pull-right" type="submit" style="display: none;">保存内容</button>
	                                                    </div>
	                                                    <?php } ?>
                                                    </form>
	                                        </div>
	                                    </div>
                                            
                                            <!--/j结算通知/-->
                                            <div class="panel panel-default">
	                                    <div class="panel-heading">
	                                        <h4 class="panel-title">
	                                                <a data-toggle="collapse" data-parent="#accordion" href="tabs_panels.html#collapseThree">结算通知</a>
	                                            </h4>
	                                    </div>
	                                    <div id="collapseThree" class="panel-collapse collapse">
	                                        <div class="panel-body panel-info" style="margin-left: 0; width: 100%; border: none; border-top: 1px solid #ddd;">
	                                        		<form action enctype="multipart/form-data" onsubmit="return false">
	                                        			<?php if(isset($balancetemp)){ ?>
														<div class="form-group"><label>模板ID</label>
															<div class="input-group">
						                                        <input type="text" class="form-control" name="tempId" id="tempIdbalance" value="<?php echo $balancetemp['tempId'] ?>"> <span class="input-group-btn"> <button type="button" class="btn btn-primary" onclick="wxTemplateset('balance');">获取模板</button></span>
						                                    </div>
						                                    <div id="divtempbalance" style="margin-top: 10px;">
						                                    	<div class="form-group"><label>标题</label><input type="text" placeholder="标题" name="first" class="form-control" value="<?php echo $balancetemp['first'] ?>"></div>
						                                    	<?php
 $i=0; foreach($balancetemp as $key=>$vo){ if(strpos($key,'keyword')===0){ $i++; ?>
																	<div class="form-group"><label><?php echo $balancetemp[kname.$i]; ?></label><select class="form-control m-b" name="keyword<?php echo $i;?>"><option value="1" <?php if($balancetemp[keyword.$i]==1){echo selected;}?>>商户名称</option><option value="2" <?php if($balancetemp[keyword.$i]==2){ echo selected;}?>>收银账户</option><option value="3" <?php if($balancetemp[keyword.$i]==3){ echo selected;}?>>收银员</option><option value="4" <?php if($balancetemp[keyword.$i]==4){ echo selected;}?>>收银笔数</option><option value="5" <?php if($balancetemp[keyword.$i]==5){ echo selected;}?>>收银金额</option></select><input name="keyname<?php echo $i;?>" type="hidden" value="<?php echo $balancetemp[keyname.$i]; ?>"><input name="kname<?php echo $i;?>" type="hidden" value="<?php echo $balancetemp[kname.$i]; ?>"></div>
						                                    	<?php }}?>
						                                    	<div class="form-group"><label>备注</label><input type="text" placeholder="备注" name="remark" class="form-control" value="<?php echo $balancetemp['remark']; ?>"></div>
						                                    	<div class="form-group"><label>URL地址</label><input type="text" placeholder="点击跳转地址" name="url" class="form-control" value="<?php echo $balancetemp['url']; ?>"></div>
						                                    	<div class="well"><h5>内容示例</h5><?php echo $balancetemp['example']; ?></div><input name="example" type="hidden" value="<?php echo $balancetemp['example']; ?>">
						                                    </div>
						                                    <button id="saveTempbalance" class="btn btn-primary pull-right" type="submit">保存内容</button>
	                                                    </div>
	                                                    <?php }else{ ?>
														<div class="form-group"><label>模板ID</label>
															<div class="input-group">
						                                        <input type="text" class="form-control" name="tempId" id="tempIdbalance"> <span class="input-group-btn"> <button type="button" class="btn btn-primary" onclick="wxTemplateset('balance');">获取模板</button></span>
						                                    </div>
						                                    <div id="divtempbalance" style="margin-top: 10px;">
						                                    </div>
						                                    <button id="saveTempbalance" class="btn btn-primary pull-right" type="submit" style="display: none;">保存内容</button>
	                                                    </div>
	                                                    <?php } ?>
                                                    </form>
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

	<div class="modal inmodal" tabindex="-1"  id="wxTemplate">
		<div class="modal-dialog">
			<div class="modal-content animated bounceInRight">
				<div class="modal-header">
                    <button type="button" class="close _close"><span>×</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">获取微信公众平台消息模板</h4>
                </div>
				<div class="modal-body">
					<input type="text" class="form-control input-sm m-b-xs" id="filter"
                               placeholder="搜索模板">
					<table class="footable table table-stripped toggle-arrow-tiny" data-page-size="8" data-filter=#filter>
                            <thead>
                            <tr>
                                <th width="100">序号</th>
                                <th>名称</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody id="tempappend">
                            </tbody>
					</table>
				</div>
				<div class="modal-footer">
                    <button type="button" class="btn btn-primary _close">关闭</button>
                </div>
			</div>
		</div>
	</div>

	<script src="/Static/SX/js/footable.all.min.js"></script>
	<script>
	var tempstr = [];

    $(document).ready(function() {
        $('.footable').footable();
    });

    function wxTemplateset(type){
		$('#wxTemplate').show();
		$.post('<?php echo U("SX/Index/getTemplate");?>',function(ret){
			var template_list = ret.template_list;
			if(template_list){
				var i = 0;
				for(var temp in template_list){
					i++;
					tempstr[temp] = JSON.stringify(template_list[temp]);
					$('#tempappend').append('<tr><td>'+i+'</td><td>'+template_list[temp].title+'</td><td><a href="javascript:;" onclick="templateappend('+temp+',\''+type+'\');"><i class="fa fa-check text-navy"></i> 使用</a></td></tr>');
			     }  
			}else{
				swal({title: "模板不存在",text:"请去微信公众平台添加模板",type: "error"});
			}
		},'JSON');
    }

	  $("#wxTemplate ._close").click(function(){
		  $('#wxTemplate').hide();
		  $('#tempappend').html('');
	  });

	  $("#saveWxconfig").click(function(){
	  	  var tempData = $(this).parents('form').serialize();
		  $.post('<?php echo U("SX/Index/saveWxconfig");?>',{data:htmlToArray(tempData)},function(result){
		  	if(result.status == 1){
		  		swal({title: "温馨提示",text:"保存成功",type: "success"});
		  	}else{
		  		swal({title: "温馨提示",text:"保存失败",type: "error"});
		  	}
		  },'JSON');
	  });

	  $("#saveTempzf").click(function(){
	  	  var tempData = $(this).parents('form').serialize();
		  $.post('<?php echo U("SX/Index/saveTempzf");?>',{data:htmlToArray(tempData)},function(result){
		  	if(result.status == 1){
		  		swal({title: "温馨提示",text:"保存成功",type: "success"});
		  	}else{
		  		swal({title: "温馨提示",text:"保存失败",type: "error"});
		  	}
		  },'JSON');
	  });

	  $("#saveTempbind").click(function(){
	  	  var tempData = $(this).parents('form').serialize();
		  $.post('<?php echo U("SX/Index/saveTempbind");?>',{data:htmlToArray(tempData)},function(result){
		  	if(result.status == 1){
		  		swal({title: "温馨提示",text:"保存成功",type: "success"});
		  	}else{
		  		swal({title: "温馨提示",text:"保存失败",type: "error"});
		  	}
		  },'JSON');
	  });
          
          $("#saveTempbalance").click(function(){
	  	  var tempData = $(this).parents('form').serialize();
		  $.post('<?php echo U("SX/Index/saveTempbalance");?>',{data:htmlToArray(tempData)},function(result){
		  	if(result.status == 1){
		  		swal({title: "温馨提示",text:"保存成功",type: "success"});
		  	}else{
		  		swal({title: "温馨提示",text:"保存失败",type: "error"});
		  	}
		  },'JSON');
	  });

	  function templateappend(temp,type){
	  	var tempjson = jQuery.parseJSON(tempstr[temp]);
	  	var tempcontent = tempjson.content;
		tempcontent = tempjson.content.replace(/{{/g, "").split(".DATA}}");

	  	$('#tempId'+type).val(tempjson.template_id);
	  	var num = 0;
	  	var str = '<div class="form-group"><label>标题</label><input type="text" placeholder="标题" name="first" class="form-control" value="'+tempjson.title+'"></div>';
	  	for(var tempco=0;tempco<tempcontent.length-1;tempco++){
	  		var temps = tempcontent[tempco].split("：");
	  		if(temps[1]){
	  			num++;
	  			str += '<div class="form-group"><label>'+temps[0]+'</label><select class="form-control m-b" name="keyword'+num+'"><option value="1">用户名</option><option value="2">商品名称</option><option value="3">用户类型</option><option value="4">商品金额</option><option value="5">时间</option></select><input name="keyname'+num+'" type="hidden" value="'+temps[1]+'"><input name="kname'+num+'" type="hidden" value="'+temps[0]+'"></div>';
	  		}
		}
		str += '<div class="form-group"><label>备注</label><input type="text" placeholder="备注" name="remark" class="form-control" value=""></div>';
		str += '<div class="well"><h5>内容示例</h5>'+tempjson.example+'</div><input name="example" type="hidden" value="'+tempjson.example+'">';

	  	$('#divtemp'+type).html(str);
		$('#saveTemp'+type).show();
	  	
		$('#wxTemplate').hide();
		$('#tempappend').html('');
	  }

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

	</script>

</html>