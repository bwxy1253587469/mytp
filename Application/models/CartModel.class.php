<?php
class CartModel extends Model 
{
	// 加入购物车
	public function addToCart()
	{
		$post = proFrSql($_POST);
		$cartModel = new CartModel();
		$goodsAttrId = $post['goods_attr_id'];
		if($goodsAttrId)
		{
			// 把属性ID升序排列，因为后台存属性的存量时是升序的，为了能取出库存量
			sort($goodsAttrId);
			$goodsAttrId = implode(',', $goodsAttrId);
		}
		unset($post['goods_attr_id']);
		$post['goods_attr_id'] = $goodsAttrId;
		$post['member_id'] = $_SESSION['home']['id'];
		$post['goods_number'] = $post['amount'];
		return $this->insert($post);
	}
	// 购物车列表
	public function cartList()
	{
		$cartModel = new CartModel();
		$where = "where member_id =".$_SESSION['home']['id'];
		$_cart = $cartModel->selectAll($where);
		/****************** 循环购物车中每件商品，根据ID取出商品详情页信息 *****************/
		$goodsModel = new GoodsModel();
		foreach ($_cart as $k => $v)
		{
			$ginfo = $goodsModel->selectByPk($v['goods_id']);
			$_cart[$k]['goods_name'] = $ginfo['goods_name'];
			$_cart[$k]['sm_logo'] = $ginfo['sm_logo'];
			// 计算会员价格
			$_cart[$k]['price'] = $goodsModel->getMemberPrice($v['goods_id']);
			//计算会员价格+属性价格
			//$_cart[$k]['price'] +=$goodsModel->getAttrPrice($v['goods_id'],$_cart[$k]['goods_attr_str'])
			// 把商品属性ID转化成商品属性字符串
			$_cart[$k]['goods_attr_str'] = $goodsModel->convertGoodsAttrIdToGoodsAttrStr($v['goods_attr_id']);
		}
		return $_cart;
	}
	
	public function updateData($gid, $gaid, $gn)
	{
		$mid = session('mid');
		if($mid)
		{
			$cartModel = M('Cart');
			if($gn == 0)
				$cartModel->where(array(
					'goods_id' => array('eq', $gid),
					'goods_attr_id' => array('eq', $gaid),
					'member_id' => array('eq', $mid),
				))->delete();
			else 
				$cartModel->where(array(
					'goods_id' => array('eq', $gid),
					'goods_attr_id' => array('eq', $gaid),
					'member_id' => array('eq', $mid),
				))->setField('goods_number', $gn);
		}
		else 
		{
			// 先从COOKIE中取出购物车的数组
			$cart = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : array();
			$key = $gid . '-' . $gaid;
			if($gn == 0)
				unset($arr[$key]);
			else
				$arr[$key] = $gn;
			// 把这个数组存回到cookie
			$aMonth = 30 * 86400;
			setcookie('cart', serialize($cart), time() + $aMonth, '/', '34.com');
		}
	}
	// 清空购物车
	public function clearDb()
	{
		$mid = session('mid');
		if($mid)
		{
			// 取出勾选的商品
			$buythis = session('buythis');
			$cartModel = M('Cart');
			// 循环勾选 的商品进行删除
			foreach ($buythis as $k => $v)
			{
				// 从字符串 解析出商品ID和商品属性ID
				$_v = explode('-', $v);
				$cartModel->where(array('member_id'=>array('eq', $mid), 'goods_id'=>array('eq', $_v[0]), 'goods_attr_id'=>array('eq', $_v[1])))->delete();
			}
		}
	}
}


















