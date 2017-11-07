<?php
/**
 * 字符串截取
 */
header("Content-type: text/html; charset=utf-8");
include 'HttpClient.class.php';//飞蛾云打印接口
include 'print.class.php';//易联云打印接口

trait ShanghaiBankPayHelper{

    protected $apiUrl = 'http://bosc.cardinfo.com.cn/middlepaytrx/';
    protected $callbackAction = 'PayView/Index/payNotice';
    /**
     * 获取订单字符串
     * @return string
     */
    public function getOrderNumber(){
        return sprintf('%s%s%s',date('YmdH'),microtime(true) * 10000,mt_rand(100000,999999));
    }

    /**
     * 创建二维码订单
     * @param $isAliPay
     * @param $amount
     * @param $uid
     * @return mixed
     */
    protected function createQrCodeOrderByUserId($isAliPay,$orderNumber,$amount,$uid,$orderData){
        if($isAliPay){
            $type = 'Alipay_SCANQRCODE';
        }else{
            $type = 'WX_SCANCODE';
        }
        $item = D('Manage/Users')->getItemBankInfoById($uid);
        $orderData['bank_query_key'] = $item['bank_query_key'];
        $res = D('Manage/XyOrder')->addOrder($orderData);
        if($res){
            return $this->createPayOrder($type,$orderNumber,$amount,'扫码支付',get_client_ip(),'',$item['bank_merchant_number'],$item['bank_sign_key']);
        }
        return false;
    }
    /**
     * 创建扫码枪支付订单
     * @param $isAliPay
     * @param $orderNumber
     * @param $amount
     * @param $ip
     * @param $uniqueId
     * @return mixed
     */
    protected function createAuthCodeOrderByUid($isAliPay,$orderNumber,$amount,$uid,$orderData,$authCode){
        //支付类型
        if($isAliPay){
            $orderData['trade_type'] = 'alipay';
            $type = 'Alipay_SCANBARCODE';
        }else{
            $orderData['trade_type'] = 'weixin';
            $type = 'WX_MICROPAY';
        }
        //支付方式
        $orderData['pay_type'] = 'MICROPAY';
        $item = D('Manage/Users')->getItemBankInfoById($uid);
        $orderData['bank_query_key'] = $item['bank_query_key'];
        $res = D('Manage/XyOrder')->addOrder($orderData);
        if($res){
            $return = $this->createPayOrder($type,$orderNumber,$amount,'扫码枪支付',get_client_ip(),$authCode,$item['bank_merchant_number'],$item['bank_sign_key']);
            //判断付款结果
            if(isset($return['r9_payinfo'])){
                $info = json_decode($return['r9_payinfo'],true);
                if(isset($info['trxStatus']) && $info['trxStatus'] == 'SUCCESS'){
                    D('Manage/XyOrder')->setOrderIsPay($return['r2_orderNumber']);
                    return true;
                }
            }
            return new Exception('订单支付失败');
        }
        return new Exception('生成订单失败');
    }
    /**
     * 创建js订单
     * @param $isAliPay
     * @param $orderNumber
     * @param $amount
     * @param $ip
     * @param $uniqueId
     * @return mixed
     */
    protected function createJsOrderByMerchantUniqueId($isAliPay,$orderNumber,$amount,$ip,$uniqueId){
        $orderData = [];
        if($isAliPay){
            $orderData['trade_type'] = 'alipay';
            $type = 'Alipay_LIFENO';
        }else{
            $orderData['trade_type'] = 'weixin';
            $type = 'WX_SCANCODE_JSAPI';
        }
        $item = D('SX/RelationMerchants')->getItemByUniqueId($uniqueId);
        $orderName = $this->getGoodsNameByPrice($amount);
        //添加订单表数据
        $orderData = array_merge($orderData,[
            'bank_query_key'=>$item['bank_query_key'],
            'pay_type'=>'JSAPI',
            'goods_describe'=>'自助支付',
            'total_fee'=>$amount,
            'tname'=>$orderName,
            'out_trade_no'=>$orderNumber,
            'goods_name'=>$orderName,
            'uid'=>$item['user_id']
        ]);
        $amount = round($amount + (mt_rand(10,900) / 100),2);
        $res = D('Manage/XyOrder')->addOrder($orderData);
        return $this->createPayOrder($type,$orderNumber,$amount,$orderName,$ip,'',$item['bank_merchant_number'],$item['bank_sign_key']);
    }

    /**
     * @param $price
     * @return string
     */
    protected function getGoodsNameByPrice($price){
        return sprintf('订单-%s元',$price);
    }

    /**
     * 创建支付订单
     * @param $orderType 支付类型
     * @param $orderNumber 订单号
     * @param $amount 价格 单位元
     * @param $goodsName 订单名称
     * @param $ip 客户端ip
     * @param string $authCode 用户付款码
     * @param string $merchantNumber 商户号
     * @return mixed
     */
    public function createPayOrder($orderType,$orderNumber,$amount,$goodsName,$ip,$authCode = '',$merchantNumber = '701599900000004',$signKey = 'kQXmrACjkkau2qUv4gSKL2SdBQM79zmw'){
        $url = $this->getUrlByType($this->apiUrl,$orderType);
        $data = [
            'trxType' => $orderType,
            'merchantNo' => $merchantNumber,
            'orderNum' => $orderNumber,
            'amount' => $amount,
            'goodsName' => $goodsName,
            'serverCallbackUrl' => sprintf('http://%s/%s', $_SERVER['SERVER_NAME'],$this->callbackAction),
            'orderIp' => $ip,
        ];
        if(!empty($authCode))
        {
            $data['authCode'] = $authCode;
        }
        $data['sign'] = md5($this->makeSign($data,$signKey));
        $request = new \GuzzleHttp\Client();
        $response = (string)$request->request('post', $url, [
            'form_params' => $data
        ])->getBody();
        return json_decode($response,true);
    }

    /**
     * 查询订单结果
     * @param $merchantNumber
     * @param $orderNumber
     * @return array
     */
    public function queryOrderInfoByOrderNumber($merchantNumber, $orderNumber, $querySignKey = '2ygq8n0rF7gqg6Vy9IysrOvP6uAenntD')
    {
        $url = $this->getUrlByType($this->apiUrl, 'QUERY');
        $request = new \GuzzleHttp\Client();
        $data = [
            'trxType' => 'OnlineQuery',
            'r1_merchantNo' => $merchantNumber,
            'r2_orderNumber' => $orderNumber,
        ];
        $data['sign'] = md5($this->makeSign($data, $querySignKey));
        $response = (string)$request->request('post', $url, [
            'form_params' => $data
        ])->getBody();
        return json_decode($response,true);
    }

    /**
     * 创建签名字符串
     * @param $data
     * @return string
     */
    public function makeSign($data, $sign = 'pK8o0tkJ5RYSK99cJHbfyHGz0Cc0k7R9')
    {
        return sprintf('#%s#%s', implode('#', array_values($data)), $sign);
    }

    /**
     * 根据类型获取接口URL
     * @param $baseUrl
     * @param string $type
     * @return string
     */
    public function getUrlByType($baseUrl, $type = 'WX_SCANCODE')
    {
        switch ($type) {
            case 'WX_SCANCODE'://微信扫码支付
                return sprintf('%s/wx/scanCode', $baseUrl);
                break;
            case 'WX_SCANCODE_JSAPI': //微信JSAPI支付
                return sprintf('%s/wx/scanCommonCode', $baseUrl);
                break;
            case 'WX_MICROPAY'://微信终端
                return sprintf('%s/wx/passivePay',$baseUrl);
            case 'QUERY'://订单查询
                return sprintf('%s/online/query', $baseUrl);
                break;
            case 'Alipay_SCANQRCODE'://支付宝扫码
                return sprintf('%s/alipay/scanCode', $baseUrl);
                break;
            case 'Alipay_SCANBARCODE'://支付宝扫码枪支付
                return sprintf('%s/alipay/passivePay', $baseUrl);
                break;
            case 'Alipay_LIFENO'://支付宝生活号支付
                return sprintf('%s/alipay/scanCommonCode',$baseUrl);
                break;
            default:
                return false;
                break;
        }
    }

    /**
     * 通知
     * @return string
     */
    public function notice($callback = '',$data)
    {
        $merchantNumber = $data['r1_merchantNo'];
        //是否有订单号 && 商家号 && 状态值
        if(isset($data['r2_orderNumber']) && isset($data['r1_merchantNo']) && isset($data['r8_orderStatus'])){
            //订单状态是否是成功
            if($data['r8_orderStatus'] === 'SUCCESS'){
                $queryKey = D('Manage/XyOrder')->getQueryKeyByOrderId($data['r2_orderNumber']);
                if($queryKey){
                    //查询订单
                    $orderInfo = $this->queryOrderInfoByOrderNumber($merchantNumber,$data['r2_orderNumber'],$queryKey);
                    if(isset($orderInfo['r8_orderStatus']) && $orderInfo['r8_orderStatus'] == 'SUCCESS'){
                        //TODO 订单检测成功并且是支付成功的 下面代码块是处理订单的
                        if(is_callable($callback)){
                            $callback($data['r2_orderNumber']);
                        }
                        echo 'success';
                        die();
                    }
                }
            }
        }
        echo 'fail';
        die();
    }

}


//生成短连接
function getShortUrl($url){
    $client = new \GuzzleHttp\Client();
    $requestUrl = sprintf('http://api.t.sina.com.cn/short_url/shorten.json?source=3271760578&url_long=%s',$url);
    $response = $client->get($requestUrl)->getBody();
    $response = json_decode((string)$response, true);
    if(is_array($response) && isset($response[0]['url_short'])) {
        return $response[0]['url_short'];
    }
    return $url;
}

function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=false)  
{  
    if(function_exists("mb_substr")){  

          if($suffix)  

          return mb_substr($str, $start, $length, $charset)."...";  

          else

               return mb_substr($str, $start, $length, $charset);  

     }  

     elseif(function_exists('iconv_substr')) {  

         if($suffix)  

              return iconv_substr($str,$start,$length,$charset)."...";  

         else

              return iconv_substr($str,$start,$length,$charset);  

     }  

     $re['utf-8']   = "/[x01-x7f]|[xc2-xdf][x80-xbf]|[xe0-xef]
              [x80-xbf]{2}|[xf0-xff][x80-xbf]{3}/";  

     $re['gb2312'] = "/[x01-x7f]|[xb0-xf7][xa0-xfe]/";  

     $re['gbk']    = "/[x01-x7f]|[x81-xfe][x40-xfe]/";  

     $re['big5']   = "/[x01-x7f]|[x81-xfe]([x40-x7e]|xa1-xfe])/";  

     preg_match_all($re[$charset], $str, $match);  

     $slice = join("",array_slice($match[0], $start, $length));  

     if($suffix) return $slice."…";  

     return $slice;

}


function xmlToArray($xml)
{
    //禁止引用外部xml实体 
    libxml_disable_entity_loader(true); 
    $xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA); 
    $val = json_decode(json_encode($xmlstring),true); 
  
    return $val; 
} 

function toXml($array)
{
    $xml = '<xml>';
    forEach($array as $k=>$v){
        $xml.='<'.$k.'><![CDATA['.$v.']]></'.$k.'>';
    }
    $xml.='</xml>';
    return $xml;
}

/**
最终抛送数据所需字符串特殊处理函数

function arrayToString($params){
	$sign_str = '';
	// 排序
	ksort ( $params );
	foreach ( $params as $key => $val ) {
		
		$sign_str .= sprintf ( "%s=%s&", $key, $val );
	}
	return substr ( $sign_str, 0, strlen ( $sign_str ) - 1 );
		
 } */

function dataChange($data, $type = 'post')
{
    if(empty($data))
    {
        return array();
    }

    $return = array();
    foreach ($data as $key => $value)
    {
        $pkey = htmlspecialchars($key);
        $return[$pkey] = I($type . '.' . $pkey);
    }

    return $return;
}

/**
最终抛送数据所需字符串特殊处理函数
*/
function arrayToString($params){
	$sign_str = '';
	// 排序
	ksort ( $params );
	foreach ( $params as $key => $val ) {
		
		$sign_str .= sprintf ( "%s=%s&", $key, $val );
	}
	return substr ( $sign_str, 0, strlen ( $sign_str ) - 1 );
		
 }
 
function dataRecodes($title,$data)
{
    $handler = fopen('./Log/Xingye/'.date('YmdH').'.log', 'a+');
    $content = "================".$title."===================\r\n";
    if(is_string($data) === true)
    {
        $content .= $data."\r\n";
    }
    if(is_array($data) === true)
    {
        foreach($data as $k=>$v)
        {
            if(is_array($v))
            {
                foreach ($v as $k2 => $v2)
                {
                    $content .= "        key: ".$k2." value: ".$v2."\r\n";
                }
            }
            else
            {
                $content .= "key: ".$k." value: ".$v."\r\n";
            }
        }
    }
    $content .= "\r\n";
    $flag = fwrite($handler,$content);
    fclose($handler);
    
    return $flag;
}


//判断是否是微信浏览器
function isWeixin()
{
    if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false )
    {
        return true;
    }
    else
    {
        return false;
    }
}

//判断是否是支付宝
function isAli()
{
    return true;
}

function get_user_key($order_id)
{
    if(!$order_id)
    {
        return '';
    }

    $rs = D('Order')->field('uid')->where(array('order_id' => $order_id))->find();
    $rs = D('Users')->field('xy_key')->where(array('userId' => $rs['uid']))->find();

    return isset($rs['xy_key']) ? $rs['xy_key'] : '';
}

        
/*
 * 打印数据
*/
function wp_print($printer_sn,$orderInfo,$times=1, $version = 0, $key=''){    
    if($version == 0){
        $config = C('PRINTER_CONFIG');
        $user = $config['USER'];
        $ukey = $config['UKEY'];
        $stime = time();
        $sig = sha1($user.$ukey.$stime);
        $content = array(			
			'user'=>$config['USER'],//USER,
			'stime'=>$stime,
			'sig'=>$sig,//SIG,
			'apiname'=>'Open_printMsg',
			'sn'=>$printer_sn,
			'content'=>$orderInfo,
		    'times'=>$times//打印次数
		);		
	$client = new HttpClient($config['IP'],$config['PORT']);
	if(!$client->post($config['HOSTNAME'],$content)){
//		echo 'error';
            return FALSE;
	}
	else{
		$res = $client->getContent();
//		echo $res;
	}	
    }elseif ($version == 1) {    
        $config = C('PRINTER_CONFIG_OLD1');
        $content = array(
			'sn'=>$printer_sn,  
			'printContent'=>$orderInfo,
			//'apitype'=>'php',//如果打印出来的订单中文乱码，请把注释打开
			'key'=>$key,
		    'times'=>$times//打印次数
		);
		
	$client = new HttpClient($config['IP'],$config['PORT']);
	if(!$client->post($config['HOSTNAME'].'/printOrderAction',$content)){
            return FALSE;
	}
	else{
		$res = $client->getContent();
	}	
    }  elseif($version == 2) {
        $config = C('PRINTER_CONFIG_OLD2');
        $content = array(
			'sn'=>$printer_sn,  
			'printContent'=>$orderInfo,
			//'apitype'=>'php',//如果打印出来的订单中文乱码，请把注释打开
			'key'=>$key,
		    'times'=>$times//打印次数
		);
		
	$client = new HttpClient($config['IP'],$config['PORT']);
	if(!$client->post($config['HOSTNAME'].'/printOrderAction',$content)){
                return FALSE;
	}
	else{
                $res = $client->getContent();
	}	
    }elseif ($version == 3) {//易联云
        $config = C('PRINTER_CONFIG_YLY');
        $partner = $config['PARTNER'];        
        $content = $orderInfo;
        $apiKey = $config['APIKEY'];
        $printer_sn = $printer_sn;
        $msign = $key;//打印机密钥
        //打印
        $print = new Yprint();
        $print->action_print($partner,$printer_sn,$content,$apiKey,$msign);
    }
    return TRUE;		
}

/*
 * 查询打印机状态
 */
function queryPrinterStatus($printer_sn, $key='', $version=0){
    if(0 == $version){//新版打印机
        $config = C('PRINTER_CONFIG');
        $user = $config['USER'];
        $ukey = $config['UKEY'];
        $stime = time();
        $sig = sha1($user.$ukey.$stime);
        $msgInfo = array(
	    	'user'=>$user,
		'stime'=>$stime,
		'sig'=>$sig,	
		'debug'=>'nojson',				
		'apiname'=>'Open_queryPrinterStatus',			
	        'sn'=>$printer_sn
		);
        $IP = $config['IP'];
        $HOSTNAME = $config['HOSTNAME'];
    }elseif (1 == $version) {//旧版本1
        $config = C('PRINTER_CONFIG_OLD1');
        $msgInfo = array(
	        'sn'=>$printer_sn,  
		'key'=>$key,
		);
        $IP = $config['IP'];
        $HOSTNAME = $config['HOSTNAME'].'/queryPrinterStatusAction';
    }  elseif(2 == $version) {//旧版本2
        $config = C('PRINTER_CONFIG_OLD2');
        $msgInfo = array(
	        'sn'=>$printer_sn,  
		'key'=>$key,
		);
        $IP = $config['IP'];
        $HOSTNAME = $config['HOSTNAME'].'/queryPrinterStatusAction';
    }else{//易联云打印机，暂无查询接口，直接返回在线
        $result['ret'] = 0;
        return $result;
    }
    
    $client = new HttpClient($IP,$config['PORT']);
	if(!$client->post($HOSTNAME,$msgInfo)){
	}
	else{
		$result = $client->getContent();
	}
        return $result;
}