<?php
//后台基础控制器
class BaseController extends Controller{
	public function __construct(){
		$this->checkLogin();
		parent::__construct();
	}

	//验证用户是否登录
	public function checkLogin(){
		if(!isset($_SESSION)){
			session_start();
		}
		//var_dump($_SESSION['admin']);
		//if(empty($_SESSION['admin'])||!isset($_SESSION['admin'])){
		if(empty($_SESSION['admin'])||!isset($_SESSION['admin'])){
			//说明没有登录
			$this->jump(U('login/login'),'你还没有登录',3);
		}else{
			//检测是否有权限
			if($_SESSION['admin']!=1){
				$model = new model("privilege");
				$sql = "select distinct(b.pri_id) from php34_admin_role a left join php34_role_privilege b on a.role_id=b.role_id where a.admin_id=".$_SESSION['admin'];
				$ids =",,,,,,";//注意strpos函数有可能再找到的情况下返回0，因此字符串开头要长，
				foreach($model->db->getAll($sql) as $k=>$v){
					$ids .=$v['pri_id'].",";
				}
				//var_dump($ids);
				$sql = "select * from php34_privilege where module_name ='".PLATFORM."'and controller_name ='".CONTROLLER."' and action_name='".ACTION."'";
				//echo $sql."<br>";
				$id=$model->db->getRow($sql);
				if(!empty($id)&&!strpos($ids,','.$id['id'].',')){
					$this->jump(U('Index/index'),'操作非法',3);
				}
			}
		}
	}







}



?>