<?php
namespace SX\Controller;
class RelationMerchantController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->model = D('SX/RelationMerchants');

    }

    public function getList(){
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
        return $this->display('/Users/createStore');
    }

    //创建数据
    public function create(){
        $data = I();
        $data['user_id'] = $data['id'];
        $model = D('SX/RelationMerchants');
        $res = $model->addStore($data);
        return $this->ajaxReturn($res);
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
     * 删除item
     */
    public function delete(){
        $id = I('id');
        $res = $this->model->deleteById($id);
        return $this->ajaxReturn($res);
    }
}