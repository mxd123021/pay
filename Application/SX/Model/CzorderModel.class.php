<?php
namespace SX\Model;
class CzorderModel extends BaseModel {
     /**
	  * 添加微信订单信息
	  */
     public function addWxCzOrder($rs){
		$rd = array('status'=>-1);
		if(!empty($rs)){
			$data = array();
			$attach = explode(",",$rs['attach']);
			$data['order_id'] = $rs['out_trade_no'];
			$data['uid'] = $attach[0]; //商户ID
			$data['pay_way'] = "weixin"; //支付平台
			$data['pay_type'] = $rs['trade_type']; //支付类型
			$data['mch_id'] = $rs['mch_id']; //商户ID
			if($rs['sub_mch_id']){
				$data['sub_mch_id'] = $rs['sub_mch_id']; //子商户ID
			}
			$data['goods_name'] = empty($attach[4])?"未知":$attach[4]; //商品名称
			$data['goods_describe'] = empty($attach[5])?"未知":$attach[5]; //支付方式
			$data['goods_price'] = $rs['total_fee']/100; //价格
			$data['add_time'] = time(); //添加时间
			$data['paytime'] = strtotime($rs['time_end']); //付款时间
			$data['state'] = 0;
			$data['ispay'] = 1; //是否支付
			$data['openid'] = $rs['openid'];
			$data['transaction_id'] = $rs['transaction_id'];
			$data['refund'] = 0; //退款状态 1退款中 2已退款 3退款失败
			$data['refundtext'] = ""; //退款人员和店铺
			$data['token'] = $attach[6]; //充值是否成功查询码

			$rs = $this->add($data);
			if(false !== $rs){
				$rd['status']= 1;
			}
		}
		return $rd;
	 }

     /**
	  * 查询所有用户充值信息
	  */
     public function getAll(){
     	$sql = "select * from __PREFIX__czorder";
	 	$sql .=" order by paytime desc ";
		$order = $this->pageQuery($sql);
		return $order;
	 }

     /**
	  * 查询所有用户充值金额
	  */
     public function monthCz($time){
     	$rs = $this->where("date_format(FROM_UNIXTIME(paytime),'%Y-%m')='".$time."'")->sum('goods_price');
		if(empty($rs))$rs = 0;
		return $rs;
	 }

     /**
	  * 查询当前订单是否充值成功
	  */
     public function getToken($token){
     	$order = $this->where("token = '%s'",$token)->find();
		return $order;
	 }
}