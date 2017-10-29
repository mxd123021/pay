<?php

namespace Manage\Controller;
use Think\Controller;

/**
 * 阿里支付宝支付
 */
class AliController extends BaseController
{
    protected $resHandler = null;
    protected $reqHandler = null;
    protected $pay = null;
    protected $cfg = null;

    public function __construct()
    {    
        Vendor('Xingye.RequestHandler', '', '.class.php');
        Vendor('Xingye.ClientResponseHandler', '', '.class.php');
        Vendor('Xingye.PayHttpClient', '', '.class.php');
        Vendor('Xingye.ConfigAli');

        $this->resHandler = new \ClientResponseHandler();
        $this->reqHandler = new \RequestHandler();
        $this->pay = new \PayHttpClient();
        $this->cfg = new \Config();

        $this->reqHandler->setGateUrl($this->cfg->C('url'));

        // $m = D('Manage/Users');
        // $users = $m->get(session('SX_USERS.userId'));
        // $post_data = $this->gettypedata($users);
        // $this->reqHandler->setKey($post_data['xy_key']);

        /*
        $this->reqHandler->setKey($this->cfg->C('key'));
        */
    }

    public function setKey($key)
    {
        $this->reqHandler->setKey($key);
    }

    /**
     * 提供给威富通的回调方法
     */
    public function callback()
    {
        $xml = file_get_contents('php://input');
        $this->resHandler->setContent($xml);
        
        $order_id = $this->resHandler->getParameter('out_trade_no');
        $user_key = get_user_key($order_id);
        $this->resHandler->setKey($user_key);

        if($this->resHandler->isTenpaySign())
        {
            if($this->resHandler->getParameter('status') == 0 && $this->resHandler->getParameter('result_code') == 0)
            {
                //echo $this->resHandler->getParameter('status');
                // 11;
                //更改订单状态
                $data = $this->resHandler->getAllParameters();
                $data['ispay'] = 1;
                $data['paytime'] = time();
                $rs = D('Manage/XyOrder')->editOrderStatus($order_id, $data);
                if($rs !== false)
                {
                    echo 'success';
                }
                else
                {
                    echo 'failure1';
                }
                
                // 读取缓存
                $value = F($order_id);
                if(!$value){//不存在缓存，则创建
                    dataRecodes('in callback'.$order_id, '创建缓存1');
                    F($order_id, 1);
                    dataRecodes('in callback'.$order_id.'缓存0=', json_encode(F($order_id)));                   
                }else{
                    dataRecodes('in callback'.$order_id.'缓存=', $value);
                }                
                if(!$value){
                    dataRecodes('in callback'.$order_id, '无缓存，发送消息');
                    //打印小票
                    $order_info = D('Manage/Order')->where(array('order_id' => $order_id))->find();
                    $ustaff = D('Manage/Ustaffs')->where(array('usId'=>$order_info['eid']))->find();
                    if(!empty($ustaff['printer_sn'])){//是否绑定了打印机
                        $printer_info = D('Manage/Printer')->where(array('printer_sn'=>$ustaff['printer_sn']))->find();
                        $printer = A('Printer');
                        $printer->setPrinterInfo($printer_info['printer_sn'], $printer_info['printer_key'], $printer_info['printer_version']);
                        $printer->setOrderInfo($order_info, $printer_info['printer_version']);
                        if($order_info['has_print'] == 0){//未打印
                            $printer->toPrintOrder();
                            //更新订单打印状态，避免重复打印
                            D('Manage/Order')->where(array('order_id' => $order_id))->updatePrintStatus($order_id, 1);
                        }
                    }
                    //支付成功发送通知            
                    if($order_info['has_sendMsg'] == 0){//未发送消息提醒
                        $store = D('Manage/Stores')->where(array('storeId'=>$order_info['storeid']))->find();
                        if(!empty($store)){
                            $post['userId'] = $order_info['uid'];
                            $post['usId'] = $order_info['eid'];
                            $post['storeId'] = $order_info['storeid'];//session('SX_USERS.storeId');
                            $post['price'] = $order_info['goods_price'];
                            $post['merchant_name'] = $store['branch_name'];
                            if($order_info['pay_way'] == 'weixin'){
                                $post['payType'] = 1;
                            }else{
                                $post['payType'] = 2;
                            }
                            $post['orderId'] = $order_info['order_id'];
                            $this->sendWxmessage($post);
                            //更新消息提醒状态，避免重复提醒
                            D('Order')->where(array('order_id' => $order_id))->updateSendMsgStatus($order_id, 1);
                        }
                    }
                }
                
                exit();
            }
            else
            {
                echo 'failure';
                exit();
            }
        }
        else
        {
            echo 'failure';
        }
    }

    public function payfinish()
    {
        R('Xingye/successTips');
    }

    public function index()
    {
        $method = isset($_REQUEST['method'])?$_REQUEST['method']:'submitOrderInfo';
        $data = dataChange($_POST);

        dataRecodes('data', $data);

        switch($method)
        {
            case 'submitOrderInfo'://提交订单

                if(empty($data['userId']))
                {
                    echo json_encode(array('status' => 500, 'msg' => '商户参数错误！'));
                    exit;
                }

                $users = D('Manage/Users')->get($data['userId']);
                $post_data = array();
                //判断是否为特约商户，特约商户调用系统微信配置
                $post_data = $this->gettypedata($users);
                if(empty($post_data['mch_id']))
                {
                    echo json_encode(array('status' => 500, 'msg' => '缺少mch_id！'));
                    exit;
                }
                /**
                session('SX_USERS.userId').",".
                session('SX_USERS.usId').",".
                session('SX_USERS.storeId').",".
                session('SX_USERS.parentId').",".
                $post_data['mchtype'].",".
                $post_data['body'].",".
                "扫码支付"
                */
                $data['out_trade_no'] = $post_data['mch_id'] . date("YmdHis");
                $data['uid'] = $data['userId'];
                $data['mch_id'] = $post_data['mch_id'];
                unset($data['userId']);

                $data['eid'] = $data['usId'];
                unset($data['usId']);

                $data['storeid'] = $data['storeId'];
                unset($data['storeId']);

                $data['goods_name'] = $data['tname'];
                //unset($data['tname']);
                $data['goods_describe'] = '扫码支付';
                $data['mchtype'] = $post_data['mchtype'];
                $data['mch_id'] = $post_data['mch_id'];
                //兼容旧数据，金誉蛋糕、曹记、奇优优二维码早已铺设，需另外区分订单
                if(in_array($data['uid'], array(38, 39, 139, 143, 161))){//金誉
                    $data['pmid'] = 178;
                }elseif (in_array($data['uid'], array(56, 59))) {//奇优优
                    $data['pmid'] = 180;
                }elseif (in_array($data['uid'], array(13, 61))) {//金苑
                    $data['pmid'] = 191;
                }elseif (in_array($data['uid'], array(198))) {//金苑
                    $data['pmid'] = 202;
                }else{
                    $data['pmid'] = !empty(session('SX_USERS.parentId')) ? session('SX_USERS.parentId') : $data['parentId'];
                }
//                $data['pmid'] = !empty(session('SX_USERS.parentId')) ? session('SX_USERS.parentId') : $data['parentId'];

                $data['total_fee'] = $data['tprice'];
                $data['body'] = $data['tname'];
                $data['mch_create_ip'] = get_client_ip();
                $this->submitOrderInfo($data);
                break;
            case 'queryOrder'://查询订单
                $this->queryOrder($data);
                break;
            case 'submitRefund'://提交退款
                $this->submitRefund();
                break;
            case 'queryRefund'://查询退款
                $this->queryRefund($data);
                break;
            case 'callback':
                $this->callback();
                break;
            case 'payfinish':
                $this->payfinish();
                break;
        }
    }
    
    /**
     * 提交订单信息
     */
    public function submitOrderInfo($data)
    {
        if(empty($data['mch_id']))
        {
            return array('status' => 5010, 'msg' => 'mch_id必传！');
        }

        $data['trade_type'] = $trade_type = 'pay.alipay.jspay';
        $res = D('Manage/XyOrder')->addOrder($data);
        if(!$res)
        {
            return array('status' => 5010, 'msg' => '创建订单失败！');
        }
        else
        {
            unset($data['userId'], $data['userId'], $data['userId'], $data['userId']);
            unset($data['pmid']);
            unset($data['parentId']);

            $data['total_fee'] *= 100;
        }

        $this->reqHandler->setReqParams($data, array('method'));
        $this->reqHandler->setParameter('service', $trade_type);//接口类型：pay.weixin.jspay
        $this->reqHandler->setParameter('mch_id', $data['mch_id']);//必填项，商户号，由威富通分配
        //$this->reqHandler->setParameter('version',$this->cfg->C('version'));
        
        //通知地址，必填项，接收威富通通知的URL，需给绝对路径，255字符内格式如:http://wap.tenpay.com/tenpay.asp
        //$notify_url = 'http://'.$_SERVER['HTTP_HOST'];
        //$this->reqHandler->setParameter('notify_url',$notify_url.'/payInterface/request.php?method=callback');
        $this->reqHandler->setParameter('notify_url', U('Ali/index@' . C('SITE_URL'), array('method' => 'callback')));//
        $this->reqHandler->setParameter('callback_url', U('Xingye/successTips@' . C('SITE_URL'), array('order_id' => $data['out_trade_no'])) );
        $this->reqHandler->setParameter('nonce_str',mt_rand(time(),time()+rand()));//随机字符串，必填项，不长于 32 位
        $this->reqHandler->createSign();//创建签名
        
        $data = toXml($this->reqHandler->getAllParameters());
        
        dataRecodes('接口请求地址', $this->reqHandler->getGateURL());
        dataRecodes('接口请求参数', $data);
        dataRecodes('调试信息', $this->reqHandler->getDebugInfo());
        

        $this->pay->setReqContent($this->reqHandler->getGateURL(), $data);
        if($this->pay->call()){
            $this->resHandler->setContent($this->pay->getResContent());
            $this->resHandler->setKey($this->reqHandler->getKey());
            if($this->resHandler->isTenpaySign()){
                //当返回状态与业务结果都为0时才返回支付二维码，其它结果请查看接口文档
                if($this->resHandler->getParameter('status') == 0 && $this->resHandler->getParameter('result_code') == 0){
                    echo json_encode(array('status' => $this->resHandler->getParameter('status'), 'token_id'=>$this->resHandler->getParameter('token_id'), 'pay_url'=>$this->resHandler->getParameter('pay_url')));
                    exit();
                }else{
                    echo json_encode(array('status'=>500,'msg'=>'Error Code:'.$this->resHandler->getParameter('err_code').' Error Message:'.$this->resHandler->getParameter('err_msg')));
                    exit();
                }
            }
            echo json_encode(array('status'=>500,'msg'=>'Error Code:'.$this->resHandler->getParameter('status').' Error Message:'.$this->resHandler->getParameter('message')));
        }else{
            echo json_encode(array('status'=>500,'msg'=>'Response Code:'.$this->pay->getResponseCode().' Error Info:'.$this->pay->getErrInfo()));
        }
    }

    /**
     * 查询订单
     */
    public function queryOrder($data)
    {
        $this->reqHandler->setReqParams($data, array('method'));
        $reqParam = $this->reqHandler->getAllParameters();
        if(empty($reqParam['transaction_id']) && empty($reqParam['out_trade_no'])){
            echo json_encode(array('status'=>500,
                                   'msg'=>'请输入商户订单号,威富通订单号!'));
            exit();
        }
        $this->reqHandler->setParameter('version',$this->cfg->C('version'));
        $this->reqHandler->setParameter('service','trade.single.query');//接口类型：trade.single.query
        $this->reqHandler->setParameter('mch_id',$this->cfg->C('mchId'));//必填项，商户号，由威富通分配
        $this->reqHandler->setParameter('nonce_str',mt_rand(time(),time()+rand()));//随机字符串，必填项，不长于 32 位
        $this->reqHandler->createSign();//创建签名
        $data = toXml($this->reqHandler->getAllParameters());

        $this->pay->setReqContent($this->reqHandler->getGateURL(),$data);
        if($this->pay->call()){
            $this->resHandler->setContent($this->pay->getResContent());
            $this->resHandler->setKey($this->reqHandler->getKey());
            if($this->resHandler->isTenpaySign()){
                $res = $this->resHandler->getAllParameters();
                dataRecodes('查询订单',$res);
                //支付成功会输出更多参数，详情请查看文档中的7.1.4返回结果
                echo json_encode(array('status'=>200,'msg'=>'查询订单成功，请查看result.txt文件！','data'=>$res));
                exit();
            }
            echo json_encode(array('status'=>500,'msg'=>'Error Code:'.$this->resHandler->getParameter('status').' Error Message:'.$this->resHandler->getParameter('message')));
        }else{
            echo json_encode(array('status'=>500,'msg'=>'Response Code:'.$this->pay->getResponseCode().' Error Info:'.$this->pay->getErrInfo()));
        }
    }
    
    
     /**
     * 提交退款
     */
    public function submitRefund($data)
    {
        $this->reqHandler->setReqParams($data,array('method'));
        $reqParam = $this->reqHandler->getAllParameters();
        if(empty($reqParam['transaction_id']) && empty($reqParam['out_trade_no'])){
            echo json_encode(array('status'=>500,
                                   'msg'=>'请输入商户订单号或威富通订单号!'));
            exit();
        }
        $this->reqHandler->setParameter('version',$this->cfg->C('version'));
        $this->reqHandler->setParameter('service','trade.single.refund');//接口类型：trade.single.refund
        $this->reqHandler->setParameter('mch_id',$this->cfg->C('mchId'));//必填项，商户号，由威富通分配
        $this->reqHandler->setParameter('nonce_str',mt_rand(time(),time()+rand()));//随机字符串，必填项，不长于 32 位
        $this->reqHandler->setParameter('op_user_id',$this->cfg->C('mchId'));//必填项，操作员帐号,默认为商户号

        $this->reqHandler->createSign();//创建签名
        $data = toXml($this->reqHandler->getAllParameters());//将提交参数转为xml，目前接口参数也只支持XML方式

        $this->pay->setReqContent($this->reqHandler->getGateURL(),$data);
        if($this->pay->call()){
            $this->resHandler->setContent($this->pay->getResContent());
            $this->resHandler->setKey($this->reqHandler->getKey());
            if($this->resHandler->isTenpaySign()){
                //当返回状态与业务结果都为0时才返回支付二维码，其它结果请查看接口文档
                if($this->resHandler->getParameter('status') == 0 && $this->resHandler->getParameter('result_code') == 0){
                    /*$res = array('transaction_id'=>$this->resHandler->getParameter('transaction_id'),
                                 'out_trade_no'=>$this->resHandler->getParameter('out_trade_no'),
                                 'out_refund_no'=>$this->resHandler->getParameter('out_refund_no'),
                                 'refund_id'=>$this->resHandler->getParameter('refund_id'),
                                 'refund_channel'=>$this->resHandler->getParameter('refund_channel'),
                                 'refund_fee'=>$this->resHandler->getParameter('refund_fee'),
                                 'coupon_refund_fee'=>$this->resHandler->getParameter('coupon_refund_fee'));*/
                    $res = $this->resHandler->getAllParameters();
                    dataRecodes('提交退款',$res);
                    echo json_encode(array('status'=>200,'msg'=>'退款成功,请查看result.txt文件！','data'=>$res));
                    exit();
                }else{
                    echo json_encode(array('status'=>500,'msg'=>'Error Code:'.$this->resHandler->getParameter('err_code').' Error Message:'.$this->resHandler->getParameter('err_msg')));
                    exit();
                }
            }
            echo json_encode(array('status'=>500,'msg'=>'Error Code:'.$this->resHandler->getParameter('status').' Error Message:'.$this->resHandler->getParameter('message')));
        }else{
            echo json_encode(array('status'=>500,'msg'=>'Response Code:'.$this->pay->getResponseCode().' Error Info:'.$this->pay->getErrInfo()));
        }
    }

    /**
     * 查询退款
     */
    public function queryRefund($data)
    {
        $this->reqHandler->setReqParams($data, array('method'));
        if(count($this->reqHandler->getAllParameters()) === 0){
            echo json_encode(array('status'=>500,
                                   'msg'=>'请输入商户订单号,威富通订单号,商户退款单号,威富通退款单号!'));
            exit();
        }
        $this->reqHandler->setParameter('version',$this->cfg->C('version'));
        $this->reqHandler->setParameter('service','trade.refund.query');//接口类型：trade.refund.query
        $this->reqHandler->setParameter('mch_id',$this->cfg->C('mchId'));//必填项，商户号，由威富通分配
        $this->reqHandler->setParameter('nonce_str',mt_rand(time(),time()+rand()));//随机字符串，必填项，不长于 32 位
        
        $this->reqHandler->createSign();//创建签名
        $data = toXml($this->reqHandler->getAllParameters());//将提交参数转为xml，目前接口参数也只支持XML方式

        $this->pay->setReqContent($this->reqHandler->getGateURL(),$data);//设置请求地址与请求参数
        if($this->pay->call()){
            $this->resHandler->setContent($this->pay->getResContent());
            $this->resHandler->setKey($this->reqHandler->getKey());
            if($this->resHandler->isTenpaySign()){
                //当返回状态与业务结果都为0时才返回支付二维码，其它结果请查看接口文档
                if($this->resHandler->getParameter('status') == 0 && $this->resHandler->getParameter('result_code') == 0){
                    /*$res = array('transaction_id'=>$this->resHandler->getParameter('transaction_id'),
                                  'out_trade_no'=>$this->resHandler->getParameter('out_trade_no'),
                                  'refund_count'=>$this->resHandler->getParameter('refund_count'));
                    for($i=0; $i<$res['refund_count']; $i++){
                        $res['out_refund_no_'.$i] = $this->resHandler->getParameter('out_refund_no_'.$i);
                        $res['refund_id_'.$i] = $this->resHandler->getParameter('refund_id_'.$i);
                        $res['refund_channel_'.$i] = $this->resHandler->getParameter('refund_channel_'.$i);
                        $res['refund_fee_'.$i] = $this->resHandler->getParameter('refund_fee_'.$i);
                        $res['coupon_refund_fee_'.$i] = $this->resHandler->getParameter('coupon_refund_fee_'.$i);
                        $res['refund_status_'.$i] = $this->resHandler->getParameter('refund_status_'.$i);
                    }*/
                    $res = $this->resHandler->getAllParameters();
                    dataRecodes('查询退款',$res);
                    echo json_encode(array('status'=>200,'msg'=>'查询成功,请查看result.txt文件！','data'=>$res));
                    exit();
                }else{
                    echo json_encode(array('status'=>500,'msg'=>'Error Code:'.$this->resHandler->getParameter('err_code')));
                    exit();
                }
            }
            echo json_encode(array('status'=>500,'msg'=>$this->resHandler->getContent()));
        }else{
            echo json_encode(array('status'=>500,'msg'=>'Response Code:'.$this->pay->getResponseCode().' Error Info:'.$this->pay->getErrInfo()));
        }
    }
}