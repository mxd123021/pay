<?php
namespace SX\Controller;
use Think\Controller;
class BaseController extends Controller {
    /**
     * 登录操作验证
     */
    public function isLogin(){
    	$s = session('SX_STAFF');
        if(empty($s))$this->redirect("Index/toLogin");
    }

    public function isAjaxLogin(){
        $s = session('SX_STAFF');
        if(empty($s))$this->ajaxReturn(array('status'=>-999,'url'=>'Index/toLogin'));
    }

    /**
     * 跳转权限操作
     */
    public function checkPrivelege($grant){
    	$s = session('SX_STAFF.grant');
    	if(empty($s) || !in_array($grant,$s)){
	        $tip['info'] = "没有权限";
	        $tip['url'] = U("SX/Index/logout");
	        $this->assign('tip',$tip);
	        $this->display("public/tip");
    	}
    }
    public function checkAjaxPrivelege($grant){
        $s = session('SX_STAFF.grant');
        if(empty($s) || !in_array($grant,$s))$this->ajaxReturn(array('status'=>-999,'url'=>'Index/toLogin'));
    }

    /**
     * 上传图片
     */
    public function uploadPic($maxSize=0,$savePath="uploads"){
       $config = array(
                'maxSize'       =>  $maxSize, //上传的文件大小限制 (0-不做限制)
                'exts'          =>  array('jpg','png','gif','jpeg'), //允许上传的文件后缀
                'rootPath'      =>  './Upload/', //保存根路径
                'driver'        =>  'LOCAL', // 文件上传驱动
                'subName'       =>  '',
                'savePath'      =>  I('dir',$savePath)."/", //文件上传（子）目录
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
     * 上传文件
     */
    public function uploadFile($maxSize=0,$exts=array('jpg','png','gif','jpeg','txt'),$savePath="uploads",$rootPath='./Upload/'){
        $config = array(
                'maxSize'       =>  $maxSize, //上传的文件大小限制 (0-不做限制) 字节
                'exts'          =>  $exts, //允许上传的文件后缀
                'savePath'      =>  I('dir',$savePath)."/", //文件上传（子）目录
                'rootPath'      =>  $rootPath, //保存根路径
                'replace'       =>  false, //存在同名文件是否是覆盖，默认为false
                'driver'        =>  'LOCAL', // 文件上传驱动
                'subName'       =>  ''
        );
        $upload = new \Think\Upload($config);
        $rs = $upload->upload($_FILES);
        if(!$rs){
            return array('status'=>-1,'error'=>$upload->getError());
        }else{
            return array('status'=>1,'savepath'=>$rootPath.$rs['file']['savepath'].$rs['file']['savename']);
        }   
    }

    /**
     * 数组转XML
     */
    public function ToXml($values)
    {
        if(!is_array($values) 
            || count($values) <= 0)
        {
            return -1;
        }
        $xml = "<xml>";
        foreach ($values as $key=>$val)
        {
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml; 
    }
    
    public function FromXml($xml)
    {   
        if(!$xml){
            return  -1;
        }
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $this->values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);        
        return $this->values;
    }
}