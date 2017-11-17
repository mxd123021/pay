<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>微信广告 - 网站管理后台</title>
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
    
    <link href="/Static/SX/css/merchant/baseshop.css" rel="stylesheet">
    <link href="/Static/SX/css/merchant/widget_add_img.css" rel="stylesheet">
    <style>
        .frm_controls {
            display: table-cell;
            vertical-align: top;
            float: left;
            width: 400px;
        }
        .frm_tips, .frm_msg {
            padding-top: 4px;
            width: 400px;
        }
        #area,#status{display:inline-block;width: auto;float:left;}
        .img_upload_preview_box p{margin:0px;}
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
                    <h2>微信广告</h2>
                    <ol class="breadcrumb">
                        
    <li>
        <a href="<?php echo U("SX/Index/index");?>">后台首页</a></li>
    <li>微信营销</li>
                        <li class="active">
                            <strong>微信广告</strong>
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
                        <a data-toggle="tab" href="#tab-1" aria-expanded="true">弹框广告</a></li>
<!--                    <li class="">
                        <a data-toggle="tab" href="#tab-2" aria-expanded="false">贴片广告</a></li>-->
                </ul>
                <div class="tab-content">
                    <div id="tab-1" class="tab-pane active">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-12 micropay">
                                    <h3 class="m-t-none m-b">
                                        <span class="label label-primary">弹框信息</span></h3>
                                    <form novalidate="novalidate" class="store_build" id="js_store_build">
                                        <div class="frm_control_group">
                                            <label for="" class="frm_label">广告图片</label>
                                            <div class="frm_controls">
                                                <p class="frm_tips">像素要求：宽300px，高<=300px 支持.jpg .jpeg .gif .png格式，大小不超过1M，注：右上角为关闭，广告图片请标明关闭按钮</p>
                                                <div id="js_upload_wrp">
                                                    <div class="img_upload_wrp group">
                                                        <div id="js_pic_url_div" class="img_upload_box" style="<?php if(($adv["photo_url"]) != ""): ?>display: none;<?php endif; ?>">
                                                            <a class="img_upload_box_oper js_upload dz-clickable" id="js_pic_url" href="javascript:">
                                                                <i class="icon20_common add_gray dz-clickable dz-started">上传</i></a>
                                                        </div>
                                                        <?php if(($adv["photo_url"]) != ""): ?><div class="img_upload_box img_upload_preview_box js_edit_pic_wrp"><img src="/<?php echo ($adv["photo_url"]); ?>" data-dz-remove=""><input name="photo_list[]" class="imginput" type="hidden" value="<?php echo ($adv["photo_url"]); ?>"><input name="photo_img[]" type="hidden" value="<?php echo ($adv["photo_url"]); ?>"><p class="img_upload_edit_area js_edit_area" style="display: none;"><a class="icon18_common del_gray js_delete" href="javascript:;" onclick="DelthisImg($(this));"></a></p></div><?php endif; ?>
                                                        <div class="js_pager"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="frm_control_group">
                                            <label for="" class="frm_label">展示地域</label>
                                            <div class="frm_controls">
                                               <select id="area" class="form-control" name="area" onchange="setarea();">
                                               <option value="0">不限</option>
                                                <?php foreach($districts as $akk=>$avv){?>
                                                  <option value="<?php echo msubstr($avv['fullname'],0,2);?>" data-id="<?php echo $avv['id']?>" <?php if(strpos($avv['fullname'],$adv['area'])===0){echo "selected";}?>><?php echo $avv['fullname'];?></option>
                                                <?php }?>
                                               </select>
                                               <input id="areaid" type="hidden" value="<?php echo ($adv["areaId"]); ?>" name="areaid">
                                            </div>
                                        </div>
                                        
                                        <div class="frm_control_group">
                                            <label for="" class="frm_label">广告标题</label>
                                            <div class="frm_controls">
                                               <input type="text" placeholder="广告标题" name="title" class="form-control" value="<?php echo ($adv["title"]); ?>">
                                            </div>
                                        </div>
                                        
                                        <div class="frm_control_group">
                                            <label for="" class="frm_label">广告描述</label>
                                            <div class="frm_controls">
                                               <input type="text" placeholder="广告描述" name="content" class="form-control" value="<?php echo ($adv["content"]); ?>">
                                            </div>
                                        </div>

                                        <div class="frm_control_group">
                                            <label for="" class="frm_label">广告链接</label>
                                            <div class="frm_controls">
                                               <input type="text" placeholder="点击图片打开的网址，请加http://" name="url" class="form-control" value="<?php echo ($adv["url"]); ?>">
                                            </div>
                                        </div>
                                        
                                        <div class="frm_control_group">
                                            <label for="" class="frm_label">广告类型</label>
                                            <div class="frm_controls">
                                               <select id="adtype" class="form-control" name="adtype">
                                               <option value="1" <?php if($adv['adtype'] == 1){echo "selected";}?>>弹窗广告</option>
                                               <option value="2" <?php if($adv['adtype'] == 2){echo "selected";}?>>图文广告</option>
                                               <option value="3" <?php if($adv['adtype'] == 3){echo "selected";}?>>贴片广告</option>
                                               </select>
                                            </div>
                                        </div>

                                        <div class="frm_control_group">
                                            <label for="" class="frm_label">广告状态</label>
                                            <div class="frm_controls">
                                               <select id="status" class="form-control" name="status">
                                               <option value="0" <?php if($adv['status'] == 0){echo "selected";}?>>关闭</option>
                                               <option value="1" <?php if($adv['status'] == 1){echo "selected";}?>>开启</option>
                                               </select>
                                            </div>
                                        </div>
                                    </form>

                                    <div>
                                        <button id="sub_add_wxtk" class="btn btn-primary pull-right" type="button">
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

    <script src="/Static/SX/js/plugins/dropzone.js"></script>
<script type="text/javascript">
$('#sub_add_wxtk').click(function(){
    $.ajax({
      url:'<?php echo U("SX/Market/advertising");?>',
      type:"post",
      data:$('form').serialize(),
      dataType:"JSON",
      success:function(ret){
        if(ret.status != -1){
          swal({
            title: "保存成功！",
            text: ret.msg,
            type: "success"
           }, function () {
             window.location.reload();
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
      }
    });
  });

            $(document).on('mouseover mouseout', '.img_upload_preview_box',
            function(event) {
                if (event.type == "mouseover") {
                    $(this).find('p').show();
                } else if (event.type == "mouseout") {
                    $(this).find('p').hide();
                }
            });

     function setarea(){
   var obj= $('#area');
     var areaid=obj.find("option:selected").attr("data-id");
     $('#areaid').val(areaid);
  }

            $("#js_pic_url,#js_pic_url .icon20_common").dropzone({
                url: "<?php echo U('SX/Market/img_Upload');?>",
                maxFilesize: 1,
                addRemoveLinks: false,
                acceptedFiles: ".jpg,.png,.gif,.jpeg",
                uploadMultiple: false,
                init: function() {
                    this.on("success",
                    function(file, responseText) {
                        /***这里的this.element 是 $("#js_pic_url")****/
                        if (responseText.status == 1) {
                            var imgHtml = '<div class="img_upload_box img_upload_preview_box js_edit_pic_wrp"><img  src="/' + responseText.savepath + '" data-dz-remove><input name="photo_list[]" class="imginput" type="hidden" value="' + responseText.savepath + '"><input name="photo_img[]" type="hidden" value="' + responseText.savepath + '"><p class="img_upload_edit_area js_edit_area"><a class="icon18_common del_gray js_delete" href="javascript:;" onclick="DelthisImg($(this));" ></a></p></div>';
                            $('#js_upload_wrp .img_upload_wrp .js_pager').before(imgHtml);
                            $(this.element).find('div').remove();
                            $("#js_pic_url_div").hide();
                        } else {
                            swal({
                                title: "上传失败",
                                text: responseText.error,
                                type: "error"
                            },
                            function() {
                                //window.location.reload();
                            });
                        }
                    });
                    this.on("maxfilesreached", function(file) {
                });
                }
            });

        function DelthisImg(obj) {
            if (confirm('您确定删除图片！')) {
                obj.parent('p').parent('.img_upload_preview_box').remove();
                $("#js_pic_url_div").show();
            }
        }
</script>

</html>