<?php
namespace app\home\controller;
use app\common\controller\Base;

/**
 * 文章类页面
 * by weichunfeng 2017/7/10
 */
class Article extends Base
{
	/**
	 * 获取文章 信息
	 * @param  string $field [description]
	 * @param  [type] $where [description]
	 * @param  [type] $type  [1:借款与还款协议 2:关于我们 3:产品介绍 4:服务与隐私协议 5:保密与授权协议 6:银行卡服务协议 7:风险提议书 8:用户使用协议]
	 * @return [type]        [description]
	 */
	public function index()
	{
		$type = input('type',1,'trim');
		// $investid = input('investid',1,'trim');
		$code = input('code','2','trim');

		$field = 'id as idstr,config_name,config_value';
		$where = $this->getwhere($type);
		$articles = db('config')->field($field)->where($where)->find();
		
		if( !$articles ) return false;
		$articles['type'] = $type;
		if( $code == '1' ) {
			wFile( $code ,'./articles.php');
			$articles['config_value'] = str_replace('云客盟', '咔哇贷', $articles['config_value']);
		}
		$articles['config_value'] = str_replace('屌丝贷', '咔哇贷', $articles['config_value']);
		return view(['articles'=>$articles]);
	}	

	final protected function getwhere($type){
		switch ($type) {
			case '1':
				$where['id'] = 101;
				break;
			case '2':
				$where['id'] = 102;
				break;
			case '3':
				$where['id'] = 103;
				break;
			case '4':
				$where['id'] = 105;
				break;
			case '5':
				$where['id'] = 106;
				break;
			case '6':
				$where['id'] = 119;
				break;
			case '7':
				$where['id'] = 120;
				break;
			case '8':
				$where['id'] = 121;
				break;
			default:
				$where['id'] = 101;
				break;
		}
		return $where;
	}

	/**
	 * 投资人查看借款人 借款协议
	 * @return [type] [description]
	 */
	public function protocol_loan(){
		$data = input();
		if( $data['code'] == '1' ){
			return view(['articles'=>$data]);
		}
		return view(['articles'=>$data]);
	}

	/**
	 * 征信详情页面
	 * @return [type] [description]
	 */
	public function zhenxin(){
		return view();
	}

	/**
	 * 显示支付相关页面post提交数据
	 * @return [type] [description]
	 */
	public function ipsPayment(){
		$data = input();
		$data['postUrl'] = config('kwd_app_pay.postUrl');
		return view($data);
	}

	/**
	 * 商户登录后台页面跳转
	 * @return [type] [description]
	 */
	public function login(){
		$data = input();
		$data['postUrl'] = config('kwd_app_pay.login_url');
		return view($data);
	}

}