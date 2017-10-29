<?php
namespace Manage\Model;
class CashierCategoryModel extends BaseModel {
     /**
	  * 获取所有类目信息
	  */
     public function getCategory($id){
     	$id = $id?$id:0;
		$district = $this->where("fid=".$id)->select();
		return $district;
	 }

     /**
	  * 根据中文查询类目信息
	  */
     public function getName($key){
		$district = $this->where("name like '".$key."'")->find();
		return $district;
	 }
}