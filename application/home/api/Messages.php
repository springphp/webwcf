<?php
namespace app\home\api;
use app\home\api\BaseApi;
use app\admin\model\Message;
/**
* 消息接口
*/
class Messages extends BaseApi
{
	public function get_msg($field='',$where=[],$page,$listRow)
	{
		$message = new Message();
		$re = $message->select_msgs($field,$where,$page,$listRow);
		foreach ($re as $key => &$value) {
			$value['create_time'] = date('Y-m-d',$value['create_time']);
			$value['content'] = html_to_str($value['content']);
		}
		if(isset($re[0])){
			Api()->setApi('url','')->ApiSuccess($re);
		}else{
			Api()->setApi('msg','没有符合条件的数据')->ApiError();
		}
	}
}