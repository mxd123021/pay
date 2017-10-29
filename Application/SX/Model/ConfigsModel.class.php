<?php
namespace SX\Model;
class ConfigsModel extends BaseModel {
	 /**
     * 读取网站配置
     */
	public function loadConfigs(){
		$rs = $this->where('configId=%d',1)->find();
		return $rs;
	}

	 /**
     * 保存网站信息
     */
	public function saveSiteinfo(){
		$rd = array('status'=>-1);
		$data = array();
		$data["siteName"] = I("sn");
		$data["siteUrl"] = I("su");
		$data["seoTitle"] = I("seot");
		$data["seoKeyword"] = I("seok");
		$data["seoDescription"] = I("seod");
		$rs = $this->where("configId = %d",1)->save($data);
		if(false !== $rs){
			$rd['status']= 1;
		}
		return $rd;
	}

	 /**
     * 保存系统的微信token信息
     */
	public function saveToken($token){
		$rd = array('status'=>-1);
		$rs = $this->where('configId=%d',1)->save($token);
		if(false !== $rs){
			$rd['status']= 1;
		}
		return $rd;
	}

	 /**
     * 保存支付信息
     */
	public function savePayinfo(){
		$rd = array('status'=>-1);
		$data = I("data",'','urldecode');
                $data['wx_appId'] = $data['gd_appId'];
                $data['wx_appSecret'] = $data['gd_appSecret'];
                $data['ds_wx_appId'] = $data['gd_appId'];
                $data['ds_wx_appSecret'] = $data['gd_appSecret'];
		$rs = $this->where("configId = %d",1)->save($data);
		if(false !== $rs){
			$rd['status']= 1;
		}
		return $rd;
	}

	/**
     * 保存微信配置信息
     */
	public function saveWxconfig(){
		$rd = array('status'=>-1);
		$data = I("data",'','urldecode');
		$rs = $this->where("configId = %d",1)->save($data);
		if(false !== $rs){
			$rd['status']= 1;
		}
		return $rd;
	}

	 /**
     * 保存支付模板
     */
	public function saveTempzf(){
		$rd = array('status'=>-1);
		$data = I("data",'','urldecode');
		$paytemp['paytemp'] = json_encode($data);
		$rs = $this->where("configId = %d",1)->save($paytemp);
		if(false !== $rs){
			$rd['status']= 1;
		}
		return $rd;
	}

	 /**
     * 保存绑定模板
     */
	public function saveTempbind(){
		$rd = array('status'=>-1);
		$data = I("data",'','urldecode');
		$bindtemp['bindtemp'] = json_encode($data);
		$rs = $this->where("configId = %d",1)->save($bindtemp);
		if(false !== $rs){
			$rd['status']= 1;
		}
		return $rd;
	}
        
        /**
     * 保存绑定模板
     */
	public function saveTempbalance(){
		$rd = array('status'=>-1);
		$data = I("data",'','urldecode');
		$balancetemp['balancetemp'] = json_encode($data);
		$rs = $this->where("configId = %d",1)->save($balancetemp);
		if(false !== $rs){
			$rd['status']= 1;
		}
		return $rd;
	}

	 /**
     * 修改网站开关状态
     */
	public function editisOpen(){
		$rd = array('status'=>-1);
		$data = array();
		$data["isOpen"] = I("isopen");
		$rs = $this->where("configId = %d",1)->save($data);
		if(false !== $rs){
			$rd['status']= 1;
		}
		return $rd;
	}

}