<?php
namespace Manage\Model;
class WxbusinessModel extends BaseModel {
	 /**
     * 显示当前用户所有开通信社区
     */
	public function loadBusiness($userId=0){
	 	$sql = "select * from __PREFIX__wxbusiness where userId=".$userId;
	 	$sql .=" order by id desc ";
		return $this->pageQuery($sql);
	}

	 /**
     * 根据ID查询社区信息
     */
	public function load($id=0){
		$rs = $this->where('id = '.$id.' and userId = '.session('SX_USERS.userId'))->find();
		return $rs;
	}

	 /**
     * 根据门店ID查询社区信息
     */
	public function loadStore($storeId=0){
		$rs = $this->where('storeId=%d',$storeId)->find();
		return $rs;
	}

	 /**
     * 根据经纬度查询社区信息
     */
	public function searchlocation($maxLat,$minLat,$maxLng,$minLng){
		$rs = $this->where('longitude <= '.$maxLng.' and longitude >= '.$minLng.' and latitude <= '.$maxLat.' and latitude >= '.$minLat.' and busStatus = 1')->order('longitude desc,latitude desc')->limit(15)->select();
		return $rs;
	}

	 /**
     * 修改微信社区开关状态
     */
	public function editisOpen(){
		$rd = array('status'=>-1);
		$data = array();
		$data["busStatus"] = I("status");
		$rs = $this->where("id = ".I('id')." and userId = ".session('SX_USERS.userId'))->save($data);
		if(false !== $rs){
			$rd['status']= 1;
		}
		return $rd;
	}

	 /**
     * 添加微信社区信息
     */
	public function openbusiness($data){
     	$rd = array('status'=>-1);
		$data['userId'] = session('SX_USERS.userId');
        $data['storeId'] = I("storeid");
        $data['longitude'] = I("longitude");
        $data['latitude'] = I("latitude");
        $data['storeId'] = I("storeid");
        $data['title'] = I("business_name");
        $data['describe'] = I("branch_name");
        $data['province'] = I("province");
        $data['city'] = I("city");
        $data['district'] = I("district");
        $data['avg_price'] = I("avg_price");
        $data['avg_price2'] = I("avg_price2");
        $data['categories'] = I("categories");
        $photo_url = I("photo_list");
        $data['photo_url'] = implode("#",$photo_url);
        $data['isOpen'] = 1;
        $data['busStatus'] = 1;
        $data['isEffect'] = 1;
		$rs = $this->add($data);
		if(false !== $rs){
			$rd['status']= 1;
		}
		return $rd;
	}

	 /**
     * 修改微信社区信息
     */
	public function updatebusiness($data){
     	$rd = array('status'=>-1);
        $data['storeId'] = I("storeid");
        $data['longitude'] = I("longitude");
        $data['latitude'] = I("latitude");
        $data['storeId'] = I("storeid");
        $data['title'] = I("business_name");
        $data['describe'] = I("branch_name");
        $data['province'] = I("province");
        $data['city'] = I("city");
        $data['district'] = I("district");
        $data['avg_price'] = I("avg_price");
        $data['avg_price2'] = I("avg_price2");
        $data['categories'] = I("categories");
        $photo_url = I("photo_list");
        $data['photo_url'] = implode("#",$photo_url);
		$rs = $this->where("id = %d",I("id",0))->save($data);
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