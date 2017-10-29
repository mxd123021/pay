<?php
namespace SX\Controller;
class ApiController extends BaseController {
    /**
     * 光大银行门店进件
     */
    public function ApigdStore($userinfo){
        $c = D('SX/Configs');
        $configs = $c->loadConfigs();

        $upoto = $this->ApigdUpoto($configs,$userinfo['sfzz'],$userinfo['sfzf']); //调用证件上传接口
        if(!empty($upoto['errMsg'])){
            $rs['errmsg'] = $upoto['errMsg'];
            return $rs;
        }else if($upoto == -1){
            $rs['errmsg'] = "请上传证件照片";
            return $rs;
        }

        $post_data = array();
        $post_data ['service'] = "ceb.cms.store.new";
        $post_data ['nonce_str'] = $this->getNonceStr();
        $post_data ['mch_id'] = $configs['gd_mchId']; //授权渠道编号

        $post_data ['merchant_name'] = $userinfo['zz_jc']; //商户名称
        $post_data ['merchant_parentMerchant'] = $configs['gd_parentMid']; //大商户、普通商户无父商户.直营商户、加盟商户的父商户为大商户，门店进件必填
        $post_data ['merchant_outMerchantId'] = $userinfo['userId']; //接入方平台商户号，与威富通商户号一一对应，接入方唯

        $post_data ['merchantDetail_industryId'] = $userinfo['incode']; //行业类别编号
        $post_data ['merchantDetail_province'] = $userinfo['fid']; //省份编号
        $post_data ['merchantDetail_city'] = $userinfo['sid']; //城市编号
        $post_data ['merchantDetail_address'] = "121.429 31.2423"; //商户地址，经纬度
        $post_data ['merchantDetail_principal'] = $userinfo['zz_name']; //负责人
        $post_data ['merchantDetail_mobile'] = $userinfo['userPhone']; //负责人手机号

        $post_data ['bankAccount_accountCode'] = $userinfo['zz_bankinfo']; //银行卡号
        $post_data ['bankAccount_bankId'] = $userinfo['zz_bank']; //开户银行编号
        $post_data ['bankAccount_accountName'] = $userinfo['zz_accountname']; //开户人/开户名称
        $post_data ['bankAccount_accountType'] = $userinfo['zz_banktype']; //账户类型
        //$post_data ['bankAccount_contactLine'] = $userinfo['backcode']; //联行号
        $post_data ['bankAccount_bankName'] = $userinfo['zz_bankqc']; //开户支行名称
        $post_data ['bankAccount_province'] = $userinfo['fid']; //开户支行所在省编号
        $post_data ['bankAccount_city'] = $userinfo['sid']; //开户支行所在市编号
        $post_data ['bankAccount_idCardType'] = $userinfo['zz_sftype']; //证件类型
        $post_data ['bankAccount_idCard'] = strtolower($userinfo['zz_sfz']); //持卡人证件号码
        $post_data ['bankAccount_address'] = "地址信息缺省"; //持卡人地址，剔除省市的具体地址
        $post_data ['bankAccount_tel'] = $userinfo['bankPhone']; //银行预留电话
        $post_data ['bankAccount_accountEnName'] = "aaa"; //持卡人英文名
        $post_data ['bankAccount_accountExpiredDate'] = $userinfo['zz_yxq']; //证件有效期
        $post_data ['bankAccount_accountPostcode'] = "550000"; //邮编
        $post_data ['indentityPhoto'] = $upoto['indentityPhoto']; //证件照路径，由证件上传接口获得

        $rate = json_decode($userinfo['gd_rate'],true);
        //微信
        $post_data ['mchPayConf_apiCode_1'] = "pay.weixin.native"; //扫码支付
        $post_data ['mchPayConf_billRate_1'] = $rate['wx_native']; //商户结算费率，参数单位为‰，如商户费率为6‰，则报文值为6。结算费率不可超过接口双方约定的费率范围
        $post_data ['mchPayConf_apiCode_2'] = "pay.weixin.micropay"; //刷卡支付
        $post_data ['mchPayConf_billRate_2'] = $rate['wx_micropay'];
        $post_data ['mchPayConf_apiCode_3'] = "pay.weixin.jspay"; //公众号支付
        $post_data ['mchPayConf_billRate_3'] = $rate['wx_jspay'];
        //支付宝
        $post_data ['mchPayConf_apiCode_4'] = "pay.alipay.nativev3";
        $post_data ['mchPayConf_billRate_4'] = $rate['ali_native'];
        $post_data ['mchPayConf_apiCode_5'] = "pay.alipay.micropayv3";
        $post_data ['mchPayConf_billRate_5'] = $rate['ali_micropay'];
        $post_data ['mchPayConf_apiCode_6'] = "pay.alipay.jspayv3";
        $post_data ['mchPayConf_billRate_6'] = $rate['ali_jspay'];
        $key = $configs['gd_key']; //授权渠道密钥
        $post_data['sign'] = $this->MakeGdSign($post_data,$key);
        $url = "https://api.swiftpass.cn/unifiedAuth";
        $result = $this->xmlpost($url,$this->ToXml($post_data));
        $rs = $this->FromXml($result);
        return $rs;
    }

    /**
     * 光大银行门店修改
     */
    public function ApigdupdateStore($userinfo){
        $c = D('SX/Configs');
        $configs = $c->loadConfigs();

		$post_data = array();
		$post_data ['service'] = "ceb.account.modify";
		$post_data ['nonce_str'] = $this->getNonceStr();
		$post_data ['mch_id'] = $configs['gd_mchId']; //授权渠道编号

		$post_data ['merchant_id'] = $userinfo['gd_mchId']; //光大商户号
		$post_data ['bankAccount_accountName'] = $userinfo['zz_name']; //开户人
		$post_data ['bankAccount_idCard'] = strtolower($userinfo['zz_sfz']); //持卡人证件号码
		$post_data ['bankAccount_bankId'] = $userinfo['zz_bank']; //开户银行编号
		//$post_data ['bankAccount_contactLine'] = $userinfo['backcode']; //联行号
		$post_data ['bankAccount_tel'] = $userinfo['bankPhone']; //银行预留电话
		$post_data ['bankAccount_accountCode'] = $userinfo['zz_bankinfo']; //银行卡号

		$key = $configs['gd_key']; //授权渠道密钥
		$post_data['sign'] = $this->MakeGdSign($post_data,$key);
		$url = "https://api.swiftpass.cn/unifiedAuth";
		$result = $this->xmlpost($url,$this->ToXml($post_data));
        $rs = $this->FromXml($result);

        return $rs;
    }

    /**
     * 光大证件上传
     */
    public function ApigdUpoto($configs,$p_idcard_pic,$r_idcard_pic){
        $post_data = array();
        $post_data ['service'] = "ceb.ecount.upoto";
        $post_data ['nonce_str'] = $this->getNonceStr();
        $post_data ['mch_id'] = $configs['gd_mchId']; //授权渠道编号
        $key = $configs['gd_key'];
        $post_data['sign'] = $this->MakeGdSign($post_data,$key);
        if(class_exists('\CURLFile')){
            $post_data ['p_idcard_pic'] = new \CURLFile($_SERVER['DOCUMENT_ROOT'].ltrim($p_idcard_pic,"."));
            $post_data ['r_idcard_pic'] = new \CURLFile($_SERVER['DOCUMENT_ROOT'].ltrim($r_idcard_pic,"."));
        }else{
            $post_data ['p_idcard_pic'] = '@' . $_SERVER['DOCUMENT_ROOT'].ltrim($p_idcard_pic,".");
            $post_data ['r_idcard_pic'] = '@' . $_SERVER['DOCUMENT_ROOT'].ltrim($r_idcard_pic,".");
        }
        $url = "https://interface.swiftpass.cn/sppay-interface-war/api/cebMerchant/uploadPhoto.xml";
        $result = $this->FromXml($this->gd_Upotopost($url,$post_data));
        return $result;
    }

    /**
     * 光大post提交,证件上传
     */
    public function gd_Upotopost($url, $post_data, $timeout = 10){
            $ch = curl_init();
            curl_setopt ($ch, CURLOPT_URL, $url);
            if(!empty($post_data)){
                curl_setopt($ch, CURLOPT_POST, TRUE);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            }
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_HEADER, false);
            $file_contents = curl_exec($ch);
            curl_close($ch);
            return $file_contents;
    }

    /**
     * 光大门店查询
     */
    public function gd_getQuery($id){
        $c = D('SX/Configs');
        $configs = $c->loadConfigs();

        $post_data = array();
        $post_data ['service'] = "unified.cms.store.query";
        $post_data ['mch_id'] = $configs['gd_mchId']; //授权渠道编号
        $post_data ['nonce_str'] = $this->getNonceStr();
        $post_data ['outMerchantId'] = $id; //接入方系统商户号，精确查询必填

        $key = $configs['gd_key']; //授权渠道密钥
        $post_data['sign'] = $this->MakeGdSign($post_data,$key);
        $url = "https://api.swiftpass.cn/unifiedAuth";
        $result = $this->xmlpost($url,$this->ToXml($post_data));
        $rs = $this->FromXml($result);

        return $rs;
    }

    /**
     * 光大费率修改
     */
    public function gd_updateRate($id){
        $c = D('SX/Configs');
        $configs = $c->loadConfigs();

        $post_data = array();
        $post_data ['service'] = "unified.cms.store.query";
        $post_data ['mch_id'] = $configs['gd_mchId']; //授权渠道编号
        $post_data ['nonce_str'] = $this->getNonceStr();
        $post_data ['outMerchantId'] = $id; //接入方系统商户号，精确查询必填

        $key = $configs['gd_key']; //授权渠道密钥
        $post_data['sign'] = $this->MakeGdSign($post_data,$key);
        $url = "https://api.swiftpass.cn/unifiedAuth";
        $result = $this->xmlpost($url,$this->ToXml($post_data));
        $rs = $this->FromXml($result);

        return $rs;
    }

    /**
     * 获取光大提现链接
     */
    public function gd_getPay($info,$id){
        $post_data = array();
        $post_data ['service'] = "unified.spay.authent";
        $post_data ['mch_id'] = $info['gd_mchId']; //授权渠道编号
        $post_data ['nonce_str'] = $this->getNonceStr();
        $post_data ['mch_create_ip'] = $_SERVER['REMOTE_ADDR']; //ip
        $post_data ['merchant_id'] = $id; //商户号

        $key = $info['gd_key']; //授权渠道密钥
        $post_data['sign'] = $this->MakeGdSign($post_data,$key);
        $url = "https://api.swiftpass.cn/unifiedAuth";
        $result = $this->xmlpost($url,$this->ToXml($post_data));
        $rs = $this->FromXml($result);
        return $rs;
    }

    /**
     * 生成光大签名
     */
    public function MakeGdSign($values,$apikey)
    {
        //签名步骤一：将参数名转为小写，按字典序排序参数
        $values = array_change_key_case($values,CASE_LOWER);
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
     * 生成随机字符串
     */
    public static function getNonceStr($length = 32) 
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";  
        $str ="";
        for ( $i = 0; $i < $length; $i++ )  {  
            $str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
        } 
        return $str;
    }

    /**
     * POT XML提交
     */
    public function xmlpost($url , $xml, $second = 10){
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

    /**
     * 电话预约入口
     */
    public function addPhone(){
        $m = D('SX/Phone');  
        $this->ajaxReturn($m->addPhone());
    }
}