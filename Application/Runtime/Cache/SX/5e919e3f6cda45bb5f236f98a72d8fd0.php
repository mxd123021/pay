<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
	<meta name="format-detection" content="telephone=no">

    <title>网站管理后台登录</title>

    <link href="/Static/SX/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Static/SX/css/font-awesome.css" rel="stylesheet">
	<link href="/Static/SX/css/awesome-bootstrap-checkbox.css" rel="stylesheet">
	<link href="/Static/SX/css/sweetalert.css" rel="stylesheet">
    <link href="/Static/SX/css/animate.css" rel="stylesheet">
	<link href="/Static/SX/css/style.css" rel="stylesheet">
	<link href="/Static/SX/css/login.css" rel="stylesheet">
        
        <style>
        .addBgArea .dn{left: 10px;top: 115px;z-index: 1;}
        </style>
</head>

<body class="gray-bg">
<div class="addBg">
    <div class="addBgArea">
        <img class="balloon dn" src="/Static/SX/images/login/dn.png" alt="balloon" style="padding-right: 100px;">
<!--        <img class="balloon" src="/Static/SX/images/login/balloon.png" alt="balloon">
        <img class="cricle" src="/Static/SX/images/login/cricle.png" alt="cricle">
        <img class="could" src="/Static/SX/images/login/could.png" alt="could">
        <img class="mountain0" src="/Static/SX/images/login/mountain0.png" alt="mountain0">
        <img class="mountain1" src="/Static/SX/images/login/mountain1.png" alt="mountain1">
        <img class="mountain2" src="/Static/SX/images/login/mountain2.png" alt="mountain2">
        <img class="tree tree0" src="/Static/SX/images/login/tree.png" alt="tree">
        <img class="tree tree1" src="/Static/SX/images/login/tree.png" alt="tree">
        <img class="tree tree2" src="/Static/SX/images/login/tree.png" alt="tree">
        <img class="point" src="/Static/SX/images/login/point.png" alt="point">
        <img class="stick" src="/Static/SX/images/login/stick.png" alt="stick">
        <img class="footprint0" src="/Static/SX/images/login/footprint.png" alt="footprint">
        <img class="footprint1" src="/Static/SX/images/login/footprint.png" alt="footprint">
        <img class="footprint2" src="/Static/SX/images/login/footprint.png" alt="footprint">
        <img class="footprint3" src="/Static/SX/images/login/footprint.png" alt="footprint">-->
    </div>
    <div class="middle-box text-center loginscreen animated fadeInDown">
			<p class="m-t">网站管理后台登录</p>
        <div>
            <form class="m-t" role="form" id="form" method="post" action="<?php echo U('SX/Index/login');?>">
                <div class="form-group">
                    <input type="test" name="username" class="form-control" placeholder="账号" required="">
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="密码" required="">
                </div>
<!--                 {pg:if $is_sms == 1}
<div class="form-group">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="输入您获取的短信验证码" name="code">
                        <input type="hidden" value="-1" id="codetime">
                        <a class="input-group-addon">获取验证码</a>
                    </div>
</div>
                {pg:/if} -->
                <button type="submit" class="btn btn-primary block full-width m-b">登录</button>
            </form>
        </div>
    </div>
</div>
    <!-- Mainly scripts -->
    <script src="/Static/SX/js/jquery-2.1.1.js"></script>
    <script src="/Static/SX/js/bootstrap.min.js"></script>
	<script src="/Static/SX/js/sweetalert.min.js"></script>
	
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
    </script>
</body>

</html>