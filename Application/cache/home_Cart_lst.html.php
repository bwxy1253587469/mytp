<?php/*声明assign函数渲染的变量*/?><?php $data=$this->tpl_var["data"];$page_keywords=$this->tpl_var["page_keywords"];$page_description=$this->tpl_var["page_description"];$page_title=$this->tpl_var["page_title"];$show_nav=$this->tpl_var["show_nav"];$page_css=$this->tpl_var["page_css"];$page_js=$this->tpl_var["page_js"];?><?php include showUrl("index/head"); ?>
<!-- 页面头部 start -->
	<div class="header w990 bc mt15">
		<div class="logo w990">
			<h2 class="fl"><a href="/"><img src="<?php echo PUBLIC_PATH; ?>/Home/images/logo.png" alt="京西商城"></a></h2>
			<div class="flow fr">
				<ul>
					<li class="cur">1.我的购物车</li>
					<li>2.填写核对订单信息</li>
					<li>3.成功提交订单</li>
				</ul>
			</div>
		</div>
	</div>
	<!-- 页面头部 end -->
	<div style="clear:both;"></div>
	<form method="POST" action="<?php echo U('order'); ?>">
	<!-- 主体部分 start -->
	<div class="mycart w990 mt10 bc">
		<h2><span>我的购物车</span></h2>
		<table>
			<thead>
				<tr>
					<th></th>
					<th class="col1">商品名称</th>
					<th class="col2">商品信息</th>
					<th class="col3">单价</th>
					<th class="col4">数量</th>
					<th class="col5">小计</th>
					<th class="col6">操作</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$tp = 0; // 总价
				foreach ($data as $k => $v): ?>
				<tr goods_id="<?php echo $v["goods_id"]; ?>" goods_attr_id="<?php echo $v["goods_attr_id"]; ?>">
					<td><input type="checkbox" value="<?php echo $v["goods_id"]; ?>-<?php echo $v["goods_attr_id"]; ?>" name="buythis[]" /></td>
					<td class="col1">
					<a href=""><img src ="<?php echo UPLOAD_PATH.$v['sm_logo']; ?>"></a>  <strong><a href=""><?php echo $v["goods_name"]; ?></a></strong></td>
					<td class="col2"> <?php echo $v["goods_attr_str"]; ?> </td>
					<td class="col3">￥<span><?php echo $v["price"]; ?></span>元</td>
					<td class="col4"> 
						<a href="javascript:;" class="reduce_num"></a>
						<input type="text" name="amount" value="<?php echo $v["goods_number"]; ?>" class="amount"/>
						<a href="javascript:;" class="add_num"></a>
					</td>
					<td class="col5">￥<span><?php $xj = $v['goods_number'] * $v['price']; $tp+=$xj; echo $xj; ?></span>元</td>
					<td class="col6"><a href="">删除</a></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="7">购物金额总计： <strong>￥ <span id="total"><?php echo $tp; ?></span>元</strong></td>
				</tr>
			</tfoot>
		</table>
		<div class="cart_btn w990 bc mt10">
			<a href="/" class="continue">继续购物</a>
			<a href="javascript:void(0);" onclick="$('form').submit();" class="checkout">结 算</a>
		</div>
	</div>
	<!-- 主体部分 end -->
	</form>
	
<script>
function ajaxUpdateCartData(goodsId, goodsAttrId, goodsNumber)
{
	var _gaid = "";
	if(goodsAttrId != "")
		_gaid = "/gaid/"+goodsAttrId;
	// 以GET方式请求一个地址
	$.get("<?php echo U('Home/Cart/ajaxUpdateData'); ?>?gid="+goodsId+"&gn="+goodsNumber+_gaid);
}
</script>
<?php include showUrl("index/foot"); ?>