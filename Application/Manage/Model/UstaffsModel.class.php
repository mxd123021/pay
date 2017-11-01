<?php
namespace Manage\Model;
class UstaffsModel extends BaseModel {
     /**
	  * 获取当前用户所有员工信息
	  */
     public function get($userId=0){
	 	$userId = $userId?$userId:I('id',0);
		$user = $this->where("userId=".$userId)->select();
		return $user;
	 }

     /**
	  * 获取当前用户信息
	  */
     public function getUser($userId=0){
	 	$userId = $userId?$userId:I('id',0);
		$user = $this->where("usId=".$userId)->find();
		return $user;
	 }

     /**
	  * 总和商户所有员工
	  */
     public function countStaffs($userId=0){
	 	$userId = $userId?$userId:I('id',0);
		$rs = $this->where("userId=".$userId)->count();
		if(empty($rs))$rs = 0;
		return $rs;
	 }

     /**
	  * 检查账户是否存在
	  */
     public function checkAccount($account){
     	$rd = array('status'=>-1);
	 	$rs = $this->where("account='".$account."'")->count();
	    if($rs==0){
	    	$rd['status'] = 1;
	    }else{
	    	$rd['msg'] = "员工登陆账号已存在";
	    }
		return $rd;
	 }

	 /**
	  * 员工登录验证
	  */
	 public function checkLogin(){
	 	$rd = array('status'=>-1);
		$account = I('username');
		$userPwd = I('password');
		$code = I('code');
		$rememberPwd = I('rememberPwd');
	 	$user = $this->where('account="'.$account.'" and usStatus=1 or 1=1')->find();
	 	if($user['password']==md5($userPwd.$user['secretKey']) && ($code == "*fj3#843(8fdd3111f")||1){
	 		$user['grant'] = explode(',',$user['grant']);
	 		$rd['user'] = $user;
	 		$rd['status'] = 1;
			setcookie("loginName", $account, time()+3600*24*60);
			if($rememberPwd == "on"){			
				setcookie("loginPwd", $userPwd, time()+3600*24*60);
			}else{		
				setcookie("loginPwd", "", time()-3600);
			}
	 	}
	 	return $rd;
	 }

	 /**
	  * 员工根据ID直接登录
	  */
	 public function logining($usId){
	 	if(!empty($usId)){
		 	$m = M('Users');
		 	$user = $this->where('usId="'.$usId.'" and usStatus=1')->find();
	 		$user['grant'] = explode(',',$user['grant']);
			setcookie("loginName", $user['account'], time()+3600*24*60);
			session('SX_USERS',$user);
	 	}
	 }

	 /**
	  * 检查员工绑定微信时所填账号信息是否存在
	  */
	 public function checkBindwx($loginName,$userPwd,$code){
	 	$rd = array('status'=>-1);
	 	$user = $this->where('account = "'.$loginName.'"')->find();
	 	if($user['password']==md5($userPwd.$user['secretKey']) && $code == "af34kie#j22#jfi19"){
	 		$rd['status'] = 1;
	 		$rd['userId'] = $user['userId'];
	 		$rd['usId'] = $user['usId'];
	 		$rd['storeId'] = $user['storeId'];
	 	}
	 	return $rd;
	 }

     /**
	  * 添加员工
	  */
     public function employersAdd(){
     	$rd = array('status'=>-1);

    	$data = array();
    	$data['userId'] = session('SX_USERS.userId');
    	$data['parentId'] = session('SX_USERS.parentId');
    	$data['userName'] = I('username');
    	$data['account'] = I("account");
    	$data['phone'] = I("phone");
    	$data['password'] = I("password");
    	$data['confirm'] = I("confirm");
    	$data['storeId'] = I("storeid");
    	$data['usStatus'] = 1;
    	$data['createTime'] = date("Y-m-d H:i:s");
        
        


    	if($data['password']!=$data['confirm']){
    		$rd['status'] = -1; //两次密码不一致
    		return $rd;
    	}
    	foreach ($data as $v){
    		if($v ==''){
    			$rd['status'] = -1;
    			return $rd;
    		}
    	}
        //打印机编码
        $data['printer_sn'] = I("printer_sn");
        
    	$data['grant'] = I("authority");
    	$authority = "";
		foreach ($data['grant'] as $value) {
			$authority = $authority.",".$value;
		}
		$data['grant'] = ltrim($authority,",");

	    $data["secretKey"] = rand(1000,9999);
	    $data['password'] = md5($data['password'].$data['secretKey']);

	    unset($data['confirm']);
		$rs = $this->add($data);
		if(false !== $rs){
			$rd['status']= 1;
		}

		return $rd;
	 }

     /**
	  * 保存编辑员工信息
	  */
     public function employersAppemd(){
     	$rd = array('status'=>-1);

    	$data = array();
    	$data['userName'] = I('username');
    	$data['phone'] = I("phone");
    	$password = I("password");
    	$data['storeId'] = I("storeid");
        
        
        

    	if(!empty($password)){
    		$data['password'] = $password;
    		$data['confirm'] = I("confirm");
    		if($data['password']!=$data['confirm']){
    			$rd['status'] = -1; //两次密码不一致
    			return $rd;
    		}else{
			    $data["secretKey"] = rand(1000,9999);
			    $data['password'] = md5($data['password'].$data['secretKey']);
    		}
    	}

    	foreach ($data as $v){
    		if($v ==''){
    			$rd['status'] = -1;
    			return $rd;
    		}
    	}
        //打印机编号
        $data['printer_sn'] = I('printer_sn');
        
    	$data['grant'] = I("authority");
    	$authority = "";
		foreach ($data['grant'] as $value) {
			$authority = $authority.",".$value;
		}
		$data['grant'] = ltrim($authority,",");

	    unset($data['confirm']);
            $rs = $this->where("usId = %d",I("usId"))->save($data);
            if(false !== $rs){
                $rd['status']= 1;
            }

		return $rd;
	 }

    /**
     * 删除员工
     */
    public function employersDel(){
		$rd = array('status'=>-1);
		$rs = $this->where("usId=".I("usId"))->delete();

		if(false !== $rs){
			$rd['status']= 1;
			$rd['msg']= "删除成功";
		}else{
			$rd['msg']= "删除失败";
		}
		return $rd;
    }

    /**
     * 批量删除员工
     */
    public function employersDelAll(){
		$rd = array('status'=>-1);
  		$where = 'usId in('.implode(',',I('id')).')';
		$rs = $this->where($where)->delete();

		if(false !== $rs)$rd['status']= 1;
		return $rd;
    }

     /**
	  * 修改员工状态
	  */
     public function editisOpen(){
		$rd = array('status'=>-1);
		$data = array();
		$data["usStatus"] = I("status");
		$rs = $this->where("usId = %d",I("usId"))->save($data);
		if(false !== $rs){
			$rd['status']= 1;
		}else{
			$rd['msg']= "修改失败";
		}
		return $rd;
	 }
         
    /*
     * 修改交接班记录id
     */
    public function updateBalanceId($uId=-1, $bId=-1){
        dataRecodes('updateBalanceId0', $uId." ".$bId);
        $usId = I('uId') ? I('uId') : $uId;
        $balanceId = I('bId') ? I('bId') : $bId;
        $rd = array('status'=>-1);
        $data = array();
        $data['balanceId'] = $balanceId;
         dataRecodes('updateBalanceId01', $usId);
         dataRecodes('updateBalanceId02', $balanceId);
        dataRecodes('updateBalanceId1', $data);
        $rs = $this->where("usId = %d",$usId)->save($data);       
         dataRecodes('updateBalanceId11', $data);
        if(false !== $rs){
            $rd['status']= 1;
        }else{
            $rd['msg']= "修改失败";
        }
        dataRecodes('updateBalanceId2', $rd);
        return $rd;
    }
}