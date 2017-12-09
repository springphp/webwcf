<?php
namespace app\home\api;
header("Content-Type:text/html;charset=UTF-8");
use app\home\api\BaseApi;
use extend\Des;

/**
*  Wopay 联通 支付接口
*  @param [string] $[iwater] [description]
*  @author [string] <[iwater]>
*  @version [date] [2017/11/22] http 协议
*/

class WoPay extends BaseApi
{
	public $forpayUrl = '';      // 代付请求地址  'mertest.unicompayment.com/issuegw/servlet/SingleIssueServlet.htm'
	public $topayUrl = '';      // 代扣
	public $callbackUrl = ''; // 商户回调地址  品台
	public $interfaceVersion = ''; // 消息版本号 
	public $wopay_api_payforanother_key = ''; // 代收
	public $wopay_api_payforpalatform_key = ''; // 代付

	public function __construct()
	{
		$this->forpayUrl		= config('wopay_api_payforanother_request_url');
		$this->topayUrl		 	= config('wopay_api_payforplantform_request_url');
		$this->callbackUrl   	= config('wopay_api_response_url');
		$this->interfaceVersion = config('wopay_api_interfaceVersion');
		$this->wopay_api_payforanother_key = config('wopay_api_payforanother_key');
		$this->wopay_api_payforpalatform_key = config('wopay_api_payforpalatform_key');
	}

	/**
	 * 支付接口入口 配置参数
	 * @return [type] [description]
	 */
	public function getApiEnter(){
		$payTypeRequest = input('payType','1','trim'); 
		switch ($payTypeRequest) {
			case '1':
				$this->payForAnother(); //待收
				break;
			case '2':
				$this->payForPlatform(); //代扣
				break;

			default:
				$this->payForAnother();
				break;
		}
	}

	/**
	 * 代付接口参数
	 * @return [type] [description]
	 */
	public function payForAnother( $payeeAcc = '6222021001116245706',$payeeName = '徐双双',$reqIp = '127.0.0.1',$amount='1000'){
		$reqTime 	= date('YmdHis');
		$orderDate  = date('Ymd');
		$orderNo = 'kwd'.preg_replace( '# #','',substr( microtime() ,2) );

		$request = [
			'interfaceVersion'		=> '1.0.0.1',//消息版本号
			'tranType'				=> 'DF02', //资金代付类型
			'merNo'					=> config('kwd_wo.payfor_merNo'), //商户号
			'orderDate'				=> $orderDate, //商户订单日期
			'reqTime'				=> $reqTime, //订单请求时间
			'orderNo'				=> $orderNo, //商户订单号
			'amount'				=> $amount, //资金代付金额 按分计算 2000 ->20.00元
			'bizCode'				=> '017', //业务类型编码 (待定)
			'payeeAcc'				=> $payeeAcc, //收款人账户，协议号  tranType=DF01时必填
			'woType'				=> '4', //收款账户类别
			'payeeBankCode'			=> '', //收款银行编码 如CCB
			'payeeName'				=> $payeeName, //收款人姓名 null
			'payeeBankBranch'		=> '', //收款银行分行 null
			'payeeUnionBankNo'		=> '', //收款银行分支行联行号 null
			'payeeAttribution'		=> '', //收款银行分支行归属地 null
			'identityInfo'			=> '', //收款人证件信息 null
			'callbackUrl'			=> $this->callbackUrl, //订单状态变更通知地址
			'reqIp'					=> $reqIp, //请求ip
			'merExtend'				=> '', //扩展字段 null 
			'signType'				=> 'RSA_SHA256', //签名方式 MD5 RSA_SHA256 2种方式
			// 'signMsg'				=> '', //商户签名
			'reqSysNo'				=> '', //内部请求系统编码
			'merAccType'			=> '', //资金代付商户账户类型
			'ftpProtocalType'		=> '', //FTP协议类型 null
		];
		
		$request['signMsg'] = $this->getSign( $request ,'./df.pem');
		$res = $this->postRequest( $this->forpayUrl,$request ); //postCurlInfo  postRequest
		
		$res = $this->doResult($res);
		if( $res['code'] == 1 ) {
			// Api()->setApi('msg','支付成功')->ApiSuccess($res);
		}else{
			Api()->setApi('msg','支付失败')->ApiError();
		}
		// Api()->setApi('msg','操作成功')->ApiSuccess($res);
	}

	/**
	 * 代收接口参数
	 * @return [type] [description]
	 * 沃用户号签约:
	 *  signAcc=18500165586|signName=李佳佳|identityNo=32010019891201098765；
	 */
	public function payForPlatform( $signAcc='',$signName='',$identityNo='',$reqIp = '127.0.0.1',$amount='2000'){
		//预定义测试参数
		if( empty($signAcc) ) $signAcc 		 = config('kwd_wo.signAcc');
		if( empty($signName) ) $signName 	 = config('kwd_wo.signName');
		if( empty($identityNo) ) $identityNo = '1'.config('kwd_wo.identityInfo');

		$reqTime 	= date('YmdHis');
		$signDate   = date('Ymd');

		$str  = 'signAcc='.$signAcc.'|signName='.$signName.'|identityNo='.'1'.$identityNo;

		$key = config('kwd_wo.topay_key');
		$signAccInfo = Des::desEncode($str,$key);
		// dump($str);die;
		$signNo  = 'kwd'.preg_replace( '# #','',substr( microtime() ,2) ).rand(100,999);
		$orderNo = 'kwd'.preg_replace( '# #','',substr( microtime() ,2) );

		$request = [
			'interfaceVersion'		=> '2.0.0.0',//消息版本号
			'payProducts'			=> 'YDK', //资金代收工具类型
			'merNo'					=> config('kwd_wo.topay_merNo'), //商户号
			'signDate'				=> $signDate, //商户订单日期
			'signNo'				=> $signNo, //商户签约申请订单号
			'accType'				=> '1', //账户类型
			'woAccType'				=> '1', //沃账户账户类型
			'accCode'				=> 'A01', //账户编码
			'payeeAcc'				=> '', //收款人账户，协议号  tranType=DF01时必填
			'woType'				=> '', //收款账户类别
			'bankCode'				=> '', //银行编码 如ICBC
			'signAccInfo'			=> $signAccInfo, //签约账户信息(加密)，包括账户号、账户人名称、证件号、有效期(信用卡)，CVV码(信用卡)，详见说明
			'signChnl'				=> 'www', //签约渠道来源 
			'merExtend'				=> '', //扩展字段 null
			'charSet'				=> 'UTF-8', //字符集
			'tradeMode'				=> '0001', //交易方式 null
			'goodsName'				=> '绑卡、借款、投资、还款', //商品名
			'goodsDesc'				=> '', //商品描述
			'reqTime'				=> $reqTime, //订单请求时间
			'orderDate'				=> $signDate, //商户订单日期
			'orderNo'				=> $orderNo, //商户订单号
			'amount'				=> $amount, //资金代收金额
			'woAcc'					=> '', //关联沃账户号
			'woType'				=> '', //关联沃账户类别
			'confirmGoodsExpireTime'=> '', //确认收货超时时间
			'goodsId'				=> '', //商品id
			'merUserId'				=> '', //商户的用户id
			'customerEmail'			=> '', //买家邮箱
			'bizCode'				=> 'B001', //业务类型编码
			'callbackUrl'			=> '', //订单状态变更通知地址	
			'reqIp'					=> $reqIp, //请求ip
			'signType'				=> 'RSA_SHA256', //签名方式
			// 'signMsg'				=> '', //商户签名
		];
		// dump($request);die;
		$request['signMsg'] = $this->getSign( $request ,'./ds.pem');
		//写日志
		$requests = str_replace( 'amp;','',http_build_query($request) );
		file_put_contents('./kwds.php', json_encode($requests));

		$res = $this->postCurlInfo( $this->topayUrl,$request ); //postCurlInfo postRequest
		
		$res = $this->doResult($res);//return $res;
		if( $res['code'] == 1 ) {
			// Api()->setApi('msg','支付成功')->ApiSuccess($res);
		}else{
			Api()->setApi('msg','支付失败')->ApiError();
		}
	}
	
	/**
	 * 处理响应参数
	 * @param  [type] $string [description]
	 * @return [type]         [description]
	 */
	public function doResult( $string ){
		$arr = explode('&',$string);
		foreach ($arr as $key => $value) {
			$newArr[] = explode('=',$value);
			$newValue[$newArr[$key][0]] = $newArr[$key][1];
		}
		wFile( $newValue ,'./woPayNote.php' );//写日志
		$data = [
			'code'	=> $newValue['transRst'],
			'msg'	=> !empty($newValue['transDesc'])?$newValue['transDesc']:$newValue['transDis'],
		];
		return $data;
	}

	/**
	 * 请求第三方
	 * @param  [type] $url  [description]
	 * @param  [type] $post [description]
	 * @return [type]       [description]
	 */
	public function postRequest( $url,$post ){
		$context = array();  
        if (is_array($post)) {  
            ksort($post);  
            $context['http'] = array  
            (     
                 'timeout'=> 60,  
                 'method' => 'POST',  
                 'header' => "Content-type: application/x-www-form-urlencoded ",
                 'content' => http_build_query($post, '', '&'),  
            );  
        }  

        $res = file_get_contents($url, false, stream_context_create($context));  
        return $res;
	}

	/**
	 * 获取 RSA_SHA256 签名
	 * @param  array  $data    [description]
	 * @param  string $cerPath [description]
	 * @return [type]          [description]
	 */
	public function getSign( $data = array() ,$cerPath='')
	{ 
		$prv = file_get_contents( $cerPath );
		
        ksort($data);
        foreach ($data as $key => &$value) {
        	if( $value == '' ) {
        		unset($data[$key]);
        	}
        }
        // dump( $data );die;
        $string = urldecode(str_replace('&','|',http_build_query($data))).'|';
        // dump($string);die;
        file_put_contents('./str.php',$string);
        openssl_sign($string, $sign, openssl_pkey_get_private($prv),OPENSSL_ALGO_SHA256);
        
        // dump(base64_encode($sign));die;
        return base64_encode($sign);
	}

	public function postCurlInfo($url,$data) {
		$curl = curl_init();
        curl_setopt($curl,CURLOPT_URL,$url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书
        curl_setopt($curl, CURLOPT_POST,1); //设置POST请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_TIMEOUT,(int)40);
        // curl_setopt($curl, CURLOPT_HTTPHEADER, $headers); //添加httpheader
        if(!$output = curl_exec($curl)) die('curl not result');
        curl_close($curl);
		return $output;
	} 

	public function getCurlInfo($url) {
		$process = curl_init($url);
		curl_setopt($process, CURLOPT_HEADER, 0);
		curl_setopt($process, CURLOPT_TIMEOUT, 30);
		curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
		$return = curl_exec($process);
		curl_close($process);
		return $return;
	} 

	/**
	 * 发送支付验证码
	 * @return [type] [description]
	 */
	public function send_pay_pwd(){
        $user_id = $this->get_userid( input('user_id',0,'trim') );
        $type = input('type','','trim');
        $dev  = input('dev','','trim');

        $tel = model('member')->where('user_id',$user_id)->value('mobile');
        $code = rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
        $time = $this->send_pay_sms($tel,$code);
        
        $checkDate = [
        	'user_id'	=> $user_id,
        	'type'		=> $type,
        	'code'		=> $code,
        	'time'		=> $time,
        	'dev'		=> $dev
        ];
        session('send_pay_pwd',$checkDate);
        Api()->setApi('msg','发送成功')->ApiSuccess();
    }

    /**
     * 验证支付验证码
     * @return [type] [description]
     */
    public function check_pay_pwd(){
    	$user_id = $this->get_userid( input('user_id',0,'trim') );
    	$code = input('code','','trim');
    	$type = input('type','','trim');
    	//&& $code == session('send_pay_pwd.dev') 设备号先不加
    	if( $user_id == session('send_pay_pwd.user_id') &&  $type == session('send_pay_pwd.type')  && $code == session('send_pay_pwd.code') ) {
    		session('send_pay_pwd',null);
    		Api()->setApi('msg','验证成功')->ApiSuccess( session('send_pay_pwd') );
    	}else{
    		if( time() - session('send_pay_pwd.time') > 60 ) {
    			session('send_pay_pwd',null);
    		}
    		Api()->setApi('msg','验证失败')->ApiError();
    	}

    }

}
