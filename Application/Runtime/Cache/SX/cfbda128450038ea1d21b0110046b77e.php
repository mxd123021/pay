<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理员列表 - 网站管理后台</title>
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
	.form-control, .single-line{width: 50%;}
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
                    <h2>管理员列表</h2>
                    <ol class="breadcrumb">
                        <li><a href="<?php echo U("SX/Index/index");?>">后台首页</a></li><li>系统配置</li><li>管理员配置</li>
                        <li class="active">
                            <strong>管理员列表</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>

<div class="wrapper wrapper-content animated fadeInRight">
    
	            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <input type="text" class="form-control input-sm m-b-xs" id="filter" placeholder="搜索管理员">
							<form action="<?php echo U('SX/Index/employersDelAll');?>" class="employersDelAll" method="post">
                            <table class="footable table table-stripped toggle-arrow-tiny" data-page-size="10" data-filter=#filter>
                                <thead>
                                <tr>
                                    <th data-sort-ignore="true" class="check-mail"><input type="checkbox" class="i-checks" id="check_box"></th>
                                    <th>管理员名称</th>
                                    <th>登陆账号</th> 
                                    <th data-hide="phone">所属角色</th>
                                    <th data-hide="phone">最后登录IP</th>
                                    <th data-hide="phone">最后登录时间</th>
                                    <th data-sort-ignore="true">操作</th>
                                </tr>
                                </thead>
                                <tbody>
								<?php foreach($staffs as $key=>$val){ ?>
                                <tr>
                                    <td class="check-mail"><input type="checkbox" class="i-checks" value="<?php echo $val['staffId'];?>" name="id[]"></td>
                                    <td><?php echo $val['staffName'];?></td>
                                    <td><?php echo $val['userName'];?></td>
                                    <td><?php echo $role[$val['staffRoleId']];?></td>
                                    <td><?php echo $val['lastIP'];?></td>
                                    <td><?php echo $val['lastTime'];?></td>
                                    <td class="center">
										<div class="btn-group">
                                            <a href="<?php echo U('SX/Index/employersEdit',array('id' => $val['staffId']));?>" class="btn btn-white btn-sm employersEdit"><i class="fa fa-pencil"></i> 编辑</a>
                                            <a href="javascript:void(0)" class="btn btn-white btn-sm employersDel" data-id="<?php echo $val['staffId'];?>"><i class="fa fa-times"></i> 删除</a>
                                        </div>
									</td>
                                </tr>
								<?php }?>
                                </tbody>
                            </table>
							</form>
							<div class="tooltip-demo">
								<button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal5" data-toggle="tooltip" data-placement="left" title="" data-original-title="添加员工"><i class="fa fa-plus"></i> 添加</button>
								<button class="btn btn-white btn-sm info_del_all" data-toggle="tooltip" data-placement="top" title="" data-original-title="删除管理员"><i class="fa fa-trash-o"></i> </button>
								<ul class="pagination pull-right"></ul>
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

	<div class="modal inmodal fade" id="myModal5" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    	<div class="modal-dialog modal-lg">
        	<div class="modal-content">
            	<div class="modal-header">
				 <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">添加管理员</h4>
                </div>
                <div class="modal-body clearfix">
					<form id="employersForm" class="form" action="<?php echo U('SX/Index/employersAdd');?>" method="post">
						<div class="col-lg-12">
							<div class="ibox">
                        		<div class="ibox-title">
                           			<h5>管理员信息</h5>
                        		</div>
                        		<div class="ibox-content">
                            		<div class="form-group">
										<label><span class="mustInput">*</span>管理员名称:<span class="f999">(20字以内)</span></label>
										<input type="text"  id="username" placeholder="请输入管理员名称" name="username" class="form-control required" aria-required="true">
									</div>
									<div class="form-group">
										<label><span class="mustInput">*</span>登录账号:</label>
										<input type="text" id="account" placeholder="请输入登录账号" name="account" class="form-control required"aria-required="true">
									</div>
									<div class="form-group">
										<label><span class="mustInput">*</span>密码:</label>
										<input type="password" id="password" placeholder="请输入密码(6到20个字符)" name="password" class="form-control required" aria-required="true">
									</div>
									<div class="form-group">
										<label><span class="mustInput">*</span>确认密码:<span class="f999"></span></label>
										<input type="password" id="confirm" placeholder="" name="confirm" class="form-control required" aria-required="true">
									</div>
									<div class="form-group">
										<label><span class="mustInput"></span>角色选择：<span class="f999"></span></label>
										 <select id="roleId" name="roleId" class="form-control" aria-required="true" min="1" style="z-index:999">
										  <option value="0">请选择门</option>
										  <?php foreach($roles as $svv){?>
										   <option  value="<?php echo $svv['roleId'];?>" ><?php echo $svv['roleName'];?>
										   </option>
										  <?php }?>
										 </select>
									</div>

                        		</div>
                    		</div>
						</div>
					</form>
               	</div>
                <div class="modal-footer">
                	<button type="button" class="btn btn-white" data-dismiss="modal">取消</button>
                	<button type="button" class="btn btn-primary formSubmit">保存</button>
                </div>
          	</div>
        </div>
    </div>

    <!-- FooTable -->
    <script src="/Static/SX/js/footable.all.min.js"></script>
	
	<!-- iCheck -->
    <script src="/Static/SX/js/plugins/icheck.min.js"></script>
	
	<!-- Jquery Validate -->
    <script src="/Static/SX/js/jquery.validate.min.js"></script>

    <!-- Page-Level Scripts -->
    <script>
        $(document).ready(function() {
			employers.init();
        });
		!function(a,b){
			var employers = employers || {};
			employers.init = function(){
				var c = employers;
				b('.footable').footable();
				b('.i-checks').iCheck({
                	checkboxClass: 'icheckbox_square-green',
                	radioClass: 'iradio_square-green',
            	});
				b('#check_box').on('ifChanged', function(){
					c.selectall('id[]','check_box');
				});
				b('.info_del_all').click(function(){
					c.delAll();
				});
				b('.part_item .checkAll').click(function(){
					var checkItems = b(this).parents('.part_item_t').siblings('.part_item_b').find('p').find('input[name="authority[]"]');
					if (b(this).is(':checked') == false) {
						checkItems.each(function(ke,el){
							$(el).iCheck('uncheck');
						});
					}else{
						checkItems.each(function(ke,el){
							$(el).iCheck('check');
						});
					}
				});
				jQuery.extend(jQuery.validator.messages, {
  					required: "必填字段",
  					remote: "请修正该字段",
  					equalTo: "请再次输入相同的值",
  					min: jQuery.validator.format("请选择角色"),
  					maxlength: jQuery.validator.format("请输入一个长度最多是 {0} 的字符串"),
  					minlength: jQuery.validator.format("请输入一个长度最少是 {0} 的字符串"),
				});
				b('#employersForm').validate({
                    errorPlacement: function (error, element){
                            element.before(error);
                    },
                    rules: {
                        confirm: {
                            equalTo: "#password"
                        },
						account: {
							minlength: 4
						},
						password: {
							minlength: 4
						}
                    }
                });
				b('.formSubmit').click(function(){
					if(b('#account').val() != ''){
						$.post('<?php echo U("SX/Index/checkAccount");?>',{account:b('#account').val()},function(re){
							if(re.status == -1){
								b('#account').addClass('error');
								swal(re.msg, "请修改登陆账号", "error");
							}else if(re.status == 1){
								b('#employersForm').submit();
							}
						},'json');
					}else{
						b('#employersForm').submit();
					}
				});
				b('.employersDel').click(function(){
					var c = b(this);
					swal({
        				title: "是否删除这条数据?",
        				text: "删除数据后将无法恢复，确认要删除吗！",
        				type: "warning",
                   	 	confirmButtonColor: "#DD6B55",
                   	 	confirmButtonText: "删除",
                    	cancelButtonText: "取消",
                    	closeOnConfirm: false,
                    	showCancelButton: true,
    				}, function (){
						$.post('<?php echo U("SX/Index/employersDel");?>',{staffId:c.attr('data-id')},function(re){
							if(re.status == 1){
								swal("成功", re.msg, "success");
								c.parents('tr').remove();
								b('.footable').footable();

							}else{
								swal("错误", re.msg, "error");
							}
						},'json');
    				});
				});
				b('.employersEdit').click(function(){
					c.edit(b(this).attr('data-id'));
				});
			};
			employers.selectall = function(name,id){
				var checkItems = b('input[name="'+name+'"]');
				if ($("#"+id).is(':checked') == false) {
					checkItems.each(function(ke,el){
						$(el).iCheck('uncheck');
					});
				}else{
					checkItems.each(function(ke,el){
						$(el).iCheck('check');
					});
				}
			}
			employers.delAll = function(){
				swal({
        			title: "是否删除选中?",
        			text: "删除数据后将无法恢复，确认要删除吗！",
        			type: "warning",
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "删除",
                    cancelButtonText: "取消",
                    closeOnConfirm: false,
                    showCancelButton: true,
    			}, function (){
					var checkItems = b('input[name="id[]"]'),c = false;
			
					checkItems.each(function(ke,el){
						if($(el).is(':checked') == true){
							c = true;
						}
					});
					if(c == false){
						swal("错误", "你至少需要选中一项 :)", "error");
						return false;
					}
					$('.employersDelAll').submit();
    			});
			}
			employers.iframeRresponsible = function(){
				var $allObjects = $("iframe, object, embed"),
        		$fluidEl = $("figure");

   	 			$allObjects.each(function() {
        			$(this)
           			 // jQuery .data does not work on object/embed elements
            		.attr('data-aspectRatio', this.height / this.width)
            		.removeAttr('height')
            		.removeAttr('width');
    			});
   		 		$(window).resize(function() {
        			var newWidth = $fluidEl.width();
        			$allObjects.each(function() {
        			    var $el = $(this);
        			    $el
        			    .width(newWidth)
        			    .height(newWidth * $el.attr('data-aspectRatio'));
        			});
    			}).resize();
			}
			a.employers = employers;
		}(window,jQuery);
    </script>

</html>