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
        <li><a href="<?php echo U('gift/index');?>">商品管理</a></li>
        <li class="active"><a href="">商品详情</a></li>
    </ul>
    <form class="form-horizontal js-ajax-form layui-form">
        <fieldset>
            <div class="control-group">
                <label class="control-label">商 品 I D </label>
                <div class="controls">
                    <div style="padding-top: 6px"><?php echo ($data["id"]); ?></div>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">商品名称</label>
                <div class="controls">
                    <div style="padding-top: 6px"><?php echo ($data["gift_name"]); ?></div>
                </div>
            </div>

            <div class="control-group" >
                <label class="control-label">商品类型</label>
                <div class="controls">
                    <?php $types = array('1'=>'积分','2'=>'赞助'); ?>
                    <div style="padding-top: 6px"><?php echo ($types[$data['type']]); ?></div>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">商品单价</label>
                <div class="controls">
                    <?php $prices = array('1'=>'分','2'=>'元'); ?>
                    <div style="padding-top: 6px"><?php echo ($data["price"]); echo ($prices[$data['type']]); ?></div>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">商品库存</label>
                <div class="controls">
                    <div style="padding-top: 6px"><?php echo ($data["surplus"]); ?></div>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">商品封面</label>
                <div class="controls">
                    <img src="<?php echo sp_get_image_preview_url($data['cover_img']);?>" style="max-width: 200px;" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">商品介绍</label>
                <div class="controls clearfix" id="intro_box"></div>
            </div>
        </fieldset>
    </form>
</div>
<script src="/public/js/common.js"></script>
<script src="/public/layui/layui.js"></script>
<script src="/public/js/app.js?_=<?php echo time();?>"></script>
<script type="text/html" id="temp">
    {{#  layui.each(d, function(index, item){
            index = item.index||index;
    }}
    <div class="int_list" style="width: 150px;margin-top: 10px;float: left;margin-right: 10px;">
        <img src="/data/upload/{{item.img}}" />
        <input type="text" style="width: 135px;margin-top: 5px;border: none;" readonly value="{{item.sort}}">
    </div>
    {{#  }) }}
</script>
<script>
    layui.use(["form",'laytpl','upload'], function () {
        let laytpl = layui.laytpl;
        let data= eval('<?php echo htmlspecialchars_decode($data["product_intro"]);?>');
        if(data){
            let html = laytpl($("#temp").html()).render(data);
            $("#intro_box").html(html);
        }
    });
</script>
</body>
</html>