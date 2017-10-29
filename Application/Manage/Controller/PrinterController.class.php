<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Manage\Controller;
class PrinterController extends BaseController {    
    
    protected $orderInfo = "";//订单信息
    protected $balanceInfo = "";//结算单
    protected $printer_sn = "";//打印机编码
    protected $printer_key = "";//打印机密钥
    protected $printer_ticketnum = 1;//订单打印联数
    protected $printer_version = 0;//打印机版本，0-2为飞蛾新旧版本打印机，3为易联云打印机
    /*
     * 增加打印机
     */
    public function addPrinter(){
        $this->isAjaxLogin();
        $m = D('Manage/Printer');
        $rs = $m->addPrinter();
        $this->ajaxReturn($rs);
    }
    
    /*
     * 删除打印机
     */
    public function delPrinter(){
        $this->isAjaxLogin();
        $m = D('Manage/Printer');
        $rs = $m->delPrinter();
        $this->ajaxReturn($rs);
    }   
    
    /*
     * 保存打印机信息
     */
    public function savePrinter(){
        $this->isAjaxLogin();
        $m = D('Manage/Printer');
        $rs = $m->savePrinter();
        $this->ajaxReturn($rs);
    }
    
    /*
     * 设置结算单数据
     */
    public function setBalanceInfo($orderlist, $balanceInfo=array(), $version = 0, $tag=1){
        if(empty($orderlist)){
            return;
        }
        $data = array();
        $data['totalMoney'] = 0;
        $data['totalNum'] = count($orderlist);
        $data['weixinMoney'] = 0;
        $data['weixinNum'] = 0;
        $data['aliMoney'] = 0;
        $data['aliNum'] = 0;
        $data['refund_num'] = 0;
        $data['refund_fee'] = 0;
        if(1 == $tag){//tag==1为新结算单打印
            $data['sbalanceTime'] = $balanceInfo['ebalanceTime']?$balanceInfo['ebalanceTime']:0;//$orderlist[0]['paytime'];
            $data['ebalanceTime'] = time();
        }else{//旧决算单打印
            $data['sbalanceTime'] = $balanceInfo['sbalanceTime'];//$orderlist[0]['paytime'];
            $data['ebalanceTime'] = $balanceInfo['ebalanceTime'];
        }
        
        foreach ($orderlist as $key => $order) {
            if($order['pay_way'] == 'weixin'){
                 $data['weixinNum'] += 1;
                 $data['weixinMoney'] += $order['goods_price'];         
                 
            }else{
                $data['aliNum'] += 1;
                $data['aliMoney'] += $order['goods_price'];
            }
            if(in_array($order['refund'], array(1, 2, 3))){
                     $data['refund_fee'] += $order['refund_fee'];
                     $data['refund_num'] += 1;
                 }
//            if($data['sbalanceTime'] > $order['paytime']){
//                $data['sbalanceTime'] = $order['paytime'];
//            }                        
        }
        $data['totalMoney'] = $data['weixinMoney'] + $data['aliMoney'];
        
        //获取商户信息
        $store = D('Manage/Stores')->getStore($orderlist[0]['storeid']);
        //获取收银员信息
        $ustaff = D('Manage/Ustaffs')->getUser($orderlist[0]['eid']);
        if($version == 0){//飞蛾打印机
            $this->balanceInfo = '<CB>结算单总计</CB><BR>';
            $this->balanceInfo .= '--------------------------------<BR>';
            $this->balanceInfo .= '商户名称：'.$store['business_name'].'<BR>';
            $this->balanceInfo .= '收银账户：'.$store['branch_name'].'<BR>';
            $this->balanceInfo .= '收银员：'.$ustaff['userName'].'<BR>';
            $this->balanceInfo .= '开始时间：'.date('Y-m-d H:i:s', $data['sbalanceTime']).'<BR>';
            $this->balanceInfo .= '结束时间：'.date('Y-m-d H:i:s', $data['ebalanceTime']).'<BR>';
            $this->balanceInfo .= '--------------------------------<BR>';
            $this->balanceInfo .= '类型    　　笔数      金额<BR>';
            $this->balanceInfo .= '--------------------------------<BR>';
            $this->balanceInfo .= '微信支付：'.'   '.$data['weixinNum'].'        '.$data['weixinMoney'].'<BR>';
            $this->balanceInfo .= '支付宝支付：'.' '.$data['aliNum'].'        '.$data['aliMoney'].'<BR>';
            $this->balanceInfo .= '--------------------------------<BR>';
            $this->balanceInfo .= '退款：'.'       '.$data['refund_num'].'        '.$data['refund_fee'].'<BR>';
            $this->balanceInfo .= '实收总计：'.'       '.$data['totalNum'].'        '.($data['totalMoney']-$data['refund_fee']).'<BR>';  
        }else{//易联云打印机
            $this->balanceInfo = '';
            $this->balanceInfo .= '<center><FS2>结算单总计</FS2></center>\n';
            $this->balanceInfo .= '******************************\n';
            $this->balanceInfo .= '商户名称：'.$store['business_name'].'\n';
            $this->balanceInfo .= '收银账户：'.$store['branch_name'].'\n';
            $this->balanceInfo .= '收银员：'.$ustaff['userName'].'\n';
            $this->balanceInfo .= '开始时间：'.date('Y-m-d H:i:s', $data['sbalanceTime']).'\n';
            $this->balanceInfo .= '结束时间：'.date('Y-m-d H:i:s', $data['ebalanceTime']).'\n';
            $this->balanceInfo .= '******************************\n';
            $this->balanceInfo .= '类型    　　笔数      金额\n';
            $this->balanceInfo .= '******************************\n';
            $this->balanceInfo .= '微信支付：'.'   '.$data['weixinNum'].'        '.$data['weixinMoney'].'\n';
            $this->balanceInfo .= '支付宝支付：'.' '.$data['aliNum'].'        '.$data['aliMoney'].'\n';
            $this->balanceInfo .= '******************************\n';
            $this->balanceInfo .= '退款：'.'       '.$data['refund_num'].'        '.$data['refund_fee'].'\n';
            $this->balanceInfo .= '实收总计：'.'   '.$data['totalNum'].'        '.($data['totalMoney']-$data['refund_fee']).'\n'; 
        }
        
        
        return $data;
    }
    
    /*
     * 设置交易订单打印数据
     */
    public function setOrderInfo($order, $version = 0){
        $store = D('Stores')->where('storeId='.$order['storeid'])->find();
        $staff = D('Ustaffs')->where('usId='.$order['eid'])->find();
        if(empty($store)){
            $store['business_name'] = '移动支付收银台';
            $store['branch_name'] = '总账户';
        }
        if(empty($staff)){
            $staff['userName'] = '默认收银员';
        }
        if($order['pay_way'] == 'alipay'){
            $order['pay_method'] = '支付宝';
        }elseif ($order['pay_way'] == 'weixin') {
            $order['pay_method'] = '微信支付';
        }
        
        
        $printer_info = D('Printer')->where(array('printer_sn'=>$this->printer_sn))->find();
        if(empty(printer_info)){
            $printer_info['printer_tip'] = '由畅远科技提供技术支持';
            $printer_info['printer_telephone'] = '3312399';
            $printer_info['printer_qrcode'] = 'http://x.eqxiu.com/s/RIdEgYPi';
        }
        //设置订单打印联数
        $this->printer_ticketnum = $printer_info['printer_ticketnum'];
        if(in_array($version, array(0, 1, 2))){//飞蛾
            $x = 1;
            $this->orderInfo = "";
            while ($x <= $this->printer_ticketnum){
                //设置打印内容格式
                $this->orderInfo .= '------------第'.$x.'联------------<BR><BR>';
                $this->orderInfo .= '<CB>支付订单</CB><BR>';
                $this->orderInfo .= '--------------------------------<BR>';
                $this->orderInfo .= '商户名称：'.$store['business_name'].'<BR>';
                $this->orderInfo .= '收银账户：'.$store['branch_name'].'<BR>';
                $this->orderInfo .= '收银员：'.$staff['userName'].'<BR>';
                $this->orderInfo .= '日期：'.date('Y-m-d H:i:s', $order['paytime']).'<BR>';
                $this->orderInfo .= '--------------------------------<BR>';
                $this->orderInfo .= '消费金额：'.$order['goods_price'].'元<BR>';
                $this->orderInfo .= '优惠金额：0.00元'.'<BR>';
                $this->orderInfo .= '退款金额：'.$order['refund_fee'].'元<BR>';
                $this->orderInfo .= '实收金额：'.($order['goods_price']-$order['refund_fee']).'元<BR>';
                $this->orderInfo .= '支付类型：'.$order['pay_method'].'<BR>';
                $this->orderInfo .= '单号:'.$order['order_id'].'<BR>';
                $this->orderInfo .= '--------------------------------<BR>';
                $this->orderInfo .= $printer_info['printer_tip'].'<BR>';
                $this->orderInfo .= '联系电话：'.$printer_info['printer_telephone'].'<BR>';
                $this->orderInfo .= '<QR>'.$printer_info['printer_qrcode'].'</QR>';
                if($x < $this->printer_ticketnum){
                    $this->orderInfo .= '<BR>';
                    $this->orderInfo .= '<BR>';
                }           
                $x++;
            }            
        }else{//易联云
                $this->orderInfo = "";                
                //设置打印内容格式
                $this->orderInfo .= '<MN>'.$this->printer_ticketnum.'</MN>';//打印联数
                $this->orderInfo .= '<center><FS2>支付订单</FS2></center>\n';
                $this->orderInfo .= '<FB>******************************</FB>\n';
                $this->orderInfo .= '商户名称：'.$store['business_name'].'\n';
                $this->orderInfo .= '收银账户：'.$store['branch_name'].'\n';
                $this->orderInfo .= '收银员：'.$staff['userName'].'\n';
                $this->orderInfo .= '日期：'.date('Y-m-d H:i:s', $order['paytime']).'\n';
                $this->orderInfo .= '<FB>******************************</FB>\n';
                $this->orderInfo .= '消费金额：'.$order['goods_price'].'元\n';
                $this->orderInfo .= '优惠金额：0.00元'.'\n';
                $this->orderInfo .= '退款金额：'.$order['refund_fee'].'元\n';
                $this->orderInfo .= '实收金额：'.($order['goods_price']-$order['refund_fee']).'元\n';
                $this->orderInfo .= '支付类型：'.$order['pay_method'].'\n';
                $this->orderInfo .= '单号:'.$order['order_id'].'\n';
                $this->orderInfo .= '<FB>******************************</FB>\n';
                $this->orderInfo .= $printer_info['printer_tip'].'\n';
                $this->orderInfo .= '联系电话：'.$printer_info['printer_telephone'].'\n';
                $this->orderInfo .= '<QR>'.$printer_info['printer_qrcode'].'</QR>\n';                          
        }                       
    }
    
    /*
     * 打印结算单
     */
    public function toPrintBalance(){
        $status = queryPrinterStatus($this->printer_sn);
        if(!empty($status) && $status['ret']==0){//在线状态
            //在线
            $res = wp_print($this->printer_sn, $this->balanceInfo, 1, $this->printer_version, $this->printer_key);
            if(TRUE == $res){
                $res = array('status'=>1);
            }  else {
                $res = array('status'=>-1);
            }
        }else{
            $res = array('status'=>-2);
        } 
        return $res;
    }

    /*
     * 打印支付订单
     */
    public function toPrintOrder(){    
        $status = queryPrinterStatus($this->printer_sn);
        if(!empty($status) && $status['ret']==0){//在线状态
            //在线
            $res = wp_print($this->printer_sn, $this->orderInfo, 1, $this->printer_version, $this->printer_key);
            if(TRUE == $res){
                $res = array('status'=>1);
            }  else {
                $res = array('status'=>-1);
            }
        }else{
            $res = array('status'=>-2);
        }               
        return $res;
    }
    
    /*
     * 打印退款订单
     */
    public function toPrintRefund($orderInfo){
            $ustaff = D('Manage/Ustaffs')->where(array('usId'=>$orderInfo['eid']))->find();
            if(!empty($ustaff['printer_sn'])){//是否绑定了打印机
                $printer_info = D('Printer')->where(array('printer_sn'=>$ustaff['printer_sn']))->find();
                $this->setPrinterInfo($printer_info['printer_sn'], $printer_info['printer_key'], $printer_info['printer_version']);
                $this->setRefundInfo($orderInfo, $printer_info['printer_version']);               
                return $this->toprint();
            }
    }
    
    /*
     * 设置退款订单打印数据
     */
    public function setRefundInfo($order, $version = 0){
        $store = D('Stores')->where('storeId='.$order['storeid'])->find();
        $staff = D('Ustaffs')->where('usId='.$order['eid'])->find();
        if(empty($store)){
            $store['business_name'] = '移动支付收银台';
            $store['branch_name'] = '总账户';
        }
        if(empty($staff)){
            $staff['userName'] = '默认收银员';
        }
        if($order['pay_way'] == 'alipay'){
            $order['pay_method'] = '支付宝';
        }elseif ($order['pay_way'] == 'weixin') {
            $order['pay_method'] = '微信支付';
        }
        
        
        $printer_info = D('Printer')->where(array('printer_sn'=>$this->printer_sn))->find();
        if(empty(printer_info)){
            $printer_info['printer_tip'] = '由畅远科技提供技术支持';
            $printer_info['printer_telephone'] = '3312399';
            $printer_info['printer_qrcode'] = 'http://x.eqxiu.com/s/RIdEgYPi';
        }
        //设置订单打印联数,退款订单打印2联
        $this->printer_ticketnum = 2;//$printer_info['printer_ticketnum'];
        if(in_array($version, array(0, 1, 2))){//飞蛾
            $x = 1;
            $this->orderInfo = "";
            while ($x <= $this->printer_ticketnum){
                //设置打印内容格式
                $this->orderInfo .= '------------第'.$x.'联------------<BR><BR>';
                $this->orderInfo .= '<CB>订单退款凭证</CB><BR>';
                $this->orderInfo .= '--------------------------------<BR>';
                $this->orderInfo .= '商户名称：'.$store['business_name'].'<BR>';
                $this->orderInfo .= '收银账户：'.$store['branch_name'].'<BR>';
                $this->orderInfo .= '收银员：'.$staff['userName'].'<BR>';
                $this->orderInfo .= '日期：'.date('Y-m-d H:i:s', time()).'<BR>';
                $this->orderInfo .= '--------------------------------<BR>';
                $this->orderInfo .= '订单金额：'.$order['goods_price'].'元<BR>';
//                $this->orderInfo .= '优惠金额：0.00元'.'<BR>';
                $this->orderInfo .= '退款金额：'.$order['refund_fee'].'元<BR>';
                $this->orderInfo .= '支付类型：'.$order['pay_method'].'<BR>';
                $this->orderInfo .= '单号:'.$order['order_id'].'<BR>';
                $this->orderInfo .= '--------------------------------<BR>';
                $this->orderInfo .= $printer_info['printer_tip'].'<BR>';
                $this->orderInfo .= '联系电话：'.$printer_info['printer_telephone'].'<BR>';
//                $this->orderInfo .= '<QR>'.$printer_info['printer_qrcode'].'</QR>';
                if($x < $this->printer_ticketnum){
                    $this->orderInfo .= '<BR>';
                    $this->orderInfo .= '<BR>';
                }           
                $x++;
            }            
        }else{//易联云
                $this->orderInfo = "";                
                //设置打印内容格式
                $this->orderInfo .= '<MN>'.$this->printer_ticketnum.'</MN>';//打印联数
                $this->orderInfo .= '<center><FS2>订单退款凭证</FS2></center>\n';
                $this->orderInfo .= '<FB>******************************</FB>\n';
                $this->orderInfo .= '商户名称：'.$store['business_name'].'\n';
                $this->orderInfo .= '收银账户：'.$store['branch_name'].'\n';
                $this->orderInfo .= '收银员：'.$staff['userName'].'\n';
                $this->orderInfo .= '日期：'.date('Y-m-d H:i:s', time()).'\n';
                $this->orderInfo .= '<FB>******************************</FB>\n';
                $this->orderInfo .= '订单金额：'.$order['goods_price'].'元\n';
//                $this->orderInfo .= '优惠金额：0.00元'.'\n';
                $this->orderInfo .= '退款金额：'.$order['refund_fee'].'元\n';
                $this->orderInfo .= '支付类型：'.$order['pay_method'].'\n';
                $this->orderInfo .= '单号:'.$order['order_id'].'\n';
                $this->orderInfo .= '<FB>******************************</FB>\n';
                $this->orderInfo .= $printer_info['printer_tip'].'\n';
                $this->orderInfo .= '联系电话：'.$printer_info['printer_telephone'].'\n';
//                $this->orderInfo .= '<QR>'.$printer_info['printer_qrcode'].'</QR>\n';                          
        }                       
    }
    
    /*
     * 打印公共方法
     */
    public function toprint(){
        $status = queryPrinterStatus($this->printer_sn);
        if(!empty($status) && $status['ret']==0){//在线状态
            //在线
            $res = wp_print($this->printer_sn, $this->orderInfo, 1, $this->printer_version, $this->printer_key);
            if(TRUE == $res){
                $res = array('status'=>1);
            }  else {
                $res = array('status'=>-1);
            }
        }else{
            $res = array('status'=>-2);
        }               
        return $res;

    }


    /*
     * 设置打印机信息
     */
    public function setPrinterInfo($printer_sn, $printer_key="", $printer_version = 0){
        $this->printer_sn = $printer_sn;
        $this->printer_key = $printer_key;
        $this->printer_version = $printer_version;
    }
}

