<?php
class AttributeModel extends Model{



	//获取一个属性
	public function getOneAtt($att_id){
		$sql = "select * from {$this->table} where id=$att_id";
		return $this->db->getRow($sql);
	}


	//获取商品属性
	public function getAtts($id){
		$sql = "select * from {$this->table} where type_id=$id";
		//分页
		require_once LIB_PATH."Page.class.php";
		isset($_GET['page'])?$p=$_GET['page']:$p=1;
		$count = $this->total('where type_id='.$id);
		$per = 5;
		$page = new Page($count,$per,$p);
		$sql .=" limit ".($p-1)*$per.",".$per;
		$data['data'] = $this->Mquery($sql);
		$data['page'] = $page->showPage();
		return $data;
	}

	public function addAtt(){
		$post = proFrSql($_POST);
		$post['attr_option_values'] = trim(trim($post['attr_option_values']),',');
		$sql = "insert into {$this->table} values('','".$post['attr_name']."','".$post['attr_type']."','".$post['attr_option_values']."','".$post['type_id']."')";
		//echo $sql;
		return $this->db->query($sql);
	}

	public function delAtt($id){
		$sql = "delete from {$this->table} where id =$id";
		return $this->db->query($sql);
	}

	public function editAtt(){}

	//获取指定类型下的属性，并构成表单
	public function getAttrsForm($type_id){
		// 获取该类型下所有的属性
		$sql = "select * from {$this->table} where type_id = $type_id";
		$attrs = $this->db->getAll($sql);
		$res = "<table width='100%' id='attrTable'>";
		$res .= "<tbody>";
		foreach ($attrs as $attr){
			$res .= "<tr>";
			$res .= "<td class='label'>{$attr['attr_name']}</td>";
			$res .= "<td>";
			$res .= "<input type='hidden' name='attr_id_list[]' value='".$attr['attr_id']."'>";
			//根据attr_input_type不同的值，生成不同的表单元素
			switch ($attr['attr_input_type']){
				case 0: #文本框
					$res .= "<input name='attr_value_list[]' type='text' size='40'>";
					break;
				case 1: #下拉列表
					$res .= "<select name='attr_value_list[]'>";
					$res .= "<option value=''>请选择...</option>";
					$opts = explode(PHP_EOL, $attr['attr_value']);
					foreach ($opts as $opt){
						$res .= "<option value='$opt'>$opt</option>";
					}
					$res .= "</select>";
					break;
				case 2: #多行文本
					$res .= "<textarea name='attr_value_list[]'></textarea>";
					break;
			}
			$res .= "<input type='hidden' name='attr_price_list[]' value='0'>";
			$res .= "</td>";
			$res .= "</tr>";
		}
		$res .= "</tbody>";
		$res .= "</table>";
		return $res;
	}
}
?>