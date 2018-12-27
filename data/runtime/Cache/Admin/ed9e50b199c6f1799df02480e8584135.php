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
</head>
<body>
	<div class="wrap js-check-wrap">
		<ul class="nav nav-tabs">
			<li class="active"><a href="<?php echo U('slide/index');?>"><?php echo L('ADMIN_SLIDE_INDEX');?></a></li>
			<?php if(count($slides) < 5): ?><li><a href="<?php echo U('slide/add');?>">新增轮播图</a></li><?php endif; ?>
		</ul>
		<form class="js-ajax-form" method="post">
			<?php $status=array("1"=>L('DISPLAY'),"0"=>L('HIDDEN')); ?>
			<table class="table table-hover table-bordered table-list">
				<thead>
					<tr>
						<th width="50"><?php echo L('SORT');?></th>
						<th width="50">ID</th>
						<th width="200"><?php echo L('TITLE');?></th>
						<th width="200"><?php echo L('DESCRIPTION');?></th>
						<th width="100"><?php echo L('LINK');?></th>
						<th width="50"><?php echo L('IMAGE');?></th>
						<th width="50"><?php echo L('STATUS');?></th>
						<th width="100"><?php echo L('ACTIONS');?></th>
					</tr>
				</thead>
				<?php if(empty($slides)): ?><tr><td colspan="8">暂无数据</td></tr><?php endif; ?>
				<?php if(is_array($slides)): foreach($slides as $key=>$vo): ?><tr>
					<td><?php echo ($vo["listorder"]); ?></td>
					<td><?php echo ($vo["slide_id"]); ?></td>
					<td><?php echo ($vo["slide_name"]); ?></td>
					<td><?php echo ($slide_des = mb_substr($vo['slide_des'], 0, 48,'utf-8')); ?></td>
					<td><?php echo ($vo["slide_url"]); ?></td>
					<td>
						<?php if(!empty($vo['slide_pic'])): ?><a href="<?php echo sp_get_image_preview_url($vo['slide_pic']);?>" target="_blank">
								<img style="max-width: 100px;" src="<?php echo sp_get_image_preview_url($vo['slide_pic']);?>">
							</a><?php endif; ?>
					</td>
					<td><?php echo ($status[$vo['slide_status']]); ?></td>
					<td>
						<a href="<?php echo U('slide/delete',array('id'=>$vo['slide_id']));?>" class="js-ajax-delete"><?php echo L('DELETE');?></a> |
						<?php if(empty($vo['slide_status']) == 1): ?><a href="<?php echo U('slide/cancelban',array('id'=>$vo['slide_id']));?>" class="js-ajax-dialog-btn" data-msg="确定显示此轮播图吗？"><?php echo L('DISPLAY');?></a>
						<?php else: ?> 
							<a href="<?php echo U('slide/ban',array('id'=>$vo['slide_id']));?>" class="js-ajax-dialog-btn" data-msg="确定隐藏此轮播图吗？"><?php echo L('HIDE');?></a><?php endif; ?>
						| <a href="<?php echo U('slide/edit',array('id'=>$vo['slide_id']));?>"><?php echo L('EDIT');?></a>
					</td>
				</tr><?php endforeach; endif; ?>

			</table>
		</form>
	</div>
	<script src="/public/js/common.js"></script>
	<script>
		setCookie('refersh_time', 0);
		function refersh_window() {
			var refersh_time = getCookie('refersh_time');
			if (refersh_time == 1) {
				window.location.reload();
			}
		}
		setInterval(function() {
			refersh_window()
		}, 3000);
	</script>
</body>
</html>