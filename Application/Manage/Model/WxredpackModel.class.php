<?php
namespace Manage\Model;
class WxredpackModel extends BaseModel {
	 /**
     * 读取红包配置
     */
	public function loadRedpack($userId=0){
		$rs = $this->where('userId=%d',$userId)->find();
		if(empty($rs)){
			$data["userId"] = $userId;
			$this->add($data);
		}
		return $rs;
	}

	 /**
     * 修改红包开关状态
     */
	public function editisOpen($userId=0){
		$rd = array('status'=>-1);
		$data = array();
		$data["packStatus"] = I("isopen");
		$data["isEffect"] = 0;
		$rs = $this->where("userId = %d",$userId)->save($data);

		if(false !== $rs){
			$rd['status']= 1;
		}
		return $rd;
	}

	 /**
     * 保存红包信息
     */
	public function saveRedpack($base_info){
		$rd = -1;
		$data = array();
		$userId = session('SX_USERS.userId');
		$data['sendName'] = $base_info['send_name'];
		$data['wishing'] = $base_info['title'];
		$data['actName'] = $base_info['sub_title'];
		$data['remark'] = $base_info['source'];
		$data['dateType'] = $base_info['date_type'];
		$data['dateStart'] = $base_info['datestart'];
		$data['dateEnd'] = $base_info['dateend'];
		$data['quantity'] = $base_info['quantity'];
		$data['getLlimit'] = $base_info['get_limit'];
		$data['leastMoney'] = $base_info['least_money'];
		$data['isShare'] = $base_info['can_share'];
		$data['redStoreId'] = $base_info['red_storeId'];
		$data['redRand'] = $base_info['red_rand'];
		$data['gdMoney'] = $base_info['gd_money'];
		$data['lMoney'] = $base_info['l_money'];
		$data['hMoney'] = $base_info['h_money'];
		$data['isEffect'] = 1;
		$rs = $this->where("userId = %d",$userId)->save($data);
		if(false !== $rs){
			$rd = 1;
		}
		return $rd;
	}
}