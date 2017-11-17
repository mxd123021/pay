<?php
namespace PayView\Controller;
//use Manage\Controller\BaseController;
use Ramsey\Uuid\Uuid;
use Think\Controller;
use Think\Log;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/2 0002
 * Time: 17:07
 */
class IndexController extends Controller{
    use \ShanghaiBankPayHelper,\ZhuoGePayHelper;

    public function pay(){
        if(IS_POST){
            $id = I('merchant_id','');
            $data = I();
            $res = $this->validPostData($data);
            if($res instanceof \Exception){
                $this->ajaxReturn([
                    'code'=>$res->getCode(),
                    'msg'=>$res->getMessage()
                ]);
            }
            //获取商户信息
            $uInfo = D('Manage/Users')->getUserByUniqueId($id);
            if(!$uInfo){
                $this->ajaxReturn([
                    'code'=>412,
                    'msg'=>'商户id有误'
                ]);
            }
            $data = [
                'payType'=>0,
                'merchantId'=>'',
                'orderNumber'=>'',
                'price'=>'',
                'goodsName'=>'',
                'timeOut'=>'',
                'serverCallbackUrl'=>'',
                'orderIp'=>'',
            ];
        }
        $this->ajaxReturn([
            'code'=>412,
            'msg'=>'请求方式有误'
        ]);
    }


    public function validPostData($data){
        $requiredKeys = [
            'payType'=>'付款类型为必填',
            'merchantNo'=>'商户id为必填',
            'orderNum'=>'订单号为必填',
            'price'=>'订单金额为必填',
            'goodsName'=>'商品名称为必填',
            'serverCallbackUrl'=>'服务器回调地址为必填',
            'orderIp'=>'支付ip为必填'
        ];
        foreach($requiredKeys as $key=>$val){
            if(!isset($data[$key])){
                return new \Exception($val,412);
            }
        }

        if(!in_array($data['payType'],[0,1])){
            return new \Exception('付款类型有误',412);
        }
        if(strlen($data['merchantNo']) != 36){
            return new \Exception('商户id有误',412);
        }
        $oLength = strlen($data['orderNum']);
        if($oLength < 8 || $oLength > 50){
            return new \Exception('订单号必须在8-50位区间',412);
        }
        $intPrice = intval($data['price']);
        if($intPrice != $data['price']){
            return new \Exception('订单金额有误',412);
        }
        $gLength = strlen($data['goodsNumber']);
        if($gLength > 150){
            return new \Exception('商品名称不能大于150个字',412);
        }
        $sLength = strlen($data['serverCallbackUrl']);
        if($sLength > 300){
            return new \Exception('回调地址字符长度必须在300位以下',412);
        }
        if(!filter_var($data['orderIp'],FILTER_VALIDATE_IP)){
            return new \Exception('用户支付IP地址有误',412);
        }
        return true;
    }
}