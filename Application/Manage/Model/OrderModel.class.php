<?php
namespace Manage\Model;
class OrderModel extends BaseModel {
     /**
	  * 根据ID查询订单信息
	  */
     public function odetail($id){
	 	$id = $id?$id:0;
		$order = $this->where("id=".$id)->find();
		return $order;
	 }

     /**
	  * 查询当前用户所有订单信息
	  */
     public function getAll($id = 0,$limit = 0,$model=1,$sql){
     	if($model==1){//根据用户ID
			$order = $this->where("uid=".$id.$sql)->order('add_time desc,paytime desc')->limit($limit)->select();
		}else if($model==2){//根据员工ID
			$order = $this->where("eid=".$id.$sql)->order('add_time desc,paytime desc')->limit($limit)->select();
		}else if($model==3){//根据店铺ID
			$order = $this->where("storeid=".$id.$sql)->order('add_time desc,paytime desc')->limit($limit)->select();
		}

		return array_map(function($item){
			$item['pay_time'] = $item['paytime'] > 0 ? date('Y-m-d H:i',$item['paytime']) : '未支付';
			$item['create_time'] = date('Y-m-d H:i',$item['add_time']);
			return $item;
		},$order);
	 }

	/**
	 *
	 * @param $uid
	 * @param $oNumber
	 * @return mixed
	 */
	public function getOrderNumberIsset($uid,$oNumber){
		return $this->where([
			'order_id'=>$oNumber,
			'uid'=>$uid
		])->find();
	}

     /**
	  * 获取当前用户所有订单分页信息
	  */
     public function queryByPage($userId=0,$model=1){
     	if($model==1){
			$sql = "select * from __PREFIX__order where uid=".$userId;
		}else{
			$sql = "select * from __PREFIX__order where eid=".$userId;
		}
	 	$sql .=" order by paytime desc ";
		return $this->pageQuery($sql);
	 }

     /**
	  * 查询代理商所属用户所有订单信息
	  */
     public function getAgentAll($userId = 0,$limit = 0){
             $order = $this->where("pmid=".$userId)->order('paytime desc')->limit($limit)->select();
		return $order;
	 }
         
    public function getAgentOrders($userId = 0, $limit=0, $sql='1'){
            $order = $this->where("pmid=".$userId." and ".$sql)->order('paytime desc')->limit($limit)->select();
		return $order;
    }

    /**
	  * 根据订单号查询订单信息
	  */
     public function get($orderid){
	 	$orderid = $orderid?$orderid:0;
		$order = $this->where("transaction_id like ".$orderid." or order_id like ".$orderid)->find();
		return $order;
	 }

     /**
	  * 查询当前用户某时间段订单
	  */
     public function selectOrders($userId=0,$starttime,$endtime){
	 	$userId = $userId?$userId:I('id',0);
		$rs = $this->where("uid=".$userId." and (paytime >= ".$starttime." and paytime <= ".$endtime.")")->select();
		if(empty($rs))$rs = null;
		return $rs;
	 }
         
    /*
     * 查询当前门店和员工某时间段的订单
     */
    public function selectOrdersByUsid($storeId, $usId, $starttime, $endtime){
        $rs = $this->where("storeid=".$storeId." and eid=".$usId." and ispay=1"." and (paytime >= ".$starttime." and paytime <= ".$endtime.")")->select();
	if(empty($rs))$rs = null;
	return $rs;
    }

    /**
	  * 查询当前用户某年订单
	  */
     public function yearOrders($userId=0,$year,$model="count",$sql="1"){
	 	$userId = $userId?$userId:I('id',0);
	 	if($model == "count"){
	 		$rs = $this->where("uid=".$userId." and year(FROM_UNIXTIME(paytime))=".$year." and ".$sql)->count();
	 		if(empty($rs))$rs = 0;
		}else if($model == "select"){
			$rs = $this->where("uid=".$userId." and year(FROM_UNIXTIME(paytime))=".$year." and ".$sql)->field('goods_price,paytime')->select();
			if(empty($rs))$rs = null;
		}
		return $rs;
	 }

     /**
	  * 查询当前用户某年某月订单
	  */
     public function monthOrders($userId=0,$time,$model="count",$sql="1"){
	 	$userId = $userId?$userId:I('id',0);
	 	if($model == "count"){
	 		$rs = $this->where("uid=".$userId." and date_format(FROM_UNIXTIME(paytime),'%Y-%m')='".$time."' and ".$sql)->count();
	 		if(empty($rs))$rs = 0;
		}else if($model == "select"){
			$rs = $this->where("uid=".$userId." and date_format(FROM_UNIXTIME(paytime),'%Y-%m')='".$time."' and ".$sql)->field('goods_price,paytime')->select();
			if(empty($rs))$rs = null;
		}
		return $rs;
	 }

     /**
	  * 查询当前用户订单数
	  */
     public function countOrders($userId=0,$sql="1"){
	 	$userId = $userId?$userId:I('id',0);
		$rs = $this->where("uid=".$userId." and ".$sql)->count();
		if(empty($rs))$rs = 0;
		return $rs;
	 }

     /**
	  * 查询当前用户收入
	  */
     public function countIncome($id=0,$sql="1",$model=1){
     	if($model==1){//根据用户ID
			$rs = $this->where("uid=".$id." and refund = 0".$sql)->sum('goods_price');
		}else if($model==2){//根据员工ID
			$rs = $this->where("eid=".$id." and refund = 0".$sql)->sum('goods_price');
		}else if($model==3){//根据店铺ID
			$rs = $this->where("storeid=".$id." and refund = 0".$sql)->sum('goods_price');
		}
		if(empty($rs))$rs = 0;
		return $rs;
	 }

     /**
	  * 查询当前用户某年某月收入
	  */
     public function monthIncome($userId=0,$time,$sql="1"){
	 	$userId = $userId?$userId:I('id',0);
		$rs = $this->where("uid=".$userId." and refund = 0 and ispay = 1 and date_format(FROM_UNIXTIME(paytime),'%Y-%m')='".$time."' and ".$sql)->sum('goods_price');
		if(empty($rs))$rs = 0;
		return $rs;
	 }

     /**
	  * 查询当前用户某年某月收入
	  */
     public function dayIncome($userId=0,$time,$sql="1"){
	 	$userId = $userId?$userId:I('id',0);
		$rs = $this->where("uid=".$userId." and refund = 0 and ispay = 1 and date_format(FROM_UNIXTIME(paytime),'%Y-%m-%d')='".$time."' and ".$sql)->sum('goods_price');
		if(empty($rs))$rs = 0;
		return $rs;
	 }
         
    /*
     * 结算管理，查询当前用户某年某月某日的结算数据
     */
    public function toBalance($userId=0, $stime, $etime, $model=1, $extsql='1'){
        $balanceList = array();
        $i = 0;
        while($stime <= $etime){
            $balance = array();
            $balance['balanceTime'] = $stime;//结算时间,T+1结算方式，今天的结算昨天的
            $balance['startTime'] = $stime- 60*60*24;//交易开始时间
            $balance['endTime'] = $stime ;//交易结束事件
            $sql = " and paytime >= ".$balance['startTime']." and paytime <= ".$balance['endTime'];
            if($model == 1){//商户登录
                $balance['totalMoney'] = $this->where("uid=".$userId." and ispay = 1".$sql.$extsql)->sum('goods_price');//交易总金额
                $balance['count'] = $this->where("uid=".$userId." and ispay = 1".$sql.$extsql)->count();//交易笔数
                $balance['refundMoney'] = $this->where("uid=".$userId." and refund != 0".$sql.$extsql)->sum('refund_fee');//退款总金额
                $balance['refundCount'] = $this->where("uid=".$userId." and refund != 0".$sql.$extsql)->count();//退款笔数
            }else{//员工登录
                $balance['totalMoney'] = $this->where("storeid=".$userId." and ispay = 1".$sql.$extsql)->sum('goods_price');//交易总金额
                $balance['count'] = $this->where("storeid=".$userId." and ispay = 1".$sql.$extsql)->count();//交易笔数
                $balance['refundMoney'] = $this->where("storeid=".$userId." and refund != 0".$sql.$extsql)->sum('refund_fee');//退款总金额
                $balance['refundCount'] = $this->where("storeid=".$userId." and refund != 0".$sql.$extsql)->count();//退款笔数
            }
            
            $balance['haspayMoney'] = $balance['totalMoney'] - $balance['refundMoney'];
            $balance['fee'] = $balance['haspayMoney'] * 0.006;//手续费，这里统一为千分之六
            $balance['incomeMoney'] = $balance['haspayMoney'] - $balance['fee'];//划账金额=支付金额-手续费
            $balanceList[$i] = $balance;
            $stime = $stime + 60*60*24;//开始时间更新
            $i++;            
        }
        return $balanceList;
    }

     /**
	  * 添加微信订单信息
	  */
     public function addWxOrder($rs){
		$rd = array('status'=>-1);
		if(!empty($rs)){
			$data = array();
			$attach = explode(",",$rs['attach']);
			$data['order_id'] = $rs['out_trade_no'];
			$data['uid'] = $attach[0]; //商户ID
			$data['eid'] = empty($attach[1])?0:$attach[1]; //员工添加则有员工ID
			$data['storeid'] = empty($attach[2])?0:$attach[2]; //有门店添加则有门店ID
			$data['pay_way'] = "weixin"; //支付平台
			$data['pay_type'] = $rs['trade_type']; //支付类型
			$data['goods_type'] = "ordinary"; //商品类型
			$data['mch_id'] = $rs['mch_id']; //商户ID
			if($rs['sub_mch_id']){
				$data['sub_mch_id'] = $rs['sub_mch_id']; //子商户ID
			}
			$data['goods_name'] = $attach[5]; //商品名称
			$data['goods_describe'] = $attach[6]; //支付方式
			$data['goods_price'] = $rs['total_fee']/100; //价格
			$data['add_time'] = time(); //添加时间
			$data['paytime'] = strtotime($rs['time_end']); //付款时间
			$data['state'] = 0;
			$data['ispay'] = 1; //是否支付
			$data['truename'] = "";
			$data['openid'] = $rs['openid'];
			$data['transaction_id'] = $rs['transaction_id'];
			$data['refund'] = 0; //退款状态 1退款中 2已退款 3退款失败
			$data['refundtext'] = ""; //退款人员和店铺
			$data['comefrom'] = 0; //0本地 1微信营销 2微店 3O2O系统
			$data['mchtype'] = empty($attach[4])?0:$attach[4]; //0普通商户 1特约商户 2平台代收
			$data['pmid'] = empty($attach[3])?0:$attach[3]; //有上级代理则有代理者ID
			$data['p_openid'] = ""; //pmid对应openid

			$rs = $this->add($data);
			if(false !== $rs){
				$rd['status']= 1;
			}
		}
		return $rd;
	 }

     /**
	  * 添加光大订单信息
	  */
     public function addGdOrder($rs){
		$rd = array('status'=>-1);
		if(!empty($rs)){
			$data = array();
			$attach = explode("&",$rs['attach']);
			$attach = explode(",",$attach[2]);
			$data['order_id'] = $rs['out_trade_no'];
			$data['uid'] = $attach[0]; //商户ID
			$data['eid'] = empty($attach[1])?0:$attach[1]; //员工添加则有员工ID
			$data['storeid'] = empty($attach[2])?0:$attach[2]; //有门店添加则有门店ID
			if(strstr($rs['trade_type'],"weixin")){
				$data['pay_way'] = "weixin"; //支付平台
				$data['truename'] = "";
			}else if(strstr($rs['trade_type'],"alipay")){
				$data['pay_way'] = "alipay";
				$data['truename'] = $rs['buyer_logon_id'];
			}else{
				$data['pay_way'] = "other";
			}
 
			if(strstr($rs['trade_type'],"micropay")){
				$data['pay_type'] = "MICROPAY"; //支付类型
			}else if(strstr($rs['trade_type'],"native")){
				$data['pay_type'] = "NATIVE";
			}else if(strstr($rs['trade_type'],"jspay")){
				$data['pay_type'] = "JSAPI";
			}else{
				$data['pay_type'] = "OTHER";
			}		 

			$data['goods_type'] = "ordinary"; //商品类型
			$data['mch_id'] = $rs['mch_id']; //商户ID
			if($rs['sub_mch_id']){
				$data['sub_mch_id'] = $rs['sub_mch_id']; //子商户ID
			}
			$data['goods_name'] = $attach[5]; //商品名称
			$data['goods_describe'] = $attach[6]; //支付方式
			$data['goods_price'] = $rs['total_fee']/100; //价格
			$data['add_time'] = time(); //添加时间
			$data['paytime'] = strtotime($rs['time_end']); //付款时间
			$data['state'] = 0;
			$data['ispay'] = 1; //是否支付
			$data['openid'] = $rs['openid'];
			$data['transaction_id'] = $rs['transaction_id'];
			$data['refund'] = 0; //退款状态 1退款中 2已退款 3退款失败
			$data['refundtext'] = ""; //退款人员和店铺
			$data['comefrom'] = 0; //0本地 1微信营销 2微店 3O2O系统
			$data['mchtype'] = empty($attach[4])?0:$attach[4]; //0普通商户 1特约商户 2平台代收
			$data['pmid'] = empty($attach[3])?0:$attach[3]; //有上级代理则有代理者ID
			$data['p_openid'] = ""; //p_openid对应上级openid

			$rs = $this->add($data);
			if(false !== $rs){
				$rd['status']= 1;
			}
		}
		return $rd;
	 }

     /**
	  * 添加兴业订单信息
	  */
     public function addXyOrder($rs){
		$rd = array('status'=>-1);
		if(!empty($rs)){
			$data = array();
			$attach = explode("&",$rs['attach']);
			$attach = explode(",",$attach[2]);
			$data['order_id'] = $rs['out_trade_no'];
			$data['uid'] = $attach[0]; //商户ID
			$data['eid'] = empty($attach[1])?0:$attach[1]; //员工添加则有员工ID
			$data['storeid'] = empty($attach[2])?0:$attach[2]; //有门店添加则有门店ID
			if(strstr($rs['trade_type'],"weixin")){
				$data['pay_way'] = "weixin"; //支付平台
				$data['truename'] = "";
			}else if(strstr($rs['trade_type'],"alipay")){
				$data['pay_way'] = "alipay";
				$data['truename'] = $rs['buyer_logon_id'];
			}else{
				$data['pay_way'] = "other";
			}
 
			if(strstr($rs['trade_type'],"micropay")){
				$data['pay_type'] = "MICROPAY"; //支付类型
			}else if(strstr($rs['trade_type'],"native")){
				$data['pay_type'] = "NATIVE";
			}else if(strstr($rs['trade_type'],"jspay")){
				$data['pay_type'] = "JSAPI";
			}else{
				$data['pay_type'] = "OTHER";
			}		 

			$data['goods_type'] = "ordinary"; //商品类型
			$data['mch_id'] = $rs['mch_id']; //商户ID
			if($rs['sub_mch_id']){
				$data['sub_mch_id'] = $rs['sub_mch_id']; //子商户ID
			}
			$data['goods_name'] = isset($attach[5]) ? $attach[5] : '商品名称'; //商品名称
			$data['goods_describe'] = isset($attach[6]) ? $attach[6] : '支付方式'; //支付方式
			$data['goods_price'] = $rs['total_fee']/100; //价格
			$data['add_time'] = time(); //添加时间
			$data['paytime'] = strtotime($rs['time_end']); //付款时间
			$data['state'] = 0;
			$data['ispay'] = 1; //是否支付
			$data['openid'] = $rs['openid'];
			$data['transaction_id'] = $rs['transaction_id'];
			$data['refund'] = 0; //退款状态 1退款中 2已退款 3退款失败
			$data['refundtext'] = ""; //退款人员和店铺
			$data['comefrom'] = 0; //0本地 1微信营销 2微店 3O2O系统
			$data['mchtype'] = empty($attach[4])?0:$attach[4]; //0普通商户 1特约商户 2平台代收
			$data['pmid'] = empty($attach[3])?0:$attach[3]; //有上级代理则有代理者ID
			$data['p_openid'] = ""; //p_openid对应上级openid

			//dump($data);
			//exit;
			$rs = $this->add($data);
			if(false !== $rs){
				$rd['status']= 1;
			}
		}
		return $rd;
	 }

     /**
	  * 修改微信退款订单信息
	  */
     public function updateWxOrder($params,$usId,$storeId){
		$rd = array('status'=>-1);
		if(!empty($params)){
			$data = array();
			$data['refund'] = $params['refund']; //退款状态 1退款中 2已退款 3退款失败                                                
			$data['refundtext'] = $usId.";".$storeId.";".time(); //退款人员和店铺
                        if(!empty($params['refund_fee'])){
                            $data['refund_fee'] = $params['refund_fee'];
                        }
                        if(!empty($params['out_refund_no'])){
                            $data['out_refund_no'] = $params['out_refund_no'];
                        }       
                        
			$rs = $this->where("transaction_id like ".$params['transaction_id'])->save($data);
			if(false !== $rs){
				$rd['status']= 1;
			}
		}
		return $rd;
	 }
    /*
     * 更新小票打印状态
     */
         public function updatePrintStatus($orderId,$status=1){
             $rd = array('status'=>-1);
		if(!empty($orderId)){
			$data = array();			
			$data['has_print'] = $status; //退款人员和店铺
			$rs = $this->where("order_id= ".$orderId)->save($data);
			if(false !== $rs){
				$rd['status']= 1;
			}
		}
		return $rd;
         }
     
    /*
     * 更新消息提醒状态
     */
    public function updateSendMsgStatus($orderId,$status=1){
             $rd = array('status'=>-1);
		if(!empty($orderId)){
			$data = array();			
			$data['has_sendMsg'] = $status; //退款人员和店铺
			$rs = $this->where("order_id= ".$orderId)->save($data);
			if(false !== $rs){
				$rd['status']= 1;
			}
		}
		return $rd;
         }
}