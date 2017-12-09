<?php
namespace app\admin\model;
use think\Model;
use traits\model\SoftDelete;
/**
 * 管理员模型
 * @author  iwater
 * @version 2017/8/26
 */
class Message extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $readonly = [];//只读字段
    protected $type = ['','声明','公告','常见问题'];

    public function select_msg($data,$field='',$where=[]){
        if(isValue($data,'statr_time') && isValue($data,'end_time')){
            $statr_time= strtotime($data['statr_time']);
            $end_time=strtotime($data['end_time']);
            $where['create_time'] =['BETWEEN',[$statr_time,$end_time]];
        }

		$list=$this->field($field)->where($where)->order('create_time desc')->paginate('',false,['query' => $data]);
		resultToArray($list);
        foreach ($list as $key => &$value) {
            $value['type'] = $this->type[$value['type']];
        }
		return $list;
    }

    /*api接口*/
    public function select_msgs($field='',$where=[],$page=1,$listRow=10,$order='create_time desc'){
        $list=$this->field($field)->where($where)->order($order)->page($page,$listRow)->select();
        resultToArray($list);
        foreach ($list as $key => &$value) {
            $value['type'] = $this->type[$value['type']];
        }
        return $list;
    }

    public function add_msg($data){
    	$result = $this->save($data);
    	if($result === false){
			return $this->getError();
		}else{
			return $result;
		}
    }

    public function edit_msg($data){
		$result = $this->save($data,['id'=>$data['id']]);
		if($result === false){
			return $this->getError();
		}else{
			return $result;
		}
    }
}