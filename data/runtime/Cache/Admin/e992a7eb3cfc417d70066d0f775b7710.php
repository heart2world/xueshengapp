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
        <li><a href="<?php echo U('Discuss/info',array('id'=>$id));?>">基本信息</a></li>
        <li class="active"><a href="<?php echo U('Discuss/comment',array('id'=>$id));?>">评论信息</a></li>
    </ul>
    <form class="well form-search" method="post" action="<?php echo U('Discuss/comment');?>" autocomplete="off">
        <div>
            文本搜索：<input type="text" name="keyword" value="<?php echo I('request.keyword/s','');?>" style="width: 180px;" placeholder="请输入评论内容/评论用户">
            评论序号/点赞量/回复量：
            <select name="number_type" style="height: 36px;">
                <option value="">全部</option>
                <option value="1" <?php if(I('request.number_type/s','') == 1): ?>selected<?php endif; ?>>评论序号</option>
                <option value="2" <?php if(I('request.number_type/s','') == 2): ?>selected<?php endif; ?>>点赞量</option>
                <option value="3" <?php if(I('request.number_type/s','') == 3): ?>selected<?php endif; ?>>回复量</option>
            </select>
            <input type="number" name="num_last" style="width: 60px;" value="<?php echo I('request.num_last/s','');?>" placeholder=""> —
            <input type="number" name="num_next" style="width: 60px;" value="<?php echo I('request.num_next/s','');?>" placeholder="">
        </div>
        <div style="margin-top: 12px;">
            评论时间：
            <input type="text" id="start_time" name="start_time" value="<?php echo I('request.start_time/s','');?>" placeholder="开始时间" style="width: 140px;"> —
            <input type="text" id="end_time" name="end_time" value="<?php echo I('request.end_time/s','');?>" placeholder="结束时间" style="width: 140px;">
            <input type="hidden" name="id" value="<?php echo ($id); ?>">
            <input type="submit" class="btn btn-info" value="查询" />
        </div>
    </form>
    <div>
        <table class="table table-hover table-bordered table-list">
            <thead>
            <tr>
                <th width="60">评论序号</th>
                <th>评论内容</th>
                <th>评论用户</th>
                <th>点赞量</th>
                <th>回复量</th>
                <th>评论时间</th>
                <th width="120">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if(empty($comment)): ?><tr><td colspan="7" class="no_data">暂无数据</td></tr><?php endif; ?>
            <?php $status_array = array(1=>'正常',2=>'停用'); ?>
            <?php $hot_array = array(1=>'—',2=>'是'); ?>
            <?php if(is_array($comment)): foreach($comment as $key=>$vc): ?><tr>
                    <td><?php echo ($vc["id"]); ?></td>
                    <td><?php echo ($vc["content"]); ?></td>
                    <td><?php echo ($vc["user_name"]); ?></td>
                    <td><?php echo ($vc["like_num"]); ?></td>
                    <td><?php echo ($vc["reply_num"]); ?></td>
                    <td><?php echo date('Y-m-d H:i:s',$vc['create_time']); ?></td>
                    <td class="action">
                        <a href="<?php echo U('Discuss/comment_delete',array('id'=>$vc['id']));?>" class="js-ajax-delete" data-msg="会一并删除该评论下回复，确定删除？">删除</a>
                        <a href="<?php echo U('Discuss/reply',array('id'=>$vc['id']));?>">评论详情</a>
                    </td>
                </tr><?php endforeach; endif; ?>
            </tbody>
        </table>
        <div class="pagination"><?php echo ($page); ?></div>
    </div>
</div>
<script src="/public/js/common.js"></script>
<script src="/public/js/laydate/laydate.js"></script>
<script type="text/javascript">
    laydate.render({elem: '#start_time',type: 'datetime'});
    laydate.render({elem: '#end_time',type: 'datetime'});
</script>
</body>
</html>