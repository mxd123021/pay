<?php
namespace Manage\Model;
class BindweixinModel extends BaseModel {
     /**
	  * 获取微信绑定信息
	  */
     public function get($userId=0,$sql=1){
	 	$userId = $userId?$userId:I('id',0);
		$user = $this->where("userId=".$userId." and ".$sql)->find();
		return $user;
	 }

     /**
	  * 获取微信当前用户所有绑定信息
	  */
     public function getuserAll($userId=0,$sql=1){
	 	$userId = $userId?$userId:I('id',0);
		$user = $this->where("userId=".$userId." and ".$sql)->select();
		return $user;
	 }

     /**
	  * 获取微信绑定信息
	  */
     public function getopenid($openid){
		$user = $this->where('openid = "'.$openid.'"')->find();
		return $user;
	 }

     /**
	  * 检查openid是否绑定
	  */
     public function checkBindopenid($openid){
	 	$rd = -1;
	 	$user = $this->where('openid = "'.$openid.'"')->count();
	 	if($user==0){
	 		$rd = 1;
	 	}
	 	return $rd;
	 }

     /**
	  * 检查当前账号是否绑定过
	  */
     public function checkBinduser($userId){
	 	$rd = -1;
	 	$user = $this->where('userId = '.$userId.' and usId = 0 and storeId = 0')->count();
	 	if($user==0){
	 		$rd = 1;
	 	}
	 	return $rd;
	 }

     /**
	  * 检查当前门店的员工账号是否绑定过
	  */
     public function checkusBinduser($data){
	 	$rd = -1;
	 	$user = $this->where('userId = '.$data['userId'].' and usId = '.$data['usId'].' and storeId = '.$data['storeId'])->count();
	 	if($user==0){
	 		$rd = 1;
	 	}
	 	return $rd;
	 }

    /**
     * 删除员工时删除该绑定微信信息
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
     * 批量删除员工绑定微信信息
     */
    public function employersDelAll(){
		$rd = array('status'=>-1);
  		$where = 'usId in('.implode(',',I('id')).')';
		$rs = $this->where($where)->delete();

		if(false !== $rs)$rd['status']= 1;
		return $rd;
    }

     /**
	  * 保存商户的微信绑定信息
	  */
     public function saveBindwx($rs,$data,$type){
     	$rd = -1;
     	$wxinfo['userId'] = $rs['userId'];
     	if($data['type']==1){
			$wxinfo['usId'] = 0;
     		$wxinfo['storeId'] = 0;
     	}else{
			$wxinfo['usId'] = $rs['usId'];
     		$wxinfo['storeId'] = $rs['storeId'];
     	}
     	$wxinfo['openid'] =  $data['openid'];
     	$wxinfo['nickname'] =  $data['nickname'];
     	$wxinfo['sex'] =  $data['sex'];
     	$wxinfo['language'] =  $data['language'];
     	$wxinfo['city'] =  $data['city'];
     	$wxinfo['province'] =  $data['province'];
     	$wxinfo['country'] =  $data['country'];
     	$wxinfo['headimgurl'] =  $data['headimgurl'];
     	$wxinfo['subscribe_time'] =  $data['subscribe_time'];
     	$wxinfo['remark'] =  $data['remark'];
     	$wxinfo['groupid'] =  $data['groupid'];
     	$wxinfo['type'] =  $data['type'];
     	if($type==1){
     		$rs = $this->save($wxinfo);
     	}else{
     		$rs = $this->add($wxinfo);
     	}
		
		if(false !== $rs){
			$rd = 1;
		}
		return $rd;
	 }

     /**
	  * 修改微信收银员通知接收状态
	  */
     public function editisSend(){
		$rd = array('status'=>-1);
		$data = array();
		$data["isSend"] = I("status");
		$rs = $this->where("id = %d",I("id"))->save($data);
		if(false !== $rs){
			$rd['status']= 1;
		}else{
			$rd['msg']= "修改失败";
		}
		return $rd;
	 }

     /**
	  * 修改微信收银员通知接收状态
	  */
     public function removeBind(){
		$rd = array('status'=>-1);
		$rs = $this->where("id = %d",I("id"))->delete();
		if(false !== $rs){
			$rd['status']= 1;
		}else{
			$rd['msg']= "修改失败";
		}
		return $rd;
	 }
         
    /**
    * 获取微信绑定信息
    */
     public function getUserById($id=0){
        $id = $id?$id:I("id");
        $user = $this->where("id = %d",$id)->find();
        return $user;
    }
}