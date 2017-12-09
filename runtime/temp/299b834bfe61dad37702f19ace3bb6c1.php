<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:61:"D:\wwwroot\kwdcdttdcom\public\theme\admin\interest\index.html";i:1504522786;s:60:"D:\wwwroot\kwdcdttdcom\public\theme\admin\layout\layout.html";i:1503643102;s:60:"D:\wwwroot\kwdcdttdcom\public\theme\admin\layout\static.html";i:1504078526;s:56:"D:\wwwroot\kwdcdttdcom\public\theme\admin\layout\js.html";i:1504078526;}*/ ?>
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
	            <th width="100%">借款 利息设置</th>
	        </tr>
	    </thead>
	    <tbody>
			<tr>
	            <td>
					<div class="form-group" style="margin: 10px 30px;">
	            		<label for="about_input">可借金额范围：</label>
			            <input type="text" name="INTEREST_LOSS" class="form-control " id="about_input" value="<?php echo (isset($interest['INTEREST_LOSS']) && ($interest['INTEREST_LOSS'] !== '')?$interest['INTEREST_LOSS']:''); ?>" placeholder="500.00" >￥ 至
			            <input type="text" name="INTEREST_HIGH" class="form-control " id="about_input" value="<?php echo (isset($interest['INTEREST_HIGH']) && ($interest['INTEREST_HIGH'] !== '')?$interest['INTEREST_HIGH']:''); ?>" placeholder="5,000.00">￥
			        </div>
			        <div class="form-group" style="margin: 10px 50px;">
	            		<label for="about_input">可借期限范围：</label>
			            <input type="text" name="INTEREST_MIN_DAY" class="form-control" id="about_input" value="<?php echo (isset($interest['INTEREST_MIN_DAY']) && ($interest['INTEREST_MIN_DAY'] !== '')?$interest['INTEREST_MIN_DAY']:''); ?>" placeholder="1">￥ 至
			            <input type="text" name="INTEREST_LENGHT_DAY" class="form-control" id="about_input" value="<?php echo (isset($interest['INTEREST_LENGHT_DAY']) && ($interest['INTEREST_LENGHT_DAY'] !== '')?$interest['INTEREST_LENGHT_DAY']:''); ?>" placeholder="30">￥
			        </div>
			        <div class="form-group" style="margin: 10px 50px;">
	            		<label for="about_input">借款服务费：</label>
			            <input type="text" name="INTEREST_BORROW_FEE" class="form-control" id="about_input" value="<?php echo (isset($interest['INTEREST_BORROW_FEE']) && ($interest['INTEREST_BORROW_FEE'] !== '')?$interest['INTEREST_BORROW_FEE']:''); ?>" placeholder="10.00">￥ 
			        </div>

					<div class="form-group" style="margin: 10px 42px;">
	            		<label for="about_input">投资服务费：</label>
			            <input type="text" name="INTEREST_INVEST_FEE" class="form-control" id="about_input" value="<?php echo (isset($interest['INTEREST_INVEST_FEE']) && ($interest['INTEREST_INVEST_FEE'] !== '')?$interest['INTEREST_INVEST_FEE']:''); ?>" placeholder="10.00">￥ 
			        </div>

					<div class="form-group" style="margin: 10px 50px;">
	            		<label for="about_input">借款利息：本金 * </label>
			            <input type="text" name="INTEREST_RATE" style="margin: 0 6px;width: 80px;" class="form-control" id="about_input" value="<?php echo (isset($interest['INTEREST_RATE']) && ($interest['INTEREST_RATE'] !== '')?$interest['INTEREST_RATE']:''); ?>" placeholder="1"> % * 借款天数
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
	            <th width="100%">逾期 利息设置</th>
	        </tr>
	    </thead>
	    <tbody>
			<tr>
	            <td>
					<div class="form-group" style="margin: 10px 30px;">
	            		<label for="about_input">逾期利息：本金 * </label>
			            <input type="text" name="INTEREST_OVERDUE_RATE" style="margin: 0 6px;width: 80px;" class="form-control" id="about_input" value="<?php echo (isset($interest['INTEREST_OVERDUE_RATE']) && ($interest['INTEREST_OVERDUE_RATE'] !== '')?$interest['INTEREST_OVERDUE_RATE']:''); ?>" placeholder="1"> % * 逾期天数
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
	            <th width="100%">提现 利息设置</th>
	        </tr>
	    </thead>
	    <tbody>
			<tr>
	            <td>
					<div class="form-group" style="margin: 10px 30px;">
	            		<label for="about_input">提现每笔：</label>
			            <input type="text" name="INTEREST_GETMONEY_FEE" class="form-control " id="about_input" value="<?php echo (isset($interest['INTEREST_GETMONEY_FEE']) && ($interest['INTEREST_GETMONEY_FEE'] !== '')?$interest['INTEREST_GETMONEY_FEE']:''); ?>" placeholder="5.00" >￥
			        </div>
			        <div class="form-group" style="margin: 10px 50px;">
	            		<label for="about_input">单笔提现范围：</label>
			            <input type="text" name="INTEREST_GETMONEY_LOSS" class="form-control" id="about_input" value="<?php echo (isset($interest['INTEREST_GETMONEY_LOSS']) && ($interest['INTEREST_GETMONEY_LOSS'] !== '')?$interest['INTEREST_GETMONEY_LOSS']:''); ?>" placeholder="10.00">￥ 至
			            <input type="text" name="INTEREST_GETMONEY_MAX" class="form-control" id="about_input" value="<?php echo (isset($interest['INTEREST_GETMONEY_MAX']) && ($interest['INTEREST_GETMONEY_MAX'] !== '')?$interest['INTEREST_GETMONEY_MAX']:''); ?>" placeholder="10,000.00">￥
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
	            <th width="100%">邀请人 奖励设置</th>
	        </tr>
	    </thead>
	    <tbody>
			<tr>
	            <td>
					<div class="form-group" style="margin: 10px 30px;">
	            		<label for="about_input">邀请人第一次奖励：</label>
			            <input type="text" name="INTEREST_REWARDS" class="form-control " id="about_input" value="<?php echo (isset($interest['INTEREST_REWARDS']) && ($interest['INTEREST_REWARDS'] !== '')?$interest['INTEREST_REWARDS']:''); ?>" placeholder="10.00" >￥
			        </div>
			        <div class="form-group" style="margin: 10px 50px;">
	            		<label for="about_input">邀请人每笔奖励：</label>
			            <input type="text" name="INTEREST_INVITE" class="form-control" id="about_input" value="<?php echo (isset($interest['INTEREST_INVITE']) && ($interest['INTEREST_INVITE'] !== '')?$interest['INTEREST_INVITE']:''); ?>" placeholder="10.00">￥ 
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
    var ue = UE.getEditor('container',{initialFrameHeight:550,allowDivTransToP:false,});
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