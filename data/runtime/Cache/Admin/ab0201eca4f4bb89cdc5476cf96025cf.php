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
    .type_pane {padding-bottom: 15px;}
    table tr td:first-child {text-align: center;}
    .table-list a{color: #1abc9c !important;cursor: pointer;}
</style>
</head>
<body>
<div class="wrap js-check-wrap">
    <ul class="nav nav-tabs">
        <li class="active"><a href="<?php echo U('authentication/index');?>">认证管理</a></li>
    </ul>
    <?php $url = U('authentication/index') ; ?>
    <?php $status=['0'=>'未处理','1'=>'已通过','2'=>'未通过']; ?>
    <form class="well form-search" method="post" action="<?php echo ($url); ?>">
        关键字：
        <input type="hidden" name="type" value="<?php echo I('type');?>">
        <input type="text" id="keyword" name="keyword" autocomplete="off" value="<?php echo I('request.keyword');?>" placeholder="输入认证用户/认证学校"/>
        认证状态：
        <select type="text" id="status" name="status" style="width: 120px;height: 35px">
            <option value="">全部</option>
            <?php if(is_array($status)): foreach($status as $key=>$v): $ck = I('request.status')==="$key"?"selected":""; ?>
                <option value="<?php echo ($key); ?>" <?php echo ($ck); ?>><?php echo ($v); ?></option><?php endforeach; endif; ?>
        </select>
        认证类型：
        <select type="text" name="type" style="width: 120px;height: 35px">
            <option value="">全部</option>
            <option value="1" <?php if(I('request.type/s','') == 1): ?>selected<?php endif; ?>>大学</option>
            <option value="2" <?php if(I('request.type/s','') == 2): ?>selected<?php endif; ?>>高中</option>
        </select>
        <br>
        <br>
        申请时间：
        <input type="text" style="width: 150px" autocomplete="off" value="<?php echo I('request.start_time');?>" name="start_time" id="start_time" placeholder="开始时间"/> -
        <input type="text" style="width: 150px" autocomplete="off" value="<?php echo I('request.end_time');?>" name="end_time" id="end_time" placeholder="结束时间"/>
        <button type="submit" class="btn btn-primary">查询</button>
    </form>
    <form method="post" class="js-ajax-form">
        <table class="table table-hover table-bordered table-list">
            <thead>
            <tr>
                <th width="50">ID</th>
                <th>认证用户</th>
                <th>认证类型</th>
                <th>认证学校</th>
                <th>申请时间</th>
                <th><?php echo L('STATUS');?></th>
                <th width="80"><?php echo L('ACTIONS');?></th>
            </tr>
            </thead>
            <tbody>
            <?php if(empty($list)): ?><tr><td colspan="7">暂无数据</td></tr><?php endif; ?>
            <?php if(is_array($list)): foreach($list as $key=>$vo): ?><tr>
                    <td><?php echo ($vo["id"]); ?></td>
                    <td><?php echo ($vo["user_name"]); ?></td>
                    <td><?php echo ($vo['type']==1?"大学":"高中"); ?></td>
                    <td><?php echo ($vo["school_name"]); ?></td>
                    <td><?php echo date("Y-m-d H:i:s",$vo['create_time']);?></td>
                    <td><?php echo ($status[$vo['status']]); ?></td>
                    <td style="text-align: center">
                        <a href="<?php echo U('authentication/edit',array('id'=>$vo['id']));?>">详情</a>
                    </td>
                </tr><?php endforeach; endif; ?>
            </tbody>
        </table>
    </form>
    <div class="pagination"><?php echo ($page); ?></div>
</div>
<script src="/public/js/common.js"></script>
<script src="/public/layui/layui.js"></script>
<script type="text/javascript">
    layui.use(["form", 'laytpl', 'laydate'], function () {
        let laydate = layui.laydate;
        laydate.render({elem: '#start_time', type: 'datetime'});
        laydate.render({elem: '#end_time', type: 'datetime'});
    });
</script>
</body>
</html>