<?php
class GoodsController extends BaseController{
	//载入添加商品页面
	public function addAction(){
		$gdM = new GoodsModel();
		/******************************************商品信息更新*******************/
		if(isset($_POST['goods_name'])&&!empty($_POST['goods_name'])){
			$post = $_POST;
			//var_dump($_POST);
			$gdM->insertGoods();
		}
		/******************************************商品信息更新*******************/
		$goods = $gdM->adddata();
		//var_dump($goods);
		// 取出所有的商品类型
    	$this->assign('typeData', $goods['typeData']);
    	// 取出所有的商品分类
    	$this->assign('catData', $goods['catData']);
    	// 取出所有的品牌
    	$this->assign('brandData', $goods['brandData']);
    	// 取出所有的会员级别
    	$this->assign('mlData', $goods['mlData']);

		$this->assign('titleURL',U('lst'));
		$this->assign('title',array("商品信息","商品列表"));
		$this->display();
	}

	//商品展示页面
	public function lstAction(){
		$gdM = new GoodsModel();
		$goods = $gdM->getGoods(0);
		//var_dump($goods);
		$this->assign('titleURL',U('add'));
		$this->assign('title',array("商品列表","添加商品"));
		$this->assign('data',$goods['data']);
		$this->assign('page',$goods['page']);
		$this->display();
	}
	//回收商品和商品展示页面
	public function recycleAction(){
		$gdM = new GoodsModel();
		//更新入回收站
		$sql = "update php34_goods set is_delete = 1 where id =".$_GET['id'];
		$gdM ->db->query($sql);
		$goods = $gdM->getGoods(0);
		//var_dump($goods);
		$this->assign('titleURL',U('lst'));
		$this->assign('title',array("商品回收列表","商品列表"));
		$this->assign('data',$goods['data']);
		$this->assign('page',$goods['page']);
		$this->display('lst');
	}

	//回收页面
	public function recyclelstAction(){
		$gdM = new GoodsModel();
		$goods = $gdM->getGoods(1);
		//var_dump($goods);
		$this->assign('titleURL',U('lst'));
		$this->assign('title',array("商品回收列表","商品列表"));
		$this->assign('data',$goods['data']);
		$this->assign('page',$goods['page']);
		$this->display();
	}

	//商品库存
	public function goods_numberAction(){
		$gdM = new GoodsModel();
		$goods = $gdM->getNum($_GET['id']);
		//库存更新
		if(isset($_POST['goods_number'])){
			//var_dump($_POST);
			$gdM->delNum();
		}
		//展示
		$goods = $gdM->getNum($_GET['id']);
		$this->assign('attr',$goods['attr']);
		$this->assign('attrvalue',$goods['attrvalue']);
		//var_dump($goods);
		$this->assign('num',$goods['num']);
		$this->assign('titleURL',U('lst'));
		$this->assign('title',array("商品库存","商品列表"));
		$this->display();
	}

	//编辑商品
	public function editAction(){
		$gdM = new GoodsModel();
		/******************************************商品信息更新*******************/
		if(isset($_POST['id'])){
			$post = $_POST;
			//var_dump($_POST);
			$gdM->updateGoods();
		}
		/******************************************商品信息更新*******************/
		$goods = $gdM->editdata($_GET['id']);
		//var_dump($goods);
		// 取出所有的商品类型
    	$this->assign('typeData', $goods['typeData']);
    	// 取出所有的商品分类
    	$this->assign('catData', $goods['catData']);
    	// 取出所有的品牌
    	$this->assign('brandData', $goods['brandData']);
    	// 取出所有的会员级别
    	$this->assign('mlData', $goods['mlData']);
    	
    	// 取出要修改的商品的基本信息
    	$this->assign('data', $goods['data'][0]);
    	// 取出当前商品扩展分类的数据
    	$this->assign('extCatId', $goods['extCatId']);
    	// 取出当前商品会员价格的数据
    	// 取出当前商品的属性数据
		// 取出当前类型下的后添加的新属性
    	$this->assign('gaData', $goods['gaData']);
    	// 取出当前商品的图片
    	$this->assign('gpData', $goods['gpData']);

		$this->assign('titleURL',U('lst'));
		$this->assign('title',array("商品信息","商品列表"));
		$this->display();
	}

	// 当选择类型时执行AJAX取出类型的属性
	public function AjaxTypeAction(){
		$gdM = new GoodsModel();
		$sql = "select * from php34_attribute where type_id =".$_GET['type_id'];
		$data = $gdM ->db->getAll($sql);
		echo json_encode($data);
	}

	// 删除图片
	public function ajaxDelImageAction(){
		$gdM = new GoodsModel();
		$sql = "select * from php34_goods_pics where id =".$_GET['pic_id'];
		$data = $gdM ->db->getAll($sql);
		//var_dump($data);
		//删除图片
		unlink(ROOT."Public/Uploads/".$data[0]['pic']);
		unlink(ROOT."Public/Uploads/".$data[0]['sm_pic']);
		$sql = "delete from php34_goods_pics where id =".$_GET['pic_id'];
		$gdM ->db->query($sql);
	
	}

	//回收站还原
	public function restoreAction(){
		$gdM = new GoodsModel();
		//回收站还原
		$sql = "update php34_goods set is_delete = 0 where id =".$_GET['id'];
		if($gdM ->db->query($sql))
				$this->jump(U('lst'),'回收成功');
			else
				$this->jump(U('recyclelst'),'回收失败');
	}

	//删除
	public function delAction(){
		$gdM = new GoodsModel();
		if($gdM->delete($_GET['id']))
				$this->jump(U('recyclelst'),'删除成功');
			else
				$this->jump(U('recyclelst'),'删除失败');
	}





}
?>