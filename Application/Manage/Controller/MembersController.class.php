<?php
namespace Manage\Controller;
class MembersController extends BaseController {
    /**
     * 显示会员等级页面
     */
    public function memberGrade(){
        $this->isLogin();
        $m = D("Manage/memberGrade");    
        $this->assign('grade',$m->getAll(session('SX_USERS.userId')));
        $this->display("memberGrade");
    }

    /**
     * 显示添加会员等级页面
     */
    public function memberGradeAdd(){
        $this->isLogin();
        $this->display("memberGradeAdd");
    }

    /**
     * 显示修改会员等级页面
     */
    public function memberGradeEdit(){
        $this->isLogin();
        $m = D("Manage/memberGrade");
        $this->assign('grade',$m->get(I('id')));
        $this->display("memberGradeEdit");
    }

    /**
     * 添加会员等级
     */
    public function gradeAdd(){
        $this->isAjaxLogin();
        $m = D("Manage/memberGrade");
        $rs = $m->gradeAdd();
        $this->ajaxReturn($rs);
    }

    /**
     * 修改会员等级
     */
    public function gradeEdit(){
        $this->isAjaxLogin();
        $m = D("Manage/memberGrade");
        $rs = $m->gradeEdit();
        $this->ajaxReturn($rs);
    }

    /**
     * 删除会员等级
     */
    public function memberGradeDel(){
        $this->isAjaxLogin();
        $m = D("Manage/memberGrade");
        $rs = $m->gradeDel();
        $this->ajaxReturn($rs);
    }
}