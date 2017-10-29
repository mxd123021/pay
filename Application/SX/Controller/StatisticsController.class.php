<?php
namespace SX\Controller;
class StatisticsController extends BaseController {
    /**
     * 显示订单列表
     */
    public function orderLists(){
        $this->isLogin();
        //$this->checkPrivelege("orderlists");
        $datetype = empty(I('datetype')) ? 'tdy' : I('datetype');
        switch ($datetype) {
            case 'tdy': //今日数据
                $starttime = date('Y-m-d');
                $endtime = date('Y-m-d');
                $stime = strtotime($starttime);
                $etime = strtotime("+1days", strtotime($endtime));
                break;
            case 'ydy': //昨日数据 
                $starttime = date('Y-m-d',strtotime("-1days"));
                $endtime = date('Y-m-d',strtotime("-1days"));
                $stime = strtotime($starttime);
                $etime = strtotime("+1days", strtotime($endtime));
                break;  
            default: //默认最近一周数据或有时间段查询操作
                $stime = I('stime',0);
                $etime = I('etime',0);
                if($stime == 0 && $etime == 0){
                    $datetype = "wk";
                }
                $starttime = $stime != 0 ? $stime : date('Y-m-d',strtotime('-6days'));
                $stime = $stime != 0 ? strtotime($stime) : strtotime(date('Y-m-d',strtotime('-6days')));
                $endtime = $etime != 0 ? $etime : date('Y-m-d');
                $etime = $etime != 0 ? strtotime("+1days", strtotime($etime)) : strtotime(date('Y-m-d',strtotime('+1days')));
                break;
        }
        $sql = "paytime >= ".$stime." and paytime <= ".$etime;

        $m = D('SX/Order');
        $orders = $m->getAll($sql);
        $countIncome = $m->countIncome($sql);
        
        
        //订单统计
        $totalMoney = 0;
        $totalNum = 0;           
        $refund_fee = 0;
        $refund_num = 0;
        foreach ($orders as $value) {            
                if($value['ispay'] == 1){
                    if(0 != $value['refund']){
                        $refund_fee += $value['refund_fee'];
                        $refund_num += 1;
                        $totalMoney += $value['goods_price'];
                        $totalNum += 1;
                    }                    
                }                                      
        }
        $countIncome = $countIncome+$totalMoney-$refund_fee;
     
                
        $this->assign('datetype',$datetype);
        $this->assign('starttime',$starttime);
        $this->assign('endtime',$endtime);
        $this->assign('countIncome',$countIncome);
        $this->assign('orders',$orders);
        $this->display("orderLists");
    }

    /**
     * 显示订单详情
     */
    public function odetail(){
        $this->isLogin();
        $id = I('id');

        $m = D('Manage/Order');
        $order = $m->odetail($id);

        $s = D('Manage/Stores');
        $stores = $s->get($order['uid']);
        $u = D('Manage/Ustaffs');
        $ustaffs = $u->get($order['uid']);

        foreach ($stores as $key => $value) {
            $store[$value['storeId']] = $value['business_name'].$value['branch_name'];
        }

        foreach ($ustaffs as $key => $value) {
            $ustaff[$value['usId']] = $value['userName'];
        }

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
        //$this->checkPrivelege("orderlists");
        $stime = I('stime',0);
        $etime = I('etime',0);

        if($stime == 0 || $etime == 0){
            die('参数错误');
        }

        $stime = $stime != 0 ? strtotime($stime) : strtotime(date('Y-m-d',strtotime('-6days')));
        $etime = $etime != 0 ? strtotime("+1days", strtotime($etime)) : strtotime(date('Y-m-d',strtotime('+1days')));

        $sql = "paytime >= ".$stime." and paytime <= ".$etime;

        $m = D('SX/Order');
        $orders = $m->getAll($sql);

        if(empty($orders)){
            die('订单数据为空');
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
                    ->setCellValue('E1', '门店ID')
                    ->setCellValue('F1', '收银员ID')
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
            $md = $ovv['storeid'];
            $syy = $ovv['eid'];
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
}