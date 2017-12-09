<?php
namespace app\admin\model;
use think\Model;
use traits\model\SoftDelete;
/**
 * 支付管理
 * @author   iwater
 * @version  2017/5/13
 */
class Bankcard extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $readonly = [];//只读字段

    public function select_card($data,$where=['type'=>2]){
        
        if(isValue($data,'statr_time') && isValue($data,'end_time')){
            $statr_time= strtotime($data['statr_time']);
            $end_time=strtotime($data['end_time']);
            $where['create_time'] =['BETWEEN',[$statr_time,$end_time]];
        }

    	if(!empty($data['bankcard_num'])){
    		$where['bankcard_num'] = $data['bankcard_num'];
    	}
    	if(!empty($data['bank_code'])){
    		$where['bank_code'] = ['like','%'.(string)$data['bank_code'].'%'];
    	}
        if(!empty($data['account'])){
            $where['account'] = ['like','%'.(string)$data['account'].'%'];
        }
        if(isValue($data,'status')){
            $where['status'] = $data['status'];
        }
		$list=$this->where($where)->order('create_time desc')->paginate('',false,['query' => $data]);
		resultToArray($list);
		return $list;
    }

    public function add_card($data){
    	$result = $this->validate('BankCard.add')->save($data);
		if($result === false){
			return $this->getError();
		}else{
			return $result;
		}
    }

    public function edit_card($data){
    	$result = $this->validate('BankCard.edit')->save($data,['id'=>$data['id']]);
		if($result === false){
			return $this->getError();
		}else{
			return $result;
		}
    }

}