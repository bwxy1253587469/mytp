<?php
//基础控制器
class Controller extends MySmarty{
	public function __construct(){
		parent::__construct();
	}
	//定义跳转方法
	public function jump($url='',$message,$wait=3){
		$url = empty($url)?PHP_SELF:$url;
		if($wait==0){
			header("Location:$url");
		}else{
			include './message.html';
		}
		exit;
	}

	//定义载入辅助函数方法，如input_helper.class
	public function helper($helper){
		require HELPER_PATH."{$helper}_helper.php";
	}

	//定义载入类库方法，如Page.class.php
	public function library($lib){
		require LIB_PATH.UCfirst($lib).".class.php";
	}

	//定义加载不存在的方法时，调用的函数
	public function showHtml(){
		$this->display();
	}

	
}

?>