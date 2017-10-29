<?php
namespace Manage\Controller;
class StatisticsController extends BaseController {
    /**
     * 显示订单列表
     */
    public function orderLists(){
        $this->isLogin();
        $this->checkPrivelege("orderlists");
        $usId = session('SX_USERS.usId');
        $datetype = I('datetype');
        switch ($datetype) {
            case 'wk':
                $starttime = date('Y-m-d',strtotime('-6days'));
                $endtime = date('Y-m-d');
                $stime = strtotime($starttime);
                $etime = strtotime(date('Y-m-d',strtotime('+1days')));
                break;
//            case 'tdy': //今日数据
//                $starttime = date('Y-m-d');
//                $endtime = date('Y-m-d');
//                $stime = strtotime($starttime);
//                $etime = strtotime("+1days", strtotime($endtime));
//                break;
            case 'ydy': //昨日数据 
                $starttime = date('Y-m-d',strtotime("-1days"));
                $endtime = date('Y-m-d',strtotime("-1days"));
                $stime = strtotime($starttime);
                $etime = strtotime("+1days", strtotime($endtime));
                break;  
            default: //默认最近一周数据或有时间段查询操作
                $stime = I('stime',0);
                $etime = I('etime',0);
                $shour = I('shour', 0);
                $ehour = I('ehour', 0);
                if($stime == 0 && $etime == 0){
                    $datetype = "tdy";
                }
                $starttime = $stime != 0 ? $stime : date('Y-m-d');
                $endtime = $etime != 0 ? $etime : date('Y-m-d');
                if($stime != 0){
//                    if($s)
                    $stime = strtotime($stime.' '.$shour);
                    $etime = strtotime($etime.' '.$ehour);
                }else{
                    $stime = strtotime(date('Y-m-d'));
                    $etime = strtotime(date('Y-m-d',strtotime('+1days')));
                }
//                $stime = $stime != 0 ? strtotime($stime) : strtotime(date('Y-m-d'));
//                $endtime = $etime != 0 ? $etime : date('Y-m-d');
//                $etime = $etime != 0 ? strtotime("+1days", strtotime($etime)) : strtotime(date('Y-m-d',strtotime('+1days')));
                
                
//                $stime += strtotime($shour);
//                $etime += strtotime($ehour);
//                $starttime = $stime != 0 ? $stime : date('Y-m-d',strtotime('-6days'));
//                $stime = $stime != 0 ? strtotime($stime) : strtotime(date('Y-m-d',strtotime('-6days')));
//                $endtime = $etime != 0 ? $etime : date('Y-m-d');
//                $etime = $etime != 0 ? strtotime("+1days", strtotime($etime)) : strtotime(date('Y-m-d',strtotime('+1days')));
                break;
        }
        $sql = " and paytime >= ".$stime." and paytime <= ".$etime;
        
        //增加筛选条件，门店\支付类型
        $paytype = I('paytype');
        $mendian = I('mendian');
        if($paytype == 1){
            $sql .= " and pay_way='weixin'";
        }elseif ($paytype == 2) {
            $sql .= " and pay_way='alipay'";
        }
        if($mendian != 0){
            $sql .= " and storeid=".$mendian;
        }

        $m = D('Manage/Order');
        //判断是商户还是员工登陆
        if(empty($usId)){//商户
            $orders = $m->getAll(session('SX_USERS.userId'),$limit=0,$model=1,$sql);//根据用户ID
            $countIncome = $m->countIncome(session('SX_USERS.userId'),$sql,$model=1);
        }else{
            $orders = $m->getAll(session('SX_USERS.storeId'),$limit=0,$model=3,$sql);//根据店铺ID
            $countIncome = $m->countIncome(session('SX_USERS.storeId'),$sql,$model=3);
        }
        $s = D('Manage/Stores');
        $stores = $s->get(session('SX_USERS.userId'));
        $u = D('Manage/Ustaffs');
        $ustaffs = $u->get(session('SX_USERS.userId'));

        foreach ($stores as $key => $value) {
            $store[$value['storeId']] = $value['business_name'].$value['branch_name'];
        }

        foreach ($ustaffs as $key => $value) {
            $ustaff[$value['usId']] = $value['userName'];
        }
        
        //订单统计
        $totalMoney = 0;
        $totalNum = 0;
        $weixinMoney = 0;
        $weixinNum = 0;
        $alipayMoney = 0;
        $alipayNum = 0;        
        $refund_fee = 0;
        $refund_num = 0;
        foreach ($orders as $value) {
            if($value['pay_way'] == 'weixin' and $value['ispay'] == 1 and $value['refund'] == 0){
                $weixinMoney += $value['goods_price'];
                $weixinNum += 1;
            }elseif($value['pay_way'] == 'alipay' and $value['ispay'] == 1 and $value['refund'] == 0){
                $alipayMoney += $value['goods_price'];
                $alipayNum += 1;
            }else{
                if($value['ispay'] == 1){
                    if(0 != $value['refund']){
                        $refund_fee += $value['refund_fee'];
                        $refund_num += 1;
                    }
                    $totalMoney += $value['goods_price'];
                    $totalNum += 1;
                }                
            }            
        }
        $countIncome = $countIncome+$totalMoney-$refund_fee;
        $totalMoney += $weixinMoney + $alipayMoney;
        $totalNum += $weixinNum + $alipayNum;
        
        
        $this->assign('mendians', $stores);
        $this->assign('mendian', $mendian);
        $this->assign('paytype', $paytype);
        $this->assign('datetype',$datetype);
        $this->assign('starttime',$starttime);
        $this->assign('endtime',$endtime);
        $this->assign('shour', $shour);
        $this->assign('ehour', $ehour);
        $this->assign('countIncome',$countIncome);
        $this->assign('orders',$orders);
        $this->assign('store',$store);
        $this->assign('ustaff',$ustaff);        
        $this->assign('totalMoney', $totalMoney);
        $this->assign('totalNum', $totalNum);
        $this->assign('weixinMoney', $weixinMoney);
        $this->assign('weixinNum', $weixinNum);
        $this->assign('alipayMoney', $alipayMoney);
        $this->assign('alipayNum', $alipayNum);
        $this->display("orderLists");
    }

    /**
     * 显示红包领取列表
     */
    public function rporderLists(){
        $this->isLogin();
        $this->checkPrivelege("orderlist");
        $m = D('Manage/Rporder');
        $rporder = $m->getAll(session('SX_USERS.userId'));
        $s = D('Manage/Stores');
        $stores = $s->get(session('SX_USERS.userId'));
        $u = D('Manage/Ustaffs');
        $ustaffs = $u->get(session('SX_USERS.userId'));

        foreach ($stores as $key => $value) {
            $store[$value['storeId']] = $value['business_name'].$value['branch_name'];
        }

        foreach ($ustaffs as $key => $value) {
            $ustaff[$value['usId']] = $value['userName'];
        }

        $this->assign('store',$store);
        $this->assign('ustaff',$ustaff);
        $this->assign('rporder',$rporder);
        $this->display("rporderLists");
    }

    /**
     * 显示订单详情
     */
    public function odetail(){
        $this->isLogin();
        $id = I('id');

        $s = D('Manage/Stores');
        $stores = $s->get(session('SX_USERS.userId'));
        $u = D('Manage/Ustaffs');
        $ustaffs = $u->get(session('SX_USERS.userId'));

        foreach ($stores as $key => $value) {
            $store[$value['storeId']] = $value['business_name'].$value['branch_name'];
        }

        foreach ($ustaffs as $key => $value) {
            $ustaff[$value['usId']] = $value['userName'];
        }

        $m = D('Manage/Order');
        $order = $m->odetail($id);
        $this->assign('store',$store);
        $this->assign('ustaff',$ustaff);
        $this->assign('order',$order);
        $this->display("odetail");
    }

    public function downDetail(){
        header("Content-Type: text/html; charset=UTF-8");
        if (PHP_SAPI == 'cli')
            die('只能从Web浏览器打开');
        $this->isLogin();
        $this->checkPrivelege("orderlists");
        $usId = session('SX_USERS.usId');
        $stime = I('stime',0);
        $etime = I('etime',0);

        if($stime == 0 || $etime == 0){
            die('参数错误');
        }

        $stime = $stime != 0 ? strtotime($stime) : strtotime(date('Y-m-d',strtotime('-6days')));
        $etime = $etime != 0 ? strtotime("+1days", strtotime($etime)) : strtotime(date('Y-m-d',strtotime('+1days')));

        $sql = " and paytime >= ".$stime." and paytime <= ".$etime;

        $m = D('Manage/Order');
        //判断是商户还是员工登陆
        if(empty($usId)){//商户
            $orders = $m->getAll(session('SX_USERS.userId'),$limit=0,$model=1,$sql);//根据用户ID
        }else{
            $orders = $m->getAll(session('SX_USERS.storeId'),$limit=0,$model=3,$sql);//根据店铺ID
        }

        if(empty($orders)){
            die('订单数据为空');
        }

        $s = D('Manage/Stores');
        $stores = $s->get(session('SX_USERS.userId'));
        $u = D('Manage/Ustaffs');
        $ustaffs = $u->get(session('SX_USERS.userId'));

        foreach ($stores as $key => $value) {
            $store[$value['storeId']] = $value['business_name'].$value['branch_name'];
        }

        foreach ($ustaffs as $key => $value) {
            $ustaff[$value['usId']] = $value['userName'];
        }

        Vendor('PHPExcel.PHPExcel');
        $objPHPExcel = new \PHPExcel();

        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
                                     ->setLastModifiedBy("Maarten Balliauw")
                                     ->setTitle("Office 2007 XLSX Test Document")
                                     ->setSubject("Office 2007 XLSX Test Document")
                                     ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                                     ->setKeywords("office 2007 openxml php")
                                     ->setCategory("Test result file");

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', '订单ID')
                    ->setCellValue('B1', '支付方式')
                    ->setCellValue('C1', '付款方式')
                    ->setCellValue('D1', '商品名称')
                    ->setCellValue('E1', '门店')
                    ->setCellValue('F1', '收银员')
                    ->setCellValue('G1', '平台支付订单ID')
                    ->setCellValue('H1', '支付者')
                    ->setCellValue('I1', '类型')
                    ->setCellValue('J1', '支付金额(元)')
                    ->setCellValue('K1', '支付时间')
                    ->setCellValue('L1', '订单状态');

        $k = 2;
        foreach($orders as $ovv){
            if($ovv['pay_way']=='weixin'){
                $pay_way = "微信";
            }elseif($ovv['pay_way']=='alipay'){
                $pay_way = "支付宝";
            }
            if($ovv['pay_type']=='NATIVE'){
                   $pay_type = "扫码支付";
             }elseif($ovv['pay_type']=='MICROPAY'){
                   $pay_type = "刷卡支付";
             }elseif($ovv['pay_type']=='JSAPI'){
                   $pay_type = "自助支付";
             }
            if($store[$ovv['storeid']]==""){$md = "无";}else{$md = $store[$ovv['storeid']];}
            if($ustaff[$ovv['eid']]==""){$syy = "无";}else{$syy = $ustaff[$ovv['eid']];}
            if(!empty($ovv['truename'])){
                $name = htmlspecialchars_decode($ovv['truename'],ENT_QUOTES);
            }elseif(!empty($ovv['openid'])){
                  $name = $ovv['openid'];
            }else{
                  $name = '未知';
            }
            if($ovv['mchtype']==1){
                $mchtype = "特约模式";
            }elseif($ovv['mchtype']==2){
                $mchtype = "平台代收";
            }elseif($ovv['mchtype']==3){
                $mchtype = "受理模式";
            }else{
                $mchtype = "普通模式";
            }
            if($order['refund']==1){
                $refund = "退款中";
            }elseif($order['refund']==2){
                $refund = "已退款";
            }elseif($order['refund']==3){
                $refund = "退款失败";
            }else{
                $refund = "已支付";
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$k, $ovv['order_id'].' ')
                    ->setCellValue('B'.$k, $pay_way)
                    ->setCellValue('C'.$k, $pay_type)
                    ->setCellValue('D'.$k, htmlspecialchars_decode($ovv['goods_name'],ENT_QUOTES))
                    ->setCellValue('E'.$k, $md)
                    ->setCellValue('F'.$k, $syy)
                    ->setCellValue('G'.$k, $ovv['transaction_id'].' ')
                    ->setCellValue('H'.$k, $name)
                    ->setCellValue('I'.$k, $mchtype)
                    ->setCellValue('J'.$k, $ovv['goods_price'])
                    ->setCellValue('K'.$k, date('Y-m-d H:i:s',$ovv['paytime']))
                    ->setCellValue('L'.$k, $refund);
            $k++;
        }

        

        $objPHPExcel->getActiveSheet()->setTitle('订单数据');
        $objPHPExcel->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="OrderDetail'.date("YmdHis").'.xls"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');

        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
        header ('Cache-Control: cache, must-revalidate');
        header ('Pragma: public');

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }
    
    /*
     * 结算管理
     */
    public function Balance(){
        $this->isLogin();
//        $this->checkPrivelege("orderlists");
        $usId = session('SX_USERS.usId');
        
        $stime = I('stime',0);
        $etime = I('etime',0);
        
        
        $starttime = $stime != 0 ? $stime : date('Y-m-d');
        $stime = $stime != 0 ? strtotime($stime) : strtotime(date('Y-m-d'));
        $endtime = $etime != 0 ? $etime : date('Y-m-d');
        $etime = $etime != 0 ? strtotime($etime) : strtotime(date('Y-m-d'));        
        //结算只能结算到今日的
        if($etime > strtotime(date('Y-m-d'))){
            $etime = strtotime(date('Y-m-d'));
        }
        
        //最多只能查询1个月内的,安全策略
        if($etime - $stime > 31*24*60*60){
            $etime = $stime + 31*24*60*60;
        }
        
        //增加筛选条件，门店\支付类型
        $paytype = I('paytype');
        $mendian = I('mendian');
        $sql = '';
        if($paytype == 1){
            $sql .= " and pay_way='weixin'";
        }elseif ($paytype == 2) {
            $sql .= " and pay_way='alipay'";
        }
        if($mendian != 0){
            $sql .= " and storeid=".$mendian;
        }
            
        $m = D('Manage/Order');
        //判断是商户还是员工登陆
        if(empty($usId)){//商户
            $balanceList = $m->toBalance(session('SX_USERS.userId'),$stime, $etime, 1, $sql);
        }else{
            $balanceList = $m->toBalance(session('SX_USERS.storeId'),$stime, $etime, 0, $sql);
        }
        
        $s = D('Manage/Stores');
        $stores = $s->get(session('SX_USERS.userId'));
        
                
        $this->assign('balanceList', $balanceList);
        $this->assign('starttime',$starttime);
        $this->assign('endtime',$endtime);
        $this->assign('paytype', $paytype);
        $this->assign('mendian', $mendian);
        $this->assign('mendians', $stores);
        $this->display();
    }
    
    /*
     * 下载结算详情，导出Excel表格
     */
    public function Downbalancedetail(){
        header("Content-Type: text/html; charset=UTF-8");
        if (PHP_SAPI == 'cli')
            die('只能从Web浏览器打开');
        $this->isLogin();
//        $this->checkPrivelege("orderlists");
        $usId = session('SX_USERS.usId');
        $stime = I('stime',0);
        $etime = I('etime',0);

        if($stime == 0 || $etime == 0){
            die('参数错误');
        }                

//        $stime = I('stime',0);
//        $etime = I('etime',0);
        
        $starttime = $stime != 0 ? $stime : date('Y-m-d');
        $stime = $stime != 0 ? strtotime($stime) : strtotime(date('Y-m-d'));
        $endtime = $etime != 0 ? $etime : date('Y-m-d');
        $etime = $etime != 0 ? strtotime($etime) : strtotime(date('Y-m-d'));        
        //结算只能结算到今日的
        if($etime > strtotime(date('Y-m-d'))){
            $etime = strtotime(date('Y-m-d'));
        }
        if($etime - $stime > 31*24*60*60){
            die('只能导出一个月内的结算数据！');
        }
        
        //增加筛选条件，门店\支付类型
        $paytype = I('paytype');
        $mendian = I('mendian');
        $sql = '';
        if($paytype == 1){
            $sql .= " and pay_way='weixin'";
        }elseif ($paytype == 2) {
            $sql .= " and pay_way='alipay'";
        }
        if($mendian != 0){
            $sql .= " and storeid=".$mendian;
        }
            
        $m = D('Manage/Order');
        //判断是商户还是员工登陆
        if(empty($usId)){//商户
            $balanceList = $m->toBalance(session('SX_USERS.userId'),$stime, $etime, 1, $sql);
        }else{
            $balanceList = $m->toBalance(session('SX_USERS.storeId'),$stime, $etime, 0, $sql);
        }

        if(empty($balanceList)){
            die('结算数据为空');
        }

        $s = D('Manage/Stores');
        $stores = $s->get(session('SX_USERS.userId'));
        

        Vendor('PHPExcel.PHPExcel');
        $objPHPExcel = new \PHPExcel();

        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
                                     ->setLastModifiedBy("Maarten Balliauw")
                                     ->setTitle("Office 2007 XLSX Test Document")
                                     ->setSubject("Office 2007 XLSX Test Document")
                                     ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                                     ->setKeywords("office 2007 openxml php")
                                     ->setCategory("Test result file");

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A7', '划账日期')
                    ->setCellValue('B7', '交易时间')
                    ->setCellValue('C7', '交易金额')
                    ->setCellValue('D7', '交易笔数')
                    ->setCellValue('E7', '退款金额')
                    ->setCellValue('F7', '退款笔数')
                    ->setCellValue('G7', '支付净额')
                    ->setCellValue('H7', '手续费金额')
                    ->setCellValue('I7', '划账金额');
//                    ->setCellValue('J1', '支付金额(元)')
//                    ->setCellValue('K1', '支付时间')
//                    ->setCellValue('L1', '订单状态');

        $k = 8;
        $totalMoney = 0;
        $totalNum = 0;
        $totalFee = 0;
        foreach($balanceList as $ovv){
//            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$k, date('Y-m-d', $ovv['balanceTime']).' ')
                    ->setCellValue('B'.$k, date('Y-m-d', $ovv['startTime']))
                    ->setCellValue('C'.$k, $ovv['totalMoney'])
                    ->setCellValue('D'.$k, $ovv['count'])
                    ->setCellValue('E'.$k, $ovv['refundMoney'])
                    ->setCellValue('F'.$k, $ovv['refundCount'])
                    ->setCellValue('G'.$k, $ovv['haspayMoney'].' ')
                    ->setCellValue('H'.$k, $ovv['fee'])
                    ->setCellValue('I'.$k, $ovv['incomeMoney']);
//                    ->setCellValue('J'.$k, $ovv['goods_price'])
//                    ->setCellValue('K'.$k, date('Y-m-d H:i:s',$ovv['paytime']))
//                    ->setCellValue('L'.$k, $refund);
            $totalMoney += $ovv['haspayMoney'];
            $totalNum += $ovv['count']-$ovv['refundCount'];
            $totalFee += $ovv['fee'];
            $k++;
        }
        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', '注意：此表中的数据是以本商户后台的数据统计生成，费率如无特别注明，均是以0.6%的费率计算。如商户使用了银行系统进行过退款等操作，会存在数据差异，存在数据差异时，均以银行系统为准。');
        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A2', '起始时间：'.date('Y-m-d H:i:s', $stime).'  终止时间'.date('Y-m-d H:i:s', $etime));
        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A3', '交易总金额：'.$totalMoney.'  交易笔数'.$totalNum);
                
        

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A4', '交易手续费：'.$totalFee);
        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A5', '下载时间：'.date('Y-m-d H:i:s'));
        
        if(1 == $paytype){
             $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A6', '支付类型：微信支付');
        }elseif(2 == $paytype){
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A6', '支付类型：支付宝支付');
        }
        

        $objPHPExcel->getActiveSheet()->setTitle('结算数据');
        $objPHPExcel->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="结算管理'.date("YmdHis").'.xls"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');

        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
        header ('Cache-Control: cache, must-revalidate');
        header ('Pragma: public');

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }
}