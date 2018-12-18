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
    .type_pane {
        padding-bottom: 15px;
    }

    table tr td:first-child {
        text-align: center;
    }
</style>
</head>
<body>
<div class="wrap js-check-wrap">
    <ul class="nav nav-tabs">
        <li class="active"><a href="<?php echo U('gift/index');?>">商品管理</a></li>
        <li class=""><a href="<?php echo U('gift/add');?>">新增商品</a></li>
    </ul>
    <?php $status=['0'=>'<font color="#20b2aa">已上架</font>','1'=>'<font color="#dc143c">已下架</font>']; ?>
    <form class="well form-search" method="post" action="<?php echo U('gift/index');?>">
        文本搜索：
        <input type="hidden" name="type" value="<?php echo I('type');?>">
        <input type="text" id="keyword" name="keyword" autocomplete="off" value="<?php echo I('request.keyword');?>" placeholder="商品名称"/>
        <select type="text" name="type" style="width: 120px;height: 35px">
            <option value="">选择商品类型</option>
            <option value="1" <?php if(I('request.type/s','') == 1): ?>selected<?php endif; ?>>积分</option>
            <option value="2" <?php if(I('request.type/s','') == 1): ?>selected<?php endif; ?>>赞助</option>
        </select>
        <select name="number_type" style="height: 36px;width: 180px;">
            <option value="">商品单价/库存剩余</option>
            <option value="1" <?php if(I('request.number_type/s','') == 1): ?>selected<?php endif; ?>>商品单价</option>
            <option value="2" <?php if(I('request.number_type/s','') == 2): ?>selected<?php endif; ?>>库存剩余</option>
        </select>
        <input type="number" name="num_last" style="width: 60px;" value="<?php echo I('request.num_last/s','');?>" placeholder=""> —
        <input type="number" name="num_next" style="width: 60px;" value="<?php echo I('request.num_next/s','');?>" placeholder="">
        <button type="submit" class="btn btn-primary">查询</button>
    </form>

    <form method="post" class="js-ajax-form">
        <table class="table table-hover table-bordered table-list">
            <thead>
            <tr>
                <th width="50">商品ID</th>
                <th>商品名称</th>
                <th>商品类型</th>
                <th>商品单价</th>
                <th>库存剩余</th>
                <th>添创建时间</th>
                <th width="60">商品状态</th>
                <th width="120"><?php echo L('ACTIONS');?></th>
            </tr>
            </thead>
            <tbody>
            <?php if(empty($list)): ?><tr><td colspan="8">暂无数据</td></tr><?php endif; ?>
            <?php if(is_array($list)): foreach($list as $key=>$vo): ?><tr>
                    <td><?php echo ($vo["id"]); ?></td>
                    <td><?php echo ($vo["gift_name"]); ?></td>
                    <td><?php echo ($vo['type']==1?"积分":"赞助"); ?></td>
                    <td><?php echo ($vo['price']); ?> <?php echo ($vo['type']==1?"分":"元"); ?></td>
                    <td><?php echo ($vo["surplus"]); ?></td>
                    <td><?php echo date("Y-m-d H:i:s",$vo['create_time']);?></td>
                    <td><?php echo ($status[$vo['status']]); ?></td>
                    <td style="text-align: center">
                        <a href="<?php echo U('gift/edit',array('id'=>$vo['id']));?>">编辑</a> |
                        <a class="status"
                           href="<?php echo ($vo['status']==0?U('gift/down',array('id'=>$vo['id'])):U('gift/up',array('id'=>$vo['id']))); ?>">
                            <?php echo ($vo['status']==0?"下架":'上架'); ?>
                        </a>
                    </td>
                </tr><?php endforeach; endif; ?>
            </tbody>
        </table>
    </form>
    <div class="pagination"><?php echo ($page); ?></div>
</div>
<script src="/public/js/common.js"></script>
<script src="/public/js/app.js"></script>
<script>
    $("a.status").on("click", function () {
        let a = $(this);
        let status= a.html().trim();
        let url = a[0].href;
        request({url:url,done:function (res) {
            console.log(res)
        }});

        return false;
    })
</script>
</body>
</html>