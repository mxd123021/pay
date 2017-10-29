$('#downloadEwm11').click(function(){
 var canvasobj=$('#qr-code-forever').find('canvas');
 if(canvasobj.size() >0 ){
     saveCanvasImg(canvasobj.get(0),'JPEG','you-need-pay-money-'+thismoney+'yuan-ewmimg.jpg',170,170);
 }else{
    alert('您还没有生成支付二维码');
 }
});

$('#downloadEwm12').click(function(){
 var canvasobj=$('#qr-code-forever').find('canvas');
 if(canvasobj.size() >0 ){
     saveCanvasImg(canvasobj.get(0),'JPEG','you-need-pay-money-'+thismoney+'yuan-ewmimg.jpg',800,800);
 }else{
    alert('您还没有生成支付二维码');
 }
});

$('#downloadEwm21').click(function(){
 var canvasobj=$('#qr-code-autopay').find('canvas');
 if(canvasobj.size() >0 ){
     saveCanvasImg(canvasobj.get(0),'JPEG','autopay-ewmimg.jpg',170,170);
 }else{
    alert('您还没有生成支付二维码');
 }
});

$('#downloadEwm22').click(function(){
 var canvasobj=$('#qr-code-autopay').find('canvas');
 if(canvasobj.size() >0 ){
     saveCanvasImg(canvasobj.get(0),'JPEG','autopay-ewmimg.jpg',800,800);
 }else{
    alert('您还没有生成支付二维码');
 }
});

function saveCanvasImg(canvasObj,imgtype,fname,weight,height){
		var bRes = false;
		if (imgtype == "PNG")
			bRes = Canvas2Image.saveAsPNG(canvasObj,weight,height,fname);
		if (imgtype == "BMP")
			bRes = Canvas2Image.saveAsBMP(canvasObj,weight,height,fname);
		if (imgtype == "JPEG")
			bRes = Canvas2Image.saveAsJPEG(canvasObj,weight,height,fname);
   }

 function GetDetail(id){
   var getUrl=odurl+"?id="+id;
    $.get(getUrl,function(ret){
	   if(ret){
		   $('body').append('<div class="modal-backdrop in"></div>');
	      $('#oderinfo .modal-body').html(ret);
		  $('#oderinfo').show();
	   }
	},'html');
 }

  $("#lkewmdown ._close").click(function(){
	  $('#lkewmdown').hide();
	  $('.modal-backdrop').remove();
  });

  $("#ewmdown ._close").click(function(){
	  $('#ewmdown').hide();
	  $('.modal-backdrop').remove();
  });

  $("#oderinfo ._close").click(function(){
	  $('#oderinfo').hide();
	  $('.modal-backdrop').remove();
	  $('#oderinfo .modal-body').html('');
  });