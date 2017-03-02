<?php
//Admin模型
class AdminModel extends Model{
	//验证用户名密码
	public function checkUser($username,$password){
		$sql="select * from {$this->table} where username='$username' and password='$password'";
		return $this->db->getRow($sql);
	}
	//得到admin表信息
	public function getAdmin(){
		$where = '';
		if(isset($_GET['username'])){
			$where="where username like '%".$_GET['username']."%'"." and is_use=".$_GET['is_use'];
		}
		$data = $this->selectAll($where);
		return $data;
	}

	public function addAdmin(){
		$post = proFrSql($_POST);
		$post['password'] = md5($post['password'].$GLOBALS['config']['md5_add']);
		$id =$this->insert($post);
		$sql = "insert into php34_admin_role values";
		foreach($post['role_id'] as $k=>$v){
			$sql .="($id,$v),";
		}
		if(strpos($sql,')'))
			return $this->db->query(trim($sql,','));
		else{
			$this->delete($id);
			return false;
		}
	}

	public function getAdminRoles($id){
		$sql = "select role_id from php34_admin_role where admin_id =$id";
		return $this->Mquery($sql);
	}

	public function delAdmin($id){
		$this->delete($id);
		$sql = "delete from php34_admin_role where admin_id=".$id;
		return $this->db->query($sql);
	}

	public function editAdmin(){
		$post = proFrSql($_POST);
		if(empty(trim($post['password']))){
			unset($post['password']);
		}else{
			$post['password'] = md5($post['password'].$GLOBALS['config']['md5_add']);
		}
		$this->update($post);
		$id =$post['id'];
		//先删除所有，再添加
		$sql = "delete from php34_admin_role where admin_id =$id";
		$this->db->query($sql);
		$sql = "insert into php34_admin_role values";
		foreach($post['role_id'] as $k=>$v){
			$sql .="($id,$v),";
		}
		if(strpos($sql,')'))
			return $this->db->query(trim($sql,','));
		else{
			$this->delete($id);
			return false;
		}
	}

}
?>