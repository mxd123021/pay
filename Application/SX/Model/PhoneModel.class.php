<?php
namespace SX\Model;
class PhoneModel extends BaseModel {
    /**
     * 获得电话预约信息
     */
    public function getAll(){
        $sql = "select * from __PREFIX__phone";
        $sql .=" order by id desc ";
        $order = $this->pageQuery($sql);
        return $order;
    }

    /**
     * 添加电话预约信息
     */
    public function addPhone(){
    	$data = array();
    	$data['phone'] = I("phone");
    	$data['type'] = I("type");

        if(!empty($data['phone'])){
		  $rs = $this->add($data);
        }
        return $rs;
    }
}