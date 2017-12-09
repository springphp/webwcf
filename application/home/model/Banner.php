<?php
namespace app\home\model;
use think\Model;
use traits\model\SoftDelete;

/**
* 会员模型
*/
class Banner extends Model
{
	use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $readonly = [];//只读字段

	
}