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
        <li class="active"><a href="<?php echo U('scripture/link');?>">链接管理</a></li>
    </ul>
    <form method="post" class="form-horizontal layui-form" action="<?php echo U('scripture/link_post');?>">
        <fieldset>

            <div class="control-group">
                <label class="control-label">分数查询链接:</label>
                <div class="controls">
                    <input type="text" autocomplete="off" value="<?php echo ($link["score"]); ?>" placeholder="请输入分数查询链接" name="score">
                    <button type="button" class="btn btn-success " id="score"><?php echo L('SAVE');?></button>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">志愿填表链接:</label>
                <div class="controls">
                    <input type="text" autocomplete="off" value="<?php echo ($link["voluntary"]); ?>" placeholder="请输入志愿填表链接" name="voluntary">
                    <button type="button" class="btn btn-success " id="voluntary"><?php echo L('SAVE');?></button>
                </div>
            </div>

        </fieldset>
    </form>
</div>
<script src="/public/js/common.js"></script>
<script src="/public/layui/layui.js"></script>
<script src="/public/js/app.js?_=<?php echo time();?>"></script>
<script>


    layui.use(["form",'laytpl','upload'], function () {
        let form = layui.form;

        $("#score").on("click",function () {
            let v= $("input[name=score]").val();
            request({
                url:"<?php echo U('scripture/link_post');?>",
                data:{score:v},
                reload:1,
            })
        });


        $("#voluntary").on("click",function () {
            let v= $("input[name=voluntary]").val();
            request({
                url:"<?php echo U('scripture/link_post');?>",
                reload:1,
                data:{voluntary:v}
            })
        });


    });
</script>
</body>
</html>