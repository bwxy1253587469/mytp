<?php
class MemberLevelController extends BaseController 
{
    public function lstAction()
    {
    	$gdM = new MemberLevelModel("member_level");
		$goods = $gdM->getMember();
		$this->assign('data',$goods);
		$this->assign('titleURL',U('add'));
		$this->assign('title',array("会员级别列表","添加会员级别"));
		$this->display();
    }

	public function addAction(){
		$gdM = new MemberLevelModel("member_level");
		if(isset($_POST['level_name'])&&!empty(trim($_POST['level_name']))){
				if($gdM->addMemberLevel())
					$this->jump(U('lst'),'添加成功');
				else
					$this->jump(U('lst'),'添加失败');
		}
		$this->assign('titleURL',U('lst'));
		$this->assign('title',array("添加会员级别","会员级别列表"));
		$this->display();
	}

	public function editAction(){
		$gdM = new MemberLevelModel("member_level");
		if(isset($_POST['id'])&&!empty($_POST['id'])){
				if($gdM->editMemberLevel())
					$this->jump(U('edit','id='.$_POST['id']),'修改成功');
				else
					$this->jump(U('edit','id='.$_POST['id']),'修改失败');
		}
		$id = $_GET['id'];
		$this->assign('data',$gdM->getOneMember($id));
		$this->assign('titleURL',U('lst'));
		$this->assign('title',array("添加会员级别","会员级别列表"));
		$this->display();
	}

	public function delAction(){
		$gdM = new MemberLevelModel("member_level");
		if(isset($_GET['id'])&&!empty($_GET['id'])){
				if($gdM->delMemberLevel($_GET['id']))
					$this->jump(U('lst'),'删除成功');
				else
					$this->jump(U('lst'),'删除失败');
		}
	}


}