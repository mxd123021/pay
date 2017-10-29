<?php
namespace SX\Model;
class FinancialModel extends BaseModel {
     /**
	  * 管理员充值金额
	  */
     public function addMoney($userId,$num){
      $rd = array('status'=>-1);
     	$userId = $userId?$userId:0;
      $data['curmoney'] = array('exp','curmoney+'.$num);
      $data['totalmoney'] = array('exp','totalmoney+'.$num);
      $money = $this->where("userId = %d",$userId)->save($data);
     	if($money != false){
			 $rd['status']= 1;
		  }
      return $rd;
	 }
}