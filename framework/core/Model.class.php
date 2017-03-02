<?php
//模型类基类
class Model{
	public $db; //数据库连接对象
	protected $table; //表名
	protected $fields = array();  //字段列表

	public function __construct($table=null){
		$dbconfig['host'] = $GLOBALS['config']['host'];
		$dbconfig['user'] = $GLOBALS['config']['user'];
		$dbconfig['password'] = $GLOBALS['config']['password'];
		$dbconfig['dbname'] = $GLOBALS['config']['dbname'];
		$dbconfig['port'] = $GLOBALS['config']['port'];
		$dbconfig['charset'] = $GLOBALS['config']['charset'];
		
		$this->db = new Mysql($dbconfig);
		if($table === null){
			$table = str_replace('Model','',get_class($this));
		}
		$this->table = $GLOBALS['config']['prefix'] . strtolower($table);
		

		//调用getFields字段
		$this->getFields();
	}

	/**
	 * 获取表字段列表
	 *
	 */
	private function getFields(){
		$sql = "DESC ". $this->table;
		$result = $this->db->getAll($sql);

		foreach ($result as $v) {
			$this->fields[] = $v['Field'];
			if ($v['Key'] == 'PRI') {
				//如果存在主键的话，则将其保存到变量$pk中
				$pk = $v['Field'];
			}
		}
		//如果存在主键，则将其加入到字段列表fields中
		if (isset($pk)) {
			$this->fields['pk'] = $pk;
		}
	}

	/**
	 * 自动插入记录
	 * @access public
	 * @param $list array 关联数组
	 * @return mixed 成功返回插入的id，失败则返回false
	 */
	public function insert($list){
		$field_list = '';  //字段列表字符串
		$value_list = '';  //值列表字符串
		foreach ($list as $k => $v) {
			if (in_array($k, $this->fields)) {
				$field_list .= "`".$k."`" . ',';
				$value_list .= "'".$v."'" . ',';
			}
		}
		//去除右边的逗号
		$field_list = rtrim($field_list,',');
		$value_list = rtrim($value_list,',');
		//构造sql语句
		$sql = "INSERT INTO `{$this->table}` ({$field_list}) VALUES ($value_list)";

		if ($this->db->query($sql)) {
			# 插入成功,返回最后插入的记录id
			return $this->db->getInsertId();
			//return true;
		} else {
			# 插入失败，返回false
			return false;
		}
		
	}

	/**
	 * 自动更新记录
	 * @access public
	 * @param $list array 需要更新的关联数组
	 * @return mixed 成功返回受影响的记录行数，失败返回false
	 */
	public function update($list){
		$uplist = ''; //更新列表字符串
		$where = 0;   //更新条件,默认为0
		foreach ($list as $k => $v) {
			if (in_array($k, $this->fields)) {
				if ($k == $this->fields['pk']) {
					# 是主键列，构造条件
					$where = "`$k`=$v";
				} else {
					# 非主键列，构造更新列表
					$uplist .= "`$k`='$v'".",";
				}
			}
		}
		//去除uplist右边的
		$uplist = rtrim($uplist,',');
		//构造sql语句
		$sql = "UPDATE `{$this->table}` SET {$uplist} WHERE {$where}";
		
		if ($this->db->query($sql)) {
			# 成功，并判断受影响的记录数
			if ($rows = mysql_affected_rows()) {
				# 有受影响的记录数
				return $rows;
			} else {
				# 没有受影响的记录数，没有更新操作
				return false;
			}	
		} else {
			# 失败，返回false
			return false;
		}
		
	}

	/**
	 * 自动删除
	 * @access public
	 * @param $pk mixed 可以为一个整型，也可以为数组
	 * @return mixed 成功返回删除的记录数，失败则返回false
	 */
	public function delete($pk){
		$where = 0; //条件字符串
		//判断$pk是数组还是单值，然后构造相应的条件
		if (is_array($pk)) {
			# 数组
			$where = "`{$this->fields['pk']}` in (".implode(',', $pk).")";
		} else {
			# 单值
			$where = "`{$this->fields['pk']}`=$pk";
		}
		//构造sql语句
		$sql = "DELETE FROM `{$this->table}` WHERE $where";

		if ($this->db->query($sql)) {
			# 成功，并判断受影响的记录数
			if ($rows = mysql_affected_rows()) {
				# 有受影响的记录
				return $rows;
			} else {
				# 没有受影响的记录
				return false;
			}		
		} else {
			# 失败返回false
			return false;
		}
	}

/***


*/	
	

	public function selectAll($where =''){
		if(empty($where)){
			$sql = "select * from $this->table";
		}
		else{
			$sql = "select * from $this->table $where";
		}
		return $this->db->getAll($sql);
	}
	/**
	 * 通过主键获取信息
	 * @param $pk int 主键值
	 * @return array 单条记录
	 */
	public function selectByPk($pk){
		$sql = "select * from `{$this->table}` where `{$this->fields['pk']}`=$pk";
		return $this->db->getRow($sql);
	}

	/**
	 * 获取总的记录数
	 * @param string $where 查询条件，如"id=1"
	 * @return number 返回查询的记录数
	 */
	public function total($where = ''){
		if(empty($where)){
			$sql = "select count(*) from {$this->table}";
		}else{
			$sql = "select count(*) from {$this->table} $where";
		}
		return $this->db->getOne($sql);
	}

	/**
	 * 分页获取信息
	 * @param $offset int 偏移量
	 * @param $limit int 每次取记录的条数
	 * @param $where string where条件,默认为空
	 */
	public function pageRows($offset, $limit,$where = ''){
		if (empty($where)){
			$sql = "select * from {$this->table} limit $offset, $limit";
		} else {
			$sql = "select * from {$this->table}  where $where limit $offset, $limit";
		}
		
		return $this->db->getAll($sql);
	}

	public function Mquery($sql){
		$result=$this->db->query($sql);
		$list = array();
		while ($row = mysql_fetch_assoc($result)){
			$list[] = $row;
		}
		return $list;
	}

	//定义一个函数有数组得到一个树状结构
	/*
	*@param $arr array 给定的数组
	*@param $pid int 指定父节点的id
	*@param $level int 节点所在的层级
	*@return array 返回制作好的数组
	*/
	public function tree($arr,$pid=0,$level=0){
		$tree=array();
		foreach($arr as $va){
			if($va['parent_id']==$pid){
				$va['level']=$level;
				$tree[]=$va;
				$tree=array_merge($tree,$this->tree($arr,$va['id'],$level+1));
			}
		}
		return $tree;
	}



	//无限级分类的删除方法，最多只允许3层
	public function getChildren($id)
	{
		$data = $this->selectAll();
		return $this->_children($data, $id);
	}
	private function _children($data, $parent_id=0, $isClear=TRUE)
	{
		static $ret = array();
		if($isClear)
			$ret = array();
		foreach ($data as $k => $v)
		{
			if($v['parent_id'] == $parent_id)
			{
				$ret[] = $v['id'];
				$this->_children($data, $v['id'], FALSE);
			}
		}
		return $ret;
	}

}