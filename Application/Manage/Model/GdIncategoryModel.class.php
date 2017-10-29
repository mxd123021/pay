<?php
namespace Manage\Model;
class GdIncategoryModel extends BaseModel {
     /**
	  * 根据code获取行业类别信息
	  */
     public function getId($code){
		$category = $this->where("code =".$code)->find();
		return $category;
	 }

     /**
	  * 根据关键词获取行业类别信息
	  */
     public function get($key){
		$category = $this->where("name like '%".$key."%'")->select();
		return $category;
	 }
}