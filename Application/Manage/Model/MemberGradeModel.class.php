<?php
namespace Manage\Model;
class MemberGradeModel extends BaseModel {
     /**
    * 查询当前用户所有会员等级信息
    */
    public function getAll($userId){
      $grade = $this->where("userId=".$userId)->order('sort desc')->select();
      return $grade;
    }

     /**
    * 查询当前id会员等级信息
    */
    public function get($id){
      $grade = $this->where("id=".$id)->find();
      return $grade;
    }

     /**
	  * 添加会员等级
	  */
     public function gradeAdd(){
      $data = array();
      $data['name'] = I("title");
      $data['userId'] = session('SX_USERS.userId');
      $data['exp'] = I("exp");
      $data['discount'] = I("discount");
      $data['sort'] = I("sort");

      $rs = $this->add($data);
      return $rs;
	 }

     /**
    * 修改会员等级
    */
     public function gradeEdit(){
      $data = array();
      $data['name'] = I("title");
      $data['exp'] = I("exp");
      $data['discount'] = I("discount");
      $data['sort'] = I("sort");

      $rs = $this->where("id=".I("id"))->save($data);
      return $rs;
   }

     /**
    * 删除会员等级
    */
     public function gradeDel(){
      $rs = $this->where("id=".I("id"))->delete();
      return $rs;
   }
}