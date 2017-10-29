<?php
namespace SX\Model;
class RolesModel extends BaseModel {
     /**
	  * 查询所有角色权限
	  */
     public function getAll(){
		$roles = $this->select();
		return $roles;
	 }

     /**
	  * 获取当前角色权限
	  */
     public function get($roleId=0){
	 	$roleId = $roleId?$roleId:I('roleId',0);
		$roles = $this->where("roleId=".$roleId)->find();
		return $roles;
	 }

     /**
	  * 添加员工
	  */
     public function rolesAdd(){
        $rd = array('status'=>-1);
        $data = array();
        $data['roleName'] = I('username');
        $data['roleFlag'] = 1;
        $data['createTime'] = date("Y-m-d H:i:s");

        foreach ($data as $v){
            if($v ==''){
                $rd['status'] = -1;
                return $rd;
            }
        }
        $data['grant'] = I("authority");
        $authority = "";
        foreach ($data['grant'] as $value) {
            $authority = $authority.",".$value;
        }
        $data['grant'] = ltrim($authority,",");

        $rs = $this->add($data);
        if(false !== $rs){
            $rd['status']= 1;
        }
        return $rd;
	 }

     /**
	  * 保存编辑角色信息
	  */
     public function rolesAppemd(){
     	$rd = array('status'=>-1);

    	$data = array();
        $data['roleName'] = I('roleName');

    	foreach ($data as $v){
    		if($v ==''){
    			$rd['status'] = -1;
    			return $rd;
    		}
    	}
    	$data['grant'] = I("authority");
    	$authority = "";
		foreach ($data['grant'] as $value) {
			$authority = $authority.",".$value;
		}
		$data['grant'] = ltrim($authority,",");

		$rs = $this->where("roleId = %d",I("roleId"))->save($data);
		if(false !== $rs){
			$rd['status']= 1;
		}

		return $rd;
	 }

     /**
	  * 删除角色
	  */
     public function rolesDel(){
		$rd = array('status'=>-1);
		$rs = $this->where("roleId=".I("roleId"))->delete();
		if(false !== $rs){
			$rd['status']= 1;
			$rd['msg']= "删除成功";
		}else{
			$rd['msg']= "删除失败";
		}
		return $rd;
	 }

    /**
     * 批量删除角色
     */
    public function rolesDelAll(){
		$rd = array('status'=>-1);
  		$where = 'roleId in('.implode(',',I('id')).')';
		$rs = $this->where($where)->delete();

		if(false !== $rs)$rd['status']= 1;
		return $rd;
    }
}