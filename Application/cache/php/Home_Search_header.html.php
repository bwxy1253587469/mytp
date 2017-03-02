<?php/*声明assign函数渲染的变量*/?><?php ?><?php
$catModel = new CategoryModel();
$catData = $catModel->getCats();
?>

<!-- 头部 start -->
	<div class="header w1210 bc mt15">
		<!-- 头部上半部分 start 包括 logo、搜索、用户中心和购物车结算 -->
		<div class="logo w1210">
			<h1 class="fl"><a href="<?php echo U('index/index');?>"><img src="<?php echo PUBLIC_PATH; ?>/Home/images/logo.png" alt="京西商城"></a></h1>
			<!-- 头部搜索 start -->
			<div class="search fl">
				<div class="search_form">
					<div class="form_left fl"></div>
					<form action="" name="serarch" method="get" class="fl">
						<input type="text" class="txt" value="请输入商品关键字" /><input type="submit" class="btn" value="搜索" />
					</form>
					<div class="form_right fl"></div>
				</div>
				
				<div style="clear:both;"></div>

				<div class="hot_search">
					<strong>热门搜索:</strong>
					<a href="">D-Link无线路由</a>
					<a href="">休闲男鞋</a>
					<a href="">TCL空调</a>
					<a href="">耐克篮球鞋</a>
				</div>
			</div>
			<!-- 头部搜索 end -->

			<!-- 用户中心 start-->
			<div class="user fl">
				<dl>
					<dt>
						<em></em>
						<a href="">用户中心</a>
						<b></b>
					</dt>
					<dd>
						<div class="prompt">
							您好，请<a href="">登录</a>
						</div>
						<div class="uclist mt10">
							<ul class="list1 fl">
								<li><a href="">用户信息></a></li>
								<li><a href="">我的订单></a></li>
								<li><a href="">收货地址></a></li>
								<li><a href="">我的收藏></a></li>
							</ul>

							<ul class="fl">
								<li><a href="">我的留言></a></li>
								<li><a href="">我的红包></a></li>
								<li><a href="">我的评论></a></li>
								<li><a href="">资金管理></a></li>
							</ul>

						</div>
						<div style="clear:both;"></div>
						<div class="viewlist mt10">
							<h3>最近浏览的商品：</h3>
							<ul id="recent_display_goods"></ul>
						</div>
					</dd>
				</dl>
			</div>
			<!-- 用户中心 end-->

			<!-- 购物车 start -->
			<div class="cart fl">
				<dl>
					<dt>
						<a href="<?php echo U('Home/Cart/lst'); ?>">去购物车结算</a>
						<b></b>
					</dt>
					<dd>
						<div class="prompt">
							购物车中还没有商品，赶紧选购吧！
						</div>
					</dd>
				</dl>
			</div>
			<!-- 购物车 end -->
		</div>
		<!-- 头部上半部分 end -->
		
		<div style="clear:both;"></div>

		<!-- 导航条部分 start -->
		<div class="nav w1210 bc mt10">
			<!--  商品分类部分 start-->
			<div class="category fl <?php if($show_nav!=1) echo 'cat1'; ?>"> <!-- 非首页，需要添加cat1类 -->
				<div class="cat_hd <?php if($show_nav!=1) echo 'off'; ?>">  <!-- 注意，首页在此div上只需要添加cat_hd类，非首页，默认收缩分类时添加上off类，鼠标滑过时展开菜单则将off类换成on类 -->
					<h2>全部商品分类</h2>
					<em></em>
				</div>
				
				<div class="cat_bd <?php if($show_nav!=1) echo 'none'; ?>">
				<!-- 循环顶级分类 -->
					<?php foreach ($catData as $k => $v): ?>
					<?php if($v['level']==0):?>
					<div class="cat <?php if($k==0) echo 'item1'; ?>">
						<h3><a href="<?php echo U('Home/Search/search','cid='.$v['id']); ?>"><?php echo $v["cat_name"]; ?></a> <b></b></h3>
						<div class="cat_detail">
							<!-- 循环二级分类 -->
							<?php foreach ($catData as $k1 => $v1): ?>
							<?php if($v1['level']==1&&$v1['parent_id']==$v['id']):?>
							<dl <?php if($k1==0) echo 'class="dl_1st"'; ?>>
								<dt><a href="<?php echo U('Home/Search/search','cid='.$v1['id']); ?>"><?php echo $v1["cat_name"]; ?></a></dt>
								<dd>
									<!-- 循环三级分类 -->
									<?php foreach ($catData as $k2 => $v2): ?>
									<?php if($v2['level']==2&&$v2['parent_id']==$v1['id']):?>
									<a href="<?php echo U('Home/Search/search','cid='.$v2['id']); ?>"><?php echo $v2["cat_name"]; ?></a>
									<?php endif; ?>
									<?php endforeach; ?>			
								</dd>
							</dl>
							<?php endif; ?>
							<?php endforeach; ?>
						</div>
					</div>
					<?php endif; ?>
					<?php endforeach; ?>
				</div>
			</div>
			<!--  商品分类部分 end--> 

			<div class="navitems fl">
				<ul class="fl">
					<li class="current"><a href="">首页</a></li>
					<li><a href="">电脑频道</a></li>
					<li><a href="">家用电器</a></li>
					<li><a href="">品牌大全</a></li>
					<li><a href="">团购</a></li>
					<li><a href="">积分商城</a></li>
					<li><a href="">夺宝奇兵</a></li>
				</ul>
				<div class="right_corner fl"></div>
			</div>
		</div>
		<!-- 导航条部分 end -->
	</div>
	<!-- 头部 end-->
	
	<div style="clear:both;"></div>
<script>
var img_url = "<?php echo UPLOAD_PATH; ?>";
// 获取最近浏览过的商品
$.ajax({
	type : "GET",
	url : "<?php echo U('Home/Index/ajaxGetRecentDisGoods'); ?>",
	dataType : "json",
	success : function(data)
	{
		var str = '';
		// 把商品的信息显示在页面上
		$(data).each(function(k,v){
			str += '<li><a href="<?php echo U('Home/Index/goods'); ?>?id='+v.id+'"><img src="'+img_url+v.sm_logo+'" alt="" /></a></li>';
		});
		// 把LI放到页面上
		$("#recent_display_goods").html(str);
	}
});
</script>