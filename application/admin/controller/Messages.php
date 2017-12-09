<?php
namespace app\admin\controller;
use app\admin\controller\AdminBase;

/**
* 消息管理
* @author  iwater 
* @version 2017/08/29 
*/
class Messages extends AdminBase
{
	public function index(){
		$data = input();
    	_pageconfig(6);
		$message = model('message')->select_msg($data);
		if(empty($message)) Api()->setApi('msg','请求失败')->setApi('url',0)->ApiError();
		foreach ($message as $key => &$value) {
			$value['admin'] = $_SESSION['admin']['user']['account'];
			$value['content'] = html_to_str($value['content']);
		}
		return view(['message'=>$message]);
	}

	public function add()
	{
		if(request()->isAjax()){
			$data = input();
			$re = model('message')->add_msg($data);
			if($re>0){
				Api()->setApi('msg','')->setApi('url','')->ApiSuccess($re);
			}else{
				Api()->setApi('msg',$rs)->setApi('url',0)->ApiError();
			}
		}else{
			return view();
		}
	}
	public function edit()
	{
		if(request()->isAjax()){
			$data = input();
			$re = model('message')->edit_msg($data);
			if($re>0){
				Api()->setApi('msg','')->setApi('url','')->ApiSuccess($re);
			}else{
				Api()->setApi('msg',$rs)->setApi('url',0)->ApiError();
			}
		}else{
			$where['id'] = input('id');
			$message = db('message')->field('id,type,title,content')->where($where)->find();
			return view(['message'=>$message]);
		}
	}
	public function del()
	{
		if(request()->isAjax()){
	        $time = time();
	        $data = input();
	        $obj =$this->setStatus('message',$time,$data['id'],'id','delete_time');
	        if(1 == $obj->code){
	            $obj->setApi('url',input('location'))->apiEcho();
	        }else{
	            $obj->setApi('url',0)->apiEcho();
	        }
        }
	}
}
