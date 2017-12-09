<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:55:"D:\wwwroot\kwdcdttdcom\public\theme\admin\pay\edit.html";i:1504167072;s:60:"D:\wwwroot\kwdcdttdcom\public\theme\admin\layout\layout.html";i:1503643102;s:60:"D:\wwwroot\kwdcdttdcom\public\theme\admin\layout\static.html";i:1504078526;s:56:"D:\wwwroot\kwdcdttdcom\public\theme\admin\layout\js.html";i:1504078526;}*/ ?>
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
<script src="<?php echo $static_path; ?>/js/uploadImg.js"></script>
<form class="form-horizontal js-ajax-form clearfix" action='<?php echo url('edit',['id'=>$cards['id']]); ?>' method='post'>
    <!-- 自定义大小 -->
    <div class="form-group">
        <label for="account" class="col-sm-2 control-label">开户名称</label>
        <div class="col-sm-4">
            <input type="text" name="account" class="form-control" id="account" value="<?php echo $cards['account']; ?>">
        </div>
    </div>
    <!-- <div class="form-group">
        <label for="bank_code" class="col-sm-2 control-label">开户银行</label>
        <div class="col-sm-4">
            <input type="text" name="bank_code" class="form-control" id="bank_code" value="<?php echo $cards['bank_code']; ?>">
        </div>
    </div> -->
    <div class="form-group">
       <label for="ex2" class="col-sm-2 control-label">开户银行</label>
       <div class="col-sm-4">
           <select id="ex2" class="form-control"  name="bank_code">
               <option value="">--开户银行--</option>
               <?php if(is_array($banks) || $banks instanceof \think\Collection || $banks instanceof \think\Paginator): $i = 0; $__LIST__ = $banks;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
               <option value="<?php echo $vo; ?>" <?php if($cards['bank_code'] == $vo): ?>selected<?php endif; ?>><?php echo $vo; ?></option>
               <?php endforeach; endif; else: echo "" ;endif; ?>
           </select>
       </div>
   </div>

    <div class="form-group">
        <label for="bank_addr" class="col-sm-2 control-label">开户地址</label>
        <div class="col-sm-4">
            <input type="text" name="bank_addr" class="form-control" id="bank_addr" value="<?php echo $cards['bank_addr']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="bankcard_num" class="col-sm-2 control-label">银行账号</label>
        <div class="col-sm-4">
            <input type="text" name="bankcard_num" class="form-control" id="bankcard_num" value="<?php echo $cards['bankcard_num']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="" class="col-sm-2 control-label">状态</label>
        <div class="col-sm-4">
              <label class="i-lab"><input type="radio" name="status" value='1' class="mgr mgr-primary" <?php if($cards['status'] == 1): ?>checked<?php endif; ?>><span>正常</span></label>
              <label class="i-lab"><input type="radio" name="status" value='0' class="mgr mgr-primary" <?php if($cards['status'] == 0): ?>checked<?php endif; ?> ><span>禁用</span></label>
        </div>
    </div>

    <div class="form-group">
        <label for="image" class="col-sm-2 control-label">上传图片</label>
        <?php if($cards['icon'] != ''): ?>
        <div class="img_cont col-sm-1" style="margin:10px 20px 0 0; ">
            <div class="img_prev"><img src="<?php echo $public_path; ?>/upload/<?php echo $cards['icon']; ?>"/></div>
            <input type="hidden" name="img" value="<?php echo $cards['icon']; ?>" />
        </div>
        <?php endif; ?>
        <div class="img_cont col-sm-1">
            <div class="img_prev"></div>
            <input type="file" name="icon" id="image" />
        </div>
    </div>

    <div class="form-group">
        <label for="inputPassword3" class="col-sm-2 control-label"></label>
        <div class="col-sm-4">
            <button type="submit" class="btn btn-info js-submit-btn mr_3px">确认</button>
            <button type="reset" class="btn btn-info">重置</button>
        </div>
    </div>
</form>

<script type="text/javascript">
    $('#image').imgUpload({
        width:100,//预览宽度
        height:100,//预览高度
        imgWidth:100,//图片上传宽度
        imgHeight:100,//图片上传高度
        isCut:false,//是否裁剪
        maxSize: 10240,//允许上传最大值(KB)
        allowedNum:1//允许上传最大数量
    });
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