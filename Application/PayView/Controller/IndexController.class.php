<?php
namespace PayView\Controller;
use Manage\Controller\BaseController;
use Ramsey\Uuid\Uuid;
use Think\Controller;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/2 0002
 * Time: 17:07
 */
class IndexController extends BaseController{
    use \ShanghaiBankPayHelper;
    public function test(){
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
        echo '禁止访问';
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

    public function payNotice(){
        $this->notice();
    }
}