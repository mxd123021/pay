<?php
namespace Manage\Model;
class WithdrawRecordModel extends BaseModel
{
	protected $tableName = 'withdraw_records';
	protected static $status = [
		'请求失败',
		'提现成功',
		'提现失败',
		'结果未明'
	];
	/**
	 * 查询当前用户所有订单信息
	 */
	public function getAll($id = 0,$limit = 0,$sql = ''){
		$list = $this->where("user_id=".$id)->where($sql)->order('add_time desc')->limit($limit)->select();
		$count = $this->where("user_id=".$id)->where($sql)->order('add_time desc')->limit($limit)->field('sum(withdraw_price) as withdraw_price,sum(withdraw_processing_fee) as withdraw_processing_fee')->find();
		$count['withdraw_price'] = round($count['withdraw_price'] / 100,2);
		$count['withdraw_processing_fee'] = round($count['withdraw_processing_fee'] / 100,2);
		$list = array_map(function($item){
			$item = [
				'local_order_number'=>$item['local_order_number'],
				'withdraw_price'=>transformIntPriceToFloat($item['withdraw_price']),
				'withdraw_processing_fee'=>transformIntPriceToFloat($item['withdraw_processing_fee']),
				'withdraw_start_time'=>date('Y-m-d H:i:s',$item['withdraw_start_time']),
				'withdraw_receive_time'=>date('Y-m-d H:i:s',$item['withdraw_receive_time']),
				'status'=>self::$status[$item['status']]
			];
			return $item;
		},$list);
		return [
			'list'=>$list,
			'info'=>$count
		];
	}

	/**
	 * 获取结算金额
	 * @param $price
	 * @param $uid
	 * @return mixed
	 */
	public function getSettlementPrice($price,$uid){
		$rate = D('Manage/Users')->getUserWithDrawRate($uid);
		return round($price * ($rate / 100));
	}

	/**
	 * 添加提现记录
	 * @param $uid
	 * @param $data
	 * @return bool
	 */
	public function addItem($uid,$data){
		$data['add_time'] = time();
		$data['user_id'] = $uid;
		//客户提现手续费
		$data['withdraw_processing_fee'] = $this->getShouxuMoney($data['withdraw_price'],$uid);
		//平台待结算金额
		$data['settlement_price'] = $this->getSettlementPrice($data['withdraw_price'],$uid);
		$data['withdraw_receive_time'] = time();
		$res = (bool)$this->add($data);
		return $res;
	}

	/**
	 * 根据本地订单号更新第三方订单号
	 * @param $oNumber
	 * @param $remoteOrderNumber
	 * @return bool
	 */
	public function updateRemoteOrderNumber($oNumber,$remoteOrderNumber){
		return (bool)$this->where([
			'local_order_number'=>$oNumber
		])->save([
			'remote_order_number'=>$remoteOrderNumber
		]);
	}

	/**
	 * 获取统计信息
	 * @param $uid
	 * @return array
	 */
	public function getToDayCountInfo($uid){
		$startTime = strtotime(date('Y-m-d'));
		$endTime = $startTime + 86399;
		//当天总销售额
		$res = D('Manage/Order')->where([
			'user_id'=>$uid,
			'order_type'=>1,
			'ispay'=>1
		])->field('sum(goods_price) as price')->select();
		//平台设置的利率
		$rate = D('Manage/Users')->getUserWithDrawRate($uid);
		//已提现的金额
		$isWithDrawPrice = $this->getUserIsWithdrawCount($uid);
		$price = intval(round($res[0]['price'] * 100));
		$allMoney = $price - $isWithDrawPrice;
//		$allMoney -= (($price * 0.007) + ($price * 0.0002) + ($price * ($rate / 100)) + 200);
		//可提现的金额
		if($allMoney == 0 || $allMoney < 250){
			$price = 0 ;
		}else{
			$price = round($price * 0.8);
			$price -= (($price * 0.007) + ($price * 0.0002) + ($price * ($rate / 100)) + 200);
		}

		return [
			'all_money'=>$allMoney == 0 ? 0 :round($allMoney / 100,2),
			'price'=>$allMoney == 0 ? 0 :round($price / 100,2),
			'tomorrow_money'=>$allMoney == 0 ? 0 :round(($allMoney - $price) / 100,2),
			'rate'=>$rate
		];
	}

	/**
	 * 获取手续费
	 * @param $price
	 * @param $uid
	 * @return float
	 */
	public function getShouxuMoney($price,$uid){
		$rate = D('Manage/Users')->getUserWithDrawRate($uid);
		return round(($price * 0.007) + ($price * 0.0002) + ($price * ($rate / 100)) + 200);
	}


	/**
	 * 获取用户已提现的金额
	 * @param $uid
	 * @return mixed
	 */
	public function getUserIsWithdrawCount($uid){
		$info = $this->where([
			'user_id'=>$uid,
			'status'=>1
		])->field('(sum(withdraw_price) + sum(withdraw_processing_fee)) as price')->find();
		return $info['price'];
	}
}

























