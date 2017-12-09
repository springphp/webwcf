<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:72:"E:\phpStudy\WWW\project\kawadai\public\theme\admin\data\invest_list.html";i:1512750373;s:69:"E:\phpStudy\WWW\project\kawadai\public\theme\admin\layout\layout.html";i:1512750373;s:69:"E:\phpStudy\WWW\project\kawadai\public\theme\admin\layout\static.html";i:1512750373;s:65:"E:\phpStudy\WWW\project\kawadai\public\theme\admin\layout\js.html";i:1512750373;}*/ ?>
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
    <form role="form" action="<?php echo url('data/invest_list'); ?>" class="form-inline panel-body hidden-xs">
        <div class="form-group">
            <label for="ex1" class="sr-only">订单号</label>
            <input type="text" placeholder="订单号" id="ex1" class="form-control" name="order_no" value="<?php echo input('order_no'); ?>">
        </div>
        <!-- <div class="form-group">
            <label for="ex1" class="sr-only">借款人</label>
            <input type="text" placeholder="借款人" id="ex1" class="form-control" name="borrow_user" value="<?php echo input('borrow_user'); ?>">
        </div> -->
        <div class="form-group">
            <label for="ex1" class="sr-only">投资人</label>
            <input type="text" placeholder="投资人" id="ex1" class="form-control" name="invest_user" value="<?php echo input('invest_user'); ?>">
        </div>
        <div class="form-group">
            <label for="ex2" class="sr-only">状态</label>
            <select id="ex2" class="form-control"  name="status">
                    <option value="">状态</option>
                    <option value="1" <?php if(input('status') ==1): ?>selected<?php endif; ?>>待收款</option>
                    <option value="2" <?php if(input('status') ==2): ?>selected<?php endif; ?>>未到期</option>
                    <option value="3" <?php if(input('status') ==3): ?>selected<?php endif; ?>>已逾期</option>
                    <option value="4" <?php if(input('status') ==4): ?>selected<?php endif; ?>>已还款</option>
                    <option value="5" <?php if(input('status') ==5): ?>selected<?php endif; ?>>已结束</option>
            </select>
        </div>
        <!-- <div class="form-group">
            <label for="ex2" class="sr-only">是否交易</label>
            <select id="ex2" class="form-control"  name="is_done">
                    <option value="">是否交易</option>
                    <option value="1" <?php if(input('is_done') ==1): ?>selected<?php endif; ?>>已交易</option>
                    <option value="0" <?php if(input('is_done') ==0 || input('is_done') != null): ?>selected<?php endif; ?>>未交易</option>
            </select>
        </div> -->
        <div class="form-group group1">
           <input type="text" name="statr_time" class="form-control i-datestart" id="date3" placeholder="投资开始日期" value="<?php echo input('statr_time'); ?>">
        </div>
        <div class="form-group gruop2">
            <input type="text" name="end_time" class="form-control i-dateend" placeholder="投资结束日期" value="<?php echo input('end_time'); ?>">
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
          <!--   <a href="<?php echo url('admins/add'); ?>" class="btn btn-default js-window-load" js-title="新增管理员" js-unique="true">
                <i class="fa fa-plus" aria-hidden="true"></i>&nbsp;新增
            </a>
            <a href="<?php echo url('admins/del'); ?>" class="btn btn-default del-all" text="删除后将无法恢复，请谨慎操作">
                <i class="fa fa-trash" aria-hidden="true"></i>&nbsp;删除
            </a> -->
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
                <th >期限（天）</th>
                <th >收益</th>
                <th >付款账号</th>
                <th >投资日期</th>
                <th >借款人</th>
                <th >借款帐号</th>
                <th >状态</th>
                <th >预收罚息</th>
            </tr>
        </thead>
        <tbody>
            <?php if(is_array($lists) || $lists instanceof \think\Collection || $lists instanceof \think\Paginator): $i = 0; $__LIST__ = $lists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <tr>
                    <td><?php echo $vo['order_no']; ?></td>
                    <td><?php echo $vo['invest_user']; ?></td>
                    <td><?php echo $vo['money']; ?></td>
                    <td><?php echo $vo['term']; ?></td>
                    <td><?php echo $vo['interest']; ?></td>
                    <td><?php echo $vo['invest_cnum']; ?></td>
                    <td class=""><?php echo date("Y-m-d",$vo['invest_time']); ?></td>
                  	<td class=""><?php echo $vo['borrow_user']; ?></td>
                    <td class=""><?php echo $vo['borrow_cnum']; ?></td>
                    <td <?php if($vo['status'] == 3): ?> style="color: red;"<?php endif; ?>><?php echo get_order_status($vo['status']); ?></td>
                    <td <?php if($vo['status'] == 3): ?> style="color: red;"<?php endif; ?>>
                    <?php echo $vo['overdue_money']; ?>
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