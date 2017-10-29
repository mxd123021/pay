<?php
namespace Manage\Controller;
class UstaffsController extends BaseController {
    /**
     * 员工登录
     */
    public function staffslogin(){
    	$m = D('Manage/Ustaffs');
    	$rs = $m->checkLogin();
    	if($rs['status']==1){
    		session('SX_USERS',$rs['user']);
    		unset($rs['user']);
    	}
    	$this->ajaxReturn($rs);
    }
    
    /**
     * 检查员工账户是否存在
     */
    public function checkAccount(){
        $this->isAjaxLogin();
        $m = D('Manage/Ustaffs');
        $rs = $m->checkAccount(I('account'));
        $this->ajaxReturn($rs);
    }

    /**
     * 添加员工
     */
    public function employersAdd(){
        $this->isAjaxLogin();
        $m = D('Manage/Ustaffs');
        $rs = $m->employersAdd();
        if($rs['status']==1){
            $tip['info'] = "员工添加成功";
        }else{
            $tip['info'] = "员工添加失败";
        }
        $tip['url'] = U("Manage/Users/employers");
        $this->assign('tip',$tip);
        $this->display("Public/tip");
    }

    /**
     * 保存编辑员工信息
     */
    public function employersAppemd(){
        $this->isLogin();
        $m = D('Manage/Ustaffs');
        $rs = $m->employersAppemd();
        if($rs['status']==1){
            $tip['info'] = "员工信息保存成功";
        }else{
            $tip['info'] = "员工信息保存失败";
        }
        $tip['url'] = U("Manage/Users/employers");
        $this->assign('tip',$tip);
        $this->display("Public/tip");
    }


    /**
     * 删除员工
     */
    public function employersDel(){
        $this->isAjaxLogin();
        $m = D('Manage/Ustaffs');
        $rs = $m->employersDel();
        $b = D('Manage/Bindweixin');
        $bdrs = $b->employersDel();
        $this->ajaxReturn($rs);
    }

    /**
     * 批量删除员工
     */
    public function employersDelAll(){
        $this->isAjaxLogin();
        $m = D('Manage/Ustaffs');
        $rs = $m->employersDelAll();
        $b = D('Manage/Bindweixin');
        $bdrs = $b->employersDelAll();
        if($rs['status']==1){
            $tip['info'] = "员工删除成功";
        }else{
            $tip['info'] = "员工删除失败";
        }
        $tip['url'] = U("Manage/Users/employers");
        $this->assign('tip',$tip);
        $this->display("Public/tip");
    }

    /**
     * 修改员工状态
     */
    public function editisOpen(){
        $this->isAjaxLogin();
        $m = D('Manage/Ustaffs');
        $rs = $m->editisOpen();
        $this->ajaxReturn($rs);
    }
}