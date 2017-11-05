<?php
namespace Manage\Helper;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/1
 * Time: 23:44
 */
trait ShanghaiBankPayHelper{




    /**
     * 创建支付订单
     * @param $orderType 支付类型
     * @param $orderNumber 订单号
     * @param $amount 价格 单位元
     * @param $goodsName 订单名称
     * @param $ip 客户端ip
     * @param string $authCode 用户付款码
     * @param string $merchantNumber 商户号
     * @return mixed
     */
    public function createPayOrder($orderType,$orderNumber,$amount,$goodsName,$ip,$authCode = '',$merchantNumber = '701599900000004',$signKey = 'kQXmrACjkkau2qUv4gSKL2SdBQM79zmw'){
        $baseUrl = 'http://bosc.cardinfo.com.cn/middlepaytrx/';
        $url = $this->getUrlByType($baseUrl,$orderType);
        $data = [
            'trxType' => $orderType,
            'merchantNo' => $merchantNumber,
            'orderNum' => $orderNumber,
            'amount' => $amount,
            'goodsName' => $goodsName,
            'serverCallbackUrl' => sprintf('http://%s/testPayNotice', $_SERVER['SERVER_NAME']),
            'orderIp' => $ip,
        ];
        if(!empty($authCode))
        {
            $data['authCode'] = $authCode;
        }
        $data['sign'] = md5($this->makeSign($data,$signKey));
        $request = new \GuzzleHttp\Client();
        $response = (string)$request->request('post', $url, [
            'form_params' => $data
        ])->getBody();
        return json_decode($response,true);
    }

    /**
     * 查询订单结果
     * @param $merchantNumber
     * @param $orderNumber
     * @return array
     */
    public function queryOrderInfoByOrderNumber($merchantNumber, $orderNumber, $querySignKey = '2ygq8n0rF7gqg6Vy9IysrOvP6uAenntD')
    {
        $baseUrl = 'http://bosc.cardinfo.com.cn/middlepaytrx/';
        $url = $this->getUrlByType($baseUrl, 'QUERY');
        $request = new \GuzzleHttp\Client();
        $data = [
            'trxType' => 'OnlineQuery',
            'r1_merchantNo' => $merchantNumber,
            'r2_orderNumber' => $orderNumber,
        ];
        $data['sign'] = md5($this->makeSign($data, $querySignKey));
        $response = (string)$request->request('post', $url, [
            'form_params' => $data
        ])->getBody();
        return json_decode($response,true);
    }

    /**
     * 创建签名字符串
     * @param $data
     * @return string
     */
    public function makeSign($data, $sign = 'pK8o0tkJ5RYSK99cJHbfyHGz0Cc0k7R9')
    {
        return sprintf('#%s#%s', implode('#', array_values($data)), $sign);
    }

    /**
     * 根据类型获取接口URL
     * @param $baseUrl
     * @param string $type
     * @return string
     */
    public function getUrlByType($baseUrl, $type = 'WX_SCANCODE')
    {
        switch ($type) {
            case 'WX_SCANCODE'://微信扫码支付
                return sprintf('%s/wx/scanCode', $baseUrl);
                break;
            case 'WX_SCANCODE_JSAPI': //微信JSAPI支付
                return sprintf('%s/wx/scanCommonCode', $baseUrl);
                break;
            case 'WX_MICROPAY'://微信终端
                return sprintf('%s/wx/passivePay',$baseUrl);
            case 'QUERY'://订单查询
                return sprintf('%s/online/query', $baseUrl);
                break;
            case 'Alipay_SCANQRCODE'://支付宝扫码
                return sprintf('%s/alipay/scanCode', $baseUrl);
                break;
            case 'Alipay_SCANBARCODE'://支付宝扫码枪支付
                return sprintf('%s/alipay/passivePay', $baseUrl);
                break;
            case 'Alipay_LIFENO'://支付宝生活号支付
                return sprintf('%s/alipay/scanCommonCode',$baseUrl);
                break;
            default:
                return false;
                break;
        }
    }

    /**
     * 通知
     * @return string
     */
    public function notice()
    {
        $merchantNumber = '70159480000002';
        //是否有订单号 && 商家号 && 状态值
        if(isset($data['r2_orderNumber']) && isset($data['r1_merchantNo']) && isset($data['r8_orderStatus'])){
            //订单状态是否是成功
            if($data['r8_orderStatus'] === 'SUCCESS'){
                //查询订单
                $orderInfo = $this->queryOrderInfoByOrderNumber($merchantNumber,$data['r2_orderNumber']);
                if(isset($orderInfo['r8_orderStatus']) && $orderInfo['r8_orderStatus'] == 'SUCCESS'){
                    //TODO 订单检测成功并且是支付成功的 下面代码块是处理订单的
                    return 'success';
                }
            }
        }
        return 'fail';
    }

}