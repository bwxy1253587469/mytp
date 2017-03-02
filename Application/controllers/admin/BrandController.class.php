<?php
class BrandController extends BaseController 
{
    public function lstAction()
    {
    	$model = new BrandModel();
    	$data = $model->getBrands();
    	$this->assign('data',$data);
		$this->assign('titleURL',U('add'));
		$this->assign('title',array("品牌列表","添加品牌"));
		$this->display();
    }

	public function addAction(){
		if(isset($_POST['brand_name'])&&!empty($_POST['brand_name'])){
			$model = new BrandModel();
    		if($model->addBrand())
				$this->jump(U('lst'),"添加成功");
			else
				$this->jump(U('lst'),"添加失败");
		}
		$this->assign('titleURL',U('lst'));
		$this->assign('title',array("添加品牌","品牌列表"));
		$this->display();
	}


	public function delAction(){
		if(isset($_GET['id'])&&!empty($_GET['id'])){
			$model = new BrandModel();
    		if($model->delBrand($_GET['id']))
				$this->jump(U('lst'),"删除成功");
			else
				$this->jump(U('lst'),"删除失败");
		}
	}


	public function editAction(){
		$model = new BrandModel();
		if(isset($_POST['id'])&&!empty($_POST['id'])){
    		if($model->editBrand())
				$this->jump(U('lst'),"修改成功");
			else
				$this->jump(U('lst'),"修改失败");
		}
		$data = $model->getOneBrand($_GET['id']);
		$this->assign('data',$data);
		$this->assign('titleURL',U('lst'));
		$this->assign('title',array("修改品牌","品牌列表"));
		$this->display();
	}



}