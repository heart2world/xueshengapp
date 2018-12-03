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
        <li><a href="<?php echo U('school/index',array('type'=>$type));?>">学校管理</a></li>
        <?php if($type == 1): ?><li class="active"><a href="<?php echo U('school/add');?>">新增大学</a></li>
            <?php else: ?>
            <li class="active"><a href="<?php echo U('school/add');?>">新增高中</a></li><?php endif; ?>
    </ul>
    <form method="post" class="form-horizontal js-ajax-form" action="<?php echo U('school/add_post');?>">
        <fieldset>
            <div class="control-group">
                <label class="control-label">学校名称</label>
                <div class="controls">
                    <input type="text" autocomplete="off" placeholder="输入学校名称" maxlength="20" name="school_name">
                    <span class="form-required">*</span>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">学校首字母</label>
                <div class="controls">
                    <input type="text" autocomplete="off" placeholder="输入学校名称首字母" maxlength="1" name="first">
                    <span class="form-required">(如不填写则自动识别,多音字可能识别有误)</span>
                </div>
            </div>

            <?php if($type == 1): ?><div class="control-group">
                    <label class="control-label">学校类型</label>
                    <div class="controls">
                        <input type="text" placeholder="输入学校类型" maxlength="20" name="professional_type">
                        <span class="form-required">*</span>
                    </div>
                </div><?php endif; ?>

            <div class="control-group">
                <label class="control-label">所在地址</label>
                <div class="controls">
                    <textarea name="address" placeholder="学校所在地址" rows="5" cols="57"></textarea>
                    <span class="form-required">*</span>
                </div>
            </div>
        </fieldset>
        <div class="form-actions">
            <input type="hidden" name="type" value="<?php echo ($type); ?>">
            <a class="btn" href="javascript:history.back(-1);"><?php echo L('BACK');?></a>
            <button type="submit" class="btn btn-success js-ajax-submit"><?php echo L('ADD');?></button>
        </div>
    </form>
</div>
<script src="/public/js/common.js"></script>
</body>
</html>