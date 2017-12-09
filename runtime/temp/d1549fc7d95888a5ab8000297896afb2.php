<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:66:"D:\wwwroot\kwdcdttdcom\public\theme\admin\auth_groups\setauth.html";i:1499825570;s:60:"D:\wwwroot\kwdcdttdcom\public\theme\admin\layout\layout.html";i:1503643102;s:60:"D:\wwwroot\kwdcdttdcom\public\theme\admin\layout\static.html";i:1504078526;s:56:"D:\wwwroot\kwdcdttdcom\public\theme\admin\layout\js.html";i:1504078526;}*/ ?>
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

<form action="" class="js-ajax-form clearfix">
    <table id="menuTree"  class="table table-hover table-bordered table-condensed" style="width:100%">
            <thead>
                <tr >
                    <th width="200" js-order='menu_name' class="js-order">名称</th>
                    <th width="1">
                        <div class="btn-group" data-toggle="buttons">
                            <label class="btn btn-default btn-outline btn-xs"><input id="0"  value="0" type="checkbox">全选</label>
                        </div>
                    </th>
                    <th>用户组权限</th>
                </tr>
            </thead>
            <tbody>
                <?php if(is_array($tree) || $tree instanceof \think\Collection || $tree instanceof \think\Paginator): $i = 0; $__LIST__ = $tree;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <tr id="<?php echo $vo['menu_id']; ?>"<?php if($vo['pid'] != 0): ?>pId="<?php echo $vo['pid']; ?>"<?php endif; ?>>
                    <td controller="true"><i title="<?php echo $vo['menu_icon']; ?>" class="fa fa-fw fa-lg fa-<?php echo $vo['menu_icon']; ?>"></i>&nbsp;<?php echo $vo['menu_name']; ?></td>
                    <td>
                        <div class="btn-group" data-toggle="buttons">
                            <label class="btn btn-default btn-outline btn-xs"><input pid="<?php echo $vo['pid']; ?>" value="<?php echo $vo['menu_id']; ?>" id="<?php echo $vo['menu_id']; ?>"  name="menu_ids[<?php echo $vo['menu_id']; ?>]" type="checkbox">全选</label>
                        </div>
                    </td>
                    <td>
                        <div class="btn-group" data-toggle="buttons">
                        <?php if($vo['level']==0): if(count($groupAuth)!=0 && in_array($vo['menu_id'],$groupAuth)): ?>
                            <label class="btn btn-default btn-outline btn-xs active"><input pid="0" value="<?php echo $vo['menu_id']; ?>" name="menu_ids[<?php echo $vo['menu_id']; ?>]" type="checkbox" checked="checked" >查看</label>
                            <?php else: ?>
                            <label class="btn btn-default btn-outline btn-xs"><input pid="0" value="<?php echo $vo['menu_id']; ?>" name="menu_ids[<?php echo $vo['menu_id']; ?>]" type="checkbox" >查看</label>
                            <?php endif; else: if(count($groupAuth)!=0 && in_array($vo['menu_id'],$groupAuth)): ?>
                            <label class="btn btn-default btn-outline btn-xs active"><input pid="<?php echo $vo['menu_id']; ?>" value="<?php echo $vo['menu_id']; ?>" name="menu_ids[<?php echo $vo['menu_id']; ?>]" type="checkbox" checked="checked">查看</label>
                            <?php else: ?>
                            <label class="btn btn-default btn-outline btn-xs"><input pid="<?php echo $vo['menu_id']; ?>" value="<?php echo $vo['menu_id']; ?>" name="menu_ids[<?php echo $vo['menu_id']; ?>]" type="checkbox">查看</label>
                            <?php endif; if(is_array($menu_model->where(['pid'=>$vo['menu_id']])->select()) || $menu_model->where(['pid'=>$vo['menu_id']])->select() instanceof \think\Collection || $menu_model->where(['pid'=>$vo['menu_id']])->select() instanceof \think\Paginator): if( count($menu_model->where(['pid'=>$vo['menu_id']])->select())==0 ) : echo "" ;else: foreach($menu_model->where(['pid'=>$vo['menu_id']])->select() as $key=>$v): if(count($groupAuth)!=0 && in_array($v['menu_id'],$groupAuth)): ?>
                            <label class="btn btn-default btn-outline btn-xs active"><input pid="<?php echo $v['pid']; ?>" value="<?php echo $v['menu_id']; ?>" name="menu_ids[<?php echo $v['menu_id']; ?>]" id="<?php echo $v['menu_id']; ?>"  type="checkbox" checked="checked"><?php echo $v['menu_name']; ?></label>
                            <?php else: ?>
                            <label class="btn btn-default btn-outline btn-xs"><input pid="<?php echo $v['pid']; ?>" value="<?php echo $v['menu_id']; ?>" name="menu_ids[<?php echo $v['menu_id']; ?>]" id="<?php echo $v['menu_id']; ?>"  type="checkbox"><?php echo $v['menu_name']; ?></label>
                            <?php endif; endforeach; endif; else: echo "" ;endif; ?>
                        </div>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>

        </table>
        <div class="col-sm-4">
            <button type="submit" class="btn btn-info js-submit-btn mr_3px">保存</button>
        </div>
</form>


<link id="tree_table_default" href="<?php echo $static_path; ?>plugins/treetable/default/jquery.treeTable.css" rel="stylesheet" type="text/css" />
<script src="<?php echo $static_path; ?>plugins/treetable/jquery.treeTable.js" type="text/javascript"> </script>
<script type="text/javascript">

$(function(){
    var option = {
        theme:'default',
        column:0,
        expandLevel : 10,
        beforeExpand : function($treeTable, id) {},//展开过后动作
        onSelect : function($treeTable, id) {}//选中时操作

    };
    $('#menuTree').treeTable(option);
});

$('input[type="checkbox"]').on('change',function(){
    // console.log(this);
    checkAll(this);
    checkThis(this);
})

function checkAll(obj){
    var id = $(obj).attr('id');
    var elem = 'input[pid="'+id+'"]';
    // console.log(obj.checked);
    if (obj.checked) {
        $(elem).each(function(){
            this.checked = true;
            $(this).parent('label').addClass('active');
            checkAll(this);
        })
    }  else {
        $(elem).each(function(){
            this.checked = false;
            $(this).parent('label').removeClass('active');
            checkAll(this);
        })
    }
}

function checkThis(obj){
    var elem = 'input[pid="'+0+'"]';
    console.log(obj.checked);
    if(obj.checked) {
        if($(obj).parent('label').text() == '查看'){
            obj.checked = true;
            $(obj).parent('label').addClass('active');
        }
    }else{
        if($(obj).parent('label').text() == '查看'){
            obj.checked = false;
            $(obj).parent('label').removeClass('active');
        }
    }

}
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