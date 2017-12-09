<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:68:"E:\phpStudy\WWW\project\kawadai\public\theme\admin\rating\index.html";i:1512750375;s:69:"E:\phpStudy\WWW\project\kawadai\public\theme\admin\layout\layout.html";i:1512750373;s:69:"E:\phpStudy\WWW\project\kawadai\public\theme\admin\layout\static.html";i:1512750373;s:65:"E:\phpStudy\WWW\project\kawadai\public\theme\admin\layout\js.html";i:1512750373;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="renderer" content="webkit">
    <title><?php echo $admin_title; ?></title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <!-- 引入公共css/js -->
<!-- 字体图标 -->
<!-- <link rel="shortcut icon" href="<?php echo $public_path; ?>favicon.ico"> -->
<link href="<?php echo $static_path; ?>css/font-awesome.min.css" rel="stylesheet">
<!-- JQuery -->
<script src="<?php echo $static_path; ?>js/jquery.min.js"></script>
<script src="<?php echo $static_path; ?>plugins/metisMenu/jquery.metisMenu.js"></script>

<!-- bootstrap -->
<link href="<?php echo $static_path; ?>plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<script src="<?php echo $static_path; ?>plugins/bootstrap/js/bootstrap.min.js"></script>

<!-- 自定义样式 -->
<link href="<?php echo $css; ?>animate.css" rel="stylesheet">
<link href="<?php echo $css; ?>style.css" rel="stylesheet">
<link href="<?php echo $css; ?>about.css" rel="stylesheet">

<!-- checkbox 和radio 美化 -->
<link href="<?php echo $static_path; ?>css/input.css" rel="stylesheet">
<!-- <link href="<?php echo $static_path; ?>/css/checkbox.css" rel="stylesheet">
<script src="<?php echo $static_path; ?>/js/checkbox.js"></script> -->

<!-- select 美化 -->
<link href="<?php echo $static_path; ?>css/select.css" rel="stylesheet">
<link href="<?php echo $static_path; ?>plugins/jquery/scrollbar.css" rel="stylesheet">
<script src="<?php echo $static_path; ?>plugins/jquery/scrollbar.js"></script>
<script src="<?php echo $static_path; ?>js/select.js"></script>

<link href="<?php echo $static_path; ?>css/common.css" rel="stylesheet">
<!-- ueditor -->
<link href="<?php echo $static_path; ?>plugins/ueditor/themes/default/css/ueditor.css" rel="stylesheet">
    <style>
        .layout-return-btn{
            position: relative;
            top: -8px;
            left: -6px;
            margin: 0!important;
        }
        body{
            height: 1vh;
        }
    </style>
</head>
<body class="gray-bg  animated fadeIn">
 
    <div class="wrapper wrapper-content ibox float-e-margins" >
        <div class="ibox-title visible-lg">
            <!-- <h5> -->
                <ul class="breadcrumb inline pull-left" >
                    <li><?php echo $menu['pmenu']; ?></li>
                    <li><?php echo $menu['menu_name']; ?></li>
                </ul>
            <!-- </h5> -->
            <a class="pull-right btn btn-default btn-xs" title="刷新当前" href=""><i class="fa fa-refresh"></i></a>
        </div>
        <div class="ibox-content">
<style>
	#about_input{
		width: 150px;
	}
	form{
		padding:30px;
	}
</style>
<form class="form-horizontal js-ajax-form clearfix form-inline" action='<?php echo url('admin/Interest/index'); ?>' method='post'>
<div class="table-responsive">
    <table class="table table-hover table-bordered table-condensed">
	    <thead>
	        <tr>
	            <th width="100%">通话记录等级设置</th>
	        </tr>
	    </thead>
	    <tbody>
			<tr>
	            <td>
					<div class="form-group" style="margin: 10px 30px;">
	            		<label for="about_input">A 级范围</label>
			            <input type="text" name="T_RATING_A_LOSS" class="form-control " id="about_input" value="<?php echo (isset($interest['T_RATING_A_LOSS']) && ($interest['T_RATING_A_LOSS'] !== '')?$interest['T_RATING_A_LOSS']:''); ?>" placeholder="500.00" >次 至
			            <input type="text" name="T_RATING_A_HIGH" class="form-control " id="about_input" value="<?php echo (isset($interest['T_RATING_A_HIGH']) && ($interest['T_RATING_A_HIGH'] !== '')?$interest['T_RATING_A_HIGH']:''); ?>" placeholder="5,000.00">次
			        </div>
			        <div class="form-group" style="margin: 10px 50px;">
	            		<label for="about_input">B 级范围</label>
			            <input type="text" name="T_RATING_B_LOSS" class="form-control" id="about_input" value="<?php echo (isset($interest['T_RATING_B_LOSS']) && ($interest['T_RATING_B_LOSS'] !== '')?$interest['T_RATING_B_LOSS']:''); ?>" placeholder="1">次 至
			            <input type="text" name="T_RATING_B_HIGH" class="form-control" id="about_input" value="<?php echo (isset($interest['T_RATING_B_HIGH']) && ($interest['T_RATING_B_HIGH'] !== '')?$interest['T_RATING_B_HIGH']:''); ?>" placeholder="30">次
			        </div>
			        <div class="form-group" style="margin: 10px 50px;">
	            		<label for="about_input">C 级范围</label>
			            <input type="text" name="T_RATING_C_LOSS" class="form-control" id="about_input" value="<?php echo (isset($interest['T_RATING_C_LOSS']) && ($interest['T_RATING_C_LOSS'] !== '')?$interest['T_RATING_C_LOSS']:''); ?>" placeholder="1">次 至
			            <input type="text" name="T_RATING_C_HIGH" class="form-control" id="about_input" value="<?php echo (isset($interest['T_RATING_C_HIGH']) && ($interest['T_RATING_C_HIGH'] !== '')?$interest['T_RATING_C_HIGH']:''); ?>" placeholder="0">次
			        </div>
	            </td>
	        </tr>
	    </tbody>
    </table>
</div>
<div class="table-responsive">
    <table class="table table-hover table-bordered table-condensed">
	    <thead>
	        <tr>
	            <th width="100%">金融机构通话记录等级设置</th>
	        </tr>
	    </thead>
	    <tbody>
			<tr>
	            <td>
					<div class="form-group" style="margin: 10px 30px;">
	            		<label for="about_input">A 级范围</label>
			            <input type="text" name="JT_RATING_A_LOSS" class="form-control " id="about_input" value="<?php echo (isset($interest['JT_RATING_A_LOSS']) && ($interest['JT_RATING_A_LOSS'] !== '')?$interest['JT_RATING_A_LOSS']:''); ?>" placeholder="500.00" >次 至
			            <input type="text" name="JT_RATING_A_HIGH" class="form-control " id="about_input" value="<?php echo (isset($interest['JT_RATING_A_HIGH']) && ($interest['JT_RATING_A_HIGH'] !== '')?$interest['JT_RATING_A_HIGH']:''); ?>" placeholder="5,000.00">次
			        </div>
			        <div class="form-group" style="margin: 10px 50px;">
	            		<label for="about_input">B 级范围</label>
			            <input type="text" name="JT_RATING_B_LOSS" class="form-control" id="about_input" value="<?php echo (isset($interest['JT_RATING_B_LOSS']) && ($interest['JT_RATING_B_LOSS'] !== '')?$interest['JT_RATING_B_LOSS']:''); ?>" placeholder="1">次 至
			            <input type="text" name="JT_RATING_B_HIGH" class="form-control" id="about_input" value="<?php echo (isset($interest['JT_RATING_B_HIGH']) && ($interest['JT_RATING_B_HIGH'] !== '')?$interest['JT_RATING_B_HIGH']:''); ?>" placeholder="30">次
			        </div>
			        <div class="form-group" style="margin: 10px 50px;">
	            		<label for="about_input">C 级范围</label>
			            <input type="text" name="JT_RATING_C_LOSS" class="form-control" id="about_input" value="<?php echo (isset($interest['JT_RATING_C_LOSS']) && ($interest['JT_RATING_C_LOSS'] !== '')?$interest['JT_RATING_C_LOSS']:''); ?>" placeholder="1">次 至
			            <input type="text" name="JT_RATING_C_HIGH" class="form-control" id="about_input" value="<?php echo (isset($interest['JT_RATING_C_HIGH']) && ($interest['JT_RATING_C_HIGH'] !== '')?$interest['JT_RATING_C_HIGH']:''); ?>" placeholder="0">次
			        </div>
	            </td>
	        </tr>
	    </tbody>
    </table>
</div>
<div class="form-group clearfix about_buttun">
    <label for="inputPassword3" class="col-sm-2 control-label"></label>
    <div class="col-sm-9">
        <button type="submit" class="btn btn-info js-submit-btn mr_3px">确认保存</button>&nbsp;&nbsp;&nbsp;
        <button type="reset" class="btn btn-info">放弃</button>
    </div>
</div>
</form>
        </div>
    </div>
</body>
<!-- 全局js -->
<script src="<?php echo $static_path; ?>plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- 第三方插件，加载进度条 -->
<script src="<?php echo $static_path; ?>plugins/pace/pace.min.js"></script>

<!-- layui -->
<script src="<?php echo $static_path; ?>plugins/layui/layer/layer.js"></script>
<script src="<?php echo $static_path; ?>plugins/layui/laydate/laydate.js"></script>

<!-- 自定义js -->
<script src="<?php echo $static_path; ?>js/layer.com.js"></script>
<script src="<?php echo $static_path; ?>js/common.js"></script>
<script src="<?php echo $static_path; ?>js/vue.js"></script>
<script src="<?php echo $js; ?>hplus.js"></script>
<script src="<?php echo $js; ?>contabs.js"></script>

<!-- ueditor编辑器 -->
<script src="<?php echo $static_path; ?>plugins/ueditor/ueditor.config.js"></script>
<script src="<?php echo $static_path; ?>plugins/ueditor/ueditor.all.js"></script>
 <!-- 实例化编辑器 -->
<script type="text/javascript">
    var ue = UE.getEditor('container',{initialFrameHeight:450,allowDivTransToP:false,});
</script>


</html>
<style type="text/css">
    .panel-heading{
        display: none;
    }
    .panel-footer{
        background-color: #fff;
        border: none
    }
    .panel-body.form-inline .form-group{
        margin-right: 10px!important;
        margin-bottom: 10px!important;

    }
    .panel-body.form-inline .form-group .form-control{
        width: 200px;
    }
    .panel-body.form-inline .form-group.group1 .form-control,
    .panel-body.form-inline .form-group.group2 .form-control
    {
        width: 205px;
    }
    .panel-body.form-inline{
        padding-bottom: 0;
    }
    .panel-body.form-inline .form-group.pull-right {
        margin: 0!important;
    }
    .panel-body.form-inline .form-group.group1{
        margin-right: 0px!important;
    }
</style>
<script type="text/javascript">
    // 页面初始化
    $(function(){
        $('a').click(function(){
            $(this).blur();
        })
        $('.city-picker-span').css('width', '');
    })

</script>