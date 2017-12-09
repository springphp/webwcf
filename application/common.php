<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Image;
use think\Session;
use think\Url;
use think\Request;
use think\Db;
use extend\QRencode;
use extend\QRcode;


/* 
 * $name  string    为type="file"的input框的name值
 * $file string     存在图片的文件夹 (文件夹必须在upload之下)
 * return  string   返回图片的文件夹和名字
 */
function upload_img($name,$file){
    $up_dir = "./public/upload/$file";
    if (!file_exists($up_dir)) {
        mkdir($up_dir, 0777, true);
    }
    $image = Image::open(request()->file($name));//打开上传图片
    $size = input('avatar_data');//裁剪后的尺寸和坐标
    $size_arr=json_decode($size,true);
    $type= substr($_FILES[$name]['name'],strrpos($_FILES[$name]['name'],'.')+1);
    $name = time().".".$type;
    $info =$image->crop($size_arr['width'], $size_arr['height'],$size_arr['x'],$size_arr['y'])->save("./public/upload/$file/$name");
    if($info){
       return $file."/".$name;
    }else{
        return false;
    }
}
// 应用公共文件
function resultToArray(&$results){
    foreach ($results as &$result) {
        $result = $result->getData();
    }
}

function getTree($data,$options=[],$level=0){
    return new \extend\Tree($data,$options,$level);
}
function Api($type = '',$setApi=false){
    $app_debug = config('app_debug');
    $api = new \app\common\controller\Api($app_debug);
    return $api->setType($type,$setApi);
}
/**
 * 判断值是否为空
 */
function isValue($data,$key=false){
    if ($key !== false) {
        if(!is_array($data)) return false;
        if(!array_key_exists($key,$data)) return false;
        $v = $data[$key];
    } else {
        $v = $data;
    }
    if ($v === 0 || $v === '0') return true;
    if($v != '') return true;
    if (is_array($v) && $v !=[]) return true;
    return false;
}
function getNamebyPk($model,$pk_name,$getField,$pk_value){
    $data = model($model)->where([$pk_name=>$pk_value])->find();
    if($data){
        return  model($model)->where([$pk_name=>$pk_value])->find()->$getField;
    }else{
        return '--';
    }
    
}

/**
 * 获取图片用于显示
 */
function getImg($imgName,$isUrl=false){
    if ($isUrl) {
        $url = $imgName;
    } else {
        $url   = config('STATIC_URL').'/upload/'.$imgName;
        $url_t   = ROOT_PATH.'public/upload/'.$imgName;
    }
    if (!is_file($url_t)) {
        $url = config('static_url').'/upload/'.config('default_img');
        $url_t = ROOT_PATH.'public/upload/default.png';
        $url = is_file($url_t) ? $url : ROOT_PATH.'/public/upload/default.png';
    }
    return $url;
}

function get_login_user_name(){
    return session('user.nickname') ?:session('user.account');
}
function get_login_admin_group(){
    $group = session('user.group');
    if (!$group) { return;}
    $name = model('auth_group')->where(['group_id'=>$group])->value('group_name');
    return $name;
}

function buildRandomString($type=1,$length=4){
    if($type == 1){
        $chars = join("",range(0,9));
    }elseif($type == 2){
        $chars = join("",array_merge(range("a","z"),range("A","Z")));
    }elseif($type == 3){
        $chars = join("",array_merge(range("a","z"),range("A","Z"),range(0,9)));
    }
    $chars = str_shuffle($chars);
    return substr($chars,0,$length);
}

/**
 * tp5废弃的字母函数
 * @version 2017/08/28 [by iwater]
 */
function C($name = '', $value = null, $range = ''){
    return config($name, $value, $range );
}
function D($name = '', $layer = 'model', $appendSuffix = false){
    return model($name, $layer, $appendSuffix);
}
function M($name = '', $config = [], $force = true){
    return db($name, $config, $force);
}
function U($url = '', $vars = '', $suffix = true, $domain = false){
    return url($url, $vars, $suffix, $domain);
}
function W($name, $data = []){
    return widget($name, $data);
}
function I($key = '', $default = null, $filter = ''){
    return input($key, $default, $filter);
}

/**
 * 获取客户端IP地址
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @return mixed
 */
function get_client_ip($type = 0) {
    $type       =  $type ? 1 : 0;
    static $ip  =   NULL;
    if ($ip !== NULL) return $ip[$type];
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos    =   array_search('unknown',$arr);
        if(false !== $pos) unset($arr[$pos]);
        $ip     =   trim($arr[0]);
    }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip     =   $_SERVER['HTTP_CLIENT_IP'];
    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip     =   $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u",ip2long($ip));
    $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}

/**
 * 随机纯数字字符串
 * @param  [number] $length [字符串长度]
 * @return [string]         [字符串]
 * wanggang
 */
function make_code($length){
    $output='';
    for ($i = 0; $i < $length; $i++) {
        $output .= rand(0, 9); //生成php随机数
    }
    return $output;
}

/**
 * 生成订单
 * @param string $header [description]
 * @return [str] [description]
 * @author [iwater]  2017/09/28
 */
function set_order($header = 'kwd'){
    $order_no = $header.time().rand(0,9).rand(0,9);
    return $order_no;
}


if (!function_exists('urldo')) {
    /**
     * Url生成
     * @param string        $url 路由地址
     * @param string|array  $vars 变量
     * @param bool|string   $suffix 生成的URL后缀
     * @param bool|string   $domain 域名
     * @return string
     */
    function urldo($url = '', $vars = '', $suffix = true, $domain = true)
    {
        return Url::build($url, $vars, $suffix, $domain);
    }
}


//获取分页参数设置
function _pageconfig($listRows){
    config(['paginate'=>['type'      => 'bootstrap','list_rows' => $listRows,'var_page'  => 'page',]]);
    Session::set('pageSize', config('paginate.list_rows'));
}

function birthday1($age){
     // $age = strtotime($birthday); 
     if($age === false){ 
      return false; 
     } 
     list($y1,$m1,$d1) = explode("-",date("Y-m-d",$age)); 
     $now = strtotime("now"); 
     list($y2,$m2,$d2) = explode("-",date("Y-m-d",$now)); 
     $age = $y2 - $y1; 
     if((int)($m2.$d2) < (int)($m1.$d1))
      $age -= 1; 
     return $age; 
} 
function birthday($birthday){
  list($year,$month,$day) = explode("-",$birthday);
  $year_diff = date("Y") - $year;
  $month_diff = date("m") - $month;
  $day_diff  = date("d") - $day;
  if ($day_diff < 0 || $month_diff < 0)
   $year_diff--;
  return $year_diff;
}
//转换ueditor html to string
function html_to_str($str){
    $str  = strip_tags(html_entity_decode($str));
    $qian = array(" ","　","\t","\n","\r");
    $hou  = array("","","","","");
    $str = str_replace($qian,$hou,$str);
    return $str;
}

function get_income_type($type){
    switch ($type) {
        case '1':
            $incomeType = '借款';
            break;
        case '2':
            $incomeType = '投资';
            break;
        case '3':
            $incomeType = '认证';
            break;
        case '4':
            $incomeType = '邀约奖金';
            break;
        case '5':
            $incomeType = '提现';
            break;
        default:
            $incomeType = '不明';
            break;
    }
    return $incomeType;
}

function get_order_status($status){
    switch ($status) {
        case '1':
            $orderstatus = '待收款';
            break;
        case '2':
            $orderstatus = '未到期';
            break;
        case '3':
            $orderstatus = '已逾期';
            break;
        case '4':
            $orderstatus = '已还款';
            break;
        case '5':
            $orderstatus = '已结束';
            break;
        default:
            $orderstatus = '不明';
            break;
    }
    return $orderstatus;
}

function get_user_role($role){
    switch ($role) {
        case '0':
            $user_role = '普通会员';
            break;
        case '1':
            $user_role = '投资者';
            break;
        case '2':
            $user_role = '借贷者';
            break;
        default:
            $user_role = '不明';
            break;
    }
    return $user_role;
}
//1、Unix时间戳转日期  
function unixtime_to_date($unixtime, $timezone = 'PRC') {  
    $datetime = new DateTime("@$unixtime"); //DateTime类的bug，加入@可以将Unix时间戳作为参数传入  
    $datetime->setTimezone(new DateTimeZone($timezone));  
    return $datetime->format("Y-m-d H:i:s");  
}  
  
//2、日期转Unix时间戳  
function date_to_unixtime($date, $timezone = 'PRC') {  
    $datetime= new DateTime($date, new DateTimeZone($timezone));  
    return $datetime->format('U');  
}

function get_collection_status($status){
    switch ($status) {
        case '1':
            $collection_status = '待催收';
            break;
        case '2':
            $collection_status = '催收中';
            break;
        case '3':
            $collection_status = '催收完成';
            break;
        case '4':
            $collection_status = '拒绝催收';
            break;
        default:
            $collection_status = '不明';
            break;
    }
    return $collection_status;
}  

function get_tops(){
    $investid = model('member')->where('role = 1')->column('user_id');
    foreach ($investid as $key => $value) {
        $sum[$value] = model('order')->where("invest_id = {$value}")->sum('money');
    }
}

/**
 * 生成二维码图片
 * @param  string $url [description]
 * @return [type]      [description]
 */
function makeCodeImg($url='',$user_id='')
{
    $value = $url;                  //二维码内容  
    $errorCorrectionLevel = 'L';    //容错级别   
    $matrixPointSize = 5;           //生成图片大小    
    
    //生成二维码图片  
    $filename = './upload/qrcode/kwd_qrcode.jpg';  
    QRcode::png($value,$filename , $errorCorrectionLevel, $matrixPointSize, 2);    
    $QR = $filename;                //已经生成的原始二维码图片文件    
    $QR = imagecreatefromstring(file_get_contents($QR));    
   
    //输出图片  
    imagepng($QR, 'kwd_qrcode.jpg');    
    imagedestroy($QR);  
    if( config('app_debug')  == true ) {
        $url = Request::instance()->domain()."/kwd_qrcode.jpg?user_id=".$user_id;
    }else{
        $url = Request::instance()->domain()."/kawadai/kwd_qrcode.jpg?user_id=".$user_id;
    }
    return $url;
}

/**
 * 获取配置文件值
 * @param  [type] $config_mark [description]
 * @return [type]              [description]
 */
function getconfigs($config_mark){
    $where['config_mark'] = $config_mark;
    return db('config')->where($where)->value('config_value');
}

/**
 * 写文件，api调试用
 */
function _log() {
    $array = ['get'=>$_GET,'post'=>$_POST,'file'=>$_FILES];
    // file_put_contents('./api.log',var_export($array,true));
    $myfile = fopen("./api.log", "w") or die("Unable to open file!");
    fwrite($myfile, var_export($array,true));
    fclose($myfile);
    // fwrite('./api.log',var_export($array,true));
}

/**
 * 验证手机号码格式
 * @param  string $mobile [description]
 * @return [type]         [description]
 */
function checkMobile( $mobile = ''){
    if( !preg_match("/^1[34578]\d{9}$/", $mobile)){
       return false;
    }else{
        return true;
    }
}


/**
 * 获取第三方接口
 * @param  [type] $url    [description]
 * @param  [type] $data   [description]
 * @param  array  $header [description]
 * @return [type]         [description]
 */
function curl_send_post($url,$data,$header = array()){
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        // CURLOPT_POSTFIELDS =>json_encode($data,JSON_UNESCAPED_UNICODE),
        CURLOPT_POSTFIELDS =>$data,
        CURLOPT_SSL_VERIFYPEER => false, // 跳过证书检查
        CURLOPT_SSL_VERIFYHOST => CURLOPT_SSL_VERIFYHOST,
        CURLOPT_HTTPHEADER =>$header
    ));
    $res = curl_exec($curl);
    curl_close($curl);
    return json_decode($res,true);
}

/**
 * 获取第三方接口(get)
 * @param  [type] $url    [description]
 * @param  [type] $data   [description]
 * @param  array  $header [description]
 * @return [type]         [description]
 */
function curl_send_get($url,$header = []){
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        // CURLOPT_POSTFIELDS =>json_encode($data,JSON_UNESCAPED_UNICODE),
        CURLOPT_SSL_VERIFYPEER => false, // 跳过证书检查
        CURLOPT_SSL_VERIFYHOST => CURLOPT_SSL_VERIFYHOST,
        CURLOPT_HTTPHEADER =>$header
    ));
    $res = curl_exec($curl);
    curl_close($curl);
    return json_decode($res,true);
}

/**
 * 获取ips 交易虚拟号
 * @param  [type] $user_id [description]
 * @return [type]          [description]
 */
function getIpsAcctNo($user_id){
    $account = model('member')->where('user_id',$user_id)->value('ipsAcctNo');
    return $account;
}


 //写文件
 function wFile( $data=[],$filePath = './log.php' ){
    if( empty($data) ) {
        $data = $_SERVER;
    }
    $f = fopen($filePath, 'a+');
    fwrite($f, var_export($data,true));
    fclose($f);
 }


 /**
 * 生成pdf
 * @param  string $html      需要生成的内容
 */
function pdf($html='<h1 style="color:red">hello word</h1>' ,$pdfName = './prototol_pdf/test.pdf'){
    vendor('Tcpdf.tcpdf');
    $pdf = new \Tcpdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    // 设置打印模式
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Nicola Asuni');
    $pdf->SetTitle('TCPDF Example 001');
    $pdf->SetSubject('TCPDF Tutorial');
    $pdf->SetKeywords('TCPDF, PDF, example, test, guide');
    // 是否显示页眉
    $pdf->setPrintHeader(false);
    // 设置页眉显示的内容
    $pdf->SetHeaderData('logo.png', 60, 'baijunyao.com', '白俊遥博客', array(0,64,255), array(0,64,128));
    // 设置页眉字体
    $pdf->setHeaderFont(Array('dejavusans', '', '12'));
    // 页眉距离顶部的距离
    $pdf->SetHeaderMargin('5');
    // 是否显示页脚
    $pdf->setPrintFooter(true);
    // 设置页脚显示的内容
    $pdf->setFooterData(array(0,64,0), array(0,64,128));
    // 设置页脚的字体
    $pdf->setFooterFont(Array('dejavusans', '', '10'));
    // 设置页脚距离底部的距离
    $pdf->SetFooterMargin('10');
    // 设置默认等宽字体
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    // 设置行高
    $pdf->setCellHeightRatio(1);
    // 设置左、上、右的间距
    $pdf->SetMargins('10', '10', '10');
    // 设置是否自动分页  距离底部多少距离时分页
    $pdf->SetAutoPageBreak(TRUE, '15');
    // 设置图像比例因子
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
        require_once(dirname(__FILE__).'/lang/eng.php');
        $pdf->setLanguageArray($l);
    }
    $pdf->setFontSubsetting(true);
    $pdf->AddPage();
    // 设置字体
    $pdf->SetFont('stsongstdlight', '', 14, '', true);
    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
    $res = $pdf->Output('prototol.pdf', 'S');
    file_put_contents( $pdfName, $res);
}
        









 