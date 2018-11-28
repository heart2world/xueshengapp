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
<div class="wrap">
	<ul class="nav nav-tabs">
		<li><a href="<?php echo U('Discuss/index');?>">讨论管理</a></li>
		<li><a href="<?php echo U('Discuss/category');?>">分类管理</a></li>
		<li class="active"><a href="<?php echo U('Discuss/label');?>">标签管理</a></li>
	</ul>
	<div style="margin-bottom: 12px;">
		<a type="button" class="btn btn-info" href="<?php echo U('Discuss/label_add');?>">新增标签</a>
	</div>
	<div>
		<table class="table table-hover table-bordered table-list">
			<thead>
			<tr>
				<th width="60">分类序号</th>
				<th>分类名称</th>
				<th>分类状态</th>
				<th width="60">操作</th>
			</tr>
			</thead>
			<tbody>
			<?php if(empty($label)): ?><tr><td colspan="4" class="no_data">暂无数据</td></tr><?php endif; ?>
			<?php $status_array = array(0=>'隐藏',1=>'正常'); ?>
			<?php if(is_array($label)): foreach($label as $key=>$vl): ?><tr>
					<td><?php echo ($vl["listorder"]); ?></td>
					<td><?php echo ($vl["name"]); ?></td>
					<td><?php echo ($status_array[$vl['status']]); ?></td>
					<td class="action">
						<?php if($vl["status"] == 1): ?><a href="<?php echo U('Discuss/label_action',array('id'=>$vl['id']));?>" class="js-ajax-dialog-btn" data-msg="确定隐藏该标签吗？">隐藏</a> |
							<?php else: ?>
							<a href="<?php echo U('Discuss/label_action',array('id'=>$vl['id']));?>" class="js-ajax-dialog-btn" data-msg="确定显示该标签吗？">显示</a> |<?php endif; ?>
						<a href="<?php echo U('Discuss/label_edit',array('id'=>$vl['id']));?>">编辑</a>
					</td>
				</tr><?php endforeach; endif; ?>
			</tbody>
		</table>
		<div class="pagination"><?php echo ($page); ?></div>
	</div>
</div>
<script src="/public/js/common.js"></script>
</body>
</html>