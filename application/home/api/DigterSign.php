<?php
namespace app\home\api;
header("Content-Type:text/html;charset=UTF-8");
use app\home\api\BaseApi;

use org\ebq\api\model\PingRequest;
use org\ebq\api\tool\RopUtils;
use org\ebq\api\model\bean\UploadFile;

use com\jzq\api\model\bean\Signatory;
use com\jzq\api\model\menu\DealType;
use com\jzq\api\model\menu\IdentityType;
use com\jzq\api\model\menu\SequenceInfo;
use com\jzq\api\model\menu\SignLevel;
use com\jzq\api\model\sign\ApplySignFileRequest;


use com\jzq\api\model\sign\DetailAnonyLinkRequest;

use com\jzq\api\model\sign\FileLinkRequest;

use com\jzq\api\model\sign\SignStatusRequest;

/**
* 电子签名 君子签接口
*/
class DigterSign extends BaseApi
{
	public $appkey 		 = 'eb4d27949ccc47f8';
	public $secret 		 = 'baf3ff05eb4d27949ccc47f88b17e8c8';
	public $service_url  = 'http://sandbox.api.junziqian.com/services';
	public $fullName 	 = '咔哇贷';
	public $identityCard = '91531512ML16QB22';

	public function getEnter(){
		$type = input('type','1','trim');
		if( $type > 5 ) {
			Api()->setApi('msg','签名类型不存在')->ApiError();
		}
		switch ($type) {
			case '1':
				$this->test();
				break;
			case '2':
				$this->doPostObj();
				break;
			case '3':
				$this->getDetailLink();
				break;
			case '4':
				$this->getFileLink();
				break;
			case '5':
				$this->getSignStatus();
				break;
			default:
				$this->toSign();
				break;
		}
	}
	
	/**
	 * PING服务 测试服务可用性
	 * @return [type] [description]
	 */
	public function test()
	{
		//组建请求参数
		$requestObj 	= new PingRequest();
		$appkey			= $this->appkey;
		$secret 		= $this->secret;
		$service_url 	= $this->service_url;

		//请求
		$response = RopUtils::doPostByObj($requestObj,$appkey,$secret,$service_url);
		//以下为返回的一些处理
		$responseJson=json_decode($response);

		if ( $responseJson->success == 'true' ) {
			Api()->setApi('msg','签名成功')->ApiSuccess( $responseJson );
		}else{
			Api()->setApi('msg','签名失败')->ApiError();
		}
	}

	/**
	 * 上传合同完成电子签名
	 * @return [type] [description]
	 */
	public function doPostObj(){
		$requestObj = new ApplySignFileRequest();
		//* 签约文件
		$requestObj->file = new UploadFile( input('fileName','E:\\tmp\\testtwo.pdf','trim') );
		//* 合同名称
		$requestObj->contractName= input('contractName','借款合同','trim');
		//是否使用云证书1使用,其它:不使用
		$requestObj->serverCa = 1;
		//签约处理类型
		$requestObj->dealType = 1;//DealType::$AUTH_SIGN;
		//是否顺序签约1为按顺序，其它无序
		$requestObj->orderFlag = 0;//1表示按顺序签（按signatories.orderNum顺序），默认不按顺序
		//* 签约方
		$signatories = array();
		//签约方1
		$signatory = new Signatory();
		//* 证件类型
		$signatory->setSignatoryIdentityType(IdentityType::$IDCARD);
		//* 名称或公司名称
		$signatory->fullName = $this->fullName;
		//* 证件号码、营业执照号、社会信用号
		$signatory->identityCard = $this->identityCard;
		//* 手机号码,为个人时必填,企业可不填
		$signatory->mobile = '17603008582';
		//签字位置,页码从0开始 
		//[{"page":0,","chaptes":[{"offsetX":0.12,"offsetY":0.23}]},{"page":1,"chaptes":[{"offsetX":0.45,"offsetY":0.67}]}]]
		$signatory->setChapteJson(array(
		    array(
		        'page'=>0,
		        'chaptes'=>array(
		            array("offsetX"=>0.12,"offsetY"=>0.23),
		            array("offsetX"=>0.45,"offsetY"=>0.67)
		        )
		    ),
		    array(
		        'page'=>1,
		        'chaptes'=>array(
		            array("offsetX"=>0.5,"offsetY"=>0.5)
		        )
		    ),
		    array(
		        'page'=>2,
		        'chaptes'=>array(
		            array("offsetX"=>0.5,"offsetY"=>0.5)
		        )
		    ),
		    array(
		        'page'=>3,
		        'chaptes'=>array(
		            array("offsetX"=>0.5,"offsetY"=>0.5)
		        )
		    ),
		    array(
		        'page'=>4,
		        'chaptes'=>array(
		            array("offsetX"=>0.5,"offsetY"=>0.5)
		        )
		    ),
		    array(
		        'page'=>5,
		        'chaptes'=>array(
		            array("offsetX"=>0.5,"offsetY"=>0.5)
		        )
		    ),
		    array(
		        'page'=>6,
		        'chaptes'=>array(
		            array("offsetX"=>0.5,"offsetY"=>0.5)
		        )
		    ),
		    array(
		        'page'=>7,
		        'chaptes'=>array(
		            array("offsetX"=>0.5,"offsetY"=>0.5)
		        )
		    ),
		    array(
		        'page'=>8,
		        'chaptes'=>array(
		            array("offsetX"=>0.5,"offsetY"=>0.5)
		        )
		    ),
		    array(
		        'page'=>9,
		        'chaptes'=>array(
		            array("offsetX"=>0.5,"offsetY"=>0.5)
		        )
		    ),
		    array(
		        'page'=>10,
		        'chaptes'=>array(
		            array("offsetX"=>0.5,"offsetY"=>0.5)
		        )
		    )
		));
		//echo $signatory->setChapteJson."</br>";
		array_push($signatories, $signatory);
		
		$requestObj->signatories = $signatories;

		//请求
		$response=RopUtils::doPostByObj( $requestObj,$this->appkey, $this->secret, $this->service_url );
		//以下为返回的一些处理
		$responseJson=json_decode($response);
		
		wFile( $response ,'./jzq_log.php');//写文件

		if ( $responseJson->success == 'true' ) {
			Api()->setApi('msg','上传合同完成电子签名')->ApiSuccess( $responseJson );
			// return $responseJson->applyNo;
		}else{
			Api()->setApi('msg', $responseJson->error->message )->ApiError( ['solution'=>$responseJson->error->solution] );
		}
	}

	/**
	 * 电子清明详情查看
	 * @return [type] [description]
	 */
	public function getDetailLink(){
		$applyNo = input('applyNo','APL939035302306123776','trim');
		//组建请求参数
		$requestObj=new DetailAnonyLinkRequest();
		//* 签约编号
		$requestObj->applyNo= $applyNo;
		//请求
		$response = RopUtils::doPostByObj( $requestObj,$this->appkey, $this->secret, $this->service_url );
		//以下为返回的一些处理
		$responseJson=json_decode($response);
		
		wFile( $response ,'./jzq_log.php');//写文件

		if ( $responseJson->success == 'true' ) {
			Api()->setApi('msg','电子清明详情查看')->ApiSuccess( $responseJson );
			// return $responseJson->link;
		}else{
			Api()->setApi('msg',$responseJson->error->message)->ApiError( $responseJson->error );
		}
	}

	/**
	 * 电子签名完成合同下载
	 * 	 * @return [type] [description]
	 */
	public function getFileLink(){
		$applyNo = input('applyNo','APL939035302306123776','trim');
		//组建请求参数
		$requestObj=new FileLinkRequest();
		//* 签约编号
		$requestObj->applyNo = $applyNo;
		//请求
		$response=RopUtils::doPostByObj( $requestObj,$this->appkey, $this->secret, $this->service_url );
		//以下为返回的一些处理
		$responseJson=json_decode($response);
		
		wFile( $response ,'./jzq_log.php');//写文件

		if ( $responseJson->success == 'true' ) {
			Api()->setApi('msg','电子签名完成合同下载')->ApiSuccess( $responseJson );
			// return $responseJson->link;
		}else{
			Api()->setApi('msg',$responseJson->error->message)->ApiError( $responseJson->error );
		}
	}	

	/**
	 * 电子签名签署状态查询
	 * @return [type] [description]
	 */
	public function getSignStatus(){
		
		pdf( $content );die;
		$applyNo = input('applyNo','','trim');
		if( !$applyNo ) Api()->setApi('msg', '签约编号不能为空' )->ApiError();
		//组建请求参数
		$signatory=new Signatory();
		//* 证件类型
		$signatory->setSignatoryIdentityType(IdentityType::$IDCARD);
		//* 名称或公司名称
		$signatory->fullName = $this->fullName;
		//* 证件号码或营业执照号或社会信用号
		$signatory->identityCard = $this->identityCard;

		$requestObj =new SignStatusRequest();
		//* 签约编号
		$requestObj->applyNo = $applyNo;
		$requestObj->signatory=$signatory;
		//请求
		$response = RopUtils::doPostByObj( $requestObj,$this->appkey, $this->secret, $this->service_url );
		//以下为返回的一些处理
		$responseJson = json_decode($response);
		
		wFile( $response ,'./jzq_log.php');//写文件

		if ( $responseJson->success == 'true' ) {
			Api()->setApi('msg','电子签名签署状态查询')->ApiSuccess( $responseJson );
		}else{
			Api()->setApi('msg',$responseJson->error->subErrors[0]->message)->ApiError();
		}
	}
}
