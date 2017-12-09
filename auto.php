<?php
// require_once './connet.php';
date_default_timezone_set('PRC');
header("Content-Type:text/html;charset=UTF-8");
//定义一组用于链接的参数
define("MYSQL_SERVER", "kwd.cdttd.com:3306");
define("MYSQL_USER", "kwdcdttdcom");
define("MYSQL_PASSWORD", "kwdcdttdcom");
define("MYSQL_DATABASE", "kwdcdttdcom");//要链接并使用的数据库名
define("MYSQL_ENCODE", "UTF8");
/**
 * 获取一个mysql的连接 ，返回这个连接，使用的是默认的设置
 */
function getMysqlConnection(){
    //链接数据库
    $conn=@mysqli_connect(MYSQL_SERVER,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE) or die("链接mysql数据库失败。错误信息：".mysqli_error($conn));
    // var_dump($conn);die;
    // @mysqli_select_db($coon,MYSQL_DATABASE) or die ("无法找到指定的数据库：".MYSQL_DATABASE." 错误信息：".mysqli_error($conn));
    // mysqli_query('SET NAMES '.MYSQL_ENCODE,$conn) or die('字符集设置错误'.mysqli_error($conn));
    return $conn;
}
//获取待检测 订单列表
function get_order(){
	$sql = "SELECT * FROM `kd_order` WHERE (`status`='2' or `status`='3') AND `is_done`='1'";
	$conn=getmysqlConnection();
	$results = array();
	$i = 0;
	foreach($conn->query($sql) as $row) {
	    $results[$i] = $row;
	    $i++;
	}
	return $results;
}
//判断是否逾期，改状态算罚息
function is_overdue(){
	$orders = get_order();
	foreach ($orders as $key => $value) {
		//已经处于逾期状态，计算罚息
		$overdue_time = $value['invest_time'] + $value['term']*86400;
		if($value['status'] == '3'){
			$interest     = get_interest($overdue_time,$value['money']);
			$sql_overdue  = "update kd_order set overdue_money=".$interest." where id=".$value['id'];
			$conn         = getmysqlConnection();
			$up_order=mysqli_query($conn,$sql_overdue) or die("修改订单sql语句执行出错1：".mysqli_error($conn));
		}
		//未到期，判断是否逾期，改状态算罚息
		if($value['status'] == '2'){
			if(time()>$overdue_time){
				$interest = get_interest($overdue_time,$value['money']);
				$sql_order = "update kd_order set is_overdue=1,status=3,overdue_money=".$interest." where id=".$value['id'];
				$conn    = getmysqlConnection();
				$up_order=mysqli_query($conn,$sql_order) or die("修改订单sql语句执行出错2：".mysqli_error($conn));
				
			}
		}
	}
}
//计算逾期利息
function get_interest($overdue_time,$money){
	$overdue_day = ceil((time() - $overdue_time)/86400);
	$sql         = "SELECT config_value FROM `kd_config` WHERE `config_mark`='INTEREST_OVERDUE_RATE'";
	$conn = getmysqlConnection();
	$rate_re     = mysqli_query($conn,$sql) or die("查询余额sql语句执行出错：".mysqli_error($conn));
	$rate        = mysqli_fetch_array($rate_re,MYSQLI_ASSOC);
	$interest    = $money*$overdue_day * $rate['config_value']/100;
	return $interest;
}
is_overdue();
echo 222;
