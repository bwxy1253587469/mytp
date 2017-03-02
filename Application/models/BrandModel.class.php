<?php
class BrandModel extends Model 
{
	public function getOneBrand($id){
		$data = $this->selectByPk($id);
		return $data;
	}

	public function getBrands(){
		$where = '';
		if(isset($_GET['brand_name'])){
			$where="where brand_name like '%".$_GET['brand_name']."%'";
		}
		$goods = $this->selectAll($where);
		return $goods;
	}

	public function addBrand(){
		$post = proFrSql($_POST);
		$file_name = '';
		//文件上传
		if(isset($_FILES['logo']['error'])&&$_FILES['logo']['error']==0){
			require_once LIB_PATH."Upload.class.php";
			$upload = new upload();
			$file_name = $upload->up($_FILES['logo']);
			//var_dump($file_name);
		}
		$post['logo'] = $file_name ;
		return $this->insert($post);
	}

	public function delBrand($id){
		return $this->delete($id);
	}

	public function editBrand(){
		$post = proFrSql($_POST);
		//文件上传
		if(isset($_FILES['logo']['error'])&&$_FILES['logo']['error']==0){
			require_once LIB_PATH."Upload.class.php";
			$upload = new upload();
			$post['logo'] = $upload->up($_FILES['logo']);
			//var_dump($file_name);
		}
		return $this->update($post);
	}




}