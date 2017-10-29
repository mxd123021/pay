<?php
function getWxNonceStr($length = 32) 
{
    $chars = "abcdefghijklmnopqrstuvwxyz0123456789";  
    $str ="";
    for ( $i = 0; $i < $length; $i++ )  {  
        $str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
    } 
    return $str;
}

    /**
     * 数组转XML
     */
    function ToXml($values)
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

    /**
     * 微信XML提交
     */
    function xmlpost($url ,$xml, $second = 5){
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

    function FromXml($xml)
    {   
        if(!$xml){
            return  -1;
        }
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);        
        return $values;
    }

    /**
     * 生成签名
     */
    function MakeSign($values,$apikey)
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

function wx_post($url, $post_data, $timeout = 5){
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
            echo $file_contents;
            exit;
            curl_close($ch);
            return json_decode(json_encode(json_decode($file_contents)),true);
    }

    function MakeGdSign($values,$apikey)
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


 function wx_xmlpost($url ,$xml, $useCert = false, $sslcert_path = "", $sslkey_path = "", $sslca_path = "", $second = 5){
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
//刷卡支付
/*$post_data = array();
$post_data['service'] = "unified.trade.micropay"; //接口类型
$post_data['mch_id'] = "100520020829"; //商户号
$post_data ['sign_agentno'] = "105560000014"; //渠道编号
$post_data ['out_trade_no'] = $post_data['mch_id'].date("YmdHis"); //订单号
$post_data ['body'] = "商有通刷卡测试"; //商品描述
$post_data ['total_fee'] = 1; //付款金额 分
$post_data ['mch_create_ip'] = "127.0.0.1"; //IP地址
$post_data ['auth_code'] = "283605592987813210"; //刷卡支付授权码
$post_data ['nonce_str'] = getWxNonceStr(); //随机字符串
$key = "8a48a11670c932a1350b42587df43d6c"; //渠道密钥
$post_data['sign'] = MakeSign($post_data,$key);

$url = "https://pay.swiftpass.cn/pay/gateway";
$result = xmlpost($url,ToXml($post_data));
$rs = FromXml($result);
print_r($rs);*/
//微信支付成功返回结果
/*Array ( [appid] => wxc3d2e734ae326831 [attach] => 1,,,0,3,微信刷卡支付,刷卡支付 [bank_type] => CFT [charset] => UTF-8 [fee_type] => CNY [is_subscribe] => N [mch_id] => 100520020829 [nonce_str] => 1473995987728 [openid] => o-hyhjltcT4XNyi8C61gVfWuNl-o [out_trade_no] => 10052002082920160916111943 [out_transaction_id] => 4009002001201609164089592658 [pay_result] => 0 [result_code] => 0 [sign] => 8DE345562B0D05AA91B3F8F96382333A [sign_agentno] => 105560000014 [sign_type] => MD5 [status] => 0 [sub_appid] => wxf43c201f7bad723b [sub_is_subscribe] => Y [sub_openid] => ouQdrwZBrENNs3oCuUYYyv2O69ME [time_end] => 20160916111947 [total_fee] => 1 [trade_type] => pay.weixin.micropay [transaction_id] => 100520020829201609166071807930 [uuid] => 617506c7f507b452313935c0c3ae7e5e [version] => 2.0 )*/
//支付宝成功返回结果
/*Array ( [appid] => 2016072501663823 [buyer_logon_id] => 838***@qq.com [buyer_user_id] => 2088802297993722 [charset] => UTF-8 [coupon_fee] => 0 [fee_type] => CNY [fund_bill_list] => [{"amount":"0.01","fundChannel":"DISCOUNT","realAmount":null}] [mch_id] => 100520020829 [nonce_str] => 1475504292279 [openid] => 2088802297993722 [out_trade_no] => 10052002082920161003221810 [out_transaction_id] => 2016100321001004720260803367 [pay_result] => 0 [result_code] => 0 [sign] => C1D3229A802CD97D18CC65AFDADE784C [sign_agentno] => 105560000014 [sign_type] => MD5 [status] => 0 [time_end] => 20161003221812 [total_fee] => 1 [trade_type] => pay.alipay.micropay [transaction_id] => 100520020829201610034077176310 [uuid] => 70c316abaf3b0c3218cbb019298c03ba [version] => 2.0 )*/
//微信支付出错返回结果
//Array ( [charset] => UTF-8 [err_code] => AUTHCODEEXPIRE [err_msg] => 请扫描微信支付被扫条码/二维码 [mch_id] => 100570000241 [need_query] => N [nonce_str] => Yg13YyAj5XAhyoSN [result_code] => 1 [sign] => 7D63CAC26B27F6B6FCF5A45F09DFBB10 [sign_agentno] => 075020000001 [sign_type] => MD5 [status] => 0 [version] => 2.0 )
//支付宝支付出错返回结果
/*Array ( [charset] => UTF-8 [err_code] => SOUNDWAVE_PARSER_FAIL [err_msg] => 支付失败，获取顾客账户信息失败，请顾客刷新付款码后重新收款，如再次收款失败，请联系管理员处理。[SOUNDWAVE_PARSER_FAIL] [mch_id] => 100570000241 [need_query] => N [result_code] => 1 [sign] => 94CEE7E7437DBD32ADB3AF92728B4AA2 [sign_agentno] => 075020000001 [sign_type] => MD5 [status] => 0 [version] => 2.0 )*/

/*//退款成功返回结果
Array ( [charset] => UTF-8 [mch_id] => 100520020829 [nonce_str] => j039pgqo41v8iiws49oxrtdibjsjbo2r [out_refund_no] => 20160916130603 [out_trade_no] => 10052002082920160916123559 [refund_channel] => ORIGINAL [refund_fee] => 1 [refund_id] => 100520020829201609163045482079 [result_code] => 0 [sign] => 5460210FF125E0D666CD4D16D2549309 [sign_agentno] => 105560000014 [sign_type] => MD5 [status] => 0 [trade_type] => pay.weixin.micropay [transaction_id] => 100520020829201609161072007098 [version] => 2.0 )*/

//二维码支付
/*$post_data = array();
$post_data['service'] = "pay.alipay.jspay"; //pay.weixin.native pay.alipay.jspay
$post_data['mch_id'] = "100520020829";
$post_data ['sign_agentno'] = "105560000014";
$post_data ['out_trade_no'] = $post_data['mch_id'].date("YmdHis");
$post_data ['body'] = "商有通扫码测试";
$post_data ['total_fee'] = 1;
$post_data ['mch_create_ip'] = "127.0.0.1";
$post_data ['notify_url'] = "http://www.amacm.com/"; //通知地址
$post_data ['nonce_str'] = getWxNonceStr();
$key = "8a48a11670c932a1350b42587df43d6c";
$post_data['sign'] = MakeSign($post_data,$key);

$url = "https://pay.swiftpass.cn/pay/gateway";
$result = xmlpost($url,ToXml($post_data));
$rs = FromXml($result);
print_r($rs);*/



//证件上传接口
/*function uploads(){
$post_data = array();
$post_data ['service'] = "ceb.ecount.upoto";
$post_data ['nonce_str'] = getWxNonceStr();
$post_data ['mch_id'] = "100590000043"; //授权渠道编号
$key = "f2e7265d026504e4c1d198f3bb116bd2";
$post_data['sign'] = MakeSign($post_data,$key);
$post_data ['p_idcard_pic'] = new CURLFile(realpath('E:\wamp\www\amacm\Upload\uploads\2016-07\57976bd04cf9e.jpg'));
$post_data ['r_idcard_pic'] = new CURLFile(realpath('E:\wamp\www\amacm\Upload\uploads\2016-07\57976bd04cf9e.jpg'));
$url = "http://support1.test.swiftpass.cn/sppay-interface-war/api/cebMerchant/uploadPhoto.xml";
$result = wx_post($url,$post_data);
return $result;
}*/

/*$uploads = uploads();
//门店进件接口
$post_data = array();
$post_data ['service'] = "ceb.cms.store.new";
$post_data ['nonce_str'] = getWxNonceStr();
$post_data ['mch_id'] = "105560000014"; //授权渠道编号

$post_data ['merchant_name'] = "测试11"; //商户名称
$post_data ['merchant_parentMerchant'] = "105520000014"; //大商户、普通商户无父商户.直营商户、加盟商户的父商户为大商户，门店进件必填
$post_data ['merchant_outMerchantId'] = "006"; //接入方平台商户号，与威富通商户号一一对应，接入方唯

        $post_data ['merchantDetail_industryId'] = "142"; //行业类别编号
        $post_data ['merchantDetail_province'] = "240000"; //省份编号
        $post_data ['merchantDetail_city'] = "240100"; //城市编号
        $post_data ['merchantDetail_address'] = "(121.429,31.2423)"; //商户地址，经纬度
        $post_data ['merchantDetail_principal'] = "魏淑遥"; //负责人
        $post_data ['merchantDetail_mobile'] = "18688445515"; //负责人手机号

    //商户结算费率，参数单位为‰，如商户费率为6‰，则报文值为6。结算费率不可超过接口双方约定的费率范围
        $post_data ['mchPayConf_apiCode_1'] = "pay.weixin.native"; //扫码支付
        $post_data ['mchPayConf_billRate_1'] = 0.5; //商户结算费率，参数单位为‰，如商户费率为6‰，则报文值为6。结算费率不可超过接口双方约定的费率范围
        $post_data ['mchPayConf_apiCode_2'] = "pay.weixin.micropay"; //刷卡支付
        $post_data ['mchPayConf_billRate_2'] = 0.5;
        $post_data ['mchPayConf_apiCode_3'] = "pay.weixin.jspay"; //公众号支付
        $post_data ['mchPayConf_billRate_3'] = 0.5;

        $post_data ['bankAccount_accountCode'] = "6217007100019004493"; //银行卡号
        $post_data ['bankAccount_bankId'] = "3"; //开户银行编号
        $post_data ['bankAccount_accountName'] = "魏淑遥"; //开户人
        $post_data ['bankAccount_accountType'] = "2"; //账户类型
        $post_data ['bankAccount_contactLine'] = "105701000438"; //联行号
        $post_data ['bankAccount_bankName'] = "中国建设银行股份有限公司贵阳花溪支行"; //开户支行名称
        $post_data ['bankAccount_province'] = "240000"; //开户支行所在省编号
        $post_data ['bankAccount_city'] = "240100"; //开户支行所在市编号
        $post_data ['bankAccount_idCardType'] = "1"; //
        $post_data ['bankAccount_idCard'] = "522129199206153014"; //持卡人证件号码
        $post_data ['bankAccount_address'] = "地址信息缺省"; //持卡人地址，剔除省市的具体地址
        $post_data ['bankAccount_tel'] = "18688445515"; //银行预留电话
        $post_data ['bankAccount_accountEnName'] = "aaa"; //持卡人英文名
        $post_data ['bankAccount_accountExpiredDate'] = "2020-3-1"; //证件有效期
        $post_data ['bankAccount_accountPostcode'] = "550000"; //邮编
        $post_data ['indentityPhoto'] = $uploads['indentityPhoto']; //证件照路径，由证件上传接口获得

        $key = "8a48a11670c932a1350b42587df43d6c"; //授权渠道密钥
        $post_data['sign'] = MakeGdSign($post_data,$key);
        $url = "https://api.swiftpass.cn/unifiedAuth";
        $result = wx_xmlpost($url,ToXml($post_data));
        print_r(ToXml($post_data));
        $rs = FromXml($result);
        print_r($rs);*/


/*<xml><activeapi><![CDATA[pay.weixin.native]]></activeapi>
<errmsg><![CDATA[ ]]></errmsg>
<merchantid><![CDATA[100550020533]]></merchantid>
<nonce_str><![CDATA[1473620788478]]></nonce_str>
<outmerchantid><![CDATA[00]]></outmerchantid>
<respcode><![CDATA[00]]></respcode>
<sign><![CDATA[23CC0591E6E8E7E25DEB5483AAD11DA3]]></sign>
</xml>*/

//门店修改接口
/*$post_data = array();
$post_data ['service'] = "ceb.account.modify";
$post_data ['nonce_str'] = getWxNonceStr();
$post_data ['mch_id'] = "105560000014"; //授权渠道编号

$post_data ['merchant_id'] = "100550021034"; //账户类型

        $post_data ['bankAccount_accountName'] = "魏淑遥"; //开户人
        $post_data ['bankAccount_idCard'] = "522129199206153014"; //持卡人证件号码
        $post_data ['bankAccount_bankId'] = "3"; //开户银行编号
        $post_data ['bankAccount_contactLine'] = "105701000438"; //联行号
        $post_data ['bankAccount_tel'] = "18688445515"; //银行预留电话
        $post_data ['bankAccount_accountCode'] = "6217007100019004493"; //银行卡号
$key = "8a48a11670c932a1350b42587df43d6c"; //授权渠道密钥
$post_data['sign'] = MakeGdSign($post_data,$key);
$url = "https://api.swiftpass.cn/unifiedAuth";
$result = wx_xmlpost($url,ToXml($post_data));
print_r($result);*/

//H5提现接口
/*$post_data = array();
$post_data ['service'] = "unified.spay.authent";
$post_data ['mch_id'] = "105560000014"; //授权渠道编号
$post_data ['nonce_str'] = getWxNonceStr();
$post_data ['mch_create_ip'] = "127.0.0.1"; //ip
$post_data ['merchant_id'] = "100520020829"; //商户号

$key = "8a48a11670c932a1350b42587df43d6c"; //授权渠道密钥
$post_data['sign'] = MakeGdSign($post_data,$key);
$url = "https://api.swiftpass.cn/unifiedAuth";
$result = wx_xmlpost($url,ToXml($post_data));
print_r($result);*/

//费率修改接口
/*$post_data = array();
$post_data ['partner'] = "105560000014"; //授权渠道编号
$post_data ['serviceName'] = "mch_bill_rate_deit";
$post_data ['dataType'] = "xml";
$post_data ['charset'] = "UTF-8";
$data  = array();
$data['merchantId'] = "100520020829";
$data['apiCode'] = "pay.weixin.jspay";
$data['billRate'] = "6";
$post_data ['data'] = ToXml($data);
$key = "8a48a11670c932a1350b42587df43d6c"; //授权渠道密钥
$post_data['dataSign'] = MakeGdSign($post_data,$key);
$url = "http://ccbzj.test.swiftpass.cn/sppay-interface-war/gateway";
$result = wx_xmlpost($url,ToXml($post_data));
print_r($result);*/


$appid = "2015120400912117";
$scope = "auth_base";
$redirect_uri = urlencode("http://www.amacm.com/alipay/index.php");
$url = "https://openauth.alipay.com/oauth2/publicAppAuthorize.htm?app_id=".$appid."&scope=".$scope."&redirect_uri=".$redirect_uri;
header("Location:".$url);
?>