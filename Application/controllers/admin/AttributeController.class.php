<?php
//属性控制器
class AttributeController extends BaseController{
	public function lstAction(){
		$Mod = new AttributeModel();
		$id = $_GET['id'];
		$data = $Mod->getAtts($id);
		$TypeModel = new TypeModel();
		$typeData = $TypeModel->getTypes();
		$this->assign('typeId',$id);
		$this->assign('typeData',$typeData);
		$this->assign('data',$data['data']);
		$this->assign('page',$data['page']);
		$this->assign('titleURL',U('add','id ='.$id));
		$this->assign('title', array("属性列表","添加属性"));
		$this->display();
	}
	public function addAction(){
		$id = $_GET['id'];
		if(isset($_POST['attr_name'])&&!empty($_POST['attr_name'])){
			$Mod = new AttributeModel();
			if($Mod->addAtt())
				$this->jump(U('lst',"id =".$_POST['type_id']),'添加成功');
			else
				$this->jump(U('lst',"id =".$id),'添加失败');
		}
		$TypeModel = new TypeModel();
		$typeData = $TypeModel->getTypes();
		$this->assign('typeId',$id);
		$this->assign('typeData',$typeData);
		$this->assign('titleURL',U('lst'));
		$this->assign('title',array("添加属性","属性列表"));
		$this->display();
	}

	public function editAction(){
		$Mod = new AttributeModel();
		$id = $_GET['id'];
		$type_id = $_GET['type_id'];
		$data = $Mod->getOneAtt($id);
		$TypeModel = new TypeModel();
		$typeData = $TypeModel->getTypes();
		$this->assign('data',$data);
		$this->assign('typeId',$type_id);
		$this->assign('typeData',$typeData);
		$this->assign('titleURL',U('lst','id='.$type_id));
		$this->assign('title',array("修改属性","属性列表"));
		$this->display();
	}

	public function delAction(){
		$id = $_GET['id'];
		$type_id = $_GET['type_id'];
		$Mod = new AttributeModel();
		if($Mod->delAtt($id))
			$this->jump(U('lst',"id =".$type_id),'删除成功');
		else
			$this->jump(U('lst',"id =".$type_id),'删除失败');
	}

}
?>
