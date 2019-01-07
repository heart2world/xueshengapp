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
	input[type="radio"]{margin: -1px 0 0 !important;}
	.radio,.checkbox{padding-left: 0 !important;margin-right: 12px;}
</style>
</head>
<body>
	<div class="wrap">
		<ul class="nav nav-tabs">
			<li><a href="<?php echo U('user/index');?>"><?php echo L('ADMIN_USER_INDEX');?></a></li>
			<li class="active"><a href="">编辑用户</a></li>
		</ul>
		<form method="post" class="form-horizontal js-ajax-form" action="<?php echo U('User/edit_post');?>">
			<fieldset>
				<div class="control-group">
					<label class="control-label">用户姓名</label>
					<div class="controls">
						<input type="text" autocomplete="off" value="<?php echo ($user_name); ?>" name="user_name" placeholder="请输入用户姓名" maxlength="25">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">手机号</label>
					<div class="controls">
						<input type="text" name="mobile" value="<?php echo ($mobile); ?>" placeholder="请输入手机号" maxlength="11">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo L('ROLE');?></label>
					<div class="controls">
						<?php if(is_array($roles)): foreach($roles as $key=>$vo): ?><label class="checkbox inline">
							<?php $role_id_checked=in_array($vo['id'],$role_ids)?"checked":""; ?>
							<input value="<?php echo ($vo["id"]); ?>" type="radio" name="role_id[]" <?php echo ($role_id_checked); ?> <?php if(sp_get_current_admin_id() != 1 && $vo['id'] == 1): ?>disabled="true"<?php endif; ?>><?php echo ($vo["name"]); ?>
						</label><?php endforeach; endif; ?>
					</div>
				</div>
			</fieldset>
			<div class="form-actions">
				<input type="hidden" name="id" value="<?php echo ($id); ?>" />
				<button type="submit" class="btn btn-primary js-ajax-submit"><?php echo L('SAVE');?></button>
				<a class="btn" href="javascript:history.back(-1);"><?php echo L('BACK');?></a>
			</div>
		</form>
	</div>
	<script src="/public/js/common.js"></script>
</body>
</html>