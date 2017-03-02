<?php
class PrivilegeModel extends Model 
{
	public function getTree()
	{
		$data = $this->selectAll();
		return $this->_reSort($data);
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
	

	public function addPri(){
		$post = proFrSql($_POST);
		return $this->insert($post);
	}

	public function getOnePri($id){
		return $this->selectByPk($id);
	}

	public function editPri(){
		$post = proFrSql($_POST);
		return $this->update($post);
	}

	public function delPri($id){
		$children = $this->getChildren($id);
		foreach($children as $k=>$v)
			$ids[]=$v;
		$ids[]=$id;
		return $this->delete($ids);
	}
}