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
    .form-horizontal{margin-top: 20px;}
    .control-label{width: 100px !important;padding-top: 8px !important;margin-right: 12px;}
</style>
</head>
<body>
<div class="wrap js-check-wrap">
    <ul class="nav nav-tabs">
        <li><a href="<?php echo U('school/index');?>">学校管理</a></li>
        <li class="active"><a href="<?php echo U('school/profession');?>">专业管理</a></li>
    </ul>
    <?php $status=['0'=>'<font color="#20b2aa">正常</font>','1'=>'<font color="#dc143c">停用</font>']; ?>
    <form class="well form-search" method="post" autocomplete="off" action="<?php echo U('school/profession');?>">
        文本搜索：
        <input type="text" id="keyword" name="keyword" value="<?php echo I('request.keyword');?>" placeholder="请输入专业名称"/>
        <button type="submit" class="btn btn-primary">查询</button>
        <a href="javascript:;" class="btn btn-info" id="btnAdd" style="margin-left: 20px;">新增专业</a>
    </form>
    <div class="js-ajax-form">
        <table class="table table-hover table-bordered table-list">
            <thead>
            <tr>
                <th width="50">专业ID</th>
                <th>专业名称</th>
                <th width="200">专业注册人数</th>
                <th width="100">专业状态</th>
                <th width="120"><?php echo L('ACTIONS');?></th>
            </tr>
            </thead>
            <tbody>
            <?php if(empty($profession)): ?><tr><td colspan="4">暂无数据</td></tr><?php endif; ?>
            <?php if(is_array($profession)): foreach($profession as $key=>$vo): ?><tr>
                    <td><?php echo ($vo["id"]); ?></td>
                    <td><?php echo ($vo["pro_name"]); ?></td>
                    <td><?php echo ($vo["number"]); ?></td>
                    <td><?php echo ($status[$vo['status']]); ?></td>
                    <td style="text-align: center">
                        <?php if(($vo["status"]) == "0"): ?><a href="<?php echo U('school/ban_pro',array('id'=>$vo['id']));?>" class="js-ajax-dialog-btn"
                               data-msg="确定停用此专业?">停用</a>
                            <?php else: ?>
                            <a href="<?php echo U('school/cancelban_pro',array('id'=>$vo['id']));?>" class="js-ajax-dialog-btn"
                               data-msg="确定启用此专业?">启用</a><?php endif; ?>
                        | <a href="javascript:;" data-id="<?php echo ($vo["id"]); ?>" data-name="<?php echo ($vo["pro_name"]); ?>" class="btnEdit">编辑</a>
                    </td>
                </tr><?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
    <div class="pagination"><?php echo ($page); ?></div>
</div>
<script src="/public/js/common.js"></script>
<script src="/public/js/layer/layer.js"></script>
<script type="text/javascript">
    var action_code = 1;
    //新增专业
    $("#btnAdd").on('click',function () {
        var html ='<div class="form-horizontal">' +
            '<div class="form-group">' +
            '   <label class="col-sm-2 control-label">专业名称</label>' +
            '   <div class="col-md-7 col-sm-8">' +
            '       <input style="width: 180px !important;" type="text" placeholder="请输入专业名称" id="proName" maxlength="20" name="pro_name">' +
            '   </div>' +
            '</div></div>';
        layer.open({
            title: '新增专业',
            type: 1,
            content: html,
            area: ['360px','180px'],
            btn: ['保存','取消'],
            yes: function (index) {
                var pro_name = $("#proName").val().trim();
                layer.confirm('确定新增该专业？', {icon: 3, title:'提示'}, function(index2){
                    if(action_code === 1) {
                        action_code = 0;
                        $.ajax({
                            type: 'POST',
                            url: '<?php echo U("School/add_pro_post");?>',
                            data: {pro_name:pro_name},
                            success: function (res) {
                                if (res.status === 1) {
                                    layer.close(index);
                                    layer.msg(res.info, {icon: 1, time: 2000}, function () {
                                        location.reload();
                                    });
                                } else {
                                    action_code = 1;
                                    layer.msg(res.info, {icon: 2, time: 2000});
                                }
                            }
                        });
                        layer.close(index2);
                    }
                });
            }
        })
    });

    //编辑专业
    $(".btnEdit").on('click',function () {
        var the_id = parseInt($(this).attr('data-id'));
        var the_name = $(this).attr('data-name');
        var html ='<div class="form-horizontal">' +
            '<div class="form-group">' +
            '   <label class="col-sm-2 control-label">专业名称</label>' +
            '   <div class="col-md-7 col-sm-8">' +
            '       <input style="width: 180px !important;" value="'+the_name+'" type="text" placeholder="请输入专业名称" id="proName" maxlength="20" name="pro_name">' +
            '   </div>' +
            '</div></div>';
        layer.open({
            title: '编辑专业',
            type: 1,
            content: html,
            area: ['360px','180px'],
            btn: ['保存','取消'],
            yes: function (index) {
                var pro_name = $("#proName").val().trim();
                layer.confirm('确定编辑该专业？', {icon: 3, title:'提示'}, function(index2){
                    if(action_code === 1) {
                        action_code = 0;
                        $.ajax({
                            type: 'POST',
                            url: '<?php echo U("School/edit_pro_post");?>',
                            data: {id:the_id,pro_name:pro_name},
                            success: function (res) {
                                if (res.status === 1) {
                                    layer.close(index);
                                    layer.msg(res.info, {icon: 1, time: 2000}, function () {
                                        location.reload();
                                    });
                                } else {
                                    action_code = 1;
                                    layer.msg(res.info, {icon: 2, time: 2000});
                                }
                            }
                        });
                        layer.close(index2);
                    }
                });
            }
        })
    })
</script>
</body>
</html>