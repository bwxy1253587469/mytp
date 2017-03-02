<?php
class TypeModel extends Model{
	public function getTypes(){
		$sql="select * from {$this->table} order by id";
		return $this->db->getAll($sql);
	}
	public function getOneType($id){
		$sql="select * from {$this->table} where id =$id order by id";
		return $this->db->getRow($sql);
	}
	public function editType(){
		$post= proFrSql($_POST);
		$sql ="update {$this->table} set type_name='".$post['type_name']."' where id =".$post['id'];
		return $this->db->query($sql);
	}
	public function insertType(){
		$post= proFrSql($_POST);
		$sql ="insert into {$this->table} values('','".$post['type_name']."')";
		return $this->db->query($sql);
	}
	public function delType($id){
		$sql ="delete from {$this->table} where id =".$id;
		return $this->db->query($sql);
	}

	//分页获取商品类型数据
	public function getPageTypes($offset,$pagesize){
		//左连接查询，获取每个类型对应的属性个数
		$sql = "SELECT a.* ,b.c FROM cz_goods_type as a left join (select count(*) as c,type_id from cz_attribute group by type_id) b on a.type_id=b.type_id ORDER BY type_id LIMIT $offset,$pagesize";
		//$sql = "SELECT * FROM {$this->table} ORDER BY type_id LIMIT $offset,$pagesize";
		return $this->db->getAll($sql);
	}
}

?>