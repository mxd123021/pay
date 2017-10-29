<?php
namespace SX\Controller;
class OrderController extends BaseController {
    /**
     * 添加微信收款订单
     */
    public function addWxCzOrder(){
        //接收传送的数据
        $data = file_get_contents("php://input"); 
        //转换为simplexml对象
        $rs = $this->FromXml($data);
        if($rs != -1 && $rs['result_code'] == "SUCCESS"){
            $o = D('SX/Czorder');
            $rd = $o->addWxCzOrder($rs);
            $f = D('Manage/Findetails');
            $rdd = $f->addWxDetails($rs);
            $m = D('Manage/Financial');
            $rddd = $m->recharge($rs);
            echo "success"; //给微信返回成功的结果
        }
    }

    /**
     * 根据时间获取指定用户订单信息
     */
    public function getajaxOrder(){
        $this->isAjaxLogin();
        $this->checkAjaxPrivelege('money_dstx');
        $usId = I('usId',0);
        $stime = I('stime',0);
        $etime = I('etime',0);
        $stime = $stime != 0 ? strtotime($stime) : strtotime(date('Y-m-d',strtotime('-6days')));
        $etime = $etime != 0 ? strtotime("+1days", strtotime($etime)) : strtotime(date('Y-m-d',strtotime('+1days')));

        $sql = " and paytime >= ".$stime." and paytime <= ".$etime;
        $o = D('Manage/Order');
        $orders = $o->getAll($usId,$limit=0,$model=1,$sql);//根据用户ID
        $this->ajaxReturn($orders);
    }
}