<?php

/**
 * 后台应答类
 * ============================================================================
 * api说明：
 * getKey()/setKey(),获取/设置密钥
 * getContent() / setContent(), 获取/设置原始内容
 * getParameter()/setParameter(),获取/设置参数值
 * getAllParameters(),获取所有参数
 * isTenpaySign(),是否威富通签名,true:是 false:否
 * getDebugInfo(),获取debug信息
 * 
 * ============================================================================
 *
 */

class ClientResponseHandler  {
	
	/** 密钥 */
	var $key;
	
	/** 应答的参数 */
	var $parameters;
	
	/** debug信息 */
	var $debugInfo;
	
	//原始内容
	var $content;
	
	function __construct() {
		$this->ClientResponseHandler();
	}
	
	function ClientResponseHandler() {
		$this->key = "";
		$this->parameters = array();
		$this->debugInfo = "";
		$this->content = "";
	}
		
	/**
	*获取密钥
	*/
	function getKey() {
		return $this->key;
	}
	
	/**
	*设置密钥
	*/	
	function setKey($key) {
		$this->key = $key;
	}
	
	//设置原始内容
	function setContent($content) {
		$this->content = $content;
		
		$xml = simplexml_load_string($this->content);
		$encode = $this->getXmlEncode($this->content);
		
		if($xml && $xml->children()) {
			foreach ($xml->children() as $node){
				//有子节点
				if($node->children()) {
					$k = $node->getName();
					$nodeXml = $node->asXML();
					$v = substr($nodeXml, strlen($k)+2, strlen($nodeXml)-2*strlen($k)-5);
					
				} else {
					$k = $node->getName();
					$v = (string)$node;
				}
				
				if($encode!="" && $encode != "UTF-8") {
					$k = iconv("UTF-8", $encode, $k);
					$v = iconv("UTF-8", $encode, $v);
				}
				
				$this->setParameter($k, $v);			
			}
		}
	}
        
        //设置原始内容
	function setMSContent($content) {
            $this->content = $content;		
            $tcode = explode('&', $content);				
            foreach ($tcode as $node){
                //有子节点
                $value = explode('=', $node, 2);
                $this->setParameter($value[0], $value[1]);			
            }		
	}
        
	
	//获取原始内容
	function getContent() {
		return $this->content;
	}
	
	/**
	*获取参数值
	*/	
	function getParameter($parameter) {
		return isset($this->parameters[$parameter])?$this->parameters[$parameter] : '';
	}
	
	/**
	*设置参数值
	*/	
	function setParameter($parameter, $parameterValue) {
		$this->parameters[$parameter] = $parameterValue;
	}
	
	/**
	*获取所有请求的参数
	*@return array
	*/
	function getAllParameters() {
		return $this->parameters;
	}	
	
	/**
	*是否威富通签名,规则是:按参数名称a-z排序,遇到空值的参数不参加签名。
	*true:是
	*false:否
	*/	
	function isTenpaySign() {
		$signPars = "";
		ksort($this->parameters);
		foreach($this->parameters as $k => $v) {
			if("sign" != $k && "" != $v) {
				$signPars .= $k . "=" . $v . "&";
			}
		}
		$signPars .= "key=" . $this->getKey();
		
		$sign = strtolower(md5($signPars));
		
		$tenpaySign = strtolower($this->getParameter("sign"));
				
		//debug信息
		$this->_setDebugInfo($signPars . " => sign:" . $sign .
				" tenpaySign:" . $this->getParameter("sign"));
		
		return $sign == $tenpaySign;
		
	}
        
        /**
	*是否民生银行签名,规则是:按参数名称a-z排序,遇到空值的参数不参加签名。
	*true:是
	*false:否
	*/	
	function isMSSign() { 
            $sign_str = '';
            // 排序
            ksort($this->parameters);
            foreach ($this->parameters as $key =>$val){
                if ($key == 'signature'){
                        continue;
                }
                $sign_str .= sprintf ( "%s=%s&", $key, $val );
            }
            $data =  substr ( $sign_str, 0, strlen ( $sign_str ) - 1 );
            
            
            $pubKey = file_get_contents(dirname ( __FILE__ ) .'/key/850440053991421_pub.pem');
//            dataRecodes('私钥1sign=', $pubKey);
            //转换为openssl密钥，必须是没有经过pkcs8转换的私钥
            $res = openssl_get_publickey($pubKey);
//            dataRecodes('私钥21sign=', $res);
            //调用openssl内置签名方法，生成签名$sign
//            openssl_sign($data, $sign, $res);
//            $sign = $this->pubkeyEncrypt($data, $res);
          
//　　　　$public_key=openssl_get_publickey($public_content);

            $sign=base64_decode($this->getParameter("signature"));//得到的签名
            $original_str=$data;
            $result=(bool)openssl_verify($original_str,$sign,$res);
           
//            dataRecodes('私钥33sign=', $sign);
            //释放资源
            openssl_free_key($res);
//            $sign = urlencode(base64_encode($sign));
//            dataRecodes('私钥4sign=', $result);
//            $sign = strtolower($sign);
//		
//            $signature = strtolower($this->getParameter("signature"));
//            $this->setParameter("signature", $sign);
            return $result;//$sign == $signature;
            
            
	
	}
        
        //公钥加密  
        public function pubkeyEncrypt($source_data, $pu_key) {  
            $data = "";  
            $dataArray = str_split($source_data, 117);  
            foreach ($dataArray as $value) {  
                 $encryptedTemp = "";   
                 openssl_public_encrypt($value,$encryptedTemp,$pu_key);//公钥加密  
                 $data .= base64_encode($encryptedTemp);  
             }  
             return $data;  
        }  
        
//        public function pubkeyEncrypt($data,$pubkey){
//            $panText = '';
//            openssl_public_encrypt($data,$panText,$pubkey,OPENSSL_PKCS1_PADDING);
//            return	strtoupper($panText);   
//            }
	
	/**
	*获取debug信息
	*/	
	function getDebugInfo() {
		return $this->debugInfo;
	}
	
	//获取xml编码
	function getXmlEncode($xml) {
		$ret = preg_match ("/<?xml[^>]* encoding=\"(.*)\"[^>]* ?>/i", $xml, $arr);
		if($ret) {
			return strtoupper ( $arr[1] );
		} else {
			return "";
		}
	}
	
	/**
	*设置debug信息
	*/	
	function _setDebugInfo($debugInfo) {
		$this->debugInfo = $debugInfo;
	}
	
	/**
	 * 是否财付通签名
	 * @param signParameterArray 签名的参数数组
	 * @return boolean
	 */	
	function _isTenpaySign($signParameterArray) {
	
		$signPars = "";
		foreach($signParameterArray as $k) {
			$v = $this->getParameter($k);
			if("sign" != $k && "" != $v) {
				$signPars .= $k . "=" . $v . "&";
			}			
		}
		$signPars .= "key=" . $this->getKey();
		
		$sign = strtolower(md5($signPars));
		
		$tenpaySign = strtolower($this->getParameter("sign"));
				
		//debug信息
		$this->_setDebugInfo($signPars . " => sign:" . $sign .
				" tenpaySign:" . $this->getParameter("sign"));
		
		return $sign == $tenpaySign;		
		
	
	}
	
}


?>