<?php
namespace SX\Controller;
class UsersController extends BaseController {
	/**
	 * 显示后台首页
	 */
    public function merLists(){
		$this->isLogin();
		$this->checkPrivelege('user_lists');
		$m = D('SX/Users');
    	$page = $m->queryByPage();
		$pager = new \Think\Page($page['total'],$page['pageSize']);// 实例化分页类 传入总记录数和每页显示的记录数
    	$page['pager'] = $pager->show();
    	$this->assign('Page',$page);
        $this->display("merLists");
    }

    /**
     * 显示实名审核页面
     */
    public function auditrealname(){
        $this->isLogin();
        $this->checkPrivelege('user_lists');
        $type = I('type',2); // 2：审核中 3：审核失败
        $m = D('SX/Users');
        $users = $m->getAudit($type);
        $this->assign('users',$users);
        $this->assign('type',$type);
        $this->display("auditrealname");
    }

    /**
     * 显示实名审核详细页面
     */
    public function auditdetail(){
        $this->isLogin();
        $this->checkPrivelege('user_lists');
	$c = D('SX/Configs');
        $m = D('SX/Users');
        $d = D('Manage/GdDistrict');
        $k = D('Manage/GdBank');
        $i = D('Manage/GdIncategory');

        $configs = $c->loadConfigs();
        $userinfo = $m->get($id);
        $rate = json_decode($userinfo['gd_rate'],true);
                
        $savePath = 'Upload/Users/'.I('id');
                
        $bank = $k->get();
        $district = $d->getProvince();
        $district2 = $d->getCity($userinfo['fid']);
		$incategory = $i->getId($userinfo['incode']);
        $incategory['namearr'] = explode("-",$incategory['name']);
        $incategory['level2'] = $i->get($incategory['namearr'][0]);
        $incategory['level3'] = $i->get($incategory['namearr'][0]."-".$incategory['namearr'][1]);

        $this->assign('savePath', $savePath);
        $this->assign('configs',$configs);
        $this->assign('userinfo',$userinfo);
        $this->assign('rate',$rate);
        $this->assign('district',$district);
        $this->assign('district2',$district2);
        $this->assign('bank',$bank);
        $this->assign('incategory',$incategory);
        $this->display("auditdetail");
    }

    /**
     * 向光大银行进行门店进件
     */
    public function savegdStore(){
        $this->isLogin();
        $id = I('id');
        $m = D('SX/Users');

        $tip['status'] = 0;
        //保存商户结算费率
        $rs = $m->saveRate($id);
        if($rs['status']==1){
            $userinfo = $m->get($id);
            //调用光大门店进件接口
            if(1)
            {
                $rs = D('Manage/Xingye')->jinjian($userinfo);
                if(isset($rs['isSuccess']))
                {
                    if($rs['isSuccess'] == 'T')
                   {
                        $m->setUseraudit($userinfo['userId'], 1, "", 3, $rs['merchant']['merchantId']); //设置商户审核状态，1为审核成功 步骤为3
                        $this->success('商家进件成功', U("Users/auditdetail",array('id'=>$id)));
                    }
                    else
                    {
                        $this->error($rs['errorMsg']);
                    }
                }
                else
                {
                    $this->error("进件接口繁忙，请重新提交");
                }
            }
            else
            {

            }                  
        }else{
            $this->error("结算费率保存失败");
        }
    }
    
    public function savegdStore33(){
        $this->isLogin();
        $id = I('id');
        $m = D('SX/Users');

        $tip['status'] = 0;
        //保存商户结算费率
        $rs = $m->saveRate($id);
        if($rs['status']==1){
        $userinfo = $m->get($id);
            //调用光大门店进件接口

            if(1)
            {
                $rs = D('Manage/Xingye')->jinjian($userinfo);
                if(isset($rs['isSuccess']))
                {
                    if($rs['isSuccess'] == 'T')
                    {
                        $m->setUseraudit($userinfo['userId'], 1, "", 3, $rs['merchant']['merchantId']); //设置商户审核状态，1为审核成功 步骤为3
                        
                        $this->success('商家进件成功', U("Users/auditdetail",array('id'=>$id)));
                    }
                    else
                    {
                        $this->error($rs['errorMsg']);
                    }
                }
                else
                {
                    $this->error("进件接口繁忙，请重新提交");
                }
            }
            else
            {

            }

            

            /*
	        $result = R("Api/ApigdStore",array($userinfo));
	        if($result['respcode'] == "00"){ //00 进件成功
	            $m->setUseraudit($userinfo['userId'],1,"",3,$result['merchantid']); //设置商户审核状态，1为审核成功 步骤为3
	            $tip['info'] = "商家进件成功";
	        }else if($result['respcode'] == "10"){ //10 进件成功,电子账户开通失败
	            $m->setUseraudit($userinfo['userId'],3,"银行信息验证失败,请检查银行信息是否正确",2,$result['merchantid']); //设置商户审核状态，3为审核失败 步骤为2
	            $tip['info'] = "商家进件成功,电子账户开通失败";
	        }else if($result['respcode'] == "05" || $result['respcode'] == "31"){ //05 外商户号重复，调用门店查询接口  31 系统处理中，请稍后调用查询接口确认结果
                $res = R("Api/gd_getQuery",array($userinfo['userId']));
                if(!empty($res['merchantId_1'])){
                    $m->setUseraudit($userinfo['userId'],1,"",3,$res['merchantId_1']);
                    $tip['info'] = "该商户已进件过，自动审核通过";
                }else{
                    $tip['info'] = "查询接口繁忙，请重新提交"; 
                }
            }else{
                if(empty($result['errmsg'])){
                    $tip['info'] = "进件接口繁忙，请重新提交"; 
                }else{
                    $m->setUseraudit($userinfo['userId'],3,$result['errmsg']);
                    $tip['info'] = $result['errmsg'];
                }
	        }
            */
        }else{
            $this->error("结算费率保存失败");
        }
    }

    /**
     * 通过商户
     */
    public function successAudit(){
        $this->isAjaxLogin();
        $this->checkAjaxPrivelege('user_lists');
        $m = D('SX/Users');
        $rs = $m->setUseraudit(I('id'),1);
        $this->ajaxReturn($rs);
    }

    /**
     * 拒绝商户银行信息
     */
    public function rebank(){
        $this->isAjaxLogin();
        $this->checkAjaxPrivelege('user_lists');
        $m = D('SX/Users');
        $rs = $m->setUseraudit(I('id'),3,"请修改银行信息",2);
        $this->ajaxReturn($rs);
    }

    /**
     * 拒绝商户
     */
    public function refused(){
        $this->isAjaxLogin();
        $this->checkAjaxPrivelege('user_lists');
        $m = D('SX/Users');
        $rs = $m->setUseraudit(I('id'),3,I('reson'));
        $this->ajaxReturn($rs);
    }

    /**
     * 修改费率
     */
    public function updaterate(){
        $this->isAjaxLogin();
        $this->checkAjaxPrivelege('user_lists');
        $id = I('id');
        $m = D('SX/Users');
        //保存商户结算费率
        $rs = $m->saveRate($id);
        $this->ajaxReturn($rs);
    }

	/**
	 * 修改用户名
	 */
    public function mdfyName(){
		$this->isAjaxLogin();
		$this->checkAjaxPrivelege('user_lists');
		$m = D('SX/Users');
		$rs = $m->mdfyName(I('uid'));
    	$this->ajaxReturn($rs);
    }

	/**
	 * 设置特约商户
	 */
    public function setIssp(){
		$this->isAjaxLogin();
		$this->checkAjaxPrivelege('user_lists');
		$m = D('SX/Users');
		$rs = $m->setIssp(I('uid'));
    	$this->ajaxReturn($rs);
    }

	/**
	 * 设置受理商户
	 */
    public function setType(){
		$this->isAjaxLogin();
		$this->checkAjaxPrivelege('user_lists');
		$m = D('SX/Users');
		$rs = $m->setType(I('uid'));
    	$this->ajaxReturn($rs);
    }

    /**
     * 直接登陆
     */
    public function logining(){
        $this->isLogin();
        $this->checkPrivelege('user_lists');
        $m = D('Manage/Users');
        $m->logining(I('id'));
        header("Location:".U('Manage/Index/index'));
    }

    /**
     * 商户充值
     */
    public function topup(){
        $this->isAjaxLogin();
        $this->checkAjaxPrivelege('user_lists');
        $m = D('SX/Financial');
        $data = I("data",'','urldecode');
        $rs = $m->addMoney($data['id'],$data['price']);
        if($rs['status'] == 1){
            $c = D('SX/Findetails');
            $c->addadminDetails($data['id'],$data['price']);
        }
        $this->ajaxReturn($rs);
    }
    
    /*
     * 开通支付类型
     */
    public function addPayType(){
//        $rs = array('status'=>1);
//        $rs['msg'] = '访问成功';
//        $this->ajaxReturn($rs);
//        return;
        if(IS_POST){          
            dataRecodes('in addPayType rate=', I('rate'));
            if(I('rate') < 2.5){                
                $rs['status'] = -1;
                $rs['msg'] = '费率不可低于标准费率！';
                $this->ajaxReturn($rs);
                return;               
            }
            dataRecodes('in addPayType $_POST=', $_POST);
            $user = D('Manage/Users')->get(I('userId'));
            $rate = json_decode($user['gd_rate'],true);
            switch (I('type')){
               case 'wx_native':
                   $payTypeId = 10000369;
                   $rate['wx_native'] = I('rate');
                   break;
               case 'wx_micropay':
                   $payTypeId = 10000370;
                   $rate['wx_micropay'] = I('rate');
                   break;
               case 'wx_jspay':
                   $payTypeId = 10000368;
                   $rate['wx_jspay'] = I('rate');
                   break;
               case 'ali_native'://扫码支付
                   $payTypeId = 10000168;
                   $rate['ali_native'] = I('rate');
                   break;
               case 'ali_micropay'://刷卡支付
                   $payTypeId = 10000169;
                   $rate['ali_micropay'] = I('rate');
                   break;
               case 'ali_jspay'://支付宝js支付
                   $payTypeId = 10000167;
                   $rate['ali_jspay'] = I('rate');
                   break;
//               case 'wx_app'://微信app支付
//                   $payTypeId = 843;
               default :
                   $payTypeId = -1;
                   break;
           }
           if(-1 == $payTypeId){
               $rs['status'] = -1;
               $this->ajaxReturn($rs);
               return;
           }
           dataRecodes('in addPayType $rate=', $rate);
           $data = array();
           if(!empty($rate)){$data['gd_rate'] = json_encode($rate);}
           $rt = D('Users')->where("userId = %d",I('userId'))->save($data);
           if(false !== $rt){
                $rs['status'] = 1;
                $rs['msg'] = '费率修改成功！';
            }else{
                $rs['status'] = -1;
                $rs['msg'] = '费率修改失败！';
            }
            dataRecodes('in addPayType rs=', $rs);
            $this->ajaxReturn($rs);     
            return;
//           $params['payTypeId'] = $payTypeId;
//           $params['billRate'] = I('rate');
//           
//           $params['merchantId'] = $user['gd_mchId'];           
//           //独立配置公众号支付不需要增加partner字段
//           if(10000368 == $payTypeId){
//                $config = D('SX/Configs')->loadConfigs();
//                if(isset($config['xy_partner'])){
//                    $params['partner'] = $config['xy_partner'];
//                }
//           }
//           
//           $m = D('Manage/Xingye');                   
//           $rs = $m->addPayType($params);
//           if(isset($rs['isSuccess']) && ('T' == $rs['isSuccess'])){
//               $rs['status'] = 1;
//               $rs['msg'] = $rs['apiCode'].':开通成功！';
//           }else{
//               $rs['status'] = -1;
//               $rs['msg'] = 'errorCode:'.$rs['errorCode'].' errorMsg:'.$rs['errorMsg'];
//           }
//           $this->ajaxReturn($rs);            
        }
    }
}