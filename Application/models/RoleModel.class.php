<?php
class RoleModel extends Model 
{

	public function getOneRole($id){
		$sql = "select a.*,GROUP_CONCAT(b.pri_id) pri_id from php34_role a left join php34_role_privilege b on a.id = b.role_id where a.id =$id GROUP BY a.id";
		return $this->db->getRow($sql);
	}
	public function getRoles(){
		$where = '';
		if(isset($_GET['role_name'])){
			$where=" having role_name like '%".$_GET['role_name']."%'";
		}
		$sql ="select a.*,GROUP_CONCAT(c.pri_name) pri_name from php34_role a left join php34_role_privilege b on a.id = b.role_id left join php34_privilege c on b.pri_id = c.id GROUP BY a.id".$where;
		$data = $this->db->getAll($sql);
		return $data;
	}
	/*
	select a.*,GROUP_CONCAT(c.pri_name) pri_name from php34_role a left join php34_role_privilege b on a.id = b.role_id left join php34_privilege c on b.pri_id = c.id GROUP BY a.id;
	*/
	public function addRole(){
		$post = proFrSql($_POST);
		$id =$this->insert($post);
		$sql = "insert into php34_role_privilege values";
		foreach($post['pri_id'] as $k=>$v){
			$sql .="($v,$id),";
		}
		if(strpos($sql,')'))
			return $this->db->query(trim($sql,','));
		else{
			$this->delete($id);
			return false;
		}
	}

	public function editRole(){
		$post = proFrSql($_POST);
		$id = $post['id'];
		$sql = "delete from php34_role_privilege where role_id= $id";
		$this->db->query($sql);
		$this->update($post);
		$sql = "insert into php34_role_privilege values";
		foreach($post['pri_id'] as $k=>$v){
			$sql .="($v,$id),";
		}
		if(strpos($sql,')'))
			return $this->db->query(trim($sql,','));
		else{
			return false;
		}
	}


	public function delRole($id){
		$sql = "delete from php34_role_privilege where role_id= $id";
		$this->db->query($sql);
		return $this->delete($id);
	}






}