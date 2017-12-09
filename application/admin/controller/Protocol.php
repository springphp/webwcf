<?php
namespace app\admin\controller;
use app\admin\controller\AdminBase;
use think\Db;

/**
* 协议设置
* @author iwater <[email address]>
* @version 2017/08/29 [description]
*/
class Protocol extends AdminBase
{
	public function index(){
		if(request()->isAjax()){
			$data = input();
			extract($data);
			$data = [
				'PROTOCOL_NAME'=>$PROTOCOL_NAME,
				'PROTOCOL_TEL'=>$PROTOCOL_TEL,
				'PROTOCOL_CONTENT'=>$PROTOCOL_CONTENT
			];
			foreach ($data as $key => $value) {
				$arr[]['config_value'] =$value;
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
			$protocol = db('config')->column('config_mark,config_value');
			return view(['protocol'=>$protocol]);	
		}
	}	

	public function server(){
		if(request()->isAjax()){
			$data = input();extract($data);
			$rs = Db::table('kd_config')->where('config_mark = "PROTOCOL_SERVER"')->update(['config_value'=>$PROTOCOL_SERVER]);
			if($rs>0){
				Api()->setApi('url','')->ApiSuccess();
			}else{
				Api()->setApi('msg','修改失败')->setApi('url',0)->ApiSuccess();
			}
		}else{
			$words = db('config')->column('config_mark,config_value');
			return view(['words'=>$words['PROTOCOL_SERVER']]);
		}
	}

	public function secrect(){
		if(request()->isAjax()){
			$data = input();extract($data);
			$field = array_keys($data);
			$rs = Db::table('kd_config')->where('config_mark = "PROTOCOL_SECRECT"')->update(['config_value'=>$PROTOCOL_SECRECT]);
			if($rs>0){
				Api()->setApi('url','')->ApiSuccess();
			}else{
				Api()->setApi('msg','修改失败')->setApi('url',0)->ApiSuccess();
			}
		}else{
			$words = db('config')->column('config_mark,config_value');
			return view(['words'=>$words['PROTOCOL_SECRECT']]);
		}
	}

	/**
	 * 银行卡服务协议
	 * @return [type] [description]
	 */
	public function protocol_bank(){
		if(request()->isAjax()){
			$data = input();extract($data);
			$field = array_keys($data);
			$rs = Db::table('kd_config')->where('config_mark = "PROTOCOL_BANK"')->update(['config_value'=>$PROTOCOL_BANK]);
			if($rs>0){
				Api()->setApi('url','')->ApiSuccess();
			}else{
				Api()->setApi('msg','修改失败')->setApi('url',0)->ApiSuccess();
			}
		}else{
			$words = db('config')->column('config_mark,config_value');
			return view(['words'=>$words['PROTOCOL_BANK']]);
		}
	}

	/**
	 * 风险提议书	 
	 * * @return [type] [description]
	 */
	public function protocol_book(){
		if(request()->isAjax()){
			$data = input();extract($data);
			$field = array_keys($data);
			$rs = Db::table('kd_config')->where('config_mark = "PROTOCOL_BOOk"')->update(['config_value'=>$PROTOCOL_BOOk]);
			if($rs>0){
				Api()->setApi('url','')->ApiSuccess();
			}else{
				Api()->setApi('msg','修改失败')->setApi('url',0)->ApiSuccess();
			}
		}else{
			$words = db('config')->column('config_mark,config_value');
			return view(['words'=>$words['PROTOCOL_BOOk']]);
		}
	}

	/**
	 * 用户使用协议	 
	 * * @return [type] [description]
	 */
	public function protocol_user(){
		if(request()->isAjax()){
			$data = input();extract($data);
			$field = array_keys($data);
			$rs = Db::table('kd_config')->where('config_mark = "PROTOCOL_USER"')->update(['config_value'=>$PROTOCOL_USER]);
			if($rs>0){
				Api()->setApi('url','')->ApiSuccess();
			}else{
				Api()->setApi('msg','修改失败')->setApi('url',0)->ApiSuccess();
			}
		}else{
			$words = db('config')->column('config_mark,config_value');
			return view(['words'=>$words['PROTOCOL_USER']]);
		}
	}
}

