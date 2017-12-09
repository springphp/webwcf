<?php
namespace app\home\model;
use think\Model;
use traits\model\SoftDelete;

/**
* 会员模型
*/
class Collection extends Model
{
	use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $readonly = [];//只读字段

	public function select_collection($field='',$where=[],$order,$page=1,$listRow=10){
		$member = $this->field($field)->where($where)->order($order)->page($page,$listRow)->select();
		resultToArray($member);
		return $member;
	}

	public function add_collection($data){
		$result = $this->save($data);
		if($result === false){
			return $this->getError();
		}else{
			return $result;
		}
	}

	public function add_collections($data){
		$result = $this->saveAll($data);
		if($result === false){
			return $this->getError();
		}else{
			return $result;
		}
	}

	public function edit_collection($data){
		$result = $this->save($data,['id'=>$data['id']]);
		if($result === false){
			return $this->getError();
		}else{
			return $result;
		}
	}

}
