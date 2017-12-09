<?php
namespace app\home\api;
header("Content-Type:text/html;charset=UTF-8");
header("Accept:text/html");
use app\home\api\BaseApi;
use extend\Des;

/**
*  通用支付 
*/
class CommPay extends BaseApi
{
	
	public $postUrl 	= ''; // 代扣 请求地址
	public $version 	= ''; // 代扣 接口版本号
	public $merchantId  = ''; // 商户身份ID 
	public $productNo   = ''; // 商户产品编号
	public $cardNo 	    = ''; // 商户 收款银行卡号
	public $notifyUrl   = ''; // 商户后台通知URL
	public $merchantBindPhoneNo   = ''; // 商户绑卡手机号码

	public function __construct()
	{
		$this->postUrl				= config('comm_pay.postUrl');
		$this->version				= config('comm_pay.version');
		$this->merchantId		 	= config('comm_pay.merchantId');
		$this->productNo		 	= config('comm_pay.productNo');
		$this->cardNo				= config('comm_pay.cardNo');
		$this->notifyUrl		 	= config('comm_pay.notifyUrl');
		$this->merchantBindPhoneNo	= config('comm_pay.merchantBindPhoneNo');
	}

	/**
	 * 后台通知地址 处理
	 * @return [type] [description]
	 */
	public function commResUrl(){
		wFile( input() , './responseDate.php' );
	}

	/**
	 * 单笔付款接口
	 * @return [type] [description]
	 */
	public function payForAnother( $cardNo='',$realname='',$card_code='',$callerIp='',$amount='1000')
	{
		if( strpos( $amount , '.' ) ) {
			$amount = str_replace('.','',$amount);//处理小数点问题 支付按分计算的 20就是2000而不是20.00
		}

		$tradeTime 	= date('YmdHis');
		$reqNo 		= 'kwdNo'.preg_replace( '# #','',substr( microtime() ,2) ).rand(100,999);
		$orderNo 	= 'kwd'.preg_replace( '# #','',substr( microtime() ,2) );
		$request = [
			'reqNo'					=> $reqNo,//请求编号
			'service'				=> 'gbp_pay', //接口名称 由快付通提供给商户
			'version'				=> $this->version, //接口版本号
			'charset'				=> 'utf-8', //参数字符集
			'language'				=> 'zh_CN', //语言
			'signatureAlgorithm'	=> 'RSA', //参数签名算法
			'signatureInfo'			=> '', //签名值
			'callerIp'				=> $callerIp, //调用端IP
			'dishonorUrl'			=> $this->notifyUrl, //快付通退票通知商户时所调用的url
			//业务参数
			'merchantId'			=> $this->merchantId, //商户身份ID   由快付通提供给商户
			'productNo'				=> '2BA00BBA', //产品编号  由快付通提供给商户
			'orderNo'				=> $orderNo, //订单编号 
			'tradeName'				=> '绑卡、投资、还款', //交易名称 由商户填写 
			'merchantBankAccountNo'	=> $this->cardNo, //商户银行账号 T+0必填
			'merchantBindPhoneNo'	=> $this->merchantBindPhoneNo, //商户开户时绑定的手机号
			'tradeTime'				=> $tradeTime, //交易方式 null
			'amount'				=> '1',//$amount, //交易金额 单位:分,不支持小数点
			'currency'				=> 'CNY', //币种
			'custBankNo'			=> $card_code, //客户银行账户行别
			'custBankAccountIssuerNo'=> '', //客户开户行网点号 null
			'custBankAccountNo'		=> $cardNo, //客户银行账户号 8-32位
			'custName'				=> $realname, //客户姓名 付款人的真实姓名
			'custBankAcctType'		=> '', //客户银行账户类型 1个人 2企业
			'custAccountCreditOrDebit'=> '', //客户账户借记贷记类型 0存折 1借记 2贷记，目前只支持借记卡	
			'custCardValidDate'		=> '', //客户信用卡有效期 null
			'custCardCvv2'			=> '', //客户信用卡的cvv2 null
			'custCertificationType'	=> '', //客户证件类型 客户的身份证件类型
			'custID'				=> '', //客户证件号码  
			'custPhone'				=> '', //如果商户购买的产品中勾选了短信通知功能，则当商户填写了手机号时,快付通会在交易成功后通过短信通知客户
			'messages'				=> '', //发送客户短信内容 null
			'custEmail'				=> '', //客户邮箱地址 null
			'emailMessages'			=> '', //发送客户邮件内容 null
			'remark'				=> '', //备注 null
			'custProtocolNo'		=> '', //客户协议编号 null
			'extendParams'			=> '', //扩展参数 null 
		];
		
		$request['signatureInfo'] = $this->getSign( $request );
		//写日志
		wFile( $request,'./comm_request_p.php' );

		$res = $this->postRequest( $this->postUrl,$request ); 
		$res = json_decode( $res );//dump( $res);die;
		/*if( $res->status == '1' ) {
			Api()->setApi('msg','支付操作成功')->ApiSuccess($res);
		}else{
			Api()->setApi( 'msg',$res->failureDetails?:'支付失败' )->ApiError();
			return $res->failureDetails;
		}*/
		return (array)$res;
		//如果 card_code错误 将报漏油错误 属于未知错误
	}

	/**
	 * 代扣接口参数
	 * @return [type] [description]
	 */
	public function PayForPlatform()
	{
		$callerIp = input('regIp','127.0.0.1','trim');
		$user_id = $this->get_userid( input('user_id','','trim') );
		$users = model('bankcard')->where('user_id',$user_id)->find();
		//除了绑卡，其他支付都可以不穿
		$cardNo = input('bankcard_id','','trim');
		$custBindPhoneNo = input('mobile','','trim');
		$idcard = input('idcard','','trim');
		$realname = input('realname','','trim');
		$card_code = input('card_code','','trim');

		$amount = input('money','2000','trim');
		if( strpos( $amount , '.' ) ) {
			$amount = str_replace('.','',$amount);//处理小数点问题 支付按分计算的 20就是2000而不是20.00
		}
		/*----------------- 支付四要素 身份证 手机号 卡号 真实姓名 -----------------*/
		if( !$idcard ) {
			$idcard = $users['idcard'];
		}

		if( !$custBindPhoneNo ) {
			$custBindPhoneNo = $users['mobile'];
		}

		if( !$cardNo ) {
			$cardNo = $users['bankcard_num'];
		}

		if( !$realname ) {
			$realname = $users['account'];
		}
		
		//银行卡编号
		if( !$card_code ) {
			$card_code = $users['card_code'];
		}
		/*----------------- 支付四要素 身份证 手机号 卡号 真实姓名 -----------------*/

		$tradeTime 	= date('YmdHis');
		$reqNo 		= 'kwdNo'.preg_replace( '# #','',substr( microtime() ,2) ).rand(100,999);
		$orderNo 	= 'kwd'.preg_replace( '# #','',substr( microtime() ,2) );
		$request = [
			'reqNo'					=> $reqNo,//请求编号
			'service'				=> 'gbp_collect_from_bank_account', //接口名称 由快付通提供给商户
			'version'				=> $this->version, //接口版本号
			'charset'				=> 'utf-8', //参数字符集
			'language'				=> 'zh_CN', //语言
			'signatureAlgorithm'	=> 'RSA', //参数签名算法
			'signatureInfo'			=> '', //签名值
			'callerIp'				=> $callerIp, //调用端IP
			//业务参数
			'merchantId'			=> $this->merchantId, //商户身份ID   由快付通提供给商户
			'productNo'				=> $this->productNo, //产品编号  由快付通提供给商户
			'orderNo'				=> $orderNo, //订单编号 
			'tradeName'				=> '绑卡、借款、投资、还款', //交易名称 由商户填写 
			'merchantBankAccountNo'	=> $this->cardNo, //商户银行账号 T+0必填
			'tradeTime'				=> $tradeTime, //交易方式 null
			'amount'				=> $amount, //交易金额 单位:分,不支持小数点
			'currency'				=> 'CNY', //币种
			'custBankNo'			=> $card_code, //客户银行账户行别
			'custBankAccountIssuerNo'=> '', //客户开户行网点号 null
			'custBankAccountNo'		=> $cardNo, //客户银行账户号 8-32位
			'custBindPhoneNo'		=> $custBindPhoneNo, //客户开户时绑定手机号
			'custName'				=> $realname, //客户姓名 付款人的真实姓名
			'custBankAcctType'		=> '1', //客户银行账户类型 1个人 2企业
			'custAccountCreditOrDebit'=> '1', //客户账户借记贷记类型 0存折 1借记 2贷记，目前只支持借记卡	
			'custCardValidDate'		=> '', //客户信用卡有效期 null
			'custCardCvv2'			=> '', //客户信用卡的cvv2 null
			'instalments'			=> '', //分期付款标识 null
			'custCertificationType'	=> '0', //客户证件类型 客户的身份证件类型
			'custID'				=> $idcard, //客户证件号码  
			'remark'				=> '', //备注 null
			'extendParams'			=> '', //扩展参数 null
			'notifyUrl'				=> $this->notifyUrl, //商户后台通知URL 
		];
		
		$request['signatureInfo'] = $this->getSign( $request );
		//写日志
		wFile( $request,'./comm_request_p.php' );

		$res = $this->postRequest( $this->postUrl,$request ); 
		$res = json_decode( $res );
		if( $res->status == '3' ) {
			Api()->setApi('msg','支付操作成功')->ApiSuccess($res);
		}else{
			$msg = substr( $res->failureDetails ,strpos( $res->failureDetails,":")+1);
			Api()->setApi('msg',$msg)->ApiError();
		}
		// $res = $this->postForm_d( $request );
		// Api()->setApi('msg','操作成功')->ApiSuccess($res);
	}

	/*public function postForm_d( $data ){
		$str = '<style> input{ padding:4px;width:100%;margin:0 auto;}</style>';
		$str .= '<h2 style="text-align:center;">代扣接口表单</h2>';
		$str .= '<form action="'.$this->postUrl.'" method="post" style="margin:0 auto;" name="form">';
			$str .= '<input type="text" name="reqNo" value="'.$data['reqNo'].'" /><br/>';
			$str .= '<input type="text" name="service" value="'.$data['service'].'" /><br/>';
			$str .= '<input type="text" name="version" value="'.$data['version'].'" /><br/>';
			$str .= '<input type="text" name="charset" value="'.$data['charset'].'" /><br/>';
			$str .= '<input type="text" name="language" value="'.$data['language'].'" /><br/>';
			$str .= '<input type="text" name="signatureAlgorithm" value="'.$data['signatureAlgorithm'].'" /><br/>';
			$str .= '<input type="text" name="signatureInfo" value="'.$data['signatureInfo'].'" /><br/>';
			$str .= '<input type="text" name="callerIp" value="'.$data['callerIp'].'" /><br/>';

			$str .= '<input type="text" name="merchantId" value="'.$data['merchantId'].'" /><br/>';
			$str .= '<input type="text" name="productNo" value="'.$data['productNo'].'" /><br/>';
			$str .= '<input type="text" name="orderNo" value="'.$data['orderNo'].'" /><br/>';
			$str .= '<input type="text" name="tradeName" value="'.$data['tradeName'].'" /><br/>';
			$str .= '<input type="text" name="merchantBankAccountNo" value="'.$data['merchantBankAccountNo'].'" placeholder="商户银行账号" /><br/>';
			$str .= '<input type="text" name="tradeTime" value="'.$data['tradeTime'].'" /><br/>';
			$str .= '<input type="text" name="amount" value="'.$data['amount'].'" /><br/>';
			$str .= '<input type="text" name="currency" value="'.$data['currency'].'" /><br/>';
			$str .= '<input type="text" name="custBankNo" value="'.$data['custBankNo'].'" placeholder="客户银行账户" /><br/>';
			$str .= '<input type="text" name="custBankAccountIssuerNo" value="'.$data['custBankAccountIssuerNo'].'" placeholder="客户开户行网点号" /><br/>';
			$str .= '<input type="text" name="custBankAccountNo" value="'.$data['custBankAccountNo'].'" placeholder="客户银行账户号" /><br/>';
			$str .= '<input type="text" name="custBindPhoneNo" value="'.$data['custBindPhoneNo'].'" placeholder="客户开户时绑定手机号" /><br/>';
			$str .= '<input type="text" name="custName" value="'.$data['custName'].'" placeholder="客户姓名" /><br/>';
			$str .= '<input type="text" name="custBankAcctType" value="'.$data['custBankAcctType'].'" placeholder="客户银行账户类型" /><br/>';
			$str .= '<input type="text" name="custAccountCreditOrDebit" value="'.$data['custAccountCreditOrDebit'].'" placeholder="客户账户借记贷记类型" /><br/>';
			$str .= '<input type="text" name="custCardValidDate" value="'.$data['custCardValidDate'].'" placeholder="客户信用卡有效期" /><br/>';
			$str .= '<input type="text" name="custCardCvv2" value="'.$data['custCardCvv2'].'" placeholder="客户信用卡的cvv2" /><br/>';
			$str .= '<input type="text" name="instalments" value="'.$data['instalments'].'" placeholder="分期付款标识" /><br/>';
			$str .= '<input type="text" name="custCertificationType" value="'.$data['custCertificationType'].'" placeholder="客户证件类型" /><br/>';
			$str .= '<input type="text" name="custID" value="'.$data['custID'].'" placeholder="客户证件号码" /><br/>';
			$str .= '<input type="text" name="remark" value="'.$data['remark'].'" placeholder="备注" /><br/>';
			$str .= '<input type="text" name="extendParams" value="'.$data['extendParams'].'" placeholder="扩展参数" /><br/>';
			$str .= '<input type="text" name="notifyUrl" value="'.$data['notifyUrl'].'" /><br/>';

			$str .= '<input type="submit" value="提交" /><br/>';
		$str .= '</form>';
		// $str .= '<script>
		// 			function sub(){
		// 				document.form.submit();
		// 			}
		// 			setTimeout(sub,0);
		// 		 </script>';
		echo $str;die;
	}*/

	/*public function postForm_qrd( $data ){
		$str = '<style> input{ padding:4px;width:100%;margin:0 auto;}</style>';
		$str .= '<h2 style="text-align:center;">代扣确认接口表单</h2>';
		$str .= '<form action="'.$this->postUrl.'" method="post" style="margin:0 auto;" name="form">';
			$str .= '<input type="text" name="reqNo" value="'.$data['reqNo'].'" /><br/>';
			$str .= '<input type="text" name="service" value="'.$data['service'].'" /><br/>';
			$str .= '<input type="text" name="version" value="'.$data['version'].'" /><br/>';
			$str .= '<input type="text" name="charset" value="'.$data['charset'].'" /><br/>';
			$str .= '<input type="text" name="language" value="'.$data['language'].'" /><br/>';
			$str .= '<input type="text" name="signatureAlgorithm" value="'.$data['signatureAlgorithm'].'" /><br/>';
			$str .= '<input type="text" name="signatureInfo" value="'.$data['signatureInfo'].'" /><br/>';
			$str .= '<input type="text" name="callerIp" value="'.$data['callerIp'].'" /><br/>';

			$str .= '<input type="text" name="merchantId" value="'.$data['merchantId'].'" /><br/>';
			$str .= '<input type="text" name="productNo" value="'.$data['productNo'].'" /><br/>';
			$str .= '<input type="text" name="orderNo" value="'.$data['orderNo'].'" placeholder="原订购订单编号" /><br/>';
			$str .= '<input type="text" name="smsCode" value="'.$data['smsCode'].'" placeholder="短信验证码" /><br/>';
			$str .= '<input type="text" name="custBindPhoneNo" value="'.$data['custBindPhoneNo'].'" placeholder="绑定手机号码" /><br/>';
			$str .= '<input type="text" name="confirmFlag" value="'.$data['confirmFlag'].'" placeholder="确认标识" /><br/>';

			$str .= '<input type="submit" value="提交" /><br/>';
		$str .= '</form>';
		echo $str;die;
	}*/

	/**
	 * 代扣确认接口参数
	 * @return [type] [description]
	 */
	public function realPayForPlatform( $orderNo='',$smsCode='123456',$confirmFlag='1' ,$mobile = '',$callerIp='127.0.0.1' )
	{
		$reqNo 		= 'kwdNo'.preg_replace( '# #','',substr( microtime() ,2) ).rand(100,999);
		$request = [
			'reqNo'					=> $reqNo,//请求编号
			'service'				=> 'gbp_confirm_from_sms_code', //接口名称 由快付通提供给商户
			'version'				=> $this->version, //接口版本号
			'charset'				=> 'utf-8', //参数字符集
			'language'				=> 'zh_CN', //语言
			'signatureAlgorithm'	=> 'RSA', //参数签名算法
			'signatureInfo'			=> '', //签名值
			'callerIp'				=> $callerIp, //调用端IP
			//业务参数
			'merchantId'			=> $this->merchantId, //商户身份ID   由快付通提供给商户
			'productNo'				=> $this->productNo, //产品编号  由快付通提供给商户
			'orderNo'				=> $orderNo, //原订购订单编号
			'smsCode'				=> $smsCode, //短信验证码 
			'custBindPhoneNo'		=> $mobile, //绑定手机号码 持卡人开户时绑定手机号
			'confirmFlag'			=> $confirmFlag, //确认标识 1确认支付2取消支付 确认支付时，短信验证码不能为空
		];
		
		$request['signatureInfo'] = $this->getSign( $request );
		//写日志
		wFile( $request,'./comm_request_rp.php');

		$res = $this->postRequest( $this->postUrl,$request ); //postCurlInfo postRequest
		$res = json_decode( $res );
		/*if( $res->status == '1' ) {
			Api()->setApi('msg','支付操作成功')->ApiSuccess($res);
		}else{
			$msg = substr( $res->failureDetails ,strpos( $res->failureDetails,":")+1);
			Api()->setApi('msg',$res->failureDetails )->ApiError();
			
		}*/
		return (array)$res;
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
            $context['ssl'] = [
		        "verify_peer"=>false,
		        "verify_peer_name"=>false,
            ] ;
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
	public function getSign( $data = array() )
	{ 
		$prv = file_get_contents( './commpay.pem' );
		//请求的参数中,除了signatureInfo和signatureAlgorithm两个参数外,其余参数都需要进行签名
		unset($data['signatureInfo']);
		unset($data['signatureAlgorithm']);
		//排序
        ksort($data);
        foreach ($data as $key => &$value) { //空参数不参加签名
        	if( $value == '' ) {
        		unset($data[$key]);
        	}
        }
        //拼接待签名参数
        $string = urldecode( http_build_query($data) );
        wFile( ['signString'=>$string] , './comm_dSign.php' ); //写文件
		openssl_sign($string, $sign, openssl_pkey_get_private($prv),OPENSSL_ALGO_SHA1);
        
        return base64_encode($sign);
        
	}

}
