<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
	<meta name="format-detection" content="telephone=no">

    <title>商户管理后台登录</title>

    <link href="/Static/SX/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Static/SX/css/font-awesome.css" rel="stylesheet">
	<link href="/Static/SX/css/awesome-bootstrap-checkbox.css" rel="stylesheet">
	<link href="/Static/SX/css/sweetalert.css" rel="stylesheet">
    <link href="/Static/SX/css/animate.css" rel="stylesheet">
	<link href="/Static/SX/css/style.css" rel="stylesheet">
	<link href="/Static/SX/css/login.css" rel="stylesheet">
    <link href="/Static/SX/css/plugins/jquery.nouislider.css" rel="stylesheet">
    <style>
        .addBgArea .dn{left: 10px;top: 118px;z-index: 1;}
    </style>
</head>

<body class="gray-bg">
<div class="addBg">
    <div class="addBgArea">
        <img class="balloon dn" src="/Static/SX/images/login/dn.png" alt="balloon" style="padding-right: 100px;">
    </div>
    <div class="middle-box text-center loginscreen animated fadeInDown">
			<p class="m-t">商户管理后台登录</p>
        <div>
            <form class="m-t" role="form" id="form" method="post" action="<?php echo U('Manage/Index/login');?>">
                <div class="form-group">
                    <input type="test" name="username" class="form-control" placeholder="账号" required="">
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="密码" required="">
                </div>
                <div class="form-group hide">
                    <div class="radio radio-info radio-inline">
                        <input type="radio" value="merchant" name="type" checked="checked" id="inlineRadio1">
                        <label for="inlineRadio1"> 商户登录</label>
                    </div>
                    <div class="radio radio-inline">
                        <input type="radio" value="employee" name="type" id="inlineRadio2">
                        <label for="inlineRadio2"> 员工登录</label>
                    </div>
                </div>
                <div class="form-group" style="width: 250px; margin-left: 30px">
                    <div class="control-label">滑动登录</div>
                    <div id="basic_slider"></div>
                </div>
<!--                 <button type="submit" class="btn btn-primary block full-width m-b hide">登录</button> -->
                
            </form>
        </div>
    </div>
</div>
    <!-- Mainly scripts -->
    <script src="/Static/SX/js/jquery-2.1.1.js"></script>
    <script src="/Static/SX/js/bootstrap.min.js"></script>
	<script src="/Static/SX/js/sweetalert.min.js"></script>
    <script src="/Static/SX/js/plugins/jquery.nouislider.min.js"></script>
	<!-- Jquery Validate -->
    <script src="/Static/SX/js/jquery.validate.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#form").validate({
                 rules: {
                     password: {
                         required: true,
                         minlength: 5
                     },
                     username: {
                         required: true,
                       	 minlength: 4
                     }
                 }
             });
		$(".addBg,.addBgArea").height($(window).height());
            $(window).resize(function(){
                $(".addBg,.addBgArea").height($(window).height());
          })
        });

        var flag = false;
        $(document).ready(function(){
        	$('.input-group-addon').click(function(){
            	var username = $.trim($('input[name="username"]').val());
            	if (username == '') {
            		swal({
  					  title: "获取短信验证码",
  					  text: '先填写您的账号',
  					  type: "error"
  					 });
 					 return false;
            	}
        		if (flag) return false;
        		flag = true;
        		$.ajax({
        			url:'?m=System&c=index&a=getCode',
        			type:"post",
        			data:{'username':username, 'login':1},
        			dataType:"JSON",
        			success:function(ret){
        				if(!ret.error){
        					$('#codetime').val(60);
        					count_down();
        				} else {
        					flag = false;
        					swal({
        					  title: "获取短信验证码",
        					  text: ret.info,
        					  type: "error"
        					 });
        			   }
        			}
        		});
        		return false;
        	});
        });

        function count_down(){
        	var down = setInterval(function(){
        		var num = $('#codetime').val();
        		if(num > 0){
        			$('#codetime').val(num - 1);
        			$('.input-group-addon').html('(' + parseInt(num - 1) + ')秒后重新获取');
        		}else{
        			flag = false;
        			$('#codetime').val(-1);
        			$('.input-group-addon').html('获取验证码');
        			clearInterval(down);
        		}
        	},1000);
        }

        var slider = document.getElementById('basic_slider');
        noUiSlider.create(slider, {
            start: 0,
            step: 1,
            range: {
                'min': 0,
                'max': 100
            }
        });
        slider.noUiSlider.on('update', function( values, handle ) {
            if(values[handle]>0){
                var un=$.trim($("input[name='username']").val());
                var pw=$.trim($("input[name='password']").val());
                if(un=="" || pw==""){
                    slider.noUiSlider.set(0);
                    return swal({title: "温馨提示",text:'账号或密码不能为空',type: "error"});
                }
                $("#basic_slider").mouseup(function(){
                    if(values[handle]< 100){
                        slider.noUiSlider.set(0);
                    }
                });
                if(values[handle]== 100){
                	var type = $("input[name='type']:checked").val();

                	if(type == "merchant"){
	                    $.post('<?php echo U("Manage/Index/login");?>',{username:un,password:pw,code:"af34kie#j22#jfi19"},function(result){
	                        if(result.status == 1){
	                            location.href = "<?php echo U('Index/index');?>";
	                        }else{
	                            slider.noUiSlider.set(0);
	                            swal({title: "温馨提示",text:'商户账号或密码错误',type: "error"});
	                        }
	                    },'json');
                	}else if(type == "employee"){
                		$.post('<?php echo U("Manage/Ustaffs/staffslogin");?>',{username:un,password:pw,code:"*fj3#843(8fdd3111f"},function(result){
	                        if(result.status == 1){
	                            location.href = "<?php echo U('Index/index');?>";
	                        }else{
	                            slider.noUiSlider.set(0);
	                            swal({title: "温馨提示",text:'员工账号或密码错误',type: "error"});
	                        }
	                    },'json');
                	}
                }
            }
        });
    </script>
</body>

</html>