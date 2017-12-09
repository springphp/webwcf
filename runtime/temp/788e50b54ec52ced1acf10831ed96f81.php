<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:62:"D:\wwwroot\kwdcdttdcom\public\theme\admin\user\apply_list.html";i:1504778468;s:60:"D:\wwwroot\kwdcdttdcom\public\theme\admin\layout\layout.html";i:1503643102;s:60:"D:\wwwroot\kwdcdttdcom\public\theme\admin\layout\static.html";i:1504078526;s:56:"D:\wwwroot\kwdcdttdcom\public\theme\admin\layout\js.html";i:1504078526;}*/ ?>
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
    <form role="form" action="<?php echo url('user/apply_list'); ?>" class="form-inline panel-body hidden-xs">
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
         <div class="form-group">
            <label for="ex3" class="sr-only">操作人</label>
            <input type="text" placeholder="操作人" id="ex3" class="form-control" name="account" value="<?php echo input('account'); ?>">
        </div>
         <div class="form-group">
            <label for="ex2" class="sr-only">状态</label>
            <select id="ex2" class="form-control"  name="is_check">
                    <option value="">状态</option>
                    <option value="1" <?php if(input('is_check') ==1): ?>selected<?php endif; ?>>已审核</option>
                    <option value="0" <?php if(input('is_check') ==0 && input('is_check')!=null): ?>selected<?php endif; ?>>待审核</option>
            </select>
        </div>
        <div class="form-group group1">
           <input type="text" name="check_statr_time" class="form-control i-datestart" id="date3" placeholder="审核开始日期" value="<?php echo input('check_statr_time'); ?>">
        </div>
        <div class="form-group gruop2">
            <input type="text" name="check_end_time" class="form-control i-dateend" placeholder="审核结束日期" value="<?php echo input('check_end_time'); ?>">
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
           	<a href="<?php echo url('user/user_check',array('is_agree'=>1)); ?>" class="btn btn-default del-all" text="通过后会员可进行更多操作">
                <i class="fa fa-wrench" aria-hidden="true"></i>&nbsp;通过
            </a>
            <a href="<?php echo url('user/user_check',array('is_agree'=>0)); ?>" class="btn btn-default del-all" text="不通过会员将无法进行跟多操作">
                <i class="fa fa-times" aria-hidden="true"></i>&nbsp;不通过
            </a>
            <a href="<?php echo url('user/del'); ?>" class="btn btn-default del-all" text="删除后将无法恢复，请谨慎操作">
                <i class="fa fa-trash" aria-hidden="true"></i>&nbsp;删除
            </a>
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
            	<th width='1'><input type="checkbox" class="my-all-check" name="input[]"></th>
                <th >姓名</th>
                <th class="hidden-xs">性别</th>
                <th >年龄</th>
                <th class="hidden-xs">身份证号</th>
                <th class="hidden-xs">手机号</th>
                <th class="hidden-xs">邮箱</th>
                <th class="hidden-xs">申请日期</th>
                <th class="hidden-xs">状态</th>
                <th class="hidden-xs">操作结果</th>
                <th class="hidden-xs">操作人</th>
                <th class="hidden-xs">操作时间</th>
                <th class="hidden-xs">备注</th>
                <th class="hidden-xs">操作</th>
            </tr>
        </thead>
        <tbody>
            <?php if(is_array($lists) || $lists instanceof \think\Collection || $lists instanceof \think\Paginator): $i = 0; $__LIST__ = $lists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <tr>
                	<td width='1'><input type="checkbox" value="<?php echo $vo['user_id']; ?>" class="i-checks" name="input[]"></td>
                    <td><a href="<?php echo url('user/detail',array('user_id'=>$vo['user_id'])); ?>" class="js-window-load" title="会员详情--<?php echo $vo['realname']; ?>"><?php echo $vo['realname']; ?></a></td>
                    <td class="hidden-xs">
                    <?php if($vo['sex'] == 1): ?>
                   	男
                   	<?php elseif($vo['sex'] == 2): ?>
                   	女
                   	<?php else: ?>
                   	不详
                   	<?php endif; ?>
                   	</td>
                    <td><?php echo birthday($vo['birthday']); ?></td>
                    <td><?php echo $vo['idcard']; ?></td>
                    <td><?php echo $vo['mobile']; ?></td>
                    <td class="hidden-xs"><?php echo $vo['email']; ?></td>
                    <td class="hidden-xs"><?php echo date("Y-m-d",$vo['apply_time']); ?></td>
                   	<?php if($vo['is_check'] == 1): ?>
                    <td >
                   	已审核
                    </td>
                   	<?php else: ?>
                    <td style="color: #1ab394">
                   	待审核
                    </td>
                   	<?php endif; if($vo['is_agree'] == 1): ?>
                    <td>
                   	通过
                    </td>
                   	<?php else: ?>
                    <td style="color:#ed5565">
                   	未通过
                    </td>
                   	<?php endif; ?>
                   	
                   	<td><?php echo $vo['check_account']; ?></td>
                   	<td>
                    <?php if($vo['check_time'] != ''): ?>
                   	<?php echo date("Y-m-d H:i:s",$vo['check_time']); endif; ?>
                   	</td>
                   	<td><?php echo $vo['remark']; ?></td>
                   	<td><a href="<?php echo url('user/user_check',array('id'=>$vo['user_id'],'is_agree'=>1)); ?>" class="btn  btn-default btn-outline btn-xs js-del-btn" text="通过后会员可进行更多操作"><i class="fa fa fa-wrench fa-fw"></i><span class="hidden-xs">通过</span></a><a href="<?php echo url('user/user_check',array('id'=>$vo['user_id'],'is_agree'=>0)); ?>" class="btn  btn-danger btn-outline btn-xs js-del-btn" text="不通过会员将无法进行跟多操作"><i class="fa fa-times fa-fw"></i><span class="hidden-xs">不通过</span></a></td>
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