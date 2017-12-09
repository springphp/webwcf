<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:60:"D:\wwwroot\kwdcdttdcom\public\theme\admin\data\protocol.html";i:1504511775;s:60:"D:\wwwroot\kwdcdttdcom\public\theme\admin\layout\layout.html";i:1503643102;s:60:"D:\wwwroot\kwdcdttdcom\public\theme\admin\layout\static.html";i:1504078526;s:56:"D:\wwwroot\kwdcdttdcom\public\theme\admin\layout\js.html";i:1504078526;}*/ ?>
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
<form class="form-horizontal js-ajax-form clearfix" action='' method='post'>
    <!-- 自定义大小 -->
    <div class="about_nav_title">
    	<h2>借款与还款协议</h2>
    </div>
	<!-- 甲方 -->
    <div class="form-group">
        <label for="editorid" class="col-sm-2 control-label">编号：</label>
        <div class="col-sm-9">
            <input type="text" name="editorid" class="form-control" id="editorid" value="<?php echo (isset($protocol['order_no']) && ($protocol['order_no'] !== '')?$protocol['order_no']:''); ?>" placeholder="编号">
        </div>
    </div>

    <div class="form-group">
        <label for="jia_name" class="col-sm-2 control-label">甲方(出借方)：</label>
        <div class="col-sm-9">
            <input type="text" name="jia_name" class="form-control" id="jia_name" value="<?php echo (isset($protocol['invest_user_name']) && ($protocol['invest_user_name'] !== '')?$protocol['invest_user_name']:''); ?>" placeholder="甲方姓名">
        </div>
    </div>
    <div class="form-group">
        <label for="jia_idcard" class="col-sm-2 control-label">身份证号码：</label>
        <div class="col-sm-9">
            <input type="text" name="jia_idcard" class="form-control" id="jia_idcard" value="<?php echo (isset($protocol['invest_user_idcard']) && ($protocol['invest_user_idcard'] !== '')?$protocol['invest_user_idcard']:''); ?>" placeholder="身份证号码">
        </div>
    </div>
    <div class="form-group">
        <label for="jia_mobile" class="col-sm-2 control-label">甲方手机号：</label>
        <div class="col-sm-9">
            <input type="tel" name="jia_mobile" class="form-control" id="jia_mobile" value="<?php echo (isset($protocol['invest_user_tel']) && ($protocol['invest_user_tel'] !== '')?$protocol['invest_user_tel']:''); ?>" placeholder="甲方手机号">
        </div>
    </div>
    <div style="margin-top:50px;"></div>
	<!-- 乙方 -->
    <div class="form-group">
        <label for="yi_name" class="col-sm-2 control-label">乙方(借入方)：</label>
        <div class="col-sm-9">
            <input type="text" name="yi_name" class="form-control" id="yi_name" value="<?php echo (isset($protocol['borrow_user_name']) && ($protocol['borrow_user_name'] !== '')?$protocol['borrow_user_name']:''); ?>" placeholder="乙方姓名">
        </div>
    </div>
    <div class="form-group">
        <label for="yi_idcard" class="col-sm-2 control-label">身份证号码：</label>
        <div class="col-sm-9">
            <input type="text" name="yi_idcard" class="form-control" id="yi_idcard" value="<?php echo (isset($protocol['borrow_user_idcard']) && ($protocol['borrow_user_idcard'] !== '')?$protocol['borrow_user_idcard']:''); ?>" placeholder="身份证号码">
        </div>
    </div>
    <div class="form-group">
        <label for="yi_mobile" class="col-sm-2 control-label">乙方手机号：</label>
        <div class="col-sm-9">
            <input type="tel" name="yi_mobile" class="form-control" id="yi_mobile" value="<?php echo (isset($protocol['borrow_user_tel']) && ($protocol['borrow_user_tel'] !== '')?$protocol['borrow_user_tel']:''); ?>" placeholder="乙方手机号">
        </div>
    </div>
    <div style="margin-top:50px;"></div>
    <!-- 丙方 -->
	<div class="form-group">
        <label for="server_name" class="col-sm-2 control-label">丙方(服务提供商)：</label>
        <div class="col-sm-9">
            <input type="text" name="server_name" class="form-control" id="server_name" value="<?php echo (isset($protocol['PROTOCOL_NAME']) && ($protocol['PROTOCOL_NAME'] !== '')?$protocol['PROTOCOL_NAME']:''); ?>" placeholder="丙方">
        </div>
    </div>
    <div class="form-group">
        <label for="server_mobile" class="col-sm-2 control-label">联系方式：</label>
        <div class="col-sm-9">
            <input type="tel" name="server_mobile" class="form-control" id="server_mobile" value="<?php echo (isset($protocol['PROTOCOL_TEL']) && ($protocol['PROTOCOL_TEL'] !== '')?$protocol['PROTOCOL_TEL']:''); ?>" placeholder="联系方式">
        </div>
    </div>
	
	<div class="form-group">
		<label for="content" class="col-sm-2 control-label">协议内容：</label>
		<div class="col-sm-9">
			<script id="container" name="content" type="text/plain">
			    <?php echo (isset($protocol['PROTOCOL_CONTENT']) && ($protocol['PROTOCOL_CONTENT'] !== '')?$protocol['PROTOCOL_CONTENT']:""); ?>
			</script>
		</div>
	</div>

    <div class="form-group clearfix about_buttun">
	    <label for="inputPassword3" class="col-sm-2 control-label"></label>
	    <!-- <div class="col-sm-9">
	        <button type="button" class="btn btn-info js-return-btn">返回</button>
	    </div> -->
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