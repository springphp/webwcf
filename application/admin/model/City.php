<?php
namespace app\admin\model;
use think\Model;
use traits\model\SoftDelete;
/**
 * 城市模型
 * @author 
 * @version wanggang 2017/8/29
 */
class City extends Model{
    // use SoftDelete;
    // protected $deleteTime = 'delete_time';
    // protected $readonly = [];//只读字段
    public function get_cityInfo($city_id){
    	$city_info = $this->where('city_code',$city_id)->find();
    	if($city_info){
    		$city_info = $city_info->toArray();
    	}
    	return $city_info;
    }
    public function get_cityBypid($pid){
    	$city_info = $this->where('pid',$pid)->find();
    	if($city_info){
    		$city_info = $city_info->toArray();
    	}
    	return $city_info;
    }
}