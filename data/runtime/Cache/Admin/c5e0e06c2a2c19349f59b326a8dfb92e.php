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
    .type_pane {
        padding-bottom: 15px;
    }

    table tr td:first-child {
        text-align: center;
    }

    .status {
        text-decoration: solid !important;
    }
</style>
</head>
<body>
<div class="wrap js-check-wrap">
    <ul class="nav nav-tabs">
        <li class="active"><a href="<?php echo U('OrderList/index');?>">发货列表</a></li>
    </ul>

    <?php $url = U('OrderList/index') ; ?>
    <?php $status=['0'=>'<font color="#6495ed">待发货</font>','1'=>'<font color="#20b2aa">已发货</font>','2'=>'<font
            color="#a9a9a9">已关闭</font>']; ?>
    <form class="well form-search" method="post" action="<?php echo ($url); ?>" autocomplete="off">
        文本搜索：
        <input type="hidden" name="type" value="<?php echo I('type');?>">
        <input type="text" id="keyword" name="keyword" value="<?php echo I('request.keyword');?>"
               placeholder="输入商品名称/收货人/收货电话"/>
        <select type="text" name="status" style="width: 120px;height: 35px">
            <option value="">选择发货状态</option>
            <?php if(is_array($status)): foreach($status as $key=>$v): $ck = I('request.status')==="$key"?"selected":""; ?>
                <option value="<?php echo ($key); ?>" <?php echo ($ck); ?>><?php echo ($v); ?></option><?php endforeach; endif; ?>
        </select>
        <input type="text"  style="width: 150px" value="<?php echo I('request.time');?>" name="time" id="time"  placeholder="按时间查询"  />
        <button type="submit" class="btn btn-primary">查询</button>
        <!--<a class="btn btn-warning" href="<?php echo ($url); ?>">重置</a>-->
    </form>


    <form method="post" class="js-ajax-form">

        <table class="table table-hover table-bordered table-list">
            <thead>
            <tr>
                <th width="50">发货ID</th>
                <th>发货商品</th>
                <th width="60">发货数量</th>
                <th>收货人</th>
                <th>收货地址</th>
                <th>收货电话</th>
                <th>提交时间</th>
                <th width="45">状态</th>
                <th width="120"><?php echo L('ACTIONS');?></th>
            </tr>
            </thead>
            <tbody>
            <?php if(empty($list)): ?><tr><td colspan="9">暂无数据</td></tr><?php endif; ?>
            <?php if(is_array($list)): foreach($list as $key=>$vo): ?><tr>
                    <td><?php echo ($vo["id"]); ?></td>
                    <td><?php echo ($vo["gift_name"]); ?></td>
                    <td><?php echo ($vo["number"]); ?></td>
                    <td><?php echo ($vo["user_name"]); ?></td>
                    <td><?php echo ($vo["address"]); ?></td>
                    <td><?php echo ($vo["mobile"]); ?></td>
                    <td><?php echo date("Y-m-d H:i:s",$vo['create_time']);?></td>
                    <td><?php echo ($status[$vo['status']]); ?></td>
                    <td style="text-align: center">
                        <?php if(($vo["status"]) == "0"): ?><a class="status" data-url="<?php echo U('OrderList/invoice',array('id'=>$vo['id']));?>">发货</a><?php endif; ?>
                    </td>
                </tr><?php endforeach; endif; ?>
            </tbody>
        </table>
    </form>
    <div class="pagination"><?php echo ($page); ?></div>
</div>
<script src="/public/js/common.js"></script>
<script src="/public/layui/layui.js"></script>
<script src="/public/js/app.js"></script>
<script id="temp" type="text/html">
    <form action="" class="layui-form">
        <input type='radio' title="已发货" name='status' value='1' checked/>
        <input type='radio' title="取消发货" name='status' value='2'/>
    </form>
</script>
<script type="text/javascript">
    layui.use(["form", 'laytpl', 'laydate'], function () {
        let form = layui.form;
        let laydate = layui.laydate;
        laydate.render({elem: '#time', type:'datetime'});
        $("a.status").on("click", function () {
            let a = $(this);
            let html = $("#temp").html();
            layer.open({
                title: false
                , content: html
                , type :0
                , yes: function (index) {
                    let url = a.data("url");
                    let status = $("[name=status]:checked").val();
                    request({
                        reload:1,
                        url: url,
                        data: {status: status},
                        done: function (res) {
                        }
                    });
                }
            });
            form.render();
            return false;
        });
    });
</script>
</body>
</html>