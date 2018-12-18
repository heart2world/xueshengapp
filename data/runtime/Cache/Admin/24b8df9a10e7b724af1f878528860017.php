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
		<li><a href="<?php echo U('Discuss/category');?>">分类管理</a></li>
		<li class="active"><a href="<?php echo U('Discuss/cate_add');?>">新增分类</a></li>
	</ul>
	<form class="form-horizontal" id="formInfo" autocomplete="off">
		<fieldset>
			<div class="control-group">
				<label class="control-label">分类序号</label>
				<div class="controls">
					<input type="number" name="listorder" placeholder="">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label">分类名称</label>
				<div class="controls">
					<input type="text" name="name" maxlength="20" placeholder="">
				</div>
			</div>
		</fieldset>
		<div class="form-actions">
			<a class="btn" href="javascript:history.back(-1);">取消</a>
			<input class="btn btn-info" id="btnSubmit" type="button" value="保存">
		</div>
	</form>
</div>
<script src="/public/js/common.js"></script>
<script type="text/javascript">
	var action_code = 1;
	$("#btnSubmit").on('click',function () {
		if(action_code === 1){
		    action_code = 0;
		    var valueInfo = $("#formInfo").serialize();
		    $.ajax({
				type: 'POST',
				url: '<?php echo U("Discuss/cate_add_post");?>',
				data: valueInfo,
				dataType: 'json',
				success: function (res) {
					if(res.status === 1){
                        layer.msg(res.info,{icon:6,time:1000},function () {
							location.href = '<?php echo U("Discuss/category");?>';
                        });
					}else{
					    action_code = 1;
					    layer.msg(res.info,{icon:5,time:2000});
					}
                }
			})
		}
    })
</script>
</body>
</html>