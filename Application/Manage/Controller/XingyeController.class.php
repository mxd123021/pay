<?php

namespace Manage\Controller;

use Endroid\QrCode\QrCode;
use Think\Log;

class XingyeController extends BaseController
{
    use \ShanghaiBankPayHelper,\ZhuoGePayHelper;
    /**
     * 上传图片文件接口(兴业银行)
     */
    public function img_upload()
    {
//        $this->isLogin();
        
        $data = array(
            'serviceName' => 'pic_upload',
            'picUpload' => array('picType' => 1),
        );
        $obj = D('Xingye');
        $rs = $obj->post_data($data);
        $rs = xmlToArray($rs);
        if(isset($rs['isSuccess']))
        {
            if($rs['isSuccess'] == 'T' && $rs['pic'])
            {
                //保存在本地
                $userId = session('SX_USERS.userId');
                $saveName = explode('.', $rs['pic']);
                $res = $this->uploadPic($saveName[0], 1048576, 'Users/'.$userId);
                if(1 == $res['status']){
                    $this->ajaxReturn(array('status' => 1, 'savepath' => $rs['pic']));
                }else{
                    $this->ajaxReturn(array('status' => -1, 'error' => '本地保存图片失败！'.$res['error']));
                }                
            }
            else
            {
                $this->ajaxReturn(array('status' => -1, 'error' => '图片不存在'));
            }
        }
        else
        {
            $this->ajaxReturn(array('status' => -1, 'error' => '系统错误'));
        }
    }
    
    /**
     * 上传图片
     */
    public function uploadPic($saveName, $maxSize=1048576,$savePath="uploads"){
       $config = array(
                'maxSize'       =>  $maxSize, //上传的文件大小限制 (0-不做限制)
                'exts'          =>  array('jpg','png','gif','jpeg'), //允许上传的文件后缀
                'rootPath'      =>  './Upload/', //保存根路径
                'driver'        =>  'LOCAL', // 文件上传驱动
                'subName'       =>  '',
                'savePath'      =>  I('dir',$savePath)."/", //文件上传（子）目录
                'saveName'      => $saveName,
        );
        $upload = new \Think\Upload($config);
        $rs = $upload->upload($_FILES);
        if(!$rs){
            return array('status'=>-1,'error'=>$upload->getError());
        }else{
            //生成缩略图
            /*$images = new \Think\Image();
            //foreach ($rs['Filedata'] as $key =>$v){
                 $images->open($config['rootPath'].$rs['file']['savepath'].$rs['file']['savename']);
                 $newsavename = str_replace('.','_thumb.',$rs['file']['savename']);
                 $vv = $images->thumb(I('width',100), I('height',100),I('thumb_type',1))->save($config['rootPath'].$rs['file']['savepath'].$newsavename);
            //}*/
            return array('status'=>1,'savepath'=>$config['rootPath'].$rs['file']['savepath'].$rs['file']['savename']);
        }

    }

    /**
     * 兴业实名认证
     */
    public function realname()
    {
        $this->isLogin();

        if(IS_POST)
        {
            $m = D('Manage/Users');

            $userId = 0;
            $type = I('type');
            if($type == "agent"){
                $userId = I('usId');
                $tip['url'] = U("Manage/Agent/merchants");
            }else{
                $userId = session('SX_USERS.userId');
                $tip['url'] = U("Xingye/realname");
            }

            $step = I('step');
            $step = ($step > 2) ? 1 : (int)$step + 1;

            $rs = $m->saverealInfo($userId);
            if($rs['status']==1){
                if(2 == $step){
                    $tip['info'] = "提交商户信息成功，请补充账户结算信息";
                    $m->setUseraudit($userId, 0, "", $step);  
                }elseif(3 == $step){
                    $tip['info'] = "提交成功，等待审核";
                    $m->setUseraudit($userId, 2, "", $step); 
                }else{
                    $tip['info'] = "请正确填写商户资料";
                    $m->setUseraudit($userId, 0, "", $step); 
                }
                              
            }else{
                $tip['info'] = "提交失败";
            }   
            $this->assign('tip',$tip);
            $this->display("Public/tip");
        }
        else
        {
            $c = D('SX/Configs');
            $configs = $c->loadConfigs();
            $m = D('Manage/Users');
            $userinfo = $m->get(session('SX_USERS.userId'));
            $d = D('Manage/GdDistrict');
            $district = $d->getProvince();
            $k = D('Manage/GdBank');
            $bank = $k->get();            
            $savePath = 'Upload/Users/'.session('SX_USERS.userId');
            
            $this->assign('savePath', $savePath);
            $this->assign('configs',$configs);
            $this->assign('userinfo',$userinfo);
            $this->assign('district',$district);
            $this->assign('bank',$bank);
            if(!empty($userinfo['fid'])){ //获取二级地区信息
                $district2 = $d->getCity($userinfo['fid']);
                $this->assign('district2',$district2);
            }
            if(!empty($userinfo['incode'])){ //获取行业类别
                $i = D('Manage/GdIncategory');
                $incategory = $i->getId($userinfo['incode']);
                $incategory['namearr'] = explode("-",$incategory['name']);
                $incategory['level2'] = $i->get($incategory['namearr'][0]);
                $incategory['level3'] = $i->get($incategory['namearr'][0]."-".$incategory['namearr'][1]);
                $this->assign('incategory',$incategory);
            }
            $this->display();
        }
    }

    /**
     * 二维码支付
     */
    public function ewmpay()
    {
        if(IS_POST)
        {
            $data = array();
            $data['trade_type'] = I('post.paytype', 0);
            $data['tname'] = $goods_name = I('post.tname', '');
            $data['total_fee'] = I('post.tprice', 0);

            $method = isset($_REQUEST['method'])?$_REQUEST['method']:'submitOrderInfo';
            switch($method){
                case 'submitOrderInfo'://提交订单

                    $users = D('Manage/Users')->get(session('SX_USERS.userId'));

                    $isAliPay = $data['trade_type'] == 'alipay';
                    $orderNumber = $this->getOrderNumber();
                    $post_data = $this->gettypedata($users);

                    /**
                    "扫码支付"
                     */
                    $data['out_trade_no'] = $orderNumber;
                    $data['uid'] = session('SX_USERS.userId');
                    $data['eid'] = session('SX_USERS.usId');
                    $data['storeid'] = session('SX_USERS.storeId');
                    $data['goods_name'] = $data['tname'];
                    //unset($data['tname']);
                    $data['goods_describe'] = '扫码支付';
                    $data['mchtype'] = $post_data['mchtype'];
                    $data['pmid'] = session('SX_USERS.parentId');
                    $data['pay_type'] = 'NATIVE';
                    //上海银行
                    if($users['api_type'] == 0){
                        M()->startTrans();
                        $info = $this->createQrCodeOrderByUserId($isAliPay,$orderNumber,$data['total_fee'],session('SX_USERS.userId'),$data);
                        if(!$info){
                            $return = [
                                'status'=>5010,
                                'msg'=>'生成支付二维码失败'
                            ];
                            M()->rollback();
                            $this->ajaxReturn($return);
                        }
                        Log::write('info==='.json_encode($info));
                        if($info['retCode'] != "0000" && $info['retMsg'] != 'SUCCESS'){
                            $return = [
                                'status'=>5010,
                                'msg'=>'生成支付二维码失败'
                            ];
                            M()->rollback();
                            $this->ajaxReturn($return);
                        }
                        $payInfo = json_decode($info['r9_payinfo'],true);
                        $qrCode = new QrCode($payInfo['qrCode']);
//                    $qrCode->writeFile(sprintf('%s/%s.png',DATA_PATH,$orderNumber));
                        $imageUrl = $qrCode->writeDataUri();
                        $return = [
                            'status'=>1,
                            'code_url'=> $imageUrl,//二维码
                            'qrcode'=>$payInfo['qrCode'],
                            'code_status'=>""
                        ];
                        M()->commit();
                        $this->ajaxReturn($return);
                    }else{//拙歌接口
                        M()->startTrans();
                        $isWeChat = $data['trade_type'] == 'weixin';
                        $info = $this->zhuoGeCreateQrCodeOrderByUserId($isWeChat,$orderNumber,$data['total_fee'],session('SX_USERS.userId'),$data);
                        if(is_string($info)){

                            $qrCode = new QrCode($info);
                            $imageUrl = $qrCode->writeDataUri();
                            $return = [
                                'status'=>1,
                                'code_url'=> $imageUrl,//二维码
                                'qrcode'=>$info,
                                'code_status'=>""
                            ];
                            M()->commit();
                            $this->ajaxReturn($return);
                        }else{
                            $return = [
                                'status'=>50010,
                                'msg'=>'生成支付二维码失败'
                            ];
                            M()->rollback();
                            $this->ajaxReturn($return);

                        }
                    }

                    exit;
                break;
                case 'queryOrder'://查询订单
                    $this->queryOrder();
                break;
                case 'submitRefund'://提交退款
                    $this->submitRefund();
                break;
                case 'queryRefund'://查询退款
                    $this->queryRefund();
                break;
                case 'callback':
                    $this->callback();
                break;
            }
        }
        else
        {
            $this->isLogin();
            $usId = session('SX_USERS.usId');
            $m = D('Manage/Order');
            if(empty($usId)){
                $order = $m->getAll(session('SX_USERS.userId'),20);
            }else{
                $order = $m->getAll(session('SX_USERS.storeId'),20,3); //1商户 2员工 3门店
            }
            // $autopayewm = $this->getshorturl(session('SX_USERS.userId'),U("Manage/Xingye/autopay@".C('SITE_URL'),array('userId'=>session('SX_USERS.userId'),'usId'=>session('SX_USERS.usId'),'storeId'=>session('SX_USERS.storeId'),'parentId'=>session('SX_USERS.parentId'))));
            // if($autopayewm == -1){
            //     $tip['info'] = "请完善支付配置信息";
            //     $tip['url'] = U("Manage/Users/payConfig",$data);
            //     $this->assign("tip",$tip);
            //     $this->display("Public/tip");
            // }
            $item = D('Manage/Users')->get(session('SX_USERS.userId'));
            $param = array(
                'userId'    => session('SX_USERS.userId'),
                'usId'      => session('SX_USERS.usId'),
                'storeId'   => session('SX_USERS.storeId'),
                'parentId'  => session('SX_USERS.parentId'),
            );
            
            $autopayewm['short_url'] = U("Manage/Xingye/self_pay@".C('SITE_URL'), $param);
            $this->assign('api_type',$item['api_type']);
            $this->assign('order',$order);
            $this->assign('autopayewm',sprintf('http://%s/%s',$_SERVER['SERVER_NAME'],'PayView/Index/Index?id='.$item['unique_id']));
            $this->assign('wftQrCode',sprintf('http://%s/%s',$_SERVER['SERVER_NAME'],'PayView/Index/wftIndex?id='.$item['unique_id']));
            $this->display();
        }
    }

    /**
     * 刷卡 扫码
     */
    public function payment()
    {
        if(IS_POST)
        {
            $this->isAjaxLogin();

            $rd = array('status' => -1);
            $users = D('Manage/Users')->get(session('SX_USERS.userId'));
            $post_data = array();
            $post_data = $this->gettypedata($users);
            if($post_data == -1)
            {
                $rd['msg'] = "请检查支付配置是否正确";
                $this->ajaxReturn($rd);
            }

            if($post_data['mchtype'] == 3)
            {
                //受理模式
                $goods_price = I('goods_price');
                $total_fee = $goods_price * 100;
                if(!$total_fee || $total_fee <= 0)
                {
                    $rd['msg'] = "价格错误";
                    $this->ajaxReturn($rd);
                }

                $m = D('Manage/Users');
                $users = $m->get(session('SX_USERS.userId'));
                $post_data = $this->gettypedata($users);

                $auth_code = I('auth_code');
                $goods_name = I('goods_name');

                $out_trade_no = $post_data['mch_id'] . date("YmdHis");//订单号
                $data = array(
                    'auth_code' => $auth_code,
                    'out_trade_no' => $out_trade_no,
                    'body' => $goods_name,
                    'attach' => '',
                    'total_fee' => $total_fee,
                    'mch_create_ip' => $_SERVER['REMOTE_ADDR'],
                    'mch_id' => $post_data['mch_id'],
                );
                
                //添加订单
                $orderInfo = array(
                    'out_trade_no'      => $this->getOrderNumber(),
                    'uid'               => session('SX_USERS.userId'),
                    'eid'               => session('SX_USERS.usId'),
                    'storeid'           => session('SX_USERS.storeId'),
                    'trade_type'        => 'unified.trade.micropay',
                    'buyer_logon_id'    => '',
                    'mch_id'            => $post_data['mch_id'],
                    'sub_mch_id'        => '',
                    'goods_name'        => $goods_name,
                    'goods_describe'    => '扫码支付',
                    'total_fee'         => $goods_price,
                    'time_end'          => time(),
                    'openid'            => '',
                    'transaction_id'    => '',
                    'mchtype'           => $post_data['mchtype'],
                    'pmid'              => session('SX_USERS.parentId'),
                    'ispay'            => 0,
                    'paytime'           => time(),
                );
                $res = $this->createAuthCodeOrderByUid(1,$orderInfo['out_trade_no'],$goods_price,$orderInfo['uid'],$orderInfo,$auth_code);
                if($res === true){
                    $this->ajaxReturn([
                        'status'=>1,
                        'msg'=>'付款成功',
                        'price'=>$goods_price
                    ]);
                }
                if($res instanceof \Exception){
                    $this->ajaxReturn([
                        'status'=>-1,
                        'msg'=>$res->getMessage(),
                        'price'=>$goods_price
                    ]);
                }
                D('Manage/XyOrder')->addOrder($orderInfo); 

                $obj = D('Manage/XyMicropay');
                $obj->setKey($post_data['xy_key']);
                $rs = $obj->submitOrderInfo($data);

                dataRecodes('小额支付结果2', $rs);

                if(isset($rs['status']) && $rs['status'] == 0)
                {
                    //pay_result

                    /**
                    0   session('SX_USERS.userId').",".
                    1   session('SX_USERS.usId').",".
                    2   session('SX_USERS.storeId').",".
                    3   session('SX_USERS.parentId').",".
                    4   $post_data['mchtype'].",".
                    5   $post_data['body'].",".
                    6   "扫码支付"
                    */
                    //out_trade_no uid eid storeid trade_type buyer_logon_id mch_id sub_mch_id 
                    //goods_name goods_describe total_fee time_end openid transaction_id mchtype pmid
                    if($rs['result_code'] == 0 && $rs['pay_result'] == 0){
                        $ispay = 1;//支付成功                                                                        
                    }else{
                        $ispay = 0;//支付失败
                    }

                    dataRecodes('in micropay res = ', $rs);
                    
                    $updateData = array(
                         'truename'    => $rs['ret']['buyer_logon_id'],
                        'paytime'          => $rs['ret']['time_end'],
                        'openid'            => $rs['ret']['openid'],
                        'transaction_id'    => $rs['ret']['transaction_id'],
                        'ispay'            => $ispay,
                        'paytime'   => strtotime($rs['ret']['time_end'])
                    );
                     if(strstr($rs['ret']['trade_type'], "weixin"))
                    {
                        $updateData['pay_way'] = "weixin"; //支付平台
                        $updateData['truename'] = "";
                    }
                    else if(strstr($rs['ret']['trade_type'], "alipay"))
                    {
                        $updateData['pay_way'] = "alipay";
                        $updateData['truename'] = isset($rs['ret']['buyer_logon_id']) ? $rs['ret']['buyer_logon_id'] : '';
                    }
                    else
                    {
                        $updateData['pay_way'] = "other";
                    }
                    D('Manage/XyOrder')->editOrderStatus($out_trade_no, $updateData);
                    
//                    $orderInfo = array(
//                        'out_trade_no'      => $out_trade_no,
//                        'uid'               => session('SX_USERS.userId'),
//                        'eid'               => session('SX_USERS.usId'),
//                        'storeid'           => session('SX_USERS.storeId'),
//                        'trade_type'        => $rs['ret']['trade_type'],
//                        'buyer_logon_id'    => $rs['ret']['buyer_logon_id'],
//                        'mch_id'            => $post_data['mch_id'],
//                        'sub_mch_id'        => '',
//                        'goods_name'        => $goods_name,
//                        'goods_describe'    => '扫码支付',
//                        'total_fee'         => $goods_price,
//                        'time_end'          => $rs['ret']['time_end'],
//                        'openid'            => $rs['ret']['openid'],
//                        'transaction_id'    => $rs['ret']['transaction_id'],
//                        'mchtype'           => $post_data['mchtype'],
//                        'pmid'              => session('SX_USERS.parentId'),
//                        'ispay'            => $ispay,
//                        'paytime'           => time(),
//                    );
//                    D('Manage/XyOrder')->addOrder($orderInfo);      
                    
                    if(1 == $ispay){
                        //打印小票
                        $order_id = $out_trade_no;
                            $order_info = D('Manage/Order')->where(array('order_id' => $order_id))->find();
                            $ustaff = D('Manage/Ustaffs')->where(array('usId'=>$order_info['eid']))->find();
                            if(!empty($ustaff['printer_sn'])){//是否绑定了打印机
                                $printer_info = D('Manage/Printer')->where(array('printer_sn'=>$ustaff['printer_sn']))->find();
                                $printer = A('Printer');
                                $printer->setPrinterInfo($printer_info['printer_sn'], $printer_info['printer_key'], $printer_info['printer_version']);
                                $printer->setOrderInfo($order_info, $printer_info['printer_version']);
                                if($order_info['has_print'] == 0){//未打印
                                    $printer->toPrintOrder();
                                    //更新订单打印状态，避免重复打印
                                    D('Manage/Order')->where(array('order_id' => $order_id))->updatePrintStatus($order_id, 1);
                                }
                            }
                            //支付成功发送通知            
                            if($order_info['has_sendMsg'] == 0){//未发送消息提醒
                                $store = D('Manage/Stores')->where(array('storeId'=>$order_info['storeid']))->find();
                                if(!empty($store)){
                                    $post['userId'] = $order_info['uid'];
                                    $post['usId'] = $order_info['eid'];
                                    $post['storeId'] = $order_info['storeid'];//session('SX_USERS.storeId');
                                    $post['price'] = $order_info['goods_price'];
                                    $post['merchant_name'] = $store['branch_name'];
                                    if($order_info['pay_way'] == 'weixin'){
                                        $post['payType'] = 1;
                                    }else{
                                        $post['payType'] = 2;
                                    }
                                    $post['orderId'] = $order_info['order_id'];
                                    $this->sendWxmessage($post);
                                    //更新消息提醒状态，避免重复提醒
                                    D('Order')->where(array('order_id' => $order_id))->updateSendMsgStatus($order_id, 1);
                                }
                            }
                    }

                    
                    if($rs['status'] == 0 && $rs['result_code'] == 1){
                        if('Y' == $rs['need_query']){
                            $rd['status'] = 2;
                            $rd['orderid'] = $data['out_trade_no'];
                            $rd['msg'] = '等待用户输入密码，请勿关闭！';
                        }else{
                            $rd['status'] = -1;
                            $rd['msg'] = '支付失败！';
                        }                        
                    }else{
                        $rd['status'] = 1;
                        $rd['msg'] = $ispay ? '支付成功！' : '支付失败！';
                    }
                    
                    $rd['price'] = $goods_price;
                    $this->ajaxReturn($rd);
                }
                else
                {
                    $rd['msg'] = $rs['msg'];
                    $this->ajaxReturn($rd);
                }
            }
            else
            {
                $rd['msg'] = "请选择受理模式";
                $this->ajaxReturn($rd);
            }
        }
        else
        {
            $this->isLogin();
            $type = I('type'); //i是调用
            $type = empty($type)?1:$type;
            $usId = session('SX_USERS.usId');
            switch($type){
                case 1:
                $this->checkPrivelege('wx_sksy');
                break;
                case 2:
                $this->checkPrivelege('wx_smtk');
                break;
            }

            $m = D('Manage/Order');
            if(empty($usId)){
                $order = $m->getAll(session('SX_USERS.userId'),20);
            }else{
                $order = $m->getAll($usId,20,2); //1商户 2员工
            }
            $this->assign("type",$type);
            $this->assign('order',$order);

            $this->display();
        }
    }
    
     /*
     * 退款
     */
    public function refund(){
        if(IS_POST)
        {            
            $this->isAjaxLogin();
            $data = array();
            if(!empty(I('auth_code'))){
                $data['transaction_id'] = I('auth_code');
                $order_info = D('Manage/Order')->where(array('transaction_id' => $data['transaction_id']))->find();
            }else{
                $id = I('id');
                $order_info = D('Manage/Order')->where(array('id' => $id))->find();
                $data['transaction_id'] = $order_info['transaction_id'];
            }
            
            
            if($order_info['paytime'] < strtotime(date('Y-m-d'))){
                $rd['status'] = -2;
                $rd['msg'] = '仅支持本日订单进行退款，已经算订单无法退款！';
                $this->ajaxReturn($rd);
                return;
            }
            $refund_fee = number_format(I('refund_fee'), 2, '.', '');
            $total_refund_fee = number_format($refund_fee + $order_info['refund_fee'], 2, '.', '');
            if($total_refund_fee > $order_info['goods_price']){
                $rd['status'] = -2;
                $rd['msg'] = '可退款金额不可大于订单金额！';
                $this->ajaxReturn($rd);
                return;
            }
            
                       
            $m = D('Manage/Users');
            $users = $m->get(session('SX_USERS.userId'));
            $post_data = $this->gettypedata($users);
            $data['mch_id'] = $post_data['mch_id'];
            //!empty($order_info['out_refund_no']) ? $order_info['out_refund_no'] :
            $data['out_refund_no'] = $post_data['mch_id'] . date("YmdHis");//退款订单号
            $data['total_fee'] = $order_info['goods_price']*100;//转化为分
            $data['refund_fee'] = $refund_fee*100;
            
            $obj = D('Manage/XyMicropay');
            $obj->setKey($post_data['xy_key']);
            $rs = $obj->submitRefund($data);
            if(isset($rs['status']) && $rs['status'] == 200){
                
                $data = array();
                //更新退款状态为退款中，写入退款单号
                $data['transaction_id'] = $rs['data']['transaction_id'];
                $data['out_refund_no'] = $rs['data']['out_refund_no'];
                $data['refund_fee'] = $rs['data']['refund_fee'] / 100;//+$order_info['refund_fee'];
                $data['refund'] = 1;//退款中
                $usId = session('SX_USERS.usId');
                $storeId = session('SX_USERS.storeId');
                
                $m = D('Manage/Order')->updateWxOrder($data, $usId, $storeId);
                
                $order_info['refund_fee'] = $refund_fee;
                $rd['status'] = 1;
                $rd['msg'] = '提交退款成功！'.$rs['msg'];
                $printer = A('Printer');
                $printer->toPrintRefund($order_info);
                $this->ajaxReturn($rd);
             }else{
                $rd['status'] = -1;
                $rd['msg'] = '退款失败！'.$rs['msg'];
                $this->ajaxReturn($rd);
             }
        }
    }
    
    public function queryRefund(){
 
            $this->isAjaxLogin();
            $data = array();
            $id = I('id');
            $order_info = D('Manage/Order')->where(array('id' => $id))->find();
            
            $data['transaction_id'] = $order_info['transaction_id'];
            $m = D('Manage/Users');
            $users = $m->get(session('SX_USERS.userId'));
            $post_data = $this->gettypedata($users);
            $data['mch_id'] = $post_data['mch_id'];
            $obj = D('Manage/XyMicropay');
            $obj->setKey($post_data['xy_key']);            
            $rs = $obj->queryRefund($data);
            if(isset($rs['status']) && $rs['status'] == 200){
                
                $data = array();
                //更新退款状态
                $index = 'refund_status_'. ($rs['data']['refund_count'] - 1);
                $refund_status = $rs['data']['refund_status_0'];
                if('SUCCESS' == $refund_status){
                    $refund = 2;//已退款
                }elseif ('PROCESSING' == $refund_status) {
                    $refund = 1;//退款中
                }else{
                    $refund = 3;//退款失败
                }
                $data['refund'] = $refund;//
                $data['transaction_id'] = $rs['data']['transaction_id'];
                $data['refund_fee'] = $rs['data']['refund_fee'] / 100;//退款成功的总金额，单位转化为元
                $usId = session('SX_USERS.usId');
                $storeId = session('SX_USERS.storeId');
                
                $m = D('Manage/Order')->updateWxOrder($data, $usId, $storeId);
                
                $rd['status'] = $refund;
                $rd['msg'] = '查询退款成功！'.$rs['msg'];
                $this->ajaxReturn($rd);
             }else{
                 if('REFUNDNOTEXIST' == $rs['err_code']){
                     $rd['msg'] = '退款订单不存在！';
                 }else{
                     $rd['msg'] = '查询退款失败=>'.$rs['msg'];
                 }                 
                $rd['status'] = -1;
                
                $this->ajaxReturn($rd);
             }
//        }
    }

    //自助支付
    public function self_pay()
    {
        if(IS_POST)
        {
            $uid = I('userId');
            if(!$uid)
            {
                echo json_encode(array('status' => 500, 'msg' => 'uid必传！'));
                exit;
            }
            $m = D('Manage/Users');
            $users = $m->get($uid);
            $post_data = $this->gettypedata($users);
            if(empty($post_data))
            {
                echo json_encode(array('status' => 500, 'msg' => '商户参数错误！'));
                exit;
            }

            if(isWeixin())
            {
                //获取用户openid
//                if(13 == $users['userId']){//金苑酒家--老客家
//                    $post_data['appid'] = 'wx9918086e11170b90';
//                    $post_data['appSecret'] = 'c2022d37373838868a51d734593b1d1f';
//                }
//                if(61 == $users['userId']){//金苑酒家->金苑酒家
//                    $post_data['appid'] = 'wx9d4e651b8e90746f';
//                    $post_data['appSecret'] = 'c3d640b4e44435b6dcc338a97219f356';
//                }
//                if(35 == $users['userId']){//贵食食品
//                    $post_data['appid'] = 'wxab45bcd64206a9b1';
//                    $post_data['appSecret'] = '15e8b01df879dba88b8a45164bb99000';
//                }
//                if(119 == $users['userId']){//唐朝
//                    $post_data['appid'] = 'wx48cf72859f8cc09f';
//                    $post_data['appSecret'] = '6313233b1017dd1116f5b987c419ec48';
//                }
//                if(116 == $users['userId']){//汇美美妆
//                    $post_data['appid'] = 'wxd89a502c27a68683';
//                    $post_data['appSecret'] = 'b93d9cea1f4b08a21cb92fdd49bbf08d';
//                }
//                if(165 == $users['userId']){//中广源
//                    $post_data['appid'] = 'wx041ab5eb0393198a';
//                    $post_data['appSecret'] = '4ac544eaac8bf32e033dd4d415ea2d92';
//                }
//                if(171 == $users['userId']){//兴宁市茜茜蛋糕奶啡店
//                    $post_data['appid'] = 'wx49f31158f1ccae62';
//                    $post_data['appSecret'] = '8a5cad7af87e8aa06248d52b5963a4ff';
//                }                
//                if(175 == $users['userId']){//瀚浩实业-兴宁A380美发机构
//                    $post_data['appid'] = 'wxc82d9181f1e44845';
//                    $post_data['appSecret'] = 'ba729d57a9884933cf9d38a695ddc7d2';
//                }
//                if(177 == $users['userId']){//深华志林大药房
//                    $post_data['appid'] = 'wxd490d1e7a3383e8d';
//                    $post_data['appSecret'] = '8fcfa79d7e05e51cf61a4552f677357a';
//                }
//                if(149 == $users['userId']){//梅州市精点之家餐饮服务有限公司 
//                    $post_data['appid'] = 'wxd38901c0d5f63ea7';
//                    $post_data['appSecret'] = '517ea9f9c60cd7f5f8ce7b095a04b24d';
//                }
//                if(196 == $users['userId']){//人人乐超市 
//                    $post_data['appid'] = 'wx97cc12a40e37fe58';
//                    $post_data['appSecret'] = '31399d6ef6c8c8cf3c067715d8df58bb';
//                }
//                if(200 == $users['userId']){//金誉 
//                    $post_data['appid'] = 'wxfa9996a96a0cb8b6';
//                    $post_data['appSecret'] = '3d0ff7ac583d7292ea58ea7754bd0838';
//                }
//                if(39 == $users['userId']){//梅州金誉 
//                    $post_data['appid'] = 'wxfa9996a96a0cb8b6';
//                    $post_data['appSecret'] = '3d0ff7ac583d7292ea58ea7754bd0838';
//                }
//                if(in_array($users['userId'], array(38, 161, 143, 139))){//兴宁 平远 蕉岭 大浦金誉 
//                    $post_data['appid'] = 'wxfa9996a96a0cb8b6';
//                    $post_data['appSecret'] = '3d0ff7ac583d7292ea58ea7754bd0838';
//                }
//                if(in_array($users['userId'], array(236, ))){//兴宁市科博眼镜有限公司
//                    $post_data['appid'] = 'wx2b5257b9309c8d3e';
//                    $post_data['appSecret'] = '331cce105a85ef59623b1c12a92ab6da';
//                }
//                if(in_array($users['userId'], array(241, ))){//兴宁市大众汽车检测
//                    $post_data['appid'] = 'wxa408f8c3b1e10884';
//                    $post_data['appSecret'] = '29a8ddd36b49b0cb15628bd67cfbc28b';
//                }
//                if(in_array($users['userId'], array(237, ))){//梅州市梅县区荣利隆科技有限公司 
//                    $post_data['appid'] = 'wxa47ba8155a93fd02';
//                    $post_data['appSecret'] = '7d52a431628ea62f948d0894afbb31de';
//                }
//                if(in_array($users['userId'], array(226, ))){//梅州市梅县区汇佳超市 
//                    $post_data['appid'] = 'wx63a9ee203035965d';
//                    $post_data['appSecret'] = '94b53bc8e1c41368bab74fe284c85d52';
//                }
                
                $obj = A('Gzzh');
                $obj->setKey($post_data['xy_key']);
                $obj->index();
            } 
            elseif(isAli())
            {
                $obj = A('Ali');
                $obj->setKey($post_data['xy_key']);
                $obj->index();
            }
            else
            {
                exit('支付方式错误');
            }
        }
        else
        {
            $data = array(
                'userId'    => I('userId'),
                'usId'      => I('usId'),
                'storeId'   => I('storeId'),
                'parentId'  => I('parentId'),
            );

            $buyer_id = $pay_type = '';
            if(isWeixin())
            {
                $url = U('self_pay@' . C('SITE_URL'), $data);
                $appid = 'wx279a7af089a58da1';
                $appSecret = '7da603f1e9fd99bab762da217a825544';
                $c = D('SX/Configs');
                $configs = $c->loadConfigs();
                $appid = $configs['gd_appId'];//'wxb642619da3a82750';
                $appSecret = $configs['gd_appSecret'];
                $m = D('Manage/Users');
                $user = $m->get($data['userId']);
                if(!empty($user['wx_appId']) && !empty($user['wx_appSecret'])){
                    $appid = $user['wx_appId'];
                    $appSecret = $user['wx_appSecret'];
                }
                
//                if(6 == $data['storeId']){//金苑酒家-》老客家
//                    $appid = 'wx9918086e11170b90';
//                    $appSecret = 'c2022d37373838868a51d734593b1d1f';
//                }
//                if(102 == $data['storeId']){//金苑酒家->金苑酒家
//                    $appid = 'wx9d4e651b8e90746f';
//                    $appSecret = 'c3d640b4e44435b6dcc338a97219f356';
//                } 
//                if(in_array($data['storeId'], array('45', '46', '47', '48', '49'))){
//                if(45 == $data['storeId'] || 46 == $data['storeId'] || 47 == $data['storeId'] || 48 == $data['storeId'] || 49 == $data['storeId'] ){//贵食食品
//                    $appid = 'wxab45bcd64206a9b1';
//                    $appSecret = '15e8b01df879dba88b8a45164bb99000';
//                }
//                if(166 == $data['storeId'] || 167 == $data['storeId'] || 168 == $data['storeId'] || 169 == $data['storeId']){//唐朝
//                    $appid = 'wx48cf72859f8cc09f';
//                    $appSecret = '6313233b1017dd1116f5b987c419ec48';
//                }
//                if(162 == $data['storeId'] || 163 == $data['storeId']){//汇美美妆
//                    $appid = 'wxd89a502c27a68683';
//                    $appSecret = 'b93d9cea1f4b08a21cb92fdd49bbf08d';
//                }
//                if(224 == $data['storeId']){//中广源
//                    $appid = 'wx041ab5eb0393198a';
//                    $appSecret = '4ac544eaac8bf32e033dd4d415ea2d92';
//                }
//                if(231 == $data['storeId']){//兴宁市茜茜蛋糕奶啡店
//                    $appid = 'wx49f31158f1ccae62';
//                    $appSecret = '8a5cad7af87e8aa06248d52b5963a4ff';
//                }                
//                if(237 == $data['storeId']){//瀚浩实业-兴宁A380美发机构
//                    $appid = 'wxc82d9181f1e44845';
//                    $appSecret = 'ba729d57a9884933cf9d38a695ddc7d2';
//                }
//                if(243 == $data['storeId'] || 244 == $data['storeId'] || 245 == $data['storeId']){//深华志林大药房
//                    $appid = 'wxd490d1e7a3383e8d';
//                    $appSecret = '8fcfa79d7e05e51cf61a4552f677357a';
//                }
//                if(208 == $data['storeId']){//精点之家
//                    $appid = 'wxd38901c0d5f63ea7';
//                    $appSecret = '517ea9f9c60cd7f5f8ce7b095a04b24d';
//                }
//                if(257 == $data['storeId']){//人人乐超市 
//                    $appid = 'wx97cc12a40e37fe58';
//                    $appSecret = '31399d6ef6c8c8cf3c067715d8df58bb';
//                }
//                if(in_array($data['storeId'], array(261, 9999))){//珊珊测试蛋糕 
//                    $appid = 'wxfa9996a96a0cb8b6';
//                    $appSecret = '3d0ff7ac583d7292ea58ea7754bd0838'; 
//                }

//                if(in_array($data['storeId'], array(50,51,52,53,54,55,56,57,58,59,60,61,62,63,263, 278))){//梅州金誉蛋糕 
//                    $appid = 'wxfa9996a96a0cb8b6';
//                    $appSecret = '3d0ff7ac583d7292ea58ea7754bd0838';
//                }                          
//                if(in_array($data['storeId'], array(193,238,198,197,220,89,90,91))){//兴宁 平远 蕉岭 大浦金誉 
//                    $appid = 'wxfa9996a96a0cb8b6';
//                    $appSecret = '3d0ff7ac583d7292ea58ea7754bd0838';
//                }    
//                if(in_array($data['storeId'], array(299))){//兴宁市科博眼镜有限公司
//                    $appid = 'wx2b5257b9309c8d3e';
//                    $appSecret = '331cce105a85ef59623b1c12a92ab6da';
//                }    
//                if(in_array($data['storeId'], array(304))){//兴宁市大众汽车检测
//                    $appid = 'wxa408f8c3b1e10884';
//                    $appSecret = '29a8ddd36b49b0cb15628bd67cfbc28b';
//                }   
//                if(in_array($data['storeId'], array(300))){//梅州市梅县区荣利隆科技有限公司
//                    $appid = 'wxa47ba8155a93fd02';
//                    $appSecret = '7d52a431628ea62f948d0894afbb31de';
//                }
//                if(in_array($data['storeId'], array(292, 293))){//梅州市梅县区汇佳超市
//                    $appid = 'wx63a9ee203035965d';
//                    $appSecret = '94b53bc8e1c41368bab74fe284c85d52';
//                }
                              
                Vendor('Weixin.JsApiPay');
                $JsApiPay = new \JsApiPay($appid, $appSecret);
                $openid = $JsApiPay->GetOpenid($url);
                
                //return $openid;
                $buyer_id = $openid;
                $pay_type = '微信支付';
            }
            elseif(isAli())
            {
                $auth_code = I('auth_code');                
                if(empty($auth_code))
                {                   
                    $appid = '2015120400912117';//'2016070101572626';//'2016122804691269';//'2088521046853877';//2088421597169606
                    $url = U('self_pay@' . C('SITE_URL'), $data);

                    $scope = "auth_base";//"auth_userinfo";//
                    $redirect_uri = urlencode($url);
                    $url = "https://openauth.alipay.com/oauth2/publicAppAuthorize.htm?app_id=".$appid."&scope=".$scope."&redirect_uri=".$redirect_uri;
                    header("Location:".$url);
                  
                }
               
                Vendor('Alipay.AopSdk');
                $AlipaySystemOauthTokenRequest = new \AlipaySystemOauthTokenRequest ();
                $AlipaySystemOauthTokenRequest->setCode ( $auth_code );
                $AlipaySystemOauthTokenRequest->setGrantType ( "authorization_code" );
                $result = \aopclient_request_execute ( $AlipaySystemOauthTokenRequest );
                $buyer_logon_id = $result->alipay_system_oauth_token_response->user_id;

                $buyer_id = $buyer_logon_id;
                $pay_type = '支付宝支付';                
            }

            $data = array(
                'userId'    => I('userId'),
                'usId'      => I('usId'),
                'storeId'   => I('storeId'),
                'parentId'  => I('parentId'),
            );
            $store = D('Stores')->where(array('storeId'=>$data['storeId']))->find();
            if(!empty($store['branch_name'])){
                $this->assign('branch_name', $store['branch_name']);
            }else{
                $this->assign('branch_name', '商户总店收银');
            }
            
            $this->assign('data', $data);
            $this->assign('pay_type', $pay_type);
            $this->assign('buyer_id', $buyer_id);
//            if(41 == $data['userId']){
            
            $this->display('scancode_pay');
            
//            }else{
//                $this->display();
//            }
            
        }
    }
    
    
    //自助支付
    public function scancode_pay()
    {
        if(IS_POST)
        {
            $uid = I('userId');
            if(!$uid)
            {
                echo json_encode(array('status' => 500, 'msg' => 'uid必传！'));
                exit;
            }
            $m = D('Manage/Users');
            $users = $m->get($uid);
            
            $post_data = $this->gettypedata($users);
            if(empty($post_data))
            {
                echo json_encode(array('status' => 500, 'msg' => '商户参数错误！'));
                exit;
            }
            if(isWeixin())
            {
                //获取用户openid                      
                $obj = A('Gzzh');
                $obj->setKey($post_data['xy_key']);
                $obj->index();
            }
            elseif(isAli())
            {
                $obj = A('Ali');
                $obj->setKey($post_data['xy_key']);
                $obj->index();
            }
            else
            {
                exit('支付方式错误');
            }
        }
        else
        {
            $data = array(
                'userId'    => I('userId'),
                'usId'      => I('usId'),
                'storeId'   => I('storeId'),
                'parentId'  => I('parentId'),
            );

            $buyer_id = $pay_type = '';
            if(isWeixin())
            {
                $url = U('scancode_pay@' . C('SITE_URL'), $data);
                $appid = 'wx279a7af089a58da1';
                $appSecret = '7da603f1e9fd99bab762da217a825544';
                $m = D('Manage/Users');
                $user = $m->get($data['userId']);
                if(!empty($user['wx_appId']) && !empty($user['wx_appSecret'])){
                    $appid = $user['wx_appId'];
                    $appSecret = $user['wx_appSecret'];
                }                

                Vendor('Weixin.JsApiPay');
                $JsApiPay = new \JsApiPay($appid, $appSecret);
                $openid = $JsApiPay->GetOpenid($url);
                
                //return $openid;
                $buyer_id = $openid;
                $pay_type = '微信支付';
            }
            elseif(isAli())
            {
                $auth_code = I('auth_code');
                if(empty($auth_code))
                {
                    $appid = '2015120400912117';//'2016070101572626';//'2016122804691269';//'2088521046853877';//
                    $url = U('scancode_pay@' . C('SITE_URL'), $data);

                    $scope = "auth_base";
                    $redirect_uri = urlencode($url);
                    $url = "https://openauth.alipay.com/oauth2/publicAppAuthorize.htm?app_id=".$appid."&scope=".$scope."&redirect_uri=".$redirect_uri;
                    header("Location:".$url);
                }

                Vendor('Alipay.AopSdk');
                $AlipaySystemOauthTokenRequest = new \AlipaySystemOauthTokenRequest ();
                $AlipaySystemOauthTokenRequest->setCode ( $auth_code );
                $AlipaySystemOauthTokenRequest->setGrantType ( "authorization_code" );
                $result = \aopclient_request_execute ( $AlipaySystemOauthTokenRequest );
                $buyer_logon_id = $result->alipay_system_oauth_token_response->user_id;

                $buyer_id = $buyer_logon_id;
                $pay_type = '支付宝支付';
            }

            $data = array(
                'userId'    => I('userId'),
                'usId'      => I('usId'),
                'storeId'   => I('storeId'),
                'parentId'  => I('parentId'),
            );
            $store = D('Stores')->where(array('storeId'=>$data['storeId']))->find();
            if(!empty($store['branch_name'])){
                $this->assign('branch_name', $store['branch_name']);
            }else{
                $this->assign('branch_name', '商户总店收银');
            }
            
            $this->assign('data', $data);
            $this->assign('pay_type', $pay_type);
            $this->assign('buyer_id', $buyer_id);
            $this->display('scancode_pay_1');           
        }
    }
   
    
    function test()
    {
        $url = U('test@' . C('SITE_URL'));
        $appid = 'wx279a7af089a58da1';
        $appSecret = '7da603f1e9fd99bab762da217a825544';

        Vendor('Weixin.JsApiPay');
        $JsApiPay = new \JsApiPay($appid, $appSecret);
        $openid = $JsApiPay->GetOpenid($url);
        dump($openid);
    }

    /**
     * 显示付款成功界面
     */
    public function successTips()
    {
        $order_id = I('order_id');
        $order_info = D('Order')->where(array('order_id' => $order_id))->find();
        //dump($order_info);
        //exit;

        $data = array();
        $data['userId'] = $order_info['uid'];
        $data['openid'] = $order_info['openid'];
        $data['tprice'] = $order_info['goods_price'];
        $data['storeId'] = $order_info['storeid'];
        $data['usId'] = $order_info['eid'];
        $data['paytype'] = $order_info['pay_way'];//$order_info['pay_type'];
                
                
//        //是否达到红包领取条件
//        $p = D('Manage/Wxredpack');
//        $redpack = $p->loadRedpack($data['userId']);
//        if($redpack['isEffect'] == 1)
//        {
//            if(strstr($redpack['redStoreId'],'('.$data['storeId'].')'))
//            {
//                $rp = "open";
//            }
//        }

        //获取门店地理位置
        if(!empty($data['storeId'])){
            $s = D('Manage/Stores');
            $store = $s->getStore($data['storeId']);
            if(!empty($store)){
                $myLat = $store['latitude']; //纬度
                $myLng = $store['longitude']; //经度
                $num = 5; //代表搜索 5km 之内，单位km 
                $range = 180 / pi() * $num / 6372.797;      
                $lngR = $range / cos($myLat * pi() / 180);  
                $maxLat = $myLat + $range;//最大纬度  
                $minLat = $myLat - $range;//最小纬度  
                $maxLng = $myLng + $lngR;//最大经度  
                $minLng = $myLng - $lngR;//最小经度
                //显示微信社区
                $b = D('Manage/Wxbusiness');
                $business = $b->searchlocation($maxLat,$minLat,$maxLng,$minLng);
            }
        }

        $a = D('SX/Advertising');
        $adv = $a->get();
//        if(41==$data['userId']){
//           $adv['isshow'] = 1; 
//        }else{
//            $adv['isshow'] = 0;
//        }
       
        if($adv['status'] == 1){
            $Ip = new \Org\Net\IpLocation('ip.dat');
            $area = $Ip->getlocation();
            $area = $area['country'];

            //如果地址匹配
            if($adv['area'] == "0"){
                $adv['isshow'] = 1;
            }else{
                if(strpos($area,$adv['area'])===0){
                    $adv['isshow'] = 1;
                }
            }
        }
        $adv['isshow'] = $adv['status'];

        $this->assign("data",$data);
        $this->assign("rp",$rp);
        $this->assign("business",$business);
        $this->assign("myLat",$myLat);
        $this->assign("myLng",$myLng);
        $this->assign("adv",$adv);
        $this->display("Wxcashier/successTips");
    }

    public function addQrCodePayOrder($data){

    }

    /**
     * 提交订单信息
     */
    public function submitOrderInfo($data)
    {
        $trade_type = $data['trade_type'];
        if($trade_type == 'weixin')
        {
            $data['trade_type'] = $trade_type = 'pay.weixin.native';
        }
        elseif($trade_type == 'alipay')
        {
            $data['trade_type'] = $trade_type = 'pay.alipay.nativev2';
        }
        else
        {
            return array('status' => 5010, 'msg' => '支付方式失败！');
        }


        $res = D('Manage/XyOrder')->addOrder($data);
        if(!$res)
        {
            return array('status' => 5010, 'msg' => '创建订单失败！');
        }

        $out_trade_no   = $data['out_trade_no'];
        $body           = $data['tname'];
        $tprice         = $data['total_fee'];


        Vendor('Xingye.RequestHandler', '', '.class.php');
        Vendor('Xingye.ClientResponseHandler', '', '.class.php');
        Vendor('Xingye.PayHttpClient', '', '.class.php');
        Vendor('Xingye.Config');

        $this->resHandler = new \ClientResponseHandler();
        $this->reqHandler = new \RequestHandler();
        $this->pay = new \PayHttpClient();
        $this->cfg = new \Config();
        $this->reqHandler->setGateUrl($this->cfg->C('url'));

        //$this->reqHandler->setReqParams($_POST,array('method'));

        $m = D('Manage/Users');
        $users = $m->get(session('SX_USERS.userId'));
        //dump($users);
        $post_data = $this->gettypedata($users);
        //dump($post_data);

        //dump($post_data['mch_id']);

        #$this->reqHandler->setKey($this->cfg->C('key'));
        $this->reqHandler->setKey($post_data['xy_key']);
        
        //exit;

        $this->reqHandler->setParameter('out_trade_no', $out_trade_no);
        $this->reqHandler->setParameter('body', $body);
        //$this->reqHandler->setParameter('attach', "bank_mch_name=".$users['zz_jc']."&bank_mch_id=".$users['gd_mchId']."&".session('SX_USERS.userId').",".session('SX_USERS.usId').",".session('SX_USERS.storeId').",".session('SX_USERS.parentId').",".$post_data['mchtype'].",".$post_data['body'].","."扫码支付");
        $this->reqHandler->setParameter('total_fee', $tprice * 100);
        $this->reqHandler->setParameter('mch_create_ip','127.0.0.1');
        $this->reqHandler->setParameter('service', $trade_type);
        //$this->reqHandler->setParameter('service','pay.alipay.nativev2');//接口类型：pay.weixin.native (微信扫码) pay.alipay.nativev2 (支付宝扫码)
        
        #$this->reqHandler->setParameter('mch_id',$this->cfg->C('mchId'));//必填项，商户号，由威富通分配
        $this->reqHandler->setParameter('mch_id', $post_data['mch_id']);

        $this->reqHandler->setParameter('version',$this->cfg->C('version'));
        
        //通知地址，必填项，接收威富通通知的URL，需给绝对路径，255字符内格式如:http://wap.tenpay.com/tenpay.asp
        //$notify_url = 'http://'.$_SERVER['HTTP_HOST'];
        //$this->reqHandler->setParameter('notify_url',$notify_url.'/payInterface/request.php?method=callback');
        $this->reqHandler->setParameter('notify_url', U("Manage/Xingye/callback@".C('SITE_URL')) );
        $this->reqHandler->setParameter('nonce_str',mt_rand(time(),time()+rand()));//随机字符串，必填项，不长于 32 位
        $this->reqHandler->createSign();//创建签名
        
        dataRecodes('接口请求参数', $this->reqHandler->getAllParameters());

        $new_data = toXml($this->reqHandler->getAllParameters());
        $this->pay->setReqContent($this->reqHandler->getGateURL(), $new_data);
        if($this->pay->call()){
            $this->resHandler->setContent($this->pay->getResContent());
            $this->resHandler->setKey($this->reqHandler->getKey());
            if($this->resHandler->isTenpaySign()){
                //当返回状态与业务结果都为0时才返回支付二维码，其它结果请查看接口文档
                if($this->resHandler->getParameter('status') == 0 && $this->resHandler->getParameter('result_code') == 0){
                    
                    return    array(
                            'status' => 1,
                            'code_url'=> $this->resHandler->getParameter('code_img_url'),//二维码
                            'qrcode'=>$this->resHandler->getParameter('code_url'),
                            'code_status'=>$this->resHandler->getParameter('code_status')
                        );
                }else{
                    return array('status'=>5001,'msg'=>'Error Code:'.$this->resHandler->getParameter('err_code').' Error Message:'.$this->resHandler->getParameter('err_msg'));
                }
            }
            return array('status'=>5002,'msg'=>'Error Code:'.$this->resHandler->getParameter('status').' Error Message:'.$this->resHandler->getParameter('message'));
        }else{
            return array('status'=>5003,'msg'=>'Response Code:'.$this->pay->getResponseCode().' Error Info:'.$this->pay->getErrInfo());
        }
    }

    /**
     * 查询订单
     */
    public function queryOrder(){
        if(IS_POST){
            Vendor('Xingye.RequestHandler', '', '.class.php');
            Vendor('Xingye.ClientResponseHandler', '', '.class.php');
            Vendor('Xingye.PayHttpClient', '', '.class.php');
            Vendor('Xingye.Config');

            $this->resHandler = new \ClientResponseHandler();
            $this->reqHandler = new \RequestHandler();
            $this->pay = new \PayHttpClient();
            $this->cfg = new \Config();
            $this->reqHandler->setGateUrl($this->cfg->C('url'));

            $m = D('Manage/Users');
            $users = $m->get(session('SX_USERS.userId'));
            $post_data = $this->gettypedata($users);        

            dataRecodes('参数请求POST',$_POST);
            $orderId = I('orderid');
            $this->reqHandler->setParameter('out_trade_no',$orderId);
            $reqParam = $this->reqHandler->getAllParameters();
            dataRecodes('参数请求reqParam',$reqParam);
            if(empty($reqParam['transaction_id']) && empty($reqParam['out_trade_no'])){
                $rd = array('status'=>2,
                    'msg'=>'请输入商户订单号,威富通订单号!');
                $this->ajaxReturn($rd);
                exit();
            }
            $this->reqHandler->setKey($post_data['xy_key']);
    //        $this->reqHandler->setParameter('version',$this->cfg->C('version'));
            $this->reqHandler->setParameter('service','unified.trade.query');//接口类型：unified.trade.query
    //        $this->reqHandler->setParameter('mch_id',$this->cfg->C('mchId'));//必填项，商户号，由威富通分配
            $this->reqHandler->setParameter('mch_id', $post_data['mch_id']);
            $this->reqHandler->setParameter('nonce_str',mt_rand(time(),time()+rand()));//随机字符串，必填项，不长于 32 位
            $this->reqHandler->createSign();//创建签名
            $data = toXml($this->reqHandler->getAllParameters());
            dataRecodes('参数请求',$data);
            $this->pay->setReqContent($this->reqHandler->getGateURL(),$data);
            if($this->pay->call()){
                $this->resHandler->setContent($this->pay->getResContent());
                $this->resHandler->setKey($this->reqHandler->getKey());
                if($this->resHandler->isTenpaySign()){
                    $rs = $this->resHandler->getAllParameters();

                    dataRecodes('查询订单',$rs);
                    if($rs['status'] == "0" && $rs['result_code'] == "0"){
                        if($rs['trade_state'] == "SUCCESS"){
                            $rd['status'] = 1;
                            $rd['price'] = $rs['total_fee'] / 100;
                            //更新订单状态
                            $updatedata['paytime'] = time();
                            $updatedata['ispay'] = 1;
                            if(strstr($rs['trade_type'], "weixin"))//支付平台
                            {
                                $updatedata['pay_way'] = "weixin"; 
                            }
                            else if(strstr($rs['trade_type'], "alipay"))
                            {
                                $updatedata['pay_way'] = "alipay";
                            }else{
                                 $updatedata['pay_way'] = "other"; //支付平台
                            }
                            
                            if(strstr($rs['trade_type'], "micropay"))//支付类型
                            {
                                $updatedata['pay_type'] = "MICROPAY"; 
                            }
                            else if(strstr($rs['trade_type'], "native"))
                            {
                                $updatedata['pay_type'] = "NATIVE";
                            }
                            else if(strstr($rs['trade_type'], "jspay"))
                            {
                                $updatedata['pay_type'] = "JSAPI";
                            }
                            else
                            {
                                $updatedata['pay_type'] = "OTHER";
                            }
                            $updatedata['transaction_id'] = $rs['transaction_id'];
                            $updatedata['openid'] = $rs['openid'];
//                            dataRecodes('更新状态',$updatedata);
                            $o = D('Manage/XyOrder')->editOrderStatus($orderId, $updatedata);
    //                        $o = D('Manage/Order');
    //                        $o->addWxOrder($rs);
    //                        //支付成功发送通知
    //                        $post['userId'] = session('SX_USERS.userId');
    //                        $post['usId'] = session('SX_USERS.usId');
    //                        $post['storeId'] = session('SX_USERS.storeId');
    //                        $post['price'] = $rd['price'];
    //                        $post['body'] = $attach[5];
        //                    $this->sendWxmessage($post);
//                            dataRecodes('返回结果',$rd);
                        }else if($rs['trade_state'] == "USERPAYING"){
                            $rd['status'] = 2;
                        }else{
                            $rd['status'] = 2;
                            if($rs['trade_state'] == "NOTPAY"){
                                $rd['msg'] = "用户取消支付";
                            }else{
                                $rd['msg'] = "支付失败";
                            }
                        }
                    }else{
                        if(empty($rs['err_msg'])){ //连接超时继续等待
                            $rd['status'] = 2;
                        }else{
                            $rd['status'] = -1;
                            $rd['msg'] = $rs['err_msg'];
                        }
                    }
                }else{
                    $rd['status'] = -1;
                    $rd['msg'] = '签名验证错误';
                }

            }else{
                $rd = array('status'=>-1,'msg'=>'Response Code:'.$this->pay->getResponseCode().' Error Info:'.$this->pay->getErrInfo());
            }
//            dataRecodes('返回结果1',$rd);
            $this->ajaxReturn($rd);
        }
        
    }
    
    /**
	 * 查询微信订单
	 */
    public function orderquery(){
    	$this->isAjaxLogin();
    	$rd = array('status'=>-1);
        $m = D('Manage/Users');
        $users = $m->get(session('SX_USERS.userId'));
        $post_data = array();

        //判断商户类别，特约商户调用系统微信配置，受理模式调用光大银行配置
        $post_data = $this->gettypedata($users);
        dataRecodes('受理模式下小额接口post_data', $post_data);
        if($post_data['mchtype'] == 3){//受理模式
            $post_data ['out_trade_no'] = I('orderid');
            $post_data ['nonce_str'] = $this->getWxNonceStr(); //随机字符串
            $post_data['service'] = "unified.trade.query"; //接口类型
            $key = $post_data['key']; //渠道密钥
            unset($post_data['appid']);
            unset($post_data['appSecret']);
            unset($post_data['mchtype']);
            unset($post_data['key']);
            $post_data['sign'] = $this->MakeWxSign($post_data,$key);
            $url = "https://pay.swiftpass.cn/pay/gateway";
            $result = $this->wx_xmlpost($url,$this->ToXml($post_data));
            $rs = $this->FromXml($result);
            dataRecodes('受理模式下小额接口查询订单返回参数', $rs);

            if($rs['status'] == "0" && $rs['result_code'] == "0"){
                if($rs['trade_state'] == "SUCCESS"){
                    $rd['status'] = 1;
                    $rd['price'] = $rs['total_fee'] / 100;
                    $o = D('Manage/Order');
                    $o->addWxOrder($rs);
                    //支付成功发送通知
                    $post['userId'] = session('SX_USERS.userId');
                    $post['usId'] = session('SX_USERS.usId');
                    $post['storeId'] = session('SX_USERS.storeId');
                    $post['price'] = $rd['price'];
                    $post['body'] = $attach[5];
//                    $this->sendWxmessage($post);
                }else if($rs['trade_state'] == "USERPAYING"){
                    $rd['status'] = 2;
                }else{
                    if($rs['trade_state'] == "NOTPAY"){
                        $rd['msg'] = "用户取消支付";
                    }else{
                        $rd['msg'] = "支付失败";
                    }
                    
                }
            }else{
                if(empty($rs['err_msg'])){ //连接超时继续等待
                    $rd['status'] = 2;
                }else{
                    $rd['status'] = -1;
                    $rd['msg'] = $rs['err_msg'];
                }
            }
        }else{
            $post_data ['out_trade_no'] = I('orderid');
            $post_data ['nonce_str'] = $this->getWxNonceStr();
            $apikey = $post_data['apikey'];
            unset($post_data['mchtype']);
            unset($post_data['apikey']);
            unset($post_data['sslcert_path']);
            unset($post_data['sslkey_path']);
            unset($post_data['appSecret']);
            unset($post_data['order_id']);
            $post_data ['sign'] = $this->MakeWxSign($post_data,$apikey);
            $url = "https://api.mch.weixin.qq.com/pay/orderquery";
            $result = $this->wx_xmlpost($url,$this->ToXml($post_data));
            $rs = $this->FromXml($result);
            if($rs['return_code'] == "FAIL"){
            	$rd['status'] = -1;
            	$rd['msg'] = $rs['return_msg'];
            }else{
            	if($rs['trade_state'] == "SUCCESS"){
                    $rd['status'] = 1;
                    $rd['price'] = $rs['total_fee'] / 100;
            		$o = D('Manage/Order');
            		$o->addWxOrder($rs);
                    //支付成功发送通知
                    $post['userId'] = session('SX_USERS.userId');
                    $post['usId'] = session('SX_USERS.usId');
                    $post['storeId'] = session('SX_USERS.storeId');
                    $post['price'] = $rd['price'];
                    $post['body'] = $attach[5];
                    $this->sendWxmessage($post);
            	}else if($rs['trade_state'] == "USERPAYING"){
            		$rd['status'] = 2;
            	}else{
            		$rd['msg'] = $rs['trade_state_desc'];
            	}  	
            }
        }
        dataRecodes('受理模式下小额接口查询订单返回结果', $rd);
        $this->ajaxReturn($rd);
    }
    
    /**
     * 提供给威富通的回调方法
     */
    public function callback(){

        Vendor('Xingye.ClientResponseHandler', '', '.class.php');
        Vendor('Xingye.Config');

        $this->resHandler = new \ClientResponseHandler();
        $this->cfg = new \Config();

        $xml = file_get_contents('php://input');
        $this->resHandler->setContent($xml);

        $order_id = $this->resHandler->getParameter('out_trade_no');
        $user_key = get_user_key($order_id);
        $this->resHandler->setKey($user_key);

        if($this->resHandler->isTenpaySign()){
            if($this->resHandler->getParameter('status') == 0 && $this->resHandler->getParameter('result_code') == 0){
                //echo $this->resHandler->getParameter('status');
                // 11;
                //                 
                //更改订单状态
                
                $data = $this->resHandler->getAllParameters();
                $data['ispay'] = 1;
                $data['paytime'] = time();
                $rs = D('Manage/XyOrder')->editOrderStatus($order_id, $data);
                if($rs !== false)
                {
                    echo 'success';
                }
                else
                {
                    echo 'failure1';
                }
                dataRecodes('接口回调收到通知参数',$this->resHandler->getAllParameters());
                //echo 'success';
                exit();
            }else{
                echo 'failure1';
                exit();
            }
        }else{
            echo 'failure2';
        }
    }
    
    //手动打印小票
    public function toPrintOrder(){
        if(IS_POST){
            $id = I('id');
            $order_info = D('Manage/Order')->where(array('id' => $id))->find();
            $ustaff = D('Manage/Ustaffs')->where(array('usId'=>$order_info['eid']))->find();
            if(!empty($ustaff['printer_sn'])){//是否绑定了打印机
                $printer_info = D('Manage/Printer')->where(array('printer_sn'=>$ustaff['printer_sn']))->find();
                $printer = A('Printer');
                $printer->setPrinterInfo($printer_info['printer_sn'], $printer_info['printer_key'], $printer_info['printer_version']);
                $printer->setOrderInfo($order_info, $printer_info['printer_version']);
                $res = $printer->toPrintOrder();
                if(1 == $res['status']){
                    $res['msg'] = '打印成功！';
                }else{
                    $res['msg'] = '打印失败！';
                }
                $this->ajaxReturn($res);
            }
        }        
    }
    
    /*
     * 开通支付类型
     */
    public function addPayType(){
        if(IS_POST){
           $params['billRate'] = I('rate');
           switch (I('type')){
               case 'rate[wx_native]':
                   $payTypeId = 10000369;
                   break;
               case 'rate[wx_micropay]':
                   $payTypeId = 10000370;
                   break;
               case 'rate[wx_jspay]':
                   $payTypeId = 10000368;
                   break;
               case 'rate[ali_native]'://扫码支付
                   $payTypeId = 10000168;
                   break;
               case 'rate[ali_micropay]'://刷卡支付
                   $payTypeId = 10000169;
                   break;
               case 'rate[ali_jspay]'://支付宝js支付
                   $payTypeId = 10000167;
                   break;
               case 'rate[wx_app]'://微信app支付
                   $payTypeId = 843;
               default :
                   $payTypeId = -1;
           }
           if(-1 == $payTypeId){
               $rs['status'] = -1;
               $this->ajaxReturn($rs);
               return;
           }
           $params['payTypeId'] = $payTypeId;
           $params['billRate'] = I('rate');
           $user = D('Manage/User')->get(I('userId'));
           $params['merchantId'] = $user['gd_mchId'];           
           //独立配置公众号支付不需要增加partner字段
           $config = D('SX/Configs')->loadConfigs();
           if(isset($config['xy_partner'])){
               $params['partner'] = $config['partner'];
           }
           $m = D('Manage/Xingye');
           $rs = $m->addPayType($params);
           if(isset($rs['isSuccess']) && ('T' == $rs['isSuccess'])){
               $rs['status'] = 1;
               $rs['msg'] = $rs['apiCode'].':开通成功！';
           }else{
               $rs['status'] = -1;
           }
           $this->ajaxReturn($rs);
//            $rs = array('status'=>1);
            
        }
    }
    
    
}