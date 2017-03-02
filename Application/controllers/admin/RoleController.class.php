<?php
class RoleController extends BaseController 
{
    public function lstAction()
    {
    	$model = new RoleModel();
    	$data = $model->getRoles();
    	$this->assign('data',$data);
		$this->assign('titleURL',U('add'));
		$this->assign('title',array("角色列表","添加角色"));
		$this->display();
    }

	public function addAction(){
		$ModR = new RoleModel();
		if(isset($_POST['role_name'])&&!empty(trim($_POST['role_name']))){
			if($ModR->addRole())
				$this->jump(U('lst'),'添加成功');
			else
				$this->jump(U('lst'),'添加失败');
		}
		$model = new PrivilegeModel();
		$priData=$model->getTree();
		$this->assign('priData',$priData);
		$this->assign('titleURL',U('lst'));
		$this->assign('title',array("添加角色","角色列表"));
		$this->display();
	}

	public function editAction(){
		$ModR = new RoleModel();
		if(isset($_POST['id'])&&!empty(trim($_POST['id']))){
			if($ModR->editRole())
				$this->jump(U('edit','id='.$_POST['id']),'修改成功');
			else
				$this->jump(U('edit','id='.$_POST['id']),'修改失败');
		}
		$id = $_GET['id'];
		$model = new PrivilegeModel();
		$priData=$model->getTree();
		$this->assign('data',$ModR->getOneRole($id));
		$this->assign('priData',$priData);
		$this->assign('titleURL',U('lst'));
		$this->assign('title',array("修改角色","角色列表"));
		$this->display();
	}

	public function delAction(){
		$ModR = new RoleModel();
		if(isset($_GET['id'])&&!empty(trim($_GET['id']))){
			if($ModR->delRole($_GET['id']))
				$this->jump(U('lst'),'删除成功');
			else
				$this->jump(U('lst'),'删除失败');
		}else
			$this->jump(U('lst'),'删除信息错误');
	}





}