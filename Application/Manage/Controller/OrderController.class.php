<?php
namespace Manage\Controller;
class OrderController extends BaseController {
    /**
     * 添加微信收款订单
     */
    public function addWxOrder(){
        //接收传送的数据
        $data = file_get_contents("php://input"); 
        //转换为simplexml对象
        $rs = $this->FromXml($data);
        if($rs != -1 && $rs['result_code'] == "SUCCESS"){
            $o = D('Manage/Order');
            $rd = $o->addWxOrder($rs);
            $attach = explode(",",$rs['attach']);
            //如果为代收用户，则增加代收金额
            if($attach[4]==2){
                $m = D('Manage/Financial');
                $rdd = $m->dsrecharge($rs);
            }
            if($rd['status']==1){
                //支付成功发送通知
                $post['userId'] = $attach[0];
                $post['usId'] = $attach[1];
                $post['storeId'] = $attach[2];
                $post['price'] = $rs['total_fee']/100;
                $post['body'] = $attach[5];
                $this->sendWxmessage($post);
                echo "success"; //给微信返回成功的结果
            }
        }
    }

    /**
     * 添加光大渠道收款订单
     */
    public function addGdOrder(){
        //接收传送的数据
        $data = file_get_contents("php://input"); 
        //转换为simplexml对象
        $rs = $this->FromXml($data);


        file_put_contents('/data/wwwroot/payv2.7zheli.com/log/data.txt', json_encode($rs)."\r\n\r\n", FILE_APPEND);
        if($rs != -1 && $rs['status'] == "0" && $rs['result_code'] == "0"){
            $o = D('Manage/Order');
            $rd = $o->addGdOrder($rs);
            $attach = explode("&",$rs['attach']);
            $attach = explode(",",$attach[2]);
            if($rd['status']==1 && strstr($rs['trade_type'],"weixin")){
                //支付成功发送通知
                $post['userId'] = $attach[0];
                $post['usId'] = $attach[1];
                $post['storeId'] = $attach[2];
                $post['price'] = $rs['total_fee']/100;
                $post['body'] = $attach[5];
                $this->sendWxmessage($post);
                echo "success"; //给微信返回成功的结果
            }
        }
    }

    /**
     * 二维码页获取微信收款订单信息
     */
    public function getajaxOrder(){
        $this->isAjaxLogin();
        $usId = session('SX_USERS.usId');
        $o = D('Manage/Order');
        $order['status'] = 1;

        if(empty($usId)){//商户
            $order['data'] = $o->getAll(session('SX_USERS.userId'),20);
        }else{//员工
            $order['data'] = $o->getAll(session('SX_USERS.storeId'),20,3); //员工查询当前门店
        }
        $this->ajaxReturn($order);
    }
}