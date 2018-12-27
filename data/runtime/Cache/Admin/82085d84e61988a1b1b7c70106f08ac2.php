<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<!-- Set render engine for 360 browser -->
	<meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- HTML5 shim for IE8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <![endif]-->

	<link href="/public/simpleboot/themes/<?php echo C('SP_ADMIN_STYLE');?>/theme.min.css" rel="stylesheet">
    <link href="/public/simpleboot/css/simplebootadmin.css" rel="stylesheet">
    <link href="/public/js/artDialog/skins/default.css" rel="stylesheet" />
    <link href="/public/simpleboot/font-awesome/4.4.0/css/font-awesome.min.css"  rel="stylesheet" type="text/css">
    <style>
		form .input-order{margin-bottom: 0px;padding:3px;width:40px;}
		.table-actions{margin-top: 5px; margin-bottom: 5px;padding:0px;}
		.table-list{margin-bottom: 0px;}
        .table-list tr th,.table-list tr td,.table-list .no_data,.table-list .action{text-align: center}
        .layui-layer-msg{border:1px solid white !important;}
	</style>
	<!--[if IE 7]>
	<link rel="stylesheet" href="/public/simpleboot/font-awesome/4.4.0/css/font-awesome-ie7.min.css">
	<![endif]-->
	<script type="text/javascript">
	//全局变量
	var GV = {
	    ROOT: "/",
	    WEB_ROOT: "/",
	    JS_ROOT: "public/js/",
	    APP:'<?php echo (MODULE_NAME); ?>'/*当前应用名*/
	};
	</script>
    <script src="/public/js/jquery.js"></script>
    <script src="/public/js/wind.js"></script>
    <script src="/public/simpleboot/bootstrap/js/bootstrap.min.js"></script>
    <script src="/public/js/layer/layer.js"></script>
    <script>
    	$(function(){
    		$("[data-toggle='tooltip']").tooltip();
    	});
    </script>
<?php if(APP_DEBUG): ?><style>
		#think_page_trace_open{
			z-index:9999;
		}
	</style><?php endif; ?>
<style type="text/css">
    .info{padding: 0 20px;}
    .div_box{padding: 12px 0;}
    .div_box div{margin-bottom: 20px;}
    .left_name{width:100px;display:inline-block;text-align: right;margin-right: 50px;}
    .table-list tr td{text-align: center;}
</style>
</head>
<body>
<div class="wrap">
    <ul class="nav nav-tabs">
        <li class="active"><a href="">基本信息</a></li>
        <li><a href="<?php echo U('Discuss/comment',array('id'=>$id));?>">评论信息</a></li>
    </ul>
    <div class="info">
        <?php $hot_array = array(1=>'—',2=>'是'); ?>
        <div class="clearfix div_box">
            <div><span class="left_name">讨论标题</span><span><?php echo ($name); ?></span></div>
            <div><span class="left_name">创建用户</span><span><?php echo ($user_name); ?></span></div>
            <div><span class="left_name">讨论所属学校</span><span><?php echo ($school_name); ?></span></div>
            <div>
                <span class="left_name">讨论正文</span><span><?php echo ($content); ?></span>
                <div style="margin-left: 150px;margin-top: 12px;">
                <?php if(is_array($image)): foreach($image as $key=>$vi): ?><span><img src="<?php echo sp_get_image_preview_url($vi);?>" style="max-height: 180px;margin-right: 12px;"></span><?php endforeach; endif; ?>
                </div>
            </div>
            <div><span class="left_name">是否是热门</span><span><?php echo ($hot_array[$hot]); ?></span></div>
            <div style="width: 40%;margin-left: 20px;">
                <table class="table table-hover table-bordered table-list">
                    <thead>
                        <tr>
                            <th>点击量</th>
                            <th>评论量</th>
                            <th>收藏量</th>
                            <th>评论点赞量</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo ($click_num); ?></td>
                            <td><?php echo ($comment_num); ?></td>
                            <td><?php echo ($collect_num); ?></td>
                            <td><?php echo ($like_num); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div><span class="left_name">讨论分类</span><span><?php echo ($category_name); ?></span></div>
            <div><span class="left_name">讨论标签</span><span><?php echo ($label); ?></span></div>
            <div><span class="left_name">创建时间</span><span><?php echo date('Y-m-d H:i:s',$create_time); ?></span></div>
            <div><span class="left_name">最后更新时间</span><span><?php echo date('Y-m-d H:i:s',$update_time); ?></span></div>
        </div>
    </div>
</div>
<script src="/public/js/common.js"></script>
</body>
</html>