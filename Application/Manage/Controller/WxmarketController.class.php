<?php
namespace Manage\Controller;

class WxmarketController extends BaseController {
    /**
     * 显示微信会员卡界面
     */
    public function wxmemberCard(){
        $this->isLogin();
        $this->display("wxmemberCard");
    }

    /**
     * 显示微信社区界面
     */
    public function business(){
        $this->isLogin();
        $m = D('Manage/Wxbusiness');
        $business = $m->loadBusiness(session('SX_USERS.userId'));
        $businesss = new \Think\Page($business['total'],$business['pageSize']);
        $business['pager'] = $businesss->show();
        $s = D('Manage/Stores');  
        $stores = $s->get(session('SX_USERS.userId'));
        $f = D('Manage/Financial');
        $money = $f->getCurmoney(session('SX_USERS.userId'));

        $this->assign('business',$business);
        $this->assign('stores',$stores);
        $this->assign('money',$money);
        $this->display("business");
    }

    /**
     * 创建微信社区界面
     */
    public function createBusiness(){
        $this->isLogin();
        $s = D('Manage/Stores');  
        $stores = $s->get(session('SX_USERS.userId'));

        $this->assign('stores',$stores);
        $this->display("createBusiness");
    }

    /**
     * 编辑微信社区界面
     */
    public function businessdetail(){
        $this->isLogin();
        $m = D('Manage/Wxbusiness');
        $business = $m->load(I('id'));
        $s = D('Manage/Stores');  
        $stores = $s->get(session('SX_USERS.userId'));

        $this->assign('business',$business);
        $this->assign('stores',$stores);
        $this->display("businessdetail");
    }

    /**
     * 上传微信社区图片
     */
    public function uploadStoreImg(){
        $this->isAjaxLogin();
        $this->ajaxReturn($this->uploadPic(1048576,"business/User/".session('SX_USERS.userId')));
    }

    /**
     * 显示红包营销界面
     */
    public function redpack(){
        $this->isLogin();
        $m = D('Manage/Wxredpack');
        $redpack = $m->loadRedpack(session('SX_USERS.userId'));
        $s = D('Manage/Stores');  
        $stores = $s->get(session('SX_USERS.userId'));
        $f = D('Manage/Financial');
        $money = $f->getCurmoney(session('SX_USERS.userId'));

        $this->assign('redpack',$redpack);
        $this->assign('stores',$stores);
        $this->assign('money',$money);
        $this->display("redpack");
    }

    /**
     * 修改红包开关状态
     */
    public function redpackisOpen(){
        $this->isAjaxLogin();
        $m = D('Manage/Wxredpack');
        $rs = $m->editisOpen(session('SX_USERS.userId'));
        $this->ajaxReturn($rs);
    }

    /**
     * 修改微信社区开关状态
     */
    public function businessisOpen(){
        $this->isAjaxLogin();
        $m = D('Manage/Wxbusiness');
        $rs = $m->editisOpen();
        $this->ajaxReturn($rs);
    }

    /**
     * 开通微信社区
     */
    public function openbusiness(){
        $this->isAjaxLogin();
        $rd = array('status'=>-1);

        $b = D('Manage/Wxbusiness');
        $store = $b->loadStore(I("storeid",0));
        if(!empty($store)){
            $rd['status']= -4;
            $rd['msg']= "当前门店已开通微信社区，请更换门店";
            $this->ajaxReturn($rd);
        }

        $m = D('Manage/Users');
        $configs = $m->get(session('SX_USERS.userId'));

        if($configs['userAudit'] != 1){
            $rd['status']= -3;
            $rd['msg']= "您当前资料未通过微信审核，请先通过审核";
            $rd['tourl'] = U("Manage/Users/userInfo");
            $this->ajaxReturn($rd);
        }

        $m = D('Manage/Financial');
        $money = $m->getCurmoney(session('SX_USERS.userId'));

        if($money['curmoney'] < 500){
            $rd['status']= -2; //充值金额大于可用金额
            $rd['msg']= "您当前可用余额不足500元，请充值！";
            $rd['tourl'] = U("Manage/Money/Payment");
            $this->ajaxReturn($rd);
        }

        $rd['status'] = $m->openbusiness(session('SX_USERS.userId'),500);

        if($rd['status']==1){
            //开通微信社区
            $c = D('Manage/CashierCategory');
            $data = array();
            $data['categories2'] = I("categories2");
            $categoriesId = $c->getName($data['categories2']);
            $data['categoriesId'] = $categoriesId['id'];
            $b->openbusiness($data);

            //添加财务明细
            $f = D('Manage/Findetails');
            $f->addbusDetails(session('SX_USERS.userId'),500);

            //调用接口给推广员提成
            if($configs['tgId'] !=0){
                Vendor('PhalApi.PhalApiClient');
                $client = \PhalApiClient::create()
                        ->withHost('http://api.amacm.com/Public/union/');
                $rs = $client->reset()
                    ->withService('Audit.addPrice')
                    ->withParams('tgId', $configs['tgId'])
                    ->withParams('price', 280)
                    ->withParams('sign', '123')
                    ->withTimeout(3000)
                    ->request();
            }
        }
        
        $this->ajaxReturn($rd);
    }

    /**
     * 修改微信社区信息
     */
    public function updatebusiness(){
        $this->isAjaxLogin();
        $b = D('Manage/Wxbusiness');
        $store = $b->loadStore(I("storeid",0));
        $id = I('id',0);
        if(!empty($store) && $store['id'] != $id){
            $rd['status']= -4;
            $rd['msg']= "当前门店已开通微信社区，请更换门店";
            $this->ajaxReturn($rd);
        }

        $data = array();
        //如果重新选择了门店 则查询类目ID
        if(empty($store)){
            $c = D('Manage/CashierCategory');
            $data['categories2'] = I("categories2");
            $categoriesId = $c->getName($data['categories2']);
            $data['categoriesId'] = $categoriesId['id'];
        }
        $rd = $b->updatebusiness($data);
        $this->ajaxReturn($rd);
    }

    /**
     * 红包余额充值
     */
    public function addRpmoney(){
        $this->isAjaxLogin();
        $rd = array('status'=>-1);

        $addmoney = I('rpnum',0);

        if($addmoney < 10){
            $rd['status']= -2;
            $rd['msg']= "最小充值金额为10元！";
            $this->ajaxReturn($rd);
        }

        $m = D('Manage/Financial');
        $money = $m->getCurmoney(session('SX_USERS.userId'));

        if($money['curmoney'] < $addmoney){
            $rd['status']= -3; //充值金额大于可用金额
            $rd['msg']= "您当前可用余额不足".$addmoney."元，请充值！";
            $rd['tourl'] = U("Manage/Money/Payment");
            $this->ajaxReturn($rd);
        }

        $rd['status'] = $m->addRpmoney(session('SX_USERS.userId'),$addmoney);

        if($rd['status']==1){
            //添加财务明细
            $f = D('Manage/Findetails');
            $f->addrpDetails(session('SX_USERS.userId'),$addmoney);
        }

        $this->ajaxReturn($rd);
    }

    /**
     * 保存红包信息
     */
    public function saveRedpack(){
        $this->isAjaxLogin();
        $rd = array('status'=>-1);
        $base_info = I('base_info');
        $base_info['red_storeId'] = implode(";",I('inputpoiid')); //门店ID

        $m = D('Manage/Financial');
        $money = $m->getCurmoney(session('SX_USERS.userId'));

        if($money['rpmoney'] < 30){
            $rd['status']= -2; //余额小于开启金额
            $rd['msg']= "红包余额不足100，请充值！";
            $this->ajaxReturn($rd);
        }

        if(empty($base_info['send_name'])){
            $rd['msg'] = "商户名称不能为空";
        }else if(empty($base_info['title'])){
            $rd['msg'] = "红包祝福语不能为空";
        }else if(empty($base_info['sub_title'])){
            $rd['msg'] = "活动名称不能为空";
        }else if(empty($base_info['source'])){
            $rd['msg'] = "备注不能为空";
        }else if(empty($base_info['red_storeId'])){
            $rd['msg'] = "请选择至少一个门店";
        }else if($base_info['gd_money'] < 1 || $base_info['gd_money'] >200 || $base_info['l_money'] < 1 || $base_info['l_money'] >200 || $base_info['h_money'] < 1 || $base_info['h_money'] >200){
            $rd['msg'] = "红包金额介于[1.00元，200.00元]之间";
        }else if($base_info['l_money'] > $base_info['h_money']){
            $rd['msg'] = "最低金额不能高于最高金额";
        }else{
            if($base_info['date_type'] == 1){
                $base_info['datestart'] = I('datestart');
                $base_info['dateend'] = I('dateend');
            }
            $m = D('Manage/Wxredpack');
            $rd['status'] = $m->saveRedpack($base_info);
        }
        $this->ajaxReturn($rd);
    }

    /**
     * 创建微信会员卡
     */
    public function createwxmemberCard(){
        $c = D('SX/Configs');
        $configs = $c->loadConfigs();
        $token = $this->getWxToken($configs['wx_token'],$configs['wx_update'],"wx70dc6a94bbc9b0cc","7f1a9e23ccbd9d244d79275d592b3c8a",2,2);//2为保存系统token
        $url = "https://api.weixin.qq.com/card/create?access_token=".$token;
        $post = array();
        $post['card']['card_type'] = "MEMBER_CARD"; //会员卡类型
        //$post['card']['member_card']['background_pic_url'] = "https://mmbiz.qlogo.cn/mmbiz/"; //先调用上传图片接口将背景图上传至CDN，否则报错，卡面设计请遵循微信会员卡自定义背景设计规范,像素大小控制在1000像素*600像素以下
        $post['card']['member_card']['base_info']['logo_url'] = "http://mmbiz.qpic.cn/mmbiz/iaL1LJM1mF9aRKPZ/0"; //卡券的商户logo，建议像素为300*300
        $post['card']['member_card']['base_info']['brand_name'] = "海底捞"; //商户名字,字数上限为12个汉字
        $post['card']['member_card']['base_info']['code_type'] = "CODE_TYPE_TEXT";
        $post['card']['member_card']['base_info']['title'] = "海底捞会员卡"; //卡券名，字数上限为9个汉字(建议涵盖卡券属性、服务及金额)
        $post['card']['member_card']['base_info']['color'] = "Color010"; //券颜色。按色彩规范标注填写Color010-Color100
        $post['card']['member_card']['base_info']['notice'] = "使用时向服务员出示此券"; //卡券使用提醒，字数上限为16个汉字
        $post['card']['member_card']['base_info']['service_phone'] = "020-88888888"; //客服电话
        $post['card']['member_card']['base_info']['description'] = "不可与其他优惠同享"; //卡券使用说明，字数上限为1024个汉字
        $post['card']['member_card']['base_info']['date_info']['type'] = "DATE_TYPE_PERMANENT";
        $post['card']['member_card']['base_info']['sku']['quantity'] = 50000000;
        $post['card']['member_card']['base_info']['get_limit'] = 3;
        $post['card']['member_card']['base_info']['use_custom_code'] = false;
        $post['card']['member_card']['base_info']['can_give_friend'] = true;
        $post['card']['member_card']['base_info']['location_id_list'] = array(123,1231);
        $post['card']['member_card']['base_info']['custom_url_name'] = "立即使用";
        $post['card']['member_card']['base_info']['custom_url'] = "http://weixin.qq.com";
        $post['card']['member_card']['base_info']['custom_url_sub_title'] = "6个汉字tips";
        $post['card']['member_card']['base_info']['promotion_url_name'] = "营销入口1";
        $post['card']['member_card']['base_info']['promotion_url'] = "http://www.qq.com";
        $post['card']['member_card']['supply_bonus'] = true; //显示积分，填写true或false，如填写true，积分相关字段均为必填
        $post['card']['member_card']['supply_balance'] = false; //是否支持储值，填写true或false。如填写true，储值相关字段均为必填
        $post['card']['member_card']['prerogative'] = "test_prerogative"; //会员卡特权说明
        $post['card']['member_card']['auto_activate'] = true; //设置为true时用户领取会员卡后系统自动将其激活，无需调用激活接口
        $post['card']['member_card']['custom_field1']['name_type'] = "FIELD_NAME_TYPE_LEVEL";
        $post['card']['member_card']['custom_field1']['url'] = "http://www.qq.com";
        $post['card']['member_card']['activate_url'] = "http://www.qq.com";
        $post['card']['member_card']['custom_cell1']['name'] = "使用入口2";
        $post['card']['member_card']['custom_cell1']['tips'] = "激活后显示";
        $post['card']['member_card']['custom_cell1']['url'] = "http://www.xxx.com";
        $post['card']['member_card']['bonus_rule']['cost_money_unit'] = 100;
        $post['card']['member_card']['bonus_rule']['increase_bonus'] = 1;
        $post['card']['member_card']['bonus_rule']['max_increase_bonus'] = 200;
        $post['card']['member_card']['bonus_rule']['init_increase_bonus'] = 10;
        $post['card']['member_card']['bonus_rule']['cost_bonus_unit'] = 5;
        $post['card']['member_card']['bonus_rule']['reduce_money'] = 100;
        $post['card']['member_card']['bonus_rule']['least_money_to_use_bonus'] = 1000;
        $post['card']['member_card']['bonus_rule']['max_reduce_bonus'] = 50;
        $post['card']['member_card']['discount'] = 10;
        $result = $this->wx_post($url,$post);
        print_r($result);
    }

    /**
     * 发送红包
     */
    public function sendredpack(){
        $data['userId'] = I('userId',0);
        $data['usId'] = I('usId',0);
        $data['storeId'] = I('storeId',0);
        $data['tprice'] = I('tprice',0);
        $data['date'] = date("Y-m-d");
        $type = -1;

        $c = D('SX/Configs');
        $configs = $c->loadConfigs();

        $url = U("Manage/Wxmarket/sendredpack@".C('SITE_URL'))."?".$this->ToUrlParams($data);
        $data['openid'] = $this->GetOpenid($url,$configs['wx_appId'],$configs['wx_appSecret']);

        $f = D('Manage/Financial');
        $fin = $f->getCurmoney($data['userId']);
        $p = D('Manage/Wxredpack');
        $redpack = $p->loadRedpack($data['userId']);

        if($redpack['redRand']==1){
            $money = rand($redpack['lMoney']*100,$redpack['hMoney']*100);
        }else{
            $money = $redpack['gdMoney']*100;
        }
        $rpmoney = $fin['rpmoney'] * 100;

        if($rpmoney < $money){
            $msg = "商户红包余额不足";
        }elseif($data['tprice'] < $redpack['leastMoney']){
            $msg = "付款金额没有达到活动条件";
        }elseif($redpack['quantity'] <=0){
            $msg = "抱歉，红包已抢完!";
        }elseif ($redpack['dateType'] == 1 && ($data['date'] <= $redpack['dateStart'] || $data['date'] >= $redpack['dateEnd'])) {
            $msg = "抱歉，活动已结束";
        }else{
            //先扣商户红包的钱
            $consumption = $f->rpconsumption($data['userId'],$money);
            if($consumption == -1){
                $msg = "商户红包余额扣款失败";
            }else{
                $post_data = array();
                //判断是否为特约商户，特约商户调用系统微信配置
                $post_data['mch_id'] = $configs['wx_mchId'];
                $post_data['nonce_str'] = $this->getWxNonceStr();
                $post_data['mch_billno'] = $configs['wx_mchId'].date("YmdHis");
                $post_data['wxappid'] = $configs['wx_appId'];
                $post_data['send_name'] = $redpack['sendName']; //商户名称
                $post_data['re_openid'] = $data['openid']; //向谁发红包
                $post_data['total_amount'] = $money; //红包金额 分
                $post_data['total_num'] = 1; //红包发放总人数
                $post_data['wishing'] = $redpack['wishing']; //红包祝福语
                $post_data['client_ip'] = $_SERVER['REMOTE_ADDR'];
                $post_data['act_name'] = $redpack['actName']; //活动名称
                $post_data['remark'] = $redpack['remark']; //备注信息
                //$post_data['msgappid'] = $post_data['appid']; //受理模式才需要填写
                //$post_data['consume_mch_id'] = $post_data['mch_id']; //红包扣钱方ID 默认不填扣子商户
                $apikey = $configs['wx_apiSecret'];
                $sslcert_path = $configs['wx_apiclientCert'];
                $sslkey_path = $configs['wx_apiclientKey'];
                $post_data['sign'] = $this->MakeWxSign($post_data,$apikey);

                $url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack";
                $result = $this->wx_xmlpost($url,$this->ToXml($post_data),true,$sslcert_path,$sslkey_path);
                $rs = $this->FromXml($result);
                if($rs['return_code'] == "FAIL"){
                    //如果失败把商户钱返回回去
                    $consumption = $f->returnMoney($data['userId'],$money);
                    $msg = $rs['return_msg'];
                }else{
                    if($rs['result_code'] == "SUCCESS"){
                        $type = 1;
                        $msg = "";
                        $o = D('Manage/Rporder');
                        $rs['userId'] = $userId;
                        $rs['eid'] = $data['usId'];
                        $rs['storeId'] = $data['storeId'];
                        $rd = $o->addWxRpOrder($rs);
                    }else{
                        //如果失败把商户钱返回回去
                        $consumption = $f->returnMoney($data['userId'],$money);
                        if($rs['err_code']=="NO_AUTH"){
                            $msg = "您的微信账号没有实认证或绑定银行卡，无法领取红包";
                        }else if($rs['err_code']=="NOTENOUGH"){
                            $msg = "红包已经被领完了，请下次再来领取";
                        }else{
                            $msg = $rs['err_code_des'];
                        }
                    }   
                }
            }
        }
        $this->assign('type',$type);
        $this->assign('msg',$msg);
        $this->display("tip");
    }
}