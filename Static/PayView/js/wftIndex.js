$(function () {
    // var host = 'http://test.yunhuotong.net/';
    var host = '/';
    // var host = 'http://192.168.10.200:8080/';
    // 数字按钮
    var num = $('.js-num').children("li");
    // 删除
    var del = $('.js-del');
    // 付款
    var ok = $('.js-ok');
    // 金额盒子
    var moneyBox = $('.money-box');
    // 键盘
    var keyboard = $('.js-keyboard');
    // 备注
    var remarkInput = $('.wg-remark-input');
    var remarkImg = $('#wg-remark-img');
    // 微信
    var weixin = $('.weixin');
    var openId = '';
    var weixinLock = true;
    // 支付宝
    var alipay = $('.alipay');
    var alipayLock = true;
    var money = $('#money');
    var payUrl = '/PayView/Index/getWftPayTokenId';
    var fail = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGAAAABgCAYAAADimHc4AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBNYWNpbnRvc2giIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6OEM0MDU1QkE0MDRBMTFFMkFDNjJEMTAxN0EwRjQ3ODAiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6OEM0MDU1QkI0MDRBMTFFMkFDNjJEMTAxN0EwRjQ3ODAiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpDOUFEMzc1OTQwNDYxMUUyQUM2MkQxMDE3QTBGNDc4MCIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDpDOUFEMzc1QTQwNDYxMUUyQUM2MkQxMDE3QTBGNDc4MCIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PpV29yAAAAM3SURBVHja7N3hcdNAEMVxxRWYEujAlJBKUEqgAzpIqECmEkowHVCC6UCcJsqQMZhYd/vevj12Z+4rsv+/eIguyuVunuchx2/uEiABEiAnARIgJwESICcBEiAnARIghzc70nXGss5lHYJ0Oayvd4RfafkEgNc4/55zWQfCNVvWYX2dLzMir4d+M4/zn6OMcBkfjoB8M9N8fRQRrsWHInjEV0R4Kz4MwSu+EsKt8SEInvEVELbGN0fwju+JUBvfFEEhvgdCa3wzBJX4TASr+CYIrVsRE+Bu8WdZ92V9B93hfitrb/zvPpR1ZO8FTcBbdQQCKn4TQi3ARNgnsURAx69GqAFgxLdEYMWvQqgBOJF3NVsQ2PGH9XV+QALs1zeljuAV/359vdD/A9QRQsRv/S5IFSFMfIsfSaohhIpv9TNhFYRw8a0AFBBCxrcE8ET4VNZjxPjWAF4I7DGLjwDoHcE0PgqgVwTz+EiA3hAg8dEAvSDA4jMAoiNA47MAoiLA4zMBoiFQ4rMBoiDQ4nsAqCNQ43sBqCLQ43sCqCG4xPcGUEFwi68A4I1wHJ6fYnCbncDHf/nK++p07S/eb17hEzAOz88aeeGjHoMMAeAZXwLBE0AhvjuCF4BSfFcEDwDF+G4IbADl+C4ITIAI8ekILIBI8akIDICI8WkIaIDI8SkISIAe4sMRUAA9xYciIAB6jP8aYXkW9agK4BF/ibInX/PBCmEXPP4S4f3A38Ox+01RwLFkrJleXX9f1snhNUicFeEdH3VuBQWhp/ghEXqLHw6hx/ihEHqNHwah5j7gTP6++zi0PTrCPFzk5b7kHfI+gPkQU2t805umDdsV8Dthxu/lWsRnfhKq9opatiKQCNbx0QjVG3Wte0EIBFR8FELbLqnYKYTTzDm20uq7o+ZTHpWOgmTFt0IwOWJT5TxOdvxWhB9W55sqHIrqFb8W4bTuvsqenr4FwTv+VgTT+Mi/H3ALgkr8WxHM46P/gsa/ENTiv4UAic/4GzJ/Q1CNfw0BFp8BcImgHv8SARrf4vT0LXfMH9dHOqLM57Keho7OishJgATISYAEyEmABMhJgATISYAE+O/nlwADAADMVJX9TVBJAAAAAElFTkSuQmCC';
    // 获取字符串
    function getStr(string, str) {
        var str_after = string.split(str)[1];
        return str_after;
    }
    var id = $('#merchant_id').val();
    $('.js-ok').click(function (e) {
        if(!IsWeixinOrAlipay()){
            $.dialog({
                type: 'info',
                infoText: '请使用微信&支付宝进行支付',
                infoIcon: fail,
                autoClose: 2000
            });
            e.stopPropagation();
            return ;
        }
    });
    // 金额获取焦点事件
    moneyBox.on('touchstart', function () {
        remarkInput.blur();
        $('#hint').hide();
        $('#js-cursor').show();
        keyboard.addClass("on")
    });
    // 备注点击事件 S
    remarkInput.on('touchstart', function () {
        if (remarkInput.val()) {
            remarkImg.show();
        } else if (!remarkInput.val()) {
            remarkImg.hide();
        }
        var JE = money.text() ? money.text() : '';
        if (!JE) {
            $('#hint').show();
        }
        $('#js-cursor').hide();
        keyboard.removeClass("on")
    });
    remarkInput.on('keyup', function () {
        if (remarkInput.val() != '添加备注（20个字以内）') {
            remarkImg.show();
        } else if (remarkInput.val() == '添加备注（20个字以内）') {
            remarkImg.hide();
        }
    });
    remarkInput.blur(function () {
        remarkImg.hide();
        $('#hint').hide();
        $('#js-cursor').show();
        keyboard.addClass("on")
    });
    // 删除备注
    remarkImg.on('touchstart', function () {
        remarkInput.val('添加备注（20个字以内）');
    });
    // 备注点击事件 E

    // 安卓监听收起键盘
    var winHeight = $(window).height();   //获取当前页面高度
    $(window).resize(function () {
        var thisHeight = $(this).height();
        if (winHeight - thisHeight > 50) {
            //当软键盘弹出，在这里面操作
            if (remarkInput.val()) {
                remarkImg.show();
            } else if (!remarkInput.val()) {
                remarkImg.hide();
            }
            var JE = money.text() ? money.text() : '';
            if (!JE) {
                $('#hint').show();
            }
            $('#js-cursor').hide();
            keyboard.removeClass("on")
        } else {
            //当软键盘收起，在此处操作
            remarkInput.blur();
        }
    });

    // 键盘点击事件
    num.on('touchstart', function () {
        // 隐藏提示文字和光标
        $('#hint').hide();
        $('#js-cursor').show();

        var number = this.innerHTML;
        var JE = money.text() ? money.text() : '';
        var str = getStr(JE, '.');
        if (parseInt(JE) >= 9999999999) {
            $.dialog({
                type: 'info',
                infoText: '土豪,金额超过了',
                infoIcon: fail,
                autoClose: 2000
            });
            return;
        }
        if (number == '.') {
            if (JE.indexOf('.') >= 0) {
                return;
            }
            if (JE.length == 0) {
                money.html(0 + number);
                return;
            }
        }
        if (JE.length == 0) {
            if (number == '0') {
                money.html(number + '.');
                return;
            }
        }
        if (str) {
            if (str.length >= 2) {
                return;
            }
        }
        money.html(JE + number);
    });
    // 删除点击事件
    del.on('touchstart', function () {
        var JE = money.text() ? money.text() : '';
        var JEs = JE.substring(0, JE.length - 1);
        money.html(JEs);
    });
    // 支付宝支付
    alipay.on('touchstart', function () {
        // 锁
        if (alipayLock == true) {
            alipayLock = false;
        } else {
            return;
        }
        var JE = money.text() ? money.text() : '';

        // 判断有没有金额
        if (!JE) {
            $.dialog({
                type: 'info',
                infoText: '请输入金额',
                infoIcon: fail,
                autoClose: 2000
            });
            alipayLock = true;
            return;
        } else if (JE < '0.01') {
            $.dialog({
                type: 'info',
                infoText: '金额不能为0',
                infoIcon: fail,
                autoClose: 2000
            });
            alipayLock = true;
            return;
        }
        var remark = remarkInput.val() == '添加备注（20个字以内）' ? '' : remarkInput.val();
        //获取支付token
        $.get(payUrl + '?price='+JE+'&id='+id+'&remark='+remark,function(resp){
            alipayLock = true;
            if(resp.data.tokenId){
                return mqq.tenpay.pay({
                    tokenId: resp.data.tokenId,
                    pubAcc: "",
                    pubAccHint: ""
                });
            }
            return $.dialog({
                type: 'info',
                infoText: resp.msg,
                infoIcon: fail,
                autoClose: 3000
            });
        });
    });
    // 微信支付
    weixin.on('touchstart', function () {
        // 锁
        if (weixinLock == true) {
            weixinLock = false;
            // weixinLock = true;
        } else {
            return;
        }
        // 判断有没有金额
        var JE = money.text() ? money.text() : '';
        if (!JE) {
            $.dialog({
                type: 'info',
                infoText: '请输入金额',
                infoIcon: fail,
                autoClose: 2000
            });
            weixinLock = true;
            return;
        } else if (JE < '0.01') {
            $.dialog({
                type: 'info',
                infoText: '金额不能为0',
                infoIcon: fail,
                autoClose: 2000
            });
            weixinLock = true;
        }
        var remark = remarkInput.val() == '添加备注（20个字以内）' ? '' : remarkInput.val();
        $.get(payUrl + '?price='+JE+'&id='+id+'&remark='+remark,function(resp){
            if(resp.url){
                location.href = resp.url;
                return weixinLock = true;
            }
            return $.dialog({
                type: 'info',
                infoText: resp.msg,
                infoIcon: fail,
                autoClose: 3000
            });
        });
    });
});
