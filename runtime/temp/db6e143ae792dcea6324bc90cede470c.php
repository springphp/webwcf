<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:71:"E:\phpStudy\WWW\project\kawadai\public\theme\admin\data\collection.html";i:1512750373;s:69:"E:\phpStudy\WWW\project\kawadai\public\theme\admin\layout\layout.html";i:1512750373;s:69:"E:\phpStudy\WWW\project\kawadai\public\theme\admin\layout\static.html";i:1512750373;s:65:"E:\phpStudy\WWW\project\kawadai\public\theme\admin\layout\js.html";i:1512750373;}*/ ?>
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

<div class="panel panel-default">
    <div class="panel-heading hidden-xs">条件搜索</div>
    <form role="form" action="<?php echo url('data/collection'); ?>" class="form-inline panel-body hidden-xs">
        <div class="form-group">
            <label for="ex2" class="sr-only">状态</label>
            <select id="ex2" class="form-control"  name="status">
                    <option value="">状态</option>
                    <option value="1" <?php if(input('status') ==1): ?>selected<?php endif; ?>>待催收</option>
                    <option value="2" <?php if(input('status') ==2): ?>selected<?php endif; ?>>催收中</option>
                    <option value="3" <?php if(input('status') ==3): ?>selected<?php endif; ?>>催收完成</option>
            </select>
        </div>
        <div class="form-group group1">
           <input type="text" name="statr_time" class="form-control i-datestart" id="date3" placeholder="催收开始日期" value="<?php echo input('statr_time'); ?>">
        </div>
        <div class="form-group gruop2">
            <input type="text" name="end_time" class="form-control i-dateend" placeholder="催收结束日期" value="<?php echo input('end_time'); ?>">
	    </div>
        <div class="form-group pull-right">
            <div class="btn-group">
                <button class="btn btn-primary btn-outline btn-w-m btn-rec">
                    <i class="fa fa-search"></i><span class="btn-desc">&nbsp;查询</span>
                </button>
                <a href="<?php echo url(''); ?>" class="btn btn-default btn-outline btn-rec">
                    <i class="fa fa-refresh"></i><span class="btn-desc">&nbsp;重置</span>
                </a>
            </div>
        </div>
    </form>
    <div class="panel-footer clearfix ">
        <div class="pull-left btn-group hidden-xs" >
          
        </div>
        <div class="pull-right">
            <?php echo $lists->render(); ?>
        </div>
    </div>
</div>
<div class="table-responsive">
        <table class="table table-hover table-bordered table-condensed">
        <thead>
            <tr>
                <th >订单号</th>
                <th >投资人</th>
                <th >投资金额（￥）</th>
                <th >收益</th>
                <th >投资日期</th>
                <th >借款人</th>
                <th >借款人手机</th>
                <th >奖励比例</th>
                <th >状态</th>
                <th >催收时间</th>
                <th >操作</th>
            </tr>
        </thead>
        <tbody>
            <?php if(is_array($lists) || $lists instanceof \think\Collection || $lists instanceof \think\Paginator): $i = 0; $__LIST__ = $lists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;if(count($vo['order_info']) !=0): ?>
                <tr>
                   
                    <td><?php echo $vo['order_info']['order_no']; ?></td>
                    
                    
                    <td><?php echo $vo['invest_user']; ?></td>
                    <td><?php echo $vo['order_info']['money']; ?></td>
                    <td><?php echo $vo['order_info']['interest']; ?></td>
                    <td class=""><?php echo date("Y-m-d",$vo['order_info']['invest_time']); ?></td>
                  	<td class=""><?php echo $vo['borrow_user']; ?></td>
                  	<td class=""><?php echo $vo['borrow_user_tel']; ?></td>
                  	<td class=""><?php echo $vo['rewards']; ?>%</td>
                    <td ><?php echo get_collection_status($vo['status']); ?></td>
                    <td ><?php echo $vo['create_time']; ?></td>
                    <td >
                    <a href="<?php echo url('collection_mobile',['order_id'=>$vo['order_id'],'page'=>$nowpage]); ?>" class="btn btn-default btn-outline btn-xs js-window-load" title="催收信息"><i class="fa fa-edit fa-fw"></i><span class="hidden-xs">催收信息</span></a>
                    <?php if($vo['status'] == 1): ?>
                    	<a href="<?php echo url('data/set_collection_status',array('id'=>$vo['id'],'status'=>2)); ?>" class="btn  btn-default btn-outline btn-xs js-del-btn" text="确认接受催收"><i class="fa fa fa-wrench fa-fw"></i><span class="hidden-xs">接受催收</span></a>
                    	<a href="<?php echo url('data/set_collection_status',array('id'=>$vo['id'],'status'=>4)); ?>" class="btn  btn-default btn-outline btn-xs js-del-btn" text="确认拒绝"><i class="fa fa fa-wrench fa-fw"></i><span class="hidden-xs">拒绝催收</span></a>
                    <?php elseif($vo['status'] == 2): ?>
                    	<a href="<?php echo url('data/set_collection_status',array('id'=>$vo['id'],'status'=>3)); ?>" class="btn  btn-default btn-outline btn-xs js-del-btn" text="确认完成"><i class="fa fa fa-wrench fa-fw"></i><span class="hidden-xs">完成催收</span></a>
                    <?php elseif($vo['status'] == 3 || $vo['status'] == 4): ?>
                    	<a href="<?php echo url('data/del_collection',array('id'=>$vo['id'])); ?>" class="btn  btn-danger btn-outline btn-xs js-del-btn" text="确认删除"><i class="fa fa fa-trash fa-fw"></i><span class="hidden-xs">删除催收</span></a>
                    <?php endif; ?>
            		</td>
                </tr>
                <?php endif; endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>
        <!-- <div class="cleanfix">
            <div class="pull-left pagination hidden-xs" >
            </div>
            <div class="pull-left">
            </div>
        </div> -->

    </div>

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