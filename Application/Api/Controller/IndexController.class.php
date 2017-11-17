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
            $id = I('');
        }
        $this->ajaxReturn([
            'code'=>412,
            'msg'=>'请求方式有误'
        ]);
    }
}