<?php
namespace app\home\api;
use think\Model;
use think\Request;
use think\Session;
use think\Db;
use extend\Upload;
use think\Cache;
use extend\Encrypt;
use extend\Crypt3Des;


/*
* api基础类
*/
class BaseApi extends Model
{
  private $allow_fun = ['setpwd','login','register','sendsms','callserver','getarticles','addvideo','applymember','getbanners','jzq'];//,'checkuser','checkmobile'
  private $post = ['login','register','setpwd','sendsms','addloan','isbackmoney','addbankcard','addloan','commpayment','commrealpayment',
                   'breakloan','checkinvestment','checkuser','checkrelation','callovermember','addinvest','addvideo','addrelative','ipspay'];

  public function __construct(){
      $this->islogin(); 
      $this->isPost();
      // $this->checkApi();
 }

  //加密处理api访问
	final protected  function checkApi(){
        $submit = input('submit',0);
        $code = config('web_api_encode');  //必须有算法，服务器支持
        if(!$code)  Api()->setApi('msg','秘钥不能为空')->ApiError();
        $vcode = md5($code); //c9038c2a29cd5383464cdb8930222831
        if ($submit !== $vcode) {
            Api()->setApi('msg','非法访问')->ApiError();
        } 
	}

  /*
  * 登录判断
  */
  final protected  function islogin(){
      $act_name = input('act');
      $act_name = strtolower(trim($act_name));
      if(!in_array($act_name, $this->allow_fun)){
          $sessionid = $this->getMd5Code();
          // dump($sessionid);die;
          
          $loginloginfo = model('loginlog')->field('id,create_time')->where('session_id',$sessionid)->order('create_time desc')->find() ;
          if( empty($loginloginfo['id']) ) Api()->setApi('msg','没有授权,非法访问')->ApiError(); //验证session_id 的合法性
          
          session_id("$sessionid");
          // session_start();
          
          if( !session('kwd_app_islogin') ) {
              Api()->setApi('msg','未登录,无权访问')->ApiError();
          }

          $this->overtime_downlogin( $loginloginfo['id'],strtotime($loginloginfo['create_time']) ); //设置过期时间
      }
  }

  /**
   * 获取加密访问接口 User-Agents
   * @return [type] [description]
   */
  public function getMd5Code(){
      $sessionid = Request::instance()->header('User-Agents');  //存放header头
      if(empty($sessionid)) Api()->setApi('msg','User-Agents不能为空')->ApiError();
      $sessionid = substr($sessionid,0,-24);
      $sessionid = substr($sessionid,32);

      return $sessionid;
  }

   /**
   * 设置登录超时，下线
   */
  final protected function overtime_downlogin($id,$logintime){
      $overtime = time() - $logintime;
      // dump( $overtime > config('overtime_downlogin') );die;
      if( $overtime > config('overtime_downlogin') ) { //有限期 3 天
          model('loginlog')->edit_log(['id'=>$id,'delete_time'=>time(),'type'=>2]);
          //清楚session    
          session('kwd_app_islogin',null);
          session('kwd_app_user',[]); 
      }
  }

  /**
   * 设置Api
   */
  final protected function setApis(){
      // 允许任意域名发起的跨域请求  
      header("Access-Control-Allow-Origin: *"); 
      header('Access-Control-Allow-Headers: X-Requested-With,X_Requested_With');

      $data = input();
      $act_name = $data['act'];
      !method_exists($this,$act_name)
      && ($ajax = Api()->setApi('msg','接口错误')->ApiError())
      || ($ajax = $this->$act_name($data));
      echo json_encode($ajax);
      die();
  }

    /**
   * 发送支付验证码
   * @param  [type] $tel  [description]
   * @param  [type] $code [description]
   * @return [type]       [description]
   */
  protected function send_pay_sms( $tel,$code ){
      $content = '如非本人，请忽略此条消息。您的支付验证码：'.$code.',请在60s内完成验证。';
      $rs = $this->sendMessage($tel,$content);//发送短信验证码
      if($rs != 1) {
          Api()->setApi('msg','发送失败')->ApiError();
      }else{
          return time();
      }
  }


  /**
   * 发送验证码
   * @param  [type] $tel  [description]
   * @param  [type] $code [description]
   * @return [type]       [description]
   */
  protected function send_sms($tel,$code){
      $content = '如非本人，请忽略此条消息。您的验证码：'.$code.',请在60s内完成验证。';
      $rs = $this->sendMessage($tel,$content);//发送短信验证码
      if($rs != 1) Api()->setApi('msg','发送失败')->ApiError();
      Api()->setApi('msg','发送成功')->ApiSuccess();
  }

  /**
   * 发送短信
   * @param  string $tel     [description]
   * @param  string $content [description]
   * @return [type]          [description]
   */
  protected function send_msg($tel = '',$content = '')
  {
      if( empty($tel) ) Api()->setApi('msg','电话号码不能为空')->ApiError();
      if( empty($content) ) Api()->setApi('msg','短信内容不能为空')->ApiError();

      $rs = $this->sendMessage($tel,$content);//发送短信验证码
      // if($rs != 1) Api()->setApi('msg','发送失败')->ApiError();
  }

  /*
  * 发送短信--第三方Api
  * @param  [string] $tel     手机号
  * @param  [type] $content   短信内容
  * @return [type]            发送状态
  */
  final protected function sendMessage($tel,$content){
      $url        = "http://www.ztsms.cn/sendSms.do";//提交地址
      $username   = 'liesunsmszh';//用户名
      $password   =  'CUzaqLS7';//原密码
      vendor('sendAPI.sendAPI');
      $sendAPI = new \sendAPI($url, $username, $password);
      $data = array(
          'content'   => $content.'【咔哇贷】',//短信内容
          'mobile'    => $tel,//手机号码
          'productid' => '676767',//产品id
          'xh'        => ''//小号
      );
      $sendAPI->data = $data;//初始化数据包
      $return = $sendAPI->sendSMS('POST');//GET or POST
      return $return;
  }



 /* 公共方法*/

  //验证接口访问方式
  public function isPost(){
      $act_name = input('act');
      $act_name = strtolower(trim($act_name));
      if(in_array($act_name,$this->post)){
          if(!request()->isPost()){
              Api()->setApi('msg','非法访问')->ApiError(); //非法访问 --  请选择post提交方式
          }
      }
  }

  //验证验证码
  public function checkCode($vcode){
      if(empty($vcode)) Api()->setApi('msg','验证码不能为空')->ApiError();
      $old_code = db('config')->where("config_mark = 'WEB_VERIFY'")->value('config_value');
      if($old_code != $vcode) Api()->setApi('msg','验证码错误')->ApiError();
  }

  //生产验证码
  public function set_code($length,$field){
      $configs = db('config')->where("config_mark = '{$field}'")->find();
      $update_time = $configs['update_time'];
      if(time()-$update_time>60 || !$configs['config_value']){
          $output='';
          for ($i = 0; $i < $length; $i++) {
              $output .= rand(0, 9); //生成php随机数
          }
          Db::table('kd_config')->where("config_mark = '{$field}'")->update(['config_value'=>$output,'update_time'=>time()]);
          return $output;
      }else{
          return $configs['config_value'];
      }
  }

  //获取登录用户的类型
  public function getUserType($username){
      if( ctype_digit($username)){  //ctype_digit()验证字符串是否为纯数字
          $field = 'mobile';
          if (!preg_match('/(^(13\d|15[^4\D]|17[13678]|18\d)\d{8}|170[^346\D]\d{7})$/', $username)) 
          Api()->setApi('msg','手机号错误')->ApiError();
      }else{
          $field = 'realname';
      }
      return $field;
  }

  //上传图片 单张上传
  public function upload_one($filepath = '')
  {
     $upload = new Upload();
     $result = $upload->uploadImg($filepath);
     return $result;
  }

  /**
   * 获取查询添加--》逾期催收 筛选
   * @param  [type] $type [description]
   * @return [type]       [description]
   */
  public function get_where_type($type){
      switch ($type) {
              case '1':
                  $where = ['BETWEEN',[1,7]];
                  break;
              case '2':
                  $where = ['BETWEEN',[8,15]];
                  break;
              case '3':
                  $where = ['BETWEEN',[16,30]];
                  break;  
              default:
                  $where = ['egt',31];
                  break;
          }
      return $where;
  }

  /**
   * 计算投资收益
   * @param  [str] $orderid [投资记录id]
   * @return [str]          [description]
   */
  public function get_invest_profit($orderid){
      $field = 'interest,overdue_money';
      $result = model('order')->field($field)->where('id',$orderid)->find();
      return $result['interest'] + $result['overdue_money'];
  }

  /**
   * 计算借款利息 type:1 / 计算逾期利息 type:2
   * @param  [numeric(10,2)] $money   [本金]
   * @param  [int] $term    [借款期限]
   * @param  [int] $type    [利息种类]
   * @return [str]          [利息]
   */
  
  public function get_interest_profit($money,$term,$type = 1){
      if($type == 1){
          $config_mark = 'INTEREST_RATE';
      }elseif($type ==2){
          $config_mark = 'INTEREST_OVERDUE_RATE';
      }
      $lx = db('config')->where("config_mark = '{$config_mark}'")->value('config_value');
      return $money*$lx*$term*0.01;
  }

  /**
   * 获取还款日期
   * @param  [type] $overtime [description]
   * @param  [type] $term     [description]
   * @return [array]           [description]
   */
  public function get_over_time($overtime,$term){
      $time = $overtime+$term*24*3600;
      $overtime = time() - $time;
      if($overtime<0) $overtime = 0;
      $overday = date('d',$overtime)-1;
      $overtime = date('Y-m-d',$time);
      return ['overday'=>$overday,'overtime'=>$overtime];
  }

  /**
   * 获取 user_id
   * @param  string $userid [description]
   * @return [type]         [description]
   */
  public function get_userid($userid = ''){
      $user_id = !empty($userid)?$userid:session('kwd_app_user.user_id');
      if( !$user_id ) {
          $sessionid = Request::instance()->header('uid');
          $user_id = model('loginlog')->where(['session_id'=>$sessionid])->value('user_id');
      }
      return $user_id;
  }

  /**
   * 添加邀请记录
   * @param [type] $userid [description]
   */
  public function add_user_income_info( $userid ){
      $fee = getconfigs('INTEREST_INVITE');
      $data = [
          'user_id'   => $userid,
          'money'     => $fee,
          'type'      => 5
      ];
      model('user_income')->add_income($data);
      model('member')->where('user_id', $userid)->setInc('account_balance', $fee);
  }


  /**
   * 获取第三方接口
   * @param  [type] $url    [description]
   * @param  [type] $data   [description]
   * @param  array  $header [description]
   * @return [type]         [description]
   */
  public function curl_post($url,$data,$header = array()){
      $curl = curl_init();
      curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS =>json_encode($data,JSON_UNESCAPED_UNICODE),
          CURLOPT_SSL_VERIFYPEER => false, // 跳过证书检查
          CURLOPT_SSL_VERIFYHOST => CURLOPT_SSL_VERIFYHOST,
          CURLOPT_HTTPHEADER =>$header
      ));
      $res = curl_exec($curl);
      curl_close($curl);
      return $res;
  }

  public function getSecrectInfo($string = '',$first = 6,$last = 4){
      $string = trim( $string );
      $len = strlen( $string );
      $front6 = substr( $string , 0 ,$first );
      $last4 = substr( $string , -$last );
      $maxLen = $first + $last;
      if( $len > $maxLen ) {
          $str = $front6.str_repeat('*', $len - $maxLen ).$last4;
      }else{
          $str = $string;
      }
      return $str;
  }

  public function getBase64Date( $file_path= '' ){
      $file = file_get_contents($file_path);
      $base = base64_encode($file);
      return $base;
  }

  /**
   * 获取商户画像信息
   * @param  string $id_no [description]
   * @return [type]        [description]
   */
  public function get_user_head_info( $id_no = '' )
  {
      $pubkey = "8fee186a-9241-4b1b-aa1e-256501fa875f"; //第三方配置
      $secretkey = "7ff1596e-d8ae-4796-a3cf-75ac1c4d5454"; //第三方配置
      $product_code = "Y1001005"; //第三方配置

      $out_order_id = 'kwd'.rand(0,9).date('YmdHis').rand(10,99);
      $data['id_no'] = $id_no;

      $str = json_encode($data,JSON_UNESCAPED_UNICODE);
      $signature = md5(time() . mt_rand(0,1000)).'/signature/'.strtoupper(md5($str."|".$secretkey));
      $url = 'https://api4.udcredit.com/dsp-front/4.1/dsp-front/default/pubkey/'.$pubkey.'/product_code/'.$product_code.'/out_order_id/'.$out_order_id.'/signature/'.$signature ;

      $header = ['Content-Type: application/json; charset=utf-8'];
      $res = curl_send_post($url,$data,$header);

      Api()->setApi('msg','')->ApiSuccess( $res );
  }

  /**
   * 获取Ips 3des加密 请求参数
   * @param  [type] $request [description]
   * @return [type]          [description]
   */
  public function get_3des_encrypt( $request ){
      $request = json_encode( $request );
      $enc = new Crypt3Des();
      $key = config('kwd_app_pay.key');
      $vi  = config('kwd_app_pay.vi');
      $request = $enc->encrypt( $request,$key,$vi,$base64 = true );
      return $request;
  }

   /**
   * 获取Ips 3des解密 请求参数
   * @param  [type] $request [description]
   * @return [type]          [description]
   */
  public function get_3des_decrypt( $request ){
      $enc = new Crypt3Des();
      $key = config('kwd_app_pay.key');
      $vi  = config('kwd_app_pay.vi');
      $request = $enc->decrypt( $request,$key,$vi,$base64 = true );
      return $request;
  }

  public function destory_login(){
      //清楚session    
      session('kwd_app_islogin',null);
      session('kwd_app_user',null); 
  }

  /**
   * 获取ips 第三方接口数据
   * @param  [type] $url  [description]
   * @param  [type] $post [description]
   * @return [type]       [description]
   */
  public function getIpsApiDate( $url, $post ,$user_id , $operationType,$kwdKey )  
  {  
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
      $res    = file_get_contents($url, false, stream_context_create($context));  
      $result = (array)json_decode($res);
      
      $result['response'] = $this->get_3des_decrypt($result['response']);

      //写入日志
      $result['user_id'] = $user_id;
      $result['operationType'] = $operationType;
      $result['create_time'] = time();
      $result['kwdKey'] = $kwdKey;
      // dump($result);die;
      $sign = db('ipslog')->column('sign'); //避免重复录入
      if( !in_array($result['sign'],$sign) ) {
          try{
              Db::table('kd_ipslog')->insert($result);
          }catch (\Exception $e) {
              Api()->setApi('msg',$e->getMessage())->ApiError();
          }
      }
      $result['response'] = json_decode($result['response']);
      return $result;
  }

  public function getHttp($url){
      if(strpos('https', $url) === false) {
          $url = str_replace('https', 'http', $url);
      }
      return $url;
  }

  //处理链接url https->http 绝对路径url
  public function getUploadUrl( $filename ){
    if( config('app_debug') == true ) {
        $filename = Request::instance()->domain().'/kawadai/public/upload'.$filename;
    }else{
        $filename = Request::instance()->domain().'/public/upload'.$filename;
    }
    return $this->getHttp( $filename );
  }

}

