<?php
namespace app\admin\model;
use think\Model;
use traits\model\SoftDelete;
/**
 * 会员人际关系模型
 * @author 
 * @version wanggang 2017/8/29
 */
class Order extends Model{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $readonly = [];//只读字段
    //关联member表
    public function borrow(){
    	return $this->hasOne('member','user_id','borrow_id');
    }
    public function invest(){
    	return $this->hasOne('member','user_id','invest_id');
    }
    //关联bankcard表
    public function borrow_bank(){
    	return $this->hasOne('bankcard','user_id','borrow_id');
    }
    public function invest_bank(){
    	return $this->hasOne('bankcard','user_id','borrow_id');
    }

    public function select_order($data,$where=array()){
    	if(isValue($data,'borrow_user')){
			$map['realname'] =['like','%'.(string)$data['borrow_user'].'%'];
			$user_ids        = model('member')->where($map)->column('user_id');
			$where['borrow_id'] = ['in',$user_ids];
		}
		if(isValue($data,'invest_user')){
			$map['realname'] =['like','%'.(string)$data['invest_user'].'%'];
			$user_idss        = model('member')->where($map)->column('user_id');
			$where['invest_id'] = ['in',$user_idss];
		}
		if(isValue($data,'order_no')){
			$where['order_no'] =['like','%'.(string)$data['order_no'].'%'];
		}
		if(isValue($data,'status')){
			$where['status'] =$data['status'];
		}
		if(isValue($data,'is_done')){
			$where['is_done'] =$data['is_done'];
		}
		if(isValue($data,'statr_time') && isValue($data,'end_time')){
            $statr_time= strtotime($data['statr_time']);
            $end_time=strtotime($data['end_time']);
			$where['create_time'] =['BETWEEN',[$statr_time,$end_time]];
		}
		if(isValue($data,'invest_statr_time') && isValue($data,'invest_end_time')){
            $invest_statr_time= strtotime($data['invest_statr_time']);
            $invest_end_time  =strtotime($data['invest_end_time']);
			$where['invest_time'] =['BETWEEN',[$invest_statr_time,$invest_end_time]];
		}
        $query =$data;
		// $where = array_merge( (array)$base, /*$REQUEST,*/ (array)$where);
		$list=$this->where($where)->order('is_done asc')->paginate('',false,['query' => $query]);
		resultToArray($list);
		foreach ($list as $key => $user) {
			//关联姓名
			if($user->borrow){
				$list[$key]['borrow_user'] = $user->borrow->realname;
			}else{
				$list[$key]['borrow_user'] ="--";
			}
			if($user->invest){
				$list[$key]['invest_user'] = $user->invest->realname;
			}else{
				$list[$key]['invest_user'] ="--";
			}
			//帐号
			if($user['borrow_id']){
				$borrow_cnum = model('bankcard')->where('user_id',$user['borrow_id'])->value('bankcard_num');
				if($borrow_cnum){
					$list[$key]['borrow_cnum'] = $borrow_cnum;
				}else{
					$list[$key]['borrow_cnum'] = '--';
				}
			}else{
				$list[$key]['borrow_cnum'] = '---';
			}
			if($user['invest_id']){
				$invest_cnum = model('bankcard')->where('user_id',$user['invest_id'])->value('bankcard_num');
				if($invest_cnum){
					$list[$key]['invest_cnum'] = $invest_cnum;
				}else{
					$list[$key]['invest_cnum'] = '--';
				}
			}else{
				$list[$key]['invest_cnum'] = '---';
			}
			
		}
		return $list;
    }
    //统计用户借款数据
    public function get_borrow_data($user_id){
    	$moneys      = $this->where('borrow_id',$user_id)->column('money');
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
    //统计用户投资数据
    public function get_invest_data($user_id){
    	$moneys      = $this->where('invest_id',$user_id)->column('money');
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
    //统计用户逾期数据
    public function get_overdue_data($user_id){
    	$moneys      = $this->where(['borrow_id'=>$user_id,'is_overdue'=>1])->column('money');
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
    //收益统计 绘图
    public function get_earnings_data(){
        $arr = array();
        for ($i=0; $i <date("j") ; $i++) { 
            $time = strtotime(date('Y').'-'.date('m').'-'.($i+1));
            $end_time = $time+86400;
            $income = model('income')->where(['type'=>['in',[1,2,3,5]],'create_time'=>['BETWEEN',[$time,$end_time]]])->column('money');
            $arr[$i] = array_sum($income);
        }
        return  $arr;
    }
}