<?php
namespace app\admin\model;
use think\Model;
use traits\model\SoftDelete;
/**
 * 会员人际关系模型
 * @author 
 * @version wanggang 2017/8/29
 */
class Collection extends Model{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $readonly = [];//只读字段
    //关联order表
    public function order(){
    	return $this->hasOne('order','id','order_id');
    }
    public function select_collection($data,$where=array()){
    	if(isValue($data,'status')){
    		$where['status'] = $data['status'];
    	}
    	if(isValue($data,'statr_time') && isValue($data,'end_time')){
    		$statr_time  = strtotime($data['statr_time']);
            $end_time    =strtotime($data['end_time']);
			$where['create_time'] =['BETWEEN',[$statr_time,$end_time]];
    	}
    	$query =$data;
		$list=$this->where($where)->order('status desc')->paginate('',false,['query' => $query]);
		resultToArray($list);
		foreach ($list as $key => $value) {
			if($value->order){
				$list[$key]['order_info']      = $value->order->data;
				$list[$key]['invest_user']     = model('member')->where('user_id',$value->order->invest_id)->value('realname');
				$list[$key]['borrow_user']     = model('member')->where('user_id',$value->order->borrow_id)->value('realname');
				$list[$key]['borrow_user_tel'] = model('member')->where('user_id',$value->order->borrow_id)->value('mobile');
			}else{
				$list[$key]['order_info']      = array();
				$list[$key]['invest_user']     = '';
				$list[$key]['borrow_user'] 	   = '';
				$list[$key]['borrow_user_tel'] = '';
			}
		}
		return $list;
    }
}