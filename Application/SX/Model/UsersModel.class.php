<?php
namespace SX\Model;
class UsersModel extends BaseModel {
     /**
	  * 获取用户信息
	  */
     public function get($userId=0){
	 	$userId = $userId?$userId:I('id',0);
		$user = $this->where("userId=".$userId)->find();
		return $user;
	 }
	
     /**
	  * 获取实名审核信息
	  * @status 2:审核中 3：审核失败
	  */
     public function getAudit($status){
		$user = $this->where("userAudit=".$status)->select();
		return $user;
	 }

	 /**
     * 保存费率信息
     */
	public function saveRate($id=0){
		$rd = array('status'=>-1);
		$data = array();
		$data["gd_rate"] = json_encode(I('rate'));
		if ($this->checkEmpty($data,true)) {
			$rs = $this->where("userId = %d",$id)->save($data);
			if(false !== $rs){
				$rd['status']= 1;
			}
		}	
		return $rd;

	}

	 /**
     * 设置商户审核状态和光大商户号
     */
	public function setUseraudit($userId=0,$num,$reson,$step,$mchid){
		$rd = array('status'=>-1);
		$data = array();
		$data['userAudit'] = $num; //0 没填写不审核  1 审核通过  2 审核中  3 审核失败
		if(!empty($step)){ //用户审核步骤
			$data['userStep'] = $step;
		}
		if(!empty($mchid)){
			$data['gd_mchId'] = $mchid;
		}
		if(!empty($reson)){ //理由不为空，则写入拒绝理由
			$data['reson'] = $reson;
		}
		$rs = $this->where("userId = %d",$userId)->save($data);
		if(false !== $rs){
			$rd['status']= 1;
		}
		return $rd;
	}

	 /**
	  * 分页列表搜索
	  */
     public function queryByPage(){
        $m = M('staffs');
        $username = I('username');
	 	$sql = "select * from __PREFIX__users";
	 	if(!empty($username)){
	 		$sql .=" where username like '%".$username."%'";
	 	}
	 	$sql .=" order by userId asc ";
		return $m->pageQuery($sql);
	 }

	/**
	 * 修改用户名
	 */
	public function mdfyName($id = ''){
		$id = ($id == '') ? I('uid') : $id;
		$rd = array('status'=>-1);
		$data = array();
		$data["userName"] = I("un");
		if ($this->checkEmpty($data,true)) {
			$rs = $this->where("userId = %d",$id)->save($data);
			if(false !== $rs){
				$rd['status']= 1;
			}
		}	
		return $rd;
	}

	/**
	 * 设置特约商户
	 */
	public function setIssp($id = ''){
		$id = ($id == '') ? I('uid') : $id;
		$rd = array('status'=>-1);
		$data = array();
		$data["wx_issp"] = I("isselect");
		if ($this->checkEmpty($data,true)) {
			$rs = $this->where("userId = %d",$id)->save($data);
			if(false !== $rs){
				$rd['status']= 1;
			}
		}	
		return $rd;
	}

	/**
	 * 设置受理商户
	 */
	public function setType($id = ''){
		$id = ($id == '') ? I('uid') : $id;
		$rd = array('status'=>-1);
		$data = array();
		$data["userType"] = I("isselect");
		if ($this->checkEmpty($data,true)) {
			$rs = $this->where("userId = %d",$id)->save($data);
			if(false !== $rs){
				$rd['status']= 1;
			}
		}	
		return $rd;
	}

     /**
	  * 查询所有用户总数
	  */
     public function countUsers(){
		$rs = $this->count();
		if(empty($rs))$rs = 0;
		return $rs;
	 }
}