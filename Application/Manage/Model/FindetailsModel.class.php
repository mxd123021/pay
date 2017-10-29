<?php
namespace Manage\Model;
class FindetailsModel extends BaseModel {
     /**
	  * 查询当前用户财务明细
	  */
     public function getDetails($userId){
     	$userId = $userId?$userId:0;
     	$sql = "select * from __PREFIX__findetails where uid = ".$userId;
	 	$sql .=" order by time desc ";
		$order = $this->pageQuery($sql);
		return $order;
	 }

     /**
	  * 添加微信充值财务明细
	  */
     public function addWxDetails($rs){
     	$attach = explode(",",$rs['attach']);
		  $data['uid'] = $attach[0]; //商户ID
     	$data['type'] = 1; //微信充值
     	$data['goods_price'] = $rs['total_fee'] / 100;
     	$data['pn'] = 1; //1加 0减
      $data['real'] = $rs['total_fee'] / 100; //实际到账
     	$data['time'] = strtotime($rs['time_end']);
     	$data['note'] = empty($attach[5])?"未知":$attach[5];
     	$this->add($data);
	 }

     /**
       * 添加红包充值财务明细
       */
     public function addrpDetails($userId,$num){
          $userId = $userId?$userId:0;
          $relnum = $num-($num*0.006);
          $relnum = substr(sprintf("%.3f", $relnum), 0, -1); //不四舍五入 只保留两位
          $data['uid'] = $userId; //商户ID
          $data['type'] = 2; //红包扣款
          $data['goods_price'] = $num;
          $data['pn'] = 0; //1加 0减
          $data['real'] = $relnum."元 (".$num." x 0.6%)"; //实际到账 减去千分之六手续费
          $data['time'] = time();
          $data['note'] = "红包可用余额充值，微信扣除0.6%手续费";
          $this->add($data);
      }

     /**
       * 添加开通微信社区财务明细
       */
     public function addbusDetails($userId,$num){
          $userId = $userId?$userId:0;
          $data['uid'] = $userId; //商户ID
          $data['type'] = 3; //微信社区
          $data['goods_price'] = $num;
          $data['pn'] = 0; //1加 0减
          $data['real'] = ""; //实际到账 减去千分之六手续费
          $data['time'] = time();
          $data['note'] = "开通微信社区";
          $this->add($data);
      }
}