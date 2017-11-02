<?php

namespace App\Http\Controllers;

use App\Models\DoorAuthorizationModel;
use App\Models\DoorShareModel;
use App\Models\OpenDoorRecordModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Ramsey\Uuid\Uuid;

class GuestController extends BaseController
{
    //访客授权页面
    public function makeGuestAuthView($number)
    {
        $auth = DoorAuthorizationModel::getItemByNumber($number);
        if ($auth && $shares = DoorShareModel::getRelationItems($auth->id)) {
            return View::make('share', [
                'shares' => $shares
            ]);
        }
    }

    //添加访客钥匙开门记录
    public function addGuestOpenDoorRecord($number)
    {
        if (empty(trim($number))) {
            return $this->setResponseCode(412)->makeResponse('编号有误');
        }
        $item = DoorShareModel::getItemByNumber($number);
        if (!$item) {
            return $this->setResponseCode(500)->makeResponse('提交失败');
        }
        //添加记录
        $res = OpenDoorRecordModel::addItem($item['door_auth_id'], $item, 2);
        if ($res) {
            return $this->makeResponse('ok');
        }
        return $this->setResponseCode(500)->makeResponse('添加失败');
    }

    /**
     * 创建订单
     * @param Request $request
     */
    public function createOrder(Request $request)
    {
        $baseUrl = 'http://bosc.cardinfo.com.cn/middlepaytrx/';
//        dd($this->queryOrderInfoByOrderNumber('70159480000002','1508932269315314'));
//        $url = $this->getUrlByType($baseUrl);
//        $data = [
//            'trxType' => 'WX_SCANCODE',
//            'merchantNo' => '70159480000002',
//            'orderNum' => '1508932269315314',
//            'amount' => '0.01',
//            'goodsName' => '正新鸡排-鸡排一份',
//            'serverCallbackUrl' => sprintf('http://%s/testPayNotice', $_SERVER['SERVER_NAME']),
//            'orderIp' => $request->getClientIp(),
//        ];
//
//        $data['sign'] = md5($this->makeSign($data, 'pK8o0tkJ5RYSK99cJHbfyHGz0Cc0k7R9'));
//        $requestClient = new \GuzzleHttp\Client();
//        $response = (string)$requestClient->request('post', $url, [
//            'form_params' => $data
//        ])->getBody();
        dd($this->createPayOrder('Alipay_LIFENO','177225933653415','0.01','正新鸡排-披萨',$request->getClientIp()));
    }

    public function getRandomMerchantPayUrl(){

    }

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
    public function createPayOrder($orderType,$orderNumber,$amount,$goodsName,$ip,$authCode = '',$merchantNumber = '70159480000002'){
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
        $data['sign'] = md5($this->makeSign($data,'pK8o0tkJ5RYSK99cJHbfyHGz0Cc0k7R9'));
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
        $data = Input::all();
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

    public static function encrypt($data, $key)
    {
        $module = mcrypt_module_open('des', '', MCRYPT_MODE_CBC, '');
        $key = substr(md5($key), 0, mcrypt_enc_get_key_size($module));
        srand();
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($module), MCRYPT_RAND);
        mcrypt_generic_init($module, $key, $iv);
        $encrypted = $iv . mcrypt_generic($module, $data);
        mcrypt_generic_deinit($module);
        mcrypt_module_close($module);
        return md5($data) . '_' . base64_encode($encrypted);
    }

    public static function decrypt($data, $key)
    {
        $_data = explode('_', $data, 2);
        if (count($_data) < 2) {
            return false;
        }
        $data = base64_decode($_data[1]);
        $module = mcrypt_module_open('des', '', MCRYPT_MODE_CBC, '');
        $key = substr(md5($key), 0, mcrypt_enc_get_key_size($module));
        $ivSize = mcrypt_enc_get_iv_size($module);
        $iv = substr($data, 0, $ivSize);
        mcrypt_generic_init($module, $key, $iv);
        $decrypted = mdecrypt_generic($module, substr($data, $ivSize, strlen($data)));
        mcrypt_generic_deinit($module);
        mcrypt_module_close($module);
        $decrypted = rtrim($decrypted, "\0");
        if ($_data[0] != md5($decrypted)) {
            return false;
        }
        return $decrypted;
    }

    public function test(){
        return view('unopen_guest');
    }
}
