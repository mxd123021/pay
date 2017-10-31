<?php
namespace Manage\Controller;
class UsersController extends BaseController {
    /**
     * 显示修改密码页面
     */
    public function modifypwd(){
        $this->isLogin();
        $this->display("modifypwd");
    }

    /**
     * 显示门店管理页面
     */
    public function storefront(){
        $this->isLogin();
        $u = D('Manage/Users');
        $m = D('Manage/RelationMerchants');

        $stores = $m->queryByPage(session('SX_USERS.userId'));
        $store = new \Think\Page($stores['total'],$stores['pageSize']);
        $stores['pager'] = $store->show();

        $this->assign('configs',$u->get(session('SX_USERS.userId')));
        $this->assign('stores',$stores);
        $this->display("storefront");
    }

    /**
     * 显示门店详细页面
     */
    public function storedetail(){
        $this->isLogin();
        $m = D('Manage/Stores');
        $store = $m->getStore();
        $photo_list = $store['photo_url'];
        $photo_list = explode("#", $photo_list);
        $this->assign('store',$store);
        $this->assign('photo_list',$photo_list);
        $this->display("storedetail");
    }

    /**
     * 显示员工管理页面
     */
    public function employers(){
        $this->isLogin();
        $m = D('Manage/Stores');  
        $stores = $m->get(session('SX_USERS.userId'));
        $u = D('Manage/Ustaffs');
        $employees = $u->get(session('SX_USERS.userId'));

        if(!empty($stores)){
            foreach ($stores as $key => $value) {
                $store[$value['storeId']] = $value['business_name'].$value['branch_name'];
            }
        }

        $this->assign('stores',$stores);
        $this->assign('store',$store);
        $this->assign('employees',$employees);
        $this->display("employers");
    }

    /**
     * 显示个人信息页面
     */
    public function userInfo(){
        $this->isLogin();
        $c = D('SX/Configs');
        $configs = $c->loadConfigs();
        $m = D('Manage/Users');
        $userinfo = $m->get(session('SX_USERS.userId'));
        $b = D('Manage/Bindweixin');
        $wxinfo = $b->get(session('SX_USERS.userId'),"type=1");
        $this->assign('configs',$configs);
        $this->assign('userinfo',$userinfo);
        $this->assign('wxinfo',$wxinfo);
        $this->display("userInfo");
    }

    /**
     * 显示实名认证页面
     */
    public function realname(){
        $this->isLogin();
        $c = D('SX/Configs');
        $configs = $c->loadConfigs();
        $m = D('Manage/Users');
        $userinfo = $m->get(session('SX_USERS.userId'));
        $d = D('Manage/GdDistrict');
        $district = $d->getProvince();
        $k = D('Manage/GdBank');
        $bank = $k->get();
        $this->assign('configs',$configs);
        $this->assign('userinfo',$userinfo);
        $this->assign('district',$district);
        $this->assign('bank',$bank);
        if(!empty($userinfo['fid'])){ //获取二级地区信息
            $district2 = $d->getCity($userinfo['fid']);
            $this->assign('district2',$district2);
        }
        if(!empty($userinfo['incode'])){ //获取行业类别
            $i = D('Manage/GdIncategory');
            $incategory = $i->getId($userinfo['incode']);
            $incategory['namearr'] = explode("-",$incategory['name']);
            $incategory['level2'] = $i->get($incategory['namearr'][0]);
            $incategory['level3'] = $i->get($incategory['namearr'][0]."-".$incategory['namearr'][1]);
            $this->assign('incategory',$incategory);
        }
        $this->display("realname");
    }

    /**
     * 显示员工编辑页面
     */
    public function employersEdit(){
        $this->isLogin();
        $m = D('Manage/Stores');  
        $stores = $m->get(session('SX_USERS.userId'));
        $u = D('Manage/Ustaffs');  
        $employees = $u->getUser();

        if($employees['userId'] != session('SX_USERS.userId')){
        	$tip['info'] = "没有权限";
        	$tip['url'] = U("Manage/Users/employers");
       		$this->assign('tip',$tip);
        	$this->display("Public/tip");
        }
        $this->assign('stores',$stores);
        $this->assign('employees',$employees);
        $this->display("employersEdit");
    }

    /**
     * 显示创建门店页面
     */
    public function createStore(){
        $this->isLogin();
        $m = D('Manage/District');  
        $c = D('Manage/CashierCategory');
        $this->assign('districts',$m->getDistrict());
        $this->assign('categorys',$c->getCategory());
        $this->display("createStore");
    }

    public function loginLog(){
        $uid = $this->getUserId();
        $m = M('log_user_logins');
        $list = $m->where(sprintf('userId=%d',$uid))->field([
            'loginTime',
            'loginIp',
        ])->select();
        $this->assign('items',$list);
        $this->display('loginLog');
    }

    /**
     * 修改密码
     */
    public function editPass(){
        $this->isLogin();
        $m = D('Manage/Users');
        $rs = $m->editPass(session('SX_USERS.userId'));
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
     * 上传图片文件
     */
    public function img_Upload(){
        $this->isAjaxLogin();
        $id = htmlspecialchars($_SERVER['QUERY_STRING']);
        $id = is_numeric($id)?$id:session('SX_USERS.userId');
        $this->ajaxReturn($this->uploadPic(512000,"info/User/".$id));
    }

   /**
     * 上传pem文件
     */
    public function pem_Upload(){
        $this->isAjaxLogin();
        $this->ajaxReturn($this->uploadFile(204800,array('pem'),"pem/User/".session('SX_USERS.userId')));
    }

    /**
     * 显示支付配置界面
     */
    public function payConfig(){
        $this->isLogin();
        $m = D('Manage/Users');
        $this->assign('configs',$m->get(session('SX_USERS.userId')));
        $this->display("payConfig");
    }

    /**
     * 保存支付信息
     */
    public function savePayinfo(){
        $this->isAjaxLogin();
        $m = D('Manage/Users');
        $rs = $m->savePayinfo();
        $this->ajaxReturn($rs);
    }

    /**
     * 微信会员中心
     */
    public function wxucenter(){
        session('SX_USERS',null);
        $c = D('SX/Configs');
        $configs = $c->loadConfigs(); 

        $url = U("Manage/Users/wxucenter@".C('SITE_URL'));
        $openid = $this->GetOpenid($url,$configs['wx_appId'],$configs['wx_appSecret']);

        if(!empty($openid)){
            $b = D('Manage/Bindweixin');
            $info = $b->getopenid($openid);
            if(!empty($info)){
                $s = session('SX_USERS');
                if(empty($s)){ //判断是否登陆
                    if($info['usId'] == 0){ //商户登陆
                        $us = D('Manage/Users');
                        $us->logining($info['userId']);
                    }else{ //员工登陆
                        $ust = D('Manage/Ustaffs');
                        $ust->logining($info['usId']);
                    }
                    $s = session('SX_USERS');
                }
                $ewm = U("Manage/Wxcashier/autopay@".C('SITE_URL'),array('userId'=>session('SX_USERS.userId'),'usId'=>session('SX_USERS.usId'),'storeId'=>session('SX_USERS.storeId'),'parentId'=>session('SX_USERS.parentId')));
                $gdPay = R('SX/Api/gd_getPay',array($configs,$s['gd_mchId']));
                if($gdPay['status'] == "0"){
                    $connection = $gdPay['respurl'];
                    $this->assign('connection',$connection);
                }else{
                    $errmsg = !empty($gdPay['message'])?$gdPay['message']:$gdPay['errormsg'];
                    if(empty($errmsg)){
                        $errmsg = "系统繁忙请刷新重试";
                    }
                    $this->assign('errmsg',$errmsg);
                }
                //获取最近10单订单
                $m = D('Manage/Order');
                $usId = $info['usId'];
                if(empty($usId)){
                    $order = $m->getAll(session('SX_USERS.userId'),30);
                }else{
                    $order = $m->getAll($usId,30,2); //1商户 2员工
                }
          
                $this->assign('order',$order);
                
                $this->assign('user',$s);
                $this->assign('info',$info);
                $this->assign('ewm',$ewm);
                $this->display("wxucenter");
            }else{
                header("Location:".U("Manage/Users/bindwx@".C('SITE_URL')));
            }
        }else{
            header("Location:".U("Manage/Users/wxucenter@".C('SITE_URL')));
        }
    }

    /**
     * 绑定商户微信信息
     */
    public function bindwx(){
        $c = D('SX/Configs');
        $configs = $c->loadConfigs();

        $url = U("Manage/Users/bindwx@".C('SITE_URL'));
        $openid = $this->GetOpenid($url,$configs['wx_appId'],$configs['wx_appSecret']);

        if(!empty($openid)){
            $b = D('Manage/Bindweixin');
            //检查当前openid是否绑定过
            $isbind = $b->checkBindopenid($openid);
            dataRecodes('in bindwx $isbind=', $isbind);
            dataRecodes('in bindwx $openid=', $openid);

            //没有绑定显示绑定页面 $isbind 1:没有绑定 -1:已被绑定
            dataRecodes('in bindwx configs=', $configs);
            if($isbind == 1){
                $token = $this->getWxToken($configs['wx_token'],$configs['wx_update'],$configs['wx_appId'],$configs['wx_appSecret'],2);//2为保存系统token
                dataRecodes('in bindwx $token=', $token);
                $wxinfo = $this->getWxUser($token,$openid,$configs);//获取用户信息
                dataRecodes('in bindwx $wxinfo=', $wxinfo);
                if($wxinfo['subscribe'] == 1){ //token过期
                    $type = 1;
                    $msg = "";
                    $this->assign('configs',$configs);
                    $this->assign('wxinfo',$wxinfo);
                }else if($wxinfo==-2){
                    $type = -1;
                    $msg = "token过期";
                }else{
                    $type = -1;
                    $msg = "您还没有关注".$wxinfo['siteName'].",请先关注！";
                }
            }else{
                //已绑定过微信
               header("Location:".U("Manage/Users/wxucenter@".C('SITE_URL')));
            }
            $this->assign('token',$token);
            $this->assign('type',$type);
            $this->assign('msg',$msg);
            $this->display("bindwx");
        }else{
            header("Location:".$url);
        }
    }

    /**
     * 保存商户绑定的微信信息
     */
    public function saveBindwx(){
        $data = I("data",'','urldecode');
        $loginName = $data['loginName'];
        $userPwd = $data['loginPwd'];
        $token = $data['token'];
        $type = $data['type'];
        $code = $data['code'];

        switch($type){
            case 1: //商户绑定
                $m = D('Manage/Users');
                //检查商户账号密码是否正确
                $rd = $m->checkBindwx($loginName,$userPwd,$code);
                if($rd['status']==1){
                    $b = D('Manage/Bindweixin');
                    //检查当前账号是否绑定过
                    $bindtype = $b->checkBinduser($rd['userId']); //-1:绑定过 1:没有绑定
                    if($bindtype == -1){
                        $rd['status'] = -2; //该账号已绑定过微信用户
                    }else{
                        $bindstatus = $b->saveBindwx($rd,$data,2); //1:update 2:insert
                        if($bindstatus == -1){
                            $rd['status'] = -3; //绑定失败
                        }else{
                            //系统向商户发送通知
                            $post['userId'] = $rd['userId'];
                            $post['userName'] = $loginName;
                            $post['userType'] = $type;
                            $this->sendWxbindmessage($post);
                        }
                    }    
                }   
            break;
            case 2: //员工绑定
                $m = D('Manage/Ustaffs');
                //检查员工账号密码是否正确
                $rd = $m->checkBindwx($loginName,$userPwd,$code);
                if($rd['status']==1){
                    $b = D('Manage/Bindweixin');
                    //检查当前门店的员工账号是否绑定过
                    $bindtype = 1;//允许一个门店二维码绑定多个微信用户//$b->checkusBinduser($rd); //-1:绑定过 1:没有绑定
                    if($bindtype == -1){
                        $rd['status'] = -2; //该账号已绑定过微信用户
                    }else{
                        $bindstatus = $b->saveBindwx($rd,$data,2); //1:update 2:insert
                        if($bindstatus == -1){
                            $rd['status'] = -3; //绑定失败
                        }else{
                            //系统向商户发送通知
                            $post['userId'] = $rd['userId'];
                            $post['usId'] = $rd['usId'];
                            $post['userName'] = $loginName;
                            $post['userType'] = $type;
                            $this->sendWxbindmessage($post);
                        }
                    }    
                }
            break;
        }
        $this->ajaxReturn($rd);
    }

    /**
     * 保存个人信息
     */
    public function saveUserInfo(){
        $this->isLogin();
        $m = D('Manage/Users');
        $rs = $m->saveUserInfo(session('SX_USERS.userId'));
        if($rs['status']==1){
            $tip['info'] = "个人信息保存成功";
        }else{
            $tip['info'] = "个人信息保存失败";
        }
        $tip['url'] = U("Manage/Users/userInfo");
        $this->assign('tip',$tip);
        $this->display("Public/tip");
    }

    /**
     * 保存实名认证信息
     */
    public function saverealInfo(){
        $this->isLogin();
        $m = D('Manage/Users');

        $type = I('type');
        if($type == "agent"){
            $userId = I('usId');
            $tip['url'] = U("Manage/Agent/merchants");
        }else{
            $userId = session('SX_USERS.userId');
            $tip['url'] = U("Manage/Users/realname");
        }

        $backcode = I('backcode');
        if(empty($backcode)){
            $tip['info'] = "联行号不能为空";
        }else{
            $rs = $m->saverealInfo($userId);
            if($rs['status']==1){
                $step = I('step'); //判断当前审核步骤
                if($step == 2){
                    $userinfo = $m->get($userId);
                    //调用光大门店修改接口
                    $result = R("SX/Api/ApigdupdateStore",array($userinfo));
                    if($result['respCode'] == "00"){ //00 修改成功
                        $m->setUseraudit($userinfo['userId'],1,"",3); //设置商户审核状态，1为审核成功 步骤为3
                        $tip['info'] = "银行信息审核成功";
                    }else{
                        if(empty($result['errMsg'])){
                            $result['errMsg'] = "系统升级繁忙，请重新提交";
                        }
                        $m->setUseraudit($userinfo['userId'],3,$result['errMsg']);
                        $tip['info'] = $result['errMsg'];
                    }
                }else{
                    $m->setUseraudit($userId,2);
                    $tip['info'] = "提交成功，等待审核";
                }  
            }else{
                $tip['info'] = "提交失败";
            }
        }    
        $this->assign('tip',$tip);
        $this->display("Public/tip");
    }

    /**
     * 修改微信平台代收模式状态
     */
    public function SetFieldV(){
        $this->isAjaxLogin();
        $m = D('Manage/Users');
        $rs = $m->SetFieldV();
        $this->ajaxReturn($rs);
    }

	/**
	 * 修改用户名
	 */
    public function mdfyName(){
		$this->isAjaxLogin();
		$this->checkAjaxPrivelege('zylb_00');
		$m = D('SX/Users');
		$rs = $m->mdfyName(I('uid'));
    	$this->ajaxReturn($rs);
    }

    /**
     * 获取光大行业类别信息
     */
    public function getGdCategory(){
        $this->isAjaxLogin();
        $m = D('Manage/GdIncategory');  
        $category['data'] = $m->get(I('key'));
        $this->ajaxReturn($category);
    }

    /**
     * 获取光大城市类别信息
     */
    public function getGdCity(){
        $this->isAjaxLogin();
        $m = D('Manage/GdDistrict');  
        $city['data'] = $m->getCity(I('fid'));
        $this->ajaxReturn($city);
    }

    /**
     * 获取光大银行联行号信息
     */
    public function getGdBankBranch(){
        $this->isAjaxLogin();
        $m = D('Manage/GdBankbranch');  
        $bank['data'] = $m->search();
        $this->ajaxReturn($bank);
    }

    /**
     * 获取地区信息
     */
    public function getDistrict(){
        $m = D('Manage/District');  
        $districts['data'] = $m->getDistrict(I('fid'));
        $this->ajaxReturn($districts);
    }

    /**
     * 获取类目信息
     */
    public function getCategory(){
        $this->isAjaxLogin();
        $m = D('Manage/CashierCategory');  
        $category['data'] = $m->getCategory(I('cid'));
        $this->ajaxReturn($category);
    }
    
    /*
     * 打印机管理
     */
    public function printerManage(){
        $this->isLogin();
        
        $u = D('Manage/Users');
        $m = D('Manage/Printer');

        $printers = $m->queryByPage(session('SX_USERS.userId'));
        $printer = new \Think\Page($printers['total'],$printers['pageSize']);
        $printers['pager'] = $printer->show();
        $this->assign('printers',$printers);
        
        $this->display('printerManage');
    }
    /*
     * 收银账户管理
     */
    public function accoutConfig(){
        $this->isLogin();
        $this->display();
    }
    /*
     * 显示创建打印机
     */
    public function createPrinter(){
        $this->isLogin();
        $this->display('createPrinter');
    }

    /*
     * 打印机详情
     */
    public function showPrinterDetail(){
        $this->isAjaxLogin();
        $m = D('Manage/Printer');
        $printer = $m->getPrinterById();
        $this->assign('printer',$printer);
        $this->display('printerDetail');
    }
    
}