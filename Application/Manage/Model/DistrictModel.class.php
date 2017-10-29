<?php
namespace Manage\Model;
class DistrictModel extends BaseModel {
     /**
	  * 获取所有省信息
	  */
     public function getDistrict($id){
     	$id = $id?$id:0;
		$district = $this->where("fid=".$id)->select();
		return $district;
	 }

     /**
	  * 根据中文查询城市信息
	  */
     public function getFullname($key){
		$district = $this->where("fullname like ".$key)->find();
		return $district;
	 }
}