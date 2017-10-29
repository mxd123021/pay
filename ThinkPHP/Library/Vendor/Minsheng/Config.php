<?php
class Config{
    //非支付宝
    private $cfg = array(
        'url'=>'https://gzwkzf.cmbc.com.cn/payment-gate-web/gateway/api/backTransReq',
        'mchId'=>'7551000001',
        'key'=>'9d101c97133837e13dde2d32a5054abb',
        'version'=>'1.0'
       );
    
    public function C($cfgName){
        return $this->cfg[$cfgName];
    }
}
?>