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
    .left_name{width:100px;display:inline-block;text-align: right;margin-right: 30px;}
    .table-list tr td{text-align: center;}
</style>
</head>
<body>
<div class="wrap">
    <ul class="nav nav-tabs">
        <li><a href="<?php echo U('Discuss/info',array('id'=>$discuss_id));?>">基本信息</a></li>
        <li><a href="<?php echo U('Discuss/comment',array('id'=>$discuss_id));?>">评论信息</a></li>
        <li class="active"><a href="">评论详情</a></li>
    </ul>
    <div class="info">
        <div class="clearfix div_box">
            <div><span class="left_name">评论序号</span><span><?php echo ($id); ?></span></div>
            <div><span class="left_name">评论用户</span><span><?php echo ($user_name); ?></span></div>
            <div><span class="left_name">评论内容</span><span><?php echo ($content); ?></span></div>
            <div><span class="left_name">点赞量</span><span><?php echo ($like_num); ?></span></div>
            <label style="width: 120px;"><span class="left_name">回复</span></label>
            <div style="width: 80%;margin-left: 130px;margin-top: -21px;">
                <table class="table table-hover table-bordered table-list">
                    <thead>
                    <tr>
                        <th>回复序号</th>
                        <th>回复内容</th>
                        <th>回复用户</th>
                        <th>回复时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(empty($reply)): ?><tr><td colspan="5" class="no_data">暂无数据</td></tr><?php endif; ?>
                    <?php if(is_array($reply)): foreach($reply as $key=>$vr): ?><tr>
                            <td><?php echo ($key+1); ?></td>
                            <td><?php echo ($vr["content"]); ?></td>
                            <td><?php echo ($vr["user_name"]); ?></td>
                            <td><?php echo date('Y-m-d H:i:s',$vr['create_time']); ?></td>
                            <td class="action">
                                <a href="<?php echo U('Discuss/reply_delete',array('id'=>$vr['id']));?>" class="js-ajax-delete" data-msg="确定删除该回复？">删除</a>
                            </td>
                        </tr><?php endforeach; endif; ?>
                    </tbody>
                </table>
                <div class="pagination"><?php echo ($page); ?></div>
            </div>
        </div>
    </div>
</div>
<script src="/public/js/common.js"></script>
</body>
</html>