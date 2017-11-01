<?php
namespace SX\Model;
use Think\Model;
class BaseModel extends Model {
    /**
     * 用来处理内容中为空的判断
     */
	public function checkEmpty($data,$isDie = false){
	    foreach ($data as $key=>$v){
			if(trim($v)==''){
				if($isDie)die("{'status':-1,'key':'$key'}");
				return false;
			}
		}
		return true;
	}

	public function updateItemById($id,$update){
		if(method_exists($this,'filterOnlyData')){
			$update = $this->filterOnlyData($update);
		}
		$update['updated_at'] = date('Y-m-d H-i-s');
		return (bool)$this->where(sprintf('id=%d',$id))->save($update);
	}
}