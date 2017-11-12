<?php

namespace Manage\Model;

/**
 * 兴业订单处理
 */
class XyOrderModel extends BaseModel
{
    protected $tableName        =   'order';

    /**
     * 设置为已支付状态
     * @param $oid
     * @return bool
     */
    public function setOrderIsPay($oid)
    {
        return $this->where([
            'order_id'=>$oid
        ])->save([
            'ispay'=>1,
            'paytime'=>time()
        ]);
    }
    /**
     * 获取查询key
     * @param $oid
     * @return mixed
     */
    public function getQueryKeyByOrderId($oid){
        return $this->where([
            'order_id'=>$oid
        ])->getField('bank_query_key');
    }

    /**
     * 添加订单
        out_trade_no uid eid storeid trade_type buyer_logon_id mch_id sub_mch_id
        goods_name goods_describe total_fee time_end openid transaction_id mchtype pmid
     */
    public function addOrder($info)
    {
        if(empty($info))
        {
            return false;
        }
        
        $data = array();
        $data['order_id'] = $info['out_trade_no'];
        $data['uid'] = empty($info['uid']) ? 0 : $info['uid']; //商户UID
        $data['eid'] = empty($info['eid']) ? 0 : $info['eid']; //员工添加则有员工ID
        $data['storeid'] = empty($info['storeid']) ? 0 : $info['storeid']; //有门店添加则有门店ID
        $data['bank_query_key'] = isset($info['bank_query_key'])?$info['bank_query_key'] :'';
        if(strstr($info['trade_type'], "weixin"))
        {
            $data['pay_way'] = "weixin"; //支付平台
            $data['truename'] = "";
        }
        else if(strstr($info['trade_type'], "alipay"))
        {
            $data['pay_way'] = "alipay";
            $data['truename'] = isset($info['buyer_logon_id']) ? $info['buyer_logon_id'] : '';
        }
        else if(strstr($info['trade_type'], "qq")){
            $data['pay_way'] = "qq";
        }
        else
        {
            $data['pay_way'] = "other";
        }

        if(!isset($info['pay_type']))
        {
            if(strstr($info['trade_type'], "micropay"))
            {
                $data['pay_type'] = "MICROPAY"; //支付类型
            }
            else if(strstr($info['trade_type'], "native"))
            {
                $data['pay_type'] = "NATIVE";
            }
            else if(strstr($info['trade_type'], "jspay"))
            {
                $data['pay_type'] = "JSAPI";
            }
            else
            {
                $data['pay_type'] = "OTHER";
            }

        }else{
            $data['pay_type'] = $info['pay_type'];
        }

        $data['goods_type'] = "ordinary"; //商品类型
        $data['mch_id'] = empty($info['mch_id']) ? 0 : $info['mch_id']; //商户ID
        if($info['sub_mch_id'])
        {
            $data['sub_mch_id'] = $info['sub_mch_id']; //子商户ID
        }
        $data['goods_name'] = $info['goods_name']; //商品名称
        $data['goods_describe'] = $info['goods_describe']; //支付方式
        $data['goods_price'] = $info['total_fee']; //价格
        $data['add_time'] = time(); //添加时间
        $data['paytime'] = strtotime($info['time_end']); //付款时间
        $data['state'] = 0;
        $data['ispay'] = empty($info['ispay']) ? 0 : $info['ispay']; //是否支付
        $data['openid'] = empty($info['openid']) ? '' : $info['openid'];
        $data['transaction_id'] = empty($info['transaction_id']) ? '' : $info['transaction_id'];
        $data['refund'] = 0; //退款状态 1退款中 2已退款 3退款失败
        $data['refundtext'] = ""; //退款人员和店铺
        $data['comefrom'] = 0; //0本地 1微信营销 2微店 3O2O系统
        $data['mchtype'] = empty($info['mchtype']) ? 0 : $info['mchtype']; //0普通商户 1特约商户 2平台代收
        $data['pmid'] = empty($info['pmid']) ? 0 : $info['pmid']; //有上级代理则有代理者ID
        $data['p_openid'] = ""; //p_openid对应上级openid

        $obj = D('Manage/Order');
        $res = $obj->add($data);
        if(false !== $res)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * 修改订单状态
     */
    public function editOrderStatus($order_id, $data)
    {
        if(!$order_id)
        {
            return false;
        }

        if(empty($data))
        {
            return false;
        }
        
        $obj = D('Manage/Order');
        $rs = $obj->where(array('order_id' => $order_id))->save($data);
        if($rs !== false)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}