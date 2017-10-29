<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Manage\Model;
class BalanceManageModel extends BaseModel {
         
    
    /**
	  * 查询当前用户所有订单信息
	  */
     public function getAll($id = 0,$limit = 0,$model=1,$sql){
     	if($model==1){//根据用户ID
            $balances = $this->where("usId=".$id.$sql)->order('ebalanceTime desc')->limit($limit)->select();
        }else if($model==2){//根据员工ID
            $balances = $this->where("storeId=".$id.$sql)->order('ebalanceTime desc')->limit($limit)->select();		
	}
        return $balances;
    }
     /*
      * 根据门店和员工id获取结算记录
      */
    public function getBalanceInfoById($storeId, $usId){        
        $balanceInfo = $this->where('storeId='.$storeId.' AND usId='.$usId)->find();
        return $balanceInfo;
    }
    /*
     * 根据记录id获取结算记录
     */
    public function getBalanceById($id=-1){
        $id = $id?$id:I("id");
        $balanceInfo = $this->where("id = %d",$id)->find();        
        return $balanceInfo;
    }
    /*
      * 保存结算信息
      */
     public function saveBalanceInfo($rs){
        $rd = array('status'=>-1);
        
        $id = $rs['id'];
        $data = array();
        $data['storeId'] = $rs['storeId'];
        $data['usId'] = $rs['usId'];
        $data['sbalanceTime'] =$rs['sbalanceTime'];
        $data['ebalanceTime'] = $rs['ebalanceTime'];
        $data['totalMoney'] = $rs['totalMoney'];
        $data['totalNum'] = $rs['totalNum'];
        $data['weixinMoney'] = $rs['weixinMoney'];
        $data['weixinNum'] = $rs['weixinNum'];
        $data['aliMoney'] = $rs['aliMoney'];
        $data['aliNum'] = $rs['aliNum'];
        $rs = $this->where('id='.$id)->save($data);
        if(false !== $rs){
            $rd['status'] = 1;
        }
        return $rd;
    }
    
    /*
      * 增加结算信息
      */
     public function addBalanceInfo($rs){
        $rd = array('status'=>-1);
                
        $data = array();
        $data['storeId'] = $rs['storeId'];
        $data['usId'] = $rs['usId'];
        $data['sbalanceTime'] =$rs['sbalanceTime'];
        $data['ebalanceTime'] = $rs['ebalanceTime'];
        $data['totalMoney'] = $rs['totalMoney'];
        $data['totalNum'] = $rs['totalNum'];
        $data['weixinMoney'] = $rs['weixinMoney'];
        $data['weixinNum'] = $rs['weixinNum'];
        $data['aliMoney'] = $rs['aliMoney'];
        $data['aliNum'] = $rs['aliNum'];
        $rs = $this->add($data);
        if(false !== $rs){
            $rd['status'] = 1;
            $rd['id'] = $rs;
        }
        return $rd;
    }


     /**
	* 获取
	  */
     public function queryByPage($Id=0){
	$Id = $Id?$Id:I('id',0);
	$sql = "select * from __PREFIX__balancemanage where id=".$Id;
	$sql .=" order by id desc ";
	return $this->pageQuery($sql);
    }
        
   
         
}
