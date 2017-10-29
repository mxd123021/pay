<?php

namespace Manage\Model;

/**
 * 统一小额接口
 */
class XyMicropayModel extends BaseModel
{
    private $resHandler = null;
    private $reqHandler = null;
    private $pay = null;
    private $cfg = null;
    
    public function __construct()
    {
        Vendor('Xingye.RequestHandler', '', '.class.php');
        Vendor('Xingye.ClientResponseHandler', '', '.class.php');
        Vendor('Xingye.PayHttpClient', '', '.class.php');
        Vendor('Xingye.Config');

        $this->resHandler = new \ClientResponseHandler();
        $this->reqHandler = new \RequestHandler();
        $this->pay = new \PayHttpClient();
        $this->cfg = new \Config();
        
        $this->reqHandler->setGateUrl($this->cfg->C('url'));
        //$this->reqHandler->setKey($this->cfg->C('key'));
    }

    public function setKey($key)
    {
        $this->reqHandler->setKey($key);
    }

    public function index(){
        $method = isset($_REQUEST['method'])?$_REQUEST['method']:'submitOrderInfo';
        switch($method){
            case 'submitOrderInfo'://提交订单
                $this->submitOrderInfo();
            break;
            case 'queryOrder'://查询订单
                $this->queryOrder();
            break;
            case 'submitRefund'://提交退款
                $this->submitRefund();
            break;
            case 'queryRefund'://查询退款
                $this->queryRefund();
            break;
            case 'callback':
                $this->callback();
            break;
        }
    }
    
    /**
     * 提交订单信息
     */
    public function submitOrderInfo($data)
    {
        $this->reqHandler->setReqParams($data,array('method'));
        $this->reqHandler->setParameter('service','unified.trade.micropay');//接口类型：unified.trade.micropay
        $this->reqHandler->setParameter('mch_id', $data['mch_id']);//必填项，商户号，由威富通分配
        $this->reqHandler->setParameter('version',$this->cfg->C('version'));
        $this->reqHandler->setParameter('nonce_str',mt_rand(time(),time()+rand()));//随机字符串，必填项，不长于 32 位
        $this->reqHandler->createSign();//创建签名
        
        $data = toXml($this->reqHandler->getAllParameters());
        //var_dump($data);
        
        $this->pay->setReqContent($this->reqHandler->getGateURL(),$data);
        if($this->pay->call()){
            $this->resHandler->setContent($this->pay->getResContent());
            $this->resHandler->setKey($this->reqHandler->getKey());
            if($this->resHandler->isTenpaySign()){

                //dataRecodes('小额支付结果1', $this->resHandler->getAllParameters());

                //当返回状态与业务结果都为0时才返回支付二维码，其它结果请查看接口文档
                if($this->resHandler->getParameter('status') == 0 && $this->resHandler->getParameter('result_code') == 0){
                    return array(
                                'status'        => $this->resHandler->getParameter('status'),
                                'result_code'   => $this->resHandler->getParameter('result_code'),
                                'pay_result'    => $this->resHandler->getParameter('pay_result'),
                                'ret'           => $this->resHandler->getAllParameters(),
                            );
                }elseif ($this->resHandler->getParameter('status') == 0 && $this->resHandler->getParameter('result_code') == 1) {
                    return array(
                                'status'        => $this->resHandler->getParameter('status'),
                                'result_code'   => $this->resHandler->getParameter('result_code'),
                                'need_query'    => $this->resHandler->getParameter('need_query'),
                                'ret'           => $this->resHandler->getAllParameters(),
                            );
                }else{
                    return array('status'=>500,'msg'=>'Error Code:'.$this->resHandler->getParameter('err_code').' Error Message:'.$this->resHandler->getParameter('err_msg'));
                }
            }
            return array('status'=>500,'msg'=>'Error Code:'.$this->resHandler->getParameter('status').' Error Message:'.$this->resHandler->getParameter('message'));
        }else{
            return array('status'=>500,'msg'=>'Response Code:'.$this->pay->getResponseCode().' Error Info:'.$this->pay->getErrInfo());
        }
    }

    /**
     * 查询订单
     */
    public function queryOrder(){
        $this->reqHandler->setReqParams($_POST,array('method'));
        $reqParam = $this->reqHandler->getAllParameters();
        if(empty($reqParam['transaction_id']) && empty($reqParam['out_trade_no'])){
            return array('status'=>500,
                                   'msg'=>'请输入商户订单号或威富通订单号!');
            exit();
        }
        $this->reqHandler->setParameter('version',$this->cfg->C('version'));
        $this->reqHandler->setParameter('service','unified.trade.query');//接口类型：unified.trade.query
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
                return array('status'=>200,'msg'=>'查询结果请查看result.txt文件！','data'=>$res);

//                exit();
            }
            return array('status'=>500,'msg'=>'Error Code:'.$this->resHandler->getParameter('status').' Error Message:'.$this->resHandler->getParameter('message'));
        }else{
            return array('status'=>500,'msg'=>'Response Code:'.$this->pay->getResponseCode().' Error Info:'.$this->pay->getErrInfo());
        }
    }
    
    /**
     * 提交退款
     */
    public function submitRefund($data){
//        $this->reqHandler->setReqParams($data,array('method'));
//        $reqParam = $this->reqHandler->getAllParameters();
//        if(empty($reqParam['transaction_id']) && empty($reqParam['out_trade_no'])){
//            return json_encode(array('status'=>500,
//                                   'msg'=>'请输入商户订单号或威富通订单号!'));
//            exit();
//        }
        $this->reqHandler->setParameter('version',$this->cfg->C('version'));
        $this->reqHandler->setParameter('service','unified.trade.refund');//接口类型：unified.trade.refund
        $this->reqHandler->setParameter('mch_id',$data['mch_id']);//必填项，商户号，由威富通分配
        $this->reqHandler->setParameter('nonce_str',mt_rand(time(),time()+rand()));//随机字符串，必填项，不长于 32 位
        $this->reqHandler->setParameter('op_user_id',$data['mch_id']);//必填项，操作员帐号,默认为商户号
        $this->reqHandler->setParameter('out_refund_no', $data['out_refund_no']);
        $this->reqHandler->setParameter('refund_fee', $data['refund_fee']);
        $this->reqHandler->setParameter('total_fee', $data['total_fee']);
        $this->reqHandler->setParameter('transaction_id', $data['transaction_id']);
        

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
//                    dataRecodes('提交退款',$res);
                    return array('status'=>200,'msg'=>'提交退款成功！','data'=>$res);
                }else{
                    return array('status'=>500,'msg'=>'Error Code:'.$this->resHandler->getParameter('err_code').' Error Message:'.$this->resHandler->getParameter('err_msg'));
                    exit();
                }
            }
            return array('status'=>500,'msg'=>'Error Code:'.$this->resHandler->getParameter('status').' Error Message:'.$this->resHandler->getParameter('message'));
        }else{
            return array('status'=>500,'msg'=>'Response Code:'.$this->pay->getResponseCode().' Error Info:'.$this->pay->getErrInfo());
        }
    }

    /**
     * 查询退款
     */
    public function queryRefund($params){
        $this->reqHandler->setParameter('version',$this->cfg->C('version'));
        $this->reqHandler->setParameter('service','unified.trade.refundquery');//接口类型：unified.trade.refundquery
        $this->reqHandler->setParameter('mch_id',$params['mch_id']);//必填项，商户号，由威富通分配
        $this->reqHandler->setParameter('nonce_str',mt_rand(time(),time()+rand()));//随机字符串，必填项，不长于 32 位
        $this->reqHandler->setParameter('transaction_id', $params['transaction_id']);//必填项，商户号，由威富通分配         
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
                    $res['refund_fee'] = 0;
                    for($i=0; $i<$res['refund_count']; $i++){
                        $res['out_refund_no_'.$i] = $this->resHandler->getParameter('out_refund_no_'.$i);
                        $res['refund_id_'.$i] = $this->resHandler->getParameter('refund_id_'.$i);
                        $res['refund_channel_'.$i] = $this->resHandler->getParameter('refund_channel_'.$i);
                        $res['refund_fee_'.$i] = $this->resHandler->getParameter('refund_fee_'.$i);
                        $res['coupon_refund_fee_'.$i] = $this->resHandler->getParameter('coupon_refund_fee_'.$i);
                        $res['refund_status_'.$i] = $this->resHandler->getParameter('refund_status_'.$i);
                        if('SUCCESS' == $res['refund_status_'.$i]){
                            $res['refund_fee'] += $res['refund_fee_'.$i];
                        }
                    }
//                    dataRecodes('查询退款',$res);
                    return array('status'=>200,'msg'=>'查询成功！','data'=>$res);
              
                }else{
                    return array('status'=>500,'msg'=>'Error Code:'.$this->resHandler->getParameter('err_code'), 'err_code'=>$this->resHandler->getParameter('err_code'));
          
                }
            }
            return array('status'=>500,'msg'=>$this->resHandler->getContent());
        }else{
            return array('status'=>500,'msg'=>'Response Code:'.$this->pay->getResponseCode().' Error Info:'.$this->pay->getErrInfo());
        }
    }
    
    /**
     * 提供给威富通的回调方法
     */
    public function callback(){
        $xml = file_get_contents('php://input');
        //$res = Utils::parseXML($xml);
        $this->resHandler->setContent($xml);
        //var_dump($this->resHandler->setContent($xml));
        $this->resHandler->setKey($this->cfg->C('key'));
        if($this->resHandler->isTenpaySign()){
            if($this->resHandler->getParameter('status') == 0 && $this->resHandler->getParameter('result_code') == 0){
                //echo $this->resHandler->getParameter('status');
                // 11;
                //更改订单状态
                
                
                Utils::dataRecodes('接口回调,返回通知参数',$this->resHandler->getAllParameters());
                echo 'success';
                exit();
            }else{
                echo 'failure';
                exit();
            }
        }else{
            echo 'failure';
        }
    }
}