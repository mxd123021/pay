<?php
namespace Manage\Model;
class GdBankModel extends BaseModel {
     /**
	  * 获取银行信息
	  */
     public function get(){
		$bank = $this->select();
		return $bank;
	 }
}