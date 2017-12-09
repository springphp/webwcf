<?php
namespace app\home\model;
use think\Model;
use traits\model\SoftDelete;
/**
* 资金流动表
*/
class Related extends Model
{
	use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $readonly = [];//只读字段

    public function get_related_list($field='*',$where=[],$order='',$page=1,$listRow=10){
    	$relatedinfo = $this->field($field)->where($where)->order($order)->page($page,$listRow)->select();
    	resultToArray($relatedinfo);
    	return $relatedinfo;
    }

    //查询人际关系详情
    public function find_related($field='*',$where=[]){
    	$relatedinfo = $this->field($field)->where($where)->find();
    	resultToArray($relatedinfo);
    	return $relatedinfo;
    }

    public function add_relation($data){
        $result = $this->save($data);
        if($result === false){
            return $this->getError();
        }else{
            return $result;
        }
    }

    public function add_relations($data){
        $result = $this->saveAll($data);
        if($result === false){
            return $this->getError();
        }else{
            return $result;
        }
    }
}