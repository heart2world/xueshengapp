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
<style type="text/css">
    .action a {color: #00a2d4;}
    .form-horizontal .controls{padding-top: 6px;}
    .controls button{margin-right: 20px;}
    .label{cursor: default;}
</style>
</head>
<body>
<div class="wrap">
    <ul class="nav nav-tabs">
        <li><a href="<?php echo U('authentication/index');?>">认证管理</a></li>
        <li class="active"><a href="">认证详情</a></li>
    </ul>
    <form method="post" class="form-horizontal js-ajax-form" action="">
        <fieldset>
            <div class="control-group">
                <label class="control-label">认证ID</label>
                <div class="controls">
                    <?php echo ($data["id"]); ?>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">认证用户</label>
                <div class="controls">
                    <?php echo ($data["user_name"]); ?>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">认证类型</label>
                <div class="controls">
                    <?php $type = ['1'=>'大学','2'=>'高中',]; ?>  <?php echo ($type[$data['type']]); ?>
                </div>
            </div>

            <div class="control-group"  >
                <label class="control-label">认证学校</label>
                <div class="controls">
                    <?php echo ($data["school_name"]); ?>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">认证图片</label>
                <div class="controls">
                    <a href="<?php echo sp_get_image_preview_url($data['student_img']);?>" target="_blank">
                        <img src="<?php echo sp_get_image_preview_url($data['student_img']);?>" style="max-height: 200px;" alt="认证图片">
                    </a>
                </div>
            </div>

            <div class="control-group"  >
                <label class="control-label">申请时间</label>
                <div class="controls">
                    <?php echo date("Y-m-d H:i:s",$data['create_time']);?>
                </div>
            </div>

            <?php if(($data["status"]) == "0"): ?><div class="control-group">
                    <div class="controls">
                        <button type="button" data-status="1" class="btn btn-success">通过</button>
                        <button type="button" data-status="2"  class="btn btn-danger">拒绝</button>
                        <a class="btn btn-default" href="javascript:history.go(-1);">取消</a>
                    </div>
                </div>
                <?php else: ?>
                <div class="control-group">
                    <label class="control-label">状态</label>
                    <div class="controls">
                        <?php if($data["status"] == 1): ?><label class="label label-success">已通过</label>
                            <?php else: ?>
                            <label class="label">未通过</label><?php endif; ?>
                    </div>
                </div><?php endif; ?>
        </fieldset>
    </form>
</div>
<script src="/public/js/common.js"></script>
<script src="/public/layui/layui.js"></script>
<script src="/public/js/app.js"></script>
<script>
    $("button.btn").on("click",function () {
        let that= $(this);
        let status = that.data("status");
        let uid = $("#uid").val();
        layer.confirm("确定<font color='#f60'> "+that.html()+" </font>此用户的认证吗?",function () {
            request({
                url:"<?php echo U('authentication/edit_post');?>",
                data:{id:"<?php echo ($data["id"]); ?>",status:status},
                done:function (res) {
                    if(res.state==='success'){
                        setTimeout(function () {
                            location.reload();
                        },1000)
                    }
                }
            })
        })
    });
</script>
</body>
</html>