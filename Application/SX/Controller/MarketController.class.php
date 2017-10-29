<?php
namespace SX\Controller;
class MarketController extends BaseController {
    /**
     * 显示微信广告界面
     */
    public function wxmarket(){
        $this->isLogin();
        $this->checkPrivelege('market_ad');
        $m = D('Manage/District');
        $a = D('SX/Advertising');
        $this->assign('districts',$m->getDistrict());
        $this->assign('adv',$a->get());
        $this->display("wxmarket");
    }

    /**
     * 显示预约留言界面
     */
    public function rlist(){
        $this->isLogin();
        $this->checkPrivelege('market_re');
        $m = D('SX/Phone');
        $page = $m->getAll();
        $pager = new \Think\Page($page['total'],$page['pageSize']);
        $page['pager'] = $pager->show();
        $this->assign('Page',$page);
        $this->display("rlist");
    }

    /**
     * 保存广告信息
     */
    public function advertising(){
        $this->isAjaxLogin();
        $this->checkPrivelege('money_cz');
        $m = D('SX/Advertising');
        $rd = $m->advertising();
        $this->ajaxReturn($rd);
    }

   /**
     * 上传广告图片
     */
    public function img_Upload(){
        $this->isAjaxLogin();
        $this->ajaxReturn($this->uploadPic(1048576,"market/SX"));
    }
}