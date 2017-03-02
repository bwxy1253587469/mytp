<?php
class PrivilegeController extends BaseController 
{
    public function lstAction()
    {
    	$model = new PrivilegeModel();
		$data = $model->getTree();
		$this->assign('data',$data);
		$this->assign('titleURL',U('add'));
		$this->assign('title',array("权限列表","添加权限"));
    	$this->display();
    }

	public function addAction(){
		$model = new PrivilegeModel();
		if(isset($_POST['pri_name'])&&!empty(trim($_POST['pri_name']))){
			if($model->addPri())
				$this->jump(U('lst'),'添加成功');
			else
				$this->jump(U('lst'),'添加失败');
		}
		$data = $model->getTree();
		$this->assign('parentData',$data);
		$this->assign('titleURL',U('lst'));
		$this->assign('title',array("添加权限","权限列表"));
    	$this->display();
	}

	public function editAction(){
		$model = new PrivilegeModel();
		if(isset($_POST['id'])&&!empty(trim($_POST['id']))){
			if($model->editPri())
				$this->jump(U('edit','id='.$_POST['id']),'修改成功');
			else
				$this->jump(U('edit','id='.$_POST['id']),'修改失败');
		}
		$data = $model->getTree();
		$onePri = $model->getOnePri($_GET['id']);
		$children = $model->getChildren($_GET['id']);
		$this->assign('children',$children);
		$this->assign('parentData',$data);
		$this->assign('data',$onePri);
		$this->assign('titleURL',U('lst'));
		$this->assign('title',array("添加权限","权限列表"));
    	$this->display();
	}

	public function delAction(){
		$model = new PrivilegeModel();
		if(isset($_GET['id'])&&!empty(trim($_GET['id']))){
			if($model->delPri($_GET['id']))
				$this->jump(U('lst'),'删除成功');
			else
				$this->jump(U('lst'),'删除失败');
		}
	}




}