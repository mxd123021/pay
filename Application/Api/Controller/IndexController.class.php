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
            if(empty($id)){
                $this->ajaxReturn([
                    'code'=>412,
                    'msg'=>'商户id有误'
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
}