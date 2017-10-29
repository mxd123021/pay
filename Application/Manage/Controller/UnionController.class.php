<?php
namespace Manage\Controller;
class UnionController extends BaseController {

    public function passport_encrypt($txt, $key) { 
        srand((double)microtime() * 1000000); 
        $encrypt_key = md5(rand(0, 32000)); 
        $ctr = 0; 
        $tmp = ''; 
        for($i = 0;$i < strlen($txt); $i++) { 
        $ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr; 
        $tmp .= $encrypt_key[$ctr].($txt[$i] ^ $encrypt_key[$ctr++]); 
        } 
        return base64_encode($this->passport_key($tmp, $key)); 
    } 

    function passport_decrypt($txt, $key) { 
        $txt = $this->passport_key(base64_decode($txt), $key); 
        $tmp = ''; 
        for($i = 0;$i < strlen($txt); $i++) { 
        $md5 = $txt[$i]; 
        $tmp .= $txt[++$i] ^ $md5; 
        } 
        return $tmp; 
    } 

    public function passport_key($txt, $encrypt_key) { 
        $encrypt_key = md5($encrypt_key); 
        $ctr = 0; 
        $tmp = ''; 
        for($i = 0; $i < strlen($txt); $i++) { 
        $ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr; 
        $tmp .= $txt[$i] ^ $encrypt_key[$ctr++]; 
        } 
        return $tmp; 
    } 

	/**
	 * 显示商家资料
	 */
    public function userInfo(){
        //解密ID号 http://www.域名.com/Manage/Union/userInfo/tgId/5/token/Afj==
        $tgId = I("tgId",0);
        $token = I("token",0);
        $key = "amacm_crypt".$tgId;
        $userId = $this->passport_decrypt($token,$key);
        $m = D('Manage/Users');
        $userinfo = $m->get($userId);
        if(empty($userinfo)) {echo "error";exit;}
        $this->assign('token',$token);
        $this->assign('userinfo',$userinfo);
        $this->display("userInfo");
    }

    /**
     * 保存商家资料信息
     */
    public function saveUserInfo(){
        $type = I('type',0);
        $tgId = I("tgId",0);
        $token = I('token',0);
        $key = "amacm_crypt".$tgId;
        $userId = $this->passport_decrypt($token,$key);
        if($type == "union" && is_numeric($userId)){
            $m = D('Manage/Users');
            $rs = $m->saveUserInfo($userId);

            if($rs['status']==1){
                $tip['info'] = "商家资料保存成功";
            }else{
                $tip['info'] = "商家资料保存失败";
            }
            $tip['url'] = U("Manage/Union/userInfo",array("tgId"=>$tgId,"token"=>$token));
            $this->assign('tip',$tip);
            $this->display("Public/tip");
        }else{
            echo "error";
            exit;
        }
    }

   /**
     * 上传图片文件
     */
    public function img_Upload(){
        $userId = $this->passport_decrypt($token,$key);
        $QUERY_STRING = $_SERVER['QUERY_STRING'];
        $QUERY_STRING = explode("/",$QUERY_STRING);
        /*Array
        (
            [0] => s=Manage
            [1] => Union
            [2] => img_Upload
            [3] => tgId
            [4] => 58
            [5] => token
            [6] => BWRXOA==
            [7] => type
            [8] => union
            [9] => key
            [10] => 663706.html
        ))*/
        $type = $QUERY_STRING[8];
        $tgId = $QUERY_STRING[4];
        $token = $QUERY_STRING[6];
        $key = "amacm_crypt".$tgId;
        $userId = $this->passport_decrypt($token,$key);
        if($type == "union" && is_numeric($userId)){
            $this->ajaxReturn($this->uploadPic(512000,"info/User/".$userId));
        }
    }
}