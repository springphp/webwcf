<?php
namespace app\admin\model;
use think\Model;
use traits\model\SoftDelete;
/**
 * 收益记录模型
 * @author 
 * @version wanggang 2017/8/29
 */
class Income extends Model{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $readonly = [];//只读字段

    public function member(){
    	return $this->hasOne('member','user_id','user_id');
    }
    public function select_income($data,$where=array()){
    	if(isValue($data,'realname')){
			$map['realname'] =['like','%'.(string)$data['realname'].'%'];
			$user_ids = model('member')->where($map)->column('user_id');
			$where['user_id'] = ['in',$user_ids];
		}
		if(isValue($data,'type')){
			$where['type'] =$data['type'];
		}
		if(isValue($data,'statr_time') && isValue($data,'end_time')){
            $statr_time= strtotime($data['statr_time']);
            $end_time=strtotime($data['end_time']);
			$where['create_time'] =['BETWEEN',[$statr_time,$end_time]];
		}
        $query =$data;
		// $where = array_merge( (array)$base, /*$REQUEST,*/ (array)$where);
		$list=$this->where($where)->order('create_time asc')->paginate('',false,['query' => $query]);
		resultToArray($list);
		foreach ($list as $key => $user) {
			if($user->member){
				$list[$key]['realname'] = $user->member->realname;
				$list[$key]['mobile'] = $user->member->mobile;
			}else{
				$list[$key]['realname'] ="--";
				$list[$key]['mobile'] ="--";
			}
		}
		return $list;
    }
}