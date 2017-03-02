// 价格搜索
		$price = I('get.price');
		if($price)
		{
			$price = explode('-', $price);
			$where['a.shop_price'] =array('between', array($price[0], $price[1]));
		}
		// 商品属性的搜索
		$sa = I('get.search_attr');
		if($sa)
		{
			$gaModel = M('GoodsAttr');
			$sa = explode('.', $sa);
			// 先定义一个数组：第一个满足条件的属性ID
			$_att1 = null;
			// 循环每个属性
			/**
			 * 找出满足每个属性条件的商品ID列表
			 * 12寸： 3,4,6,7,9
			独立： 3,5,9,76
			345G: 3,5
			$arr= array('3,4,6,7,9','3,5,9,76','3,5');
			 */
			// 现在要找出满足所有的条件的商品ID就是把上面的ID字符串取交集
			foreach ($sa as $k => $v)
			{
				if($v != '0')
				{
					$_v = explode('-', $v);
					// 到商品属性表中搜索有这个属性以及值的商品的ID,并返回字符串1,2,3,4
					$_attrGoodsId = $gaModel->field('GROUP_CONCAT(goods_id) goods_id')->where(array(
						'attr_id' => $_v[1],
						'attr_value' => $_v[0],
					))->find();
					$_attrGoodsId = $_attrGoodsId['goods_id'];
					// 如果是第一个就先保存起来
					if($_att1 === null)
						$_att1 = explode(',', $_attrGoodsId);
					else 
					{
						// 如果$_attr1不为空，保存的就是上一次满足条件的商品ID,那么就和这次取交集
						$_attrGoodsId = explode(',', $_attrGoodsId);
						$_att1 = array_intersect($_att1, $_attrGoodsId);
						// 如果已经是空了就直接退出不用再比较了，肯定没交集
						if(empty($_att1))
							break;
					}
				}
			}
			// $_attr1保存的就是满所有条件 的商品的ID
			if($_att1)
				$where['a.id'] = array('in', $_att1);
			else 
				$where['a.id'] = array('eq', 0);  // 如果没有满足条件的商品就直接设置为一个搜索不出来的条件
		}
		/****************** 排序 ********************/
		$orderBy = 'xl';  // 排序字段
		$orderWay = 'DESC'; // 排序方式
		// 接收用户传的排序参数
		$ob = I('get.ob');
		$ow = I('get.ow');
		if($ob && in_array($ob, array('xl','shop_price','pl','addtime')))
		{
			$orderBy = $ob;
			// 如果是根据价格排序，才接收ow变量
			if($ob == 'shop_price' && $ow && in_array($ow, array('asc', 'desc')))
				$orderWay = $ow;
		}
		/******************* 翻页 ***********************/
		// 取出总的记录数
		$count = $this->alias('a')->where($where)->count();
		$page = new \Think\Page($count, 24);
		// 配置翻页的样式
		$page->setConfig('prev', '上一页');
		$page->setConfig('next', '下一页');
		$data['page'] = $page->show();
		
		/*********************** 取商品 **********************/
		/**
		 * SELECT a.id,a.goods_name,IFNULL(SUM(b.goods_number),0) xl,(SELECT count(id) FROM php34_comment c WHERE c.goods_id=a.id) pl
 FROM php34_goods a
  LEFT JOIN php34_order_goods b
   ON (a.id=b.goods_id AND b.order_id IN(SELECT id FROM php34_order WHERE pay_status=1))
      GROUP BY a.id
       ORDER BY xl ASC
       总结：因为如果使用两个都外链（left join）那么取出的结构会互相影响，所以销量用的LEFT JOIN 而评论数用的子查询，这样没有互相影响
		 
		$data['data'] = $this->field('a.id,a.goods_name,sm_logo,shop_price,IFNULL(SUM(b.goods_number),0) xl,(SELECT count(id) FROM php34_comment c WHERE c.goods_id=a.id) pl')->alias('a')->join('LEFT JOIN php34_order_goods b ON (a.id=b.goods_id AND b.order_id IN(SELECT id FROM php34_order WHERE pay_status=1))')->where($where)->group('a.id')->order("$orderBy $orderWay")->limit($page->firstRow.','.$page->listRows)->select();
		*/