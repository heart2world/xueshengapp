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
        <li><a href="<?php echo U('school/index');?>">学校管理</a></li>
        <li class="active"><a href="<?php echo U('school/edit');?>">编辑学校</a></li>
    </ul>
    <form method="post" class="form-horizontal js-ajax-form" action="<?php echo U('school/edit_post');?>">
        <fieldset>
            <div class="control-group">
                <label class="control-label">学校名称</label>
                <div class="controls">
                    <input type="text" autocomplete="off" value="<?php echo ($data["school_name"]); ?>" maxlength="20" placeholder="输入学校名称" name="school_name">
                    <span class="form-required">*</span>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">学校首字母</label>
                <div class="controls">
                    <input type="text" autocomplete="off" placeholder="输入学校名称首字母" maxlength="1" value="<?php echo ($data["first"]); ?>" name="first">
                    <span class="form-required">(如不填写则自动识别,多音字可能识别有误)</span>
                </div>
            </div>

            <?php if($data["type"] == 1): ?><div class="control-group">
                    <label class="control-label">学校类型</label>
                    <div class="controls">
                        <input type="text" placeholder="输入专业类型" maxlength="20" value="<?php echo ($data["professional_type"]); ?>" name="professional_type">
                        <span class="form-required">*</span>
                    </div>
                </div><?php endif; ?>

            <div class="control-group">
                <label class="control-label">所在地址</label>
                <div class="controls">
                    <textarea name="address" placeholder="学校所在地址" rows="5" cols="57"><?php echo ($data["address"]); ?></textarea>
                    <span class="form-required">*</span>
                </div>
            </div>

            <div class="control-group">
                <div class="controls">
                    <input type="hidden" value="<?php echo ($data["id"]); ?>" name="id">
                    <a class="btn" href="javascript:history.back(-1);"><?php echo L('BACK');?></a>
                    <button type="submit" class="btn btn-success js-ajax-submit"><?php echo L('SAVE');?></button>
                </div>
            </div>
        </fieldset>

        <?php if(($data["type"]) == "1"): $status=array(0=>'正常',1=>'停用'); ?>
            <div class="form-actions" style="background: #fff">
                <button id="add_pro" type="button" class="btn btn-info" style="margin-bottom: 10px">新增专业</button>
                <table class="table table-hover table-bordered table-list" style="width: 200px">
                    <thead>
                    <tr>
                        <th>专业名称</th>
                        <th>状态</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($pro_list)): foreach($pro_list as $key=>$vo): ?><tr>
                            <td><?php echo ($vo["pro_name"]); ?></td>
                            <td><?php echo ($status[$vo['status']]); ?></td>
                            <td class="action">
                                <?php if(($vo["status"]) == "0"): ?><a href="<?php echo U('school/ban_pro',array('id'=>$vo['id']));?>" class="js-ajax-dialog-btn"
                                       data-msg="确定禁用此专业?">停用</a>
                                    <?php else: ?>
                                    <a href="<?php echo U('school/cancelban_pro',array('id'=>$vo['id']));?>" class="js-ajax-dialog-btn"
                                       data-msg="确定启用此专业?">启用</a><?php endif; ?>
                            </td>
                        </tr><?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div><?php endif; ?>
    </form>
</div>
<script type="text/html" id="temp">
    <h3 style="text-align: center;font-size: 16px"> 添加专业 </h3>
    <form class="layui-form control-group" action="<?php echo U('school/add_pro_post');?>" id="formBox"
          style="padding: 25px 10px 0 0">
        <div class="layui-form-item">
            <label class="layui-form-label">专业名称</label>
            <div class="layui-input-block">
                <input type="hidden" name="school_id" value="<?php echo ($data["id"]); ?>"/>
                <input type="text" name="pro_name" lay-verify="required"
                       placeholder="请输入名称"
                       autocomplete="off">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-block">
                <input type="radio" value="0" name="status" title="启用" checked/>
                <input type="radio" value="1" name="status" title="禁用"/>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button style="float: right" class="btn btn-info" lay-submit lay-filter="add">添加</button>
            </div>
        </div>
    </form>
</script>
<script src="/public/js/common.js"></script>
<script src="/public/layui/layui.js"></script>
<script src="/public/js/app.js?_=<?php echo time();?>"></script>
<script>
    layui.use("form", function () {
        let form = layui.form;
        $("#add_pro").on('click', function () {
            openWin();
            form.render()
        });
        form.on("submit(add)", function (data) {
            request({
                data: data.field,
                reload: 1,
                done: function () {
                    setTimeout(function () {
                        layer.closeAll()
                    }, 1000)
                }
            });
            return false;
        });
    });

    function openWin() {
        layer.open({
            title: false,
            area: ["350px", "250px"],
            content: $("#temp").html(),
            shadeClose: 1,
            type: 1,
            btn: false,
            yes: function (index) {

            }, end: function () {

            }
        });
    }

</script>
</body>
</html>