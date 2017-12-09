<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:57:"D:\wwwroot\kwdcdttdcom\public\theme\admin\menus\edit.html";i:1499829180;s:60:"D:\wwwroot\kwdcdttdcom\public\theme\admin\layout\layout.html";i:1503643102;s:60:"D:\wwwroot\kwdcdttdcom\public\theme\admin\layout\static.html";i:1504078526;s:56:"D:\wwwroot\kwdcdttdcom\public\theme\admin\layout\js.html";i:1504078526;}*/ ?>
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
<form class="form-horizontal js-ajax-form clearfix" action='<?php echo url('Menus/edit'); ?>'>
	<input type="hidden" name="menu_id" value="<?php echo $menu_info['menu_id']; ?>">
   <div class="form-group animated fadeInRight">
        <label for="select-tree" class="col-sm-2 control-label">上级菜单</label>
        <div class="col-sm-4">
            <?php echo $select; ?>
        </div>
    </div>
    <div class="form-group">
        <label for="menu_name" class="col-sm-2 control-label">菜单名称</label>
        <div class="col-sm-4">
            <input type="text" name="menu_name" class="form-control" id="menu_name" placeholder="菜单名称" value="<?php echo $menu_info['menu_name']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="sort" class="col-sm-2 control-label">菜单图标</label>
        <div class="col-sm-4">
            <div class="input-group">
                <input type="text" name="menu_icon" id="menu_icon" readonly="" class="form-control" placeholder="" value="<?php echo $menu_info['menu_icon']; ?>">
                <span class="input-group-btn">
                    <a class="btn btn-primary js-window-load" href="<?php echo url('admin/menus/fontawesome'); ?>" js-unique="true" js-width="80%" js-height='85%'>选择图标</a>
                </span>

            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="sort" class="col-sm-2 control-label">排序</label>
        <div class="col-sm-2">
            <input type="text" name="sort" class="form-control" id="sort" placeholder="排序" value="<?php echo $menu_info['sort']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="sort" class="col-sm-2 control-label">状态</label>
        <div class="col-sm-4">
            <label class="i-lab">
                <input type="radio" name="status" value='1' class="mgr mgr-primary mgr-lg"<?php if($menu_info['status'] == 1): ?> checked<?php endif; ?>><span>显示</span>
            </label>
            <label class="i-lab">
                <input type="radio" name="status" value='0' class="mgr mgr-primary mgr-lg"<?php if($menu_info['status'] == 0): ?> checked<?php endif; ?>> <span>隐藏</span>
            </label>
        </div>
    </div>
    <div class="form-group">
        <label for="menu_des" class="col-sm-2 control-label">菜单描述</label>
        <div class="col-sm-4">
            <input type="text" name="menu_des" class="form-control" id="menu_des" placeholder="菜单描述" value="<?php echo $menu_info['menu_des']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="" class="col-sm-2 control-label">URL类型</label>
        <div class="col-sm-4">
            <label class="i-lab">
                <input type="radio" name="url_type" value='1' class="mgr mgr-primary mgr-lg"<?php if($menu_info['url_type'] == 1): ?> checked<?php endif; ?>><span>内链接</span>
            </label>
            <label class="i-lab">
                <input type="radio" name="url_type" value='2' class="mgr mgr-primary mgr-lg"<?php if($menu_info['url_type'] == 2): ?> checked<?php endif; ?>><span>外链接</span>
            </label>
        </div>
    </div>
    <div id="in">
        <div class="form-group">
            <label for="module" class="col-sm-2 control-label">模块</label>
            <div class="col-sm-4">
                <input type="text" name="module" class="form-control" id="module" placeholder="" value="<?php echo $menu_info['module']; ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="controller" class="col-sm-2 control-label" >控制器</label>
            <div class="col-sm-4">
                <input type="text" name="controller" class="form-control" id="controller" placeholder="" value="<?php echo $menu_info['controller']; ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="action" class="col-sm-2 control-label">方法</label>
            <div class="col-sm-4">
                <input type="text" name="action" class="form-control" id="action" placeholder="" value="<?php echo $menu_info['action']; ?>">
            </div>
        </div>
    </div>
    <div id="out">
        <div class="form-group">
            <label for="url" class="col-sm-2 control-label">链接地址</label>
            <div class="col-sm-4">
                <input type="text" name="url" class="form-control" id="url" placeholder="" value="<?php echo $menu_info['url']; ?>">
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword3" class="col-sm-2 control-label"></label>
        <div class="col-sm-4">
            <button type="submit" class="btn btn-primary js-submit-btn mr_3px">确认</button>
            <button type="reset" class="btn btn-primary">重置</button>
        </div>
    </div>
</form>

<script type="text/javascript">
$(document).ready(function(){
    $('#in').css('display','none');
    $('#out').css('display','none');
    $('input[name="url_type"]').each(function(){
        if (this.checked) {
            $(this).val()==1 && $('#in').css('display','block') && $('#out').css('display','none');
            $(this).val()==2 && $('#in').css('display','none') && $('#out').css('display','block');
        }
    })
    $('input[name="url_type"]').click(function(){
        $(this).val()==1 && $('#in').css('display','block') && $('#out').css('display','none');
        $(this).val()==2 && $('#in').css('display','none') && $('#out').css('display','block');
    })
})
</script>
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