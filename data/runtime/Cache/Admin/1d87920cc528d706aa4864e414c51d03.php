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
        <li class="active"><a href="<?php echo U('gift/add');?>">添加商品</a></li>
    </ul>
    <form method="post" class="form-horizontal js-ajax-form layui-form" action="<?php echo U('gift/add_post');?>">
        <fieldset>

            <div class="control-group">
                <label class="control-label">商品名称</label>
                <div class="controls">
                    <input type="text" autocomplete="off" value=" " placeholder="商品名称" name="gift_name">
                </div>
            </div>

            <div class="control-group" >
                <label class="control-label">商品类型</label>
                <div id="type"  class="controls">
                    <input type="radio"  checked name="type" title="积分" value="1" />
                    <input type="radio" name="type" title="赞助" value="2"  />
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">商品单价</label>
                <div class="controls">
                    <input type="number" value="" name="price" placeholder="单价">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">商品库存</label>
                <div class="controls">
                    <input type="number" value="" name="surplus" placeholder="库存">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">商品封面</label>
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
                <div class="controls clearfix" id="intro_box"> </div>
                <div class="controls">
                    <button type="button" class="btn btn-success" id="add_one_img">添加图片</button>
                </div>
            </div>
        </fieldset>
        <div class="form-actions">
            <button type="submit" class="btn btn-success js-ajax-submit"><?php echo L('SAVE');?></button>
        </div>
    </form>
</div>
<script src="/public/js/common.js"></script>
<script src="/public/layui/layui.js"></script>
<script src="/public/js/app.js?_=<?php echo time();?>"></script>
<script type="text/html" id="temp">
    {{#  layui.each(d, function(index, item){
    index = item.index||index;
    }}
    <div class="span3" style="width: 150px;margin-bottom: 10px;">
        <img src="{{item.img}}" id="img_{{index}}" onerror="this.src='/admin/themes/simplebootx/Public/assets/images/default-thumbnail.png'" />
        <input type="hidden" name="img[{{index}}]" id="input_img_{{index}}" class="intro_info"  value="{{item.img}}">
        <input type="text" style="width: 135px;margin-top: 5px" name="sort[{{index}}]" placeholder="排序" value="{{item.sort}}">
    </div>
    {{#  }) }}
</script>

<script>


    layui.use(["form",'laytpl','upload'], function () {
        let form = layui.form;
        let laytpl = layui.laytpl;
        let upload = layui.upload;
        form.render()


        //追加一张
        $("#add_one_img").on("click",function () {
            let span = $("#intro_box .span3");
            let sort = span.hasClass("span3") ? span.length:0;

            if(sort>=10){
                layer.msg("最多上传10张图片",{icon:7});
                return false;
            }

            let html = laytpl($("#temp").html()).render([{
                img:"",sort:(sort+1),index:sort
            }]);
            $("#intro_box").append(html);
            init_upload("img_"+sort);
            $("#intro_box #img_"+sort).click();
        });


        /**
         * 上传初始化
         * @param id
         */
        function init_upload(id) {
            upload.render({
                elem: '#'+id
                ,url: '<?php echo U("gift/upload");?>'
                ,auto: true //选择文件后不自动上传
                //  ,bindAction: '#testListAction' //指向一个按钮触发上传
                ,before: function(obj){ //obj参数包含的信息，跟 choose回调完全一致，可参见上文。
                    layer.load(); //上传loading
                }
                ,choose: function(obj){

                } ,done: function(res, index, upload){
                    if(res.state==='success'){
                        let img_path = res['img_path'];
                        $("#"+id)[0].src = '/data/upload/'+img_path;
                        $("#input_"+id).val(img_path);
                    }
                    layer.closeAll('loading'); //关闭loading
                }
            });
        }

    });
</script>
</body>
</html>