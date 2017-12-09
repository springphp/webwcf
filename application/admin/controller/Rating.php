<?php
namespace app\admin\controller;
use app\admin\controller\AdminBase;
use think\Db;

/**
* 利息设置
* @author  iwater 
* @version 2017/08/29 
*/
class Rating extends AdminBase
{
	
	public function index(){
		if(request()->isAjax()){
			$data = input();
			foreach ($data as $key => $value) {
				$arr[]['config_value'] = $value;
			}
			$num = count($data);
			$keys = array_keys($data);
			$mark = 0;//验证标记
			for($i=0;$i<$num;$i++){
				$k = $where['config_mark'] = $keys[$i];
			    Db::table('kd_config')->where($where)->update($arr[$i]);
				$mark++;
			}
			if($num === $mark){
				Api()->setApi('url','')->ApiSuccess();
			}else{
				Api()->setApi('msg','修改异常')->setApi('url',0)->ApiError();
			}
		}else{
			$rs = db('config')->where(['group'=>'rating'])->whereOr(['group'=>'jrating'])->column('config_mark,config_value');
			return view(['interest'=>$rs]);
		}
	}
}
