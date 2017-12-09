<?php
namespace app\home\api;
use app\home\api\BaseApi;
use app\home\api\User;
use app\admin\model\Config;
use app\admin\model\Bankcard;
use app\admin\model\Order;
use app\admin\model\UserIncome;
use think\Validate;
use think\Request;
use think\Session;
use think\Db;
use think\Cache;
use app\home\api\WoPay;
use app\home\api\CommPay;

/**
* 借款借款
*/
class Loan extends BaseApi
{
	//我要借款
	private $status = ['','未收款','未还款','已逾期','已还款','已结束','已退款'];
	private $ischeck = ['','未实名认证','已实名认证'];
	private $cardtype = ['','储蓄卡','信用卡'];
	private $types = ['','投资','借款','认证','投资收益','邀请认证奖励','逾期利息',
						'结算罚息','邀请投资奖励','收款','还款','提现','退款'];
	private $sex = ['不详','男','女'];
	private $sex_nickname = ['不详','先生','女士'];

	public function get_loan()
	{
		//如果有没有还款记录，只能借款一次
		$user_id = $this->get_userid( input('user_id','','trim') );
		$borrow_status = model('order')->where('borrow_id',$user_id)->column('status');
		if( in_array( 0,$borrow_status ) || in_array( 1,$borrow_status ) ) {
			// Api()->setApi('msg','您有未还款的借款记录，请还款后再来借款')->ApiError();
			$loan_status = 1;
		}else{
			$loan_status = 2;
		}
		//验证是否绑卡
		$isHasCard = model('bankcard')->where('user_id',$user_id)->value('bankcard_num');
		if ( $isHasCard ) { //有绑卡
			$data = [
				'max_loan_money'	=> getconfigs('INTEREST_HIGH'),
				'min_loan_money'	=> getconfigs('INTEREST_LOSS'),
				'max_loan_day'		=> getconfigs('INTEREST_LENGHT_DAY'),
				'min_loan_day'		=> getconfigs('INTEREST_MIN_DAY'),
				'loan_status'		=> $loan_status
			];
			//处理返回值逻辑  按借款次数来获取最大值
			$where = ['borrow_id'=>$user_id,'status'=>['<>',6]];
			$count = model('order')->where($where)->count('id');
			if($count>9) $count = 9;
			$data['max_loan_money'] = sprintf('%.2f',$this->getmaxloan($count));
			
		}else{
			$data = [
				'max_loan_money'	=> '5000.00',
				'min_loan_money'	=> '500.00',
				'max_loan_day'		=> '30',
				'min_loan_day'		=> '15',
				'loan_status'		=> $loan_status
			];
		}
		Api()->setApi('url','')->ApiSuccess($data);
	}

	//借款
	public function get_loan_list($field='',$where=[],$page=1,$listRow=10)
	{
		$ids = model('order')->column('borrow_id');
		if(!empty($where['borrow_id'])){
			if( !in_array($where['borrow_id'],$ids) ) Api()->setApi('msg','借款人不存在')->ApiError();//验证借款人合法性
		}

		$loanlist = model('order')->field($field)->where($where)->order('borroe_time desc')->page($page,$listRow)->select();

		foreach ($loanlist as $key => &$value) {
			if(!empty($value['back_time'])){
				$value['back_time'] = date('Y-m-d H:i',$value['back_time']);
			}

			if(!empty($value['borrow_id'])){
				$value['borrow_id'] = model('member')->where("user_id = {$value['borrow_id']}")->value('realname')?:'张三';
			}
			
			$value['borroe_time'] = date('Y-m-d H:i',$value['borroe_time']);
			
			if( $value['overdue_money'] ) {
				$value['backMoney'] = ( $value['money'] + $value['overdue_money'] );
				if( !strpos( $value['backMoney'] , '.') ) {
					$value['backMoney'] = $value['backMoney'].'.00';
				}
			}

			if(!empty($value['status'])){
				$value['statusName'] = $this->status[$value['status']];
			}
			
			if(!empty($value['invest_id'])){
				$value['invest_id'] = model('member')->where("user_id = {$value['invest_id']}")->value('realname');
			}
			if(!empty($value['invest_time'])){
				$value['invest_time'] = date('Y-m-d H:i',$value['invest_time']);
			}

			if( !empty( $value['video'] ) ){
				if( config('app_debug') === true ) {
					$value['video'] = Request::instance()->domain().'/kawadai/public/upload/upload_video_file/'.$value['video'];//处理链接url
				}else{
					$value['video'] = Request::instance()->domain().'/public/upload/upload_video_file/'.$value['video'];//处理链接url
				}
				$value['video'] = $this->getHttp( $value['video'] );
			}else{
				$value['video'] = '';
			}

			if(isValue($value,'bankcard_id')){
				$bankinfo = db('bankcard')->where("id = {$value['bankcard_id']}")->find();
				$card = substr($bankinfo['bankcard_num'], -4,4); //65285 455485456564
				$value['backtype'] = $bankinfo['bank_code'].'('.$card.')';
			}
		}

		if(strpos($field,'borrow_id')){
			$count = db('order')->where(['status'=>['<>',6]])->count('id');
			$loanlist[]['num'] = $count;
		}

		if(isset($loanlist[0])){
			Api()->setApi('url','')->ApiSuccess($loanlist);
		}else{
			Api()->setApi('msg','没有符合条件的数据')->ApiError();
		}
	}

	//确认还款
	public function is_back_money(){
		$data = input();unset($data['act']);
		if(!isValue($data,'id')) Api()->setApi('msg','借款记录id不能为空')->ApiError();
		if(!isValue($data,'money')) Api()->setApi('msg','还款金额不能为空')->ApiError();

		if( !isValue($data,'bankCardId') ) Api()->setApi('msg','投资银行卡号id不能为空')->ApiError();
		$borrowUserInfo = model('bankcard')->where('id',$data['bankCardId'])->find();//借款人银行卡信息
		$data['bankcard_id'] = $borrowUserInfo['bankcard_num'];
		extract($data);

		$user_id = $this->get_userid( input('user_id','','trim') );
		$borrowinfo = model('order')->where( ['id'=>$id,'borrow_id'=>$user_id] )->find();
		if( empty( $borrowinfo ) ) 
			Api()->setApi('msg','借款记录不存在')->ApiError();
		if( $borrowinfo['status'] == 4 ) 
			Api()->setApi('msg','已还款,请勿重复操作')->ApiError();
		//验证借款是否逾期
		if( $borrowinfo['status'] ==3 ) {
			$backmoney = (int)($data['money'] + $borrowinfo['overdue_money'])*100;
		}else{
			$backmoney = (int)$data['money']*100;
		}

        try{
			$investUserInfo = db('bankcard')->where('user_id',$borrowinfo['invest_id'])->find(); //获取投资人银行卡信息
			extract( $investUserInfo );
		}catch (\Exception $e) {
            Api()->setApi('msg',$e->getMessage())->ApiError();
        }

        if( !$bankcard_num ) {
    		Api()->setApi( 'msg',' 投资人未绑卡，无法完成支付' )->ApiError();
    	}

		/* ---------------------- 接入沃支付第三方支付开始 ---------------------- */
		/*if( config('ispay') == true ) {
			$woPay = new WoPay();
			$regIp = input('regIp','127.0.0.1','trim');

			$res = $woPay->payForPlatform($data['bankcard_id'],$borrowUserInfo['account'],$borrowUserInfo['idcard'],$regIp,$backmoney); //还款 给平台钱
	    	if( !empty( $res ) && $res['code'] != '1' ) {
	    		Api()->setApi('msg',$res['msg'])->ApiError();
	    	}

	    	$res = $woPay->payForAnother($bankcard_num,$account,$regIp,(int)$data['money']*100); //还款 给投资人钱
	    	if( !empty( $res ) && $res['code'] != '1' ) {
	    		Api()->setApi('msg',$res['msg'])->ApiError();
	    	}
    	}*/
    	/* ---------------------- 接入沃支付第三方支付结束 ---------------------- */

    	/* ---------------------- 接入 通用 第三方支付开始 ---------------------- */
		if( config('ispay') == true ) {
			//通用支付模块
        	$commPay = new CommPay();
        	$regIp = input('regIp','127.0.0.1','trim');
			$confirmFlag = input('flag','1','trim');
			$smsCode = input('code','123456','trim');
			$orderNo = input('order_id','','trim');

        	$res = $commPay->realPayForPlatform( $orderNo ,$smsCode ,$confirmFlag ,$borrowUserInfo['mobile'] ,$regIp);  //放款 给平台钱
        	if( !empty( $res ) && $res['status'] != '1' ) 
	    		Api()->setApi('msg',$res['failureDetails']?:'借款人还款支付失败' )->ApiError();
	    	
			$res = $commPay->payForAnother($bankcard_num,$account,$card_code,$regIp,(int)$data['money']*100); //还款 给投资人钱
	    	if( !empty( $res ) && $res['status'] != '1' ) 
	    		Api()->setApi('msg',$res['failureDetails']?:'投资人收款支付失败' )->ApiError();
	    	
    	}
    	/* ---------------------- 接入 通用 第三方支付结束 ---------------------- */

		$rs = model('order')->edit_order( ['id'=>$data['id'],'status'=>4,'back_time'=>time()] );
		if( $rs > 0 ){
			$income_data = [
				'money'		=> $data['money'],
				'type'		=> 10,
				'user_id'	=> $this->get_userid( input('user_id','','trim') ),
				'order_no'	=> $borrowinfo['order_no'],
			];
			model('user_income')->add_income($income_data);
			Api()->setApi('msg','还款成功')->ApiSuccess();
		}else{
			Api()->setApi('msg','还款失败')->ApiError();
		}
	}

	//支持银行卡列表
	public function get_cards($field='',$where=[]){
		$cards = db('banks')->field($field)->where($where)->select();
		foreach ($cards as $key => &$value) {
			if( config('app_debug') == true ){
				$value['card_log'] = Request::instance()->domain().'/kawadai/public/upload'.$value['card_log'];//处理链接url
			}else{
				$value['card_log'] = Request::instance()->domain().'/public/upload'.$value['card_log'];//处理链接url
			}
			$value['card_log'] = $this->getHttp( $value['card_log'] );
		}
		// dump($cards);die;
		if(isset($cards[0])){
			Api()->setApi('url','')->ApiSuccess($cards);
		}else{
			Api()->setApi('msg','没有符合条件的数据')->ApiError();
		}
	}

	//获取用户银行卡
	public function get_user_cards($field='',$where=[]){
		$cards = db('bankcard')->field($field)->where($where)->order('create_time asc')->select();

		foreach ($cards as $key => &$value) {
			$value['icon'] = $this->getUploadUrl( $value['icon'] );

			$value['idcard'] = $this->getSecrectInfo( $value['idcard'] );
			$value['bankcard_num'] = $this->getSecrectInfo( $value['bankcard_num'] );
			$value['mobile'] = $this->getSecrectInfo( $value['mobile'] ,3);

			if(isValue($value,'cardtype')){
				$value['cardtype'] = $this->cardtype[$value['cardtype']];
			}
		}

		if(isset($cards[0])){
			Api()->setApi('url','')->ApiSuccess($cards);
		}else{
			Api()->setApi('msg','没有符合条件的数据')->ApiError();
		}

	}

	//添加银行卡
	public function add_bankcard( $data =[] ){
		extract($data);
		$user_id = $this->get_userid( input('user_id','','trim') );
		$validate = new Validate([
            'bankcard_num|银行卡号'    => 'require|unique:bankcard',
            'realname|姓名'   		   => 'require',
            'idcard|绑卡身份证号码'    => 'require',
            'bank_addr|开户地址'       => 'require',
            'card_id|支持银行卡id'     => 'require',
            'card_code|银行卡编号'     => 'require',
            'mobile|绑卡手机号码'      => 'require',
    	]);
    	
    	if (!$validate->check($data)) Api()->setApi('msg',$validate->getError())->ApiError();
		//处理银行卡输入有空格的情况
		$bankcard_num  = preg_replace('# #', '', trim( (string)$data['bankcard_num'] ));
		//获取银行卡列表信息
		try{
			$cardinfo = db('banks')->where('id',$card_id)->find();
			extract($cardinfo);
		}catch (\Exception $e) {
            Api()->setApi('msg',$e->getMessage())->ApiError(); 
       	}
		//处理银行卡数据
		$bankcardinfo = [
			'bankcard_num'		=> $bankcard_num,
			'account'			=> $realname,
			'bank_addr'			=> $bank_addr,
			'card_id'			=> $card_id,
			'user_id'			=> $user_id,
			'type'				=> 1,
			'bank_code'			=> $card_name,
			'bgcolor'			=> $bgcolor,
			'icon'				=> $card_log,
			'card_code'			=> $card_code,
			'idcard'			=> $idcard,
			'mobile'			=> $mobile
		];

		//每个用户只能绑定一张银行卡
        if( db('bankcard')->where('user_id',$user_id)->count('id') >= 1 ) {
    		Api()->setApi('msg','每个用户只能绑定一张银行卡')->ApiError();
        }
		//获取用户信息
        try{
			$member = model('member')->where('user_id',$user_id)->find();
		}catch (\Exception $e) {
            Api()->setApi('msg',$e->getMessage())->ApiError();
        }

        $investment = model('bankcard')->where( 'user_id',$member['pid'] )->find(); //邀请人信息
    	if( !$investment['bankcard_num'] ) {
    		Api()->setApi('msg','邀请人未绑卡，无法完成支付' )->ApiError();
    	}
    	
		/* ---------------------- 接入沃支付第三方支付开始 ---------------------- */

		/*if( config('ispay') == false ) {
			$woPay = new WoPay();
			$regIp = input('regIp','127.0.0.1','trim');
			

			$res = $woPay->payForPlatform($bankcard_num,$realname,$idcard,$regIp); //给平台20元
        	if( $res['code'] != '1' ) {
        		Api()->setApi('msg',$res['msg'])->ApiError();
        	}
        	
	        if( $member['pid'] ) { //有邀请人
        		$res = $woPay->payForAnother($bankcard_num,$realname,$regIp,'10');//给邀请人10块钱
        		if( $res['code'] != '1' ) {
        			Api()->setApi('msg',$res['msg'])->ApiError();
        		}else{
        			//推荐成功 记录数据
        			$this->add_user_income_info( $member['pid'] );
        			model('member')->edit_member(['user_id'=>$member['pid'],'ispay'=>1]);
        		}
	        }
		}*/

		/* ---------------------- 接入沃支付第三方支付结束 ---------------------- */

		/* ---------------------- 接入通用第三方支付开始 ---------------------- */

		if( config('ispay') == true ) {
        	//确认代扣接口
        	$commPay = new CommPay();
        	$regIp = input('regIp','127.0.0.1','trim');
			$confirmFlag = input('flag','1','trim');
			$smsCode = input('code','123456','trim');
			$orderNo = input('order_id','','trim');
        	$rs = $commPay->realPayForPlatform( $orderNo ,$smsCode ,$confirmFlag ,$mobile ,$regIp ); //给平台20元

	        if( $member['pid'] ) { //有邀请人
        		$res = $commPay->payForAnother($investment['bankcard_num'],$investment['account'],$investment['card_code'],$regIp,'1000');//给邀请人10块钱
        		if( !empty( $res['status'] ) && $res['status'] != '1' ) {
        			Api()->setApi('msg',$res['failureDetails'])->ApiError();
        		}else{
        			//推荐成功 记录数据
        			$this->add_user_income_info( $member['pid'] );
        		}
	        }
		}

		/* ---------------------- 接入通用第三方支付结束 ---------------------- */

		$result = model('Bankcard')->add_cards($bankcardinfo);
		// dump( $result );die;
		if($result === false){
			Api()->setApi('msg',$this->getError())->ApiError();
		}else{
			//处理用户真实姓名
			$res = model('member')->edit_member(['user_id'=>$user_id,'realname'=>$data['realname'],'idcard'=>$data['idcard']]);
			if( !$res ) Api()->setApi('msg',$res)->ApiError();
			//发短信
			$this->sendsms_addcard( $member['realname'],$member['sex'],$member['mobile'] );
			Api()->setApi('msg','银行卡录入成功！')->ApiSuccess( ['bankCardId'=>$result] );
		}
	}

	/**
	 * 添加银行卡 发送短信通知
	 * @param  [type] $realname [description]
	 * @param  [type] $sex      [description]
	 * @param  [type] $mobile   [description]
	 * @return [type]           [description]
	 */
	public function sendsms_addcard($realname,$sex,$mobile){
		$content = config('kwd_app.msg_bankcard');
		$nickname = $realname.( $this->sex_nickname[ $sex ] );
		$content = str_replace( 'x',$nickname,$content );//处理短信内容
		$this->send_msg( $mobile , $content );
	}

	


	//推荐认证奖励
	public function get_income($field,$where,$page,$listRow) {
		$data = input();
		if(isValue($data,'create_time')){
			$start_time 	  = strtotime($data['create_time']);
			$end_time 	  	  = strtotime($data['create_time'])+24*3600;
			$where['create_time'] = ['BETWEEN',[$start_time,$end_time]];
		}
		$total_money = model('user_income')->get_money_sum($where['type'],$where);
		$result = model('user_income')->select_money($field,$where,$page,$listRow);
		foreach ($result as $key => &$value) {
			$value['create_time'] = date('Y-m-d',$value['create_time']);
			$value['typeName'] = $this->types[$value['type']];
			$value['user_id'] = model('member')->where("user_id = {$value['user_id']}")->value('realname');

		}
		$push_time = !empty($data['create_time'])?$data['create_time']:'全部';
		if($where['type'] ==5 || $where['type']  == 8){
			$total = ['total_money'=>$total_money?:0,'total_person'=>count($result),'push_time'=>$push_time?:'全部'];
		}else{
			$total = ['total_money'=>$total_money?:0,'push_time'=>$push_time?:'全部'];
		}
		if(!$result) $data = ['list'=>[],'total'=>$total];
			$data = ['list'=>$result,'total'=>$total];
		if(!isset($result[0])){
			Api()->setApi('msg','没有符合条件的数据')->ApiError();
		}else{
			Api()->setApi('msg','')->ApiSuccess($data);
		}
	}

	//我的财富
	public function get_wealth($data){
		$data['user_id'] = $this->get_userid( input('user_id','','trim') );
		//用提现余额
        $getcash = model('member')->where(['user_id'=> $data['user_id']])->value('account_balance');
        $map['user_id'] = $data['user_id'];
        $datas = [
			'recommend_renzheng'	=> model('user_income')->get_money_sum(5,$map)?:0,
			'recommend_touzi'		=> model('user_income')->get_money_sum(8,$map)?:0,
			'overdue_interest'		=> model('user_income')->get_money_sum(7,$map)?:0,
			'get_cash'				=> $getcash?:0,
			'make_money'			=> model('user_income')->get_makemoney($data['user_id'])
		];
		// dump($datas);die;
		if(!isValue($data,'user_type')) $data['user_type'] = 1;//user_type=1:借款人页面 2：投资人页面
		if($data['user_type'] == 2){
			$datas['touzi'] = model('user_income')->get_money_sum(4,$map)?:0;
		}else{
			$field = 'borroe_time,money,id as idstr';
			$where = ['status'=>['<>',6],'borrow_id'=>$data['user_id'],'is_done'=>['<>',1]];
			$datas['loan_list'] = model('order')->select_order($field,$where);
			if( !empty($datas['loan_list']) ) {
				foreach ($datas['loan_list'] as $key => &$value) {
					$value['borroe_time'] = date('Y-m-d H:i',$value['borroe_time']);
				}
			}
			
		}
		$datas['get_cash_now'] = $this->apply_cash($data['user_id']); //立即体现数据

		Api()->setApi('msg','')->ApiSuccess($datas);
	}

	//取消借款
	public function break_loan($data){
		if(!isValue($data,'id')) Api()->setApi('msg','借款id不能为空')->ApiError();
		//核查数据安全
		$where['id'] = $data['id'];
		$money = model('order')->where($where)->value('money');
		if(!$money) Api()->setApi('msg','借款id不存在')->ApiError();

		$orders = ['id'=>$data['id'],'status'=>6,'exit_time'=>time()];
		$incomes = ['type'=>12,'money'=>$money,'user_id'=>$this->get_userid( input('user_id','','trim') )];
		$rs  =  model('order')->edit_order($orders);
		if($rs>0){
			$re  =  model('user_income')->add_income($incomes);
			if($re>0) Api()->setApi('msg','借款已取消')->ApiSuccess();
		}
		Api()->setApi('msg','借款取消失败')->ApiError();
	}

	//财富记录
	public function get_wealth_list($field,$where,$page,$listRow){
		$data = input();
		if(!$where['user_id']) $where['user_id'] = $this->get_userid( input('user_id','','trim') );
		
		if(isValue($data,'create_time')){
			$start_time 	  = strtotime($data['create_time']);
			$end_time 	  	  = strtotime($data['create_time'])+24*3600;
			$where['create_time'] = ['BETWEEN',[$start_time,$end_time]];
		}
		$where['type'] = ['in',[4,5,7,8]];
		$total = [
			'total_money'	=> model('user_income')->where($where)->sum('money')?:0,//总收益
			'getmoney_time'	=> !empty($data['create_time'])?$data['create_time']:'全部'
		];

		$result = model('user_income')->select_money($field,$where,$page,$listRow);
		foreach ($result as $key => &$value) {
			$value['create_time'] = date('Y-m-d H:i',$value['create_time']);
			$value['typeName'] = $this->types[$value['type']];
			$value['user_id'] = model('member')->where("user_id = {$where['user_id']}")->value('realname');
		}
		$datas = [
			'list'		=> $result?:[],
			'total'		=> $total
		];
		if(!isset($result[0])){
			Api()->setApi('msg','')->ApiError(['total'=>$total]);
		}else{
			Api()->setApi('msg','')->ApiSuccess($datas);
		}		
	}

	//获取会员详情
	public function get_member_info($data){
		if(!isValue($data,'id')) Api()->setApi('msg','借款记录id不能为空')->ApiError();
		$map['id'] = $data['id'];
		//借款信息
		$field = 'interest,overdue_money,money,term,borrow_id,borroe_time,video,order_no,bankcard_id';
		$borrowinfo = model('order')->field($field)->where($map)->find();
		if(empty($borrowinfo)) Api()->setApi('msg','参数不合法')->ApiError();
		$borrowinfo['make_money'] = $borrowinfo['interest'] + $borrowinfo['overdue_money']; 
		//处理借款视频url
		if( config('app_debug') === true ) {
			$borrowinfo['video'] = Request::instance()->domain().'/kawadai/public/upload/upload_video_file/'.$borrowinfo['video'];
		}else{
			$borrowinfo['video'] = Request::instance()->domain().'/public/upload/upload_video_file/'.$borrowinfo['video'];
		}

		$borrowinfo['video'] = $this->getHttp($borrowinfo['video']);
		unset($borrowinfo['overdue_money']);

		//借款人基本信息
		$field = 'realname,idcard,sex,province_id,city_id,constellation,birthday,mobile_status,mobile,status';
		$where['user_id'] = $borrowinfo['borrow_id'];
		$memberinfo = model('member')->field($field)->where($where)->find();

		//投资人信息
		$user_id = $this->get_userid( input('user_id',0,'trim') );
		$investinfo = model('member')->field('mobile,realname,idcard')->where('user_id',$user_id)->find();
		//处理年龄-》生日计算
		$memberinfo['age'] = birthday($memberinfo['birthday']);
		unset($memberinfo['birthday']);

		$memberinfo['sexName'] = $this->sex[$memberinfo['sex']];
		if(!$memberinfo['constellation']) $memberinfo['constellation'] = '射手座';

		//处理户籍 
		if($memberinfo['province_id'] && $memberinfo['city_id']){
			$province = db('city')->where("city_code = {$memberinfo['province_id']}")->value('city_name');
			$city = db('city')->where("city_code = {$memberinfo['city_id']}")->value('city_name');
			$memberinfo['area_name'] = $province.'-'.$city;
		}
		unset($memberinfo['province_id']);
		unset($memberinfo['city_id']);
		// unset($borrowinfo['borrow_id']);
		//征信详情 | 第三方接口
		//TODE 如何获取历史最高逾期天数  ----》是逾期处理了，状态改为 已结束？
		
		$creditinfo['creditinfo_data'] = $this->getCreditInfo( $memberinfo['idcard'] ,$where['user_id']);//征信说明
		$creditinfo['creditinfo_data']['real_name'] = $memberinfo['status'];//实名认证状态1：未实名认证 2：已实名认证
		$creditinfo['creditinfo_data']['overdue_days'] = model('order')->where('borrow_id',$borrowinfo['borrow_id'])->order('overday desc')->value('overday');//逾期天数
		$creditinfo['creditinfo_url'] = urldo('home/Article/zhenxin',$creditinfo['creditinfo_data']);//征信说明
		//协议
		
		$code = input('code','2','trim');
		if ( $user_id == '176' ) {
			$code = 1;
		}

		$creditinfo['creditinfo_url'] = $this->getHttp($creditinfo['creditinfo_url']);
		$protocol['prtocol_book_url'] = urldo('home/Article/index?type=7&code='.$code);//风险提示书
		$protocol['prtocol_book_url'] = $this->getHttp($protocol['prtocol_book_url']);//风险提示书

		// dump(date('Y-m-d H:i:s','1511149374'));die;
		
		$over_time = $borrowinfo['borroe_time'] + $borrowinfo['term']*24*60*60;
		//银行卡信息
		$bankinfo = db('bankcard')->field('bank_code,account')->where('user_id',$borrowinfo['borrow_id'])->find();
		// dump($bankinfo);die;
		
		$protocol_loan = [
			'code'			=> $code,
			'orderid'		=> $borrowinfo['order_no']?:'kwd151076940511',
			'loan_name'		=> $memberinfo['realname'],
			'loan_mobile'	=> $this->getSecrectInfo($memberinfo['mobile'],3),
			'loan_idcard'	=> $this->getSecrectInfo($memberinfo['idcard']),

			'invest_name'	=> $investinfo['realname'],
			'invest_mobile'	=> $this->getSecrectInfo($investinfo['mobile'],3),
			'invest_idcard'	=> $this->getSecrectInfo($investinfo['idcard']),

			'manage_fee'	=> getconfigs('INTEREST_INVEST_FEE'),
			'term'			=> $borrowinfo['term'],
			'interest'		=> $borrowinfo['interest'],
			'loan_time'		=> date('Y-m-d',$borrowinfo['borroe_time']),
			'over_time'		=> date('Y-m-d',$over_time),
			'loan_bank_name'	=> $bankinfo['account'],
			'loan_bank'			=> $bankinfo['bank_code'],
			'bankcard'			=> $this->getSecrectInfo($borrowinfo['bankcard_id'])
		];
		unset($borrowinfo['interest']);
		$type = input('proType',2,'trim');
		if( $type == 2 ) {
			$protocol['prtocol_loan_url'] = urldo('home/Article/protocol_loan',$protocol_loan);
		}else{
			$protocol['prtocol_loan_url'] = urldo('home/Article/index?type=1&code='.$code);//TODE 要传参数，待处理
		}
		$protocol['prtocol_loan_url'] = $this->getHttp($protocol['prtocol_loan_url']);

		//隐藏敏感数字
		$memberinfo['idcard'] = $this->getSecrectInfo( $memberinfo['idcard'] );
		$memberinfo['mobile'] = $this->getSecrectInfo( $memberinfo['mobile'],3 );
		$borrowinfo['bankcard_id'] = $this->getSecrectInfo( $borrowinfo['bankcard_id'] );

		$datas = ['userinfo'=>$memberinfo,'borrowinfo'=>$borrowinfo,'creditinfo'=>$creditinfo,'protocol'=>$protocol];
		if($datas){
			Api()->setApi('url','')->ApiSuccess($datas);
		}else{
			Api()->setApi('msg','没有符合条件的数据')->ApiError();
		}
	}

	/**
	 * 运营商信息 征信
	 * @return [type] [description]
	 */
	public function getCreditInfo( $id_no= '' ,$user_id = ''){
        $id_no = "";//511502199306078858
        $user_id = "";//74 

        //运营商信息
        $user = model('Member')->where('user_id',$user_id)->field('mobile,realname,taskid')->find();
        $mobile = $user['mobile']?:'';
        $task_id = $user['taskid']?:'';
        $url = "https://api.51datakey.com/carrier/v3/mobiles/{$mobile}/mxdata?task_id={$task_id}";
        $header = ['Content-Type: application/json; charset=utf-8',
                    'Authorization:token 25136488e83f4b38b534fac141e8ffd6'
                ];
        $operator = curl_send_get($url,$header);
        if(isset($operator['bills'])){
        	$lastmonth = date("Y-m-d",mktime(0, 0 , 0,date("m")-1,1,date("Y")));
        	foreach ($operator['bills'] as $v) {
        		if($v['bill_start_date'] == $lastmonth){
        			$sms_fee = $v['sms_fee'];//短彩信费用
        			$web_fee = $v['web_fee'];//网络流量费用
        		}
        	}
        	for($i=0;$i<3;$i++){
        		$sms_grade = array('NOTE_C_LOSS'=>0,'NOTE_C_HIGH'=>300,'NOTE_B_LOSS'=>300,'NOTE_B_HIGH'=>900,'NOTE_A_LOSS'=>900,'NOTE_A_HIGH'=>2000);//短信费用等级
        		$wed_grade = array('FLOW_C_LOSS'=>0,'FLOW_C_HIGH'=>2000,'FLOW_B_LOSS'=>2000,'FLOW_B_HIGH'=>5000,'FLOW_A_LOSS'=>5000,'FLOW_A_HIGH'=>10000);//网络流量费用等级
	            $num = array('A','B','C');
	            if($sms_fee < $sms_grade['NOTE_'.$num[$i].'_HIGH'] && $sms_fee >= $sms_grade['NOTE_'.$num[$i].'_LOSS']){
	                $sms_fee = $num[$i];//通话等级
	            }
	            if($sms_fee > $sms_grade['NOTE_A_HIGH']){
	                $sms_fee = 'A';//通话等级
	            }

	            if($web_fee < $wed_grade['FLOW_'.$num[$i].'_HIGH'] && $web_fee >= $wed_grade['FLOW_'.$num[$i].'_LOSS']){
	                $web_fee = $num[$i];//金融通话等级
	            }
	            if($web_fee > $wed_grade['FLOW_A_HIGH']){
	                $web_fee = 'A';//金融通话等级
	            }
	        }
        }



        //用户通话记录
        $bank_call_count = D('User','api')->get_bank_call_record($user_id);

        //运营商征信
        $pubkey = "8fee186a-9241-4b1b-aa1e-256501fa875f";
        $product_code = "Y1001005";
        $secretkey = "7ff1596e-d8ae-4796-a3cf-75ac1c4d5454";

        $out_order_id = 'kwd'.rand(0,9).date('YmdHis').rand(10,99);
       
        $data['id_no'] = $id_no;
        $str = json_encode($data,JSON_UNESCAPED_UNICODE);
        $signature = md5($str."|".$secretkey);

        $url = 'https://api4.udcredit.com/dsp-front/4.1/dsp-front/default/pubkey/'.$pubkey.'/product_code/'.$product_code.'/out_order_id/'.$out_order_id.'/signature/'.$signature;
        $header = ['Content-Type: application/json; charset=utf-8'];
        $res = curl_send_post($url,$data,$header);

        if(isset($res['body'])){
		    $result = $res;//json_decode($res,true);
		    $loan_blacklist = $result['body']['graph_detail']['link_user_detail']['online_dishonest_count'];//网贷失信
		    $court_blacklist = $result['body']['graph_detail']['link_user_detail']['court_dishonest_count'];//法院失信
		    $loan_number = $result['body']['loan_detail']['actual_loan_platform_count'];//借款次数
		    $name_credible = $result['body']['id_detail']['name_credible'];//可信姓名
		    $bankcard_count = $result['body']['graph_detail']['user_have_bankcard_count'];//名下银行卡数
		}else{
			$result = $res;
		    $loan_blacklist = 0;//网贷失信
		    $court_blacklist = 0;//法院失信
		    $loan_number = 0;//借款次数
		    $name_credible = '未认证';//可信姓名
		    $bankcard_count = 0;
		}

		$data_zx['sms_fee'] = isset($sms_fee)?$sms_fee:'C';
		$data_zx['wed_fee'] = isset($web_fee)?$web_fee:'C';
        $data_zx['loan_blacklist'] = isset($loan_blacklist)?$loan_blacklist:'0';
        $data_zx['court_blacklist'] = isset($court_blacklist)?$court_blacklist:'0';
        $data_zx['loan_number'] = isset($loan_number)?$loan_number:'0';
        $data_zx['name_credible'] = isset($name_credible)?$name_credible:$user['realname'];
        $data_zx['bankcard_count'] = isset($bankcard_count)?$bankcard_count:'0';

        $t_rating = Db('config')->where(['group'=>'rating'])->column('config_value','config_mark');//通话等级
        $j_rating = Db('config')->where(['group'=>'jrating'])->column('config_value','config_mark');//金融通话等级
        for($i=0;$i<3;$i++){
            $num = array('A','B','C');
            if($bank_call_count['phone_count'] < $t_rating['T_RATING_'.$num[$i].'_HIGH'] && $bank_call_count['phone_count'] >= $t_rating['T_RATING_'.$num[$i].'_LOSS']){
                $data_zx['phone_count'] = $num[$i];//通话等级
            }
            if($bank_call_count['phone_count'] > $t_rating['T_RATING_A_HIGH']){
                $data_zx['phone_count'] = 'A';//通话等级
            }

            if($bank_call_count['bank_count'] < $j_rating['JT_RATING_'.$num[$i].'_HIGH'] && $bank_call_count['bank_count'] >= $j_rating['JT_RATING_'.$num[$i].'_LOSS']){
                $data_zx['bank_phone_count'] = $num[$i];//金融通话等级
            }
            if($bank_call_count['bank_count'] > $j_rating['JT_RATING_A_HIGH']){
                $data_zx['bank_phone_count'] = 'A';//金融通话等级
            }

            $data_zx['t_rating_'.$num[$i]] = $t_rating['T_RATING_'.$num[$i].'_LOSS']."-".$t_rating['T_RATING_'.$num[$i].'_HIGH'];
            $data_zx['jt_rating_'.$num[$i]] = $j_rating['JT_RATING_'.$num[$i].'_LOSS']."-".$j_rating['JT_RATING_'.$num[$i].'_HIGH'];
        }

        if($data_zx['loan_blacklist'] != 0 || $data_zx['court_blacklist'] != 0){
            $data_zx['id_blacklist'] = '1';//身份证黑名单 1->y 0->n
        }else{
            $data_zx['id_blacklist'] = '0';//身份证黑名单 1->y 0->n
        }
        return $data_zx;//征信详情
    }

	//申请借款
	public function apply_loan($data){
		if( !isValue($data,'bankCardId') ) Api()->setApi('msg','投资银行卡号id不能为空')->ApiError();
		$data['bankcard_id'] = model('bankcard')->where('id',$data['bankCardId'])->value('bankcard_num');
		if(!isValue($data,'money'))  Api()->setApi('msg','借款金额不能为空')->ApiError();
		if(!isValue($data,'term'))  Api()->setApi('msg','借款期限不能为空')->ApiError();
		if(!isValue($data,'video'))  Api()->setApi('msg','借款视频不能为空')->ApiError();
		extract($data);

		$user_id = $this->get_userid( input('user_id','','trim') );
		//处理返回值逻辑  按借款次数来获取最大值
		$where['status'] = ['in',[0,1]];
		$where['borrow_id']	= $user_id;
		$status = model('order')->where($where)->column('status');
		// dump( $count );die;
		if( $status ) Api()->setApi('msg','您有未还款借款记录，请先前往还款')->ApiError();
		$count = model('order')->where(['is_done'=>1,'borrow_id'=>$user_id])->count('id');
		// dump();die;
		if($count>9) $count = 9;
		$max_loan_money = $this->getmaxloan($count);
		if( $data['money'] > $max_loan_money || $data['money'] < 500 ) {
			Api()->setApi('msg','借款金额必须在500~'.$max_loan_money.'范围内')->ApiError();
		}

		$fee = db('config')->where("config_mark = 'INTEREST_BORROW_FEE'")->value('config_value');
		$interest = $this->get_interest_profit($data['money'],$data['term']);
		$order_no = $orderNo = 'kwd'.preg_replace( '# #','',substr( microtime() ,2) );
		$orders = [
			'order_no'		=> $order_no,
			'bankcard_id'	=> $bankcard_id,
			'borrow_id'		=> $user_id,
			'money'			=> $money,
			'fee'			=> $fee,
			'interest'		=> (int)$interest - 20,
			'borroe_time'	=> time(),
			'term'			=> $term,
			'video'			=> $video
		];
		$user_income = [
			'type'			=> 2,
			'money'			=> $money,
			'user_id'		=> $user_id,
			'order_no'		=> $order_no
		];
		$rs = model('order')->add_order($orders);
		$re = model('user_income')->add_income($user_income);
		if($rs>0 && $re>0){
			Api()->setApi('msg','申请借款成功！')->ApiSuccess();
		}else{
			Api()->setApi('msg',$rs)->ApiError();
		}
	}

	//立即提现
	public function get_cash(){
		$data = input();
		if(!isValue($data,'money')) Api()->setApi('msg','提现金额不能为空')->ApiError();

		if( !isValue($data,'bankCardId') ) Api()->setApi('msg','投资银行卡号id不能为空')->ApiError();
		$bankcardInfo = model('bankcard')->where('id',$data['bankCardId'])->find();
		$data['bankcard_id'] = $bankcardInfo['bankcard_num'];
		extract($data);
		
		$user_id = $this->get_userid( input('user_id','','trim') );
		try{
			$member = model('member')->where('user_id',$user_id)->find();
		}catch (\Exception $e) {
            Api()->setApi('msg',$e->getMessage())->ApiError();
        }

		/* ---------------------- 接入沃支付第三方支付开始 ---------------------- */
		// if( config('ispay') == true ) {
		// 	$woPay = new WoPay();
		// 	$regIp = input('regIp','127.0.0.1','trim');

		// 	$res = $woPay->payForAnother($data['bankcard_id'],$bankcardInfo['account'],$regIp,(int)$data['money']*100); //给提现人钱
		// 	if( $res['code'] != '1' ) {
		// 		Api()->setApi('msg',$res['msg'])->ApiError();
		// 	}
		// }
		/* ---------------------- 接入沃支付第三方支付结束 ---------------------- */

		/* ---------------------- 接入通用第三方支付开始 ---------------------- */
		if( config('ispay') == true ) {
			$commPay = new CommPay();
        	$regIp = input('regIp','127.0.0.1','trim');

			$res = $commPay->payForAnother($data['bankcard_id'],$bankcardInfo['account'],$bankcardInfo['card_code'],$regIp,(int)$data['money']*100); //给提现人钱
			if( !empty( $res['status'] ) && $res['status'] != '1' ) {
    			Api()->setApi('msg',$res['failureDetails'])->ApiError();
    		}
		}
		/* ---------------------- 接入通用第三方支付结束 ---------------------- */

		//验证提现金额合法性
		if( $data['money'] > $member['account_balance'] ) Api()->setApi('msg','余额不足')->ApiError();
		//处理提现余额
		$account_balance = $data['money'] + getconfigs('INTEREST_GETMONEY_FEE');//提现手续费
		model('member')->where('user_id',$user_id)->setDec('account_balance',$account_balance);//提现扣余额

		$income_data = [
			'type'		=> 11,
			'money'		=> $data['money'],
			'user_id'	=> $user_id
		];
		$re = model('user_income')->add_income($income_data);
		if($re === false ) {
			Api()->setApi('msg','提现失败！')->ApiError();
		}else{
			Api()->setApi('msg','提现成功！')->ApiSuccess();
		}
	}

	 /**
     * 提现申请
     * @param  [user_id] $data [description]
     * @return [data]  array     [description]
     */
    public function apply_cash($user_id){
        $field = 'bankcard_num,cardtype,bank_code,icon';
        $where['user_id'] = $user_id;

        $leave_money = model('member')->where($where)->value('account_balance');
        $max_get_cash = getconfigs('INTEREST_GETMONEY_MAX');
        $min_get_cash = getconfigs('INTEREST_GETMONEY_LOSS');
        $fee = getconfigs('INTEREST_GETMONEY_FEE');

        $bankcard = db('bankcard')->field($field)->where($where)->order('create_time desc')->find();
        if(!$bankcard) Api()->setApi('msg','您没有绑卡，请前往绑卡！')->ApiError();

        if( config('app_debug') === true ) {
        	 $bankcard['icon'] = Request::instance()->domain().'/kawadai/public/upload'.$bankcard['icon'];
        }else{
        	 $bankcard['icon'] = Request::instance()->domain().'/public/upload'.$bankcard['icon'];
        }
        $bankcard['icon'] = $this->getHttp( $bankcard['icon'] );
        $bankcard['bankcard_num'] = $this->getSecrectInfo( $bankcard['bankcard_num'] );
        $bankcard['bankcard_num_last4'] = substr($bankcard['bankcard_num'],-4);

        if($bankcard['cardtype'] == 1){
            $bankcard['cardtype'] = '储蓄卡';
        }else{
            $bankcard['cardtype'] = '信用卡';
        }

        return ['leave_money'=>$leave_money,'max_get_cash'=>$max_get_cash,'min_get_cash'=>$min_get_cash,'fee'=>$fee,'bankcard'=>$bankcard];
    }

    /**
     * 获取最高借款限额
     * @param  [type] $count [description]
     * @return [type]        [description]
     */
    public function getmaxloan($count){
    	switch ($count) {
    		case '0':
    			$maxloanmoney = getconfigs('INTEREST_OEN_HIGH');
    			break;
    		case '1':
    			$maxloanmoney = getconfigs('INTEREST_TWO_HIGH');
    			break;
			case '2':
    			$maxloanmoney = getconfigs('INTEREST_THIRD_HIGH');
    			break;
			case '3':
    			$maxloanmoney = getconfigs('INTEREST_FORTH_HIGH');
    			break;
			case '4':
    			$maxloanmoney = getconfigs('INTEREST_FIFTH_HIGH');
    			break;
			case '5':
    			$maxloanmoney = getconfigs('INTEREST_SIXTH');
    			break;
			case '6':
    			$maxloanmoney = getconfigs('INTEREST_SEVENTH_HIGH');
    			break;
			case '7':
    			$maxloanmoney = getconfigs('INTEREST_EIGHTH_HIGH');
    			break;
			case '8':
    			$maxloanmoney = getconfigs('INTEREST_NINTH');
    			break;
			case '9':
    			$maxloanmoney = getconfigs('INTEREST_TENTH_HIGH');
    			break;
    		default:
    			$maxloanmoney = getconfigs('INTEREST_TENTH_HIGH');
    			break;
    	}
    	return $maxloanmoney;
    }

}