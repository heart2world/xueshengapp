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
			<li><a href="<?php echo U('slide/index');?>"><?php echo L('ADMIN_SLIDE_INDEX');?></a></li>
			<li class="active"><a>编辑轮播图</a></li>
		</ul>
		<form action="<?php echo U('slide/edit_post');?>" method="post" class="form-horizontal js-ajax-form" enctype="multipart/form-data">
			<fieldset>
				<div class="control-group">
					<label class="control-label"><span class="form-required">*</span><?php echo L('TITLE');?></label>
					<div class="controls">
						<input type="text"  autocomplete="off" style="width: 400px;" name="slide_name" value="<?php echo ($slide_name); ?>" placeholder="请输入轮播图名称"/>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><span class="form-required">*</span>图片</label>
					<div class="controls">
					<input type="hidden" name="slide_pic" id="thumb" value="<?php echo ($slide_pic); ?>">
					<a href="javascript:upload_one_image('图片上传','#thumb');">
						<?php if(empty($slide_pic)): ?><img src="/admin/themes/simplebootx/Public/assets/images/default-thumbnail.png" id="thumb-preview" width="135" style="cursor: hand"/>
							<?php else: ?>
							<img src="<?php echo sp_get_image_preview_url($slide_pic);?>" id="thumb-preview" width="135" style="cursor: hand; height: 113px;"/><?php endif; ?>
					</a>
					<input type="button" class="btn btn-small" onclick="$('#thumb-preview').attr('src','/admin/themes/simplebootx/Public/assets/images/default-thumbnail.png');$('#thumb').val('');return false;" value="取消图片">
					<span>(建议尺寸:750*350)</span>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">排序</label>
					<div class="controls">
						<input type="number"  autocomplete="off" style="width: 400px;" value="<?php echo ($listorder); ?>" name="listorder" placeholder="请输入序号"/>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo L('LINK');?></label>
					<div class="controls">
						<input type="text" autocomplete="off" name="slide_url" value="<?php echo ($slide_url); ?>" style="width: 400px">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo L('DESCRIPTION');?></label>
					<div class="controls">
						<input type="text"  autocomplete="off" name="slide_des" value="<?php echo ($slide_des); ?>" style="width: 400px">
					</div>
				</div>
			</fieldset>
			<div class="form-actions">
				<input type="hidden" name="slide_id" value="<?php echo ($slide_id); ?>"/>
				<button class="btn btn-primary js-ajax-submit" type="submit">提交</button>
				<a class="btn" href="javascript:history.back(-1);">返回</a>
			</div>
		</form>
	</div>
	<script type="text/javascript" src="/public/js/common.js"></script>
</body>
</html>