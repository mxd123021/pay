<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    

    <title>商户注册</title>
    <link href="/Static/SX/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Static/SX/css/font-awesome.css" rel="stylesheet">
    <link href="/Static/SX/css/plugins/custom.css" rel="stylesheet">
    <link href="/Static/SX/css/animate.css" rel="stylesheet">
    <link href="/Static/SX/css/style.css" rel="stylesheet">
    <script>if(window.top !== window.self){ window.top.location = window.location;}</script>

</head>

<body class="gray-bg">

    <div class="middle-box text-center loginscreen   animated fadeInDown">
        <div>
            <div>

                <!-- <h1 class="logo-name">微</h1> -->

            </div>
            <h3>欢迎注册 微信支付</h3>
            <p>创建一个微信支付新账户</p>
                        <form class="form-horizontal m-t" id="signupForm" method="post" novalidate="novalidate" action="<?php echo U("Index/toRegist");?>" >
                            <div class="form-group">
                                    <input style="width: 270px; float: right;" id="username" placeholder="账号名称" name="username" class="form-control" type="text" aria-required="true" aria-invalid="true">
                                    <span style="margin-bottom: 0;
  float:left;" class="help-block">登陆账号</span>
                                    <span style="margin-left: 20px;"></span>
                            </div>
                            <div class="form-group">
                                    <input style="width: 270px; float: right;" id="companyname" placeholder="公司名称" name="companyname" class="form-control" type="text">
                                    <span style="margin-bottom: 0;
  float:left;" class="help-block">公司名称</span>
                                    <span style="margin-left: 20px;"></span>
                            </div>
                            <div class="form-group">
                                    <input style="width: 270px; float: right;" id="password" placeholder="密码" name="password" class="form-control" type="password">
                                    <span style="margin-bottom: 0;
  float:left;" class="help-block">登陆密码</span>
                                      <span style="margin-left: 20px;"></span>
                            </div>
                            <div class="form-group">
                                    <input style="width: 270px; float: right;" id="confirm_password" placeholder="密码确认"  name="confirm_password" class="form-control" type="password">
                                    <span style="margin-bottom: 0;
  float:left;" class="help-block">密码确认</span>
                                      <span style="margin-left: 20px;"></span>
                            </div>
                            <div class="form-group">
                                    <input style="width: 270px; float: right;" id="email" placeholder="邮箱地址" name="email" class="form-control" type="email">
                                    <span style="margin-bottom: 0;
  float:left;" class="help-block">邮箱地址</span>
                                      <span style="margin-left: 20px;"></span>
                            </div>
                            <div class="form-group">
                                    <input style="width: 270px; float: right;" id="tel" placeholder="手机号码" name="tel" class="form-control" type="tel">
                                    <span style="margin-bottom: 0;
  float:left;" class="help-block">手机号码</span>
                                      <span style="margin-left: 20px;"></span>
                            </div>
                            <input name="type" type="hidden" value="reg">
                            <input name="tgId" type="hidden" value="<?php echo ($tgId); ?>">
                            <input name="tgemId" type="hidden" value="<?php echo ($tgemId); ?>">
                            <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" class="checkbox" id="agree" name="agree"> 我已经认真阅读并同意《微信支付使用协议》
                                        </label>
                                    </div>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary block full-width m-b" type="submit">注册</button>
                                <p class="text-muted text-center"><small>已经有账户了？</small><a href="<?php echo U("Index/toLogin");?>">点此登录</a>
                </p>
                            </div>
                        </form>
        </div>
    </div>

    <!-- 全局js -->
    <script src="/Static/SX/js/jquery-2.1.1.js"></script>
    <script src="/Static/SX/js/bootstrap.min.js"></script>
    <script src="/Static/SX/js/jquery.validate.min.js"></script>
    <script type="text/javascript">
        
        $.validator.setDefaults({
    highlight: function(a) {
        $(a).closest(".form-group").removeClass("has-success").addClass("has-error")
    },
    success: function(a) {
        a.closest(".form-group").removeClass("has-error").addClass("has-success")
    },
    errorElement: "span",
    errorPlacement: function(a, b) {
        if (b.is(":radio") || b.is(":checkbox")) {
            a.appendTo(b.parent().parent().parent())
        } else {
            a.appendTo(b.parent())
        }
    },
    errorClass: "help-block m-b-none",
    validClass: "help-block m-b-none"
   });
   $().ready(function() {
    $("#commentForm").validate();
    var a = "<i class='fa fa-times-circle'></i> ";
    jQuery.validator.addMethod("chinese", function(value, element) {
    var chinese = /^[\u4e00-\u9fa5]+$/;
    return this.optional(element) || (chinese.test(value));
    }, "只能输入中文");
    $("#signupForm").validate({
        rules: {
            companyname: {
                chinese: true,
                required: true,
                minlength: 4
            },
            username: {
                required: true,
                minlength: 4
            },
            username: {
                required: true,
                minlength: 4
            },
            password: {
                required: true,
                minlength: 5
            },
            confirm_password: {
                required: true,
                minlength: 5,
                equalTo: "#password"
            },
            email: {
                required: true,
                email: true
            },
            tel: {
                required: true,
                minlength: 11,
                maxlength: 11
            },
            topic: {
                required: "#newsletter:checked",
                minlength: 2
            },
            agree: "required"
        },
        messages: {
            companyname: {
                chinese: a + "公司名称必须为中文",
                required: a + "请输入您的公司全称",
                minlength: a + "公司名必须4个字符以上"
            },
            username: {
                required: a + "请输入您的账号名称",
                minlength: a + "用户名必须4个字符以上"
            },
            password: {
                required: a + "请输入您的密码",
                minlength: a + "密码必须5个字符以上"
            },
            confirm_password: {
                required: a + "请再次输入密码",
                minlength: a + "密码必须5个字符以上",
                equalTo: a + "两次输入的密码不一致"
            },
            confirm_password: {
                required: a + "请再次输入密码",
                minlength: a + "密码必须5个字符以上",
                equalTo: a + "两次输入的密码不一致"
            },
            tel: {
                required: a + "请输入您的手机号码",
                minlength: a + "请输入正确的手机号码",
                maxlength: a + "请输入正确的手机号码"
            },
            email: a + "请输入您的E-mail",
            agree: {
                required: a + "必须同意协议后才能注册",
                element: "#agree-error"
            }
        }
    });
   });
    </script>

</body>

</html>