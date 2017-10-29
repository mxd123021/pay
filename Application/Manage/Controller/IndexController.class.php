<?php
namespace Manage\Controller;
class IndexController extends BaseController {
	/**
	 * 显示后台首页
	 */
    public function index(){
    	$this->isLogin();
        $userId = session('SX_USERS.userId');
        $usId = session('SX_USERS.usId');
        $m = D('Manage/Users');
	    $configs = $m->get($userId);
        $c = D('SX/Configs');
        $sysconfigs = $c->loadConfigs();
        $o = D('Manage/Order');
        $b = D('Manage/Bindweixin');
        $month = intval(date('m'));
        switch($month){
            case $month<=7:
                $months['m'] = array('01','02','03','04','05','06','07');
            break;
            default:
                $months['m'] = array($month-6,$month-5,$month-4,$month-3,$month-2,$month-1,$month);
            break;
        }
        //判断是商户还是员工登陆
        if(empty($usId)){//商户
            $f = D('Manage/Financial');
            $money = $f->getCurmoney(session('SX_USERS.userId'));

            $s = D('Manage/Stores');
	        //检查是否配置信息
	        $data = array();
            $data['isbind'] = 0;
	        $data['weixin'] = 0;
	        $data['jd'] = 0;
	        
            //检查微信是否绑定
            $isbind = $b->checkBinduser(session('SX_USERS.userId'));
            if($isbind == -1){
                $data['isbind'] = 1;
                $data['jd']++;
            }

	        //判断是否为代收用户
	        if($configs['wx_issp'] == 2){
	            $data['weixin'] = 1;
	            $data['jd']++;
	        }else if($configs['wx_issp'] == 1){
	            if(!empty($configs['wx_mchId'])){
	                    $data['weixin'] = 1;
	                    $data['jd']++;
	            }
	        }else{
                if(!empty($configs['wx_appId']) && !empty($configs['wx_appSecret']) && !empty($configs['wx_mchId']) && !empty($configs['wx_apiSecret'])){
                        $data['weixin'] = 1;
                        $data['jd']++;
                }
            }

            //今年订单数
            $data['curTotal'] = $o->yearOrders($userId,date('Y'),"count");
            //去年订单数
            $data['lastTotal'] = $o->yearOrders($userId,date('Y',strtotime("-1 year")),"count");
            //订单同期增长率
            $data['perOrder'] = intval(($data['curTotal'] - $data['lastTotal']) / $data['lastTotal'] * 100);

            //当月订单数
            $data['curmTotal'] = $o->monthOrders($userId,date('Y-m'),"count");
            //上月订单数
            $data['lastmOrder'] = $o->monthOrders($userId,date('Y-m',strtotime("-1 month")),"count");

            //当天订单收入
            $data['curmIncome'] = $o->dayIncome($userId,date('Y-m-d'));
            //昨天订单收入
            $data['lastmIncome'] = $o->dayIncome($userId,date('Y-m-d',strtotime("-1 day")));

            //收入同期增长率
            $data['perIncome'] = intval(($data['curmIncome'] - $data['lastmIncome']) / $data['lastmIncome'] * 100);

            //统计半年内的数据
            for($i=0;$i<7;$i++){
                $mon = $months['m'][$i];
                if($mon <10){
                    $mon = '0'.$mon;
                }
                $months['month'][$i] = $mon."月";
                $months['price'][$i] = $o->monthIncome($userId,date('Y-'.$mon));
                $months['total'][$i] = $o->monthOrders($userId,date('Y-'.$mon),"count");
            }

	        //检查是否添加门店
	        $data['Stores'] = $s->countStores(session('SX_USERS.userId'));
	        if($data['Stores'] > 0){
	            $data['jd']++;
	        }
	        //检查是否添加员工
	        $u = D('Manage/Ustaffs');
	        $data['Ustaffs'] = $u->countStaffs(session('SX_USERS.userId'));
	        if($data['Ustaffs'] > 0){
	            $data['jd']++;
	        }

            $this->assign('sysconfigs',$sysconfigs);
	        $this->assign('configs',$configs);
	        $this->assign('data',$data);
            $this->assign('months',$months);
	        $this->display("index");
    	}else{//员工
            $u = D('Manage/Ustaffs');
            $ustaffs = $u->getUser($usId);
            
            $s = D('Manage/Stores');
            $stores = $s->getStore($ustaffs['storeId']);

            //员工门店信息获取失败处理
            if(empty($stores)){
                $tip['url'] = U("Manage/Index/logout");
                $tip['info'] = "该员工所属门店已删除，请重新分配";
                $this->assign('tip',$tip);
                $this->display("Public/tip");
                exit;
            }
            //今年订单数
            $data['curTotal'] = $o->yearOrders($userId,date('Y'),"count","eid=".$usId);
            //去年订单数
            $data['lastTotal'] = $o->yearOrders($userId,date('Y',strtotime("-1 year")),"count","eid=".$usId);
            //订单同期增长率
            $data['perOrder'] = intval(($data['curTotal'] - $data['lastTotal']) / $data['lastTotal'] * 100);

            //当月订单数
            $data['curmTotal'] = $o->monthOrders($userId,date('Y-m'),"count","eid=".$usId);
            //上月订单数
            $data['lastmOrder'] = $o->monthOrders($userId,date('Y-m',strtotime("-1 month")),"count","eid=".$usId);

            //当天订单收入
            $data['curmIncome'] = $o->dayIncome($userId,date('Y-m-d'),"eid=".$usId);
            //昨天订单收入
            $data['lastmIncome'] = $o->dayIncome($userId,date('Y-m-d',strtotime("-1 day")),"eid=".$usId);
            //收入同期增长率
            $data['perIncome'] = intval(($data['curmIncome'] - $data['lastmIncome']) / $data['lastmIncome'] * 100);

            //统计半年内的数据
            for($i=0;$i<7;$i++){
                $mon = $months['m'][$i];
                if($mon <10){
                    $mon = '0'.$mon;
                }
                $months['month'][$i] = $mon."月";
                $months['price'][$i] = $o->monthIncome($userId,date('Y-'.$mon),"storeId=".$ustaffs['storeId']);
                $months['total'][$i] = $o->monthOrders($userId,date('Y-'.$mon),"count","storeId=".$ustaffs['storeId']);
            }

            //检查员工微信是否绑定
            $wxinfo = $b->get(session('SX_USERS.userId'),"usId=".$usId." and type=2");

            $this->assign('sysconfigs',$sysconfigs);
    		$this->assign('configs',$configs);
            $this->assign('data',$data);
            $this->assign('wxinfo',$wxinfo);
            $this->assign('ustaffs',$ustaffs);
    		$this->assign('stores',$stores);
            $this->assign('months',$months);
    		$this->display("staffindex");
    	}
    }

    /**
     * 显示登陆页面
     */
    public function toLogin(){
    	$this->display("login");
    }

    /**
     * 注册界面
     * 
     */
    public function register(){
        $tgId = I("tgId",0);
        $tgemId = I("tgemId",0);
        $this->assign('tgId',$tgId);
        $this->assign('tgemId',$tgemId);
        $this->display('register');
    }

    /**
     * 新用户注册
     */
    public function toRegist(){
        $m = D('Manage/Users');
        $res = array();
        $type = I("type");
        if($type!="reg"){ 
            $res['status'] = -4;
        }else{          
            $res = $m->regist();
            if($res['userId']>0){//注册成功         
                //加载用户信息                
                $user = $m->get($res['userId']);
                if(!empty($user))session('SX_USERS',$user);
            }
        }
        $tip['url'] = U("Manage/Index/register/tgId/".I("tgId",0));
        switch($res['status']){
            case 1:
                $tip['info'] = "注册成功";
                $tip['url'] = U("Index/index");
            break;
            case -1:
                $tip['info'] = "注册失败";
            break;
            case -2:
                $tip['info'] = "该账号已存在";
            break;
            case -3:
                $tip['info'] = "两次密码不一致";
            break;
            case -4:
                $tip['info'] = "非法操作";
            break;
            case -6:
                $tip['info'] = "请同意注册协议";
            break;
            case -7:
                $tip['info'] = "请完善所有资料";
            break;
            case -8:
                $tip['info'] = "手机号码已存在";
            break;
        }
        $this->assign('tip',$tip);
        $this->display("Public/tip");
    }


    /**
     * 管理员登录
     */
    public function login(){
    	$m = D('Manage/Users');
    	$rs = $m->checkLogin();
    	if($rs['status']==1){
    		session('SX_USERS',$rs['user']);
    		unset($rs['user']);
    	}
    	$this->ajaxReturn($rs);
    }

    /**
     * 离开系统
     */
    public function logout(){
        session('SX_USERS',null);
        $tip['info'] = "退出成功";
        $tip['url'] = U("toLogin");
        $this->assign('tip',$tip);
        $this->display("Public/tip");
    }

    /**
     * 修改微信收银员通知接收状态
     */
    public function editisSend(){
        $this->isAjaxLogin();
        $m = D('Manage/Bindweixin');
        $rs = $m->editisSend();
        $this->ajaxReturn($rs);
    }

    /**
     * 微信收银员解除绑定
     */
    public function removeBind(){
        $this->isAjaxLogin();
        $m = D('Manage/Bindweixin');
        $rs = $m->removeBind();
        if($rs['status'] == 1){
            $type = I('type'); //1 PC端发送 2 微信手机端发送
            if($type == 2){
                session('SX_USERS',null);     
            }
        }
        $this->ajaxReturn($rs);
    }
    /*
     * 交接班结算
     */
    public function balance(){
        $this->isAjaxLogin();        
        $rs['status'] = 1;
        $rs['msg'] = '打印交接班成功';
        $m = D('Manage/Bindweixin');
        $user = $m->getUserById();
        if(!empty($user)){            
            $storeId = $user['storeId'];//门店id
            $usId = $user['usId'];//员工id
            $ustaff = D('Manage/Ustaffs')->where('usId='.$usId)->find();
            //查找结算记录
            $b = D('Manage/BalanceManage');
            $balanceInfo = $b->getBalanceInfoById($storeId, $usId);
            $printer_info = D('Manage/Printer')->getPrinterBySN($ustaff['printer_sn']);
//            if(!empty($printer_info)){
                if(!empty($balanceInfo)){
                    $stime = $balanceInfo['ebalanceTime'];
                    $o = D('Manage/Order');
                    //查找从上次结算时间到此刻的所有订单
                    $orderList = $o->selectOrdersByUsid($storeId, $usId, $stime, time());
                    if(!empty($orderList)){
                        //打印交接班结算单
                        $printer = A('Printer');
                        $printer->setPrinterInfo($printer_info['printer_sn'], $printer_info['printer_key'],$printer_info['printer_version']);
                        $data = $printer->setBalanceInfo($orderList, $balanceInfo, $printer_info['printer_version']);
                        $printer->toPrintBalance();
                        //更新交接班记录
                        $balanceInfo['storeId'] = $storeId;
                        $balanceInfo['usId'] = $usId;
                        $balanceInfo['totalMoney'] = $data['totalMoney'];
                        $balanceInfo['totalNum'] = $data['totalNum'];
                        $balanceInfo['weixinMoney'] = $data['weixinMoney'];
                        $balanceInfo['weixinNum'] = $data['weixinNum'];
                        $balanceInfo['aliMoney'] = $data['aliMoney'];
                        $balanceInfo['aliNum'] = $data['aliNum'];
                        $balanceInfo['refund_num'] = $data['refund_num'];
                        $balanceInfo['refund_fee'] = $data['refund_fee'];                        
                        $balanceInfo['sbalanceTime'] = $stime;
                        $balanceInfo['ebalanceTime'] = time();
                        $b->saveBalanceInfo($balanceInfo);
                        //发送结算模板消息                       
                        $store = D('Manage/Stores')->getStore($storeId); //获取商户信息
                        $balanceInfo['businessName'] = $store['business_name'];
                        $balanceInfo['branchName'] = $store['branch_name'];
                        $balanceInfo['userName'] = $ustaff['userName'];
                        $balanceInfo['userId'] = $ustaff['userId'];
                        $this->sendWxBalancemessage($balanceInfo);
//                        if(109 == $usId){
                            D('Manage/Ustaffs')->updateBalanceId($usId,$balanceInfo['id']);//更新记录           
//                        }
                             
                    }else{
                        $rs['status'] = -2;
                        $rs['msg'] = '无订单可结账！';
                    }
                    
                }else{
                    $stime = 0;
                    $o = D('Manage/Order');
                    //查找从上次结算时间到此刻的所有订单
                    $orderList = $o->selectOrdersByUsid($storeId, $usId, $stime, time());
                    if(!empty($orderList)){
                        //打印交接班结算单
                        $printer = A('Printer');
                        $printer->setPrinterInfo($printer_info['printer_sn'], $printer_info['printer_key'], $printer_info['printer_version']);
                        $data = $printer->setBalanceInfo($orderList, array(), $printer_info['printer_version']);
                        $printer->toPrintBalance();
                        //保存交接班记录
                        $balanceInfo['storeId'] = $storeId;
                        $balanceInfo['usId'] = $usId;
                        $balanceInfo['totalMoney'] = $data['totalMoney'];
                        $balanceInfo['totalNum'] = $data['totalNum'];
                        $balanceInfo['weixinMoney'] = $data['weixinMoney'];
                        $balanceInfo['weixinNum'] = $data['weixinNum'];
                        $balanceInfo['aliMoney'] = $data['aliMoney'];
                        $balanceInfo['aliNum'] = $data['aliNum'];
                        $balanceInfo['refund_num'] = $data['refund_num'];
                        $balanceInfo['refund_fee'] = $data['refund_fee']; 
                        $balanceInfo['sbalanceTime'] = $stime;
                        $balanceInfo['ebalanceTime'] = time();
                        $b->addBalanceInfo($balanceInfo);                        
                        //发送结算模板消息                       
                        $store = D('Manage/Stores')->getStore($storeId); //获取商户信息
                        $balanceInfo['businessName'] = $store['business_name'];
                        $balanceInfo['branchName'] = $store['branch_name'];
                        $balanceInfo['userName'] = $ustaff['userName'];
                        $balanceInfo['userId'] = $ustaff['userId'];
                        $this->sendWxBalancemessage($balanceInfo);                        
                    }else{
                        $rs['status'] = -2;
                        $rs['msg'] = '无订单可结账！';
                    }
                }               
//            }else{
//                $rs['status'] = -1;
//            }
        }else{
            $rs['status'] = -1;//非法用户或未绑定打印机，无法进行交接班数据打印
        }
        
        $this->ajaxReturn($rs);
    }
    
    /*
     * 交接班
     */
    public function toBalance(){
        $this->isAjaxLogin();        
        $rs['status'] = 1;
        $rs['msg'] = '打印交接班成功';
        $m = D('Manage/Bindweixin');
        $user = $m->getUserById();
        dataRecodes('打印交接班结账单', $user);
        if(!empty($user)){            
            $storeId = $user['storeId'];//门店id
            $usId = $user['usId'];//员工id
            $ustaff = D('Manage/Ustaffs')->where('usId='.$usId)->find();
            $b = D('Manage/BalanceManage');
            $balanceInfo = $b->getBalanceById($ustaff['balanceId']);
            //兼容原来的
            if(empty($balanceInfo)){
                $balanceInfo = $b->getBalanceInfoById($storeId, $usId);
            }
            
            $printer_info = D('Manage/Printer')->getPrinterBySN($ustaff['printer_sn']);//打印机信息
            //获取最新订单
            
            $stime = !empty($balanceInfo) ? $balanceInfo['ebalanceTime']:0;
            $o = D('Manage/Order');
            //查找从上次结算时间到此刻的所有订单
            $orderList = $o->selectOrdersByUsid($storeId, $usId, $stime, time());
            if(!empty($orderList)){
                //打印交接班结算单
                $printer = A('Printer');
                $printer->setPrinterInfo($printer_info['printer_sn'], $printer_info['printer_key'], $printer_info['printer_version']);
                $data = $printer->setBalanceInfo($orderList, $balanceInfo, $printer_info['printer_version']);
                $printer->toPrintBalance();
                dataRecodes('打印交接班结账单1', $data);
                //保存交接班记录
                $balanceInfo['storeId'] = $storeId;
                $balanceInfo['usId'] = $usId;
                $balanceInfo['totalMoney'] = $data['totalMoney'];
                $balanceInfo['totalNum'] = $data['totalNum'];
                $balanceInfo['weixinMoney'] = $data['weixinMoney'];
                $balanceInfo['weixinNum'] = $data['weixinNum'];
                $balanceInfo['aliMoney'] = $data['aliMoney'];
                $balanceInfo['aliNum'] = $data['aliNum'];
                $balanceInfo['refund_num'] = $data['refund_num'];
                $balanceInfo['refund_fee'] = $data['refund_fee']; 
                $balanceInfo['sbalanceTime'] = $stime;
                $balanceInfo['ebalanceTime'] = time();
                $rs = $b->addBalanceInfo($balanceInfo); //增加交接班记录                 
                dataRecodes('打印交接班结账单2', $rs);
                D('Manage/Ustaffs')->updateBalanceId($usId,$rs['id']);//更新记录          
                dataRecodes('打印交接班结账单21', $balanceInfo);           
                //发送结算模板消息                       
                $store = D('Manage/Stores')->getStore($storeId); //获取商户信息
                $balanceInfo['businessName'] = $store['business_name'];
                $balanceInfo['branchName'] = $store['branch_name'];
                $balanceInfo['userName'] = $ustaff['userName'];
                $balanceInfo['userId'] = $ustaff['userId'];
                $this->sendWxBalancemessage($balanceInfo);
                dataRecodes('打印交接班结账单3', $balanceInfo);
//            }elseif ($ustaff['balanceId'] > 0) {
//                //无订单，提示是否重新打印上一个交接班记录
//                $rs['status'] = 2;                
//                $rs['bId'] = $ustaff['balanceId'];
//                $rs['msg'] = '是否重新打印交接班记录？';
            }else{
                $rs['status'] = -2;
                $rs['msg'] = '无订单可结账！';
            }
        }else{
            $rs['status'] = -1;//非法用户或未绑定打印机，无法进行交接班数据打印
        }
        dataRecodes('打印交接班结账单5', $rs);
        $this->ajaxReturn($rs);
    }
    /*
     * 重新打印结算订单记录
     */
    public function rePrintBalance(){
        $this->isAjaxLogin();        
        $rs['status'] = 1;
        $rs['msg'] = '打印交接班成功';
        $balanceInfo = D('Manage/BalanceManage')->getBalanceById(I('bId'));
        dataRecodes('rePrintBalance', $balanceInfo);
        if(!empty($balanceInfo)){
            $ustaff = D('Manage/Ustaffs')->where('usId='.$balanceInfo['usId'])->find();        
            $printer_info = D('Manage/Printer')->getPrinterBySN($ustaff['printer_sn']);//打印机信息
            //查找所有订单
            $o = D('Manage/Order');
            $orderList = $o->selectOrdersByUsid($balanceInfo['storeId'], $balanceInfo['usId'], $balanceInfo['sbalanceTime'], $balanceInfo['ebalanceTime']);
            dataRecodes('rePrintBalance0', $balanceInfo);
            //打印交接班结算单
            $printer = A('Printer');
            $printer->setPrinterInfo($printer_info['printer_sn'], $printer_info['printer_key'], $printer_info['printer_version']);
            $data = $printer->setBalanceInfo($orderList, $balanceInfo, $printer_info['printer_version'], 2);
            $printer->toPrintBalance();        
            dataRecodes('rePrintBalance1', $data);
            //发送结算模板消息                       
            $store = D('Manage/Stores')->getStore($balanceInfo['storeId']); //获取商户信息
            $balanceInfo['businessName'] = $store['business_name'];
            $balanceInfo['branchName'] = $store['branch_name'];
            $balanceInfo['userName'] = $ustaff['userName'];
            $balanceInfo['userId'] = $ustaff['userId'];
            dataRecodes('rePrintBalance2', $balanceInfo);
            $this->sendWxBalancemessage($balanceInfo);
        }else{
            $rs['status'] = -1;
            $rs['msg'] = '交接班失败，不存在记录！';
        }
       dataRecodes('rePrintBalance', $rs);
        $this->ajaxReturn($rs);
    }
    
    /*
     * 交接班
     */
    public function balancelist(){
        $this->isAjaxLogin();        
        $rs['status'] = 1;
        $rs['msg'] = '打印交接班成功';
        $m = D('Manage/Bindweixin');
        $user = session('SX_USERS');//$m->getUserById();
        $c = D('SX/Configs');
        $configs = $c->loadConfigs(); 
        $url = U("Manage/Index/balancelist@".C('SITE_URL'));
        $openid = $this->GetOpenid($url,$configs['wx_appId'],$configs['wx_appSecret']);

        if(!empty($openid)){
            $b = D('Manage/Bindweixin');
            $info = $b->getopenid($openid);        
        }
            
        dataRecodes('balancelist', $user);
        if(!empty($user)){           
            $storeId = $user['storeId'];//门店id
            $usId = $user['usId'];//员工id
//            $ustaff = D('Manage/Ustaffs')->where('usId='.$usId)->find();
            $b = D('Manage/BalanceManage');
            $balances = $b->getAll($usId, 5);            
        }
        $this->assign('balances', $balances);
        $this->assign('user', $user);
        $this->assign('info', $info);
        $this->display("balancelist");
    }   

    
    /**
     * 测试支付模板信息
     */
    public function testsend(){
        $post['userId'] = 1;
        $post['usId'] = 0;
        $post['storeId'] = 0;
        $post['price'] = 1000;
        $post['body'] = "测试信息";
        print_r($this->sendWxmessage($post));
    }
}