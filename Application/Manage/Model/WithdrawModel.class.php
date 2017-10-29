<?php
namespace Manage\Model;
class WithdrawModel extends BaseModel {
     /**
	  * 添加提现申请
	  */
     public function addWithdraw($money){
     	$rd = array("status"=>-1);
     	$withdraw['orderid'] =  "T".$money['userId'].date("YmdHis");
     	$withdraw['userId'] =  $money['userId'];
     	$withdraw['dprice'] =  $money['dscurmoney'] * 0.006;
     	$withdraw['txprice'] =  $money['dscurmoney'];
     	$withdraw['price'] =  $money['dscurmoney'] - $money['dscurmoney'] * 0.006;
     	$withdraw['applytime'] =  time();
     	$withdraw['stime'] =  time();
     	$withdraw['status'] =  2;
		$rs = $this->add($withdraw);
		if(false !== $rs){
			$rd['txprice'] = $withdraw['txprice'];
			$rd['price'] = $withdraw['price'];
			$rd['status'] = 1;
		}
		return $rd;
	 }

     /**
	  * 查询当前用户所有提前申请信息
	  */
     public function getAll($id = 0,$limit = 0,$sql){
		$order = $this->where("userId=".$id.$sql)->order('stime desc')->limit($limit)->select();
		return $order;
	 }
}