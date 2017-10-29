var checkPhone = function(a) {
	var patrn = /^((?:13|15|18|14|17)\d{9}|0(?:10|2\d|[3-9]\d{2})[1-9]\d{6,7})$/;
	if (!patrn.exec(a)) return false;
	return true;
};
function check(type){
	var str = $("#iphone1").val();
	var types = $("#types").val();
	if (str.length == 11 && checkPhone(str)) {
		$("#fixed-wrongtip2").fadeIn(1000);
		$.ajax({
	        url: "http://www.amacm.com/SX/Api/addPhone",
	        type: "POST",
	        dataType: "json",
	        data:{phone:str,type:types},
	        success: function(res){
	        }
    	});
	} else {
		$("#"+type).fadeIn(1000);
	}
}
function openclass(type){
	$("."+type).fadeIn(1000);
	}
function closeclass(type){
	$("."+type).fadeOut(1000);
	}
function openid(type){
	$("#"+type).fadeIn(1000);
	}
function closeid(type){
	$("#"+type).fadeOut(1000);
	}
function showid(type){
	$("#"+type).show();
	}
function hideid(type){
	$("#"+type).hide();
	}
function showclass(type){
	$("."+type).show();
	}
function hideclass(type){
	$("."+type).hide();
	}
