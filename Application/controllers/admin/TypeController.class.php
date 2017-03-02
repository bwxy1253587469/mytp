<?php
//商品类型控制器
class TypeController extends BaseController{
	public function addAction(){
		$gdM = new TypeModel();
		if(isset($_POST['type_name'])&&!empty($_POST['type_name'])){
			if($gdM->insertType())
				$this->jump(U('lst'),'添加成功');
			else
				$this->jump(U('add'),'添加失败');
		}
		$this->assign('titleURL',U('lst'));
		$this->assign('title',array("修改类型","类型列表"));
		$this->display();
	}

	public function lstAction(){
		$gdM = new TypeModel();
		$goods = $gdM->getTypes();
		$this->assign('data',$goods);
		$this->assign('titleURL',U('add'));
		$this->assign('title',array("类型列表","添加类型"));
		$this->display();
	}

	public function editAction(){
		$gdM = new TypeModel();
		if(isset($_POST['id'])){
			$gdM->editType();
		}
		$id= proFrSql($_GET['id']);
		$data = $gdM->getOneType($id);
		$this->assign('data',$data);
		$this->assign('titleURL',U('lst'));
		$this->assign('title',array("修改类型","类型列表"));
		$this->display();
	}

	public function delAction(){
		$gdM = new TypeModel();
		if(isset($_GET['id'])&&!empty($_GET['id'])){
			$id= proFrSql($_GET['id']);
			if($gdM->delType($id))
				$this->jump(U('lst'),'删除成功');
			else
				$this->jump(U('lst'),'删除失败');
		}
	}






}






?>