<?php
namespace Manage\Model;
class GdDistrictModel extends BaseModel {
     /**
	  * 获取光大省级地区信息
	  */
     public function getProvince(){
		$district = $this->where("level = 2")->select();
		return $district;
	 }

     /**
	  * 获取光大当前城市信息
	  */
     public function getCity($fid){
		$district = $this->where("fid like '".$fid."'")->select();
		return $district;
	 }
}