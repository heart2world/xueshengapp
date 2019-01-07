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
<style>
	.type_pane{
		padding-bottom: 15px;
	}
	table tr td:first-child{
		text-align: center;
	}
</style>
</head>
<body>
	<div class="wrap js-check-wrap">
		<ul class="nav nav-tabs">
			<li class="active"><a href="<?php echo U('school/index');?>">学校管理</a></li>
			<li><a href="<?php echo U('school/profession');?>">专业管理</a></li>
		</ul>
		<?php $status=['0'=>'<font color="#20b2aa">正常</font>','1'=>'<font color="#dc143c">停用</font>']; ?>
		<div class="type_pane">
			<a href="<?php echo U('school/index', ['type'=>1]);?>" class="btn <?php echo empty($type) || $type==1 ? 'btn-default':'btn-info' ;?>">大学管理</a>
			<a  href="<?php echo U('school/index',['type'=>2]);?>"  class="btn <?php echo ($type==2?'btn-default':'btn-info'); ?>">高中管理</a>
		</div>

		<form class="well form-search" method="post" action="<?php echo U('School/index',array('type'=>$type));?>">
			关键字：
			<input type="text" id="keyword" name="keyword" autocomplete="off" value="<?php echo I('request.keyword');?>" placeholder="学校名称/地区/专业类型"/>
			状态：
			<select type="text" id="status" name="status"  style="width: 120px;height: 35px" >
				<option value="">全部</option>
				<?php if(is_array($status)): foreach($status as $key=>$v): $ck = I('request.status')==="$key"?"selected":""; ?>
					<option value="<?php echo ($key); ?>" <?php echo ($ck); ?>><?php echo ($v); ?></option><?php endforeach; endif; ?>
			</select>
			<button type="submit" class="btn btn-primary">查询</button>
			<a class="btn btn-info" href="<?php echo U('school/add',array('type'=>$type));?>">新增学校</a>
		</form>
		<form method="post" class="js-ajax-form">
			<table class="table table-hover table-bordered table-list">
				<thead>
					<tr>
						<th width="50"><?php if(($type) == "1"): ?>大学<?php else: ?>高中<?php endif; ?>ID</th>
						<th><?php if(($type) == "1"): ?>大学<?php else: ?>高中<?php endif; ?>名称</th>
						<th>所在地区</th>
						<?php if(($type) == "1"): ?><th>学校类型</th><?php endif; ?>
						<th>注册总人数</th>
						<th width="45"><?php echo L('STATUS');?></th>
						<th width="120"><?php echo L('ACTIONS');?></th>
					</tr>
				</thead>
				<tbody>
				<?php if(empty($list)): ?><tr><td colspan="7">暂无数据</td></tr><?php endif; ?>
					<?php if(is_array($list)): foreach($list as $key=>$vo): ?><tr>
						<td><?php echo ($vo["id"]); ?></td>
						<td><?php echo ($vo["school_name"]); ?></td>
                        <td><?php echo ($vo["address"]); ?></td>
						<?php if(($type) == "1"): ?><td><?php echo ($vo["professional_type"]); ?></td><?php endif; ?>
						<td><?php echo ($vo["regs"]); ?></td>
						<td><?php echo ($status[$vo['status']]); ?></td>
						<td>
							<a href="<?php echo U('school/edit',array('id'=>$vo['id']));?>">详情</a> |
                            <?php if(($vo["status"]) == "0"): ?><a href="<?php echo U('school/ban',array('id'=>$vo['id']));?>" class="js-ajax-dialog-btn" data-msg="确定停用此学校?">停用</a>
                             <?php else: ?>
                                <a href="<?php echo U('school/cancelban',array('id'=>$vo['id']));?>" class="js-ajax-dialog-btn" data-msg="确定启用此学校?">启用</a><?php endif; ?>
						</td>
					</tr><?php endforeach; endif; ?>
				</tbody>
			</table>
		</form>
        <div class="pagination"><?php echo ($page); ?></div>
	</div>
	<script src="/public/js/common.js"></script>
</body>
</html>