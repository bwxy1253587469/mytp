<?php
class CategoryModel extends Model{
	//查询获取所有的分类
	public function getCats(){
		$sql="select * from {$this->table} order by id asc";
		$data = $this->db->getAll($sql);
		return $this->_reSort($data);
	}
	//根据ID得到分类信息
	public function getOneCat($id){
		$sql = "select * from {$this->table} where id =$id";
		return $this->db->getRow($sql);
	}
	public function getTypes(){
		$sql = "SELECT * FROM `php34_type`";
		return $this->db->getAll($sql);
	}
	public function AttrData($ids){
		$sql ="select a.*,b.type_name from php34_attribute a inner join php34_type b on a.type_id=b.id where a.id in ($ids)";
		return $this->db->getAll($sql);
	}

	//更新分类信息
	public function updateCat(){
		$post = proFrSql($_POST);
		//var_dump($post);
		sort($post['attr_id']);
		$search_attr_id =trim(implode(',',$post['attr_id']));
		if(!empty($post['cat_name']))
			$sql ="update php34_category set cat_name ='".$post['cat_name']."',parent_id ='".$post['parent_id']."',search_attr_id='$search_attr_id' where id =".$post['id'];
		return $this->db->query($sql);
	}

	//插入数据
	public function insertCat(){
		$post = proFrSql($_POST);
		var_dump($post);
		sort($post['attr_id']);
		$search_attr_id =trim(implode(',',$post['attr_id']),',');
		if(!empty($post['cat_name']))
			$sql ="insert into php34_category values('','".$post['cat_name']."','".$post['parent_id']."','$search_attr_id')";
		//echo $sql;
		return $this->db->query($sql);
	}

	//删除分类
	public function delCats($id){
		//删除id，parentId=$id的数据
		$sql = "delete from php34_category where id =$id or parent_id  =$id";
		return $this->db->query($sql);
	}


	private function _reSort($data, $parent_id=0, $level=0, $isClear=TRUE)
	{
		static $ret = array();
		if($isClear)
			$ret = array();
		foreach ($data as $k => $v)
		{
			if($v['parent_id'] == $parent_id)
			{
				$v['level'] = $level;
				$ret[] = $v;
				$this->_reSort($data, $v['id'], $level+1, FALSE);
			}
		}
		return $ret;
	}
	





}
?>