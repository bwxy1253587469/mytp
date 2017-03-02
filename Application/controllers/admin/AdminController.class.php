<?php
class AdminController extends BaseController 
{
    public function lstAction()
    {
    	$model = new AdminModel();
    	$data = $model->getAdmin();
    	$this->assign('data',$data);
		$this->assign('titleURL',U('add'));
		$this->assign('title',array("管理员列表","添加管理员"));
		$this->display();
    }
	public function addAction(){
		$model = new AdminModel();
		$post = proFrSql($_POST);
		if(isset($_POST['role_id'])&&!empty($_POST['role_id'])){
			if($post['password']==$post['cpassword']){
				if($model->addAdmin())
					$this->jump(U('lst'),'添加成功');
				else
					$this->jump(U('lst'),'添加失败');
			}else{
				$this->jump(U('lst'),'两次密码不一致');
			}
		}
		$Rmodel = new RoleModel();
    	$roleData = $Rmodel->getRoles();
    	$this->assign('roleData',$roleData);
		$this->assign('titleURL',U('lst'));
		$this->assign('title',array("添加管理员","管理员列表"));
		$this->display();
	}

	public function editAction(){
		$model = new AdminModel();
		$post = proFrSql($_POST);
		if(isset($_POST['id'])&&!empty($_POST['id'])){
			if($post['password']==$post['cpassword']){
				if($model->editAdmin())
					$this->jump(U('edit','id='.$_POST['id']),'修改成功');
				else
					$this->jump(U('edit','id='.$_POST['id']),'添加失败');
			}else{
				$this->jump(U('lst'),'两次密码不一致');
			}
		}
		$id =$_GET['id'];
		$Rmodel = new RoleModel();
    	$roleData = $Rmodel->getRoles();
		$rid = '';
		foreach($model->getAdminRoles($id) as $v)
			$rid .= $v['role_id'].",";
		$this->assign('rid',$rid);
    	$this->assign('roleData',$roleData);
		$this->assign('data',$model->selectByPk($id));
		$this->assign('titleURL',U('lst'));
		$this->assign('title',array("添加管理员","管理员列表"));
		$this->display();
	}

	public function delAction(){
		$model = new AdminModel();
		if(isset($_GET['id'])&&!empty($_GET['id'])){
				if($model->delAdmin($_GET['id']))
					$this->jump(U('lst'),'删除成功');
				else
					$this->jump(U('lst'),'删除失败');
		}
	}

    public function ajaxUpdateIsuse()
    {
    	
    }
}