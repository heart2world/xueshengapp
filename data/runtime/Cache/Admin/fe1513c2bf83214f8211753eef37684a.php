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
    .status {text-decoration: solid !important;}
    .table-list a{color: #1abc9c !important;cursor: pointer;}
</style>
</head>
<body>
<div class="wrap js-check-wrap">
    <?php $li = ["1"=>"用户昵称","2"=>"评论及回复","3"=>"讨论标题及正文",]; ?>
    <ul class="nav nav-tabs">
        <li class="active"><a href="<?php echo U('FilterKeyword/index');?>">关键词过滤</a></li>
    </ul>
    <div style="padding: 10px 0">
        <button class="addFilter btn btn-info">新增关键词</button>
    </div>
    <?php $url = U('FilterKeyword/index') ; ?>
    <form class="well form-search" method="post" action="<?php echo ($url); ?>">
        文本搜索：
        <input type="text" id="keyword" name="keyword" autocomplete="off" value="<?php echo I('request.keyword');?>" placeholder="输入关键词内容"/>
        <button type="submit" class="btn btn-primary">查询</button>
    </form>
    <form method="post" class="js-ajax-form">
        <table class="table table-hover table-bordered table-list">
            <thead>
            <tr>
                <th width="50">ID</th>
                <th>关键词</th>
                <th>生效区域</th>
                <th width="120"><?php echo L('ACTIONS');?></th>
            </tr>
            </thead>
            <tbody>
            <?php if(empty($list)): ?><tr><td colspan="4">暂无数据</td></tr><?php endif; ?>
            <?php if(is_array($list)): foreach($list as $key=>$vo): ?><tr>
                    <td><?php echo ($vo["id"]); ?></td>
                    <td><?php echo ($vo["keyword"]); ?></td>
                    <td><?php echo ($vo["effect_area"]); ?></td>
                    <td style="text-align: center">
                        <a class="js-ajax-delete" href="<?php echo U('FilterKeyword/delete',array('id'=>$vo['id']));?>"  data-msg="">删除</a>
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
    <form action="" class="layui-form" autocomplete="off" style="padding: 20px 10px;">
        <div class="layui-form-item">
            <label for="kwd">新增关键词</label> <input type="text" id="kwd" maxlength="25" placeholder="请输入过滤关键词" />
        </div>
        <div class="layui-form-item">
            <label >生效区域</label>
            <?php if(is_array($li)): foreach($li as $key=>$v): ?><input type='checkbox' title="<?php echo ($v); ?>" name='checkbox' value='<?php echo ($key); ?>'/><?php endforeach; endif; ?>
        </div>
    </form>
</script>
<script>

    layui.use(["form"], function () {
        /**
         * 获取选中Id
         * @return array
         **/
        function checked() {
            let values = [];
            $("[name=checkbox]:checked").each(function (i,e) {
                values.push(e.value);
            });
            return values;
        }

        let form = layui.form;

        $(".addFilter").on("click", function () {
            let a = $(this);
            let html = $("#temp").html();
            layer.open({
                title: false
                , content: html
                , type :1
                , btn :['确定']
                , yes: function (index) {

                    let keyword = $("#kwd").val().trim();
                    if(keyword == ''){
                        layer.msg("请输入过滤关键词",{type:1});
                        return;
                    }
                    let check =  checked();
                    if (!check.length){
                        layer.msg("生效区域至少选择一个",{type:1});
                        return;
                    }
                    request({
                        url: "<?php echo U('FilterKeyword/addPost');?>",
                        data: {keyword:keyword,effect_area: check.join(",")},
                        done: function (res) {
                            res.state==="success"&&setTimeout(function () {
                               res.state && location.reload();
                            },500)
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