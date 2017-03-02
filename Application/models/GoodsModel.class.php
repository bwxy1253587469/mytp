<?php
class GoodsModel extends Model{
	/*
select a.*,b.goods_number goods_number from php34_goods a  inner join (select goods_id,sum(goods_number) goods_number from php34_goods_number group by goods_id) b on a.id=b.goods_id ;
select goods_id,sum(goods_number) from php34_goods_number group by goods_id;
*/
	public function getGoods($is_delete=0){
		$where = "select a.*,b.goods_number from php34_goods a  left join (select goods_id,sum(goods_number) goods_number from php34_goods_number group by goods_id) b on a.id=b.goods_id where is_delete =".$is_delete." order by a.id desc";
		$sql = ' where is_delete ='.$is_delete;
		if(isset($_GET['goods_name'])){
			$where='select a.*,b.goods_number from php34_goods a  left join php34_goods_number b on a.id=b.goods_id '.$this->search()." and is_delete =$is_delete ";
			$sql = $this->search()." and is_delete =$is_delete ";
		}
		//分页
		require_once LIB_PATH."Page.class.php";
		isset($_GET['page'])?$p=$_GET['page']:$p=1;
		$count = $this->total($sql);
		$per = 5;
		$page = new Page($count,$per,$p);
		//echo "     ".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."<br>";
		$where .=" limit ".($p-1)*$per.",".$per;
		$goods['data'] = $this->Mquery($where);
		$goods['page'] = $page->showPage();
		return $goods;
	}

	//搜索拼装sql语句，返回sql where条件语句
	public function search(){
		$sql = "where 1";
		if(!empty($_GET['goods_name']))
			$sql .=" and goods_name like '%".proFrSql($_GET['goods_name'])."%'";
		if(!empty($_GET['cat_id']))
			$sql .=" and cat_id=".proFrSql($_GET['cat_id']);
		if(!empty($_GET['brand_id']))
			$sql .=" and brand_id=".proFrSql($_GET['brand_id']);
		if(!empty($_GET['shop_pricefrom']))
			$sql .=" and shop_price>=".proFrSql($_GET['shop_pricefrom']);
		if(!empty($_GET['shop_priceto']))
			$sql .=" and shop_price<=".proFrSql($_GET['shop_priceto']);
		if(isset($_GET['is_hot'])&&$_GET['is_hot']!=-1)
			$sql .=" and is_hot=".proFrSql($_GET['is_hot']);
		if(isset($_GET['is_new'])&&$_GET['is_new']!=-1)
			$sql .=" and is_new=".proFrSql($_GET['is_new']);
		if(isset($_GET['is_best'])&&$_GET['is_best']!=-1)
			$sql .=" and is_best=".proFrSql($_GET['is_best']);
		if(isset($_GET['is_on_sale'])&&$_GET['is_on_sale']!=-1)
			$sql .=" and is_on_sale=".proFrSql($_GET['is_on_sale']);
		if(!empty($_GET['type_id']))
			$sql .=" and type_id=".proFrSql($_GET['type_id']);
		if(!empty($_GET['addtimefrom']))
			$sql .=" and shop_price>=".proFrSql(strtotime($_GET['addtimefrom']."00:00:00"));
		if(!empty($_GET['addtimeto']))
			$sql .=" and shop_price<=".proFrSql(strtotime($_GET['addtimeto']."23:59:59"));
		return $sql;
	}


	//得到商品属性对应的数量$id商品id
	public function getNum($id){
		//获取商品属性名
		$sql = "SELECT * FROM `php34_attribute` where id in (select attr_id from php34_goods_attr where goods_id = $id) and attr_type = 1";
		$data['attr'] = $this->Mquery($sql);

		//获取商品属性对应的值
		$sql = "SELECT a.*,b.attr_name,b.attr_option_values FROM `php34_goods_attr` a left join php34_attribute b on a.attr_id = b.id where a.goods_id =".$id." and attr_type = 1";
		$data['attrvalue'] = $this->Mquery($sql);
		//获取商品对应的数量
		$sql = "SELECT * FROM `php34_goods_number` where goods_id =".$id;
		$data['num'] = $this->Mquery($sql);
		return $data;
	}

	//商品更新库存处理
	public function delNum(){
		$post = proFrSql($_POST);
		//删除原先的id对应的所有数据
		$sql = "delete from php34_goods_number where goods_id = ".$post['goods_id'];
		$this->db->query($sql);
		if(!empty($_POST['goods_number'])){
			$sql = "insert into php34_goods_number values";
			foreach($_POST['goods_number'] as $k=>$v){
				if(isset($post["goods_attr_id"][$k])){
					sort($post["goods_attr_id"][$k]);
					$goods_attr_id = implode(',',$post["goods_attr_id"][$k]);
					}
					else
						$goods_attr_id = '';
				$sql .= "(".$post['goods_id'].",$v,'$goods_attr_id'),";
			}
			$this->db->query(trim($sql,','));
			//echo $sql;
		}

	}


	//商品编辑数据处理
	public function editdata($id){
		// 取出所有的商品类型
		$sql = "select * from php34_type";
		$data['typeData'] = $this->db->getAll($sql);
    	// 取出所有的商品分类
		$sql = "select * from php34_category";
		$data['catData'] = $this->tree($this->db->getAll($sql));
    	// 取出所有的品牌
		$sql ="select * from php34_brand";
		$data['brandData'] = $this->db->getAll($sql);
    	// 取出所有的会员级别和取出当前商品会员价格的数据
		//select a.*,b.* from php34_member_level a left join (select * from php34_member_price where goods_id = 7) b on a.id = b.level_id
		$sql = "select a.*,b.* from php34_member_level a left join (select * from php34_member_price where goods_id = $id) b on a.id = b.level_id";
    	$data['mlData'] = $this->db->getAll($sql);
    	// 取出要修改的商品的基本信息
		$data['data'] = $this->selectAll('where id = '.$id);
    	// 取出当前商品扩展分类的数据
		$sql = "select * from php34_goods_cat where goods_id = ".$id;
		$data['extCatId'] = $this->db->getAll($sql);
    	// 取出当前商品的属性数据
		$sql = "select a.*,b.attr_name,b.attr_type ,b.attr_option_values,b.type_id from (select * from php34_goods_attr where goods_id = $id) a left join php34_attribute b on a.attr_id = b.id";
		$data['gaData'] = $this->db->getAll($sql);
		// 取出当前类型下的属性
    	// 取出当前商品的图片
		$sql = "select * from php34_goods_pics where goods_id = $id";
		$data['gpData'] = $this->db->getAll($sql);

		return $data;
	}

	//商品添加数据处理
	public function adddata(){
		// 取出所有的商品类型
		$sql = "select * from php34_type";
		$data['typeData'] = $this->db->getAll($sql);
    	// 取出所有的商品分类
		$sql = "select * from php34_category";
		$data['catData'] = $this->tree($this->db->getAll($sql));
    	// 取出所有的品牌
		$sql ="select * from php34_brand";
		$data['brandData'] = $this->db->getAll($sql);
    	// 取出所有的会员级别的数据
		$sql = "select * from php34_member_level";
    	$data['mlData'] = $this->db->getAll($sql);

		return $data;
	}


	public function updateGoods(){
		$post = proFrSql($_POST);
		//var_dump($_POST);
		//文件上传
		if($_FILES['logo']['error']==0){
			require_once LIB_PATH."Upload.class.php";
			$upload = new upload();
			$file_name = $upload->up($_FILES['logo']);
			//var_dump($file_name);
			//生成缩略图
			require_once LIB_PATH."Image.class.php";
			$img = new image();
			$thumb = $img->thumbnail(UPLOAD.$file_name,200,200,UPLOAD.str_replace(strrchr($file_name,'/'),'',$file_name).'/');
		}
		//更新商品基本信息
		$sql = "update php34_goods set 
						goods_name ='".$post['goods_name']."',
						cat_id ='".$post['cat_id']."',
						brand_id ='".$post['brand_id']."',
						market_price ='".$post['market_price']."',
						shop_price ='".$post['shop_price']."',
						jifen ='".$post['jifen']."',
						jyz ='".$post['jyz']."',
						jifen_price ='".$post['jifen_price']."',
						is_hot ='".$post['is_hot']."',
						is_new ='".$post['is_new']."',
						is_best ='".$post['is_best']."',
						is_on_sale ='".$post['is_on_sale']."',
						seo_keyword ='".$post['seo_keyword']."',
						seo_description ='".$post['seo_description']."',
						type_id ='".$post['type_id']."',
						sort_num ='".$post['sort_num']."',
						goods_desc ='".$post['goods_desc']."',
						addtime ='".time()."'";
		//商品logo
		if($_FILES['logo']['error']==0){
			$sql .=",logo ='".$file_name."',sm_logo ='".$thumb."'";
		}
		//是否促销
		if(isset($post['is_promote'])){
			$sql .=	",is_promote ='".$post['is_promote']."',
						promote_price ='".$post['promote_price']."',
						promote_start_time ='".strtotime($post['promote_start_time'])."',
						promote_end_time ='".strtotime($post['promote_end_time'])."'";
		}else{
			$sql .=	",is_promote ='0',promote_price ='0.00',promote_start_time ='',promote_end_time =''";
		}
		//是否添加商品类型
		if(empty($post['type_id'])){
			$sql .=",type_id ='0'";
		}else
			$sql .=",type_id =".$post['type_id'];
		$sql .=" where id =".$post['id'];
		$this->db->query($sql);
		//添加类型属性,先删除再添加
		$sql ="delete from php34_goods_attr where goods_id =".$post['id'];
		$this->db->query($sql);
		if(isset($post['ga'])){
			$sql ="insert into php34_goods_attr values";
			foreach($post['ga'] as $k=>$v){
				if(!empty($v)){
					foreach($v as $kv=>$vv){
						if(!empty($vv)){
							$attr_price=isset($post['attr_price'][$k])?$post['attr_price'][$k][$kv]:0;
							$sql .="('','".$post['id']."','$k','$vv','$attr_price'),";
						}
					}
				}
			}
			if(strpos($sql,'('))
				$this->db->query(trim($sql,','));
		}

		//添加扩展分类
		//先删除再添加
		$sql ="delete from php34_goods_cat where goods_id = ".$post['id'];
		$this->db->query($sql);
		$sql ="insert into php34_goods_cat values";
		foreach($post['ext_cat_id'] as $k=>$v){
			if(!empty($v))
				$sql .="(".$post['id'].",$v),";
		}
		if(strpos($sql,'('))
			$this->db->query(trim($sql,','));


		//会员价格
		//先删除再添加
		$sql = "delete from php34_member_price where goods_id = ".$post['id'];
		$this->db->query($sql);
		$sql ="insert into php34_member_price values";
		foreach($post['mp'] as $k=>$v){
			if(!empty($v)&&is_numeric($v))
				$sql .="(".$post['id'].",$k,$v),";
		}
		if(strpos($sql,'('))
			$this->db->query(trim($sql,','));

		//添加商品图片
		
		if(isset($_FILES['pics'])&&$_FILES['pics']['error'][0]==0){
			require_once LIB_PATH."Upload.class.php";
			$upload = new upload();
			$file_name = $upload->multiUp($_FILES['pics']);
			//var_dump($file_name);
			//生成缩略图
			require_once LIB_PATH."Image.class.php";
			$img = new image();
			foreach($file_name as $k=>$v)
			$thumb[$k] = $img->thumbnail(UPLOAD.$v,200,200,UPLOAD.str_replace(strrchr($v,'/'),'',$v).'/');
			//var_dump($thumb);

			$sql ="insert into php34_goods_pics values";
			foreach($file_name as $k=>$v){
				if(!empty($v))
					$sql .="('','$v','".$thumb[$k]."','".$post['id']."'),";
			}
			if(strpos($sql,'('))
				$this->db->query(trim($sql,','));
		}
		
	}


	//添加数据
	public function insertGoods(){
		$post = proFrSql($_POST);
		$post['id'] = $this->insert(array('id'=>''));
		//var_dump($_POSt);
		//文件上传
		if($_FILES['logo']['error']==0){
			require_once LIB_PATH."Upload.class.php";
			$upload = new upload();
			$file_name = $upload->up($_FILES['logo']);
			//var_dump($file_name);
			//生成缩略图
			require_once LIB_PATH."Image.class.php";
			$img = new image();
			$thumb = $img->thumbnail(UPLOAD.$file_name,200,200,UPLOAD.str_replace(strrchr($file_name,'/'),'',$file_name).'/');
		}
		//更新商品基本信息
		$sql = "update php34_goods set 
						goods_name ='".$post['goods_name']."',
						cat_id ='".$post['cat_id']."',
						brand_id ='".$post['brand_id']."',
						market_price ='".$post['market_price']."',
						shop_price ='".$post['shop_price']."',
						jifen ='".$post['jifen']."',
						jyz ='".$post['jyz']."',
						jifen_price ='".$post['jifen_price']."',
						is_hot ='".$post['is_hot']."',
						is_new ='".$post['is_new']."',
						is_best ='".$post['is_best']."',
						is_on_sale ='".$post['is_on_sale']."',
						seo_keyword ='".$post['seo_keyword']."',
						seo_description ='".$post['seo_description']."',
						type_id ='".$post['type_id']."',
						sort_num ='".$post['sort_num']."',
						goods_desc ='".$post['goods_desc']."',
						addtime ='".time()."'";
		//商品logo
		if($_FILES['logo']['error']==0){
			$sql .=",logo ='".$file_name."',sm_logo ='".$thumb."'";
		}
		//是否促销
		if(isset($post['is_promote'])){
			$sql .=	",is_promote ='".$post['is_promote']."',
						promote_price ='".$post['promote_price']."',
						promote_start_time ='".strtotime($post['promote_start_time'])."',
						promote_end_time ='".strtotime($post['promote_end_time'])."'";
		}else{
			$sql .=	",is_promote ='0',promote_price ='0.00',promote_start_time ='',promote_end_time =''";
		}
		//是否添加商品类型
		if(empty($post['type_id'])){
			$sql .=",type_id ='0'";
		}else
			$sql .=",type_id =".$post['type_id'];
		$sql .=" where id =".$post['id'];
		$this->db->query($sql);
		//添加类型属性,先删除再添加
		$sql ="delete from php34_goods_attr where goods_id =".$post['id'];
		$this->db->query($sql);
		if(isset($post['ga'])){
			$sql ="insert into php34_goods_attr values";
			foreach($post['ga'] as $k=>$v){
				if(!empty($v)){
					foreach($v as $kv=>$vv){
						if(!empty($vv)){
							$attr_price=isset($post['attr_price'][$k])?$post['attr_price'][$k][$kv]:0;
							$sql .="('','".$post['id']."','$k','$vv','$attr_price'),";
						}
					}
				}
			}
			if(strpos($sql,'('))
				$this->db->query(trim($sql,','));
		}

		//添加扩展分类
		//先删除再添加
		$sql ="delete from php34_goods_cat where goods_id = ".$post['id'];
		$this->db->query($sql);
		$sql ="insert into php34_goods_cat values";
		foreach($post['ext_cat_id'] as $k=>$v){
			if(!empty($v))
				$sql .="(".$post['id'].",$v),";
		}
		if(strpos($sql,'('))
			$this->db->query(trim($sql,','));


		//会员价格
		//先删除再添加
		$sql = "delete from php34_member_price where goods_id = ".$post['id'];
		$this->db->query($sql);
		$sql ="insert into php34_member_price values";
		foreach($post['mp'] as $k=>$v){
			if(!empty($v)&&is_numeric($v))
				$sql .="(".$post['id'].",$k,$v),";
		}
		if(strpos($sql,'('))
			$this->db->query(trim($sql,','));

		//添加商品图片
		
		if(isset($_FILES['pics'])&&$_FILES['pics']['error'][0]==0){
			require_once LIB_PATH."Upload.class.php";
			$upload = new upload();
			$file_name = $upload->multiUp($_FILES['pics']);
			//var_dump($file_name);
			//生成缩略图
			require_once LIB_PATH."Image.class.php";
			$img = new image();
			foreach($file_name as $k=>$v)
			$thumb[$k] = $img->thumbnail(UPLOAD.$v,200,200,UPLOAD.str_replace(strrchr($v,'/'),'',$v).'/');
			//var_dump($thumb);

			$sql ="insert into php34_goods_pics values";
			foreach($file_name as $k=>$v){
				if(!empty($v))
					$sql .="('','$v','".$thumb[$k]."','".$post['id']."'),";
			}
			if(strpos($sql,'('))
				$this->db->query(trim($sql,','));
		}
	}


	/********************************************前台方法***************************************************/


	// 获取当前正处在促销期间的商品
	public function getPromoteGoods($limit = 5)
	{
		$now = time();
		$sql = "select id,goods_name,promote_price,sm_logo from {$this->table} where is_on_sale=1 and is_delete=0 and is_promote=1 and promote_start_time<$now and promote_end_time>$now order by sort_num ASC limit $limit ";
		return $this->Mquery($sql);
	}
	// 最新的
	public function getNew($limit = 5)
	{
		$sql = "select id,goods_name,shop_price,sm_logo from {$this->table} where is_on_sale=1 and is_delete=0 and is_new=1 order by sort_num ASC limit $limit";
		return $this->Mquery($sql);
	}
	public function getHot($limit = 5)
	{
		$sql = "select id,goods_name,shop_price,sm_logo from {$this->table} where is_on_sale=1 and is_delete=0 and is_hot=1 order by sort_num ASC limit $limit";
		return $this->Mquery($sql);
	}
	public function getBest($limit = 5)
	{
		$sql = "select id,goods_name,shop_price,sm_logo from {$this->table} where is_on_sale=1 and is_delete=0 and is_best=1 order by sort_num ASC limit $limit";
		return $this->Mquery($sql);
	}

	//得到商品图片
	public function getGoodPic($id){
		$sql = "select * from php34_goods_pics where goods_id = $id";
		return $this->Mquery($sql);
	}

	//得到商品可选属性
	public function getGoodAttr($id){
		$sql = "select a.*,b.attr_name,b.attr_option_values,b.type_id from (select * from php34_goods_attr where goods_id = $id) a left join php34_attribute b on a.attr_id = b.id where b.attr_type =1";
		return $this->Mquery($sql);
	}

	//得到商品唯一属性
	public function getGoodAttr1($id){
		$sql = "select a.*,b.attr_name ,b.attr_option_values,b.type_id from (select * from php34_goods_attr where goods_id = $id) a left join php34_attribute b on a.attr_id = b.id where b.attr_type =0";
		return $this->Mquery($sql);
	}
	// 计算会员价格
	public function getMemberPrice($goodsId)
	{
		$now = time();
		// 先判断是否有促销价格
		$price = $this->selectByPk($goodsId);
		if($price['is_promote'] == 1 && ($price['promote_start_time'] < $now && $price['promote_end_time'] > $now))
		{
			return $price['promote_price'];
		}
		// 如果会员没登录直接使用本店价
		if(!isset($_SESSION)){
			session_start();
		}
		if(!isset($_SESSION['home']))
			return $price['shop_price'];
		$memberId = $_SESSION['home'];
		// 计算会员价格
		$sql = "select * from php34_member_price where goods_id =$goodsId and level_id =".$_SESSION['home']['level'];
		$mprice = $this->db->getRow($sql);		
		// 如果有会员价格就直接使用会员价格
		if($mprice)
			return $mprice['price'];
		else 
			// 如果没有设置会员价格，就按这个级别的折扣率来算
			return $_SESSION['home']['rate'] * $price['shop_price'];
	}
	/*
	$attr 属性组成的字符串
	返回属性价格
	*/
	public function getAttrPrice($goods_id,$attr){
	}
	/**
	 * 转化商品属性ID为商品属性字符串
	 *
	 */
	public function convertGoodsAttrIdToGoodsAttrStr($gaid)
	{
		if($gaid)
		{
			$sql = 'SELECT GROUP_CONCAT( CONCAT( b.attr_name,  ":", a.attr_value ) SEPARATOR  "<br />" ) gastr FROM php34_goods_attr a LEFT JOIN php34_attribute b ON a.attr_id = b.id WHERE a.id IN ('.$gaid.')';
			$ret = $this->Mquery($sql);
			return $ret[0]['gastr'];
		}
		else 
			return '';
	}
	// 前台商品搜索功能使用的方法
	public function search_goods()
	{
		/******************* 搜索 ********************/
		return $data;
	}



}
?>