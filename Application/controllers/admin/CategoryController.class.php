<?php
class CategoryController extends BaseController{
	public function lstAction(){
		$gdM = new CategoryModel();
		$goods = $gdM->getCats();
		//var_dump($goods);
		$this->assign('titleURL',U('add'));
		$this->assign('title',array("商品分类列表","添加商品分类"));
		$this->assign('data',$goods);
		$this->display();
	}

	public function editAction(){
		$gdM = new CategoryModel();
		if(isset($_POST['id'])){
			$gdM->updateCat();
		}
		$id = proFrSql($_GET['id']);
		$cat = $gdM->getOneCat($id);
		if(!empty($cat['search_attr_id'])){
			$searchAttrData = $gdM->AttrData($cat['search_attr_id']);
			//var_dump($searchAttrData);
			$this->assign('searchAttrData',$searchAttrData);
		}
		$this->assign('data',$cat);
		$this->assign('typeData',$gdM->getTypes());
		$this->assign('parentData',$gdM->getCats());
		$this->assign('titleURL',U('lst'));
		$this->assign('title',array("修改商品分类","商品分类列表"));
		$this->display();
	}

	//删除分类
	public function delAction(){
		$gdM = new CategoryModel();
		$id = proFrSql($_GET['id']);
		if($gdM->delCats($id))
			$this->jump(U('lst'),'删除成功');
		else
			$this->jump(U('lst'),'删除失败');;
	}



	//分类添加
	public function addAction(){
		$gdM = new CategoryModel();
		if(isset($_POST['parent_id'])){
			$gdM->insertCat();
		}
		$this->assign('typeData',$gdM->getTypes());
		$this->assign('parentData',$gdM->getCats());
		$this->assign('titleURL',U('lst'));
		$this->assign('title',array("添加商品分类","商品分类列表"));
		$this->display();
	}


}
?>