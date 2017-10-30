<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>子商户管理 - 网站管理后台</title>
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
    
    <link href="/Static/SX/css/wxCoupon.css" rel="stylesheet">
    <link href="/Static/SX/css/dataTables/dataTables.bootstrap.css" rel="stylesheet">
    <link href="/Static/SX/css/plugins/custom.css" rel="stylesheet">
    <link href="/Static/SX/css/footable.core.css" rel="stylesheet">
    <style>
        .tipa {display:inline-block; *display:inline; zoom: 1; vertical-align: middle;}
        .fa-question-circle {
            display: inline-block;
            width: 16px;
            height: 16px;
            vertical-align: middle;
            margin: -4px 3px 0;
            color: #44B549;
            font-size: 18px;
        }
        .ibox-title h5 {
            margin: 10px 0 0px;
        }

        select.input-sm {
            height: 35px;
            line-height: 35px;
        }

        .float-e-margins .btn-info {
            margin-bottom: 0px;
        }

        .fa-paste {
            margin-right: 7px;
            padding: 0px;
        }

        .dz-preview {
            display: none;
        }

        .ibox-title ul {
            list-style: outside none none !important;
            margin: 0 0 0 10px;
            padding: 0;
        }

        .ibox-title li {
            float: left;
            width: 15%;
        }

        #commonpage {
            float: right;
            margin-bottom: 10px;
        }

        #table-list-body .btn-st {
            background-color: #337ab7;
            border-color: #2e6da4;
            cursor: auto;
        }

        #select_Cardtype .i-checks label {
            cursor: pointer;
        }

        #ewmPopDiv .modal-body {
            text-align: center;
        }

        .modal-footer {
            text-align: center;
        }

        .modal-footer .btn {
            padding: 7px 30px;
        }

        .js_modify_quantity .fa {
            margin-left: 10px;
        }

        #ewmPopDiv .downewm {
            font-size: 14px;
            padding: 15px;
            text-align: center;
        }

        .modal-body {
            padding: 20px 30px 15px;
        }

        #select_Cardtype p {
            margin-bottom: 8px;
        }
    </style>
    <style type="text/css">
        #table-list-body .fa-edit{ color: #3DA142;font-size: 20px;}
        #table-list-body .tips{ color: #3DA142;cursor: pointer;}
        #table-list-body .tips span{ display: none;}
        #table-list-body .prelative .form-control {
            display: none;
            vertical-align: middle;
            width: auto;
            height: 30px;
            padding: 3px 10px;
        }
    </style>
    <script src="/Static/SX/js/footable.all2.min.js"></script>

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
                    <h2>子商户管理</h2>
                    <ol class="breadcrumb">
                        <li><a href="<?php echo U("SX/Index/index");?>">后台首页</a></li><li>商家列表</li>
                        <li class="active">
                            <strong>子商户管理</strong>
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
                <div class="ibox-title clearfix">
                    <ul class="nav">
                        <li>
                            <button class="btn btn-primary" id="pop_add_shop"><i class="fa fa-plus"></i> 创建子商户</button>
                        </li>
                    </ul>
                </div>
                <div class="ibox-content">
                    <nav class="ui-nav clearfix"></nav>
                    <div class="app__content js-app-main page-cashier">
                        <div>
                            <!-- 实时交易信息展示区域 -->
                            <div class="cashier-realtime">
                                <div class="realtime-title-block clearfix">
                                    <h1 class="realtime-title">子商户列表 共:(<?php echo ($stores['total']); ?> 个)</h1>
                                </div>
                            </div>
                            <div class="js-real-time-region realtime-list-box loading">
                                <div class="widget-list">
                                    <div class="js-list-filter-region clearfix ui-box"
                                         style="position: relative;">
                                        <div class="widget-list-filter"></div>
                                    </div>
                                    <div class="ui-box">
                                        <table class="ui-table ui-table-list" data-page-size="20" style="padding: 0px;">
                                            <thead class="js-list-header-region tableFloatingHeaderOriginal">
                                            <tr class="widget-list-header">
                                                <th>编号</th>
                                                <th data-hide="phone">门店名称</th>
                                                <th data-hide="phone">门店电话</th>
                                                <th data-hide="phone">操作</th>
                                            </tr>
                                            </thead>
                                            <tbody class="js-list-body-region" id="table-list-body">
                                            <?php if(!empty($stores['root'])): if(is_array($stores['root'])): $i = 0; $__LIST__ = $stores['root'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ovv): $mod = ($i % 2 );++$i;?><tr class="widget-list-item">
                                                        <td class="sid"><?php echo ($ovv["id"]); ?></td>
                                                        <td class="prelative"><span class="wxname"><?php echo (msubstr($ovv["name"],0,10)); ?></span>
                                                            <input type="text" class="form-control" placeholder="请输入商户名称">&nbsp;&nbsp;&nbsp;
                                                            <span class="tips"><i class="fa fa-edit"></i><span>保存修改</span></span>
                                                        </td>
                                                        <td>
                                                            我是电话
                                                        </td>
                                                        <td>
                                                            <a class="btn btn-sm btn-info" href="<?php echo U('SX/RelationMerchant/edit',array('id' => $ovv['id']));?>" style="vertical-align: top;"> 门店详情 </a>
                                                            <?php if($ovv['available_state'] == 3 || $ovv['available_state'] == 0){?>
                                                            <button class="btn btn-sm btn-danger delete" data-id="<?php echo $ovv['id'];?>" poi_id="0"><strong>删&nbsp;&nbsp;&nbsp;除 </strong></button>
                                                            <?php }else{?>
                                                            <button class="btn btn-sm btn-gray"><strong>不可删 </strong></button>
                                                            <?php }?>
                                                        </td>
                                                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                                                <?php else: ?>
                                                <tr class="widget-list-item">
                                                    <td colspan="9">暂无商户</td>
                                                </tr><?php endif; ?>
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
                    </div>
                </div>
            </div>
        </div>
        <?php echo ($stores['pager']); ?>
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

    <div class="modal inmodal" tabindex="-1" role="dialog" id="popgetshop">
        <div class="modal-dialog">
            <div class="modal-content animated bounceInRight">
                <div class="modal-header">
                    <h4 class="modal-title">正在获取微信门店数据....</h4>
                </div>
                <div class="modal-body">
                    <div class="spiner-example" style="padding-top: 30px;">
                        <div class="sk-spinner sk-spinner-circle" style="height: 100px; width: 100px;">
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
                </div>
            </div>
        </div>
    </div>
    <script src="/Static/SX/js/plugins/icheck.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.ui-table-list').footable();
            $('#select_Cardtype .i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });

            $('[rel="popover"],[data-rel="popover"]').popover({
                trigger: 'hover',
                html: true,
                placement: 'auto'
            });

            $("#pop_get_shop").click(function(){
                $('body').append('<div class="modal-backdrop in"></div>');
                $('#popgetshop').show();
                $.post('<?php echo U("Manage/Stores/getWxStore");?>',function(rets){
                    $('#popgetshop').hide();
                    $('.modal-backdrop').remove();
                    if(rets.status == -1){
                        swal({
                            title: "温馨提示",
                            text: "没有已审核的门店可同步！",
                            type: "error"
                        });
                    }else if(rets.status == -2){
                        swal({
                            title: "温馨提示",
                            text: "请检查微信Appid和AppSecret是否正确",
                            type: "error"
                        });
                    }else{
                        swal({
                            title: "温馨提示",
                            text: "已经同步完微信门店数据！",
                            type: "success"
                        }, function () {
                            window.location.reload();
                        });
                    }
                },'JSON');
            });

            $("#pop_add_shop").click(function(){
                window.location.href="<?php echo U('SX/RelationMerchant/showCreate',array('id'=>$id));?>";
            });

            $('.delete').click(function(){
                var id = $(this).attr('data-id');
                var poi_id = $(this).attr('poi_id');
                swal({
                    title: "删除子商户",
                    text: "您真的要删除该子商户吗？",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: "删除",
                    cancelButtonText: "取消",
                    closeOnConfirm: false,
                    closeOnCancel: true
                }, function(isConfirm){
                    if (isConfirm) {
                        $.ajax({
                            url:"<?php echo U('SX/RelationMerchant/delete');?>",
                            type:"get",
                            data:{id:id},
                            dataType:"JSON",
                            success:function(ret){
                                if(ret.status == 1){
                                    swal({
                                        title: "删除成功",
                                        text: '子商户删除成功',
                                        type: "success",
                                        closeOnConfirm: false
                                    },function(){
                                        location.reload();
                                    });
                                } else {
                                    swal("删除子商户失败", ret.errmsg, "error");
                                }
                            }
                        });
                    }
                });
            });
        });

        $('.status-checkbox').change(function(){
            var i = $(this).attr('data-id'),s = $(this).is(':checked') ? 1 : 0;
            $.post('<?php echo U("Manage/Stores/editisSend");?>',{storeId:i,status:s},function(re){
                if(re.status == -1){
                    swal("错误", re.msg, "error");
                }
            },'json');
        });

        $('.status-checkbox2').change(function(){
            var i = $(this).attr('data-id'),s = $(this).is(':checked') ? 1 : 0;
            $.post('<?php echo U("Manage/Stores/editisallSend");?>',{storeId:i,status:s},function(re){
                if(re.status == -1){
                    swal("错误", re.msg, "error");
                }
            },'json');
        });

        $('#table-list-body .prelative .tips').click(function(){
            if($(this).hasClass('fedit')){
                var sid= $(this).parent().siblings('.sid').text();
                sid=parseInt($.trim(sid));
                var vv=$(this).siblings('.form-control').val();
                vv=$.trim(vv);
                if(!vv){
                    swal({title: "温馨提示",text:'没填写内容！',type: "error"});
                    return false;
                }else{
                    var _this= $(this);
                    $.post('<?php echo U("SX/RelationMerchant/updateName");?>',{storeId:sid,name:vv},function(ret){
                        if(ret.status == '1'){
                            _this.siblings('.wxname').text(vv);
                        }else{
                            swal({title: "温馨提示",text:'修改失败！',type: "error"});
                        }
                        _this.siblings('.wxname').show();
                        _this.siblings('.form-control').hide();
                        _this.find('span').hide();
                        _this.removeClass('fedit');
                    },'JSON');
                }
            }else{
                $(this).siblings('.wxname').hide();
                var wxname=$(this).siblings('.wxname').text();
                $(this).siblings('.form-control').val(wxname).show();
                $(this).find('span').show();
                $(this).addClass('fedit');
            }
        });

    </script>

</html>