<?php
namespace PayView\Controller;
use Ramsey\Uuid\Uuid;
use Think\Controller;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/2 0002
 * Time: 17:07
 */
class IndexController extends Controller{

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

    public function getJsPayUrl(){
        $id = I('id');
    }
}