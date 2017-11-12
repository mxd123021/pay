<?php
namespace SX\Model;

use Arrayzy\ArrayImitator;
use Ramsey\Uuid\Uuid;
use SX\Helper\ShanghaiBankPayHelper;

class RelationMerchantsModel extends BaseModel
{
    use ShanghaiBankPayHelper;
    protected $fillable = [
        'api_type',
        'user_id',
        'name',
        'bank_merchant_number',
        'bank_sign_key',
        'bank_query_key',
        'mobile_number'
    ];

    /**
     * 过滤提交数据
     * @param $data
     * @return array
     */
    public function filterOnlyData($data)
    {
        $item = ArrayImitator::create($data);
        return $item->only($this->fillable)->toArray();
    }

    /**
     * 获取当前用户所有门店信息
     */
    public function get($userId = 0)
    {
        $userId = $userId ? $userId : I('id', 0);
        $user = $this->where("userId=" . $userId)->select();
        return $user;
    }

    /**
     * 获取当前用户所有门店分页信息
     */
    public function queryByPage($userId = 0)
    {
        $userId = $userId ? $userId : 0;
        $sql = "select * from __PREFIX__relation_merchants where user_id=" . $userId;
        $sql .= " order by updated_at desc";
        return $this->pageQuery($sql);
    }

    /**
     * 获取当前门店信息
     */
    public function getStore($storeId = 0)
    {
        $storeId = $storeId ? $storeId : I('id', 0);
        $stores = $this->where("storeId=" . $storeId)->find();
        return $stores;
    }

    /**
     * 检查门店信息是否存在
     */
    public function countStores($userId = 0)
    {
        $userId = $userId ? $userId : I('id', 0);
        $rs = $this->where("userId=" . $userId)->count();
        if (empty($rs)) $rs = 0;
        return $rs;
    }

    /**
     * 删除门店信息
     */
    public function deleteById($id = 0)
    {
        $rd = array('status' => -1);
        $rs = $this->where('id = ' . $id)->delete();
        if (false !== $rs) {
            $rd['status'] = 1;
        }
        return $rd;
    }

    /**
     * 保存从微信获取的商户门店信息
     */
    public function saveWxStores($business)
    {
        $rd = array('status' => -1);
        if (!empty($business)) {
            $data = array();
            foreach ($business['business_list'] as $value) {
                $data['userId'] = session('SX_USERS.userId');
                $data['poi_id'] = $value['base_info']['poi_id'];
                $data['business_name'] = $value['base_info']['business_name'];
                $data['branch_name'] = $value['base_info']['branch_name'];
                $data['address'] = $value['base_info']['address'];
                $data['telephone'] = $value['base_info']['telephone'];
                $categories = explode(",", $value['base_info']['categories'][0]); //美食,江浙菜
                $data['categories'] = $categories[0];
                $data['categories2'] = $categories[1];
                $data['city'] = $value['base_info']['city'];
                $data['province'] = $value['base_info']['province'];
                $data['district'] = $value['base_info']['district'];
                $data['longitude'] = $value['base_info']['longitude'];
                $data['latitude'] = $value['base_info']['latitude'];
                $data['photo_url'] = $value['base_info']['photo_list'][0]['photo_url'];
                $data['introduction'] = $value['base_info']['introduction'];
                $data['special'] = $value['base_info']['special'];
                $data['open_time'] = $value['base_info']['open_time'];
                $data['avg_price'] = $value['base_info']['avg_price'];
                //available_state门店是否可用状态。0 未提交审核、1 表示系统错误、2 表示审核中、3 审核通过、4 审核驳回。当该字段为1、2、4 状态时，poi_id 为空
                $data['available_state'] = $value['base_info']['available_state'];
                //update_status扩展字段是否正在更新中。1 表示扩展字段正在更新中，尚未生效，不允许再次更新； 0 表示扩展字段没有在更新中或更新已生效，可以再次更新
                $data['update_status'] = $value['base_info']['update_status'];

                $is = $this->where("poi_id = %d", $data['poi_id'])->find();
                if (empty($is)) {
                    $rs = $this->add($data);
                } else {
                    $rs = $this->where("poi_id = %d", $data['poi_id'])->save($data);
                }
                if (false !== $rs) {
                    $rd['status'] = 1;
                }
            }
        }
        return $rd;
    }

    /**
     * 添加门店信息
     */
    public function addStore($data)
    {
        $rd = array('status' => -1, 'msg' => '添加失败');
        $data = $this->filterOnlyData($data);
        $data = array_merge($data, [
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'unique_id'=>Uuid::uuid4()->toString()
        ]);
        $rs = $this->add($data);
        if (false !== $rs) {
            $rd['status'] = 1;
            $rd['msg'] = '添加成功';
        }
        return $rd;
    }

    /**
     * 根据唯一id获取item
     * @param $id
     * @return mixed
     */
    public function getItemByUniqueId($id){
        return $this->where([
            'unique_id'=>$id
        ])
            ->field(['bank_query_key','user_id','bank_sign_key','bank_merchant_number'])
            ->find();
    }

    /**
     * 根据商户id随机获取子商户唯一id
     * @param $id
     * @return mixed
     */
    public function getRandomMerchantInfoByUserId($id){
        $item = $this->where([
            'user_id'=>$id
        ])->order('rand()')->field(['unique_id','name'])->find();
        return $item;
    }

    /**
     * 修改门店微信收银接收状态
     */
    public function editisSend()
    {
        $rd = array('status' => -1);
        $data = array();
        $data["isSend"] = I("status");
        $rs = $this->where("storeId = %d", I("storeId"))->save($data);
        if (false !== $rs) {
            $rd['status'] = 1;
        } else {
            $rd['msg'] = "修改失败";
        }
        return $rd;
    }

    /**
     * 修改门店微信所有员工收银接收状态
     */
    public function editisallSend()
    {
        $rd = array('status' => -1);
        $data = array();
        $data["isallSend"] = I("status");
        $rs = $this->where("storeId = %d", I("storeId"))->save($data);
        if (false !== $rs) {
            $rd['status'] = 1;
        } else {
            $rd['msg'] = "修改失败";
        }
        return $rd;
    }

    /**
     * 修改分店名
     */
    public function editStoreName($id, $name)
    {
        $rd = array('status' => -1);
        $data = [
            'name' => $name
        ];
        if ($this->checkEmpty($data, true)) {
            $rs = $this->where("id = %d", $id)->save($data);
            if (false !== $rs) {
                $rd['status'] = 1;
            }
        }
        return $rd;
    }
}