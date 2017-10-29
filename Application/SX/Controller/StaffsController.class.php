<?php
namespace SX\Controller;
class StaffsController extends BaseController {
     /**
     * 修改职员密码
     */
    public function editPass(){
        $this->isLogin();
        $m = D('SX/Staffs');
        $rs = $m->editPass(session('WST_STAFF.staffId'));
        $this->ajaxReturn($rs);
    }
}