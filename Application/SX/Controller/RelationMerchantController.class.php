<?php
namespace SX\Controller;
use SX\Helper\ShanghaiBankPayHelper;

class RelationMerchantController extends BaseController
{
    use ShanghaiBankPayHelper;
    public function __construct()
    {
        parent::__construct();
        $this->model = D('SX/RelationMerchants');

    }

    public function getList(){
//        $res = $this->createPayOrder('WX_SCANCODE_JSAPI',sprintf('%s%s',time(),mt_rand(10000,99999)),'0.01','正新鸡排-披萨','127.0.0.1');
//        dump($res);
//        exit();
        $u = D('Manage/Users');
        $m = D('SX/RelationMerchants');
        $id = I('id',0);
        $stores = $m->queryByPage($id);
        $store = new \Think\Page($stores['total'],$stores['pageSize']);
        $stores['pager'] = $store->show();

        $this->assign('configs',$u->get(session('SX_USERS.userId')));
        $this->assign('id',$id);
        $this->assign('stores',$stores);
        return $this->display('/Users/relation_merchants');
    }

    //显示创建页面
    public function showCreate(){
        $id = I('id',0);
        $this->assign('id',$id);
        $this->assign('mode','创建');
        return $this->display('/Users/createStore');
    }

    //创建数据
    public function create(){
        $data = I();
        $data['user_id'] = $data['id'];
        $model = D('SX/RelationMerchants');
        $res = $model->addStore($data);
        $this->ajaxReturn($res);
    }

    /**
     * 修改名称
     */
    public function updateName(){
        $name = I('name','');
        $id = I('storeId',0);
        $res = $this->model->editStoreName($id,$name);
        return $this->ajaxReturn($res);
    }

    /**
     * 修改子商户信息
     */
    public function update(){
        $data = I();
        $res = $this->model->updateItemById($data['id'],$data);
        if($res){
            $this->makeResponse('ok',[
                'status'=>1
            ]);
        }
        $this->setResponseCode(412)->makeResponse('操作失败,请重试',[
            'status'=>-1
        ]);
    }

    /**
     * 删除item
     */
    public function delete(){
        $id = I('id');
        $res = $this->model->deleteById($id);
        $this->ajaxReturn($res);
    }

    public function edit(){
        $merchantId = I('merchant_id');
        $id = I('id');
        $itemInfo = $this->model->find($id);
        $this->assign('id',$merchantId);
        $this->assign('item',json_encode($itemInfo));
        $this->assign('is_edit',true);
        $this->assign('mode','编辑');
        return $this->display('/Users/createStore');
    }
}