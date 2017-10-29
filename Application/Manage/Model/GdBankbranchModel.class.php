<?php
namespace Manage\Model;
class GdBankbranchModel extends BaseModel {
     /**
	  * 搜索银行联行号信息
	  */
     public function search(){
     	$key = I('key');
     	$gdcity = I('gdcity');
     	$gdcity2 = I('gdcity2');
     	$khbank = I('khbank');
		$bank = $this->where("name like '%".$key."%' and province like '%".$gdcity."%' and city like '%".$gdcity2."%' and bank like '%".$khbank."%'")->limit(4)->select();
		return $bank;
	 }
}