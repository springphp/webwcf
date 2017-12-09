<?php
namespace app\admin\controller;
use app\admin\controller\AdminBase;
use think\Db;

/**
* 关于我们/产品介绍
*/
class About extends AdminBase
{
	public function index(){
		if(request()->isAjax()){
			$data = input();
			extract($data);
			$datas['config_value'] = $ABOUT_TXT;
			$where['config_mark'] = 'ABOUT_TXT';
			$rs = Db::table('kd_config')->where($where)->update($datas);
			if($rs>0){
				Api()->setApi('url','')->ApiSuccess();
			}else{
				Api()->setApi('msg','修改失败')->setApi('url',0)->ApiSuccess();
			}
		}else{
			$words = db('config')->column('config_mark,config_value');
			return view(['words'=>$words['ABOUT_TXT']]);
		}
	}

	public function product(){
		if(request()->isAjax()){
			$data = input();
			extract($data);
			$datas['config_value'] = $PRODUCT_DES;
			$where['config_mark'] = 'PRODUCT_DES';
			$rs = Db::table('kd_config')->where($where)->update($datas);
			if($rs>0){
				Api()->setApi('url','')->ApiSuccess();
			}else{
				Api()->setApi('msg','修改失败')->setApi('url',0)->ApiSuccess();
			}
		}else{
			$words = db('config')->column('config_mark,config_value');
			return view(['words'=>$words['PRODUCT_DES']]);
		}
	}
	
}