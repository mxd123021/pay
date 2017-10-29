<?php
namespace SX\Controller;
class MoneyController extends BaseController {
    /**
     * 显示付款界面
     */
    public function paylist(){
        $this->isLogin();
        $this->checkPrivelege('money_cz');
		$m = D('SX/Czorder');
    	$page = $m->getAll();
		$pager = new \Think\Page($page['total'],$page['pageSize']);// 实例化分页类 传入总记录数和每页显示的记录数
    	$page['pager'] = $pager->show();
    	$this->assign('Page',$page);
        $this->display("paylist");
    }

    /**
     * 显示商户代收提现界面
     */
    public function dsaccount(){
        $this->isLogin();
        $this->checkPrivelege('money_dstx');
        $type = I('type',2); // 1:已提现 2：申请中 3：申请失败
        $m = D('SX/Withdraw');
        $withdraw = $m->getTx($type);
        $this->assign('withdraw',$withdraw);
        $this->assign('type',$type);
        $this->display("dsaccount");
    }

    /**
     * 代收订单详细信息
     */
    public function dsodetail(){
        $this->isLogin();
        $this->checkPrivelege('money_dstx');
        $id = I('id',0);
        $w = D('SX/Withdraw');
        $data['withdraw'] = $w->get($id);
        $u = D('SX/Users');
        $data['userInfo'] = $u->get($data['withdraw']['userId']);
        $b = D('Manage/Bindweixin');
        $data['bind'] = $b->get($data['withdraw']['userId'],"type = 1");
        $starttime = date('Y-m-d',strtotime('-6days'));
        $endtime = date('Y-m-d');
        $this->assign('data',$data);
        $this->assign('starttime',$starttime);
        $this->assign('endtime',$endtime);
        $this->display("dsodetail");
    }

    /**
     * 商户提现
     */
    public function withdraw(){
        $this->isAjaxLogin();
        $this->checkAjaxPrivelege('money_dstx');
        $rd = array('status'=>-1);
        $orderId = I('id',0);
        if(!empty($orderId)){
            $w = D('SX/Withdraw');
            $order = $w->get_2($orderId);
            if(!empty($order['orderid'])){
                $b = D('Manage/Bindweixin');
                $bind = $b->get($order['userId'],"type = 1");
                if(!empty($bind)){
                    $c = D('SX/Configs');
                    $configs= $c->loadConfigs();

                    $post_data = array();
                    $post_data['mch_appid'] = $configs['wx_appId'];
                    $post_data['mchid'] = $configs['wx_mchId'];
                    $post_data ['nonce_str'] = R("Manage/Base/getWxNonceStr");
                    $post_data ['partner_trade_no'] = $post_data['mchid'].date("YmdHis");
                    $post_data ['openid'] = $bind['openid'];
                    $post_data ['check_name'] = "NO_CHECK"; //NO_CHECK：不校验真实姓名 FORCE_CHECK：强校验真实姓名（未实名认证的用户会校验失败，无法转账） OPTION_CHECK：针对已实名认证的用户才校验真实姓名（未实名认证用户不校验，可以转账成功）
                    //$post_data ['re_user_name'] = "代收提现"; //真实姓名
                    $post_data ['amount'] = $order['price'] * 100;
                    $post_data ['desc'] = "代收提现";
                    $post_data ['spbill_create_ip'] = get_client_ip();
                    $sslcert_path = $_SERVER['DOCUMENT_ROOT'].ltrim($configs['wx_apiclientCert'],".");
                    $sslkey_path = $_SERVER['DOCUMENT_ROOT'].ltrim($configs['wx_apiclientKey'],".");
                    $sslca_path = $_SERVER['DOCUMENT_ROOT'].ltrim($configs['wx_apiclientCa'],".");
                    $url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers";
                    $apikey = $configs['wx_apiSecret'];
                    $post_data['sign'] = R("Manage/Base/MakeWxSign",array($post_data,$apikey));
                    $result = R("Manage/Base/wx_xmlpost",array($url,R("Manage/Base/ToXml",array($post_data)),true,$sslcert_path,$sslkey_path,$sslca_path));
                    $rs = $this->FromXml($result);
                    if($rs['return_code'] == "FAIL"){
                        $rd['msg'] = $rs['return_msg'];
                    }else{
                        if($rs['result_code'] == "SUCCESS"){
                            $f = D('Manage/Financial');
                            $f->Withdraw($order['userId'],$order['price']);//扣除提现金额
                            $w->withdrawstatus($order['orderid'],1);//设置提现单号为已提现*/
                            $rd['status'] = 1;
                        }else{
                            $rd['msg'] = empty($rs['err_code_des'])?"私钥文件失效或无授权资格,请检查微信支付配置证书是否正确":$rs['err_code_des'];
                        }
                    }
                }else{
                    $rd['msg'] = "此商户没有绑定微信";
                }
            }else{
                $rd['msg'] = "不存在此提现单号";
            }
        }else{
            $rd['msg'] = "提现单号不能为空";
        }
        $this->ajaxReturn($rd);
    }
}