<?php
namespace app\home\api;
use app\home\api\BaseApi;
use think\Request;
use think\Session;
use think\Db;
use think\Cache;
use app\home\api\CommPay;
use app\home\api\WoPay;

/**
* 投资接口
*/
class Investment extends BaseApi
{
	private $status = ['','未接单','催收中','催收结束','拒绝催收'];	
	private $sex = ['不详','先生','女士'];
	private $sex_nickname = ['不详','先生','女士'];

	//投资总收益
	Public function get_interestments(){
		$data =input();
		$data['user_id'] = $this->get_userid( input('user_id','','trim') );

		//验证用户输入数据安全
		$user_id = model('member')->where('user_id',$data['user_id'])->count('user_id');
		if( !$user_id ) Api()->setApi('msg','用户不存在')->ApiError();

		$where['invest_id'] = $data['user_id'];
		$total = model('order')->get_total_sum($where);
		$total['touzi_total_money'] = model('user_income')->get_invest_total_sum($data['user_id']);
		$interestments = [
			'total'			=> $total,
			'give_money'	=> model('order')->get_sums(1,$where)?:0,
			'not_get_day'	=> model('order')->get_sums(2,$where)?:0,
			'get_money'		=> model('order')->get_sums(4,$where)?:0,
			'overdue'		=> model('order')->get_sums(3,$where)?:0,
		];
		Api()->setApi('msg','')->ApiSuccess($interestments);
	}

	//我的投资
	// public function get_total_interestment($data){
	// 	if(!isValue($data,'status')) Api()->setApi('msg','请输入合法参数')->ApiError();
	// 	$data = model('order')->get_sums($data['status']);
	// 	Api()->setApi('msg','')->ApiSuccess($data);
	// }

	/**
	 * 投资 筛选 接口
	 * @param  [type] $field   [description]
	 * @param  [type] $where   [description]
	 * @param  [type] $order   [description]
	 * @param  [type] $page    [description]
	 * @param  [type] $listRow [description]
	 * @return [type]          [description]
	 */
	public function make_loan($field,$where,$order,$page,$listRow)
	{
		//按逾期天数查询
		if(isValue($where,'overday')){
			$where['overday'] = $this->get_where_type($where['overday']);
		}
		//过滤条件 到期时间
		if(!empty($data['back_time'])){
			$start_time 	  = strtotime($data['back_time']);
			$end_time 	  	  = strtotime($data['back_time'])+24*3600;
			$where['update_time'] = ['BETWEEN',[$start_time,$end_time]];
		}
		
		$result = model('order')->select_order($field,$where,$order,$page,$listRow);
		// dump($where);dump($result);die;
		foreach ($result as $key => &$value) {
			if(!empty($value['borrow_id'])){
				$value['interest'] = model('order')->where('borrow_id',$value['borrow_id'])->sum('interest')?:0;
				$value['total_interest'] = $value['interest'] + $value['overdue_money']?:0;
				$value['borrowid'] = $value['borrow_id'];
				$member = model('member')->field('realname,idcard_s,mobile,taskid')->where('user_id',$value['borrow_id'])->find();
				//添加联系手机和 taskid
				$value['borrow_mobile'] = $member['mobile']?:0;
				$value['mobile'] = $member['mobile']?:0;
				$value['taskid'] = $member['taskid']?:'000000';
				$value['task_id'] = $member['taskid']?:'000000';

				$value['borrow_id'] = $member['realname'];
				if( config('app_debug') == false ){
					$value['idcard_s'] = Request::instance()->domain().'/public/upload/'.$member['idcard_s'];
				}else{
					$value['idcard_s'] = Request::instance()->domain().'/kawadai/public/upload/'.$member['idcard_s'];
				}
				$value['idcard_s'] = $this->getHttp( $value['idcard_s'] );
				$value['icon'] = $value['idcard_s'];
			}
			if( !empty($value['invest_id']) ){
				$value['touzi_status'] = 1; //已投资该借款
				unset($value['invest_id']);
			}else{
				$value['touzi_status'] = 2;
			}
			if(!empty($value['invest_time'])){
				$value['invest_time'] = date('Y-m-d',$value['invest_time']);
			}
			if(!empty($value['borroe_time'])){
				$value['borroe_time'] = date('Y-m-d',$value['borroe_time']);
			}
			if(!empty($value['update_time'])){
				$value['update_time'] = date('Y-m-d',$value['update_time']);
			}
			if(!empty($value['overtime']) && !empty($value['term'])){
				$overtime = $this->get_over_time($value['overtime'],$value['term']);
				$value['overtime'] = $overtime['overtime'];
				// $value['overday'] = $overtime['overday'];
			}

			if(!empty($value['back_time'])){
				$value['back_time'] = date('Y-m-d',$value['back_time']);
			}

			if(isValue($value,'overdue_money') && isValue($value,'interest')){
				$value['total_income'] = $value['interest']+$value['overdue_money'];
				$value['backMoney'] = $value['money']+$value['overdue_money'];
				unset($value['overdue_money']);
				// unset($value['interest']);
			}

			if(isValue($value,'userid')){
				$user = db('member')->where("user_id = {$value['userid']}")->find();
				// dump($user);die;
				if($user['user_id']){
					$value['username'] = $user['realname'];
					if($user['idcard_s']){
						$value['headImg'] = Request::instance()->domain().'/public/upload/'.$user['idcard_s'];
						$value['headImg'] = $this->getHttp( $value['headImg'] );
					}
					if($user['province_id']){
						$province_id = db('city')->where('city_code' , $user['province_id'])->value('city_name');
						// dump($province_id);die;
						// $city_id = db('city')->where("city_code = {$user['city_id']}")->value('city_name');
						$value['user_addr'] = $province_id;
					}
					unset($value['userid']);
				} 
			}
			
			//逾期列表需要联系人信息
			if( isset($where['status']) &&  $where['status'] == 3 ) {
				$relations = model('related')->where('user_id',$where['invest_id'])->column('mobile,realname');
				foreach ($relations as $k => $v) {
					$value['relationship'][] = ['mobile'=>$k,'realname'=>$v];
				}
			}
		}
		// dump($result);die;
		if(isset($result[0])){
			Api()->setApi('msg','')->ApiSuccess($result);
		}else{
			Api()->setApi('msg','没有符合条件的数据')->ApiError();
		}
	}

	//待还款列表status/2  已还款列表status/4  已逾期列表status/3
	public function make_loan_list($field,$where,$order,$page,$listRow)
	{
		//过滤条件 到期时间
		if(!empty(input('time'))){
			$start_time 	  = strtotime(input('time'));
			$end_time 	  	  = strtotime(input('time'))+24*3600;
			$where['borroe_time'] = ['BETWEEN',[$start_time,$end_time]];
		}
		//统计数据
		$count  = model('order')->where($where)->count('invest_id');
		if(!$count) $sum = 0;
		$sum 	= model('order')->where($where)->sum('money');
		$sum = sprintf("%.2f",$sum);
		$total  = ['sum'=>$sum,'count'=>$count?:0];
		// dump($where);die;
		
		$where['invest_id'] = $this->get_userid( input('user_id','','trim') );
		// dump($where);die;
		$result = model('order')->select_order($field,$where,$order,$page,$listRow);
		foreach ($result as $key => &$value) {
			$value['borrowid'] = $value['borrow_id'];
			$value['borrow_id'] = db('member')->where("user_id = {$value['borrow_id']}")->value('realname');
			if(!empty($value['borroe_time']) && !empty($value['term'])){
				$overtime = $this->get_over_time($value['borroe_time'],$value['term']);
				$value['back_time'] = $overtime['overtime'];
				// $value['overday'] = strtotime($overtime['overtime']);
			}
			$value['borroe_time'] = date('Y-m-d',$value['borroe_time']);
			$value['totalEarn'] = $value['interest'] + $value['overdue_money'];
			$value['total_income'] = $value['totalEarn'];

		}
		$data = ['total'=>$total,'list'=>$result];
		if(isset($result[0])){
			Api()->setApi('msg','')->ApiSuccess($data);
		}else{
			Api()->setApi('msg','没有符合条件的数据')->ApiError();
		}
	}

	

	public function get_interestment($data){
		if(!isValue($data,'status')) Api()->setApi('msg','状态不能为空')->ApiError();
		$rs = model('order')->get_order_info($data['status']);
		if($rs>0){
			Api()->setApi('msg','')->ApiSuccess($rs);
		}else{
			Api()->setApi('msg','没有符合条件的数据')->ApiError();
		}
	}

	/**
	 * 我的账单
	 * @param  [array] $data [description]
	 * @return [type]       [description]
	 */
	public function get_bill_list($data = []){
		$data['user_id'] = $this->get_userid( input('user_id','','trim') );
		if(!isValue($data,'type')) $data['type'] = 1;
		$where['invest_id'] = $data['user_id'];

		extract($data);
		if(isValue($data,'create_time')) {
			$where['create_time'] = $this->get_time($create_time);
 		}else{
			$where['create_time'] = $this->get_time();
		}

        $total = [
			'notback_money'		=> model('order')->get_sums(2,$where),
			'overdue_money'		=> model('order')->get_sums(3,$where),
			'back_money'		=> model('order')->get_sums(4,$where),
			'total_money'		=> model('order')->get_total_sum($where),
		];
		$total['lijitouzi_cash'] = $total['total_money']['sum'];
		$total['zhanyongbenjin_cash'] = $total['notback_money']['sum'];
		unset($where['invest_id']);
		//投资走势
		$where['type'] = $data['type'];
		// dump($where);die;
		if($data['type'] == 2){
			$goinfo['sum'] = model('user_income')->where($where)->sum('money');
		}else{
			$goinfo['count'] = model('user_income')->where($where)->count('id');
		}
		$goinfo['time'] = !empty($data['create_time'])?$data['create_time']:date('m.d');
		$where['user_id'] = $data['user_id'];
		//钱来钱往
		$move_money = [
			'total_money'			=> model('user_income')->get_sum($where)?:0,
			'recommend_renzheng'	=> model('user_income')->get_money_sum(5,$where)?:0,
			'recommend_touzi'		=> model('user_income')->get_money_sum(8,$where)?:0,
			'touzi'					=> model('user_income')->get_money_sum(4,$where)?:0,
			'getcash'				=> model('user_income')->get_money_sum(11,$where)?:0,
		];
		//投资会员 排名
		$top_number = $this->get_top_number($data['user_id'],4,$where['create_time']);
		$datas = ['total'=>$total,'goinfo'=>$goinfo,'move_money'=>$move_money,'top_number'=>$top_number,'default_month'=>date('Y-m')];
		Api()->setApi('msg','')->ApiSuccess($datas);
	}

	/**
	 * 投资会员 排名
	 * @param  [type]  $invest_id   [description]
	 * @param  integer $type        [description]
	 * @param  [type]  $create_time [description]
	 * @return [type]               [description]
	 */
	public function get_top_number($invest_id,$type = 4,$create_time=[]){
        $investid = model('member')->where('role = 1')->column('user_id');
        if(!in_array($invest_id,$investid)) Api()->setApi('msg','您还不是投资人，请前往申请！')->ApiError();
        if(!empty($create_time)) $where['create_time'] = $create_time;
       	$where['type'] = $type;
        foreach ($investid as $key => $value) {
        	$where['user_id'] = $value;
            $sum[$value] = (int)model('user_income')->where($where)->sum('money')?:$key;
        }
        if(empty($sum))  $top_number = '暂无排名';
        arsort($sum);
        $top = array_values($sum);
       	$top_number = array_flip($top);
       	$top_number = $top_number[$sum[$invest_id]]+1;
        return $top_number;
    }

	/**
	 * 退款记录	 
	 * @param  [type]  $field   [description]
	 * @param  [type]  $where   [description]
	 * @param  integer $page    [description]
	 * @param  integer $listRow [description]
	 * @return [type]           [description]
	 */
	public function back_money_note($field,$where=[],$page=1,$listRow=10){
		$result = model('order')->field($field)->where($where)->page($page,$listRow)->select();
		foreach ($result as $key => &$value) {
			// $back_time = $this->get_over_time($value['borroe_time'],$value['over_day']);
			if( !empty($value['exit_time']) ) {
				$value['back_time'] = $value['exit_time'];
			}else{
				unset($value['exit_time']);
			}
			if(!empty($value['borrow_id'])){
				$value['borrow_id'] = model('member')->where("user_id = {$value['borrow_id']}")->value('realname');
			}
			$value['totalEarn'] = $value['interest'];	
			$value['borroe_time'] = date('Y-m-d',$value['borroe_time']);
			unset($value['interest']);
		}
		if(isset($result[0])){
			Api()->setApi('url','')->ApiSuccess($result);
		}else{
			Api()->setApi('msg','没有符合条件的数据')->ApiError();
		}
		
	}

	/**
	 * 获取当月数据记录
	 * @param  string $mtime [description]
	 * @return [type]        [description]
	 */
	public function get_time($mtime = ''){
		if($mtime){
			$get_times = explode('-', $mtime);
			$year  = $get_times[0];
			$month = $get_times[1];
			$time  = $mtime;
		}else{
			$year  = date('Y');
			$month = date('m');
			$time  = date('Y-m');
		}
		if($month == 12){
			$nextmonth = 1;
		}else{
			$nextmonth = $month + 1;
		}
		$start_time = strtotime($time);
		$end_time = strtotime($year.'-'.$nextmonth);
        $if = ['BETWEEN',[$start_time,$end_time]];
        return $if;
	}

	//逾期催收
	public function get_overdue($data){
		$user_id = $this->get_userid( input('user_id','','trim') );
		$where = ['invest_id'=>$user_id,'is_overdue'=>1];
		$map = ['invest_id'=>$user_id,'is_overdue'=>1,'status'=>4]; //当前逾期 已还款的逾期记录

		$overdue_now_sum = model('order')->where($map)->count('id');
		$overdue_sum = model('order')->where($where)->count('id');
		$overdue_money = model('order')->where($where)->sum('money');

		$data = ['overdue_now_sum' =>$overdue_now_sum?:0,'overdue_sum'=>$overdue_sum?:0,'overdue_money'=>$overdue_money?$overdue_money:0];
		Api()->setApi('msg','')->ApiSuccess($data);
	}

	//获取省信息
	public function get_province($field,$where=[],$order,$page=1,$listRow=50){
		$result = db('city')->field($field)->where($where)->page($page,$listRow)->select();
		foreach ($result as $key => &$value) {
			if($value['city_name'] == '内蒙古自治区'){
				$value['city_name'] = '内蒙古';
			}
			if($value['city_name'] == '广西壮族自治区'){
				$value['city_name'] = '广西';
			}
			if($value['city_name'] == '西藏自治区'){
				$value['city_name'] = '西藏';
			}
			if($value['city_name'] == '宁夏回族自治区'){
				$value['city_name'] = '宁夏';
			}
			if($value['city_name'] == '新疆维吾尔自治区'){
				$value['city_name'] = '新疆';
			}
			$data['province_info'][] = $value;
		}
		if(isset($result[0])){
			Api()->setApi('url','')->ApiSuccess($data);
		}else{
			Api()->setApi('msg','没有符合条件的数据')->ApiError();
		}
	}

	//短信催收 接口  1:催款2:为催收令
	Public function sendsms_to_borrower($data){
		extract($data);
		$type = !empty($data['type'])?$data['type']:1;
		// $rewards = db('config')->where("config_mark = 'INTEREST_COLLECTION_REWARD'")->value('config_value');
		if(!isValue($data,'order_id')) Api()->setApi('msg','催收记录id不能为空')->ApiError();
		$orderid = (int)$data['order_id'];
		
		//催收令
		if($type == 2 ){
			$rewards  = input('rewards','','trim');
			$backday  = input('backday','','trim'); 
			$cui_data = ['order_id'=>$orderid,'rewards'=>$rewards,'status'=>2,'backday'=>$backday];//催收奖励如何处理,后台定死
			$rs = model('collection')->add_collection($cui_data); // 添加催收令记录
			$orderinfo = ['id'=>$orderid,'cui_command_status'=>2];
			$res = model('order')->edit_order($orderinfo);	//修改催收状态
			if( $rs && $res ){
				Api()->setApi('msg','已生成催收令')->ApiSuccess(['status'=>200]);
			}else{
				Api()->setApi('msg','催收令发生未知错误')->ApiError(['status'=>400]);
			}
		}
		
		//催收
		$borrowid = model('order')->where('id',$orderid)->value('borrow_id');
		if(empty($borrowid)) Api()->setApi('msg','没有符合要求的催收数据')->ApiError();
		//处理数组
		$members = db('member')->field('realname,sex,mobile')->where('user_id',$borrowid)->find();
		$sms_data = $this->getSmsInfo($members);
		extract($sms_data);

		$this->sendMessage($mobile,$content);//发送短信验证码 未监听
		
		$res = model('order')->edit_order(['id'=>$orderid,'cui_status'=>1]);
		if( $res ) {
			Api()->setApi('msg','催收成功')->ApiSuccess(['status'=>200]);
		}else{
			Api()->setApi('msg','催收失败')->ApiError(['status'=>400]);
		}
	}

	public function getSmsInfo($members){
		$sex = $this->sex[$members['sex']];
		if(!$sex) $sex = '男士/女士';
		if(!$members['realname']) $members['realname'] = 'kawadai';
		$content = $members['realname'].$sex.',您的借款已逾期,请尽快还款，否则会有高额的罚款,及意料之外的生活困扰。谢谢合作！';
		$data = [
			'content'	=> $content,
			'mobile'	=> $members['mobile']?:'000000'
		];
		return $data;
	}


	/**
	 * 我的催收令 列表
	 * @param  [type] $field [description]
	 * @param  [type] $where [description]
	 * @param  [type] $order [description]
	 * @param  [type] $page  [description]
	 * @param  [type] $row   [description]
	 * @return [type]        [description]
	 */
	public function get_overcommand_list($field,$where,$order,$page,$row){
		$data = model('collection')->select_collection($field,$where,$order,$page,$row);
		$invest_id = $this->get_userid( input('user_id','','trim') );
		
		foreach ($data as $key => &$value) {
			$value['create_time'] = date('Y-m-d',$value['create_time']);
			$value['statusName'] = $this->status[$value['status']];
			$value['rewards'] = $value['rewards'].'%';
			if($value['order_id']){
				$field = 'borrow_id,term,borroe_time,money,interest,overdue_money';
				$orderinfo = db('order')->field($field)->where('id', $value['order_id'])->find();
			}
			if($orderinfo['borrow_id']){
				$value['realname'] = model('member')->where("user_id = {$orderinfo['borrow_id']}")->value('realname');
				
			}

			$time = time() - $orderinfo['borroe_time'] - $orderinfo['term']*24*3600;
			$value['overday'] = date('d',$time)-1; 
			$value['total_money'] = $orderinfo['money']+$orderinfo['overdue_money'];
			unset($value['order_id']);
		}
		if(isset($data[0])) {
            Api()->setApi('msg','')->ApiSuccess($data);
        }else{
            Api()->setApi('msg','没有符合条件的数据')->ApiError();
        }
	}

	/**
	 * 投资--支付 （为付款前-生成订单）
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	public function to_pay($data) 
	{
		if(!isValue($data,'id')) Api()->setApi('msg','借款记录不能为空')->ApiError();
		$user_id = $this->get_userid( input('user_id','','trim') );
		$order_no = $order_no = $orderNo = 'kwd'.preg_replace( '# #','',substr( microtime() ,2) ); //生成订单号

		if( !is_array($data['id']) ) $data['id'] = (array)$data['id'];
		$orderid = $data['id'];
		foreach ($orderid as $v) {
			$orderinfo = model('order')->field('mark_time,invest_id,checked,status')->where("id = {$v}")->find();
			$mark_time = $orderinfo['mark_time'];
			if( !$mark_time ) $mark_time = time();

			if( $orderinfo['status'] == 2 || $orderinfo['checked'] == 1 ) {
				Api()->setApi('msg','借款记录不合法或借款已被投资')->ApiError(); //验证数据合法性
			}

			$save_data1[] = array_merge(['id'=>$v],['invest_id'=>$user_id,'is_done'=>2,'status'=>1,'mark_time'=>$mark_time]);
		}

		//批量修改状态为 放款中
		$rs = model('order')->edit_orders($save_data1);
		//根据借款记录id 获取数据
		$where['id'] = ['in',$orderid];
		$sum = model('order')->where($where)->sum('money')?:0;
		$payinfo = ['order_no'=>$order_no,'total_money'=>$sum,'jishuqi_time'=>config('app_pay_get_time')];

		//处理过期支付记录数据释放
		$this->setovertime($user_id);

		//处理单日投资限额
		$is_vip = model('member')->where('user_id',$user_id)->value('is_vip');
		if ( $is_vip != 1 ) { //不是vip限额
			$maxAllowInvest = getconfigs('INVEST_QUOTA');
			$today = date('Y-m-d');
			$start_time = strtotime($today);
			$end_time   = strtotime($today)+24*60*60;
			$where['invest_time'] = ['BETWEEN',[$start_time,$end_time]];
			$investMoney = model('order')->where($where)->sum('money')?:0;
			// dump($where);die;
			if ( $investMoney > $maxAllowInvest ) {
				Api()->setApi('msg','您今日的投资不能超过'.$maxAllowInvest)->ApiError();
			}
		}

		Api()->setApi('msg','')->ApiSuccess($payinfo);
	}

	/**
	 * 处理过期释放 借款订单
	 * @param  [type] $user_id [description]
	 * @return [type]          [description]
	 */
	public function setovertime($user_id){
		$where = ['checked'=>2,'check_man'=>$user_id];
		$result = model('order')->where($where)->order('mark_time asc')->column('id,mark_time');
		// dump($result);die;
		$mark_time = array_values($result);
		$mark_time = end($mark_time);//获取最后操作时间
		// if( !$mark_time ) Api()->setApi('msg','用户不存在')->ApiError();
		$order_ids = array_keys($result);
		if(empty($order_ids)) Api()->setApi('msg','借款记录不存在')->ApiError();
		foreach ($order_ids as $v) {
			$save_data2[] = array_merge(['id'=>$v],['checked'=>1,'is_done'=>3,'status'=>0,'mark_time'=>null,'order_no'=>null]);
		}
		if(time() - $mark_time > config('app_pay_get_time')) {
			model('order')->edit_orders($save_data2);
			Api()->setApi('msg','借款记录已过期,被释放')->ApiError();
		}
	}

	/**
	 * 投资 我要放款 （支付给借款人钱--提供订单号--可能有多条记录） 
	 * 目前只支付投资一天借款记录！！！
	 * @param [type] $data [description]
	 */
	public function add_invest($data){
		unset($data['act']);
		if( !isValue($data,'bankCardId') ) Api()->setApi('msg','投资银行卡号id不能为空')->ApiError();
		$touziCardInfo = model('bankcard')->where('id',$data['bankCardId'])->find();
		$data['touzi_cardid'] = $touziCardInfo['bankcard_num'];
		if( empty($data['id']) ) {
			Api()->setApi('msg','借款记录不能为空')->ApiError();
		}
		if( !is_array($data['id']) ) {
			$data['id'] = (array)$data['id'];
		}

		$data['user_id'] = $this->get_userid( input('user_id','','trim') );
		
        try{
			$member = model('member')->where('user_id',$data['user_id'])->find(); //获取投资人信息
		}catch (\Exception $e) {
            Api()->setApi('msg',$e->getMessage())->ApiError();
        }

        $investment = model('bankcard')->where( 'user_id',$member['pid'] )->find(); //邀请人信息
    	if( !$investment['bankcard_num'] ) {
    		Api()->setApi('msg','邀请人未绑卡，无法完成支付' )->ApiError();
    	}

        try{
			$investCard = model('bankcard')->where('user_id',$data['user_id'])->find(); //投资人银行卡信息
		}catch (\Exception $e) {
            Api()->setApi('msg',$e->getMessage())->ApiError();
        }

        try{
			$borrowInfo = model('order')->where('id','in',$data['id'])->find();//订单信息
		}catch (\Exception $e) {
            Api()->setApi('msg',$e->getMessage())->ApiError();
        }
        //验证投资状态，避免重复投资
		if( empty($borrowInfo['check_man']) || $borrowInfo['status'] >= 2 ) {
			Api()->setApi('msg','投资方式不合法')->ApiError();
		}

        try{
			$borrowCard = model('bankcard')->where('user_id',$borrowInfo['borrow_id'])->find(); //借款人银行卡信息
		}catch (\Exception $e) {
            Api()->setApi('msg',$e->getMessage())->ApiError();
        }

        if( !$borrowCard['bankcard_num'] ) {
    		Api()->setApi('msg','借款人未绑卡，无法完成支付' )->ApiError();
    	}

		/* ---------------------- 接入沃支付第三方支付开始 ---------------------- */
		/*if( config('ispay') == true ) {
			$woPay = new WoPay();
			$regIp = input('regIp','127.0.0.1','trim');
			$res = $woPay->payForPlatform($data['touzi_cardid'],$member['realname'],$member['idcard'],$regIp,(int)$borrowInfo['money']*100); //放款 给平台钱
	    	if( $res['code'] != '1' ) {
	    		Api()->setApi('msg',$res['msg'])->ApiError();
	    	}

    		if( $member['pid'] ) { //有邀请人 
    			$investMan = db('bankcard')->field('account,bankcard_num')->where('user_id',$member['pid'])->find();
				$res = $woPay->payForAnother($investMan['bankcard_num'],$investMan['account'],$regIp,'1000'); //给邀请人10块钱
        		if( $res['code'] != '1' ) {
        			Api()->setApi('msg',$res['msg'])->ApiError();
        		}else{
        			//处理邀请人投资认证奖励 
					$rewardMoney = getconfigs('INTEREST_INVITE');
					$recReward = [
						'type'		=> 8,
						'money'		=> $rewardMoney,
						'user_id'	=> $member['pid']
					]; //投资认证奖励
					$res = model('user_income')->add_income( $recReward );
					if( !$res ) Api()->setApi('msg','邀请人奖励设置失败')->ApiError();
			        // 将投资所得存入账户余额
					model('member')->where('user_id',$member['pid'])->setInc( 'account_balance', $rewardMoney);
        		}
	        }

	        $res = $woPay->payForAnother($data['touzi_cardid'],$member['realname'],$regIp,(int)$borrowInfo['interest']*100); //平台给投资人钱
        	if( $res['code'] != '1' ) {
        		Api()->setApi('msg',$res['msg'])->ApiError();
        	}

	        $money = (int)($borrowInfo['money'] - 20 - $borrowInfo['interest'])*100;
	        $res = $woPay->payForAnother($borrowCard['bankcard_num'],$borrowCard['account'],$regIp,$money); //平台给借款人钱
        	if( $res['code'] != '1' ) {
        		Api()->setApi('msg',$res['msg'])->ApiError();
        	}
    	}*/
    	/* ---------------------- 接入沃支付第三方支付结束 ---------------------- */

    	/* ---------------------- 接入通用第三方支付开始 ---------------------- */
		if( config('ispay') == true ) {
	    	//通用支付模块
        	$commPay = new CommPay();
        	$regIp = input('regIp','127.0.0.1','trim');
			$confirmFlag = input('flag','1','trim');
			$smsCode = input('code','123456','trim');
			$orderNo = input('order_id','','trim');

        	$res = $commPay->realPayForPlatform( $orderNo ,$smsCode ,$confirmFlag ,$touziCardInfo['mobile'] ,$regIp );  //放款 给平台钱
        	if( !empty( $res['status'] ) && $res['status'] != '1' ) {
    			Api()->setApi('msg',$res['failureDetails']?:'投资放款支付失败')->ApiError();
    		}

    		if( $member['pid'] ) { //有邀请人 

        		$res = $commPay->payForAnother($investment['bankcard_num'],$investment['account'],$investment['card_code'],$regIp,'1000');//给邀请人10块钱
        		// dump( $res );die;
        		if( !empty( $res['status'] ) && $res['status'] != '1' ) {
        			Api()->setApi('msg',$res['failureDetails']?:'邀请人奖励支付失败')->ApiError();
        		}else{
        			//处理邀请人投资认证奖励
        			$rewardMoney = getconfigs('INTEREST_INVITE');
					$recReward = [
						'type'		=> 8,
						'money'		=> $rewardMoney,
						'user_id'	=> $member['pid'],
						'order_no'	=> $borrowInfo['order_no']
					]; //投资认证奖励
					$res = model('user_income')->add_income( $recReward );
					if( !$res ) Api()->setApi('msg','邀请人奖励设置失败')->ApiError();
			        // 将投资所得存入账户余额
					$res = model('member')->where('user_id',$member['pid'])->setInc( 'account_balance', $rewardMoney);
					if( !$res ) Api()->setApi('msg','投资余额变更失败')->ApiError();
        		}
	        }

	        $res = $commPay->payForAnother( $data['touzi_cardid'],$member['realname'],$investCard['card_code'],$regIp,(int)$borrowInfo['money']*100 ); //平台给投资人钱
        	if( !empty( $res['status'] ) && $res['status'] != '1' ) {
    			Api()->setApi('msg',$res['failureDetails']?:'投资人收益支付失败')->ApiError();
    		}

	        $money = (int)($borrowInfo['money'] - 20 - $borrowInfo['interest'])*100;
	        $res = $commPay->payForAnother($borrowCard['bankcard_num'],$borrowCard['account'],$borrowCard['card_code'],$regIp,$money); //平台给借款人钱
        	if( !empty( $res['status'] ) && $res['status'] != '1' ) {
    			Api()->setApi('msg',$res['failureDetails']?:'放款给借款人支付失败')->ApiError();
    		}
    	}
    	/* ---------------------- 接入通用第三方支付结束 ---------------------- */
    	//数据逻辑处理如下： 
		$orders = [
			'id'			=> $data['id'][0],
			'status'		=> 2,
			'invest_time'	=> time(),
			'touzi_cardid'	=> $data['touzi_cardid'],
			'is_done'		=> 1,
			'invest_fee'	=> getconfigs('INTEREST_INVEST_FEE')
		];//修改订单状态记录投资时间
		
		$touzi_incomes = [
			[
				'type'			=> 1,
				'money'			=> $borrowInfo['money'],
				'user_id'		=> $data['user_id'],
				'order_no'		=> $borrowInfo['order_no']
			],	//记录投资数据
			[
				'type'			=> 4,
				'money'			=> $borrowInfo['interest'],
				'user_id'		=> $data['user_id'],
				'order_no'		=> $borrowInfo['order_no']
			]	//投资收益
		]; 

		// 将投资所得存入账户余额
		$res   = model('member')->where('user_id',$data['user_id'])->setInc( 'account_balance', $borrowInfo['interest'] );
		if( !$res ) Api()->setApi('msg','投资余额变更失败')->ApiError();

		$res  = model('user_income')->add_incomes($touzi_incomes);
		if( !$res ) Api()->setApi('msg','投资收益或投资记录失败')->ApiError();
		$res  = model('order')->edit_order($orders); //写入订单
		if( !$res ) Api()->setApi('msg','投资失败')->ApiError();

		//发送短信
		$nickname = $member['realname'].( $this->sex_nickname[ $member['sex'] ] );
		//处理短信内容数据
		$content  = str_replace( 'x', $nickname, config('kwd_app.msg_invest') );//投资人名
		$content  = str_replace( '@', $borrowCard['account'], $content );	 //	借款人名
		$content  = str_replace( '*', $borrowInfo['interest'], $content );//投资收益

		$this->send_msg( $member['mobile'] , $content );		

		Api()->setApi('msg','投资放款成功！')->ApiSuccess();
		
	}

	//联系客服
	public function call_servers(){
		$group_id = db('auth_group')->where("group_name = '客服'")->value('group_id');
		if(!$group_id) Api()->setApi('msg','抱歉，暂时没有在线客服')->ApiError();
		$where['group'] = $group_id;
		$server_mobile = db('admin')->where($where)->value('phone'); 
		if(isset($server_mobile)){
			Api()->setApi('msg','获取客服电话成功')->ApiSuccess(['mobile'=>$server_mobile]);
		}else{
			Api()->setApi('msg','没有符合条件的数据')->ApiError();
		}
	}

	//邀请赚钱
	public function apply_member(){
		$user_id = $this->get_userid( input('user_id','','trim') );
		$userinfo = model('member')->field('realname,sex')->where('user_id',$user_id)->find();
		if( $userinfo['sex'] == 1 ){
			$username = mb_substr($userinfo['realname'], 0,1).'先生';
		}else{
			$username = mb_substr($userinfo['realname'], 0,1).'女士';
		}
		
		$where['config_mark'] = ['in',['INTEREST_INVEST_FEE','INTEREST_INVITE']];
		$config_value = db('config')->where($where)->column('config_value');

	    // $regUrl = makeCodeImg('http://www.baidu.com',$user_id);
	   
        $androidUrl = Request::instance()->domain().'/public/upload/yaoqing/andrion_code.png';
        $iosUrl  	= Request::instance()->domain().'/public/upload/yaoqing/ios_code.jpg';
        $image_url  = Request::instance()->domain().'/public/upload/yaoqing/kawadai.jpg';
        $share_url 	= Request::instance()->domain().'/download/kwdShare/index.html?user_id='.$user_id.'&username=rose';

        //写文件
        wFile();

		$data = [
			'give_applyment_fee'	=> $config_value[0],
			'pay_money_success_fee'	=> $config_value[1],
			'code_url'				=> $this->getHttp($iosUrl),
			'andrion_code_url'	    => $this->getHttp($androidUrl),
			'decode_url'			=> $this->getHttp($image_url),
			// 'regUrl'				=> $this->getHttp($regUrl),
			'share_url'				=> $this->getHttp($share_url)
		];

		//验证设备 显示二维码
		if ( Request::instance()->isMobile()) {
			if( !empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
				if( strpos( $_SERVER['HTTP_USER_AGENT'], 'iPhone' ) ){
					$data['devType'] = 1; //ios 设备
				}else{
					$data['devType'] = 2; //andriod 设备
				}
			}
		}

		Api()->setApi('msg','操作成功')->ApiSuccess($data);
	}

	/**
	 * 成为投资人
	 * @return boolean [description]
	 */
	public function is_investment(){
		$user_id = $this->get_userid( input('user_id','','trim') );

		$role = model('member')->where(['user_id'=>$user_id])->value('role');
		if($role == 1){
			Api()->setApi('msg','您已是投资人,无需申请')->ApiSuccess();
		}else{
			$rs = model('member')->edit_member(['user_id'=>$user_id,'role'=>1]);
			if($rs>0){
				Api()->setApi('msg','申请成为投资人成功！')->ApiSuccess(['status'=>'ok']);
			}else{
				Api()->setApi('msg',$rs)->ApiError();
			}
		}
	}

}