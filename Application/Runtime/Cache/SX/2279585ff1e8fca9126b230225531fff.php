<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>创建子商户 - 网站管理后台</title>
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
                    <h2>创建子商户</h2>
                    <ol class="breadcrumb">
                        
    <li><a href="<?php echo U(" Manage/Index/index");?>">后台首页</a></li>
    <li>商家设置</li>

                        <li class="active">
                            <strong>创建子商户</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>

<div class="wrapper wrapper-content animated fadeInRight">
    
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-md-12">
                    <div class="panel panel-default">

                        <div class="panel-heading">
                            <div class="panel-title"><em class="fa fa-list"></em> 子商户信息填写</div>
                        </div>
                        <div class="panel-body">
                            <form method="post" class="form-horizontal" action="<?php echo U('SX/RelationMerchant/create');?>" id="myform">

                            <input type="hidden" value="<?php echo $id;?>" name="id">
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">子商户名称</label>
                                    <div class="col-sm-3"><input class="form-control" data-val="true" id="PassWord"
                                                                 name="name" value="">
                                        <div id="PassWordTip" class="onCorrect">&nbsp;</div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">银行商户号</label>
                                    <div class="col-sm-3"><input class="form-control" data-val="true" id="NewPassWord"
                                                                 name="bank_merchant_number" value="">
                                        <div id="NewPassWordTip" class="onShow">请输入银行提供的商户号</div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">加密密钥</label>
                                    <div class="col-sm-3"><input class="form-control" data-val="true" id="NewPassWord2"
                                                                 name="bank_sign_key" value="">
                                        <div id="NewPassWord2Tip" class="onShow">请输入银行提供的加密密钥</div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">查询密钥</label>
                                    <div class="col-sm-3"><input class="form-control" data-val="true" id="Vcode"
                                                                 name="bank_query_key" type="text" value="">
                                        请输入银行提供的查询密钥
                                    </div>
                                </div>
                            </fieldset>
                            </form>
                        </div>
                        <div class="panel-footer text-left">
                            <fieldset>
                                <div class=" form-group">
                                    <label class="col-sm-2 control-label"></label>
                                    <div class="col-sm-3">
                                        <button type="submit" id="submitBtn" class="mb-sm btn btn-success">提交</button>
                                    </div>
                                    <div class="col-sm-7"></div>
                                </div>
                            </fieldset>
                        </div>
                    </div>

            </div>
        </div>
    </div>
    <script type="text/javascript">
        $('#submitBtn').click(function (e) {
            e.preventDefault();
            var thisObj = $(this);
            if (checkInPut()) {
                thisObj.prop('disabled', true);
                $.ajax({
                    url: '<?php echo U("SX/RelationMerchant/create");?>',
                    type: "post",
                    data: $('form').serialize(),
                    dataType: "JSON",
                    success: function (ret) {
                        if (!ret.error) {
                            swal({
                                title: "保存成功！",
                                text: ret.msg,
                                type: "success"
                            }, function () {
                                window.location.href = "<?php echo U('SX/RelationMerchant/getList',array('id'=>$id));?>";
                            });
                        } else {
                            swal({
                                title: "保存失败！",
                                text: ret.msg,
                                type: "error"
                            }, function () {
                                //window.location.reload();
                            });
                        }
                        thisObj.prop('disabled', false);
                    }
                });
            }
        });
        function checkInPut(){
            return true;
        }
    </script>

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

</html>