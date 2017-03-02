<?php
class IndexController extends BaseController{
	function indexAction(){
		$this->display();
	}

	function topAction(){
		$this->display();
	}

	function menuAction(){
		//var_dump($_SESSION);
		//查找对应的连接
		$model = new model("privilege");
		if($_SESSION['admin']!=1){
			$sql = "select distinct(b.pri_id) from php34_admin_role a left join php34_role_privilege b on a.role_id=b.role_id where a.admin_id=".$_SESSION['admin'];
			$sql1 = "SELECT * FROM php34_privilege where id in(";
			foreach($model->db->getAll($sql) as $k=>$v){
				$sql1 .=$v['pri_id'].",";
			}
			$sql1 =trim($sql1,',').")";
		}else
			$sql1="SELECT * FROM php34_privilege";
		$pri = $model->Mquery($sql1);
		$btn = array();
		// 放前两级的权限
		// 从所有的权限中取出前两级的权限
		foreach ($pri as $k => $v)
		{
			// 找顶级权限
			if($v['parent_id'] == 0)
			{
				// 再循环把这个顶级权限的子权限
				foreach ($pri as $k1 => $v1)
				{
					if($v1['parent_id'] == $v['id'])
					{
						$v['children'][] = $v1;
					}
				}
				$btn[] = $v;
			}
		}
		//var_dump($btn);
		$this->assign('btn', $btn);
		$this->display();
	}

	function mainAction(){
		$this->display();
	}
}
?>