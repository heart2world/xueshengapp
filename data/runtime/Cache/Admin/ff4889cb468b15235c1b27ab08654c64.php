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
        <li class="active"><a href="<?php echo U('Score/link');?>">积分设置</a></li>
    </ul>
    <form method="post" class="form-horizontal layui-form" action="<?php echo U('Score/link_post');?>">
        <fieldset>
            <div class="control-group">
                <label class="control-label" style="width: 350px;text-align: left;padding-left: 100px">连续登录积分（保存后明日开始生效）</label><br><br>
                <div class="controls">
                    首天登录获得 <input type="number" style="width: 100px;padding: 2px 6px" autocomplete="off" value="<?php echo ($score["first_day"]); ?>" max="99999" placeholder="" name="first_day"> 积分 <br><br>
                    连续登录每次递增 <input type="number" style="width: 100px;padding: 2px 6px" autocomplete="off" value="<?php echo ($score["increase_progressively"]); ?>" max="99999" placeholder="" name="increase_progressively"> 积分 <br><br>
                    连续登录单次最多 <input type="number" style="width: 100px;padding: 2px 6px" autocomplete="off" value="<?php echo ($score["max_single_time"]); ?>" max="99999" placeholder="" name="max_single_time"> 积分 <br><br>
                </div>
            </div>
            <div class="control-group">
                <label style="width: 350px;text-align: left;padding-left: 100px"  class="control-label">额外积分（保存后明日开始生效）</label><br><br>
                <div class="controls">
                    发布讨论获得 <input type="number" style="width: 100px;padding: 2px 6px" autocomplete="off" value="<?php echo ($score["publish_discussion"]); ?>" max="99999" placeholder="" name="publish_discussion"> 积分 ，
                    每人每天限 <input type="number" style="width: 100px;padding: 2px 6px" autocomplete="off" value="<?php echo ($score["publish_discussion_every_day_max"]); ?>" max="99999" placeholder="" name="publish_discussion_every_day_max"> 次 <br><br>
                    发表回复获得 <input type="number" style="width: 100px;padding: 2px 6px" autocomplete="off" value="<?php echo ($score["publish_reply"]); ?>" placeholder="" max="99999" name="publish_reply"> 积分 ，
                    每人每天限 <input type="number" style="width: 100px;padding: 2px 6px" autocomplete="off" value="<?php echo ($score["publish_reply_every_day_max"]); ?>" max="99999" placeholder="" name="publish_reply_every_day_max"> 次 <br><br>
                    认证通过获得 <input type="number" style="width: 100px;padding: 2px 6px" autocomplete="off" value="<?php echo ($score["pass_authentication"]); ?>" max="99999" placeholder="" name="pass_authentication"> 积分
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <button type="button" lay-submit lay-filter="save" class="btn btn-success " id="voluntary"><?php echo L('SAVE');?></button>
                </div>
            </div>

        </fieldset>
    </form>
</div>
<script src="/public/js/common.js"></script>
<script src="/public/layui/layui.js"></script>
<script src="/public/js/app.js?_=<?php echo time();?>"></script>
<script>


    layui.use(["form" ], function () {
        let form = layui.form;

        form.on("submit(save)",function (data) {
            let field= data.field;
            request({
                url:"<?php echo U('Score/editPost');?>",
                data:field,
            });
            return false
        });

    });
</script>
</body>
</html>