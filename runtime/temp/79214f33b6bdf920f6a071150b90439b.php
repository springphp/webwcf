<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:67:"E:\phpStudy\WWW\project\kawadai\public\theme\admin\pay\my_card.html";i:1512750374;s:69:"E:\phpStudy\WWW\project\kawadai\public\theme\admin\layout\layout.html";i:1512750373;s:69:"E:\phpStudy\WWW\project\kawadai\public\theme\admin\layout\static.html";i:1512750373;s:65:"E:\phpStudy\WWW\project\kawadai\public\theme\admin\layout\js.html";i:1512750373;}*/ ?>
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
    <form role="form" action="<?php echo url('Pay/my_card'); ?>" class="form-inline panel-body hidden-xs">
    <div class="form-group">
            <label for="ex1" class="sr-only">开户名称</label>
            <input type="text" placeholder="开户名称" id="ex1" class="form-control" name="account" value="<?php echo input('account'); ?>">
        </div>

        <!-- <div class="form-group">
            <label for="ex1" class="sr-only">开户银行</label>
            <input type="text" placeholder="开户银行" id="ex1" class="form-control" name="bank_code" value="<?php echo input('bank_code'); ?>">
        </div> -->

        <div class="form-group">
           <label for="ex2" class="sr-only">开户银行</label>
           <select id="ex2" class="form-control"  name="bank_code">
                   <option value="">--- 开户银行 ---</option>
                   <?php if(is_array($banks) || $banks instanceof \think\Collection || $banks instanceof \think\Paginator): $i = 0; $__LIST__ = $banks;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                   <option value="<?php echo $vo; ?>" <?php if(input('bank_code') == $vo): ?>selected<?php endif; ?>><?php echo $vo; ?></option>
                   <?php endforeach; endif; else: echo "" ;endif; ?>
           </select>
       </div>

        <div class="form-group">
            <label for="ex1" class="sr-only">银行帐号</label>
            <input type="text" placeholder="银行帐号" id="ex1" class="form-control" name="bankcard_num" value="<?php echo input('bankcard_num'); ?>">
        </div>
        <div class="form-group">
            <label for="ex2" class="sr-only">状态</label>
            <select id="ex2" class="form-control"  name="status">
                    <option value="">状态</option>
                    <option value="1" <?php if(input('status') == 1): ?>selected<?php endif; ?>>启用</option>
                    <option value="0">禁用</option>
            </select>
        </div>

        <div class="form-group group1">
           <input type="text" name="statr_time" class="form-control i-datestart" id="date3" placeholder="创建日期" value="<?php echo input('statr_time'); ?>">
        </div>
        <div class="form-group gruop2">
            <input type="text" name="end_time" class="form-control i-dateend" placeholder="至今" value="<?php echo input('end_time'); ?>">
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
            <a href="<?php echo url('Pay/add'); ?>" class="btn btn-default js-window-load" js-title="新增银行卡" js-unique="true">
                <i class="fa fa-plus" aria-hidden="true"></i>&nbsp;添加
            </a>
            <a href="<?php echo url('Pay/del'); ?>" class="btn btn-default del-all" text="删除后将无法恢复，请谨慎操作">
                <i class="fa fa-trash" aria-hidden="true"></i>&nbsp;删除
            </a>
        </div>
        <div class="pull-right">
            <?php echo $cards->render(); ?>
        </div>
    </div>
</div>
<div class="table-responsive">
        <table class="table table-hover table-bordered table-condensed">
        <thead>
            <tr>
                <th width='1'><input type="checkbox" class="my-all-check" name="input[]"></th>
                <th width="150">开户名称</th>
                <th width="250" class="hidden-xs">开户银行</th>
                <th>开户地址</th>
                <th class="hidden-xs">银行账号</th>
                <th class="hidden-xs">创建日期</th>
                <th width="300" class="hidden-xs">创建人</th>
                <th width="250">操作</th>
            </tr>
        </thead>
        <tbody>
            <?php if(is_array($cards) || $cards instanceof \think\Collection || $cards instanceof \think\Paginator): $i = 0; $__LIST__ = $cards;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <tr>
                   <td width='1'><input type="checkbox" value="<?php echo $vo['id']; ?>" class="i-checks" name="input[]"></td>
                    <td><?php echo $vo['account']; ?></td>
                    <td><?php echo $vo['bank_code']; ?></td>
                    <td><?php echo $vo['bank_addr']; ?></td>
                    <td><?php echo $vo['bankcard_num']; ?></td>
                    <td><?php echo $vo['create_time']; ?></td>
                    <td><?php echo $create_name; ?></td>
                    <td >
                        <span class="btn-group">
                            <a href="<?php echo url('edit',['id'=>$vo['id'],'page'=>$nowpage]); ?>" class="btn btn-default btn-outline btn-xs js-window-load" title="编辑--<?php echo $vo['bank_code']; ?>"><i class="fa fa-edit fa-fw"></i><span class="hidden-xs">编辑</span></a>
                            <?php if($vo['status'] == 0): ?>
                            <a href="<?php echo url('change_status',['id'=>$vo['id'],'status'=>1]); ?>" js-color="#eea236" class="btn btn-default btn-outline btn-xs js-del-btn" text="启用后该用户可以正常登录"><i class="fa fa-check fa-fw"></i><span class="hidden-xs">启用</span></a>
                            <?php elseif($vo['status'] == 1): ?>
                            <a href="<?php echo url('change_status',['id'=>$vo['id'],'status'=>0]); ?>" js-color="#eea236" class="btn btn-default btn-outline btn-xs js-del-btn" text="禁用后该用户将无法登录,请谨慎操作！"><i class="fa fa-times fa-fw"></i><span class="hidden-xs">禁用</span></a>
                            <?php endif; ?>
                            <a href="<?php echo url('del',['id'=>$vo['id']]); ?>" class="btn  btn-danger btn-outline btn-xs js-del-btn" text="删除后将无法恢复,请谨慎操作！"><i class="fa fa-trash-o fa-fw"></i><span class="hidden-xs">删除</span></a>
                        </span>
                    </td>
                </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>
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