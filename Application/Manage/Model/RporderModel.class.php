<?php
namespace Manage\Model;
class RporderModel extends BaseModel {
     /**
	  * 查询当前用户所有红包领取信息
	  */
     public function getAll($userId = 0,$limit = 0){
		$order = $this->where("uid=".$userId)->order('send_time desc')->limit($limit)->select();
		return $order;
	 }

     /**
	  * 添加微信红包订单信息
	  */
     public function addWxRpOrder($rs){
		$rd = array('status'=>-1);
		if(!empty($rs)){
			$data = array();
			$data['mch_billno'] = $rs['mch_billno'];
			$data['uid'] = $rs['userId']; //商户ID
			$data['eid'] = empty($rs['eid'])?0:$rs['eid']; //员工添加则有员工ID
			$data['storeid'] = empty($rs['storeId'])?0:$rs['storeId']; //有门店添加则有门店ID
			$data['mch_id'] = $rs['mch_id']; //商户ID
			if($rs['sub_mch_id']){
				$data['sub_mch_id'] = $rs['sub_mch_id']; //子商户ID
			}
			$data['total_amount'] = $rs['total_amount']/100; //价格
			$data['send_time'] = time(); //发送时间
			$data['wxappid'] = $rs['wxappid'];
			$data['send_listid'] = $rs['send_listid'];
			$data['mchtype'] = empty($rs['mchtype'])?0:$rs['mchtype']; //0普通商户 1特约商户 2平台代收
			$data['re_openid'] = $rs['re_openid'];

			$rs = $this->add($data);
			if(false !== $rs){
				$rd['status']= 1;
			}
		}
		return $rd;
	 }
}