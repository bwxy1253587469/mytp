<?php
class MemberModel extends Model 
{
	//验证用户名密码
	public function checkUser($email,$password){
		$sql="select * from {$this->table} where email='$email' and password='$password'";
		//echo $sql;
		return $this->db->getRow($sql);
	}
	// 现在这个方法可以不用密码也能登录，这个功能在QQ登录时要用
	public function login($usePassword = TRUE)
	{
		$email = $this->email;
		if($usePassword)
			$password = $this->password;
		$user = $this->where(array('email'=>array('eq', $email)))->find();
		if($user)
		{
			// 判断是否已经通过email验证
			if(empty($user['email_code']))
			{
				// 如果需要登录登录才验证密码否则无须输入密码
				if($usePassword)
				{
					if($user['password'] != md5($password . C('MD5_KEY')))
					{
						$this->error = '密码不正确！';
						return FALSE;
					}
				}
					session('mid', $user['id']);
					session('email', $user['email']);
					session('jyz', $user['jyz']);
					// 取出当前登录会员所在的级别ID和这个级别的折扣率
					$mlModel = M('MemberLevel');
					$ml = $mlModel->field('id,rate')->where("{$user['jyz']} BETWEEN bottom_num AND top_num")->find();
					session('level_id', $ml['id']);
					session('rate', $ml['rate']/100);
					// 把购物车中的数据从COOKIE移动到数据库
					$cartModel = D('Admin/Cart');
					$cartModel->moveDataToDb();
					// 如果有openid就绑定到这个账号上
					if(isset($_SESSION['openid']))
					{
						$this->where(array('id'=>array('eq', $user['id'])))->setField('openid', $_SESSION['openid']);
						unset($_SESSION['openid']);
					}
					return TRUE;
			}
			else 
			{
				$this->error = '账号还没有通过email验证！';
				return FALSE;
			}
		}
		else 
		{
			$this->error = '账号不存在！';
			return FALSE;
		}
	}
}














