<?php if (!defined('THINK_PATH')) exit();?><style>.modal-body{padding: 10px; }
#ibox-content-me{padding: 0px;}
#ibox-content-me .info-table th{text-align:left;}


    #popPay .modal-footer{padding: 15px 0 0;
    text-align: center;}
    #popPay .modal-dialog{width: 530px;}
    #popPay .spiner-example{height: 170px;}
    #popPay .okk{height: 170px;color: #0ab85c;font-size: 45px;text-align: center;}
    #popPay .tip{color: #0ab85c;}
    #popPay .modal-footer button{font-size: 22px;}
    .inmodal .modal-header{padding: 30px 15px 15px 15px;}
    .showSweetAlert input[type="text"]{
        display: inline;
    }

</style>
<div class="ibox-content" id="ibox-content-me">
   <div class="page-trade-order-detail">
         <?php if(!empty($order)){ ?>
			<!--<div class="step-region">
			 <ul class="ui-step ui-step-4">
			  <li class="ui-step-done">
					<div class="ui-step-title">买家下单</div>
					<div class="ui-step-number">1</div>
					<div class="ui-step-meta"><?php echo date('Y-m-d H:i:s',$order['add_time'])?></div>
				</li>
			  <li class="ui-step-done">
					<div class="ui-step-title">付款至平台</div>
					<div class="ui-step-number">2</div>
					<div class="ui-step-meta"><?php echo date('Y-m-d H:i:s',$order['paytime'])?></div>
			   </li>
			  <li class="ui-step-done">
					<div class="ui-step-title">商家发货</div>
					<div class="ui-step-number">3</div>
					<div class="ui-step-meta"><?php echo date('Y-m-d H:i:s',$order['paytime'])?></div>
			   </li>
			  <li class="ui-step-done">
					<div class="ui-step-title">结算货款</div>
					<div class="ui-step-number">4</div>
					<div class="ui-step-meta"><?php echo date('Y-m-d H:i:s',$order['paytime'])?></div>
			   </li>
			 </ul>
			</div>-->
			
			<div class="content-region clearfix">
			
			 <div class="info-region" style="width: 100%;">
			  <h3><span>订单信息</span></h3>
			  <table class="info-table">
			   <tbody>
			   	<tr>
				 <th>商品名称：</th>
				 <td>&nbsp;&nbsp;<span><?php echo htmlspecialchars_decode($order['goods_name'],ENT_QUOTES);?></span></td>
				 </tr>
				<tr>
				 <th>订单编号：</th>
				 <td>&nbsp;&nbsp;<span><?php echo $order['order_id'];?></span></td>
				 </tr>
				 <tr>
				 <tr>
				 <th>付款方式：</th>
				 <td>&nbsp;&nbsp;<?php if($order['pay_way']=='weixin'){ echo "微信"; }elseif($order['pay_way']=='alipay'){ echo "支付宝"; }elseif($order['pay_way']=='qq'){ echo "QQ"; }else{ echo "其他"; } if($order['pay_type']=='NATIVE'){ echo " - 扫码"; }elseif($order['pay_type']=='MICROPAY'){ echo " - 刷卡"; }elseif($order['pay_type']=='JSAPI'){ echo " - 自助"; } echo "支付"; ?></td>
				</tr>
				<!--<tr>-->
				 <!--<th>门店：</th>-->
				 <!--<td>&nbsp;&nbsp;<span><?php if($store[$order['storeid']]==""){echo "无";}else{echo $store[$order['storeid']];}?></span></td>-->
				 <!--</tr>-->
				<!--<tr>-->
				 <!--<th>收银员：</th>-->
				 <!--<td>&nbsp;&nbsp;<span><?php if($ustaff[$order['eid']]==""){echo "无";}else{echo $ustaff[$order['eid']];}?></span></td>-->
				 <!--</tr>-->
				<tr>
				 <th>第三方支付订单ID：</th>
				 <td>&nbsp;&nbsp;<span><?php echo $order['transaction_id']?></span></td>
				 </tr>
				 <tr>
				 <th>支付者：</th>
				 <td>&nbsp;&nbsp;<?php if(!empty($order['truename'])){ echo htmlspecialchars_decode($order['truename'],ENT_QUOTES); }elseif(!empty($order['openid'])){ echo $order['openid']; }else{ echo '未知'; }?></td>
				</tr>
				  <?php if(!empty($order['refundtext'])){?>
				     		<tr><th>退款时间：</th>
							<td>&nbsp;&nbsp;<?php $refundtext = explode(";",$order['refundtext']); echo date("Y-m-d H:i:s",$refundtext[2]); ?>		</td></tr>
					 <?php }?>
				<!--<tr><th>类型：</th>-->
				<!--<?php if($order['mchtype']==2){?>-->
					<!--<td>&nbsp;&nbsp;<span>平台代收</span></td>-->
				<!--<?php }elseif($order['mchtype']==3){?>-->
					<!--<td>&nbsp;&nbsp;<span>受理模式</span></td>-->
				<!--<?php }else{ ?>-->
					<!--<td>&nbsp;&nbsp;<span>普通</span></td>-->
				<!--<?php }?>-->
				<!--</tr>-->
			   </tbody>
			  </table>
			  <div class="dashed-line"></div>
			 </div>

			</div>

		<table class="ui-table ui-table-simple goods-table">
		 <thead>
		  <tr>
		   <th>支付金额(元)</th>
		   <th data-hide="phone">支付时间</th>
                   <th>已退款金额(元)</th>
		   <th data-hide="phone">订单状态</th>
                   <!--<th>操作</th>-->
		  </tr>
		 </thead>
		 <tbody>
		  <tr class="test-item">
		   <td><span><?php echo $order['goods_price'];?></span></td>
		   <td><?php echo date('Y-m-d H:i:s',$order['paytime'])?></td>
                    <td><?php echo $order['refund_fee']?></td>
		   <td>
				<?php if($order['refund']==1){?>
			     <font>退款中...</font>
			<?php }elseif($order['refund']==2){?>
			     <font color="#2e6da4">已退款</font>
			<?php }elseif($order['refund']==3){?>
			     <font color="#ed5565">退款失败</font>
			 <?php }else{?>
                            <?php if($order['ispay']==1){?>
                             <font color="#44b549">已支付</font>
                            <?php }else{ ?>
                            <font color="#ed5565">未支付</font>
                            <?php }?>
                        <?php }?>
		   </td>
                   <!--<td>-->
                       <!--<button class="btn btn-primary btn-sm" onclick="refund(<?php echo ($order["id"]); ?>, <?php echo ($order["goods_price"]); ?>);"><strong>退款</strong></button>-->
                        <!--<button class="btn btn-sm btn-info" onclick="queryRefund(<?php echo ($order["id"]); ?>);"><strong>查询退款</strong></button>-->
                   <!--</td>-->
		  </tr>
		 </tbody>
<!-- 		 <tfoot>
 <tr>
  <td colspan="8" class="text-right"><span class="c-gray">应收总价：</span><span class="real-pay ui-money-income"><span>&yen; </span><span><?php echo $order['goods_price'];?></span></span></td>
 </tr>
</tfoot> -->
		</table>
                        
	  <?php }else{?>
		<div class="step-region" style="border: medium none; font-size: 20px; margin-top: 15px;text-align: center;"><div>订单不存在！</div></div>
	  <?php }?>
   </div>
  </div> 

<div class="modal inmodal" tabindex="-1" role="dialog"  id="popPay">
        <div class="modal-dialog">
            <div class="modal-content animated bounceInRight">
                <div class="modal-header">
                    <h6 class="modal-title">请耐心等待用户支付完成....</h6>
                    <span>请耐心等待支付完成，不要点取消！</span>
                </div>
                <div class="modal-body">
                    <div class="spiner-example" style="padding-top: 30px;">
                        <div class="sk-spinner sk-spinner-circle" style="height: 100px;width: 100px;">
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
                    <div class="modal-footer">                       
                        <button id="btntxt" type="button" class="btn btn-white" onclick="window.location.reload();"> 取 消 </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

  <script>
   $('.ui-table-simple').footable();
   
   function refund(id, price){              
        swal({  
            title: "退款确认",  
            text: "请输入退款金额",  
            type: "input",  
            showCancelButton: true,  
            confirmButtonColor: "#DD6B55",  
            confirmButtonText: "退款",  
            cancelButtonText: "取消",  
            inputValue: price,
        closeOnConfirm: false,  
        closeOnCancel: true }, 

            function(inputValue){
//                inputValue = Number(inputValue);
                        if (inputValue === false) return false;
                        if (inputValue === "" || isNaN(inputValue)) {      
                            swal.showInputError("请正确输入退款金额！");
                            return false    
                        }       
                        $('#popPay .modal-header').html('<h6 class="modal-title">请耐心等待....</h6><span>请耐心等待，不要点取消！</span>');
                        $.ajax({
                            type: "POST",
                            url: "<?php echo U('Manage/Xingye/refund');?>",
                            data: {'id': id, 'refund_fee': inputValue},
                            dataType: "json",
                            beforeSend: function(){
                                $('#popPay').show();
                            },
                            success: function(data){
                                    $('#popPay').hide();
                                    swal(data.msg, 'success');                        
                                      },
                            error:function(XMLHttpRequest, textStatus, errorThrown){
                                $('#popPay').hide();
                                swal('请求失败， textStatus:'+textStatus, 'error');
                            }
                        });  
                    } 
        );     
    }
    
    function queryRefund(id){
            $('#popPay .modal-header').html('<h6 class="modal-title">请耐心等待....</h6><span>请耐心等待，不要点取消！</span>');
             $.ajax({
            type: "POST",
            url: "<?php echo U('Manage/Xingye/queryRefund');?>",
            data: {'id': id},
            dataType: "json",
            beforeSend: function(){
                $('#popPay').show();
            },
            success: function(data){
                    $('#popPay').hide();
                    if(1 == data.status){
                        swal('订单正在退款中...', 'success');
                    }else if(2 == data.status){
                        swal('订单已退款成功！', 'success');
                    }else if(3 == data.status){
                        swal('退款失败！', 'success');
                    }else{
                        swal(data.msg, 'success');
                    }                    
                        
                      },
            error:function(XMLHttpRequest, textStatus, errorThrown){
                $('#popPay').hide();
                swal('请求失败， textStatus:'+textStatus, 'error');
            }
        });
    
    
    }
	</script>