<?php
namespace app\admin\model;
use think\Model;
use traits\model\SoftDelete;
/**
 * 管理员模型
 * @author  iwater
 * @version 2017/8/26
 */
class Article extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $readonly = [];//只读字段

    public function select_article($type=1){
    	$where['type'] = ['eq',$type];
        return $this->field('article_id,content')->where($where)->find();
    }

    public function get_content_field($type=1){
        $where['type'] = ['eq',$type];
        return $this->where($where)->value('content');
    }

    public function edit_article($data){
    	$type = $data['type'];
    	//验证是否有文章，有就编辑，没有就写入数据
    	$rs = $this->select_article($type);
    	if($rs['article_id']>0){
            $data['do_content'] = html_to_str($data['content']);
    		$result = $this->save($data,['article_id'=>$rs['article_id']]);
    	}else{
            $data['do_content'] = html_to_str($data['content']);
    		$result = $this->save($data);
    	}
		if($result === false){
			return $this->getError();
		}else{
			return $result;
		}
    }
}