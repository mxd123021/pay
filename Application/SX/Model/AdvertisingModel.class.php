<?php
namespace SX\Model;
class AdvertisingModel extends BaseModel {
    /**
     * 获得广告信息
     */
    public function get(){
		$rs = $this->where("id=1")->find();
		return $rs;
    }

    /**
     * 保存广告信息
     */
    public function advertising(){
     	$rd = array('status'=>-1);

    	$data = array();
    	$data['area'] = I("area");
    	$data['areaId'] = I("areaid");
        $data['url'] = I("url");
    	$data['status'] = I("status");
        $data['title'] = I("title");
        $data['content'] = I("content");
        $data['adtype'] = I("adtype");
		$photo_url = I("photo_list");
		$data['photo_url'] = implode("#",$photo_url);
		$rs = $this->where("id=1")->save($data);
		if(false !== $rs){
			$rd['status']= 1;
		}
		return $rd;
    }
}