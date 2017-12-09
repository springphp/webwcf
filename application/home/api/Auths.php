<?php
namespace app\home\api;
use app\home\api\BaseApi;

/**
* 身份认证
*/
class Auths extends BaseApi
{
	
	public function check_idcard($filepath='')
	{
		//图片上传
		$result = $this->upload_one($filepath);
		dump($result);die;
	}
}

