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
</head>
<body>
<div class="wrap js-check-wrap">
	<ul class="nav nav-tabs">
		<li class="active"><a href="<?php echo U('user/index');?>"><?php echo L('ADMIN_USER_INDEX');?></a></li>
		<li><a href="<?php echo U('user/add');?>"><?php echo L('ADMIN_USER_ADD');?></a></li>
	</ul>
	<?php $status=['0'=>'<font color="#dc143c">停用</font>','1'=>'<font color="#20b2aa">正常</font>']; ?>
	<form class="well form-search" method="post" autocomplete="off" action="<?php echo U('User/index');?>">
		文本搜索：
		<input type="text" name="keyword" style="width: 160px;" value="<?php echo I('request.keyword/s','');?>" placeholder="输入手机号/用户姓名">
		<select type="text" name="status" style="width: 120px;height: 35px">
			<option value="">选择状态</option>
			<?php if(is_array($status)): foreach($status as $key=>$v): $ck = I('request.status')==="$key"?"selected":""; ?>
				<option value="<?php echo ($key); ?>" <?php echo ($ck); ?>><?php echo ($v); ?></option><?php endforeach; endif; ?>
		</select>
		<select type="text" name="role_id" style="width: 150px;height: 35px">
			<option value="">选择角色</option>
			<?php if(is_array($roles)): foreach($roles as $key=>$vr): ?><option value="<?php echo ($vr["id"]); ?>" <?php if(I('request.role_id/s','') == $vr['id']): ?>selected<?php endif; ?>><?php echo ($vr["name"]); ?></option><?php endforeach; endif; ?>
		</select>
		新增时间：
		<input type="text"  style="width: 150px" value="<?php echo I('request.time');?>" name="time" id="time"  placeholder=""  />
		<input type="submit" class="btn btn-primary" value="查询" />
	</form>
	<table class="table table-hover table-bordered table-list">
		<thead>
			<tr>
				<th width="50">用户ID</th>
				<th>手机号</th>
				<th>用户姓名</th>
				<th>用户角色</th>
				<th>新增时间</th>
				<th>用户<?php echo L('STATUS');?></th>
				<th width="120"><?php echo L('ACTIONS');?></th>
			</tr>
		</thead>
		<tbody>
		<?php if(empty($users)): ?><tr><td colspan="7">暂无数据</td></tr><?php endif; ?>
			<?php if(is_array($users)): foreach($users as $key=>$vo): ?><tr>
				<td><?php echo ($vo["id"]); ?></td>
				<td><?php echo ($vo["mobile"]); ?></td>
				<td><?php echo ($vo["user_name"]); ?></td>
				<td><?php echo ($vo["role_name"]); ?></td>
				<td><?php echo ($vo["create_time"]); ?></td>
				<td><?php echo ($status[$vo['user_status']]); ?></td>
				<td>
					<?php if($vo['id'] == 1 || $vo['id'] == sp_get_current_admin_id()): ?><font color="#cccccc"><?php echo L('DELETE');?></font> |
						<?php if($vo['user_status'] == 1): ?><font color="#cccccc">停用</font> |
						<?php else: ?>
							<font color="#cccccc"><?php echo L('ACTIVATE_USER');?></font> |<?php endif; ?>
						<font color="#cccccc"><?php echo L('EDIT');?></font>
					<?php else: ?>
						<a class="js-ajax-delete" href="<?php echo U('user/delete',array('id'=>$vo['id']));?>"><?php echo L('DELETE');?></a> |
						<?php if($vo['user_status'] == 1): ?><a href="<?php echo U('user/ban',array('id'=>$vo['id']));?>" class="js-ajax-dialog-btn" data-msg="<?php echo L('BLOCK_USER_CONFIRM_MESSAGE');?>">停用</a> |
						<?php else: ?>
							<a href="<?php echo U('user/cancelban',array('id'=>$vo['id']));?>" class="js-ajax-dialog-btn" data-msg="<?php echo L('ACTIVATE_USER_CONFIRM_MESSAGE');?>"><?php echo L('ACTIVATE_USER');?></a> |<?php endif; ?>
						<a href='<?php echo U("user/edit",array("id"=>$vo["id"]));?>'><?php echo L('EDIT');?></a><?php endif; ?>
				</td>
			</tr><?php endforeach; endif; ?>
		</tbody>
	</table>
	<div class="pagination"><?php echo ($page); ?></div>
</div>
<script src="/public/js/common.js"></script>
<script src="/public/layui/layui.js"></script>
<script type="text/javascript">
	layui.use(["laydate" ], function () {
		let laydate = layui.laydate;
		laydate.render({elem: '#time', type:'datetime'});
	});
</script>
</body>
</html>