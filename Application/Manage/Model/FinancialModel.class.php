<?php
namespace Manage\Model;
class FinancialModel extends BaseModel {
     /**
	  * 查询当前金额
	  */
  public function getCurmoney($userId){
		$userId = $userId?$userId:0;
		$money = $this->where("userId=".$userId)->find();
		if(empty($money)){
			$data["userId"] = $userId;
			$this->add($data);
		}
		return $money;
	}

     /**
	  * 微信充值金额
	  */
     public function recharge($rs){
     	$attach = explode(",",$rs['attach']);
		  $userId = $attach[0]; //商户ID
     	$num = $rs['total_fee'] / 100;
     	$data['curmoney'] = array('exp','curmoney+'.$num);
     	$data['totalmoney'] = array('exp','totalmoney+'.$num);
     	$money = $this->where("userId = %d",$userId)->save($data);
	 }

     /**
    * 申请提现成功后扣除可申请金额
    */
     public function reWithdraw($userId,$money){
      $data['dscurmoney'] = array('exp','dscurmoney-'.$money['txprice']);
      $data['dstxmoney'] = array('exp','dstxmoney+'.$money['price']);
      $money = $this->where("userId = %d",$userId)->save($data);
   }

     /**
    * 代收确定打款后扣除提现金额
    */
     public function Withdraw($userId,$money){
      $data['dstxmoney'] = array('exp','dstxmoney-'.$money);
      $money = $this->where("userId = %d",$userId)->save($data);
   }

     /**
       * 代收用户增加代收金额
       */
     public function dsrecharge($rs){
          $attach = explode(",",$rs['attach']);
          $userId = $attach[0]; //商户ID
          $num = $rs['total_fee'] / 100;
          $data['dsshmoney'] = array('exp','dsshmoney+'.$num);
          $data['dstotalmoney'] = array('exp','dstotalmoney+'.$num);
          $money = $this->where("userId = %d",$userId)->save($data);
      }

     /**
       * 代收用户退款减少待划账金额
       */
     public function dscutrecharge($userId,$num){
          $data['dsshmoney'] = array('exp','dsshmoney-'.$num);
          $data['dstotalmoney'] = array('exp','dstotalmoney-'.$num);
          $money = $this->where("userId = %d",$userId)->save($data);
      }

     /**
       * 开通微信社区
       */
     public function openbusiness($userId,$num){
          $userId = $userId?$userId:0;
          $data['curmoney'] = array('exp','curmoney-'.$num);
          $money = $this->where("userId = %d",$userId)->save($data);
          if($money == false){
               return -1;
          }else{
               return 1;
          }
      }

     /**
	  * 余额充值红包金额
	  */
     public function addRpmoney($userId,$num){
     	$userId = $userId?$userId:0;
     	$relnum = $num-($num*0.006); //实际充值金额 千分之6手续费 
     	$relnum = substr(sprintf("%.3f", $relnum), 0, -1); //不四舍五入 只保留两位小数
     	$data['curmoney'] = array('exp','curmoney-'.$num);
     	$data['rpmoney'] = array('exp','rpmoney+'.$relnum);
     	$data['rptotalmoney'] = array('exp','rptotalmoney+'.$relnum);
     	$money = $this->where("userId = %d",$userId)->save($data);
     	if($money == false){
			return -1;
		}else{
			return 1;
		}
	 }

     /**
	  * （红包）消费失败返还金额
	  */
     public function returnMoney($userId,$num){
     	$userId = $userId?$userId:0;
     	$num = $num / 100;
     	$data['rpmoney'] = array('exp','rpmoney+'.$num);
     	$money = $this->where("userId = %d",$userId)->save($data);
     	if($money == false){
			 return -1;
		  }
	 }

     /**
	  * 消费扣除金额
	  */
     public function rpconsumption($userId,$num){
     	$userId = $userId?$userId:0;
     	$num = $num / 100;
     	$data['rpmoney'] = array('exp','rpmoney-'.$num);
     	$money = $this->where("userId = %d",$userId)->save($data);
     	if($money == false){
			return -1;
		}
	 }
}