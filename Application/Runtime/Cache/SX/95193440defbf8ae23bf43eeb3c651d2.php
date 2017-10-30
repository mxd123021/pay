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
                    <h2>首页</h2>
                    <ol class="breadcrumb">
                        <li><a href="<?php echo U("SX/Index/index");?>">后台首页</a></li>
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
            <div class="col-sm-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-success pull-right">月</span>
                        <h5>订单金额</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins"><?php echo ($data['curmIncome']); ?></h1>
                        <div class="stat-percent font-bold <?php if($data['perIncome'] == 0): ?>text-success<?php elseif($data['perIncome'] > 0): ?>text-info<?php elseif($data['perIncome'] < 0): ?>text-danger<?php endif; ?>"><?php echo ($data['perIncome']); ?>% <i class="fa <?php if($data['perIncome'] == 0): ?>fa-bolt<?php elseif($data['perIncome'] > 0): ?>fa-level-up<?php elseif($data['perIncome'] < 0): ?>fa-level-down<?php endif; ?>"></i>
                        </div>
                        <small>总金额</small>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-info pull-right">全年</span>
                        <h5>商户订单</h5>
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
                        <span class="label label-success pull-right">月</span>
                        <h5>充值金额</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins"><?php echo ($data['curmCz']); ?></h1>
                        <div class="stat-percent font-bold text-navy"></i>
                        </div>
                        <small>总金额</small>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-danger pull-right">所有用户</span>
                        <h5>注册用户</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins"><?php echo ($data['userTotal']); ?></h1>
                        <div class="stat-percent font-bold text-danger">
                        </div>
                        <small>总数</small>
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
                                        商户半年收入利润率
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
                                                            <div class="progress-bar" style="width: <?php echo $data['lastmOrder']/($data['lastmOrder']+$data['curmTotal'])*100; ?>%;"></div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <h2 class="no-margins "><?php echo ($data["curmTotal"]); ?></h2>
                                                        <small>当月订单</small>
                                                        <div class="progress progress-mini">
                                                            <div class="progress-bar" style="width: <?php echo $data['curmTotal']/($data['lastmOrder']+$data['curmTotal'])*100; ?>%;"></div>
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
                                    <h5>用户行为统计</h5>
                                </div>
                                <div class="ibox-content">
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <small class="stats-label">访问页面 / 浏览量</small>
                                            <h4>236 321.80</h4>
                                        </div>

                                        <div class="col-xs-4">
                                            <small class="stats-label">% 新访客</small>
                                            <h4>46.11%</h4>
                                        </div>
                                        <div class="col-xs-4">
                                            <small class="stats-label">最后一周</small>
                                            <h4>432.021</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="ibox-content">
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <small class="stats-label">访问页面 / 浏览量</small>
                                            <h4>643 321.10</h4>
                                        </div>

                                        <div class="col-xs-4">
                                            <small class="stats-label">% 新访客</small>
                                            <h4>92.43%</h4>
                                        </div>
                                        <div class="col-xs-4">
                                            <small class="stats-label">最后一周</small>
                                            <h4>564.554</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="ibox-content">
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <small class="stats-label">访问页面 / 浏览量</small>
                                            <h4>436 547.20</h4>
                                        </div>

                                        <div class="col-xs-4">
                                            <small class="stats-label">% 新访客</small>
                                            <h4>150.23%</h4>
                                        </div>
                                        <div class="col-xs-4">
                                            <small class="stats-label">最后一周</small>
                                            <h4>124.990</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>-->

<!--<div class="row">

                        <div class="col-lg-12">
                            <div class="ibox float-e-margins">
                                <div class="ibox-title">
                                    <h5>自定义响应表格</h5>
                                    <div class="ibox-tools">
                                        <a class="collapse-link">
                                            <i class="fa fa-chevron-up"></i>
                                        </a>
                                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                            <i class="fa fa-wrench"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-user">
                                            <li><a href="#">设置选项1</a>
                                            </li>
                                            <li><a href="#">设置选项2</a>
                                            </li>
                                        </ul>
                                        <a class="close-link">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="ibox-content">
                                    <div class="row">
                                        <div class="col-sm-9 m-b-xs">
                                            <div data-toggle="buttons" class="btn-group">
                                                <label class="btn btn-sm btn-white">
                                                    <input type="radio" id="option1" name="options">天</label>
                                                <label class="btn btn-sm btn-white active">
                                                    <input type="radio" id="option2" name="options">周</label>
                                                <label class="btn btn-sm btn-white">
                                                    <input type="radio" id="option3" name="options">月</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="input-group">
                                                <input type="text" placeholder="搜索" class="input-sm form-control"> <span class="input-group-btn">
                                        <button type="button" class="btn btn-sm btn-primary">搜索</button> </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>项目</th>
                                                    <th>进度</th>
                                                    <th>任务</th>
                                                    <th>日期</th>
                                                    <th>操作</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>米莫说｜MiMO Show</td>
                                                    <td><span class="pie" style="display: none;">0.52/1.561</span><svg class="peity" height="16" width="16"><path d="M 8 8 L 8 0 A 8 8 0 0 1 14.933563796318165 11.990700825968545 Z" fill="#1ab394"></path><path d="M 8 8 L 14.933563796318165 11.990700825968545 A 8 8 0 1 1 7.999999999999998 0 Z" fill="#d7d7d7"></path></svg>
                                                    </td>
                                                    <td>20%</td>
                                                    <td>2014.11.11</td>
                                                    <td><a href="table_basic.html#"><i class="fa fa-check text-navy"></i></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>商家与购物用户的交互试衣应用</td>
                                                    <td><span class="pie" style="display: none;">6,9</span><svg class="peity" height="16" width="16"><path d="M 8 8 L 8 0 A 8 8 0 0 1 12.702282018339785 14.47213595499958 Z" fill="#1ab394"></path><path d="M 8 8 L 12.702282018339785 14.47213595499958 A 8 8 0 1 1 7.999999999999998 0 Z" fill="#d7d7d7"></path></svg>
                                                    </td>
                                                    <td>40%</td>
                                                    <td>2014.11.11</td>
                                                    <td><a href="table_basic.html#"><i class="fa fa-check text-navy"></i></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>天狼---智能硬件项目</td>
                                                    <td><span class="pie" style="display: none;">3,1</span><svg class="peity" height="16" width="16"><path d="M 8 8 L 8 0 A 8 8 0 1 1 0 8.000000000000002 Z" fill="#1ab394"></path><path d="M 8 8 L 0 8.000000000000002 A 8 8 0 0 1 7.999999999999998 0 Z" fill="#d7d7d7"></path></svg>
                                                    </td>
                                                    <td>75%</td>
                                                    <td>2014.11.11</td>
                                                    <td><a href="table_basic.html#"><i class="fa fa-check text-navy"></i></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>线下超市+线上商城+物流配送互联系统</td>
                                                    <td><span class="pie" style="display: none;">4,9</span><svg class="peity" height="16" width="16"><path d="M 8 8 L 8 0 A 8 8 0 0 1 15.48012994148332 10.836839096340286 Z" fill="#1ab394"></path><path d="M 8 8 L 15.48012994148332 10.836839096340286 A 8 8 0 1 1 7.999999999999998 0 Z" fill="#d7d7d7"></path></svg>
                                                    </td>
                                                    <td>18%</td>
                                                    <td>2014.11.11</td>
                                                    <td><a href="table_basic.html#"><i class="fa fa-check text-navy"></i></a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>-->

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

 <script src="/Static/SX/js/flot/jquery.flot.js"></script>
 <script src="/Static/SX/js/flot/jquery.flot.tooltip.min.js"></script>
 <script src="/Static/SX/js/flot/jquery.flot.resize.js"></script>
 <script src="/Static/SX/js/Chart.min.js"></script>
 <script>
        $(document).ready(function() {
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