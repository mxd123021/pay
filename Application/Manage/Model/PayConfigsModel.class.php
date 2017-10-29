<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Manage\Model;
class PayConfigsModel extends BaseModel {
         
    
    public function getPayConfig($id=0){
        $id = I('id') ? I('id') : $id;
        $payConfig = $this->where('id='.$id)->find();
        return $payConfig;
    }
    /*
    * 获取当前用户的所有收银账户
    */
   public function getPayConfigs($id=0){
       $userId = I('userId') ? I('userId'):id;
       $payConfigs = $this->where('userId='.$userId)->select();
       return $payConfigs;
   }
   
   /*
    * 增加收银账户
    */
   public function addPayConfig($uid=0){
       $res = array(
           'status'=>-1
       );
       $userId = I('userId') ? I('userId') : $uid;
       $data = array(
           'userId'=>$userId,
           'wx_appId'=>I('appId'),
           'wx_appSecret'=>I('appSecret'),
           'wx_mchId'=>I('mchId'),
           'wx_key'=>I('key')
       );
       if($this->add($data)){
           $res['status'] = 1;
       }
       return $res;
   }
   
   /*
    * 删除收银账号
    */
   public function delPayConfig($id=0){
       $res = array(
           'status'=>-1
       );
       $id = I('id') ? I('id') : $id;
       $rs = $this->where('id='.$id)->delete();
       if($rs){
           $res['status'] = 1;
       }
       return $res;
   }
   /*
    * 保持收银账户信息
    */
   public function savePayConfig($id=0){
        $res = array(
           'status'=>-1
       );
       $id = I('id') ? I('id') : $id;
       $data = array(
           'wx_appId'=>I('appId'),
           'wx_appSecret'=>I('appSecret'),
           'wx_mchId'=>I('mchId'),
           'wx_key'=>I('key')
       );
       $rs = $this->where('id='.$id)->save($data);
       if($rs){
           $res['status'] = 1;
       }
       return $res;
   }


   /**
    * 获取当前用户所有打印机分页信息
      */
     public function queryByPage($userId=0){
	$userId = $userId?$userId:I('id',0);
	$sql = "select * from __PREFIX__pay_configs where userId=".$userId;
	$sql .=" order by id desc ";
	return $this->pageQuery($sql);
    }
         
}
