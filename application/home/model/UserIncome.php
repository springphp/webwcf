<?php
namespace app\home\model;
use think\Model;
use traits\model\SoftDelete;

/**
* 资金流动表
*/
class UserIncome extends Model
{
	use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $readonly = [];//只读字段

	public function select_money($field ='',$where=[],$page=1,$listRow=10)
	{
		$result = $this->field($field)->where($where)->order('create_time desc')->page($page,$listRow)->select();
		resultToArray($result);
		return $result;
	}

	public function get_money_sum($type = 4,$where=[]){
		$where['type'] = $type;
		$sum = $this->where($where)->sum('money');
		return $sum;
	}

	public function get_sum($where=[]){
		$where['type'] = ['in',[4,5,6,8]];
		$sum = $this->where($where)->sum('money');
		return $sum;
	}

	public function get_makemoney( $userid ){
		$where = ['user_id'=>$userid,'type'=>4];
		$make_money = model('user_income')->where($where)->sum('money')?:0;
		return $make_money;
	}

	public function add_income($data){
		$result = $this->save($data);
		if($result === false){
			return $this->getError();
		}else{
			return $result;
		}
	}

	public function add_incomes($data){
		$result = $this->saveAll($data);
		if($result === false){
			return $this->getError();
		}else{
			return $result;
		}
	}

	public function get_invest_total_sum($user_id){
		$where['type'] = 4;
		$where['user_id'] = $user_id;
		// $count = $this->where($where)->count('id')?:0;
		$sum = $this->where($where)->sum('money')?:0;
		return $sum;
	}
}