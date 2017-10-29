<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Manage\Model;
class PrinterModel extends BaseModel {
     /**
	  * 获取当前用户的所有打印机
	  */
     public function get($userId=0){
	 	$userId = $userId?$userId:I('id',0);
		$printers = $this->where("userId=".$userId)->select();
		return $printers;
	 }
         
         /*
          * 根据sn获取打印机
          */
     public function getPrinterBySN($printer_sn=0){
         $printer_sn = $printer_sn?$printer_sn:I('printer_sn', 0);
         $printer = $this->where("printer_sn=".$printer_sn)->find();
         return $printer;
     }
     
     /*
      * 根据id获取打印机
      */
    public function getPrinterById($printer_id){
        $printer_id = $printer_id ? $printer_id : I('printer_id', 0);
        $printer = $this->where('printer_id='.$printer_id)->find();
        return $printer;
    }


    /*
     * 增加打印机
     */
    public function addPrinter(){
        $rd = array('status'=>-1);

    	$data = array();
        $data['userId'] = session('SX_USERS.userId');
        $data['printer_sn'] = I('printer_sn');
        $data['printer_key'] = I('printer_key');
        $data['printer_name'] = I('printer_name');
        $data['printer_tip'] = I('printer_tip');
        $data['printer_qrcode'] = I('printer_qrcode');
        $data['printer_telephone'] = I('printer_telephone');
        $data['printer_ticketnum'] = I('printer_ticketnum');
        $data['printer_version'] = I('printer_version');
        $rs = $this->add($data);
        if(false !== $rs){
            $rd['status'] = 1;
        }
        
        return $rd;
     }
     
     /*
      * 保存打印机
      */
     public function savePrinter(){
        $rd = array('status'=>-1);
        
        $printer_id = I('printer_id');
        $data = array();
        $data['printer_sn'] = I('printer_sn');
        $data['printer_key'] = I('printer_key');
        $data['printer_name'] = I('printer_name');
        $data['printer_tip'] = I('printer_tip');
        $data['printer_qrcode'] = I('printer_qrcode');
        $data['printer_telephone'] = I('printer_telephone');
        $data['printer_ticketnum'] = I('printer_ticketnum');
        $data['printer_version'] = I('printer_version');
        $rs = $this->where('printer_id='.$printer_id)->save($data);
        if(false !== $rs){
            $rd['status'] = 1;
        }
        return $rd;
    }


     /**
	* 获取当前用户所有打印机分页信息
	  */
     public function queryByPage($userId=0){
	$userId = $userId?$userId:I('id',0);
	$sql = "select * from __PREFIX__printer where userId=".$userId;
	$sql .=" order by printer_id desc ";
	return $this->pageQuery($sql);
    }
    /**
    * 删除打印机信息
    */     
    public function delPrinter($printer_id=0){
        $printer_id = $printer_id?$printer_id:I('printer_id',0);
	$rd = array('status'=>-1);
	$rs = $this->where("printer_id=".$printer_id)->delete();
	if(false !== $rs){
            $rd['status']= 1;
	}
	return $rd;	 
    }
         
}
