<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>网站配置 - 网站管理后台</title>
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
                    <h2>网站配置</h2>
                    <ol class="breadcrumb">
                        
    <li>
        <a href="<?php echo U(" SX/Index/index ");?>">后台首页</a></li>
    <li>系统配置</li>
                        <li class="active">
                            <strong>网站配置</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>

<div class="wrapper wrapper-content animated fadeInRight">
    
    <div class="row">
        <div class="col-lg-7">
            <div class="tabs-container weixin">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a data-toggle="tab" href="#tab-1" aria-expanded="true">网站信息</a></li>
                    <li class="">
                        <a data-toggle="tab" href="#tab-2" aria-expanded="false">安全配置</a></li>
<!--                    <li class="">
                        <a data-toggle="tab" href="#tab-3" aria-expanded="false">业务收费</a></li>
                    <li class="">
                        <a data-toggle="tab" href="#tab-4" aria-expanded="false">计划任务</a></li>-->
                </ul>
                <div class="tab-content">
                    <div id="tab-1" class="tab-pane active">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-12 micropay">
                                    <h3 class="m-t-none m-b">
                                        <span class="label label-primary">基本信息</span></h3>
                                    <div class="form-group">
                                        <label>平台名称 (简称)</label>
                                        <input type="text" placeholder="平台名称" name="sn" class="form-control" value="<?php echo ($configs['siteName']); ?>"></div>
                                    <div class="form-group">
                                        <label>网站域名</label>
                                        <input type="text" placeholder="网站域名" name="su" class="form-control" value="<?php echo ($configs['siteUrl']); ?>"></div>
                                    <h3 class="m-t-none m-b">
                                        <span class="label label-primary">SEO配置</span></h3>
                                    <div class="form-group">
                                        <label>标题</label>
                                        <input type="text" placeholder="标题" name="seot" class="form-control" value="<?php echo ($configs['seoTitle']); ?>"></div>
                                    <div class="form-group">
                                        <label>关键词</label>
                                        <input type="text" placeholder="每个词间用，分隔" name="seok" class="form-control" value="<?php echo ($configs['seoKeyword']); ?>"></div>
                                    <div class="form-group">
                                        <label>描述</label>
                                        <input type="text" placeholder="描述" name="seod" class="form-control" value="<?php echo ($configs['seoDescription']); ?>"></div>
                                    <div>
                                        <button class="btn btn-primary pull-right" type="button">
                                            <i class="fa fa-check"></i>&nbsp;保存</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tab-2" class="tab-pane">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-12 micropay">
                                    <div class="form-group">
                                        <label>网站开关</label>
                                        <div class="switch">
                                            <div class="onoffswitch">
                                                <input type="checkbox" <?php if(($configs['isOpen']) == "1"): ?>checked=""<?php endif; ?>class="onoffswitch-checkbox" id="example1">
                                                <label class="onoffswitch-label" for="example1">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tab-3" class="tab-pane">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-12 micropay">
                                    <h3 class="m-t-none m-b">
                                        <span class="label label-primary">费用管理</span></h3>
                                    <div class="form-group">
                                        <label>微信社区开通费 (元)</label>
                                        <input type="number" placeholder="开通费用" name="sn" class="form-control" value="<?php echo ($configs['siteName']); ?>"></div>
                                    <div>
                                        <button class="btn btn-primary pull-right" type="button">
                                            <i class="fa fa-check"></i>&nbsp;保存</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tab-4" class="tab-pane">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-12 micropay">
                                    <div class="alert alert-success">
                                        Linux在Crontab中使用PHP执行脚本<br>
                                        每天17点执行xxx.php如下：<br>
                                        crontab -e<br>
                                        0 17 * * * PHP路径/bin/php 网站路径/Crontab/xxx.php</div>
                                    <div class="project-list">
                                        <table class="table table-hover">
                                            <tbody>
                                                <tr>
                                                    <td class="project-status">
                                                        <span class="label label-primary">正常
                                                    </span></td>
                                                    <td class="project-title">
                                                        <a href="javascript:;">代收的待划账金额满500元自动划账到可申请金额里</a>
                                                        <br>
                                                        <small>执行时间 每天17:00</small>
                                                    </td>
                                                    <td class="project-actions">
                                                        <a href="javascript:;" class="btn btn-white btn-sm"><i class="fa fa-minus-square"></i> 停止 </a>
                                                        <a href="javascript:;" onclick="crontab('/Crontab/Withdraw.php')" class="btn btn-white btn-sm" target="_blank"><i class="fa fa-check-square"></i> 立即运行 </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="project-status">
                                                        <span class="label label-default">停止
                                                    </span></td>
                                                    <td class="project-title">
                                                        <a href="javascript:;">自动删除1年没有登陆的用户</a>
                                                        <br>
                                                        <small>执行时间 每天03:00</small>
                                                    </td>
                                                    <td class="project-actions">
                                                        <a href="javascript:;" class="btn btn-white btn-sm"><i class="fa fa-caret-square-o-right"></i> 运行 </a>
                                                        <a href="javascript:;" class="btn btn-white btn-sm"><i class="fa fa-check-square"></i> 立即运行 </a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
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

    <script type="text/javascript">
        $('.micropay .btn-primary').click(function() {
            var sn = $.trim($("input[name='sn']").val());
            var su = $.trim($("input[name='su']").val());
            var seot = $.trim($("input[name='seot']").val());
            var seok = $.trim($("input[name='seok']").val());
            var seod = $.trim($("input[name='seod']").val());
            $.post('<?php echo U("SX/Index/saveSiteinfo");?>', {
                sn: sn,
                su: su,
                seot: seot,
                seok: seok,
                seod: seod
            },
            function(ret) {
                if (ret.status == '1') {
                    swal({
                        title: "温馨提示",
                        text: '修改成功',
                        type: "success"
                    });
                } else {
                    swal({
                        title: "温馨提示",
                        text: '修改失败！',
                        type: "error"
                    });
                }
            });
        });

        $('.onoffswitch .onoffswitch-checkbox').click(function() {
            var isopen;
            if ($(this).attr('checked')) {
                isopen = 0;
                $(this).removeAttr('checked');
            } else {
                isopen = 1;
                $(this).attr("checked", '');
            }
            $.post('<?php echo U("SX/Index/editisOpen");?>', {
                isopen: isopen
            },
            function(ret) {
                if (ret.status != '1') {
                    swal({
                        title: "温馨提示",
                        text: '修改失败！',
                        type: "error"
                    });
                }
            });
        });

function crontab(url){
    $.post(url,function(ret){
        swal({
            title: "温馨提示",
            text: '执行成功！',
            type: "success"
        });   
    });
}
</script>

</html>