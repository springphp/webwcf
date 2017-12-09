<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:68:"E:\phpStudy\WWW\project\kawadai\public\theme\home\article\index.html";i:1512747924;s:68:"E:\phpStudy\WWW\project\kawadai\public\theme\home\layout\layout.html";i:1512747924;s:68:"E:\phpStudy\WWW\project\kawadai\public\theme\home\layout\static.html";i:1512747924;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="renderer" content="webkit">
    <title>文章页</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <!-- 自定义样式 -->
<link href="<?php echo $css; ?>global.css" rel="stylesheet">
<link href="<?php echo $css; ?>style.css" rel="stylesheet">
<link href="<?php echo $css; ?>weui.css" rel="stylesheet">
 
    <style>
        #ioswrap{
			width: 92%;
			margin: 0 auto;
			padding:0;
			font-size: 12px;
		}
        
        .weui_cells {
            margin-top: 1.17647059em;
            background-color: #FFFFFF;
            line-height: 1.41176471;
            font-size: 14px;
            width: 100% !important;
        }
    </style>
</head>
<body style=' background: rgb(244, 242, 240);white-space: normal;font-family: '微软雅黑';'>    <!-- rgb(244, 242, 240) -->
   
<div id="ioswrap" style='padding: 2% 0;<?php if($articles['type'] == 3): ?>width: 100%;<?php endif; ?>'>
	<div class="ios-block">
		<?php if($articles['type'] == 1): ?>
			<p>
				<span style=";font-family:微软雅黑;color:rgb(0,176,240);font-size:19px"><span style="font-family:微软雅黑"><br/></span></span>
			</p>
			<p style="line-height: 28px;">
			    <span style=";font-family:微软雅黑;font-size:14px"><span style="font-family:微软雅黑">编号：<span id="orderid" style="text-decoration:underline"></span></span></span>
			</p>
			<p style="line-height: 28px;">
			    <span style=";font-family:微软雅黑;font-size:14px"><span style="font-family:微软雅黑">甲方（出借方）：</span></span>
			</p>
			<p style="line-height: 28px;">
			    <span style=";font-family:微软雅黑;font-size:14px"><span style="font-family:微软雅黑">身份证号码：</span></span>
			</p>
			<p style="line-height: 28px;">
			    <span style=";font-family:微软雅黑;font-size:14px"><span style="font-family:微软雅黑">甲方手机号：</span></span>
			</p>
			<p style="line-height: 28px;">
			    <span style=";font-family:微软雅黑;font-size:14px">&nbsp;</span>
			</p>
			<p style="line-height: 28px;">
			    <span style=";font-family:微软雅黑;font-size:14px"><span style="font-family:微软雅黑">乙方（借入方）：<span id="username" style="text-decoration:underline"></span></span></span>
			</p>
			<p style="line-height: 28px;">
			    <span style=";font-family:微软雅黑;font-size:14px"><span style="font-family:微软雅黑">身份证号码：<span id="sfz" style="text-decoration:underline"></span></span></span>
			</p>
			<p style="line-height: 28px;">
			    <span style=";font-family:微软雅黑;font-size:14px"><span style="font-family:微软雅黑">乙方手机号：<span id="phone" style="text-decoration:underline"></span></span></span>
			</p>
			<p style="line-height: 28px;">
			    <span style=";font-family:微软雅黑;font-size:14px">&nbsp;</span>
			</p>
			
		<?php endif; ?>
		<p><?php echo (isset($articles['config_value']) && ($articles['config_value'] !== '')?$articles['config_value']:''); ?></p>
	</div>
</div>

<?php if($articles['type'] == 3): ?>
	<script >
		var arr=Array.prototype.slice.call(document.getElementsByClassName('page'));
		for(var i in arr){
			arr[i].style.width='100%';
		}
		// var arrWidth=Array.prototype.slice.call(document.getElementsByClassName('page_width'));
		// for(var iw in arrWidth){
		// 	arr[iw].style.width='50%';
		// }
	</script>
	<style>
		body {
			background: #fff!important;
		}
	</style>
<?php endif; ?>






</body>
</html>


