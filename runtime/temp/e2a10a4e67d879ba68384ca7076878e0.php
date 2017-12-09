<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:59:"D:\phpStudy\WWW\kawadai\public\theme\admin\user\detail.html";i:1504510712;s:61:"D:\phpStudy\WWW\kawadai\public\theme\admin\layout\layout.html";i:1503643102;s:61:"D:\phpStudy\WWW\kawadai\public\theme\admin\layout\static.html";i:1504078526;s:57:"D:\phpStudy\WWW\kawadai\public\theme\admin\layout\js.html";i:1504078526;}*/ ?>
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
	<div class="panel" style="border-bottom: 1px solid #e7eaec">
		<p style="margin:5px;font-size: 14px;color: #1ab394;font-weight:bold">会员信息</p>
	</div>
	<form role="form" action="<?php echo url('user/index'); ?>" class="form-inline panel-body">
        <div class="form-group">
           <p class="form-control" >姓名:<?php echo $info['realname']; ?></p>
        </div>
       
        <div class="form-group">
             <p class="form-control" >手机号：<?php echo $info['mobile']; ?></p>
        </div>
         <div class="form-group">
             <p class="form-control" >邮箱：<?php echo $info['email']; ?></p>
        </div>
         <div class="form-group">
             <p class="form-control" >年龄：<?php echo birthday($info['birthday']); ?></p>
        </div>
         <div class="form-group">
             <p class="form-control" >性别：<?php if($info['sex'] == 1): ?>男<?php elseif($info['sex'] == 2): ?>女<?php else: ?>不详<?php endif; ?></p>
        </div>
         <div class="form-group">
             <p class="form-control" >户籍：<?php echo $info['province_id']; ?></p>
        </div>
         <div class="form-group">
             <p class="form-control" >星座：<?php echo $info['constellation']; ?></p>
        </div>
        <div class="form-group">
             <p class="form-control" >角色：<?php echo get_user_role($info['role']); ?></p>
        </div>
        <div class="form-group">
            <p class="form-control" style="width: 300px;">身份证：<?php echo $info['idcard']; ?></p>
        </div>
    </form>
</div>
<div class="panel panel-default" style="margin-top: 40px;: ">
	<div class="panel" style="border-bottom: 1px solid #e7eaec">
		<p style="margin:5px;font-size: 14px;color: #1ab394;font-weight:bold">身份证信息</p>
	</div>
	<div class="table-responsive">
		<table class="table table-hover table-bordered table-condensed">
        <thead>
            <tr>
                <th >身份证人像面</th>
                <th >身份证国徽面</th>
                <th >身份证手持照</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <td><img src="" width="260" height="150"></td><td><img src="" width="260" height="150"></td><td><img src="" width="260" height="150"></td>
            </tr>
        </tbody>
    </table>
	</div>
</div>
<div class="panel panel-default" style="margin-top: 40px;: ">
	<div class="panel" style="border-bottom: 1px solid #e7eaec">
	 	<p style="margin:5px;font-size: 14px;color: #1ab394;font-weight:bold">人际信息</p>
	</div>
	<div class="table-responsive">
		<table class="table table-hover table-bordered table-condensed">
        <thead>
            <tr>
                <th >姓名</th>
                <th >关系</th>
                <th >联系电话</th>
                <th >电子邮件</th>
                <th >详细地址</th>
            </tr>
        </thead>
        <tbody>
            <?php if(is_array($info['related']) || $info['related'] instanceof \think\Collection || $info['related'] instanceof \think\Paginator): $i = 0; $__LIST__ = $info['related'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
            <tr>
            <td><?php echo $vo['realname']; ?></td><td><?php echo $vo['relation']; ?></td><td><?php echo $vo['mobile']; ?></td><td><?php echo $vo['email']; ?></td><td><?php echo $vo['address']; ?></td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>
	</div>
</div>
<div class="panel panel-default" style="margin-top: 40px;: ">
	<div class="panel" style="border-bottom: 1px solid #e7eaec">
		<p style="margin:5px;font-size: 14px;color: #1ab394;font-weight:bold">银行卡信息</p>
	</div>
	<div class="table-responsive">
		<table class="table table-hover table-bordered table-condensed">
        <thead>
            <tr>
                <th >账户</th>
                <th >帐号</th>
                <th >所属银行</th>
            </tr>
        </thead>
        <tbody>
            <?php if(is_array($info['bankcard']) || $info['bankcard'] instanceof \think\Collection || $info['bankcard'] instanceof \think\Paginator): $i = 0; $__LIST__ = $info['bankcard'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v1): $mod = ($i % 2 );++$i;?>
            <tr>
            <td><?php echo $info['realname']; ?></td><td><?php echo $v1['bankcard_num']; ?></td><td><?php echo $v1['bank_code']; ?></td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>
	</div>
</div>
<div class="panel panel-default" style="margin-top: 40px;: ">
	<div class="panel" style="border-bottom: 1px solid #e7eaec">
	   <p style="margin:5px;font-size: 14px;color: #1ab394;font-weight:bold">征信详情</p>
	</div>
	<div class="table-responsive">
		<table class="table table-hover table-bordered table-condensed">
        <thead>
            <tr>
                <th >项目</th>
                <th >状态</th>
                <th >信用积分</th>
                <th >信用等级</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <td>手机运营商</td><td></td><td></td><td></td>
            </tr>
            <tr>
            <td>芝麻信用授信</td><td></td><td></td><td></td>
            </tr>
            <tr>
            <td>淘宝授信</td><td></td><td></td><td></td>
            </tr>
            <tr>
            <td>京东授信</td><td></td><td></td><td></td>
            </tr>
            <tr>
            <td>邮箱授信</td><td></td><td></td><td></td>
            </tr>
            <tr>
            <td>网贷黑名单</td><td></td><td></td><td></td>
            </tr>
            <tr>
            <td>信用卡授信</td><td></td><td></td><td></td>
            </tr>
            <tr>
            <td>法院黑名单</td><td></td><td></td><td></td>
            </tr>
            <tr>
            <td>身份证黑名单</td><td></td><td></td><td></td>
            </tr>
            <tr>
            <td>金融机构通话说明</td><td></td><td></td><td></td>
            </tr>
            <tr>
            <td>通话记录</td><td></td><td></td><td></td>
            </tr>
            <tr>
            <td>信用卡滞纳金</td><td></td><td></td><td></td>
            </tr>
            <tr>
            <td>历史逾期最高天数</td><td></td><td></td><td></td>
            </tr>
            <tr>
            <td>逾期次数</td><td></td><td></td><td></td>
            </tr>
        </tbody>
    </table>
	</div>
</div>
<div class="panel panel-default" style="margin-top: 40px;: ">
	<div class="panel" style="border-bottom: 1px solid #e7eaec">
	   <p style="margin:5px;font-size: 14px;color: #1ab394;font-weight:bold">个人财富</p>
	</div>
	<div class="table-responsive">
		<table class="table table-hover table-bordered table-condensed">
        <thead>
            <tr>
                <th >状态</th>
                <th >收益总金额</th>
                <th >笔数</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <td>推荐用户认证奖励</td><td></td><td></td>
            </tr>
            <tr>
            <td>推荐用户投资奖励</td><td></td><td></td>
            </tr>
            <tr>
            <td>推荐用户借款奖励</td><td></td><td></td>
            </tr>
            <tr>
            <td>投资收益</td><td></td><td></td>
            </tr>
            <tr>
            <td>已结算罚息</td><td></td><td></td>
            </tr>
            <tr>
            <th>汇总</th><th></th><th></th>
            </tr>
        </tbody>
    </table>
	</div>
</div>
<div class="panel panel-default" style="margin-top: 40px;: ">
    <div class="panel" style="border-bottom: 1px solid #e7eaec">
        <p style="margin:5px;font-size: 14px;color: #1ab394;font-weight:bold">投资记录汇总</p>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-bordered table-condensed">
        <thead>
            <tr>
                <th >投资金额</th>
                <th >笔数</th>
                <th >收益</th>
                <th >结算罚息</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <td><?php echo $info['total_invest']; ?></td><td><?php echo $info['count_invest']; ?></td><td><?php echo $info['interest_invest']; ?></td><td><?php echo $info['overdue_invest']; ?></td>
            </tr>
        </tbody>
    </table>
    </div>
</div>
<div class="panel panel-default" style="margin-top: 40px;: ">
    <div class="panel" style="border-bottom: 1px solid #e7eaec">
        <p style="margin:5px;font-size: 14px;color: #1ab394;font-weight:bold">借款记录汇总</p>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-bordered table-condensed">
        <thead>
            <tr>
                <th >借款金额</th>
                <th >笔数</th>
                <th >收益</th>
                <th >结算罚息</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <td><?php echo $info['total_borrow']; ?></td><td><?php echo $info['count_borrow']; ?></td><td><?php echo $info['interest_borrow']; ?></td><td><?php echo $info['overdue_borrow']; ?></td>
            </tr>
        </tbody>
    </table>
    </div>
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