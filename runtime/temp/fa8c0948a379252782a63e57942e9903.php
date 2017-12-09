<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:66:"E:\phpStudy\WWW\project\kawadai\public\theme\admin\user\index.html";i:1512750381;s:69:"E:\phpStudy\WWW\project\kawadai\public\theme\admin\layout\layout.html";i:1512750373;s:69:"E:\phpStudy\WWW\project\kawadai\public\theme\admin\layout\static.html";i:1512750373;s:65:"E:\phpStudy\WWW\project\kawadai\public\theme\admin\layout\js.html";i:1512750373;}*/ ?>
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
    <form role="form" action="<?php echo url('user/index'); ?>" class="form-inline panel-body hidden-xs">
        <div class="form-group">
            <label for="ex1" class="sr-only">姓名</label>
            <input type="text" placeholder="姓名" id="ex1" class="form-control" name="realname" value="<?php echo input('realname'); ?>">
        </div>
       <div class="form-group">
            <label for="ex2" class="sr-only">手机号</label>
            <input type="text" placeholder="手机号" id="ex2" class="form-control" name="mobile" value="<?php echo input('mobile'); ?>">
        </div>
        <div class="form-group">
            <label for="ex3" class="sr-only">身份证</label>
            <input type="text" placeholder="身份证" id="ex3" class="form-control" name="idcard" value="<?php echo input('idcard'); ?>">
        </div>
        <div class="form-group group1">
           <input type="text" name="statr_time" class="form-control i-datestart" id="date3" placeholder="开始日期" value="<?php echo input('statr_time'); ?>">
        </div>
        <div class="form-group gruop2">
            <input type="text" name="end_time" class="form-control i-dateend" placeholder="结束日期" value="<?php echo input('end_time'); ?>">
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
       
        <div class="pull-right">
            <?php echo $members->render(); ?>
        </div>
    </div>
</div>
<div class="table-responsive">
        <table class="table table-hover table-bordered table-condensed">
        <thead>
            <tr>
                <th >姓名</th>
                <th class="hidden-xs">身份证号</th>
                <th >户籍</th>
                <th class="hidden-xs">星座</th>
                <th class="hidden-xs">性别</th>
                <th class="hidden-xs">年龄</th>
                <th class="hidden-xs">手机号</th>
                <th class="hidden-xs">邮箱</th>
                <th class="hidden-xs">投资金额汇总/笔数</th>
                <th class="hidden-xs">收益汇总/笔数</th>
                <th class="hidden-xs">借款金额汇总/笔数</th>
                <th class="hidden-xs">逾期本金汇总/笔数</th>
                <th class="hidden-xs">创建时间</th>
                <th class="hidden-xs">操作</th>
            </tr>
        </thead>
        <tbody>
            <?php if(is_array($members) || $members instanceof \think\Collection || $members instanceof \think\Paginator): $i = 0; $__LIST__ = $members;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <tr>
                    <td><a href="<?php echo url('user/detail',array('user_id'=>$vo['user_id'])); ?>" class="js-window-load" title="会员详情--<?php echo $vo['realname']; ?>"><?php echo $vo['realname']; ?></a></td>
                    <td class="hidden-xs"><?php echo $vo['idcard']; ?></td>
                    <td><?php echo $vo['home']; ?></td>
                    <td><?php echo $vo['constellation']; ?></td>
                    <td class="hidden-xs"><?php echo $vo['sex']; ?></td>
                    <td class="hidden-xs"><?php echo birthday($vo['birthday']); ?></td>
                    <td class="hidden-xs"><?php echo $vo['mobile']; ?></td>
                   	<td><?php echo $vo['email']; ?></td>
                   	<td><?php echo $vo['invest_data']['money']; ?>/<?php echo $vo['invest_data']['count']; ?></td>
                   	<td><?php echo $vo['earnings_data']['money']; ?>/<?php echo $vo['earnings_data']['count']; ?></td>
                   	<td><?php echo $vo['borrow_data']['money']; ?>/<?php echo $vo['borrow_data']['count']; ?></td>
                   	<td><?php echo $vo['overdue_data']['money']; ?>/<?php echo $vo['overdue_data']['count']; ?></td>
                   	<td><?php echo $vo['create_time']; ?></td>
                    <td>
                    <?php if($vo['is_vip'] == 0): ?>
                    <a href="<?php echo url('is_vip',['id'=>$vo['user_id'],'is_vip'=>1]); ?>" js-color="#eea236" class="btn btn-default btn-outline btn-xs js-del-btn" text="设为vip后，用户单日投资无限额"><i class="fa fa-check fa-fw"></i><span class="hidden-xs">设为vip</span></a>
                    <?php elseif($vo['is_vip'] == 1): ?>
                    <a href="<?php echo url('is_vip',['id'=>$vo['user_id'],'is_vip'=>0]); ?>" js-color="#eea236" class="btn btn-default btn-outline btn-xs js-del-btn" text="取消vip后，用户单日投资有一定限额"><i class="fa fa-times fa-fw"></i><span class="hidden-xs">取消vip</span></a>
                    <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
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