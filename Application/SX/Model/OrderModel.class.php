<?php
namespace SX\Model;
class OrderModel extends BaseModel {
     /**
	  * 根据订单号查询订单信息
	  */
     public function get($orderid){
	 	$orderid = $orderid?$orderid:0;
		$order = $this->where("transaction_id like ".$orderid." or order_id like ".$orderid)->find();
		return $order;
	 }

     /**
	  * 查询当前用户某时间段订单
	  */
     public function selectOrders($userId=0,$starttime,$endtime){
	 	$userId = $userId?$userId:I('id',0);
		$rs = $this->where("uid=".$userId." and (paytime >= ".$starttime." and paytime <= ".$endtime.")")->select();
		if(empty($rs))$rs = null;
		return $rs;
	 }

     /**
	  * 查询所有用户某年订单
	  */
     public function yearOrders($year,$model="count"){
	 	if($model == "count"){
	 		$rs = $this->where("year(FROM_UNIXTIME(paytime))=".$year)->count();
	 		if(empty($rs))$rs = 0;
		}else if($model == "select"){
			$rs = $this->where("year(FROM_UNIXTIME(paytime))=".$year)->field('goods_price,paytime')->select();
			if(empty($rs))$rs = null;
		}
		return $rs;
	 }

     /**
	  * 查询所有用户某年某月订单
	  */
     public function monthOrders($time,$model="count"){
	 	if($model == "count"){
	 		$rs = $this->where("date_format(FROM_UNIXTIME(paytime),'%Y-%m')='".$time."'")->count();
	 		if(empty($rs))$rs = 0;
		}else if($model == "select"){
			$rs = $this->where("date_format(FROM_UNIXTIME(paytime),'%Y-%m')='".$time."'")->field('goods_price,paytime')->select();
			if(empty($rs))$rs = null;
		}
		return $rs;
	 }

     /**
	  * 查询当前用户订单数
	  */
     public function countOrders($sql="1"){
		$rs = $this->where($sql)->count();
		if(empty($rs))$rs = 0;
		return $rs;
	 }

     /**
	  * 查询所有用户收入
	  */
     public function countIncome($sql="1"){
		$rs = $this->where("refund = 0 and ".$sql)->sum('goods_price');
		if(empty($rs))$rs = 0;
		return $rs;
	 }

     /**
	  * 查询当前用户某年某月收入
	  */
     public function monthIncome($time){
		$rs = $this->where("refund = 0 and date_format(FROM_UNIXTIME(paytime),'%Y-%m')='".$time."'")->sum('goods_price');
		if(empty($rs))$rs = 0;
		return $rs;
	 }

     /**
	  * 查询所有用户所有订单信息
	  */
     public function getAll($sql="1",$limit = 0){
		$order = $this->where($sql)->order('paytime desc')->limit($limit)->select();
		return $order;
	 }
}