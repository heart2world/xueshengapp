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
        <li class="active"><a href="<?php echo U('gift/edit');?>">编辑商品</a></li>
    </ul>
    <form method="post" class="form-horizontal js-ajax-form layui-form" action="<?php echo U('gift/edit_post');?>">
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
                    <input type="text" autocomplete="off" value="<?php echo ($data["gift_name"]); ?>" placeholder="商品名称" name="gift_name">
                    <input type="hidden" value="<?php echo ($data["id"]); ?>" name="id">
                </div>
            </div>

            <div class="control-group" >
                <label class="control-label">商品类型</label>
                <div id="type" data-type="<?php echo ($data["type"]); ?>" class="controls">
                    <input type="radio"  name="type" title="积分" value="1" />
                    <input type="radio" name="type" title="赞助" value="2"  />
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">商品单价(分/元)</label>
                <div class="controls">
                    <input type="number" value="<?php echo ($data["price"]); ?>" name="price" placeholder="单价">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">商品库存</label>
                <div class="controls">
                    <input type="number" value="<?php echo ($data["surplus"]); ?>" name="surplus" placeholder="库存">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">商品封面</label>
                <div class="controls">
                    <input type="hidden" name="cover_img" id="thumb" value="<?php echo ($data["cover_img"]); ?>">
                    <a href="javascript:upload_one_image('图片上传','#thumb');">
                        <img src="<?php echo sp_get_image_preview_url($data['cover_img']);?>" onerror="this.src='/admin/themes/simplebootx/Public/assets/images/default-thumbnail.png'" id="thumb-preview" width="135" style="cursor: hand" />
                    </a>
                    <input type="button" class="btn btn-small" onclick="
                            $('#thumb-preview').attr('src','/admin/themes/simplebootx/Public/assets/images/default-thumbnail.png');
                            $('#thumb').val('');return false;" value="取消图片">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">商品介绍</label>
                <div class="controls">
                    <button type="button" class="btn btn-success" id="add_one_img">添加图片</button>
                </div>
                <div class="controls clearfix" id="intro_box"></div>
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
    <div class="int_list" style="width: 150px;margin-top: 10px;float: left;margin-right: 10px;position: relative;">
        <img src="/data/upload/{{item.img}}" id="img_{{index}}" onerror="this.src='/admin/themes/simplebootx/Public/assets/images/default-thumbnail.png'" />
        <input type="hidden" name="img[{{index}}]" id="input_img_{{index}}" class="intro_info"  value="{{item.img}}">
        <input type="text" style="width: 135px;margin-top: 5px" name="sort[{{index}}]" placeholder="排序" value="{{item.sort}}">
        <span style="position: absolute;top:0;right:0;z-index: 2;width: 18px;height: 18px;text-align: center;cursor: pointer;background-color: rgba(0,0,0,.5);color: white;border-radius: 2px;" class="delImg">x</span>
    </div>
    {{#  }) }}
</script>

<script>


    layui.use(["form",'laytpl','upload'], function () {
        let form = layui.form;
        let laytpl = layui.laytpl;
        let upload = layui.upload;

        let data= eval('<?php echo htmlspecialchars_decode($data["product_intro"]);?>');
        if(data){
            let html = laytpl($("#temp").html()).render(data);
            $("#intro_box").html(html);
            for (let i in data){init_upload("img_"+i)}
        }



        /**
         * 初始化单选按钮选中
         */
        setTimeout(function () {
            let type = $("#type").data("type");
            $("input[value="+type+"]").click();
            form.render()
        },10);

        //追加一张
        $("#add_one_img").on("click",function () {
            let intList = $("#intro_box .int_list");
            let sort = intList.hasClass("int_list") ? intList.length:0;
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
            //删除介绍
            $(".delImg").on('click',function () {
                $(this).closest('.int_list').remove();
            });
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
                    }else{
                        layer.msg(res.info,{icon:2});
                    }
                    layer.closeAll('loading'); //关闭loading
                }
            });
        }

    });
</script>
</body>
</html>