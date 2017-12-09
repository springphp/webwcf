<?php
namespace app\home\api;
use app\home\api\BaseApi;

/**
* 
*/
class Protocol extends BaseApi
{
	
	public function aaa(){
		Api()->setApi('msg','this is test')->ApiError();
	}
}