<?php
class MemberController extends BaseController 
{
   public function registAction()
   {
   		if(isset($_POST['email']))
   		{
   			$model = new MemberModel();
			$post = proFrSql($_POST);
   			//0.验证验证码
			session_start();
			$chkcode=trim($post['chkcode']);
			if(strtolower($chkcode)!=$_SESSION['captcha']){
				$this->jump(U('regist'),'验证码错误',3);
			}
			if($post['password']!=$post['cpassword']){
				$this->jump(U('regist'),'2次密码不一致',3);
			}
			$post['password']=md5(trim($post['password'].$GLOBALS['config']['md5_add']));
			$post['addtime'] = time();
			if($model->insert($post))
				$this->jump(U('login'),'注册成功',3);
			else
				$this->jump(U('regist'),'注册失败',3);
   		}
   		// 设置页面标题等信息
   		$this->setPageInfo('会员注册', '会员注册', '会员注册', 1, array('login'));
   		$this->display();
   }
   public function loginAction()
   {
	   if(isset($_POST['email']))
   		{
   			$model = new MemberModel();
			$post = proFrSql($_POST);
   			//0.验证验证码
			session_start();
			$chkcode=trim($post['chkcode']);
			if(strtolower($chkcode)!=$_SESSION['captcha']){
				$this->jump(U('login'),'验证码错误',3);
			}
			$password=md5(trim($post['password'].$GLOBALS['config']['md5_add']));
			$userinfo=$model->checkUser($post['email'],$password);
			if(empty($userinfo)){
				//不存在该用户
				$this->jump(U('login'),'该用户不存在或账号密码错误',30);
			}else{
				//计算会员等级
				$MemberLevelModel = new MemberLevelModel('member_level');
				$tem =$MemberLevelModel->getMemberLevel($userinfo['jifen']);
                $userinfo['level'] = $tem['id'];
				$userinfo['rate'] = $tem['rate']/100;
				$_SESSION['home']=$userinfo;
				$this->jump(U('Index/index'),"",0);
			}
   		}
   		// 设置页面标题等信息
   		$this->setPageInfo('会员登录', '会员登录', '会员登录', 1, array('login'));
   		$this->display();
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
	public function logoutAction()
	{
		//销毁session
		if(!isset($_SESSION['home']))
			session_start();
		unset($_SESSION['home']);
		session_destroy();
		$this->jump(U('index/index'),'',0);
	}
	// 判断登录
	public function ajaxChkLoginAction()
	{
		session_start();
		if(isset($_SESSION['home']))
		{
			$arr = array(
				'ok' => 1,
				'email' => $_SESSION['home']['email'],
			);
		}
		else
		{
			$arr = array('ok' => 0);
		}
		echo json_encode($arr);
	}
	public function saveAndLogin()
	{
		// 获取AJAX是从哪个页面发过来的
		session('returnUrl', $_SERVER['HTTP_REFERER']);
	}
	public function qqlogin()
	{
		// 调用QQ的两个接口获取openid
		include('./qq/oauth/qq_callback.php');  // 获取openid并保存到session中
		// 查询会员表中哪个会员与这个QQ相关联
		$member = D('Admin/Member');
		$user = $member->field('email')->where(array('openid'=>array('eq', $_SESSION['openid'])))->find();
		// QQ号已经和一个账号关联之后
		if($user)
		{
			// 如果有这个会员就让这个会员为登录状态
			$member->email = $user['email']; // 把账号传给会员模型，登录时要用
			if($member->login(FALSE) === FALSE)
			{
				header('Content-type:text/html;charset=utf-8');
				die($member->getError());
			}
			echo <<<JS
			<script>
			opener.window.location.href='/';
			window.close();
			</script>
JS;
			exit;
		}
		else 
		{
			// 如果是第一次用QQ号登录那么应该显示一个表单引导用户关联一个账号
			redirect(U('login'));
		}
	}
}















