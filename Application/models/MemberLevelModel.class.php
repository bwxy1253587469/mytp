<?php
class MemberLevelModel extends Model 
{
	public function getMember(){
		$data = $this->selectAll();
		return $data;
	}
	public function getOneMember($id){
		$data = $this->selectByPk($id);
		return $data;
	}
	public function addMemberLevel(){
		$post = proFrSql($_POST);
		return $this->insert($post);
	}
	public function editMemberLevel(){
		$post = proFrSql($_POST);
		return $this->update($post);
	}
	public function delMemberLevel($id){
		return $this->delete($id);
	}
	//根据积分得到会员等级
	public function getMemberLevel($num){
		$sql = "select id,rate from {$this->table} where $num>=bottom_num and $num<=top_num";
		return $this->db->getRow($sql);
	}
}