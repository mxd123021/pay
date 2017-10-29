<?php
namespace Manage\Controller;
class StoresController extends BaseController {
    /**
     * 上传微信门店图片
     */
    public function uploadStoreImg(){
        $this->isAjaxLogin();
        $this->ajaxReturn($this->uploadPic(1048576,"store/User/".session('SX_USERS.userId')));
    }

    /**
     * 根据门店ID获取门店信息
     */
    public function getStore(){
        $rd = array('status'=>-1);
        $this->isAjaxLogin();
        $m = D('Manage/Stores');
        $rd['stores'] = $m->getStore(I('id'));
        if(false !== $rd['stores']){
            $rd['status']= 1;
        }
        $this->ajaxReturn($rd);
    }

    /**
     * 获取微信门店列表
     */
    public function getWxStore(){
        $rd = array('status'=>-2);
        $this->isAjaxLogin();
        $m = D('Manage/Users');
        $configs = $m->get(session('SX_USERS.userId'));
        if(!empty($configs['wx_appId']) && !empty($configs['wx_appSecret'])){
            $token = $this->getWxToken($configs['wx_token'],$configs['wx_update'],$configs['wx_appId'],$configs['wx_appSecret']);
            if($token != -1){
                $post_data = array();
                $post_data ['begin'] = 0;
                $post_data ['limit'] = 50;
                $url = "https://api.weixin.qq.com/cgi-bin/poi/getpoilist?access_token=".$token;
                $business = $this->wx_post($url,$post_data);
                $d = D('Manage/Stores');
                $rd = $d->saveWxStores($business);
            }
        }
        $this->ajaxReturn($rd);
    }

    /**
     * 删除微信门店信息
     */
    public function delWxStore(){
        $this->isAjaxLogin();

        //判断是否开通微信社区
        $b = D('Manage/Wxbusiness');
        $store = $b->loadStore(I('id'));
        if(!empty($store)){
            $rd['status']= -2;
            $rd['errmsg']= "当前门店已开通微信社区，请更换社区门店后再删除";
            $this->ajaxReturn($rd);
        }

        $m = D('Manage/Users');
        $configs = $m->get(session('SX_USERS.userId'));
        $token = $this->getWxToken($configs['wx_token'],$configs['wx_update']);
        $post_data = array();
        $post_data ['poi_id'] = I('poi_id');
        if($post_data ['poi_id'] != 0){
            $url = "https://api.weixin.qq.com/cgi-bin/poi/delpoi?access_token=".$token;
            $tip = $this->wx_post($url,$post_data);
            if($tip['errcode']==0){
                $d = D('Manage/Stores');
                $rd = $d->delWxStore(I('id'));
            }else{
                $rd = array('status'=>-1);
            }
        }else{
            $d = D('Manage/Stores');
            $rd = $d->delWxStore(I('id'));
        }
        $this->ajaxReturn($rd);
    }

    /**
     * 添加微信商户门店信息
     */
    public function addStore(){
        $this->isAjaxLogin();
        $m = D('Manage/Stores');
        $rs = $m->addStore();
        $this->ajaxReturn($rs);
    }

    /**
     * 修改门店微信收银接收状态
     */
    public function editisSend(){
        $this->isAjaxLogin();
        $m = D('Manage/Stores');
        $rs = $m->editisSend();
        $this->ajaxReturn($rs);
    }

    /**
     * 修改门店微信所有员工收银接收状态
     */
    public function editisallSend(){
        $this->isAjaxLogin();
        $m = D('Manage/Stores');
        $rs = $m->editisallSend();
        $this->ajaxReturn($rs);
    }
    
    /**
	 * 修改分店名
	 */
    public function editStoreName(){
        $this->isAjaxLogin();
        $m = D('Manage/Stores');
        $rs = $m->editStoreName();
    	$this->ajaxReturn($rs);
    }
}