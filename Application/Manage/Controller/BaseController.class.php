<?php
namespace Manage\Controller;
use Think\Controller;
class BaseController extends Controller {

    public function __construct(){
        parent::__construct();
        if(ACTION_NAME != 'toLogin' && ACTION_NAME != 'login'){
            $this->isLogin();
        }
    }

    /**
     * 用户id
     * @return mixed|null
     */
    public function getUserId(){
        return session('SX_USERS.usId');
    }

    /**
     * 登录操作验证
     */
    public function isLogin(){
    	$s = session('SX_USERS');
        if(empty($s))$this->redirect("Index/toLogin");
    }

    public function isAjaxLogin(){
        $s = session('SX_USERS');
        if(empty($s))$this->ajaxReturn(array('status'=>-999,'url'=>'Index/toLogin'));
    }

    /**
     * 验证权限操作
     */
    public function checkPrivelege($grant){
    	//先判断是员工还是商户 是员工判断权限 是商户判断userType是普通还是受理商户
    	$yes = false;
        $usId = session('SX_USERS.usId');
        if(!empty($usId)){//员工
	    	$s = session('SX_USERS.grant');
	    	if(empty($s) || !in_array($grant,$s)){
		        $yes = true;
	    	}
    	}else if($grant == 1){
            if(session('SX_USERS.userType') != 1){
                    $yes = true;
            }
    	}
    	if($yes){
            $tip['info'] = "没有权限";
            $tip['url'] = U("Manage/Index/index");
            $this->assign('tip',$tip);
            $this->display("public/tip");
    	}
    }
    public function checkAjaxPrivelege($grant){
    	$yes = false;
    	$usId = session('SX_USERS.usId');
        if(!empty($usId)){//员工
	    	$s = session('SX_USERS.grant');
	    	if(empty($s) || !in_array($grant,$s)){
		        $yes = true;
	    	}
    	}else if($grant == 1){
    		if(session('SX_USERS.userType') != 1){
    			$yes = true;
    		}
    	}
    	if($yes){
    		$this->ajaxReturn(array('status'=>-999,'url'=>'Index/toLogin'));
    	}
    }

    /**
     * 上传图片
     */
    public function uploadPic($maxSize=0,$savePath="uploads"){
       $config = array(
                'maxSize'       =>  $maxSize, //上传的文件大小限制 (0-不做限制)
                'exts'          =>  array('jpg','png','gif','jpeg'), //允许上传的文件后缀
                'rootPath'      =>  './Upload/', //保存根路径
                'driver'        =>  'LOCAL', // 文件上传驱动
                'subName'       =>  '',
                'savePath'      =>  I('dir',$savePath)."/", //文件上传（子）目录
        );
        $upload = new \Think\Upload($config);
        $rs = $upload->upload($_FILES);
        if(!$rs){
            return array('status'=>-1,'error'=>$upload->getError());
        }else{
            //生成缩略图
            /*$images = new \Think\Image();
            //foreach ($rs['Filedata'] as $key =>$v){
                 $images->open($config['rootPath'].$rs['file']['savepath'].$rs['file']['savename']);
                 $newsavename = str_replace('.','_thumb.',$rs['file']['savename']);
                 $vv = $images->thumb(I('width',100), I('height',100),I('thumb_type',1))->save($config['rootPath'].$rs['file']['savepath'].$newsavename);
            //}*/
            return array('status'=>1,'savepath'=>$config['rootPath'].$rs['file']['savepath'].$rs['file']['savename']);
        }

    }

    /**
     * 上传文件
     */
    public function uploadFile($maxSize=0,$exts=array('jpg','png','gif','jpeg','txt'),$savePath="uploads",$rootPath='./Upload/'){
        $config = array(
                'maxSize'       =>  $maxSize, //上传的文件大小限制 (0-不做限制) 字节
                'exts'          =>  $exts, //允许上传的文件后缀
                'savePath'      =>  I('dir',$savePath)."/", //文件上传（子）目录
                'rootPath'      =>  $rootPath, //保存根路径
                'replace'       =>  false, //存在同名文件是否是覆盖，默认为false
                'driver'        =>  'LOCAL', // 文件上传驱动
                'subName'       =>  ''
        );
        $upload = new \Think\Upload($config);
        $rs = $upload->upload($_FILES);
        if(!$rs){
            return array('status'=>-1,'error'=>$upload->getError());
        }else{
            return array('status'=>1,'savepath'=>$config['rootPath'].$rs['file']['savepath'].$rs['file']['savename']);
        }   
    }

    /**
     * 判断商户类型返回数据
     */
    public function gettypedata($users){
        if($users['wx_issp']==0){
            $post_data['mchtype'] = 0;//普通商户
            $post_data['apikey'] = $users['wx_apiSecret'];
            $post_data['appid'] = $users['wx_appId'];
            $post_data['appSecret'] = $users['wx_appSecret'];
            $post_data['mch_id'] = $users['wx_mchId'];
            $post_data['sslcert_path'] = $_SERVER['DOCUMENT_ROOT'].ltrim($users['wx_apiclientCert'],".");
            $post_data['sslkey_path'] = $_SERVER['DOCUMENT_ROOT'].ltrim($users['wx_apiclientKey'],".");
            $post_data['order_id'] = $users['wx_mchId']; //生成的订单号开头
        }else if($users['wx_issp']==1){
            $post_data['mchtype'] = 1;//特约商户
            $c = D('SX/Configs');
            $configs = $c->loadConfigs();
            $post_data['apikey'] = $configs['wx_apiSecret'];//'CEA117A5C4AAD752A5ABBDBAAF617A00';
            $post_data['appid'] = $configs['wx_appId'];//'wxf43c201f7bad723b';
            $post_data['appSecret'] = $configs['wx_appSecret'];
            $post_data['mch_id'] = $configs['wx_mchId'];
            $post_data['sub_mch_id'] = $users['wx_mchId'];//'1299505501';
            $post_data['sslcert_path'] = $_SERVER['DOCUMENT_ROOT'].ltrim($configs['wx_apiclientCert'],".");
            $post_data['sslkey_path'] = $_SERVER['DOCUMENT_ROOT'].ltrim($configs['wx_apiclientKey'],".");
            $post_data['order_id'] = $users['wx_mchId']; //生成的订单号开头
        }else if($users['wx_issp']==2){
            $post_data['mchtype'] = 2;//平台代收
            $c = D('SX/Configs');
            $configs = $c->loadConfigs();
            $post_data['apikey'] = $configs['ds_wx_apiSecret'];
            $post_data['appid'] = $configs['ds_wx_appId'];
            $post_data['appSecret'] = $configs['ds_wx_appSecret'];
            $post_data['mch_id'] = $configs['ds_wx_mchId'];
            $post_data['sslcert_path'] = $_SERVER['DOCUMENT_ROOT'].ltrim($configs['ds_wx_apiclientCert'],".");
            $post_data['sslkey_path'] = $_SERVER['DOCUMENT_ROOT'].ltrim($configs['ds_wx_apiclientKey'],".");
            $post_data['order_id'] = $configs['ds_wx_mchId']; //生成的订单号开头
        }else if($users['wx_issp']==3){
            $post_data['mchtype'] = 3;//受理模式
            $c = D('SX/Configs');
            $configs = $c->loadConfigs();
            $post_data['appid'] = $configs['gd_appId'];
            $post_data['appSecret'] = $configs['gd_appSecret'];
            $post_data['key'] = $configs['gd_key']; //渠道密钥
            $post_data['xy_key'] = $users['xy_key']; //普通密钥
            $post_data['sign_agentno'] = $configs['gd_mchId']; //渠道编号
            $post_data['mch_id'] = $users['gd_mchId']; //光大商户号
            
//            if(41 == $users['userId']){
//                $payConfigs = D("Manage/payConfigs")->getPayConfigs($users['userId']);
//                dataRecodes('支付账户', $payConfigs);
//                $index = rand(0, count($payConfigs)-1);
//                $post_data['appid'] = $payConfigs[$index]['wx_appId'];
//                $post_data['appSecret'] = $payConfigs[$index]['wx_appSecret'];
//                $post_data['xy_key'] = $payConfigs[$index]['wx_key'];
//                $post_data['mch_id'] = $payConfigs[$index]['wx_mchId'];      
//                dataRecodes('支付账户1', $post_data);
//            }
        }
        if($post_data['mch_id'] == "" || ($post_data['mchtype'] == 1 && $post_data['sub_mch_id'] == "")){
            return -1;
        }
        return $post_data;
    }

    /**
     * 数组转XML
     */
    public function ToXml($values)
    {
        if(!is_array($values) 
            || count($values) <= 0)
        {
            return -1;
        }
        $xml = "<xml>";
        foreach ($values as $key=>$val)
        {
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml; 
    }

    public function FromXml($xml)
    {   
        if(!$xml){
            return  -1;
        }
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $this->values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);        
        return $this->values;
    }

    /**
     * 生成微信签名
     */
    public function MakeWxSign($values,$apikey)
    {
        //签名步骤一：按字典序排序参数
        ksort($values);
        $buff = "";
        foreach ($values as $k => $v)
        {
            if($k != "sign" && $v != "" && !is_array($v)){
                $buff .= $k . "=" . $v . "&";
            }
        }

        $string = trim($buff, "&");

        //签名步骤二：在string后加入KEY
        $string = $string . "&key=".$apikey;

        //签名步骤三：MD5加密
        $string = md5($string);

        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);

        return $result;
    }

    /**
     * 微信生成随机字符串
     */
    public static function getWxNonceStr($length = 32) 
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";  
        $str ="";
        for ( $i = 0; $i < $length; $i++ )  {  
            $str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
        } 
        return $str;
    }

    /**
     * 
     * 拼接签名字符串
     * @param array $urlObj
     * 
     * @return 返回已经拼接好的字符串
     */
    public function ToUrlParams($urlObj)
    {
        $buff = "";
        foreach ($urlObj as $k => $v)
        {
            if($k != "sign"){
                $buff .= $k . "=" . $v . "&";
            }
        }
        
        $buff = trim($buff, "&");
        return $buff;
    }

    /**
     * 获取微信OpenId
     */
    public function GetOpenid($url,$appid,$appSecret){
        Vendor('Weixin.JsApiPay');
        $JsApiPay = new \JsApiPay($appid,$appSecret);
        $openid = $JsApiPay->GetOpenid($url);
        return $openid;
    }

    /**
     * 获取支付宝auth_code
     */
    public function GetAlipayCode($url,$appid){
        $auth_code = I('auth_code');
        if(empty($auth_code)){
            $appid = $appid;
            $scope = "auth_base";
            $redirect_uri = urlencode($url);
            $url = "https://openauth.alipay.com/oauth2/publicAppAuthorize.htm?app_id=".$appid."&scope=".$scope."&redirect_uri=".$redirect_uri;
            header("Location:".$url);
        }
        Vendor('Alipay.AopSdk');
        $AlipaySystemOauthTokenRequest = new \AlipaySystemOauthTokenRequest ();
        $AlipaySystemOauthTokenRequest->setCode ( $auth_code );
        $AlipaySystemOauthTokenRequest->setGrantType ( "authorization_code" );
        
        $result = \aopclient_request_execute ( $AlipaySystemOauthTokenRequest );
        return $result->alipay_system_oauth_token_response->user_id;
    }

    /**
     * 获取微信Token
     *   @type 1=token保存到用户自己的信息里   2=token保存到系统配置里
     *   @yz 1=要验证时间   2=不验证时间直接读取保存token
     */
    public function getWxToken($wx_token,$wx_update,$wx_appId,$wx_appSecret,$type=1,$yz=1){
        if(strtotime("+1 hours",strtotime($wx_update)) < time() || $yz==2){
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$wx_appId."&secret=".$wx_appSecret;
            $token =$this->wx_post($url);
            if(!empty($token['access_token'])){
                if($type==1){
                    $m = D('Manage/Users');
                    $m->saveToken(array('wx_token' => $token['access_token'],'wx_update' => date("Y-m-d H:i:s",time())));
                    dataRecodes('in getWxToken, data1=', $token);
                    return $token['access_token'];
                }else{
                    dataRecodes('in getWxToken, data2=', $token);
                    $c = D('SX/Configs');
                    $c->saveToken(array('wx_token' => $token['access_token'],'wx_update' => date("Y-m-d H:i:s",time())));
                    return $token['access_token'];
                }
            }else{
                dataRecodes('in getWxToken, data21=', $wx_token);
                return -1;
            }
        }else{
            dataRecodes('in getWxToken, data3=', $wx_token);
            return $wx_token;
        }

    }

    /**
     * 获取微信用户信息
     *   @num 1=第一次调用   2=第二次调用后不再调用
     */
    public function getWxUser($token,$openid,$data,$num=1){
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$token."&openid=".$openid."&lang=zh_CN";
        $wxinfo = $this->wx_post($url);
        if($wxinfo['errcode'] == 42001 || $wxinfo['errcode'] == 40001){ //token过期
            if($num==1){
                $token2 = $this->getWxToken($data['wx_token'],$data['wx_update'],$data['wx_appId'],$data['wx_appSecret'],2,2);//2为保存系统token 2为不验证时间
                $wxinfo = $this->getWxUser($token2,$openid,$data,2);
                return $wxinfo;
            }else{
                return -1;//token过期
            }
        }else{
            return $wxinfo;
        }
        //如果 $wxinfo['subscribe'] == 1 已关注
    }

    /**
     * 发送微信模板
     */
    public function sendWxtemp($data,$temp,$configs){
        if(!empty($data['openid']) && !empty($temp)){
            $temp = json_decode($temp,true);
            $post = array();
            $post['touser'] = $data['openid'];
            $post['template_id'] = $temp['tempId'];
            $post['url'] = $temp['url'];
            $post['data']['first']['value'] = $temp['first'];
            $post['data']['first']['color'] = "#173177";
            $i=0;
            foreach($temp as $key=>$vo){ 
                if(strpos($key,'keyword')===0){
                    $i++;
                    switch($temp[keyword.$i]){
                        case 1: //付款金额
                            $value = $data['price']."元";//$data['userName'];
                        break;
                        case 2: //商户名称
                            $value = $data['merchant_name'];//$data['body'];
                        break;
                        case 3: //支付方式                            
                            if($data['payType'] == 1){
                                $value = "微信支付";
                            }else{
                                $value = "支付宝支付";
                            }
                        break;
                        case 4: //交易单号
                            $value = $data['orderId'];//$data['price']."元";
                        break;
                        case 5: //时间
                            $value = date("Y-m-d H:i:s");
                        break;
                    }
                    $post['data'][$temp[keyname.$i]]['value'] = $value;
                }
            }
            $post['data']['remark']['value'] = $temp['remark'];
            $post['data']['remark']['color'] = "#173177";
            $token = $this->getWxToken($configs['wx_token'],$configs['wx_update'],$configs['wx_appId'],$configs['wx_appSecret'],2);//2为保存系统token

            if($token != ""){
                $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$token;
                $result = $this->wx_post($url,$post);
                //token过期发送失败重新发送一次
                if($result['errmsg']!="ok"){
                    $token = $this->getWxToken($configs['wx_token'],$configs['wx_update'],$configs['wx_appId'],$configs['wx_appSecret'],2,2);//2为保存系统token 2为不验证时间
                    $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$token;
                    $this->wx_post($url,$post);
                }
            }
        }
    }
    
    /**
     * 发送微信交接班模板
     */
    public function sendWxBalancetemp($data,$temp,$configs){
        if(!empty($data['openid']) && !empty($temp)){
            $temp = json_decode($temp,true);
            $post = array();
            $post['touser'] = $data['openid'];
            $post['template_id'] = $temp['tempId'];
            $post['url'] = $temp['url'];
            $post['data']['first']['value'] = $temp['first'];
            $post['data']['first']['color'] = "#173177";
            $i=0;
            foreach($temp as $key=>$vo){ 
                if(strpos($key,'keyword')===0){
                    $i++;
                    switch($temp[keyword.$i]){
                        case 1: //商户名称
                            $value = $data['businessName'];
                        break;
                        case 2: //收银账户
                            $value = $data['branchName'];
                        break;
                        case 3: //收银员                            
                            $value = $data['userName'];
                        break;
                        case 4: //收银笔数
                            $value = $data['totalNum']."笔"."(微信:".$data['weixinNum']."笔 支付宝:".$data['aliNum']."笔 退款:".$data['refund_num']."笔)";//$data['price']."元";
                        break;
                        case 5: //收银金额
                            $value = ($data["totalMoney"]-$data['refund_fee'])."元"."(微信：".$data['weixinMoney']."元 支付宝:".$data['aliMoney']."元 退款：".$data['refund_fee'].'元)';
                        break;
                    }
                    $post['data'][$temp[keyname.$i]]['value'] = $value;
                }
            }
            $post['data']['remark']['value'] = $temp['remark'];
            $post['data']['remark']['color'] = "#173177";
            $token = $this->getWxToken($configs['wx_token'],$configs['wx_update'],$configs['wx_appId'],$configs['wx_appSecret'],2);//2为保存系统token

            if($token != ""){
                $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$token;
                $result = $this->wx_post($url,$post);
                //token过期发送失败重新发送一次
                if($result['errmsg']!="ok"){
                    $token = $this->getWxToken($configs['wx_token'],$configs['wx_update'],$configs['wx_appId'],$configs['wx_appSecret'],2,2);//2为保存系统token 2为不验证时间
                    $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$token;
                    $this->wx_post($url,$post);
                }
            }
        }
    }

    /**
     * 发送微信支付模板信息
     */
    public function sendWxmessage($data,$type){
        $c = D('SX/Configs');
        $configs = $c->loadConfigs();
        $temp = $configs['paytemp'];

        if(!empty($temp)){
            $b = D('Manage/Bindweixin');
            if(!empty($data['storeId'])){ //判断是否有门店
                $s = D('Manage/Stores');
                $store = $s->getStore($data['storeId']);
                if($store['isSend']==1){ //判断该门店向商户（管理员）发送信息是否打开
                    $wxinfo = $b->get($data['userId'],"type=1");
                    $data['openid'] = $wxinfo['openid'];
                    $this->sendWxtemp($data,$temp,$configs);    
                }

                if($store['isallSend']==1){ //判断该门店向所有收银员发送信息是否打开
                    $wxinfo = $b->getuserAll($data['userId'],"type=2 and storeId=".$data['storeId']);
                    foreach ($wxinfo as $key => $value) {
                        if($value['isSend'] == 1){ //判断员工接收收银信息是否打开
                            $data['openid'] = $value['openid'];
                            $this->sendWxtemp($data,$temp,$configs);
                        }
                    }
                }else{ //没有打开则单独向当前收银的收银员发送信息
                    if(!empty($data['usId'])){ //判断员工是否存在
                        $wxinfo = $b->get($data['userId'],"usId=".$data['usId']." and type=2");
                        if($wxinfo['isSend'] == 1){ //判断员工接收收银信息是否打开
                            $data['openid'] = $wxinfo['openid'];
                            $this->sendWxtemp($data,$temp,$configs);
                        }
                    }  
                }

            }else{ //没有门店直接向商户（管理员）发送信息
                $wxinfo = $b->get($data['userId'],"type=1");
                $data['openid'] = $wxinfo['openid'];
                $this->sendWxtemp($data,$temp,$configs);
            }
        }
    }
    
    /**
     * 发送微信结算模板信息
     */
    public function sendWxBalancemessage($data,$type){
        $c = D('SX/Configs');
        $configs = $c->loadConfigs();
        $temp = $configs['balancetemp'];//'aesWpVzqSX6s8rgQoH1CLt1f7Hiz-QwNI_OBpwsHtI0';//暂时写在这里

        if(!empty($temp)){
            $b = D('Manage/Bindweixin');
            if(!empty($data['storeId'])){ //判断是否有门店
                $s = D('Manage/Stores');
                $store = $s->getStore($data['storeId']);
                if($store['isSend']==1){ //判断该门店向商户（管理员）发送信息是否打开
                    $wxinfo = $b->get($data['userId'],"type=1");
                    $data['openid'] = $wxinfo['openid'];
                    $this->sendWxBalancetemp($data,$temp,$configs);    
                }

                if($store['isallSend']==1){ //判断该门店向所有收银员发送信息是否打开
                    $wxinfo = $b->getuserAll($data['userId'],"type=2 and storeId=".$data['storeId']);
                    foreach ($wxinfo as $key => $value) {
                        if($value['isSend'] == 1){ //判断员工接收收银信息是否打开
                            $data['openid'] = $value['openid'];
                            $this->sendWxBalancetemp($data,$temp,$configs);
                        }
                    }
                }else{ //没有打开则单独向当前收银的收银员发送信息
                    if(!empty($data['usId'])){ //判断员工是否存在
                        $wxinfo = $b->get($data['userId'],"usId=".$data['usId']." and type=2");
                        if($wxinfo['isSend'] == 1){ //判断员工接收收银信息是否打开
                            $data['openid'] = $wxinfo['openid'];
                            $this->sendWxBalancetemp($data,$temp,$configs);
                        }
                    }  
                }

            }else{ //没有门店直接向商户（管理员）发送信息
                $wxinfo = $b->get($data['userId'],"type=1");
                $data['openid'] = $wxinfo['openid'];
                $this->sendWxBalancetemp($data,$temp,$configs);
            }
        }
    }

    /**
     * 发送微信绑定模板信息
     */
    public function sendWxbindmessage($data){
        $c = D('SX/Configs');
        $configs = $c->loadConfigs();
        $temp = $configs['bindtemp'];

        if(!empty($temp)){
            $b = D('Manage/Bindweixin');
            if($data['userType'] == 1){
                $wxinfo = $b->get($data['userId'],"type=1");
            }else{
                $wxinfo = $b->get($data['userId'],"usId=".$data['usId']." and type=2");
            }

            $data['openid'] = $wxinfo['openid'];
            $this->sendWxtemp($data,$temp,$configs);
        }
    }

    /**
     * 获取微信短链接
     */
    public function getshorturl($userId=0,$url){
        $m = D('Manage/Users');
        $users = $m->get($userId);
        $data = array();
        //判断是否为特约商户，特约商户调用系统微信配置
        $data = $this->gettypedata($users);

        //商户号为空
        if($data == -1){
            return $data;
        }

        if(!empty($data['appid']) && !empty($data['appSecret'])){
            $token = $this->getWxToken($data['wx_token'],$data['wx_update'],$data['appid'],$data['appSecret']);
            if($token != -1){
                $post_data = array();
                $post_data['action'] = "long2short";
                $post_data['long_url'] = $url;
                $url = "https://api.weixin.qq.com/cgi-bin/shorturl?access_token=".$token;
                $result = $this->wx_post($url,$post_data);
            }
        }
        return $result;
    }

    /**
     * 微信post提交
     */
    public function wx_post($url, $post_data, $timeout = 10){
            $ch = curl_init();
            curl_setopt ($ch, CURLOPT_URL, $url);
            if(!empty($post_data)){
                curl_setopt($ch, CURLOPT_POST, TRUE);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data,JSON_UNESCAPED_UNICODE));
            }
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_HEADER, false);
            $file_contents = curl_exec($ch);
            curl_close($ch);
            return json_decode(json_encode(json_decode($file_contents)),true);
    }

    /**
     * 微信XML提交
     */
    public function wx_xmlpost($url ,$xml, $useCert = false, $sslcert_path = "", $sslkey_path = "", $sslca_path = "", $second = 10){
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);//严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    
        if($useCert == true){
            //设置证书
            //使用证书：cert 与 key 分别属于两个.pem文件
            curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
            curl_setopt($ch,CURLOPT_SSLCERT, $sslcert_path);
            curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
            curl_setopt($ch,CURLOPT_SSLKEY, $sslkey_path);
            if(!empty($sslca_path)){
                curl_setopt($ch, CURLOPT_CAINFO, $sslca_path);
            }
        }
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);

        //返回结果
        if($data){
            curl_close($ch);
            return $data;
        } else { 
            $error = curl_errno($ch);
            curl_close($ch);
            return -1;
        }
    }
}