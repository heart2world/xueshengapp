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
<link rel="stylesheet" href="/public/layui/css/layui.css">
<style>
    .action a {
        color: #00a2d4;
    }
</style>
</head>
<body>
<div class="wrap">
    <ul class="nav nav-tabs">
        <li><a href="<?php echo U('scripture/index');?>">学霸笔记管理</a></li>
        <li class="active"><a href="<?php echo U('scripture/add');?>">添加笔记</a></li>
    </ul>
    <form method="post" class="form-horizontal js-ajax-form layui-form" action="<?php echo U('scripture/add_post');?>">
        <fieldset>

            <div class="control-group">
                <label class="control-label">标题</label>
                <div class="controls">
                    <input type="text" autocomplete="off"  placeholder="输入文章标题" name="title">
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">封面</label>
                <div class="controls">

                    <div class="span3">
                        <div style="text-align: center;">
                            <input type="hidden" name="cover_img" id="thumb" value="">
                            <a href="javascript:upload_one_image('图片上传','#thumb');">
                                <img src="" onerror="this.src='/admin/themes/simplebootx/Public/assets/images/default-thumbnail.png'" id="thumb-preview" width="135" style="cursor: hand" />
                            </a>
                            <input type="button" class="btn btn-small" onclick="
                            $('#thumb-preview').attr('src','/admin/themes/simplebootx/Public/assets/images/default-thumbnail.png');
                            $('#thumb').val('');return false;" value="取消图片">
                        </div>
                    </div>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">商品介绍</label>
                <div class="controls clearfix" style="min-width: 800px;width:60%">
                    <script type="text/plain" id="content" name="content" ></script>
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <button type="submit" class="btn btn-success js-ajax-submit"><?php echo L('SAVE');?></button>
                </div>
            </div>
        </fieldset>


    </form>
</div>
<script src="/public/js/common.js"></script>
<script type="text/javascript">
    //编辑器路径定义
    var editorURL = GV.WEB_ROOT;
</script>
<script type="text/javascript" src="/public/js/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="/public/js/ueditor/ueditor.all.min.js"></script>
<script src="/public/js/app.js?_=<?php echo time();?>"></script>


<script>
    //编辑器
    $("#content").height(300)
    editorcontent = new baidu.editor.ui.Editor();
    editorcontent.render('content');
    try {
        editorcontent.sync();
    } catch (err) {
    }

</script>
</body>
</html>