<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
	<head>
		<meta charset="UTF-8" />
		<title>学生APP <?php echo L('ADMIN_CENTER');?></title>
		<meta http-equiv="X-UA-Compatible" content="chrome=1,IE=edge" />
		<meta name="renderer" content="webkit|ie-comp|ie-stand">
		<meta name="robots" content="noindex,nofollow">
		<link href="/admin/themes/simplebootx/Public/assets/css/admin_login.css" rel="stylesheet" />

		<style>
			#login_btn_wraper{
				text-align: center;
			}
			#login_btn_wraper .tips_success{
				color:#fff;
			}
			#login_btn_wraper .tips_error{
				color:#DFC05D;
			}
			#login_btn_wraper button:focus{outline:none;}
			.layui-layer-msg{border:1px solid white !important;}
			body{
				background: url("/admin/themes/simplebootx/Public/assets/images/bg.jpg") no-repeat center;
				background-size: cover;
				height: 1080px;
				width: 1920px;
				position: relative;
			}
			.wrap{
				margin: 0 auto;
				width: 460px;
				height: 540px;
				box-sizing: border-box;
				box-shadow: 0 0 30px #1262b73d;
				border-radius: 10px;
				position: absolute;
				top: 204px;
				right: 167px;
				padding: 128px 74px;
			}
		</style>
		<script>
			if (window.parent !== window.self) {
					document.write = '';
					window.parent.location.href = window.self.location.href;
					setTimeout(function () {
							document.body.innerHTML = '';
					}, 0);
			}
		</script>
		
	</head>
<body>
	<div class="wrap">
		<h1><a>学生APP管理后台</a></h1>
		<form method="post" name="login" action="<?php echo U('public/dologin');?>" autoComplete="off" class="js-ajax-form">
			<div class="login">
				<ul>
					<li>
						<input class="input" id="js-admin-name" name="username" type="text" placeholder="<?php echo L('USERNAME_OR_EMAIL');?>" title="<?php echo L('USERNAME_OR_EMAIL');?>" value="<?php echo cookie('admin_username');?>" data-rule-required="true"  data-msg-required=""/>
					</li>
					<li>
						<input class="input" id="admin_pwd" type="password" name="password" placeholder="<?php echo L('PASSWORD');?>" title="<?php echo L('PASSWORD');?>" data-rule-required="true"  data-msg-required=""/>
					</li>
					<li class="verify_imgbox">
						<input class="input" type="text" name="verify" placeholder="<?php echo L('ENTER_VERIFY_CODE');?>" />
						<?php echo sp_verifycode_img('length=4&font_size=16&width=120&height=40&use_noise=1&use_curve=0','style="cursor: pointer;" title="点击获取" class="verifycode"');?>
					</li>
				</ul>
				<div id="login_btn_wraper">
					<button type="submit" name="submit" class="btn js-ajax-submit" data-loadingmsg="<?php echo L('LOADING');?>"><?php echo L('LOGIN');?></button>
				</div>
			</div>
		</form>
	</div>

<script>
var GV = {
    ROOT: "/",
    WEB_ROOT: "/",
    JS_ROOT: "public/js/"
};
</script>
<script src="/public/js/wind.js"></script>
<script src="/public/js/jquery.js"></script>
<script src="/public/js/layer/layer.js"></script>
<script type="text/javascript" src="/public/js/common.js"></script>
<script>
;(function(){
	document.getElementById('js-admin-name').focus();
})();
</script>
</body>
</html>