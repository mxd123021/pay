<?php
/**
 * 字符串截取
 */
header("Content-type: text/html; charset=utf-8");
include 'HttpClient.class.php';//飞蛾云打印接口
include 'print.class.php';//易联云打印接口

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