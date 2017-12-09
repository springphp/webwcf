<?php
namespace app\home\model;
use think\Model;
use traits\model\SoftDelete;

/**
* 会员模型
*/
class Loginlog extends Model
{
	use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $readonly = [];//只读字段

    public function add_log($data){
    	$result = $this->save($data);
    	if($result === false){
    		return $this->getError();
    	}else{
    		return $result;
    	}
    }
    
    public function edit_log($data){
        $result = $this->save($data,['id'=>$data['id']]);
        if($result === false){
            return $this->getError();
        }else{
            return $result;
        }
    }

    public function del_log($id){
        Loginlog::where("id = {$id}")->delete();
    }
}