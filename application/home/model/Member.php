<?php
namespace app\home\model;
use think\Model;
use traits\model\SoftDelete;

/**
* 会员模型
*/
class Member extends Model
{
	use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $readonly = [];//只读字段

	public function select_member($field='',$where=[],$page=1,$listRow=10){
		$member = $this->field($field)->where($where)->page($page,$listRow)->select();
		resultToArray($member);
		return $member;
	}

	public function find_member($field='*',$where=[]){
		$member = $this->field($field)->where($where)->find();
		// resultToArray($member);
		return $member;
	}

	public function add_member($data){
		$result = $this->save($data);
		if($result === false){
			return $this->getError();
		}else{
			return $result;
		}
	}

	public function getUser($where=[]){
		$member = $this->where($where)->find();
		return $member;
	}

	public function edit_member($data){
		$result = $this->save($data,['user_id'=>$data['user_id']]);
		if($result === false){
			return $this->getError();
		}else{
			return $result;
		}
	}

	public function edit_members($data){
		$result = $this->saveAll($data);
		if($result === false){
			return $this->getError();
		}else{
			return $result;
		}
	}

}
