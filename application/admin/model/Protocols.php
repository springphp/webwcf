<?php
namespace app\admin\model;
use think\Model;
use traits\model\SoftDelete;

/**
* 
*/
class Protocols extends Model
{
	use SoftDelete;
	protected $deleteTime = 'delete_time';
	protected $readonly = [];
	
	public function find_protocol(){
		$protocol = $this->find();
		return $protocol;
	}

	public function edit_protocol($data){
    	$rs = $this->find_protocol();
    	if($rs['id']>0){
            $data['do_content'] = html_to_str($data['content']);
    		$result = $this->save($data,['id'=>$rs['id']]);
    	}else{
            $data['do_content'] = html_to_str($data['content']);
    		$result = $this->save($data);
    	}
		if($result === false){
			return $this->getError();
		}else{
			return $result;
		}
	}
}