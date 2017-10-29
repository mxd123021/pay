$(document).ready(function() {
    inf_status = false;
    startIndex = 0;
    sms_key = $('#sms_key').val();
    $("#js_edit_area").steps({
        bodyTag: "fieldset",
        forceMoveForward: inf_status,
        startIndex: startIndex,
        onInit: function(event, currentIndex) {
            switchery = [];
            $('.js-switch').each(function(index, el) {
                var this_name = $(this).attr('name');
                switchery['' + this_name] = new Switchery(el, {
                    color: '#63b359',
                    className: 'switchery ' + this_name
                });
            });
            var h = $('#js_preview_area').parent('.member_card').offset().top;
            $(window).scroll(function() {
                if ($(window).scrollTop() > h) {
                    $('#js_preview_area').parent('.member_card').addClass('affix');
                } else {
                    $('#js_preview_area').parent('.member_card').removeClass('affix');
                }
            });
            //选择是否本地会员卡
            $('#js_editform input[name="localcard"]').click(function(event) {
                if ($(this).val() == 1) {
                    $('#show_is_msg').show('fast');
                    $('#pay_way2').parent('.radio').hide();
                    $('#activate1').parent('.radio').hide();
                    $('#activate0').prop('checked', true);
                    $('#activate1').prop('checked', false);
                    $('.no_auto_activate').hide();
                    $('#use_custom_code').parent('div.appmsg_edit_item').show();
                } else {
                    $('#show_is_msg').hide('fast');
                    $('#pay_way2').parent('.radio').show();
                    $('#activate1').parent('.radio').show();
                    if (!$('#activate1').prop('checked')) {
                        $('#use_custom_code').prop('checked', false);
                        $('#is_quantity').show();
                        $('#use_custom_code').parent('div.appmsg_edit_item').hide();
                        $('#use_custom_code_info').hide();
                    }
                }
            });
            //开关等级功能
            $('#js_editform .switchery.is_grade').click(function(event) {
                var clickCheckbox = document.querySelector('.is_grade');
                var clickCheckbox1 = document.querySelector('.is_storedvalu');
                if (clickCheckbox.checked) {
                    $('#is_grade_show').show('fast');
                    $('#supply_discount_grade').parent('.radio').show();
                    if (!clickCheckbox1.checked) {
                        $('#custom_field2_type1').parent('.radio').show();
                    }
                    $('#custom_field3_type1').parent('.radio').show();
                    $('#custom_field3_type2').parent('.radio').show();
                    $('.signinexp').show();
                } else {
                    $('#is_grade_show').hide('fast');
                    if (!clickCheckbox1.checked) {
                        $('.custom_field2_type').prop('checked', false);
                        $('#custom_field2_type0').prop('checked', true);
                        $('.custom_field3_type').prop('checked', false);
                        $('#custom_field3_type0').prop('checked', true);
                        $('#js_preview_area .demo_grade span').text('未设置');
                        $('#js_preview_area .demo_storedvalu span').text('未设置');
                        //$('#custom_field3_type2').parent('.radio').hide();
                    }
                    $('#supply_discount_grade').parent('.radio').hide();
                    $('#custom_field2_type1').parent('.radio').hide();
                    $('#custom_field3_type1').parent('.radio').hide();
                    $('.signinexp').hide();
                }
                change_customid()
            });
            //开关储值功能
            $('#js_editform .switchery.is_storedvalu').click(function(event) {
                var clickCheckbox = document.querySelector('.is_grade');
                var clickCheckbox1 = document.querySelector('.is_storedvalu')
                if (clickCheckbox1.checked) {
                    $('#is_storedvalu_show').show('fast');
                    $('.is_balance').show();
                    $('#custom_field2_type7').prop('checked', true);
                    $('.is_field2').hide();
                    $('#custom_field3_type2').parent('.radio').show();
                    $('#paymentSettings3').parent('.checkbox').show();
                } else {
                    $('#is_storedvalu_show').hide('fast');
                    $('.custom_field2_type').prop('checked', false);
                    $('#custom_field2_type0').prop('checked', true);
                    $('.custom_field3_type').prop('checked', false);
                    $('#custom_field3_type0').prop('checked', true);
                    $('#js_preview_area .demo_grade span').text('未设置');
                    $('#js_preview_area .demo_storedvalu span').text('未设置');
                    //$('#custom_field3_type2').parent('.radio').hide();
                    $('.is_field2').show();
                    if (!clickCheckbox.checked) {
                        $('#custom_field2_type1').parent('.radio').hide();
                    }
                    $('.is_balance').hide();
                    $('#paymentSettings3').parent('.checkbox').hide();
                }
                change_customid()
            });
            $('#js_colorpicker .jsDropdownBt').click(function() {
                $('#js_colorpicker .dropdown_data_container').show();
            });
            $('#ymdatepicker').datepicker({
                keyboardNavigation: false,
                forceParse: false,
                autoclose: true,
                format: "yyyy-mm-dd"
            });
            $('.input-clockpicker').clockpicker();
            /***计算字符串长度(英文占1个字符，中文汉字占2个字符)*****/
            String.prototype.gbLen = function() {
                var len = 0;
                for (var i = 0; i < this.length; i++) {
                    if (this.charCodeAt(i) > 127 || this.charCodeAt(i) == 94) {
                        len += 2;
                    } else {
                        len++;
                    }
                }
                return len;
            }
            String.prototype.strLen = function() {
                    var len = 0;
                    for (var i = 0; i < this.length; i++) {
                        var c = this.charCodeAt(i);
                        //单字节加1
                        if ((c >= 0x0001 && c <= 0x007e) || (0xff60 <= c && c <= 0xff9f)) {
                            len++;
                        } else {
                            len += 2;
                        }
                    }
                    return len;
                }
                //开关背景图片
            $('#js_editform input[name="use_image"]').click(function(event) {
                if ($(this).val() == 1) {
                    $("#js_editform .background_pic_info,#js_editform .uploadBgurl").show('fast');
                    var background_pic_url_tmp = $('#background_pic_url_local').val();
                    if (background_pic_url_tmp != '') {
                        $('#js_preview_area .js_background_pic_url_preview').css('background-image', ' url(' + background_pic_url_tmp + ')');
                    }
                } else {
                    $("#js_editform .background_pic_info,#js_editform .uploadBgurl").hide('fast');
                    $('#js_preview_area .js_background_pic_url_preview').css('background-image', 'none');
                }
            });
            //卡券封面联动
            $('.jsDropdownItem').click(function(event) {
                var colorN = $(this).attr('data_name');
                var colorV = $(this).attr('data_value');
                $('#js_colorpicker .jsBtLabel').css('background-color', colorV);
                $('#js_color').val(colorN);
                $('.js_color_bg_preview').css('background-color', colorV);
                $('.js_title_color_preview').css('color', colorV);
                $('#js_colorpicker .dropdown_data_container').hide();
            });
            //有效期联动
            $('.date_info_type').click(function(event) {
                if ($(this).val() == 'DATE_TYPE_FIX_TIME_RANGE') {
                    $('#ymdatepicker').show('fast');
                } else {
                    $('#ymdatepicker').hide('fast');
                }
            });
            //可用时段联动
            $('.is_time_limit').click(function(event) {
                if ($(this).val() == 'all') {
                    $('#ymtimepicker').hide();
                } else {
                    $('#ymtimepicker').show();
                }
            });
            //固定折扣联动
            $('#supply_discount,#supply_discount_grade').click(function(event) {
                var is_discount = $('#supply_discount:checked').val();
                var is_discount_grade = $('#supply_discount_grade:checked').val();
                if (is_discount && !is_discount_grade) {
                    $('.supply_discount_info').show('fast');
                } else {
                    $('.supply_discount_info').hide('fast');
                }
            });
            //logo上传联动
            var popoverid = '';
            $(".dropz").on('click', function(event) {
                popoverid = $(this).find('.popover').attr('id');
            });
            var localcard = $('#js_edit_area-p-0 input[name="localcard"]:checked').val();
            $(".dropz").dropzone({
                url: "?m=User&c=memberCard&a=uploadImg&type=0",
                addRemoveLinks: false,
                maxFilesize: 1,
                acceptedFiles: ".jpg,.png",
                uploadMultiple: false,
                init: function() {
                    this.on("success", function(file, responseText) {
                        var rept = $.parseJSON(responseText);
                        if (!rept.error) {
                            $('#js_logo_url_preview,#js_logo_url_preview_1').attr('src', rept.localimg);
                            $('#js_logo_url').val(rept.wxlogurl);
                            $('#' + popoverid).hide('fast');
                        } else {
                            swal({
                                title: "上传失败",
                                text: rept.msg,
                                type: "error"
                            });
                        }
                    });
                }
            });
            //卡背景上传联动
            var popoverbgid = '';
            $(".dropz_bg").on('click', function(event) {
                popoverbgid = $(this).find('.popover').attr('id');
            });
            $(".dropz_bg").dropzone({
                url: "?m=User&c=memberCard&a=uploadImg&type=1",
                addRemoveLinks: false,
                maxFilesize: 1,
                acceptedFiles: ".jpg,.png",
                uploadMultiple: false,
                init: function() {
                    this.on("success", function(file, responseText) {
                        var rept = $.parseJSON(responseText);
                        if (!rept.error) {
                            $('#js_preview_area .js_background_pic_url_preview').css('background-image', ' url(' + rept.localimg + ')');
                            $('#background_pic_url_local').val(rept.localimg);
                            $('#background_pic_url').val(rept.wxlogurl);
                            $('#' + popoverbgid).hide('fast');
                        } else {
                            swal({
                                title: "上传失败",
                                text: rept.msg,
                                type: "error"
                            });
                        }
                    });
                }
            });
            //添加图文消息
            var list_num0 = $('#js_editform .graphic').find('.graphic_list').length;
            if (list_num0 > 4) {
                $('#graphic_edit .adduplod').hide('fast');
            }
            $('#js_editform .adduplod').bind('click', function(event) {
                var list_num1 = $('#js_editform .graphic').find('.graphic_list').length;
                var adduplod_str = '<div class="graphic_list"><div style="text-align: right; position: relative; top: 25px; color: #fff; padding: 0px 1.8em;"><span class="deluplod" style="cursor: pointer;">删除</span></div><div><img src="" class="js_graphic_url_preview" style="width:374px;height:218px;display:none;"/><input type="hidden" value="" class="js_dropz_graphic_url" name="graphicintroduction[image_url][]" /><input type="hidden" value="" class="js_dropz_graphic_locl_url" name="graphicintroduction[local_url][]" /><input type="hidden" name="graphicintroduction[id][]" class="js_dropz_graphic_id" value="' + (list_num1 + 1) + '"></div><div class="dropz_graphic dropz_graphic' + (list_num1 + 1) + '" style="height: 34px;line-height: 34px;border: 1px solid #44b549;width:374px;text-align: center;cursor: pointer; color:#fff;display:inline-block;background-color:#44b549;">上传图片</div><div><textarea name="graphicintroduction[text][]" class="graphic_text" style="width:374px;height: 100px;" placeholder="图文内容建议上传商品、服务、环境等优质图片，并辅之以简单描述"></textarea></div></div>';
                $('#js_editform .graphic').append(adduplod_str);
                $('#js_editform .deluplod').show('fast');
                if ((list_num1 + 1) > 4) {
                    $('#graphic_edit .adduplod').hide('fast');
                }
                //删除图文消息
                $("#js_editform .deluplod").bind('click', function(event) {
                    var list_num2 = $("#js_editform .graphic").find(".graphic_list").length;
                    $(this).parent().parent(".graphic_list").remove();
                    $("#graphic_edit .adduplod").css("display", "inline-block");
                });
                //图文介绍上传联动
                $(".dropz_graphic" + (list_num1 + 1) + "").dropzone({
                    url: "?m=User&c=memberCard&a=uploadGraphic",
                    addRemoveLinks: false,
                    maxFilesize: 1,
                    autoDiscover: false,
                    acceptedFiles: ".jpg,.png",
                    uploadMultiple: false,
                    init: function() {
                        this.autoDiscover = false;
                        this.on("success", function(file, responseText) {
                            var rept = $.parseJSON(responseText);
                            if (!rept.error) {
                                $(this.element).prev('div').children('.js_graphic_url_preview').attr('src', rept.localimg);
                                $(this.element).prev('div').children('.js_dropz_graphic_locl_url').val(rept.localimg);
                                $(this.element).prev('div').children('.js_dropz_graphic_url').val(rept.wxlogurl);
                            } else {
                                swal({
                                    title: "上传失败",
                                    text: rept.msg,
                                    type: "error"
                                });
                            }
                        });
                    }
                });
            });
            //图文介绍上传联动
            $(".dropz_graphic0").dropzone({
                url: "?m=User&c=memberCard&a=uploadGraphic",
                addRemoveLinks: false,
                maxFilesize: 1,
                acceptedFiles: ".jpg,.png",
                uploadMultiple: false,
                init: function() {
                    this.autoDiscover = false;
                    this.on("success", function(file, responseText) {
                        var rept = $.parseJSON(responseText);
                        if (!rept.error) {
                            $(this.element).prev('div').children('.js_graphic_url_preview').attr('src', rept.localimg);
                            $(this.element).prev('div').children('.js_dropz_graphic_locl_url').val(rept.localimg);
                            $(this.element).prev('div').children('.js_dropz_graphic_url').val(rept.wxlogurl);
                            $(this.element).prev('div').children('.js_graphic_url_preview').show('fast');
                        } else {
                            swal({
                                title: "上传失败",
                                text: rept.msg,
                                type: "error"
                            });
                        }
                    });
                }
            });
            //买单功能开关
            $('#js_editform .switchery.is_pay').click(function(event) {
                var clickCheckbox = document.querySelector('.is_pay')
                if (clickCheckbox.checked) {
                    $('.is_pay_info').show('fast');
                } else {
                    $('.is_pay_info').hide('fast');
                }
            });
            //买单方式选择联动 pay_way
            $('#js_editform input[name="pay_way"]').click(function(event) {
                if ($(this).val() == 1) {
                    $('#js_editform .pay_way_info').hide('fast');
                } else {
                    $('#js_editform .pay_way_info').show('fast');
                }
            });
            //激活设置联动
            $('#js_editform input[name="activate"]').click(function(event) {
                if ($(this).val() == 0) {
                    $('#js_editform .no_auto_activate').hide('fast');
                    if ($('#js_editform input[name="localcard"]').val() == 1) {
                        $('#use_custom_code').prop('checked', false);
                        $('#is_quantity').show();
                        $('#use_custom_code').parent('div.appmsg_edit_item').hide();
                        $('#use_custom_code_info').hide();
                    }
                } else {
                    $('#js_editform .no_auto_activate').show('fast');
                    $('#use_custom_code').parent('div.appmsg_edit_item').show();
                }
            });
            change_customid();
            //类目二联动
            $('#js_editform .custom_field2_type').click(function(event) {
                var new_text = $.trim($(this).next('label').text());
                $('#js_preview_area .demo_grade span').text(new_text);
                change_customid();
            });
            //类目三联动
            $('#js_editform .custom_field3_type').click(function(event) {
                var new_text = $.trim($(this).next('label').text());
                $('#js_preview_area .demo_storedvalu span').text(new_text);
                change_customid();
            });
            change_custom_url();
            $('#js_editform .custom_url').click(function(event) {
                var new_text = '';
                if ($(this).val() > 0) {
                    new_text = $.trim($(this).next('label').text());
                }
                $('#customurlname').val(new_text);
                change_custom_url();
            });
            $('#js_editform .cell1_url').click(function(event) {
                var new_text = '';
                if ($(this).val() > 0) {
                    new_text = $.trim($(this).next('label').text());
                }
                $('#custom_cell1name').val(new_text);
                change_custom_url();
            });
            $('#js_editform .promotion_url').click(function(event) {
                var new_text = '';
                if ($(this).val() > 0) {
                    new_text = $.trim($(this).next('label').text());
                }
                $('#promotionname').val(new_text);
                change_custom_url();
            });
            //输入初始化
            $('#js_editform .ckinput').each(function(index) {
                var vtext = $(this).val();
                var ltext = Math.ceil(vtext.gbLen() / 2);
                var now_obj = $(this).parent().find('.tips').children('span').first();
                now_obj.text(ltext);
            });
            //输入联动
            $('#js_editform .ckinput').keyup(function(event) {
                var vtext = $(this).val();
                var ltext = Math.ceil(vtext.gbLen() / 2);
                var now_obj = $(this).next('.tips').children('span').first();
                var max_obj = $(this).next('.tips').children('span').last();
                var frm_msg = $(this).next('.frm_msg');
                $(this).parents('div.appmsg_edit_item').children('p.frm_msg.fail').remove();
                var this_id = $(this).attr('id');
                if (ltext <= max_obj.text()) {
                    now_obj.text(ltext);
                    switch (this_id) {
                        case 'brand_name':
                            $('#js_brand_name_preview').text(vtext);
                            break;
                        case 'title':
                            $('#js_title_preview').text(vtext);
                            break;
                        case 'center_title':
                            $('#js_center_title_preview').text(vtext);
                            break;
                        case 'center_sub_title':
                            $('#js_center_sub_title_preview').text(vtext);
                            break;
                        case 'customurlname':
                            $('#js_customurlname_preview').text(vtext);
                            break;
                        case 'customurlsubtitle':
                            $('#js_customurlsubtitle_preview').text(vtext);
                            break;
                        case 'promotionname':
                            $('#js_promotionname_preview').text(vtext);
                            break;
                        case 'promotionsubtitle':
                            $('#js_promotionsubtitle_preview').text(vtext);
                            break;
                        case 'custom_cell1name':
                            $('#js_custom_cell1name_preview').text(vtext);
                            break;
                        case 'custom_cell1tips':
                            $('#js_custom_cell1tips_preview').text(vtext);
                            break;
                    }
                }
            });
            //自定义卡号开关 use_custom_code
            $('#js_editform .switchery.use_custom_code').click(function(event) {
                var clickCheckbox = document.querySelector('.use_custom_code')
                if (clickCheckbox.checked) {
                    $('#use_custom_code_info').show('fast');
                    $('#is_quantity').hide('fast');
                } else {
                    $('#use_custom_code_info').hide('fast');
                    $('#is_quantity').show('fast');
                }
            });
            //自定义卡号处理函数
            String.prototype.customCode = function() {
                    var pattern_num = /^[0-9]*$/;
                    var pattern_en = /^[a-zA-Z]*$/;
                    var digit = $('#digit').val();
                    if (digit == '' || !pattern_num.test(digit) || digit < 2 || digit > 20) {
                        $('#digit').parent().next('.frm_msg.fail').text('请输入 2 - 20 的数字').show('fast');
                        return;
                    } else {
                        $('#digit').parent().next('.frm_msg.fail').hide('fast');
                    }
                    var prefixion = $('#prefixion').val();
                    $('#js_prefixion_tip').text(prefixion.gbLen());
                    $('#js_prefixion_limit').text(digit - 2);
                    if (!pattern_en.test(prefixion) || prefixion.gbLen() > (digit - 2)) {
                        $('#prefixion').parent().next('.frm_msg.fail').text('请输入小于' + (digit - 2) + '位的英文字母').show('fast');
                        return;
                    } else {
                        $('#prefixion').parent().next('.frm_msg.fail').hide('fast');
                    }
                    $('#custom_code_max').text(digit - prefixion.gbLen());
                    var stanum = $('#stanum').val();
                    var custom_code_sta = stanum;
                    var custom_code_end = Math.pow(10, (digit - prefixion.gbLen())) - 1;
                    if (stanum == '' || !pattern_num.test(stanum) || stanum < 0 || stanum > custom_code_end - 1) {
                        $('#stanum').parent().next('.frm_msg.fail').text('请输入 0 - ' + (custom_code_end - 1) + ' 的数字').show('fast');
                        return;
                    } else {
                        $('#stanum').parent().next('.frm_msg.fail').hide('fast');
                    }
                    var zeroize = $('#zeroize').prop('checked');
                    $('.frm_controls.custom_code_scope .custom_code_pref').text(prefixion);
                    if (zeroize == true) {
                        custom_code_sta = (Array(digit - prefixion.gbLen()).join(0) + stanum).slice(-(digit - prefixion.gbLen()));
                    }
                    $('.frm_controls.custom_code_scope .custom_code_sta').text(custom_code_sta);
                    $('.frm_controls.custom_code_scope .custom_code_end').text(custom_code_end);
                    $('.frm_controls.custom_code_scope').show();
                }
                //初始化自定义卡号
            var autoCode = '初始化自定义卡号';
            autoCode.customCode();
            $("#digit,#prefixion,#stanum").keyup(function(event) {
                var autoCode = '初始化自定义卡号';
                autoCode.customCode();
            });
            $('#zeroize').click(function(event) {
                var autoCode = '初始化自定义卡号';
                autoCode.customCode();
            });
            var form = $('#js_editform');
            form.validate({
                onsubmit: false,
                //onfocusout:true,
                errorPlacement: function(error, element) {
                    vleft = element.position().left;
                    vtop = element.position().top;
                    if (error.text()) {
                        options = {
                            placement: "top",
                            content: error.text(),
                            trigger: 'click',
                        };
                        element.popover(options);
                        element.popover('show');
                    }
                },
                success: function(label, element) {
                    $(element).popover('destroy');
                },
                focusCleanup: true
            }).settings.ignore = ":disabled,:hidden";
        },
        onStepChanging: function(event, currentIndex, newIndex) {
            if (currentIndex > newIndex) {
                //后退
                return true;
            }
            if (currentIndex + 1 < newIndex) {
                swal("温馨提示", '请点击下一步', "error");
                return false;
            }
            //currentIndex 当前的
            //newIndex 之后的
            var form = $('#js_editform');
            if (currentIndex < newIndex) {
                $(".body:eq(" + newIndex + ") label.error", form).remove();
                $(".body:eq(" + newIndex + ") .error", form).removeClass("error");
                form.find('input').focus(function(event) {
                    if ($(this).prev('.formError').length > 0) {
                        $(this).prev('.formError').remove();
                    }
                });
                form.validate({
                    onsubmit: false,
                    errorPlacement: function(error, element) {
                        vleft = element.position().left;
                        vtop = element.position().top;
                        if (error.text()) {
                            options = {
                                placement: "top",
                                content: error.text(),
                                trigger: 'click',
                            };
                            element.popover(options);
                            element.popover('show');
                        }
                    },
                    success: function(label, element) {
                        $(element).popover('destroy');
                    },
                    focusCleanup: true
                }).settings.ignore = ":disabled,:hidden";
                //前进
                var postdata = {};
                var tmpdata = '';
                var valid = true;
                tmpdata = $('#card_id').val();
                tmpdata = $.trim(tmpdata);
                postdata.card_id = tmpdata;
                switch (currentIndex) {
                    case 0: //提交第一步
                        tmpdata = $('#js_edit_area-p-0 input[name="localcard"]:checked').val();
                        tmpdata = $.trim(tmpdata);
                        postdata.localcard = tmpdata;
                        if (sms_key) {
                            var clickCheckbox = document.querySelector('.is_msg')
                            if (clickCheckbox.checked) {
                                postdata.is_msg = 1;
                            } else {
                                postdata.is_msg = 0;
                            }
                        }
                        tmpdata = $('#cost_money_unit').val();
                        tmpdata = $.trim(tmpdata);
                        /*if(!tmpdata){
                            validation($('#cost_money_unit'),'此项不能为空！');
                            valid=false;
                          }*/
                        postdata.cost_money_unit = tmpdata;
                        tmpdata = $('#increase_bonus').val();
                        tmpdata = $.trim(tmpdata);
                        postdata.increase_bonus = tmpdata;
                        tmpdata = $('#init_increase_bonus').val();
                        tmpdata = $.trim(tmpdata);
                        postdata.init_increase_bonus = tmpdata;
                        tmpdata = $('#max_increase_bonus').val();
                        tmpdata = $.trim(tmpdata);
                        postdata.max_increase_bonus = tmpdata;
                        tmpdata = $('#cost_bonus_unit').val();
                        tmpdata = $.trim(tmpdata);
                        postdata.cost_bonus_unit = tmpdata;
                        tmpdata = $('#reduce_money').val();
                        tmpdata = $.trim(tmpdata);
                        postdata.reduce_money = tmpdata;
                        tmpdata = $('#least_money_to_use_bonus').val();
                        tmpdata = $.trim(tmpdata);
                        postdata.least_money_to_use_bonus = tmpdata;
                        tmpdata = $('#max_reduce_bonus').val();
                        tmpdata = $.trim(tmpdata);
                        postdata.max_reduce_bonus = tmpdata;
                        var clickCheckbox = document.querySelector('.is_grade')
                        if (clickCheckbox.checked) {
                            postdata.is_grade = 1;
                            tmpdata = $('#exper_name').val();
                            tmpdata = $.trim(tmpdata);
                            postdata.exper_name = tmpdata;
                            tmpdata = $('#cost_bonus_exper').val();
                            tmpdata = $.trim(tmpdata);
                            postdata.cost_bonus_exper = tmpdata;
                            tmpdata = $('#increase_exper').val();
                            tmpdata = $.trim(tmpdata);
                            postdata.increase_exper = tmpdata;
                            tmpdata = $('#init_increase_exper').val();
                            tmpdata = $.trim(tmpdata);
                            postdata.init_increase_exper = tmpdata;
                        } else {
                            postdata.is_grade = 0;
                        }
                        var clickCheckbox = document.querySelector('.is_storedvalu')
                        if (clickCheckbox.checked) {
                            postdata.is_storedvalu = 1;
                            tmpdata = $('#cost_bonus_valu').val();
                            tmpdata = $.trim(tmpdata);
                            postdata.cost_bonus_valu = tmpdata;
                            tmpdata = $('#increase_valu').val();
                            tmpdata = $.trim(tmpdata);
                            postdata.increase_valu = tmpdata;
                            tmpdata = $('#stored_guidelines').val();
                            tmpdata = $.trim(tmpdata);
                            postdata.stored_guidelines = tmpdata;
                        } else {
                            postdata.is_storedvalu = 0;
                        }
                        break;
                    case 1: //提交第二步
                        tmpdata = $('#js_logo_url').val();
                        tmpdata = $.trim(tmpdata);
                        postdata.logo_url = tmpdata;
                        tmpdata = $('#js_edit_area-p-1 input[name="use_image"]:checked').val();
                        tmpdata = $.trim(tmpdata);
                        postdata.use_image = tmpdata;
                        tmpdata = $('#background_pic_url').val();
                        tmpdata = $.trim(tmpdata);
                        postdata.background_pic_url = tmpdata;
                        tmpdata = $('#js_color').val();
                        tmpdata = $.trim(tmpdata);
                        postdata.color = tmpdata;

                        tmpdata = $('#js_edit_area-p-1 input.date_info_type:checked').val();
                        tmpdata = $.trim(tmpdata);
                        postdata.type = tmpdata;
                        if (postdata.type == 'DATE_TYPE_FIX_TIME_RANGE') {
                            tmpdata = $('#ymstart').val();
                            tmpdata = $.trim(tmpdata);
                            postdata.begin_timestamp = tmpdata;
                            tmpdata = $('#ymend').val();
                            tmpdata = $.trim(tmpdata);
                            postdata.end_timestamp = tmpdata;
                        }
                        tmpdata = $('#js_edit_area-p-1 input.is_time_limit:checked').val();
                        tmpdata = $.trim(tmpdata);
                        postdata.is_time_limit = tmpdata;
                        if (postdata.is_time_limit == 'part') {
                            tmpdata = '';
                            $("#js_edit_area-p-1 input.time_limit_type:checked").each(function() {
                                tmpdata += "," + $.trim($(this).val());
                            });
                            postdata.time_limit_type = tmpdata;
                            tmpdata = $('#lmstar1').val();
                            tmpdata = $.trim(tmpdata);
                            postdata.begin_time1 = tmpdata;
                            tmpdata = $('#lmend1').val();
                            tmpdata = $.trim(tmpdata);
                            postdata.end_time1 = tmpdata;
                            tmpdata = $('#lmstar2').val();
                            tmpdata = $.trim(tmpdata);
                            postdata.begin_time2 = tmpdata;
                            tmpdata = $('#lmend2').val();
                            tmpdata = $.trim(tmpdata);
                            postdata.end_time2 = tmpdata;
                        }
                        tmpdata = $('#supply_bonus:checked').val();
                        tmpdata = $.trim(tmpdata);
                        postdata.supply_bonus = tmpdata;
                        tmpdata = $('#supply_discount:checked').val();
                        tmpdata = $.trim(tmpdata);
                        postdata.supply_discount = tmpdata;
                        tmpdata = $('#supply_discount_grade:checked').val();
                        tmpdata = $.trim(tmpdata);
                        postdata.supply_discount_grade = tmpdata;
                        tmpdata = $('#discount').val();
                        tmpdata = $.trim(tmpdata);
                        postdata.discount = tmpdata;
                        tmpdata = $('#notice').val();
                        tmpdata = $.trim(tmpdata);
                        postdata.notice = tmpdata;
                        tmpdata = $('#prerogative').val();
                        tmpdata = $.trim(tmpdata);
                        postdata.prerogative = tmpdata;
                        tmpdata = $('#description').val();
                        tmpdata = $.trim(tmpdata);
                        postdata.description = tmpdata;
                        //图文
                        tmpdata = [];
                        $('#js_edit_area-p-1 input.js_dropz_graphic_url').each(function(index, el) {
                            tmpdata[index] = $.trim($(this).val());
                        });
                        postdata.js_dropz_graphic_url = tmpdata;
                        tmpdata = [];
                        $('#js_edit_area-p-1 input.js_dropz_graphic_locl_url').each(function(index, el) {
                            tmpdata[index] = $.trim($(this).val());
                        });
                        postdata.js_dropz_graphic_locl_url = tmpdata;
                        tmpdata = [];
                        $('#js_edit_area-p-1 input.js_dropz_graphic_id').each(function(index, el) {
                            tmpdata[index] = $.trim($(this).val());
                        });
                        postdata.js_dropz_graphic_id = tmpdata;
                        tmpdata = [];
                        $('#js_edit_area-p-1 .graphic_text').each(function(index, el) {
                            tmpdata[index] = $.trim($(this).val());
                        });
                        postdata.graphic_text = tmpdata;
                        tmpdata = $('#service_phone').val();
                        tmpdata = $.trim(tmpdata);
                        postdata.service_phone = tmpdata;
                        tmpdata = '';
                        $("#js_edit_area-p-1 input.business_service:checked").each(function() {
                            tmpdata += "," + $.trim($(this).val());
                        });
                        postdata.business_service = tmpdata;
                        break;
                    case 2:
                        tmpdata = $('#js_edit_area-p-2 input[name="comsume_way"]:checked').val();
                        tmpdata = $.trim(tmpdata);
                        postdata.comsume_way = tmpdata;
                        var clickCheckbox = document.querySelector('.is_pay')
                        if (clickCheckbox.checked) {
                            postdata.is_pay = 1;
                            tmpdata = '';
                            $("#js_edit_area-p-2 input[name='pay_way']:checked").val()
                            postdata.pay_way = tmpdata;
                            if (postdata.pay_way == 0) {
                                tmpdata = '';
                                $("#js_edit_area-p-2 input[name='paymentSettings']:checked").each(function() {
                                    tmpdata += "," + $.trim($(this).val());
                                });
                                postdata.paymentSettings = tmpdata;
                                var clickCheckbox = document.querySelector('.is_points')
                                if (clickCheckbox.checked) {
                                    postdata.is_points = 1;
                                } else {
                                    postdata.is_points = 0;
                                }
                                var clickCheckbox = document.querySelector('.is_overlay')
                                if (clickCheckbox.checked) {
                                    postdata.is_overlay = 1;
                                } else {
                                    postdata.is_overlay = 0;
                                }
                                var clickCheckbox = document.querySelector('.is_autopoint')
                                if (clickCheckbox.checked) {
                                    postdata.is_autopoint = 1;
                                } else {
                                    postdata.is_autopoint = 0;
                                }
                                var clickCheckbox = document.querySelector('.is_mandatory')
                                if (clickCheckbox.checked) {
                                    postdata.is_mandatory = 1;
                                } else {
                                    postdata.is_mandatory = 0;
                                }
                            }
                        } else {
                            postdata.is_pay = 0;
                        }
                        tmpdata = $('#center_title').val();
                        tmpdata = $.trim(tmpdata);
                        postdata.center_title = tmpdata;
                        /*tmpdata=$('#center_sub_title').val();
                        tmpdata=$.trim(tmpdata);
                        postdata.center_sub_title=tmpdata;*/
                        break;
                    case 3:
                        /*tmpdata = '';
                        $("#js_edit_area-p-3 input.inputpoiid:checked").each(function() {
                            tmpdata += ","+$.trim($(this).val());
                        });
                        postdata.store_ids=tmpdata;*/
                        tmpdata = $('#js_edit_area-p-3 input.activate:checked').val();
                        tmpdata = $.trim(tmpdata);
                        postdata.activate = tmpdata;
                        if (postdata.activate == 1) {
                            tmpdata = '';
                            $("#js_edit_area-p-3 input[name='activate_required']:checked").each(function() {
                                tmpdata += "," + $.trim($(this).val());
                            });
                            postdata.activate_required = tmpdata;
                            tmpdata = '';
                            $("#js_edit_area-p-3 input.activate_norequired:checked").each(function() {
                                tmpdata += "," + $.trim($(this).val());
                            });
                            postdata.activate_norequired = tmpdata;
                        }
                        tmpdata = $('#signin_one_point').val();
                        tmpdata = $.trim(tmpdata);
                        postdata.signin_one_point = tmpdata;
                        tmpdata = $('#signin_one_exp').val();
                        tmpdata = $.trim(tmpdata);
                        postdata.signin_one_exp = tmpdata;
                        tmpdata = $('#signin_mon_total').val();
                        tmpdata = $.trim(tmpdata);
                        postdata.signin_mon_total = tmpdata;
                        tmpdata = $('#signin_mon_point').val();
                        tmpdata = $.trim(tmpdata);
                        postdata.signin_mon_point = tmpdata;
                        tmpdata = $('#signin_mon_exp').val();
                        tmpdata = $.trim(tmpdata);
                        postdata.signin_mon_exp = tmpdata;
                        var clickCheckbox = document.querySelector('.can_share')
                        if (clickCheckbox.checked) {
                            postdata.can_share = 1;
                        } else {
                            postdata.can_share = 0;
                        }
                        tmpdata = $('#js_edit_area-p-3 input.modify_msg_operation_type:checked').val();
                        tmpdata = $.trim(tmpdata);
                        postdata.modify_msg_operation_type = tmpdata;
                        //消息通知内容
                        break;
                    }
                    if (form.valid()) {
                        postdata.startIndex = currentIndex;
                        $.post('?m=User&c=memberCard&a=ajaxUpdateCard', postdata, function(ret) {
                            if (ret.error) {
                                swal("温馨提示", ret.msg, "error");
                                return false;
                            }
                        }, 'JSON');
                        return true
                    } else {
                        return false;
                    }
                }
            },
            onStepChanged: function(event, currentIndex, priorIndex) {
            //priorIndex 之前的
            //currentIndex 当前的
        },
        onFinishing: function(event, currentIndex) {
            var form = $(this);
            //currentIndex 当前的
            var postdata = {};
            var tmpdata = '';
            tmpdata = $('#card_id').val();
            tmpdata = $.trim(tmpdata);
            postdata.card_id = tmpdata;
            postdata.startIndex = currentIndex;
            tmpdata = $('#js_edit_area-p-4 input.custom_field2_type:checked').val();
            tmpdata = $.trim(tmpdata);
            postdata.custom_field2_type = tmpdata;
            tmpdata = $('#js_edit_area-p-4 input.custom_field3_type:checked').val();
            tmpdata = $.trim(tmpdata);
            postdata.custom_field3_type = tmpdata;
            tmpdata = $('#customurlname').val();
            tmpdata = $.trim(tmpdata);
            postdata.custom_url_name = tmpdata;
            tmpdata = $('#customurlsubtitle').val();
            tmpdata = $.trim(tmpdata);
            postdata.custom_url_sub_title = tmpdata;
            tmpdata = $('#js_edit_area-p-4 input.custom_url:checked').val();
            tmpdata = $.trim(tmpdata);
            postdata.custom_url = tmpdata;
            tmpdata = $('#promotionname').val();
            tmpdata = $.trim(tmpdata);
            postdata.promotion_url_name = tmpdata;
            tmpdata = $('#promotionsubtitle').val();
            tmpdata = $.trim(tmpdata);
            postdata.promotion_url_sub_title = tmpdata;
            tmpdata = $('#js_edit_area-p-4 input.promotion_url:checked').val();
            tmpdata = $.trim(tmpdata);
            postdata.promotion_url = tmpdata;
            tmpdata = $('#custom_cell1name').val();
            tmpdata = $.trim(tmpdata);
            postdata.custom_cell1name = tmpdata;
            tmpdata = $('#custom_cell1tips').val();
            tmpdata = $.trim(tmpdata);
            postdata.custom_cell1tips = tmpdata;
            tmpdata = $('#js_edit_area-p-4 input.cell1_url:checked').val();
            tmpdata = $.trim(tmpdata);
            postdata.cell1_url = tmpdata;

            $.post('?m=User&c=memberCard&a=ajaxUpdateCard', postdata, function(data) {
                if (data.error) {
                    swal("温馨提示", data.msg, "error");
                    return false;
                } else {
                    swal("温馨提示", data.msg, "success");
                    window.location.href = 'http://' + window.location.host + '/merchants.php?m=User&c=memberCard&a=index';
                }
            }, 'JSON');
            return form;
        },
        onFinished: function(event, currentIndex) {
            var form = $(this);
            //$('#js_editform').submit();
        },
        labels: {
            finish: "完成",
            next: "下一步",
            previous: "上一步"
        }
    });
});

jQuery.validator.addMethod("lenientTel", function(value, element) {
    return this.optional(element) || (/^([0-9]{1,9}(\-)?)?([0-9]{1,9}){1}(\-[0-9]{1,9})?$/.test(value));
}, "电话号码格式错误!");

jQuery.validator.addMethod("maxNum", function(value, element) {
    return this.optional(element) || (value.gbLen() / 2 <= $(element).attr('maxNum'));
}, "输入内容长度错误!");

jQuery.validator.addMethod("greaterto", function(value, element) {
    return this.optional(element) || (value >= $($(element).attr('greaterto')).val());
}, "不能小于起始值!");

$.extend($.validator.messages, {
    required: "这是必填项",
    remote: "请修正此项",
    email: "请输入有效的电子邮件地址",
    url: "请输入有效的网址",
    date: "请输入有效的日期",
    dateISO: "请输入有效的日期 (YYYY-MM-DD)",
    number: "请输入有效的数字",
    digits: "只能输入整数",
    creditcard: "请输入有效的信用卡号码",
    equalTo: "你的输入不相同",
    extension: "请输入有效的后缀",
    maxlength: $.validator.format("最多可以输入 {0} 个字符"),
    minlength: $.validator.format("最少要输入 {0} 个字符"),
    rangelength: $.validator.format("请输入长度在 {0} 到 {1} 之间的字符串"),
    range: $.validator.format("请输入范围在 {0} 到 {1} 之间的数值"),
    max: $.validator.format("请输入不大于 {0} 的数值"),
    min: $.validator.format("请输入不小于 {0} 的数值")
});

function change_customid() {
    var now_customid = '';
    //初始化类目
    if ($('#js_editform .custom_field2_type:checked').val() > 0) {
        now_customid += ',' + $('#js_editform .custom_field2_type:checked').val();
    }
    if ($('#js_editform .custom_field3_type:checked').val() > 0) {
        now_customid += ',' + $('#js_editform .custom_field3_type:checked').val();
    }
    $('#js_editform .custom_field2_type').attr('disabled', false);
    $('#js_editform .custom_field3_type').attr('disabled', false);
    $('#js_editform .custom_field2_type').each(function(index, el) {
        if (now_customid.indexOf($(this).val()) > 0 && !$(this).is(':checked')) {
            $(this).attr('disabled', true);
        }
    });
    $('#js_editform .custom_field3_type').each(function(index, el) {
        if (now_customid.indexOf($(this).val()) > 0 && !$(this).is(':checked')) {
            $(this).attr('disabled', true);
        }
    });
}

function change_custom_url() {
    var now_customurl = '';
    //初始化自定义入口
    $('#js_editform .ckinput').each(function(index) {
        var vtext = $(this).val();
        var ltext = Math.ceil(vtext.gbLen() / 2);
        var now_obj = $(this).parent().find('.tips').children('span').first();
        now_obj.text(ltext);
    });
    if ($('#js_editform .custom_url:checked').val() > 0) {
        now_customurl += ',' + $('#js_editform .custom_url:checked').val();
    }
    if ($('#js_editform .cell1_url:checked').val() > 0) {
        now_customurl += ',' + $('#js_editform .cell1_url:checked').val();
    }
    if ($('#js_editform .promotion_url:checked').val() > 0) {
        now_customurl += ',' + $('#js_editform .promotion_url:checked').val();
    }
    console.log(now_customurl + '1111');
    $('#js_editform .custom_url').attr('disabled', false);
    $('#js_editform .cell1_url').attr('disabled', false);
    $('#js_editform .promotion_url').attr('disabled', false);
    $('#js_editform .custom_url').each(function(index, el) {
        if (now_customurl.indexOf($(this).val()) > 0 && !$(this).is(':checked')) {
            $(this).attr('disabled', true);
        }
    });
    $('#js_editform .cell1_url').each(function(index, el) {
        if (now_customurl.indexOf($(this).val()) > 0 && !$(this).is(':checked')) {
            $(this).attr('disabled', true);
        }
    });
    $('#js_editform .promotion_url').each(function(index, el) {
        if (now_customurl.indexOf($(this).val()) > 0 && !$(this).is(':checked')) {
            $(this).attr('disabled', true);
        }
    });
}