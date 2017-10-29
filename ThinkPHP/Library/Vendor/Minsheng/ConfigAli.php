<?php
class Config{
    //支付宝
    private $cfg = array(
        'url'=>'https://pay.swiftpass.cn/pay/gateway',  //支付请求url，无需更改
        'mchId'=>'101520000465',        //测试商户号，商户正式上线时需更改为自己的
        'key'=>'58bb7db599afc86ea7f7b262c32ff42f',   //测试密钥，商户需更改为自己的
        'version'=>'1.0'        //版本号
       );
    
    public function C($cfgName){
        return $this->cfg[$cfgName];
    }
}
?>