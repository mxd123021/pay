<?php
namespace Manage\Controller;
class MoneyController extends BaseController {
    /**
     * 显示付款界面
     */
    public function payment(){
        $this->isLogin();
        $this->checkPrivelege("orderlist");
        $m = D('Manage/Financial');
        $money = $m->getCurmoney(session('SX_USERS.userId'));
        if(empty($money)){
            $money['curmoney'] = "0.00";
        }
        $this->assign("money",$money);
        $this->display("payment");
    }

    /**
     * 显示受理管理界面
     */
    public function account(){
        $this->isLogin();
        $this->checkPrivelege("orderlist");
        $c = D('SX/Configs');
        $configs = $c->loadConfigs();
        $u = D('Manage/Users');
        $users = $u->get(session('SX_USERS.userId'));
        $datetype = I('datetype');
        switch ($datetype) {
            case 'tdy': //今日数据
                $starttime = date('Y-m-d');
                $endtime = date('Y-m-d');
                $stime = strtotime($starttime);
                $etime = strtotime("+1days", strtotime($endtime));
                break;
            case 'ydy': //昨日数据 
                $starttime = date('Y-m-d',strtotime("-1days"));
                $endtime = date('Y-m-d',strtotime("-1days"));
                $stime = strtotime($starttime);
                $etime = strtotime("+1days", strtotime($endtime));
                break;  
            default: //默认最近一周数据或有时间段查询操作
                $stime = I('stime',0);
                $etime = I('etime',0);
                if($stime == 0 && $etime == 0){
                    $datetype = "wk";
                }
                $starttime = $stime != 0 ? $stime : date('Y-m-d',strtotime('-6days'));
                $stime = $stime != 0 ? strtotime($stime) : strtotime(date('Y-m-d',strtotime('-6days')));
                $endtime = $etime != 0 ? $etime : date('Y-m-d');
                $etime = $etime != 0 ? strtotime("+1days", strtotime($etime)) : strtotime(date('Y-m-d',strtotime('+1days')));
                break;
        }
        $sql = " and stime >= ".$stime." and stime <= ".$etime;

        $gdPay = R('SX/Api/gd_getPay',array($configs,$users['gd_mchId']));
        if($gdPay['status'] == "0"){
            $ewm = $gdPay['respurl'];
        }else{
            $ewm = !empty($gdPay['message'])?$gdPay['message']:$gdPay['errormsg'];
            if(empty($ewm)){
                $ewm = "系统繁忙请刷新重试";
            }
        }

        $rate = json_decode($users['gd_rate'],true);
        $this->assign("users",$users);
        $this->assign('rate',$rate);
        $this->assign('ewm',$ewm);
        $this->assign('datetype',$datetype);
        $this->assign('starttime',$starttime);
        $this->assign('endtime',$endtime);
        $this->assign('configs',$configs);
        $this->display("account");
    }

    /**
     * 显示代收管理界面
     */
    public function dsaccount(){
        $this->isLogin();
        $this->checkPrivelege("orderlist");
        $datetype = I('datetype');
        switch ($datetype) {
            case 'tdy': //今日数据
                $starttime = date('Y-m-d');
                $endtime = date('Y-m-d');
                $stime = strtotime($starttime);
                $etime = strtotime("+1days", strtotime($endtime));
                break;
            case 'ydy': //昨日数据 
                $starttime = date('Y-m-d',strtotime("-1days"));
                $endtime = date('Y-m-d',strtotime("-1days"));
                $stime = strtotime($starttime);
                $etime = strtotime("+1days", strtotime($endtime));
                break;  
            default: //默认最近一周数据或有时间段查询操作
                $stime = I('stime',0);
                $etime = I('etime',0);
                if($stime == 0 && $etime == 0){
                    $datetype = "wk";
                }
                $starttime = $stime != 0 ? $stime : date('Y-m-d',strtotime('-6days'));
                $stime = $stime != 0 ? strtotime($stime) : strtotime(date('Y-m-d',strtotime('-6days')));
                $endtime = $etime != 0 ? $etime : date('Y-m-d');
                $etime = $etime != 0 ? strtotime("+1days", strtotime($etime)) : strtotime(date('Y-m-d',strtotime('+1days')));
                break;
        }
        $sql = " and stime >= ".$stime." and stime <= ".$etime;
        $w = D('Manage/Withdraw');
        $withdraw = $w->getAll(session('SX_USERS.userId'),$limit=0,$sql);

        $m = D('Manage/Financial');
        $money = $m->getCurmoney(session('SX_USERS.userId'));

        $b = D('Manage/Bindweixin');
        $bind = $b->get(session('SX_USERS.userId'),"type = 1");

        $this->assign("money",$money);
        $this->assign('datetype',$datetype);
        $this->assign('withdraw',$withdraw);
        $this->assign('starttime',$starttime);
        $this->assign('endtime',$endtime);
        $this->assign('bind',$bind);
        $this->display("dsaccount");
    }

    /**
     * 显示财务明细界面
     */
    public function paylist(){
        $this->isLogin();
        $this->checkPrivelege("orderlist");
        $m = D('Manage/Findetails');
        $page = $m->getDetails(session('SX_USERS.userId'));
        $pager = new \Think\Page($page['total'],$page['pageSize']);// 实例化分页类 传入总记录数和每页显示的记录数
        $page['pager'] = $pager->show();
        $this->assign('Page',$page);
        $this->display("paylist");
    }

    /**
     * 获取立刻支付二维码
     */
    public function getEwm(){
        $this->isAjaxLogin();
        $rd = array('status'=>-1);
        $c = D('SX/Configs');
        $configs = $c->loadConfigs();
        $m = D('Manage/Users');
        $users = $m->get(session('SX_USERS.userId'));
        $post_data = array();
        $post_data['mch_id'] = $configs['ds_wx_mchId'];
        $token = md5($users['userId'].date("YmdHis")); //充值查询码
        $post_data['appid'] = $configs['ds_wx_appId'];
        $post_data['nonce_str'] = $this->getWxNonceStr();
        $post_data['body'] = "平台预付款充值";
        $post_data['attach'] = $users['userId'].",".$users['parentId'].",".$users['tgId'].",".$users['tgemId'].",".$users['loginName'].","."即时扫码支付,".$token;
        $post_data['out_trade_no'] = $users['userId'].$post_data['mch_id'].date("YmdHis");  //商品订单号  随机生成
        $post_data['total_fee'] =I("tprice",0) * 100; 	//总金额
        $post_data['spbill_create_ip'] = $_SERVER['REMOTE_ADDR'];	//终端IP
        $post_data['notify_url'] = U("SX/Order/addWxCzOrder@".C('SITE_URL'));	//通知地址
        $post_data['trade_type'] = "NATIVE";	//交易类型
        $apikey = $configs['ds_wx_apiSecret'];
        $post_data['sign'] =$this->MakeWxSign($post_data,$apikey);	//微信签名
        $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
        $result = $this->wx_xmlpost($url,$this->ToXml($post_data));  //微信xml提交方式
        $data = $this->FromXml($result);
        $qrcode=$data['code_url'];
        if(!empty($qrcode)){
            $rd['qrcode'] = $qrcode;
            $rd['token'] = $token;
            $rd['price'] = I("tprice",0) * 100;
            $rd['status'] = 1;
        }else{
            $rd['msg'] = $data['return_msg'];
        }
        $this->ajaxReturn($rd);
    }

    /**
     * 查询充值是否成功
     */
    public function tokenquery(){
        $this->isAjaxLogin();
        $rd = array('status'=>-1);
        $m = D('SX/Czorder');
        $token = $m->getToken(I('token'));
        if(!empty($token)){
            $rd['status'] = 1;
        }
        $this->ajaxReturn($rd);
    }

    /**
     * 添加提现申请
     */
    public function addWithdraw(){
        $this->isAjaxLogin();
        $rd = array('status'=>-1);
        $f = D('Manage/Financial');
        $money = $f->getCurmoney(session('SX_USERS.userId'));
        if($money['dscurmoney'] >=500){//可提现金额大于等于500才允许提现
            $m = D('Manage/Withdraw');
            $rd = $m->addWithdraw($money);//添加提现申请
            if($rd['status']==1){//申请成功扣除当前可提现金额，增加待支付金额
                $f->reWithdraw(session('SX_USERS.userId'),$rd);
            }
        }
        $this->ajaxReturn($rd);
    }
}