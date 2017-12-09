<?php
namespace app\admin\model;
use think\Model;
use traits\model\SoftDelete;

/**
 * 会员模型
 * @author 
 * @version wanggang 2017/8/29
 */
class Member extends Model{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $readonly = [];//只读字段
    /**
     * 关联admin表
     */
    public function admin(){
    	return $this->hasOne('admin','admin_id','check_user_id');
    }
    /**
     * 关联人际关系related表
     */
    public function related(){
    	return $this->hasOne('related','user_id','user_id');
    }
    /**
     * 关联人际关系related表
     */
    public function bankcard(){
    	return $this->hasOne('bankcard','user_id','user_id');
    }
    
    /**
     * 会员列表查询
     */
    public function select_member($data,$where=array()){
    	if(isValue($data,'realname')){
			$where['realname'] =['like','%'.(string)$data['realname'].'%'];
		}
		if(isValue($data,'mobile')){
			$where['mobile'] =['like','%'.(string)$data['mobile'].'%'];
		}
		if(isValue($data,'idcard')){
			$where['idcard'] =['like','%'.(string)$data['idcard'].'%'];
		}
		if(isValue($data,'account')){
			$map['account'] =['like','%'.(string)$data['account'].'%'];
			$admin_ids        =D('admin')->where($map)->column('admin_id');
			$where['check_user_id'] =['in',$admin_ids];
		}
		if(isValue($data,'is_check')){
			$where['is_check'] =$data['is_check'];
		}
		if(isValue($data,'statr_time') && isValue($data,'end_time')){
            $statr_time= strtotime($data['statr_time']);
            $end_time=strtotime($data['end_time']);
			$where['create_time'] =['BETWEEN',[$statr_time,$end_time]];
		}
		if(isValue($data,'check_statr_time') && isValue($data,'check_end_time')){
            $check_statr_time= strtotime($data['check_statr_time']);
            $check_end_time  =strtotime($data['check_end_time']);
			$where['check_time'] =['BETWEEN',[$check_statr_time,$check_end_time]];
		}
        $query =$data;
		// $where = array_merge( (array)$base, /*$REQUEST,*/ (array)$where);
		$list=$this->where($where)->order('is_check asc')->paginate('',false,['query' => $query]);
		resultToArray($list);
		foreach ($list as $key => $user) {
            //审核人关联
			if($user->admin){
				$list[$key]['check_account'] = $user->admin->account;
			}else{
				$list[$key]['check_account'] ="--";
			}
            //借款统计
            $list[$key]['borrow_data']   = model('Order')->get_borrow_data($user['user_id']);
            //投资统计
            $list[$key]['invest_data']   = model('Order')->get_invest_data($user['user_id']);
            //逾期统计
            $list[$key]['overdue_data']  = model('Order')->get_overdue_data($user['user_id']);
            //收益统计
            $list[$key]['earnings_data'] = model('UserIncome')->get_earnings_data($user['user_id']);
            $city_info                   = model('City')->get_cityInfo($user['province_id']);
            
            $list[$key]['home']          = $city_info['city_name'];
		}
		return $list;
    }
    /**
     * 会员详情
     */
    public function getinfoByid($id){
    	$info = $this->where('user_id',$id)->find();
    	if($info){
    		$info = $info->toArray();
    		// $info['related'] = $info->related;
    		// $info['bankcard'] = $info->bankcard;
    		$related  = model('related')->where('user_id',$id)->select();
    		$bankcard = model('bankcard')->where('user_id',$id)->select();
    		if($related){
                resultToArray($related);
    			$info['related'] = $related;
    		}else{
    			$info['related'] = '';
    		}
    		if($bankcard){
                resultToArray($bankcard);
    			$info['bankcard'] = $bankcard;
    		}else{
    			$info['bankcard'] = '';
    		}
            //投资统计
            $invest = model('order')->where(['invest_id'=>$id])->column('money');
            if($invest){
                $info['total_invest'] = array_sum($invest);
                $info['count_invest'] = count($invest);
                $invest_interest = model('order')->where(['invest_id'=>$id])->column('interest');
                $info['interest_invest'] = array_sum($invest_interest);
                $overdue = model('order')->where(['invest_id'=>$id,'status'=>['in',[4,5]]])->column('overdue_money');
                $info['overdue_invest'] = array_sum($overdue);
            }else{
                $info['total_invest'] = 0;
                $info['count_invest'] = 0;
                $info['interest_invest'] = 0;
                $info['overdue_invest'] = 0;
            }
            //借款统计
            $borrow = model('order')->where(['borrow_id'=>$id])->column('money');
            if($borrow){
                $info['total_borrow'] = array_sum($borrow);
                $info['count_borrow'] = count($borrow);
                $borrow_interest = model('order')->where(['borrow_id'=>$id])->column('interest');
                $info['interest_borrow'] = array_sum($borrow_interest);
                $overdue = model('order')->where(['borrow_id'=>$id,'status'=>['in',[4,5]]])->column('overdue_money');
                $info['overdue_borrow'] = array_sum($overdue);
            }else{
                $info['total_borrow'] = 0;
                $info['count_borrow'] = 0;
                $info['interest_borrow'] = 0;
                $info['overdue_borrow'] = 0;
            }
            //财富统计
            $invest_income   = model('UserIncome')->where(['user_id'=>$id,'type'=>4])->column('money');
            $rewards_r       = model('UserIncome')->where(['user_id'=>$id,'type'=>5])->column('money');
            $rewards_t       = model('UserIncome')->where(['user_id'=>$id,'type'=>8])->column('money');
            $overdue_m       = model('UserIncome')->where(['user_id'=>$id,'type'=>6])->column('money');
            $info['invest_income']   = array_sum($invest_income);
            $info['rewards_r']       = array_sum($rewards_r);
            $info['rewards_t']       = array_sum($rewards_t);
            $info['overdue_m']       = array_sum($overdue_m);
            $info['invest_income_count']   = count($invest_income);
            $info['rewards_r_count']       = count($rewards_r);
            $info['rewards_t_count']       = count($rewards_t);
            $info['overdue_m_count']       = count($overdue_m);
            $info['total_income']          = array_sum($invest_income) + array_sum($rewards_r) + array_sum($overdue_m) + array_sum($rewards_t);
            $info['total_count']           = count($invest_income) + count($rewards_r) + count($overdue_m) + count($rewards_t);
    	}
    	return $info;
    }

}

