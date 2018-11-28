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
</head>
<body>
<div class="wrap">
	<ul class="nav nav-tabs">
		<li class="active"><a href="<?php echo U('Indexadmin/index');?>"><?php echo L('USER_INDEXADMIN_INDEX');?></a></li>
	</ul>
	<form class="well form-search" method="post" autocomplete="off" action="<?php echo U('Indexadmin/index');?>">
		<div>
			文本搜素：
			<input type="text" name="keyword" style="width: 200px;" value="<?php echo I('request.keyword/s','');?>" placeholder="请输入手机号/昵称/学校/专业">
			用户类型：
			<select name="user_type" style="height: 36px;width: 150px;">
				<option value="">请选择用户类型</option>
				<option value="2" <?php if(I('request.user_type/s','') == 2): ?>selected<?php endif; ?>>学生</option>
				<option value="3" <?php if(I('request.user_type/s','') == 3): ?>selected<?php endif; ?>>家长</option>
			</select>
			学校类型：
			<select name="school_type" style="height: 36px;width: 150px;">
				<option value="">请选择学校类型</option>
				<option value="1" <?php if(I('request.school_type/s','') == 1): ?>selected<?php endif; ?>>大学</option>
				<option value="2" <?php if(I('request.school_type/s','') == 2): ?>selected<?php endif; ?>>高中</option>
			</select>
			用户状态：
			<select name="user_status" style="height: 36px;width: 150px;">
				<option value="">请选择用户状态</option>
				<option value="1" <?php if(I('request.user_status/s','') == 1): ?>selected<?php endif; ?>>正常</option>
				<option value="2" <?php if(I('request.user_status/s','') == 2): ?>selected<?php endif; ?>>停用</option>
			</select>
		</div>
		<div style="margin-top: 12px;">
			累积在线时长：
			<input type="number" name="num_last" style="width: 60px;" value="<?php echo I('request.num_last/s','');?>" placeholder=""> —
			<input type="number" name="num_next" style="width: 60px;" value="<?php echo I('request.num_next/s','');?>" placeholder="">
			注册时间：
			<input type="text" class="js-datetime" name="start_time" value="<?php echo I('request.start_time/s','');?>" placeholder="开始时间" style="width: 130px;"> —
			<input type="text" class="js-datetime" name="end_time" value="<?php echo I('request.end_time/s','');?>" placeholder="结束时间" style="width: 130px;">
			<input type="submit" class="btn btn-info" value="查询" />
		</div>
	</form>
	<table class="table table-hover table-bordered table-list">
		<thead>
		<tr>
			<th>用户ID</th>
			<th>手机号</th>
			<th>用户昵称</th>
			<th>用户类型</th>
			<th>学校类型</th>
			<th>所属学校</th>
			<th>所属专业</th>
			<th>是否认证</th>
			<th>注册时间</th>
			<th>累积在线时长(h)</th>
			<th>用户状态</th>
		</tr>
		</thead>
		<tbody>
		<?php if(empty($list)): ?><tr><td colspan="11" style="text-align: center;">暂无数据</td></tr><?php endif; ?>
		<?php $user_type=array('2'=>'学生','3'=>'家长'); ?>
		<?php $school_type=array('1'=>'大学','2'=>'高中'); ?>
		<?php $verify_type=array('0'=>'否','1'=>'是'); ?>
		<?php if(is_array($list)): foreach($list as $key=>$vo): ?><tr>
				<td><?php echo ($vo["id"]); ?></td>
				<td><?php echo ($vo['mobile']); ?></td>
				<td><?php echo ($vo['user_nicename']); ?></td>
				<td><?php echo ($user_type[$vo['user_type']]); ?></td>
				<td><?php echo ($school_type[$vo['type']]); ?></td>
				<td><?php echo ($vo["school_name"]); ?></td>
				<td><?php echo ($vo["pro_name"]); ?></td>
				<td><?php echo ($verify_type[$vo['verify']]); ?></td>
				<td><?php echo ($vo["create_time"]); ?></td>
				<td><?php echo ($vo["online_time"]); ?></td>
				<td class="action">
					<?php if($vo['user_status'] == 1): ?><a href="<?php echo U('indexadmin/ban',array('id'=>$vo['id']));?>" class="js-ajax-dialog-btn" data-msg="确定要停用该用户？">正常</a>
						<?php else: ?>
					<a href="<?php echo U('indexadmin/cancelban',array('id'=>$vo['id']));?>" class="js-ajax-dialog-btn" data-msg="确定要将该用户恢复正常？">停用</a><?php endif; ?>
				</td>
			</tr><?php endforeach; endif; ?>
		</tbody>
	</table>
	<div class="pagination"><?php echo ($page); ?></div>
</div>
<script src="/public/js/common.js"></script>
</body>
</html>