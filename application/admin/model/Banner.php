<?php
namespace app\admin\model;
use think\Model;
use traits\model\SoftDelete;
/**
 * 管理员模型
 * @author  iwater
 * @version 2017/8/26
 */
class Banner extends Model{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $readonly = [];//只读字段
    public function select_banner(){
    	$list=$this->order('id asc')->paginate('',false);
		resultToArray($list);
		return $list;
    }
    public function add_banner($data){
    	$result = $this->save($data);
		if($result === false){
			return $this->getError();
		}else{
			return $result;
		}
    }

    public function edit_banner($data){
    	$result = $this->save($data,['id'=>$data['id']]);
		if($result === false){
			return $this->getError();
		}else{
			return $result;
		}
    }
}