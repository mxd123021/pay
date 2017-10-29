<?php
namespace SX\Model;
class WithdrawModel extends BaseModel {
     /**
	  * 查询商户提现申请信息
	  * @status 1:已提现 2：申请中 3：申请失败
	  */
     public function getTx($status){
		$order = $this->where("status=".$status)->order('applytime asc')->select();
		return $order;
	 }

     /**
	  * 根据提现单号查询单号信息
	  */
     public function get($id){
		$order = $this->where("orderid='".$id."'")->find();
		return $order;
	 }

     /**
	  * 根据提现单号查询未提现单号信息
	  */
     public function get_2($id){
		$order = $this->where("orderid='".$id."' and status = 2")->find();
		return $order;
	 }

     /**
	  * 商户提现订单状态变更
	  */
     public function withdrawstatus($orderId,$status){
     	$data = array();
     	$data['status'] = $status;
     	$order = $this->where("orderid = '".$orderId."'")->save($data);
	 }
}