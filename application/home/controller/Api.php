<?php
namespace app\home\controller;
use app\home\api\BaseApi;
use think\Request;
use think\Session;
use think\Cache;


/**
* api 入口文件
*/
class Api extends BaseApi
{
	
	function __construct()
	{
		$this->setApis($this);
		parent::__construct();
	}

	/*---------------接口开始----------------*/
	//接口本地访问地址  192.168.124.41/kawadai/home/api/index/act/login.shtml?username=17603008582&password=123456
	//测试服务器地址如：http://admin.kwcd520.com/home/api/index/act/login

	/*
     * 登录接口
     * @param  string  username(mobile/realname)
     * @param  string  password
     * @return [type] [description]
     */
	protected function login(){
		return D('User','Api')->login(input());
	}

	/*
     * 注册接口
     * @param  string  mobile
     * @param  string  code 验证码
     * @param  string  password
     * @param  string  realname 推荐人(可选参数)
     * @return [type] [description]
     */
	protected function register(){
		return D('User','Api')->register(input());
	}

	//退出登录 无参数 无返回值
	protected function loginout(){
		return D('User','Api')->loginout();
	}

	 /*
     * 修改密码 接口
     * @param  string  mobile
     * @param  string  code 验证码
     * @param  string  password
     * @return [type] [description]
     */
	protected function setpwd(){
		return D('User','Api')->set_pwd();
	}

	/**
     * 发送短信接口
     *@param   $[mobile] [description]
     */
	protected function sendsms(){
		return D('User','Api')->send(input());
	}

	/**
	 * 文章类接口
	 * @param   $[where] [description]
	 * @return  data arrary [description]
	 */
	
	//借款与还款协议
	//[1:借款与还款协议 2:关于我们 3:产品介绍 4:服务与隐私协议 5:保密与授权协议 6:银行卡服务协议 7:风险提议书 8:用户使用协议]
	protected function getarticles(){
		$type = input('type',1,'trim');
		return D('Article','Api')->get_article($type);
	}

	//常见问题 TODE
	protected function getcommoninfo(){
		return D('Article','Api')->get_common_problem();
	}


	/*------------------------------- 借款 -------------------------------*/

	/**
	 * 借款接口
	 * @param   $[name] [description]
	 * @return  data array [description]
	 */
	protected function getloan(){
		$data = input();
		$page = isset($data['page'])?$data['page']:1;
		$row  = isset($data['row'])?$data['row']:10000000000;
		$field = 'id as idstr,borrow_id,money,borroe_time,overdue_money';
		$where['status'] = ['<>',6];
		return D('Loan','Api')->get_loan_list($field,$where,$page,$row);
	}

	/**
	 * 消息接口
	 * @param   $[field,where,data] [data含分页参数]
	 * @return [data] [description]
	 */
	protected function getMsg(){
  
		$data = input();
		$page = isset($data['page'])?$data['page']:1;
		$row  = isset($data['row'])?$data['row']:10;
		$field = 'id as idstr,type,title,content,create_time';
		$where['type'] = ['in',[1,2]];
		return D('Messages','Api')->get_msg($field,$where,$page,$row);
	}


	/**
	 * 借款记录 接口
	 * @param   $[name] [description]
	 * @return  data array [description]
	 */
	protected function getloannote(){
		$data = input();
		$page = isset($data['page'])?$data['page']:1;
		$row  = isset($data['row'])?$data['row']:10;

		$field = 'id as idstr,money,borroe_time,status,invest_id,bankcard_id,invest_time,overdue_money,back_time';
		$where['status'] = ['in',[2,3,4]];
		
		$where['is_done'] = 1;//显示已完成投资的记录

		$where['borrow_id'] =  $this->get_userid( input('user_id','','trim') );
		return D('Loan','Api')->get_loan_list($field,$where,$page,$row);
	}

	/**
	 * 申请还款 接口
	 * @param   $[name] [description]
	 * @return  data array [description]
	 */
	/*protected function backmoney(){
		$data = input();
		if(!isValue($data,'id')) Api()->setApi('msg','借款id不能为空')->ApiError();
		$field = 'id as idstr,money as getmoney,borroe_time,invest_id';
		$where['id'] = $data['id'];
		return D('Loan','Api')->apply_back_money($field,$where);
	}*/

	//确认还款 (借款多少就还款多少)
	protected function isbackmoney(){
		return D('Loan','Api')->is_back_money();
	}

	//已还款
	/*protected function backmoneyinfo(){
		$data = input();
		if(!isValue($data,'id')) Api()->setApi('msg','还款id不能为空')->ApiError();
		$field = 'id as idstr,money,bankcard_id,borroe_time,invest_id,update_time as repayment_time';
		$where['id'] = $data['id'];
		return D('Loan','Api')->back_money($field,$where);
	}*/

	/**
	 * 借款视频 接口
	 * @param   $[user_id] [description]
	 * @return  data array [description]
	 */
	protected function getloanvideo(){
		$data = input();
		$page = isset($data['page'])?$data['page']:1;
		$row  = isset($data['row'])?$data['row']:10;

		$field = 'id as idstr,money,borroe_time,video,overdue_money';
		$where = ['borrow_id'=>$this->get_userid( input('user_id','','trim') )];
		$where['status'] = ['<>',6];
		return D('Loan','Api')->get_loan_list($field,$where,$page,$row);
	}

	/**
	 * 支持银行卡列表
	 * @param   $[name] [description]
	 * @return  data array [description]
	 */
	protected function getcardlist(){
		$field = 'id as idstr,card_name,card_log,bgcolor,card_code';
		return D('Loan','Api')->get_cards($field);
	}

	/**
	 * 获取用户银行卡列表
	 * @param   $[user_id] [用户id /借款人/投资人id不同]
	 * @return  data array [description]
	 */
	protected function getcards(){
		$field = 'id as idstr,account,bank_code,bankcard_num,bgcolor,cardtype,icon,card_code,idcard,mobile';
		$where = [
			'user_id'	=> $this->get_userid( input('user_id','','trim') ),
			'type'		=> 1
		];
		return D('Loan','Api')->get_user_cards($field,$where);
	}

	/**
	 * 添加银行卡
	 * @param   $[bankcard_num] [银行卡号]
	 * @param   $[name] [姓名]
	 * @param   $[bank_code] [银行名称]
	 * @param   $[bank_addr] [开户地址]
	 * @param   $[idcard] [银行卡]
	 * @return  data array [description]
	 */
	protected function addbankcard(){
		$data = input();
		return D('Loan','Api')->add_bankcard($data);
	}

	/**
	 * 我要借款接口
	 * @param   $[name] [description]
	 * @return  data array [description]
	 */
	//我要借款数据显示
	protected function loan(){
		return D('Loan','Api')->get_loan();
	}

	//我要借款--申请借款 接口 post
	protected function addloan(){
		$data = input();
		return D('Loan','Api')->apply_loan($data);
	}

	/**
	 * 推荐认证奖励/5 推荐投资奖励/8 投资收益/4 已结算罚息/7 提现记录/11 
	 * @param [string] $[user_id] [description]
	 * @param [string] $[type] [推荐认证类型 5]
	 * @return  data arrary [获取认证奖励信息]
	 */
	protected function getincomes(){
		$data = input();
		if(!isValue($data,'type')) $data['type'] = 4;
		$page = isset($data['page'])?$data['page']:1;
		$row  = isset($data['row'])?$data['row']:10;

		$field = 'id as idstr,user_id,type,money,create_time';
		$where = ['type'=>$data['type'],'user_id'=>$this->get_userid( input('user_id','','trim') )];

		return D('Loan','Api')->get_income($field,$where,$page,$row);
	}

	/**
	 * 我的财富
	 * @param [type] $[name] [description]
	 * @return  data array [description]
	 */
	protected function getwealth(){
		$data = input();
		return D('Loan','Api')->get_wealth($data);
	}

	//我的财富--取消借款
	protected function breakloan(){
		$data = input();
		return D('Loan','Api')->break_loan($data);
	}

	/**
	 * 财富记录
	 * @param [type] $[name] [description]
	 * @return  data array [description]
	 */
	protected function getwealthlist(){
		$data = input();
		$page = isset($data['page'])?$data['page']:1;
		$row  = isset($data['row'])?$data['row']:10;

		$field = 'id as idstr,type,user_id,money,create_time';
		$where['type'] = ['in',[2,5,10]];
		$where['user_id'] = $this->get_userid( input('user_id','','trim') );

		return D('Loan','Api')->get_wealth_list($field,$where,$page,$row);
	}

	/**
	 * 立即提现
	 * @param [type] $[name] [description]
	 * @return data array [description]
	 */
	protected function getcash(){
		return D('Loan','Api')->get_cash();
	}

	/*---------- 发现 ----------*/

	/**
	 * 申请投资人 --->验证是否是投资人，不是则让他成为投资人
	 *@param [type] $[name] [description]
	 * @return [type] [description]
	 */
	protected function checkinvestment(){
		return D('Investment','Api')->is_investment();
	}

	/**
	 * 邀请赚钱
	 *@param [type] $[name] [description]
	 * @return [type] [description]
	 */
	protected function applymember(){
		return D('Investment','Api')->apply_member(input());
	}

	/**
	 * 常见问题
	 * 公共模块已有此接口，相同共用即可
	 */

	/**
	 * 联系客服
	 *@param [type] $[name] [description]
	 * @return [type] [description]
	 */
	protected function callserver(){
		return D('Investment','Api')->call_servers();
	}

	/*----------- 认证 -----------*/

	/**
	 * 认证中心 ispass
	 * @param [string] $[img] [description]
	 * @return  status [description]
	 */
	// protected function checkcenter(){
	// 	return D('User','Api')->check_center(input());
	// }

	/**
	 * 身份认证
	 * @param [string] $[img] [description]
	 * @return  status [description]
	 */
	protected function checkuser(){
		return D('User','Api')->check_user();
	}

	/**
	 * 人际信息
	 * @param [string] $[img] [description]
	 * @return  status [description]
	 */
	protected function checkrelation(){
		return D('User','Api')->check_relation(input());
	}

	/**
	 * 手机认证
	 * @param [string] $[img] [description]
	 * @return  status [description]
	 */
	protected function checkmobile(){
		return D('User','Api')->check_mobile();
	}


	/*--------------- 投资人 ---------------*/

	/**
	 * 我的 投资总收益 接口
	 * @param   $[name] [description]
	 * @return data array [description]
	 */
	protected function getinterestments(){
		return D('Investment','Api')->get_interestments();
	}

	/**
	 * 放款列表
	 * @param   $[name] [description]
	 * @return data array [description]
	 */
	protected function makeloanlist(){
		$data = input();
		$invest_id = $this->get_userid( input('user_id','','trim') );
		$role = model('member')->where("user_id",$invest_id)->value('role');
		if($role !=1 || empty($role)) Api()->setApi('msg','投资人不存在')->ApiError();

		$page = isset($data['page'])?$data['page']:1;
		$row  = isset($data['row'])?$data['row']:10;
		
		$field = 'id as idstr,term,money,mark_time as invest_time';
		$where = ['is_done'=>2,'status'=>1,'invest_id'=>$invest_id,'checked'=>2];
		$order = 'invest_time desc';

		return D('Investment','Api')->make_loan($field,$where,$order,$page,$row);
	}

	

	/**
	 * 待还款列表status/2  已还款列表status/4  已逾期列表status/3
	 * @param   $[name] [description]
	 * @return data array [description]
	 */
	protected function getloanlist(){ 
		$data = input();
		$status = !empty($data['status'])?$data['status']:3;
		if(!in_array($status,[2,3,4])) Api()->setApi('msg','投资状态不存在')->ApiError();

		$page = isset($data['page'])?$data['page']:1;
		$row  = isset($data['row'])?$data['row']:10;

		$field = 'id as idstr,borrow_id,term,money,borroe_time,overday as over_day,interest,overdue_money';
		$where = ['status'=>$status,'is_done'=>1];
		if($status == 3) $where['is_overdue'] = 1;
		$order = 'borroe_time desc';
		
		return D('Investment','Api')->make_loan_list($field,$where,$order,$page,$row);
	}

	/**
	 * 催收--逾期列表
	 * @param   $[name] [description]
	 * @return data array [description]
	 */
	protected function getoverlist(){ 
		$data = input();
		if(isValue($data,'overday')) $where['overday'] = $data['overday'];
		$invest_id = $this->get_userid( input('user_id','','trim') );
		// dump($invest_id);die;
		$page = isset($data['page'])?$data['page']:1;
		$row  = isset($data['row'])?$data['row']:10;

		$field = 'id as idstr,order_no,cui_status,cui_command_status as cuishouling_status,term,money,borrow_id,
		borrow_id as cuishou,borroe_time,borroe_time as overtime,overdue_money,interest,fee,overday';
		$where = ['is_overdue'=>1,'invest_id'=>$invest_id,'status'=>3];
		//按逾期天数查询
		if(isValue($data,'overday')){
			$where['overday'] = $data['overday'];
		}
		$order = 'borroe_time desc';

		return D('Investment','Api')->make_loan($field,$where,$order,$page,$row);
	}


	/**
	 * 我要催收 逾期催收
	 * @param   $[name] [description]
	 * @return data array [description]
	 */
	protected function getoverdue(){
		$data = input();
		return D('Investment','Api')->get_overdue($data);
	}

	/**
	 * 催收 
	 * 发送短信通知借款人+生成催收令
	 * @param   $[user_id] [借款人id]
	 * @param   $[type] [1:催款2:为催收令]
	 * @return data array [description]
	 */
	protected function callovermember(){
		$data = input();
		return D('Investment','Api')->sendsms_to_borrower($data);
	}

	/**
	 * 我的 催收令列表
	 * @param   $[name] [description]
	 * @return data array [description]
	 */
	protected function getovercommandlist(){
		$data = input();
		$page = isset($data['page'])?$data['page']:1;
		$row  = isset($data['row'])?$data['row']:10;

		$field = 'id as idstr,order_id,rewards,status,create_time';
		$user_id = $this->get_userid( input('user_id','','trim') );
		$orderId = model('order')->where('invest_id',$user_id)->column('id');
		$where['order_id'] = ['in',$orderId];
		$order = "create_time desc";
		return D('Investment','Api')->get_overcommand_list($field,$where,$order,$page,$row);
	}

	/**
	 * 我的账单
	 * @param [type] $[name] [description]
	 * @return  data array [description]
	 */
	protected function getbill(){
		$data = input();
		return D('Investment','Api')->get_bill_list($data);
	}

	//退款记录
	protected function backmoneynote(){
		$data = input();
		//过滤条件
		if(!empty($data['create_time'])){
			$start_time 	  = strtotime($data['create_time']);
			$end_time 	  	  = strtotime($data['create_time'])+24*3600;
			$where['create_time'] = ['BETWEEN',[$start_time,$end_time]];
		}
		$page = isset($data['page'])?$data['page']:1;
		$row  = isset($data['row'])?$data['row']:10;

		$field = 'id as idstr,borroe_time,term as over_day,money,borrow_id,interest,exit_time';
		$where['status'] = 6; 
		return D('Investment','Api')->back_money_note($field,$where,$page,$row);
	}


	//投资+筛选
	protected function toinvest(){
		$data = input();
		$page = isset($data['page'])?$data['page']:1;
		$row  = isset($data['row'])?$data['row']:10000000000;

		$user_id = $this->get_userid( input('user_id','','trim') );
		//筛选 排序
		if(isValue($data,'orders') && $data['orders'] == 1){
			$order = 'borroe_time asc';
		}else{
			$order = 'borroe_time desc'; //倒叙
		}
		
		//筛选 期限
		if(isValue($data,'term')){
			if($data['term'] ==1){
				$where['term'] = ['BETWEEN',[15,20]];
			}elseif($data['term'] ==2){
				$where['term'] = ['BETWEEN',[21,30]];
			}
		}
		//筛选  借款金额
		if(isValue($data,'money')){
			if($data['money'] ==1){
				$where['money'] = ['BETWEEN',[500,10000]];
			}elseif($data['money'] ==2){
				$where['money'] = ['BETWEEN',[10000,20000]];
			}elseif($data['money'] ==3){
				$where['money'] = ['BETWEEN',[20000,50000]];
			}
		}
		//筛选  性别
		if(isValue($data,'sex')){
			if( $data['sex'] != '0' ) {
				$map = [
					'user_id'	=> ['<>',$user_id],
					'sex'		=> $data['sex']
				];
				$user_ids = db('member')->where($map)->column('user_id');
				if( !$user_ids ) Api()->setApi('msg','没有符合条件的数据')->ApiError();
				$where['borrow_id'] = ['in',$user_ids,'<>',$user_id]; //限制投资人看到自己的借款记录
			}
		}
		//筛选  地区
		if(isValue($data,'area_id')){
			if( $data['area_id'] != '0' ) {
				if(!is_array($data['area_id'])) $data['area_id'] = (array)[$data['area_id']];
				$map = [
					'user_id'			=> ['<>',$user_id],
					'province_id'		=> ['in',$data['area_id']]
				];
				$user_ids = db('member')->where($map)->column('user_id');
				if( !$user_ids ) Api()->setApi('msg','没有符合条件的数据')->ApiError();

				if( empty($where['borrow_id']) ) {
					$where['borrow_id'] = ['in',$user_ids,'<>',$user_id]; //限制投资人看到自己的借款记录
					
				}else{
					$where['borrow_id'] = array_merge($where['borrow_id'],['in',$user_ids]); //限制投资人看到自己的借款记录
				}
			}
		}
		// dump( $where );
		$field = 'id as idstr,borrow_id as userid,term,money,interest,checked,overdue_money';
		$where['status'] = ['in',[0,1]];
		// $where['check_man'] = $user_id;
		if( empty($where['borrow_id']) ){
			$where['borrow_id'] = ['<>',$user_id];
		}
		// dump($where);die;
		return D('Investment','Api')->make_loan($field,$where,$order,$page,$row);
	}

	/**
	 * 支付 投资--我要放款 接口
	 * @param [type] $[name] [description]
	 * @return data array [description]
	 */
	
	protected function topay(){
		$data = input();
		return D('Investment','Api')->to_pay($data);
	}

	protected function addinvest(){
		$data = input();
		return D('Investment','Api')->add_invest($data);
	}

	//查询省数据
	protected function getprovince(){
		$data = input();
		$page = isset($data['page'])?$data['page']:1;
		$row  = isset($data['row'])?$data['row']:50;

		$field = 'city_id,city_code,city_name';
		$where['pid'] = 1;
		$order = 'city_id asc';
		return D('Investment','Api')->get_province($field,$where,$order,$page,$row);
	}

	/**
	 *  投资  借款人详细信息 接口
	 * @param [int] $[borrow_id] [description]
	 * @return data array [description]
	 */
	protected function getmemberinfo(){
		return D('Loan','Api')->get_member_info(input());
	}

	/**
	 * 认证中心
	 * @param [string] $[img] [description]
	 * @return  status [description]
	 */
	protected function ispass(){
		return D('User','Api')->is_pass(input());
	}

	/**
	 * 筛选 投资
	 * @param [str] $[user_id] [description]
	 * @return [str] [status]
	 */
	protected function ischeck(){
		return D('User','Api')->is_check(input());
	}
	
	/**
	 * 提现申请 (已取消2017-10-11)
	 * @return [type] [description]
	 */
	protected function applycash(){
		return D('User','Api')->apply_cash(input());
	}

	/**
	 * 获取微信用户信息
	 * @return [type] [description]
	 */
	protected function getwxinfo(){
		return D('User','Api')->get_wxinfo(input());
	}

	/**
	 * 上传视频接口
	 * @return [type] [description]
	 */
	protected function addvideo(){
		return D('User','Api')->file_add_video(); 
	}

	/**
	 * 添加通讯录信息
	 * @return [type] [description]
	 */
	// protected function addrelative(){
	// 	return D('User','Api')->add_relative(); 
	// }

	/**
	 * 获取通讯录信息
	 * @return [type] [description]
	 */
	protected function getrelative(){
		return D('User','Api')->get_relative(); 
	}



	/*---------------------------- 支付模块开始 ------------------------------*/

	/**
	 * ips 支付接口
	 * @return boolean [description]
	 */
	protected function ipspay(){
		return D('IpsPay','Api')->getEnter(); 
	}

	/**
	 * 回调地址 逻辑处理
	 * @return boolean [description]
	 */
	protected function ipsResponse(){
		return D('IpsPay','Api')->ips_response(); 
	}

	/**
	 * app 交互 通知支付状态接口
	 * @return boolean [description]
	 */
	protected function isPayResult(){
		return D('IpsPay','Api')->is_pay_result(); 
	}

	/*---------------------------- 支付模块结束 ------------------------------*/
	
	/**
	 * 获取banner图
	 * @return [type] [description]
	 */
	protected function getbanners(){
		return D('User','Api')->get_banners(); 
	}


	/*---------------------------- 支付模块开始 ------------------------------*/

	/**
	 * woPay 支付接口
	 * @return boolean [description]
	 */
	protected function wogateway(){
		return D('WoPay','Api')->getApiEnter(); 
	}

	protected function woResponse(){
		return D('WoPay','Api')->woPayResponse(); 
	}

	/**
	 * 发送支付验证码
	 * @return [type] [description]
	 */
	protected function sendpayment(){
		return D('WoPay','Api')->send_pay_pwd(); 
	}

	/**
	 * 验证支付验证码
	 * @return [type] [description]
	 */
	protected function checkpayment(){
		return D('WoPay','Api')->check_pay_pwd(); 
	}

	/*---------------------------- 支付模块结束 ------------------------------*/


	/*---------------------------- 通用支付模块开始 ------------------------------*/

	/**
	 * 通用 支付接口
	 * @return boolean [description]
	 */
	protected function commpayment(){
		return D('CommPay','Api')->PayForPlatform(); 
	}

	/**
	 * 通用 支付确认接口
	 * @return [type] [description]
	 */
	protected function commrealpayment(){ 
		return D('CommPay','Api')->realPayForPlatform(); 
	}

	/**
	 * 通用 支付确认接口
	 * @return [type] [description]
	 */
	protected function commpayforother(){ 
		return D('CommPay','Api')->payForAnother(); 
	}

	/**
	 * 后台通知地址
	 * @return boolean [description]
	 */
	protected function commresurl(){
		return D('CommPay','Api')->commResUrl(); 
	}

	/*---------------------------- 通用支付模块结束 ------------------------------*/


	/**
	 * 电子签名
	 * @return boolean [description]
	 */
	protected function jzq(){
		return D('DigterSign','Api')->getEnter(); 
	}

}