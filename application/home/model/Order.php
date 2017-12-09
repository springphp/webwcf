<?php
namespace app\home\model;
use think\Model;
use traits\model\SoftDelete;
/**
* 资金流动表
*/
class Order extends Model
{
	use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $readonly = [];//只读字段
    
	public function select_order($field ='',$where=[],$order='',$page=1,$listRow=10)
	{
		$result = $this->field($field)->where($where)->order($order)->page($page,$listRow)->select();
		resultToArray($result);
		return $result;
	}

	public function select_orders($field ='',$where=[])
	{
		$result = $this->field($field)->where($where)->find();
		resultToArray($result);
		return $result;
	}

	public function get_order_info($status){
		$where['status'] = $status;
		$where['invest_id'] = session('user.user_id');
		$sum = $this->where($where)->sum('money');
		$count = $this->where($where)->count('id');
		$data = ['sum'=>$sum,'num'=>$count];
		return $data;
	}

	public function add_order($data){
		$result = $this->save($data);
		if($result === false){
			return $this->getError();
		}else{
			return $result;
		}
	}

	public function edit_order($data){
		$result = $this->save($data,['id'=>$data['id']]);
		if($result === false){
			return $this->getError();
		}else{
			return $result;
		}
	}

	public function edit_orders($data){
		$result = $this->saveAll($data);
		if($result === false){
			return $this->getError();
		}else{
			return $result;
		}
	}

	/**
	 * 获取用户总金额是数量
	 * @param  [type] $where [description]
	 * @return [type]        [description]
	 */
	public function get_sum($where = []){
		$count = $this->where($where)->count('invest_id')?:0;
		$sum = $this->sum('money')?:0;
		return ['sum'=>$sum,'count'=>$count];
	}

	/**
	 * 获取用户不同状态下的总金额和数量
	 * @param  integer $status [description]
	 * @param  [type]  $where  [description]
	 * @return [type]          [description]
	 */
	public function get_sums($status=2,$where=[]){
		$where['status'] = $status;
		$count = $this->where($where)->count('invest_id')?:0;
		$sum = $this->where($where)->sum('money')?:0;
		return ['sum'=>$sum,'count'=>$count];
	}

	public function get_total_sum($where){
		$where['status'] = ['in',[2,3,4]];
		$count = $this->where($where)->count('invest_id')?:0;
		$sum = $this->where($where)->sum('money')?:0;
		return ['sum'=>$sum,'count'=>$count];
	}

	public function get_total_sums($where){
		$where['status'] = ['in',[2,3,4]];
		$count = $this->where($where)->count('invest_id')?:0;
		$sum = $this->where($where)->sum('interest')?:0;
		return ['sum'=>$sum,'count'=>$count];
	}
}