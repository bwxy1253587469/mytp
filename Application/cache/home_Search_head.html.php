<?php/*声明assign函数渲染的变量*/?><?php ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title><?php echo $page_title; ?></title>
	<meta name="keywords" content="<?php echo $page_keywords; ?>" />
	<meta name="description" content="<?php echo $page_description; ?>" />
	<link rel="stylesheet" href="<?php echo PUBLIC_PATH; ?>/Home/style/base.css" type="text/css">
	<link rel="stylesheet" href="<?php echo PUBLIC_PATH; ?>/Home/style/global.css" type="text/css">
	<link rel="stylesheet" href="<?php echo PUBLIC_PATH; ?>/Home/style/header.css" type="text/css">
	<?php foreach ($page_css as $k => $v): ?>
	<link rel="stylesheet" href="<?php echo PUBLIC_PATH; ?>/Home/style/<?php echo $v; ?>.css" type="text/css">
	<?php endforeach; ?>
	<link rel="stylesheet" href="<?php echo PUBLIC_PATH; ?>/Home/style/bottomnav.css" type="text/css">
	<link rel="stylesheet" href="<?php echo PUBLIC_PATH; ?>/Home/style/footer.css" type="text/css">
	<script type="text/javascript" src="<?php echo PUBLIC_PATH; ?>/Home/js/jquery-1.8.3.min.js"></script>
	<script type="text/javascript" src="<?php echo PUBLIC_PATH; ?>/Home/js/header.js"></script>
	<?php foreach ($page_js as $k => $v): ?>
	<script type="text/javascript" src="<?php echo PUBLIC_PATH; ?>/Home/js/<?php echo $v; ?>.js"></script>
	<?php endforeach; ?>
</head>
<body>
	<!-- 顶部导航 start -->
	<div class="topnav">
		<div class="topnav_bd w1210 bc">
			<div class="topnav_left">
				
			</div>
			<div class="topnav_right fr">
				<ul>
					<li id="logInfo">您好，欢迎来到京西！[<a href='<?php echo U('Home/Member/login'); ?>'>登录</a>] [<a href='<?php echo U('Home/Member/regist'); ?>'>免费注册</a>]</li>
					<li class="line">|</li>
					<li>我的订单</li>
					<li class="line">|</li>
					<li>客户服务</li>

				</ul>
			</div>
		</div>
	</div>
	<!-- 顶部导航 end -->
	<div style="clear:both;"></div>