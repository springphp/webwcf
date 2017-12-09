<?php
namespace app\home\api;
use app\home\api\BaseApi;
use app\admin\model\Message;
use app\home\api\DigterSign;
use think\Request;

/**
* 
*/
class Article extends BaseApi
{
	/**
	 * 获取文章 信息
	 * @param  string $field [description]
	 * @param  [type] $where [description]
	 * @param  [type] $type  [1:借款与还款协议2:常见问题]
	 * @return [type]        [description]
	 */
	public function get_article( $type )
	{
		$code = input('code','2','trim');
		$data = ['url'=>urldo('home/Article/index?type='.$type.'&code='.$code)];

		$filePath = './prototol_pdf/prototol.pdf';
		if( !file_exists( $filePath ) ) {
			$res = file_get_contents( $data['url'] );
			pdf( $res , $filePath );
			$jzq = new DigterSign();
			$applyNo = $jzq->doPostObj();
			if ( $applyNo ){
				$res = $jzq->getDetailLink( $applyNo );
				$fileToPdfPath = './prototol_pdf/prototol'.date('Ymdhis').'.pdf';
				file_put_contents( $fileToPdfPath , $res );
			}
		}

		if( $type == 1 ){
			$filePath = Request::instance()->domain().'/prototol_pdf/prototol.pdf';
			$data['url'] = file_get_contents( $filePath );
		}
		

		$data['url'] = $this->getHttp($data['url']);
		$data['config_value'] = $this->getHttp($data['url']);
		if( $type == 2 ) {
			$data['config_name'] = '关于我们';
		}elseif( $type == 3 ){
			$data['config_name'] = '产品介绍';
		}
		
		Api()->setApi('msg','')->ApiSuccess($data);
	}

	/**
	 * 常见问题 
	 * TODE 由于涉及多条记录，所有是否考虑创建一个表处理该问题
	 * @param   $[where] [description]
	 * @return  data array [description]
	 */
	
	public function get_common_problem()
	{
		$data = input();extract($data);
		$message = new Message();
		$field = 'type,title as name,content';
		$where['type'] = 3;
		$order = 'id asc';
		$page = !empty($page)?$page:1;
		$listRow = !empty($row)?$row:100;

		//多版本控制
		$code = input('code','2','trim');
		
		$common_problem = $message->select_msgs($field,$where,$page,$listRow,$order);
		foreach ($common_problem as $key => &$value) {
			if($value['name']){
				$value['online'] = $message->where("title = '{$value['name']}'")->count('id');
				//多版本控制
				if( $code == '1' ) {
					$value['name'] = str_replace('云客盟', '咔哇贷', $value['name']);
				}
			}
			$value['content'] = html_to_str($value['content']);
			//多版本控制
			if( $code == '1' ) {
				$value['content'] = str_replace('云客盟', '咔哇贷', $value['content']);
			}
			$value['content'] = str_replace('屌丝贷', '咔哇贷', $value['content']);

			$value['friends'][]['name'] = $value['content'];
			unset($value['type']);
			unset($value['content']);
		}
		if(isset($common_problem[0])){
			Api()->setApi('msg','')->ApiSuccess($common_problem);
		}else{
			Api()->setApi('msg','没有符合条件的数据')->ApiError();
		}
	}
	
	
}