<?php
namespace SX\Model;
class FindetailsModel extends BaseModel {
     /**
       * 添加管理员充值财务明细
       */
     public function addadminDetails($userId,$num){
          $userId = $userId?$userId:0;
          $data['uid'] = $userId; //商户ID
          $data['type'] = 4; //管理员充值
          $data['goods_price'] = $num;
          $data['pn'] = 1; //1加 0减
          $data['real'] = $num; //实际到账
          $data['time'] = time();
          $data['note'] = "管理员充值";
          $this->add($data);
      }
}