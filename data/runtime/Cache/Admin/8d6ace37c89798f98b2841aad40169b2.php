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
        <li class="active"><a href="<?php echo U('Discuss/index');?>">讨论管理</a></li>
        <li><a href="<?php echo U('Discuss/category');?>">分类管理</a></li>
        <li><a href="<?php echo U('Discuss/label');?>">标签管理</a></li>
    </ul>
    <form class="well form-search" method="post" action="<?php echo U('Discuss/index');?>" autocomplete="off">
        <div>
            文本搜索：<input type="text" name="keyword" value="<?php echo I('request.keyword/s','');?>" style="width: 230px;" placeholder="请输入讨论标题/创建用户/所属学校">
            <select name="number_type" style="height: 36px;">
                <option value="">点击/评论/收藏/评论点赞量</option>
                <option value="1" <?php if(I('request.number_type/s','') == 1): ?>selected<?php endif; ?>>点击量</option>
                <option value="2" <?php if(I('request.number_type/s','') == 2): ?>selected<?php endif; ?>>评论量</option>
                <option value="3" <?php if(I('request.number_type/s','') == 3): ?>selected<?php endif; ?>>收藏量</option>
                <option value="4" <?php if(I('request.number_type/s','') == 4): ?>selected<?php endif; ?>>评论点赞量</option>
            </select>
            <input type="number" name="num_last" style="width: 60px;" value="<?php echo I('request.num_last/s','');?>" placeholder=""> —
            <input type="number" name="num_next" style="width: 60px;" value="<?php echo I('request.num_next/s','');?>" placeholder="">
            <select name="hot_type" style="height: 36px;">
                <option value="">是否是热门</option>
                <option value="2" <?php if(I('request.hot_type/s','') == 2): ?>selected<?php endif; ?>>热门</option>
                <option value="1" <?php if(I('request.hot_type/s','') == 1): ?>selected<?php endif; ?>>非热门</option>
            </select>
        </div>
        <div style="margin-top: 12px;">
            <select name="status" style="height: 36px;width: 100px;">
                <option value="">讨论状态</option>
                <option value="1" <?php if(I('request.status/s','') == 1): ?>selected<?php endif; ?>>正常</option>
                <option value="2" <?php if(I('request.status/s','') == 2): ?>selected<?php endif; ?>>停用</option>
            </select>
            <select name="time_type" style="height: 36px;width: 120px;">
                <option value="">时间类型</option>
                <option value="1" <?php if(I('request.time_type/s','') == 1): ?>selected<?php endif; ?>>创建时间</option>
                <option value="2" <?php if(I('request.time_type/s','') == 2): ?>selected<?php endif; ?>>最后更新时间</option>
            </select>
            <input type="text" class="js-datetime" name="start_time" value="<?php echo I('request.start_time/s','');?>" placeholder="开始时间" style="width: 130px;"> —
            <input type="text" class="js-datetime" name="end_time" value="<?php echo I('request.end_time/s','');?>" placeholder="结束时间" style="width: 130px;">
            <input type="submit" class="btn btn-info" value="查询" />
        </div>
    </form>
    <div>
        <table class="table table-hover table-bordered table-list">
            <thead>
            <tr>
                <th width="60">讨论ID</th>
                <th>讨论标题</th>
                <th>创建用户</th>
                <th>讨论所属学校</th>
                <th>是否热门</th>
                <th>讨论分类</th>
                <th>讨论标签</th>
                <th>点击量</th>
                <th>评论量</th>
                <th>收藏量</th>
                <th>评论点赞量</th>
                <th>创建时间</th>
                <th>最后更新时间</th>
                <th>状态</th>
                <th width="120">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if(empty($discuss)): ?><tr><td colspan="15" class="no_data">暂无数据</td></tr><?php endif; ?>
            <?php $status_array = array(1=>'正常',2=>'停用'); ?>
            <?php $hot_array = array(1=>'—',2=>'是'); ?>
            <?php if(is_array($discuss)): foreach($discuss as $key=>$vd): ?><tr>
                    <td><?php echo ($vd["id"]); ?></td>
                    <td><?php echo ($vd["name"]); ?></td>
                    <td><?php echo ($vd["user_name"]); ?></td>
                    <td><?php echo ($vd["school_name"]); ?></td>
                    <td><?php echo ($hot_array[$vd['hot']]); ?></td>
                    <td><?php echo ($vd["category_name"]); ?></td>
                    <td><?php echo ($vd["label"]); ?></td>
                    <td><?php echo ($vd["click_num"]); ?></td>
                    <td><?php echo ($vd["comment_num"]); ?></td>
                    <td><?php echo ($vd["collect_num"]); ?></td>
                    <td><?php echo ($vd["like_num"]); ?></td>
                    <td><?php echo date('Y-m-d H:i:s',$vd['create_time']); ?></td>
                    <td><?php echo date('Y-m-d H:i:s',$vd['update_time']); ?></td>
                    <td><?php echo ($status_array[$vd['status']]); ?></td>
                    <td class="action">
                        <?php if($vd["status"] == 1): ?><a href="<?php echo U('Discuss/discuss_status',array('id'=>$vd['id']));?>" class="js-ajax-dialog-btn" data-msg="确定停用吗？">停用</a> |
                            <?php else: ?>
                            <a href="<?php echo U('Discuss/discuss_status',array('id'=>$vd['id']));?>" class="js-ajax-dialog-btn" data-msg="确定停用吗？">启用</a> |<?php endif; ?>
                        <?php if($vd["hot"] == 1): ?><a href="<?php echo U('Discuss/discuss_hot',array('id'=>$vd['id']));?>" class="js-ajax-dialog-btn" data-msg="确定设为热门吗？">设为热门</a> |
                            <?php else: ?>
                            <a href="<?php echo U('Discuss/discuss_hot',array('id'=>$vd['id']));?>" class="js-ajax-dialog-btn" data-msg="确定取消热门吗？">取消热门</a> |<?php endif; ?>
                        <a href="<?php echo U('Discuss/info',array('id'=>$vd['id']));?>">详情</a>
                    </td>
                </tr><?php endforeach; endif; ?>
            </tbody>
        </table>
        <div class="pagination"><?php echo ($page); ?></div>
    </div>
</div>
<script src="/public/js/common.js"></script>
</body>
</html>