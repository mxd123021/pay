<?php
namespace Manage\Controller;

class WxcashierController extends BaseController {
	/**
	 * 显示扫码收银
	 */
    public function payment(){
    	$this->isLogin();
    	$type = I('type'); //i是调用
    	$type = empty($type)?1:$type;
        $usId = session('SX_USERS.usId');
    	switch($type){
    		case 1:
    		$this->checkPrivelege('wx_sksy');
    		break;
    		case 2:
    		$this->checkPrivelege('wx_smtk');
    		break;
    	}

        $m = D('Manage/Order');
        if(empty($usId)){
            $order = $m->getAll(session('SX_USERS.userId'),20);
        }else{
            $order = $m->getAll($usId,20,2); //1商户 2员工
        }

    	$this->assign("type",$type);
        $this->assign('order',$order);
    	$this->display("payment");
    }

    /**
     * 显示二维码收银
     */
    public function ewmpay(){
    	$this->isLogin();
        $usId = session('SX_USERS.usId');
        $m = D('Manage/Order');
        if(empty($usId)){
            $order = $m->getAll(session('SX_USERS.userId'),20);
        }else{
            $order = $m->getAll(session('SX_USERS.storeId'),20,3); //1商户 2员工 3门店
        }
        $autopayewm = $this->getshorturl(session('SX_USERS.userId'),U("Manage/Wxcashier/autopay@".C('SITE_URL'),array('userId'=>session('SX_USERS.userId'),'usId'=>session('SX_USERS.usId'),'storeId'=>session('SX_USERS.storeId'),'parentId'=>session('SX_USERS.parentId'))));
        if($autopayewm == -1){
            $tip['info'] = "请完善支付配置信息";
            $tip['url'] = U("Manage/Users/payConfig",$data);
            $this->assign("tip",$tip);
            $this->display("Public/tip");
        }
        $this->assign('order',$order);
        $this->assign('autopayewm',$autopayewm['short_url']);
    	$this->display("ewmpay");
    }

    /**
     * 永久付款二维码跳转
     */
    public function foreverpay(){
        $data = array();
        $data['userId'] = I('userId');
        $data['usId'] = I('usId');
        $data['storeId'] = I('storeId');
        $data['tprice'] = I('tprice');
        $data['tname'] = I('tname');
        $data['parentId'] = I('parentId');
        $data['type'] = "foreverpay";
        
        $url = U("Manage/Wxcashier/foreverpay@".C('SITE_URL'))."?".$this->ToUrlParams($data);
        if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) { //微信
            $m = D('Manage/Users');
            $users = $m->get($data['userId']);
            $post_data = array();
            //判断是否为特约商户，特约商户调用系统微信配置
            $post_data = $this->gettypedata($users);
            $data['openid'] = $this->GetOpenid($url,$post_data['appid'],$post_data['appSecret']);
            $data['paytype'] = "weixin";
        }else{ //支付宝
            $data['openid'] = $this->GetAlipayCode($url,"2015120400912117");
            $data['paytype'] = "alipay";
        }
        
        if(empty($data['openid'])){
            $tip['msg'] = "付链接已失效，系统正在重新生成链接...";
            $tip['url'] = U("Manage/Wxcashier/foreverpay@".C('SITE_URL'),$data);
            $this->assign("tip",$tip);
            $this->display("errorTips");
        }
        $url = U("Manage/Wxcashier/paying@".C('SITE_URL'))."?".$this->ToUrlParams($data);
        Header("Location: $url");
        


    }

    /**
     * 显示自助付款二维码界面
     */
    public function autopay(){
        $data = array();
        $data['userId'] = I('userId');
        $data['usId'] = I('usId');
        $data['storeId'] = I('storeId');
        $data['parentId'] = I('parentId');

        $url = U("Manage/Wxcashier/autopay@".C('SITE_URL'))."?".$this->ToUrlParams($data);
        if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) { //微信
            $m = D('Manage/Users');
            $users = $m->get($data['userId']);
            $post_data = array();
            //判断是否为特约商户，特约商户调用系统微信配置
            $post_data = $this->gettypedata($users);
            $data['openid'] = $this->GetOpenid($url,$post_data['appid'],$post_data['appSecret']);
            $data['paytype'] = "weixin";
        }else{ //支付宝
            $data['openid'] = $this->GetAlipayCode($url,"2015120400912117");
            $data['paytype'] = "alipay";
        }
        
        if(empty($data['openid'])){
            $tip['msg'] = "付链接已失效，系统正在重新生成链接...";
            $tip['url'] = U("Manage/Wxcashier/autopay@".C('SITE_URL'),$data);
            $this->assign("tip",$tip);
            $this->display("errorTips");
        }
        $this->assign("data",$data);
        $this->display("autopay");
    }

    /**
     * 显示付款成功界面
     */
    public function successTips(){
        $data = array();
        $data['userId'] = I('userId');
        $data['openid'] = I('id');
        $data['tprice'] = I('tprice');
        $data['storeId'] = I('storeId');
        $data['usId'] = I('usId');
        $data['paytype'] = I('paytype');

        //是否达到红包领取条件
        $p = D('Manage/Wxredpack');
        $redpack = $p->loadRedpack($data['userId']);
        if($redpack['isEffect'] == 1){
            if(strstr($redpack['redStoreId'],'('.$data['storeId'].')')){
                $rp = "open";
            }
        }

        //获取门店地理位置
        if(!empty($data['storeId'])){
            $s = D('Manage/Stores');
            $store = $s->getStore($data['storeId']);
            if(!empty($store)){
                $myLat = $store['latitude']; //纬度
                $myLng = $store['longitude']; //经度
                $num = 5; //代表搜索 5km 之内，单位km 
                $range = 180 / pi() * $num / 6372.797;      
                $lngR = $range / cos($myLat * pi() / 180);  
                $maxLat = $myLat + $range;//最大纬度  
                $minLat = $myLat - $range;//最小纬度  
                $maxLng = $myLng + $lngR;//最大经度  
                $minLng = $myLng - $lngR;//最小经度
                //显示微信社区
                $b = D('Manage/Wxbusiness');
                $business = $b->searchlocation($maxLat,$minLat,$maxLng,$minLng);
            }
        }

        $a = D('SX/Advertising');
        $adv = $a->get();
        $adv['isshow'] = 0;
        if($adv['status'] == 1){
            $Ip = new \Org\Net\IpLocation('ip.dat');
            $area = $Ip->getlocation();
            $area = $area['country'];

            //如果地址匹配
            if($adv['area'] == "0"){
                $adv['isshow'] = 1;
            }else{
                if(strpos($area,$adv['area'])===0){
                    $adv['isshow'] = 1;
                }
            }
        }

        $this->assign("data",$data);
        $this->assign("rp",$rp);
        $this->assign("business",$business);
        $this->assign("myLat",$myLat);
        $this->assign("myLng",$myLng);
        $this->assign("adv",$adv);
        $this->display("successTips");
    }

    /**
     * 获取立刻支付二维码
     */
    public function getEwm(){
        $this->isAjaxLogin();
        $rd = array('status'=>-1);
        $m = D('Manage/Users');
        $users = $m->get(session('SX_USERS.userId'));
        $post_data = array();
        $paytype = I('paytype','weixin');
        //判断是否为特约商户，特约商户调用系统微信配置
        $post_data = $this->gettypedata($users);

        if($post_data['mchtype']==3){ //渠道商户
            $post_data ['nonce_str'] = $this->getWxNonceStr(); //随机字符串
            $post_data['service'] = "pay.".$paytype.".native";
            $post_data ['out_trade_no'] = $post_data['mch_id'].date("YmdHis");
            $post_data['body'] = I('tname');
            if(empty($post_data['body'])){
                $post_data['body'] = "扫码支付";
            }
            $post_data['attach'] = "bank_mch_name=".$users['zz_jc']."&bank_mch_id=".$users['gd_mchId']."&".session('SX_USERS.userId').",".session('SX_USERS.usId').",".session('SX_USERS.storeId').",".session('SX_USERS.parentId').",".$post_data['mchtype'].",".$post_data['body'].","."扫码支付";
            $post_data ['total_fee'] = I("tprice",0) * 100;
            $post_data ['mch_create_ip'] = $_SERVER['REMOTE_ADDR'];
            $post_data ['notify_url'] = U("Manage/Order/addGdOrder@".C('SITE_URL')); //通知地址
            $key = $post_data['key'];
            unset($post_data['appid']);
            unset($post_data['appSecret']);
            unset($post_data['mchtype']);
            unset($post_data['key']);
            $post_data['sign'] =$this->MakeWxSign($post_data,$key);
            $url = "https://pay.swiftpass.cn/pay/gateway";
            $result = $this->wx_xmlpost($url,$this->ToXml($post_data));
            $data = $this->FromXml($result);
            $qrcode=$data['code_url'];
            if(!empty($qrcode)){
                $parameters = array('tprice'=>I("tprice"),'tname'=>$post_data['body'],'userId'=>session('SX_USERS.userId'),'usId'=>session('SX_USERS.usId'),'storeId'=>session('SX_USERS.storeId'),'parentId'=>session('SX_USERS.parentId'));
                $rd['qrcode'] = $qrcode;
                $short_url = $this->getshorturl(session('SX_USERS.userId'),U("Manage/Wxcashier/foreverpay@".C('SITE_URL'),$parameters));
                $rd['foreverpay'] = $short_url['short_url'];
                $rd['status'] = 1;
            }else{
                $rd['msg'] = $data['message'];
            }
        }else{
            $post_data['nonce_str'] = $this->getWxNonceStr();
            $post_data['body'] = I('tname');
            if(empty($post_data['body'])){
                $post_data['body'] = "扫码支付";
            }
            $post_data['attach'] = session('SX_USERS.userId').",".session('SX_USERS.usId').",".session('SX_USERS.storeId').",".session('SX_USERS.parentId').",".$post_data['mchtype'].",".$post_data['body'].","."扫码支付";
            $post_data['out_trade_no'] = $post_data['order_id'].date("YmdHis"); //商品订单号  随机生成
            $post_data['total_fee'] =I("tprice",0) * 100;               //总金额
            $post_data['spbill_create_ip'] = $_SERVER['REMOTE_ADDR'];              //终端IP
            $post_data['notify_url'] = U("Manage/Order/addWxOrder@".C('SITE_URL')); //通知地址
            $post_data['trade_type'] = "NATIVE";               //交易类型
            $apikey = $post_data['apikey'];
            $appSecret = $post_data['appSecret'];
            unset($post_data['mchtype']);
            unset($post_data['sslcert_path']);
            unset($post_data['sslkey_path']);
            unset($post_data['apikey']);
            unset($post_data['appSecret']);
            unset($post_data['order_id']);
            $post_data['sign'] =$this->MakeWxSign($post_data,$apikey);               //微信签名

            /* //$post_data['detail'] = " ";               //商品详情可以默认为空
             //$post_data['fee_type'] = " ";               //货币类型
             //$post_data['time_start'] = " ";               //交易起始时间  可以默认为空
             //$post_data['time_expire'] =" ";               //交易结束时间  可以默认为空
             //$post_data['goods_tag'] = " ";               //商品标记
             //$post_data['product_id'] = "";               //商品ID  可以默认为空
             //$post_data['limit_pay'] = ;               //指定支付方式
             //$post_data['openid'] = "";               //用户标识   可以默认为空
             //$post_data['device_info'] = " ";                                   //设备号   可以为空
            */
            $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
            $result = $this->wx_xmlpost($url,$this->ToXml($post_data));  //微信xml提交方式
            $data = $this->FromXml($result);

            $qrcode=$data['code_url'];
            if(!empty($qrcode)){
                $parameters = array('tprice'=>I("tprice"),'tname'=>$post_data['body'],'userId'=>session('SX_USERS.userId'),'usId'=>session('SX_USERS.usId'),'storeId'=>session('SX_USERS.storeId'),'parentId'=>session('SX_USERS.parentId'));
                $rd['qrcode'] = $qrcode;
                $short_url = $this->getshorturl(session('SX_USERS.userId'),U("Manage/Wxcashier/foreverpay@".C('SITE_URL'),$parameters));
                $rd['foreverpay'] = $short_url['short_url'];
                $rd['status'] = 1;
                /*Vendor('Qrcode.phpqrcode');
                $QRcode = new \QRcode();*/
                //echo $QRcode::png($ewmurl);
            }else{
                $rd['msg'] = $data['return_msg'];
            }
        }


        $this->ajaxReturn($rd);
    }

	/**
	 * 刷卡收银
	 */
    public function pay(){
    	$this->isAjaxLogin();
    	$this->checkAjaxPrivelege('wx_sksy');
    	$rd = array('status'=>-1);
        $m = D('Manage/Users');
        $users = $m->get(session('SX_USERS.userId'));
        $post_data = array();
        //判断商户类别，特约商户调用系统微信配置，受理模式调用光大银行配置
        $post_data = $this->gettypedata($users);
        if($post_data == -1){
            $rd['msg'] = "请检查支付配置是否正确";
            $this->ajaxReturn($rd);
        }
        if($post_data['mchtype'] == 3){ //受理模式
            $post_data ['nonce_str'] = $this->getWxNonceStr(); //随机字符串
            $post_data['service'] = "unified.trade.micropay"; //接口类型
            $post_data['body'] = I('goods_name');
            if(empty($post_data['body'])){
                $post_data['body'] = "刷卡支付";
            }
            $post_data['attach'] = "bank_mch_name=".$users['zz_jc']."&bank_mch_id=".$users['gd_mchId']."&".session('SX_USERS.userId').",".session('SX_USERS.usId').",".session('SX_USERS.storeId').",".session('SX_USERS.parentId').",".$post_data['mchtype'].",".$post_data['body'].","."刷卡支付";
            $post_data ['out_trade_no'] = $post_data['mch_id'].date("YmdHis"); //订单号
            $price = I('goods_price');
            $post_data ['total_fee'] = $price * 100; //付款金额 分
            $post_data ['mch_create_ip'] = $_SERVER['REMOTE_ADDR']; //IP地址
            $post_data ['auth_code'] =I('auth_code'); //刷卡支付授权码
            $post_data ['op_user_id'] =session('SX_USERS.userId'); //操作员 默认为商户号
            $post_data ['op_shop_id'] =session('SX_USERS.storeId'); //门店编号
            $key = $post_data['key']; //渠道密钥
            $mchtype = $post_data['mchtype'];
            unset($post_data['appid']);
            unset($post_data['appSecret']);
            unset($post_data['mchtype']);
            unset($post_data['key']);
            $post_data['sign'] = $this->MakeWxSign($post_data,$key);
            $url = "https://pay.swiftpass.cn/pay/gateway";
            $result = $this->wx_xmlpost($url,$this->ToXml($post_data));
            $rs = $this->FromXml($result);

            if($rs['status'] == "0"){
                if($rs['result_code'] == "0"){
                    if($rs['pay_result'] == "0"){
                        $rd['status'] = 1;
                        $rd['price'] = $post_data ['total_fee'] / 100;
                        //支付成功添加订单
                        $o = D('Manage/Order');
                        $o->addGdOrder($rs);
                        //支付成功发送通知
                        $post['userId'] = session('SX_USERS.userId');
                        $post['usId'] = session('SX_USERS.usId');
                        $post['storeId'] = session('SX_USERS.storeId');
                        $post['price'] = $price;
                        $post['body'] = $post_data['body'];
                        $this->sendWxmessage($post);
                    }else{
                        $rd['status'] = -1;
                        $rd['msg'] = "支付失败";
                    }
                }else{
                    if($rs['err_code']=="USERPAYING"){
                        $rd['status'] = 2;
                        $rd['orderid'] = $post_data ['out_trade_no']; //订单ID
                    }
                    $rd['msg'] = $rs['err_msg'];
                }
            }else{
                if(!empty($rs['err_msg'])){
                    $rd['msg'] = $rs['err_msg'];
                }else{
                    $rd['msg'] = $rs['message'];
                }
            }
        }else{ //微信模式
            $post_data['nonce_str'] = $this->getWxNonceStr();
            $post_data['body'] = I('goods_name');
            if(empty($post_data['body'])){
            	$post_data['body'] = "刷卡支付";
            }

            $post_data['attach'] = session('SX_USERS.userId').",".session('SX_USERS.usId').",".session('SX_USERS.storeId').",".session('SX_USERS.parentId').",".$post_data['mchtype'].",".$post_data['body'].","."刷卡支付";
            $post_data['out_trade_no'] = $post_data['order_id'].date("YmdHis");
            $price = I('goods_price');
            $post_data['total_fee'] = $price * 100;
            $post_data['spbill_create_ip'] = $_SERVER['REMOTE_ADDR'];
            $post_data['auth_code'] = I('auth_code');
            $apikey = $post_data['apikey'];
            $mchtype = $post_data['mchtype'];
    		unset($post_data['mchtype']);
            unset($post_data['sslcert_path']);
            unset($post_data['sslkey_path']);
            unset($post_data['apikey']);
            unset($post_data['appSecret']);
            unset($post_data['order_id']);
            $post_data ['sign'] = $this->MakeWxSign($post_data,$apikey);
            $url = "https://api.mch.weixin.qq.com/pay/micropay";
            $result = $this->wx_xmlpost($url,$this->ToXml($post_data));
            $rs = $this->FromXml($result);
            if($rs['return_code'] == "FAIL"){
            	$rd['status'] = -1;
            	$rd['msg'] = $rs['return_msg'];
            }else{
            	if($rs['result_code'] == "SUCCESS"){
                    $rd['status'] = 1;
                    $rd['price'] = $post_data ['total_fee'] / 100;
            		//支付成功添加订单
                    $o = D('Manage/Order');
            		$o->addWxOrder($rs);
                    //如果为代收，则增加待划账金额
                    if($mchtype==2){
                        $f = D('Manage/Financial');
                        $f->dsrecharge($rs);
                    }
                    //支付成功发送通知
                    $post['userId'] = session('SX_USERS.userId');
                    $post['usId'] = session('SX_USERS.usId');
                    $post['storeId'] = session('SX_USERS.storeId');
                    $post['price'] = $price;
                    $post['body'] = $post_data['body'];
                    $this->sendWxmessage($post);
            	}else{
            		if($rs['err_code']=="USERPAYING"){
            			$rd['status'] = 2;
            			$rd['orderid'] = $post_data ['out_trade_no']; //订单ID
            		}
    				$rd['msg'] = $rs['err_code_des'];
            	}  	
            }
        }
        $this->ajaxReturn($rd);
    }

	/**
	 * 扫码退款
	 */
    public function refund(){
    	$this->isAjaxLogin();
    	$this->checkAjaxPrivelege('wx_smtk');
    	$rd = array('status'=>-1);
        $m = D('Manage/Users');
        $users = $m->get(session('SX_USERS.userId'));
        $post_data = array();

        //判断是否为特约商户，特约商户调用系统微信配置
        $post_data = $this->gettypedata($users);
        if($post_data == -1){
            $rd['msg'] = "请检查支付配置是否正确";
            $this->ajaxReturn($rd);
        }
        $o = D('Manage/Order');
        $orderid = $o->get(I('auth_code'));

        if(!empty($orderid)){

            if($orderid['mchtype']==3){ //渠道商户退款
                $post_data ['out_trade_no'] = $orderid['order_id'];
                $post_data ['nonce_str'] = $this->getWxNonceStr(); //随机字符串
                $post_data['service'] = "unified.trade.refund"; //接口类型
                $post_data ['total_fee'] = $orderid['goods_price'] * 100;
                $post_data ['refund_fee'] = $orderid['goods_price'] * 100;
                $post_data ['op_user_id'] = $orderid ['uid'];
                $post_data ['out_refund_no'] = $post_data ['order_id'].date("YmdHis");
                $key = $post_data['key']; //渠道密钥
                unset($post_data['appid']);
                unset($post_data['appSecret']);
                unset($post_data['mchtype']);
                unset($post_data['key']);
                $post_data['sign'] = $this->MakeWxSign($post_data,$key);
                $url = "https://pay.swiftpass.cn/pay/gateway";
                $result = $this->wx_xmlpost($url,$this->ToXml($post_data));
                $rs = $this->FromXml($result);
                if($rs['status'] == "0" && $rs['result_code'] == "0"){
                    //微信退款修改订单信息
                    $o->updateWxOrder($rs,session('SX_USERS.usId'),session('SX_USERS.storeId'));
                    $rd['status'] = 1;
                    $rd['msg'] = "退款成功";
                }else{
                    if($rs['err_code'] == "REFUND_FEE_INVALID"){
                        $o->updateWxOrder($orderid,session('SX_USERS.usId'),session('SX_USERS.storeId'));
                        $rd['msg'] = "已退款";
                    }else{
                        $rd['status'] = -1;
                        $rd['msg'] = $rs['err_msg'];
                    }
                }
            }else{
                //如果为代收,判断待划账金额是否大于等于退款金额
                if($orderid['mchtype']==2){
                    $f = D('Manage/Financial');
                    $money = $f->getCurmoney(session('SX_USERS.userId'));
                    if($money['dsshmoney'] < $orderid['goods_price']){
                        $rd['msg'] = "当前订单为代收，代收的待划账金额不足无退款";
                        $this->ajaxReturn($rd);
                    }
                }

                $post_data ['transaction_id'] = $orderid['transaction_id'];
                $post_data ['nonce_str'] = $this->getWxNonceStr();
                $post_data ['total_fee'] = $orderid['goods_price'] * 100;
                $post_data ['refund_fee'] = $orderid['goods_price'] * 100;
                $post_data ['op_user_id'] = $post_data ['mch_id'];
                $post_data ['out_refund_no'] = $post_data ['order_id'].date("YmdHis");
                $sslcert_path = $post_data['sslcert_path'];
                $sslkey_path = $post_data['sslkey_path'];
                $url = "https://api.mch.weixin.qq.com/secapi/pay/refund";
                $apikey = $post_data['apikey'];
                unset($post_data['mchtype']);
                unset($post_data['apikey']);
                unset($post_data['sslcert_path']);
                unset($post_data['sslkey_path']);
                unset($post_data['appSecret']);
                unset($post_data['order_id']);
                $post_data ['sign'] = $this->MakeWxSign($post_data,$apikey);
                $result = $this->wx_xmlpost($url,$this->ToXml($post_data),true,$sslcert_path,$sslkey_path);
                $rs = $this->FromXml($result);
                if($rs['return_code'] == "FAIL"){
                    $rd['status'] = -1;
                    $rd['msg'] = $rs['return_msg'];
                }else{
                    if($rs['result_code'] == "SUCCESS"){
                        //微信退款修改订单信息
                        $o->updateWxOrder($rs,session('SX_USERS.usId'),session('SX_USERS.storeId'));

                        //如果为代收，则减少待划账金额
                        if($orderid['mchtype']==2){
                            $f->dscutrecharge(session('SX_USERS.userId'),$orderid['goods_price']);
                        }

                        $rd['status'] = 1;
                        $rd['msg'] = "退款成功";
                    }else{
                        $rd['msg'] = empty($rs['err_code_des'])?"私钥文件失效或无授权资格,请检查微信支付配置证书是否正确":$rs['err_code_des'];
                    }   
                }
            }
        }else{
            $rd['status'] = -1;
            $rd['msg'] = "订单不存在";
        }
        $this->ajaxReturn($rd);
    }

    /**
     * 永久付款二维码支付（公众号支付方式）
     */
    public function paying(){
        $rd = array('status'=>1);
        $data = array();
        $data['tprice'] = I('tprice');
        $data['tname'] = I('tname');
        $data['userId'] = I('userId');
        $data['usId'] = I('usId');
        $data['storeId'] = I('storeId');
        $data['parentId'] = I('parentId');
        $data['openid'] = I('openid');
        $data['type'] = I('type');
        $data['paytype'] = I('paytype','weixin');

        switch ($data['type']) {
            case 'autopay':
                    $tip['url'] = U("Manage/Wxcashier/autopay@".C('SITE_URL'),$data);
                break;
            
            case 'foreverpay':
                    $tip['url'] = U("Manage/Wxcashier/foreverpay@".C('SITE_URL'),$data);
                break;
            default:
                    echo "类型错误";
                    exit;
                break;
        }

        if(empty($data['userId']) || $data['userId'] <= 0){
            $rd = array('status'=>-1);
            $tip['msg'] = "参数错误";
        }else if(empty($data['tname'])){
            $rd = array('status'=>-1);
            $tip['msg'] = "付款理由不能为空";
        }else if(empty($data['tprice']) || $data['tprice'] <= 0){
            $rd = array('status'=>-1);
            $tip['msg'] = "付款金额必须大于0.01元";
        }

        if($rd['status'] == -1){
            $this->assign("tip",$tip);
            $this->display("errorTips");
            exit;
        }

        $m = D('Manage/Users');
        $users = $m->get($data['userId']);
        $post_data = array();

        //判断是否为特约商户，特约商户调用系统微信配置
        $post_data = $this->gettypedata($users);

        if($post_data['mchtype']==3){ //渠道商户
            $post_data ['nonce_str'] = $this->getWxNonceStr(); //随机字符串
            $post_data['service'] = "pay.".$data['paytype'].".jspay";
            $post_data['body'] = $data['tname'];
            if(empty($post_data['body'])){
                $post_data['body'] = "自助支付";
            }
            $post_data['attach'] = "bank_mch_name=".$users['zz_jc']."&bank_mch_id=".$users['gd_mchId']."&".$data['userId'].",".$data['usId'].",".$data['storeId'].",".$data['parentId'].",".$post_data['mchtype'].",".$post_data['body'].","."自助支付";
            $post_data ['out_trade_no'] = $post_data['mch_id'].date("YmdHis");
            $post_data ['total_fee'] = $data['tprice'] * 100;
            $post_data ['mch_create_ip'] = $_SERVER['REMOTE_ADDR'];
            $post_data ['notify_url'] = U("Manage/Order/addGdOrder@".C('SITE_URL')); //通知地址
            if($data['paytype'] == "weixin"){
                $post_data['sub_openid'] = $data['openid'];
                $post_data ['is_raw'] = "1"; //是否返回微信JS原生态信息
            }else if($data['paytype'] == "alipay"){
                $post_data ['buyer_id'] = $data['openid']; //买家支付宝用户ID
            }
            $key = $post_data['key'];
            unset($post_data['appid']);
            unset($post_data['appSecret']);
            unset($post_data['mchtype']);
            unset($post_data['key']);
            $post_data['sign'] =$this->MakeWxSign($post_data,$key);
            $url = "https://pay.swiftpass.cn/pay/gateway";
            $result = $this->wx_xmlpost($url,$this->ToXml($post_data));
            $rs = $this->FromXml($result);
            if($rs['status'] == "0" && $rs['result_code'] == "0"){
                    $jsApiParameters = $rs['pay_info'];
                    $this->assign("data",$data);
                    $this->assign("jsApiParameters",$jsApiParameters);
                    $this->assign("pay_url",$rs['pay_url']);
                    $this->display("paying");
            }else{
                $rd['status'] = -1;
                if(!empty($rs['err_msg'])){
                    $rd['msg'] = $rs['err_msg'];
                }else{
                    $rd['msg'] = $rs['message'];
                }     
            }
        }else{
            $post_data['nonce_str'] = $this->getWxNonceStr();
            $post_data['body'] = $data['tname'];
            if(empty($post_data['body'])){
                $post_data ['body'] = "自助支付";
            }
            $post_data['attach'] = $data['userId'].",".$data['usId'].",".$data['storeId'].",".$data['parentId'].",".$post_data['mchtype'].",".$post_data['body'].","."自助支付";
            $post_data['out_trade_no'] = $post_data['order_id'].date("YmdHis");              //商品订单号  随机生成
            $post_data['total_fee'] =$data['tprice'] * 100;               //总金额
            $post_data['spbill_create_ip'] = $_SERVER['REMOTE_ADDR'];              //终端IP
            $post_data['notify_url'] =U("Manage/Order/addWxOrder@".C('SITE_URL')); //通知地址
            $post_data['trade_type'] = "JSAPI";               //交易类型
            $post_data['openid'] = $data['openid'];
            $apikey = $post_data['apikey'];
            $appSecret = $post_data['appSecret'];
            unset($post_data['mchtype']);
            unset($post_data['sslcert_path']);
            unset($post_data['sslkey_path']);
            unset($post_data['apikey']);
            unset($post_data['appSecret']);
            unset($post_data['order_id']);
            $post_data['sign'] =$this->MakeWxSign($post_data,$apikey);               //微信签名
            /* //$post_data['detail'] = " ";               //商品详情可以默认为空
             //$post_data['fee_type'] = " ";               //货币类型
             //$post_data['time_start'] = " ";               //交易起始时间  可以默认为空
             //$post_data['time_expire'] =" ";               //交易结束时间  可以默认为空
             //$post_data['goods_tag'] = " ";               //商品标记
             //$post_data['product_id'] = "";               //商品ID  可以默认为空
             //$post_data['limit_pay'] = ;               //指定支付方式
             //$post_data['openid'] = "";               //用户标识   可以默认为空
             //$post_data['device_info'] = " ";                                   //设备号   可以为空
            */
            $url = "https://api.mch.weixin.qq.com/pay/unifiedorder"; 
            $result = $this->wx_xmlpost($url,$this->ToXml($post_data));  //微信xml提交方式
            $rs = $this->FromXml($result);

            if($rs['return_code'] == "FAIL"){
                $rd['status'] = -1;
                $rd['msg'] = $rs['return_msg'];
            }else{
                if($rs['result_code'] == "SUCCESS"){
                    Vendor('Weixin.JsApiPay');
                    $JsApiPay = new \JsApiPay($data['appid'],$appSecret);
                    $jsApiParameters = $JsApiPay->GetJsApiParameters($rs,$apikey);
                    $this->assign("data",$data);
                    $this->assign("jsApiParameters",$jsApiParameters);
                    $this->display("paying");
                }else{
                    $rd['status'] = -1;
                    $rd['msg'] = $rs['err_code_des'];
                }
            }
        }
        if($rd['status'] == -1){
            $this->assign("tip",$rd);
            $this->display("errorTips");
        }

    }

	/**
	 * 查询微信订单
	 */
    public function orderquery(){
    	$this->isAjaxLogin();
    	$rd = array('status'=>-1);
        $m = D('Manage/Users');
        $users = $m->get(session('SX_USERS.userId'));
        $post_data = array();

        //判断商户类别，特约商户调用系统微信配置，受理模式调用光大银行配置
        $post_data = $this->gettypedata($users);
        if($post_data['mchtype'] == 3){//受理模式
            $post_data ['out_trade_no'] = I('orderid');
            $post_data ['nonce_str'] = $this->getWxNonceStr(); //随机字符串
            $post_data['service'] = "unified.trade.query"; //接口类型
            $key = $post_data['key']; //渠道密钥
            unset($post_data['appid']);
            unset($post_data['appSecret']);
            unset($post_data['mchtype']);
            unset($post_data['key']);
            $post_data['sign'] = $this->MakeWxSign($post_data,$key);
            $url = "https://pay.swiftpass.cn/pay/gateway";
            $result = $this->wx_xmlpost($url,$this->ToXml($post_data));
            $rs = $this->FromXml($result);
            dataRecodes('受理模式下小额接口查询订单返回参数', $rs);

            if($rs['status'] == "0" && $rs['result_code'] == "0"){
                if($rs['trade_state'] == "SUCCESS"){
                    $rd['status'] = 1;
                    $rd['price'] = $rs['total_fee'] / 100;
                    $o = D('Manage/Order');
                    $o->addWxOrder($rs);
                    //支付成功发送通知
                    $post['userId'] = session('SX_USERS.userId');
                    $post['usId'] = session('SX_USERS.usId');
                    $post['storeId'] = session('SX_USERS.storeId');
                    $post['price'] = $rd['price'];
                    $post['body'] = $attach[5];
//                    $this->sendWxmessage($post);
                }else if($rs['trade_state'] == "USERPAYING"){
                    $rd['status'] = 2;
                }else{
                    if($rs['trade_state'] == "NOTPAY"){
                        $rd['msg'] = "用户取消支付";
                    }else{
                        $rd['msg'] = "支付失败";
                    }
                    
                }
            }else{
                if(empty($rs['err_msg'])){ //连接超时继续等待
                    $rd['status'] = 2;
                }else{
                    $rd['status'] = -1;
                    $rd['msg'] = $rs['err_msg'];
                }
            }
        }else{
            $post_data ['out_trade_no'] = I('orderid');
            $post_data ['nonce_str'] = $this->getWxNonceStr();
            $apikey = $post_data['apikey'];
            unset($post_data['mchtype']);
            unset($post_data['apikey']);
            unset($post_data['sslcert_path']);
            unset($post_data['sslkey_path']);
            unset($post_data['appSecret']);
            unset($post_data['order_id']);
            $post_data ['sign'] = $this->MakeWxSign($post_data,$apikey);
            $url = "https://api.mch.weixin.qq.com/pay/orderquery";
            $result = $this->wx_xmlpost($url,$this->ToXml($post_data));
            $rs = $this->FromXml($result);
            if($rs['return_code'] == "FAIL"){
            	$rd['status'] = -1;
            	$rd['msg'] = $rs['return_msg'];
            }else{
            	if($rs['trade_state'] == "SUCCESS"){
                    $rd['status'] = 1;
                    $rd['price'] = $rs['total_fee'] / 100;
            		$o = D('Manage/Order');
            		$o->addWxOrder($rs);
                    //支付成功发送通知
                    $post['userId'] = session('SX_USERS.userId');
                    $post['usId'] = session('SX_USERS.usId');
                    $post['storeId'] = session('SX_USERS.storeId');
                    $post['price'] = $rd['price'];
                    $post['body'] = $attach[5];
                    $this->sendWxmessage($post);
            	}else if($rs['trade_state'] == "USERPAYING"){
            		$rd['status'] = 2;
            	}else{
            		$rd['msg'] = $rs['trade_state_desc'];
            	}  	
            }
        }
        dataRecodes('受理模式下小额接口查询订单返回结果', $rd);
        $this->ajaxReturn($rd);
    }

}