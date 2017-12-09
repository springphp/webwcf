<?php
namespace app\admin\model;
use think\Model;
use traits\model\SoftDelete;
/**
 * 收益记录模型
 * @author 
 * @version wanggang 2017/9/4
 */
class UserIncome extends Model{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $readonly = [];//只读字段

    public function member(){
    	return $this->hasOne('member','user_id','user_id');
    }

    //统计用户收益
    public function get_earnings_data($user_id){
    	$moneys      = $this->where(['user_id'=>$user_id,'type'=>['in',[4,5]]])->column('money');
    	if($moneys){
    		$total_money = array_sum($moneys);
    		$count  = count($moneys);
    	}else{
    		$total_money = '--';
    		$count  = '--';
    	}
    	$arr    = ['money'=>$total_money,'count'=>$count];
    	return $arr;
    }
}