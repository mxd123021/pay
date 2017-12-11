<?php
namespace PayView\Controller;
//use Manage\Controller\BaseController;
use GuzzleHttp\Client;
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
    use \ShanghaiBankPayHelper,\ZhuoGePayHelper,\swiftPassPayHelper;
    public function test(){
        $this->createWftPayOrder('pay.tenpay.jspay',time().mt_rand(10000,50000),1,'面包',$_SERVER['REMOTE_ADDR'],'129540013589','dd3957b0753901d8ee1d631f6f97bdaf');
        $users = D('Manage/Users')->field(['userId'])->select();
        foreach($users as $uid){
            D('Manage/Users')->where('userId='.$uid['userId'])->save([
                'unique_id'=>Uuid::uuid4()->toString()
            ]);
        }
        $users = D('SX/RelationMerchants')->field(['id'])->select();
        foreach($users as $uid){
            D('SX/RelationMerchants')->where('id='.$uid['id'])->save([
                'unique_id'=>Uuid::uuid4()->toString()
            ]);
        }
        $id = I('merchant_id');
    }

    //显示支付页面
    public function index(){
        $uniqueId = I('id','');
        $userModel = D('SX/Users');
        $id = $userModel->where([
            'unique_id'=>$uniqueId
        ])->getField('userId');
        if($id > 0){
            $info = D('SX/RelationMerchants')->getRandomMerchantInfoByUserId($id);
            $this->assign('info',$info);
            return $this->display('pay_view');
        }
        $this->display('un_open');
    }

    //显示支付页面
    public function wftIndex(){
        $uniqueId = I('id','');
        $userModel = D('SX/Users');
        $requestId = I('request_id','');
        if(empty($requestId)){
            header(sprintf('Location:%s',sprintf('http://%s/PayView/Index/wftIndex?id=%s&request_id=%s',$_SERVER['SERVER_NAME'],$uniqueId,Uuid::uuid4()->toString())));
            Log::write('没带id');
            exit();
        }
        $res = M('request_ids')->where([
            'request_id'=>$requestId
        ])->find();
        if($res){
            exit();
        }
        M('request_ids')->add([
            'request_id'=>$requestId
        ]);
        $id = $userModel->where([
            'unique_id'=>$uniqueId
        ])->getField('userId');
        if($id > 0){
            $jumpUrl = D('SX/RelationMerchants')->getWftCurrentWheelUrlByUid($id);
            if($jumpUrl){
                redirect($jumpUrl);
                exit();
            }
        }
        $this->display('un_open');
    }

    /**
     * 获取js支付url
     */
    public function getJsPayUrl(){
        $id = I('id','');
        if(empty($id)){
            echo 'id有误';
            return ;
        }
        $price = I('price',0,'floatval');
        if($price <= 0){
            echo '价格有误';
            return ;
        }
        $isAliPay = I('alipay',0);
        $info = $this->createJsOrderByMerchantUniqueId($isAliPay,$this->getOrderNumber(),$price,get_client_ip(),$id);
        if(isset($info['r9_payinfo'])) {
            $res = json_decode($info['r9_payinfo'],true);
            $this->ajaxReturn([
                'url'=>$res['url']
            ]);
        }
        $this->ajaxReturn([
            'msg'=>'数据获取失败'
        ]);
    }

    /**
     * 支付通知
     */
    public function payNotice(){
        $data = I();
        $this->notice(function($orderNumber){
            D('Manage/XyOrder')->setOrderIsPay($orderNumber);
            Log::write('成功');
        },$data);
//        $this->notice();
    }

    public function zhuogePayNotice(){
        $data = I();
        Log::write(json_encode($data));
        $this->zhuogeNotice(function($orderNumber){
            return D('Manage/XyOrder')->setOrderIsPay($orderNumber);
        },$data);
    }
}