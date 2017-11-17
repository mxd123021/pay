<?php
namespace SX\Controller;
class IndexController extends BaseController {
	/**
	 * 显示后台首页
	 */
    public function index(){
    	$this->isLogin();
        //获取订单数
        $o = D('SX/Order');
        //今年订单数
        $data['curTotal'] = $o->yearOrders(date('Y'),"count");
        //去年订单数
        $data['lastTotal'] = $o->yearOrders(date('Y',strtotime("-1 year")),"count");
        //订单同期增长率
        $data['perOrder'] = intval(($data['curTotal'] - $data['lastTotal']) / $data['lastTotal'] * 100);

        //当月订单数
        $data['curmTotal'] = $o->monthOrders(date('Y-m'),"count");
        //上月订单数
        $data['lastmOrder'] = $o->monthOrders(date('Y-m',strtotime("-1 month")),"count");
        //当月订单收入
        $data['curmIncome'] = $o->monthIncome(date('Y-m'));
        //上月订单收入
        $data['lastmIncome'] = $o->monthIncome(date('Y-m',strtotime("-1 month")));

        //收入同期增长率
        $data['perIncome'] = intval(($data['curmIncome'] - $data['lastmIncome']) / $data['lastmIncome'] * 100);

        //统计半年内的数据
        $month = intval(date('m'));
        switch($month){
            case $month<=7:
                $months['m'] = array('01','02','03','04','05','06','07');
            break;
            default:
                $months['m'] = array($month-6,$month-5,$month-4,$month-3,$month-2,$month-1,$month);
            break;
        }

        for($i=0;$i<7;$i++){
            $mon = $months['m'][$i];
            if($mon <10){
                $mon = '0'.$mon;
            }
            $months['month'][$i] = $mon."月";
            $months['price'][$i] = $o->monthIncome(date('Y-'.$mon));
            $months['total'][$i] = $o->monthOrders(date('Y-'.$mon),"count");
        }

        //获取充值金额
        $c = D('SX/Czorder');
        $data['curmCz'] = $c->monthCz(date('Y-m'));

        //获取用户总数
        $c = D('SX/Users');
        $data['userTotal'] = $c->countUsers();

        $this->assign('data',$data);
        $this->assign('months',$months);
        $this->display("index");
    }

    /**
     * 显示登陆页面
     */
    public function toLogin(){
    	$this->display("login");
    }

    /**
     * 显示网站配置界面
     */
    public function toConfig(){
        $this->isLogin();
        $this->checkPrivelege('sys_wz');
        $m = D('SX/Configs');
        $this->assign('configs',$m->loadConfigs());
        $this->display("config");
    }

    /**
     * 保存网站信息
     */
    public function saveSiteinfo(){
        $this->isAjaxLogin();
        $this->checkAjaxPrivelege('sys_wz');
        $m = D('SX/Configs');
        $rs = $m->saveSiteinfo();
        $this->ajaxReturn($rs);
    }

    /**
     * 修改网站开关状态
     */
    public function editisOpen(){
        $this->isAjaxLogin();
        $this->checkAjaxPrivelege('sys_wz');
        $m = D('SX/Configs');
        $rs = $m->editisOpen();
        $this->ajaxReturn($rs);
    }

    /**
     * 显示支付配置界面
     */
    public function payConfig(){
        $this->isLogin();
        $this->checkPrivelege('sys_zf');
        $m = D('SX/Configs');
        $this->assign('configs',$m->loadConfigs());
        $this->display("payConfig");
    }

    /**
     * 显示微信配置界面
     */
    public function wxConfig(){
        $this->isLogin();
        $this->checkPrivelege('sys_wx');
        $c = D('SX/Configs');
        $configs = $c->loadConfigs();
        $paytemp = $configs['paytemp'];
        if(!empty($paytemp)){
            $paytemp = json_decode($paytemp,true);
            $this->assign('paytemp',$paytemp);
        }
        $bindtemp = $configs['bindtemp'];
        if(!empty($bindtemp)){
            $bindtemp = json_decode($bindtemp,true);
            $this->assign('bindtemp',$bindtemp);
        }
        $balancetemp = $configs['balancetemp'];
        if(!empty($balancetemp)){
            $balancetemp = json_decode($balancetemp,true);
            $this->assign('balancetemp',$balancetemp);
        }
        
        $this->assign('configs',$configs);
        $this->display("wxConfig");
    }

    /**
     * 保存支付信息
     */
    public function savePayinfo(){
        $this->isAjaxLogin();
        $this->checkAjaxPrivelege('sys_zf');
        $m = D('SX/Configs');
        $rs = $m->savePayinfo();
        $this->ajaxReturn($rs);
    }

    /**
     * 根据模板ID获取字段
     */
    public function getTemplate(){
        $this->isAjaxLogin();
        $this->checkAjaxPrivelege('sys_wz');
        $c = D('SX/Configs');
        $configs = $c->loadConfigs();
        $token = R('Manage/Base/getWxToken',array($configs['wx_token'],$configs['wx_update'],$configs['wx_appId'],$configs['wx_appSecret'],2));
        $url = "https://api.weixin.qq.com/cgi-bin/template/get_all_private_template?access_token=".$token;
        $rs = R('Manage/Base/wx_post',array($url));
        $this->ajaxReturn($rs);
    }

    /**
     * 保存微信配置信息
     */
    public function saveWxconfig(){
        $this->isAjaxLogin();
        $this->checkAjaxPrivelege('sys_wx');
        $m = D('SX/Configs');
        $rs = $m->saveWxconfig();
        $this->ajaxReturn($rs);
    }

    /**
     * 保存支付模板
     */
    public function saveTempzf(){
        $this->isAjaxLogin();
        $this->checkAjaxPrivelege('sys_wz');
        $m = D('SX/Configs');
        $rs = $m->saveTempzf();
        $this->ajaxReturn($rs);
    }

    /**
     * 保存绑定模板
     */
    public function saveTempbind(){
        $this->isAjaxLogin();
        $this->checkAjaxPrivelege('sys_wz');
        $m = D('SX/Configs');
        $rs = $m->saveTempbind();
        $this->ajaxReturn($rs);
    }
    
    /**
     * 保存结算模板
     */
    public function saveTempbalance(){
        $this->isAjaxLogin();
        $this->checkAjaxPrivelege('sys_wz');
        $m = D('SX/Configs');
        $rs = $m->saveTempbalance();
        $this->ajaxReturn($rs);
    }

   /**
     * 上传pem文件
     */
    public function pem_Upload(){
        $this->isAjaxLogin();
        $this->checkAjaxPrivelege('sys_wx');
        $this->ajaxReturn($this->uploadFile(204800,array('pem'),"pem/SX"));
    }

    /**
     * 管理员登录
     */
    public function login(){
    	$m = D('SX/Staffs');
    	$rs = $m->login();
    	if($rs['status']==1){
    		session('SX_STAFF',$rs['staff']);
    		unset($rs['staff']);
    		$tip['info'] = "登陆成功";
    		$tip['url'] = U("index");
    	}else{
    		$tip['info'] = "账号或密码错误";
    		$tip['url'] = U("toLogin");
    	}
    	$this->assign('tip',$tip);
    	$this->display("Public/tip");
    }

    /**
     * 显示修改密码页面
     */
    public function modifypwd(){
        $this->isLogin();
        $this->checkPrivelege('sys_pass');
        $this->display("modifypwd");
    }

    /**
     * 修改密码
     */
    public function editPass(){
        $this->isLogin();
        $this->checkPrivelege('sys_pass');
        $m = D('SX/Staffs');
        $rs = $m->editPass(session('SX_STAFF.staffId'));
        if($rs['status']==1){
            $tip['info'] = "修改成功";
        }else{
            $tip['info'] = "密码错误";
        }
        $tip['url'] = U("modifypwd");
        $this->assign('tip',$tip);
        $this->display("Public/tip");
    }

    /**
     * 显示管理员页面
     */
    public function employers(){
        $this->isLogin();
        $this->checkPrivelege('sys_admin');
        $m = D('SX/Staffs');
        $staffs = $m->getAll();
        $r = D('SX/Roles');
        $roles = $r->getAll();

        if(!empty($roles)){
            foreach ($roles as $key => $value) {
                $role[$value['roleId']] = $value['roleName'];
            }
        }

        $this->assign('staffs',$staffs);
        $this->assign('roles',$roles);
        $this->assign('role',$role);
        $this->display("employers");
    }

    /**
     * 检查管理员账号是否存在
     */
    public function checkAccount(){
        $this->isAjaxLogin();
        $this->checkAjaxPrivelege('sys_admin');
        $m = D('SX/Staffs');
        $rs = $m->checkAccount(I('account'));
        $this->ajaxReturn($rs);
    }

     /**
      * 添加管理员
      */
     public function employersAdd(){
        $this->isAjaxLogin();
        $this->checkAjaxPrivelege('sys_admin');
        $m = D('SX/Staffs');
        $rs = $m->employersAdd();
        if($rs['status']==1){
            $tip['info'] = "管理员添加成功";
        }else{
            $tip['info'] = "管理员添加失败";
        }
        $tip['url'] = U("SX/Index/employers");
        $this->assign('tip',$tip);
        $this->display("Public/tip");
    }

    /**
     * 显示管理员编辑页面
     */
    public function employersEdit(){
        $this->isLogin();
        $this->checkPrivelege('sys_admin');
        $s = D('SX/Staffs');
        $staffs = $s->get(I('id'));
        $r = D('SX/Roles');
        $roles = $r->getAll();
        $this->assign('staffs',$staffs);
        $this->assign('roles',$roles);
        $this->display("employersEdit");
    }

    /**
     * 保存编辑管理员信息
     */
    public function employersAppemd(){
        $this->isLogin();
        $this->checkPrivelege('sys_admin');
        $r = D('SX/Staffs');
        $rs = $r->employersAppemd();
        if($rs['status']==1){
            $tip['info'] = "管理员信息保存成功";
        }else{
            $tip['info'] = "管理员信息保存失败";
        }
        $tip['url'] = U("SX/Index/employers");
        $this->assign('tip',$tip);
        $this->display("Public/tip");
    }

    /**
     * 删除管理员
     */
    public function employersDel(){
        $this->isAjaxLogin();
        $this->checkAjaxPrivelege('sys_admin');
        $r = D('SX/Staffs');
        $rs = $r->employersDel();
        $this->ajaxReturn($rs);
    }

    /**
     * 批量删除管理员
     */
    public function employersDelAll(){
        $this->isAjaxLogin();
        $this->checkAjaxPrivelege('sys_admin');
        $m = D('SX/Staffs');
        $rs = $m->employersDelAll();
        if($rs['status']==1){
            $tip['info'] = "管理员删除成功";
        }else{
            $tip['info'] = "管理员删除失败";
        }
        $tip['url'] = U("SX/Index/employers");
        $this->assign('tip',$tip);
        $this->display("Public/tip");
    }

    /**
     * 显示角色管理页面
     */
    public function roles(){
        $this->isLogin();
        $this->checkPrivelege('sys_admin');
        $r = D('SX/Roles');
        $roles = $r->getAll();
        $this->assign('roles',$roles);
        $this->display("roles");
    }

     /**
      * 添加角色
      */
     public function rolesAdd(){
        $this->isAjaxLogin();
        $this->checkAjaxPrivelege('sys_admin');
        $m = D('SX/Roles');
        $rs = $m->rolesAdd();
        if($rs['status']==1){
            $tip['info'] = "角色添加成功";
        }else{
            $tip['info'] = "角色添加失败";
        }
        $tip['url'] = U("SX/Index/roles");
        $this->assign('tip',$tip);
        $this->display("Public/tip");
    }

    /**
     * 保存编辑角色信息
     */
    public function rolesAppemd(){
        $this->isLogin();
        $this->checkPrivelege('sys_admin');
        $r = D('SX/Roles');
        $rs = $r->rolesAppemd();
        if($rs['status']==1){
            $tip['info'] = "员工信息保存成功";
        }else{
            $tip['info'] = "员工信息保存失败";
        }
        $tip['url'] = U("SX/Index/roles");
        $this->assign('tip',$tip);
        $this->display("Public/tip");
    }

    /**
     * 显示角色编辑页面
     */
    public function rolesEdit(){
        $this->isLogin();
        $this->checkPrivelege('sys_admin');
        $r = D('SX/Roles');
        $roles = $r->get(I('roleId'));
        $this->assign('roles',$roles);
        $this->display("rolesEdit");
    }

    /**
     * 删除角色
     */
    public function rolesDel(){
        $this->isAjaxLogin();
        $this->checkAjaxPrivelege('sys_admin');
        $r = D('SX/Roles');
        $rs = $r->rolesDel();
        $this->ajaxReturn($rs);
    }
    //添加商户
    public function showAddMerchant(){
        $this->display('addMerchant');
    }
    /**
     * 批量删除角色
     */
    public function rolesDelAll(){
        $this->isAjaxLogin();
        $this->checkAjaxPrivelege('sys_admin');
        $m = D('SX/Roles');
        $rs = $m->rolesDelAll();
        if($rs['status']==1){
            $tip['info'] = "角色删除成功";
        }else{
            $tip['info'] = "角色删除失败";
        }
        $tip['url'] = U("SX/Index/roles");
        $this->assign('tip',$tip);
        $this->display("Public/tip");
    }

    /**
     * 离开系统
     */
    public function logout(){
        session('SX_STAFF',null);
        $tip['info'] = "退出成功";
        $tip['url'] = U("toLogin");
        $this->assign('tip',$tip);
        $this->display("Public/tip");
    }
}