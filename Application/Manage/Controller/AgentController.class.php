<?php
namespace Manage\Controller;
class AgentController extends BaseController {
    /**
     * 显示代理添加商户页面
     */
    public function merchants(){
        $this->isLogin();
        $this->checkPrivelege(1); //判断是否为受理商
        $m = D('Manage/Users');
        $merchants = $m->getUsers(session('SX_USERS.userId'));
        //统计收入情况
        $o = D('Manage/Order');        
        $data['curdIncome'] = 0;//当天
        $data['curmIncome'] = 0;//当月
        foreach ($merchants as $key => $merchant) {
            $data['curdIncome'] += $o->dayIncome($merchant['userId'],date('Y-m-d'));
            $data['curmIncome'] += $o->monthIncome($merchant['userId'],date('Y-m'));
        }
        
        $this->assign('data', $data);
        $this->assign('merchants',$merchants);
        $this->display("merchant");
    }

    /**
     * 显示受理商添加商户详细页面
     */
    public function merchantdetail(){
        $this->isLogin();
        $this->checkPrivelege(1);

        $m = D('Manage/Users');
        $userinfo = $m->get();
        if($userinfo['parentId'] != session('SX_USERS.userId')){
            $tip['info'] = "没有权限";
            $tip['url'] = U("Manage/Agent/merchants");
            $this->assign('tip',$tip);
            $this->display("Public/tip");
        }
        
        $c = D('SX/Configs');
        $configs = $c->loadConfigs();
        $d = D('Manage/GdDistrict');
        $district = $d->getProvince();
        $k = D('Manage/GdBank');
        $bank = $k->get();
        $this->assign('configs',$configs);
        $this->assign('userinfo',$userinfo);
        $this->assign('district',$district);
        $this->assign('bank',$bank);
        
        $this->display("merchantdetail");
    }

    /**
     * 显示受理商所属用户订单列表
     */
    public function orderLists(){
        $this->isLogin();
        $this->checkPrivelege(1);
        $paytype = I('paytype');
        switch ($paytype) {
            case '1': //微信数据                
                break;
            case '2': //支付宝数据                 
                break;  
            default: //所有类型               
                $paytype = "0";                
                break;
        }
        $merchant = I('merchant');
        //时间段
        $stime = I('stime',0);
        $etime = I('etime',0);               
        $starttime = $stime != 0 ? $stime : date('Y-m-d');
        $stime = $stime != 0 ? strtotime($stime) : strtotime(date('Y-m-d'));
        $endtime = $etime != 0 ? $etime : date('Y-m-d');
        $etime = $etime != 0 ? strtotime("+1days", strtotime($etime)) : strtotime(date('Y-m-d',strtotime('+1days')));
        //获取子商户
         $m = D('Manage/Users');
        $merchants = $m->getUsers(session('SX_USERS.userId'));        
        $merchantName = array();
        foreach ($merchants as $value) {
            $merchantName[$value['userId']] = $value['userName'];
        }
        //获取订单
        $sql = '';
        //支付类型
        if($paytype==1){
            $sql = "pay_way = 'weixin'";
        }elseif($paytype == 2){
            $sql = "pay_way = 'alipay'";
        }
        //商户id
        if($merchant != 0){
            if(!empty($sql)){
                $sql .= ' and uid='.$merchant;
            }else{
                $sql = 'uid='.$merchant;
            }            
        }
        //时间
        if(!empty($sql)){
            $sql .= " and paytime >= ".$stime." and paytime <= ".$etime;
        }else{
            $sql = "paytime >= ".$stime." and paytime <= ".$etime;
        }
        
        
        dataRecodes('订单筛选sql', $sql);
        
        $m = D('Manage/Order');
        if($sql){
            $order = $m->getAgentOrders(session('SX_USERS.userId'), 0, $sql);
        }else{
            $order = $m->getAgentAll(session('SX_USERS.userId'));
        }
        //订单统计
        $totalMoney = 0;
        $totalNum = 0;
        $weixinMoney = 0;
        $weixinNum = 0;
        $alipayMoney = 0;
        $alipayNum = 0;        
        foreach ($order as $value) {
            if($value['pay_way'] == 'weixin' and $value['ispay'] == 1 and $value['refund'] == 0){
                $weixinMoney += $value['goods_price'];
                $weixinNum += 1;
            }elseif($value['pay_way'] == 'alipay' and $value['ispay'] == 1 and $value['refund'] == 0){
                $alipayMoney += $value['goods_price'];
                $alipayNum += 1;
            }else{
                if($value['ispay'] == 1 and $value['refund'] == 0){
                    $totalMoney += $value['goods_price'];
                    $totalNum += 1;
                }                
            }            
        }
        $totalMoney += $weixinMoney + $alipayMoney;
        $totalNum += $weixinNum + $alipayNum;
        
        $this->assign('paytype', $paytype);
        $this->assign('merchant', $merchant);
        $this->assign('starttime',$starttime);
        $this->assign('endtime',$endtime);
        $this->assign('merchantName', $merchantName);
        $this->assign('merchants', $merchants);
        $this->assign('order',$order);
        $this->assign('totalMoney', $totalMoney);
        $this->assign('totalNum', $totalNum);
        $this->assign('weixinMoney', $weixinMoney);
        $this->assign('weixinNum', $weixinNum);
        $this->assign('alipayMoney', $alipayMoney);
        $this->assign('alipayNum', $alipayNum);
        $this->display("orderLists");
    }
    

    /**
     * 检查商户账户是否存在
     */
    public function checkLoginKey(){
        $this->isAjaxLogin();
        $this->checkAjaxPrivelege(1);
        $m = D('Manage/Users');
        $rs = $m->checkLoginKey(I('account'));
        $this->ajaxReturn($rs);
    }

    /**
     * 检查商户手机号是否存在
     */
    public function checkPhone(){
        $this->isAjaxLogin();
        $this->checkAjaxPrivelege(1);
        $m = D('Manage/Users');
        $rs = $m->checkPhone(I('phone'));
        $this->ajaxReturn($rs);
    }

    /**
     * 添加商户
     */
    public function merchantsAdd(){
        $this->isLogin();
        $this->checkPrivelege(1);
        $m = D('Manage/Users');
        $rs = $m->merchantsAdd();
        if($rs['status']==1){
            $tip['info'] = "商户添加成功";
        }else{
            $tip['info'] = "商户添加失败";
        }
        $tip['url'] = U("Manage/Agent/merchants");
        $this->assign('tip',$tip);
        $this->display("Public/tip");
    }
}