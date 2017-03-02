<?php
//登录控制器
class LoginController extends Controller{

	//载入登录页面
	public function loginAction(){
		$this->display();
	}

	//登录验证
	public function signinAction(){
		//0.验证验证码
		session_start();
		$captcha=trim($_POST['captcha']);
		if(strtolower($captcha)!=$_SESSION['captcha']){
			$this->jump(U('login'),'验证码错误',3);
		}
		//1.收集表单数据,并对特殊字符转义
		$username=proFrSql(trim($_POST['username']));
		$password=md5(proFrSql(trim($_POST['password'].$GLOBALS['config']['md5_add'])));
		//2.验证及处理数据
		//3.调用模型验证，给出提示
		$adminModel=new adminModel('admin');
		$userinfo=$adminModel->checkUser($username,$password);
		if(empty($userinfo)||$userinfo['is_use']==0){
			//不存在该用户
			$this->jump(U('login'),'该用户不存在或账号密码错误',3);
		}else{
			$_SESSION['admin']=$userinfo['id'];
			$this->jump(U('Index/index'),"",0);
		}
	}

	//注销登录
	public function logoutAction(){
		//销毁session
		if(!isset($_SESSION['admin']))
			session_start();
		unset($_SESSION['admin']);
		session_destroy();
		$this->jump(U('login'),'',0);
	}

	//生成验证码
	public function captchaAction(){
		//载入验证码类
		$this->library('captcha');
		//调用实例
		$c = new Captcha();
		$c->generateCode();
		session_start();
		$_SESSION['captcha'] = $c->getCode();
	}







}



?>