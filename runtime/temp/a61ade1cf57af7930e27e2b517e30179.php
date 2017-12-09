<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:57:"D:\wwwroot\kwdcdttdcom\public\theme\admin\data\video.html";i:1504252917;s:60:"D:\wwwroot\kwdcdttdcom\public\theme\admin\layout\layout.html";i:1503643102;s:60:"D:\wwwroot\kwdcdttdcom\public\theme\admin\layout\static.html";i:1504078526;s:56:"D:\wwwroot\kwdcdttdcom\public\theme\admin\layout\js.html";i:1504078526;}*/ ?>
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
<div class="panel">
	<input type="hidden" name="video" value="">
	<div id="a1"></div>
	</div>
<script type="text/javascript" src="<?php echo $js; ?>ckplayer.js" charset="utf-8"></script>
<script type="text/javascript">
	// var flashvars={
	// 	f:'http://img.ksbbs.com/asset/Mon_1605/0ec8cc80112a2d6.mp4',//视频地址
	// 	a:'',//调用时的参数，只有当s>0的时候有效
	// 	s:'0',//调用方式，0=普通方法（f=视频地址），1=网址形式,2=xml形式，3=swf形式(s>0时f=网址，配合a来完成对地址的组装)
	// 	c:'0',//是否读取文本配置,0不是，1是
	// 	x:'',//调用配置文件路径，只有在c=1时使用。默认为空调用的是ckplayer.xml
	// 	i:'http://www.ckplayer.com/static/images/cqdw.jpg',//初始图片地址
	// 	d:'http://www.ckplayer.com/down/pause6.1_1.swf|http://www.ckplayer.com/down/pause6.1_2.swf',//暂停时播放的广告，swf/图片,多个用竖线隔开，图片要加链接地址，没有的时候留空就行
	// 	u:'',//暂停时如果是图片的话，加个链接地址
	// 	l:'http://www.ckplayer.com/down/adv6.1_1.swf|http://www.ckplayer.com/down/adv6.1_2.swf',//前置广告，swf/图片/视频，多个用竖线隔开，图片和视频要加链接地址
	// 	r:'',//前置广告的链接地址，多个用竖线隔开，没有的留空
	// 	t:'10|10',//视频开始前播放swf/图片时的时间，多个用竖线隔开
	// 	y:'',//这里是使用网址形式调用广告地址时使用，前提是要设置l的值为空
	// 	z:'http://www.ckplayer.com/down/buffer.swf',//缓冲广告，只能放一个，swf格式
	// 	e:'8',//视频结束后的动作，0是调用js函数，1是循环播放，2是暂停播放并且不调用广告，3是调用视频推荐列表的插件，4是清除视频流并调用js功能和1差不多，5是暂停播放并且调用暂停广告
	// 	v:'80',//默认音量，0-100之间
	// 	p:'0',//视频默认0是暂停，1是播放，2是不加载视频
	// 	h:'0',//播放http视频流时采用何种拖动方法，=0不使用任意拖动，=1是使用按关键帧，=2是按时间点，=3是自动判断按什么(如果视频格式是.mp4就按关键帧，.flv就按关键时间)，=4也是自动判断(只要包含字符mp4就按mp4来，只要包含字符flv就按flv来)
	// 	q:'',//视频流拖动时参考函数，默认是start
	// 	m:'',//让该参数为一个链接地址时，单击播放器将跳转到该地址
	// 	o:'',//当p=2时，可以设置视频的时间，单位，秒
	// 	w:'',//当p=2时，可以设置视频的总字节数
	// 	g:'',//视频直接g秒开始播放
	// 	j:'',//跳过片尾功能，j>0则从播放多少时间后跳到结束，<0则总总时间-该值的绝对值时跳到结束
	// 	k:'32|63',//提示点时间，如 30|60鼠标经过进度栏30秒，60秒会提示n指定的相应的文字
	// 	n:'这是提示点的功能，如果不需要删除k和n的值|提示点测试60秒',//提示点文字，跟k配合使用，如 提示点1|提示点2
	// 	wh:'',//宽高比，可以自己定义视频的宽高或宽高比如：wh:'4:3',或wh:'1080:720'
	// 	lv:'0',//是否是直播流，=1则锁定进度栏
	// 	loaded:'loadedHandler',//当播放器加载完成后发送该js函数loaded
	// 	//调用播放器的所有参数列表结束
	// 	//以下为自定义的播放器参数用来在插件里引用的
	// 	my_title:'演示视频标题文字',
	// 	my_url:encodeURIComponent(window.location.href)//本页面地址
	// 	//调用自定义播放器参数结束
	// 	};
	// var params={bgcolor:'#FFF',allowFullScreen:true,allowScriptAccess:'always'};//这里定义播放器的其它参数如背景色（跟flashvars中的b不同），是否支持全屏，是否支持交互
	// var video=['http://img.ksbbs.com/asset/Mon_1605/0ec8cc80112a2d6.mp4->video/mp4'];
	// CKobject.embed('ckplayer/ckplayer.swf','a1','ckplayer_a1','100%','100%',false,flashvars,video,params);
	

	//TODO页面隐藏input中获取文件名称
	var flashvars={
		f:'http://test.baogt.com/download/test.mp4',
		c:0
		};
	var video=['http://test.baogt.com/download/test.mp4->video/mp4'];
	var support=['all'];
	CKobject.embedHTML5('a1','ckplayer_a1',400,230,video,flashvars,support);
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