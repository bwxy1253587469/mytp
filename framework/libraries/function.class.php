<?php
//路由函数
/*
$url mvc 参数 admin/index/index
$get get参数，字符串，如id=1，写成id = 1
*/
function U($url,$get=''){
	$get == ''?:$get ='?'.str_replace(' ','',$get);
	//生成模板替换后的文件
	$list=explode('/',$url);
	$ret = '';
	switch(count($list)){
		case 1:
			$ret = HTTP_ROOT.PLATFORM.'/'.CONTROLLER.'/'.$list[0].'.html'.$get;
			break;
		case 2:
			$ret = HTTP_ROOT.PLATFORM.'/'.$list[0].'/'.$list[1].'.html'.$get;
			break;
		case 3:
			$ret = HTTP_ROOT.$list[0].'/'.$list[1].'/'.$list[2].'.html'.$get;
			break;
	}
	return $ret;
}

//include模板替换函数,只支持同一p平台下
function showUrl($url){
	$list=explode('/',$url);
	$ret = '';
	switch(count($list)){
		case 1:
			//确定类名和方法名
			$controller_name=CONTROLLER."Controller";
			$action_name=$list[0]."Action";
			//实例化控制器，然后调用相应的方法
			$controller=new $controller_name;
			$ret = $controller->compile($list[0].".html");
			break;
		case 2:
			//确定类名和方法名
			$controller_name=ucfirst($list[0])."Controller";
			$action_name=$list[1]."Action";
			//实例化控制器，然后调用相应的方法
			$controller=new $controller_name;
			$ret = $controller->compile($list[1].".html");
			break;
	}
	return $ret;
}
/****************************防止sql,js,html注入******************************************/
//批量转义
function deepslashes($data){
	//判断数据形式，有单个数据，数组，二维数组，空。
	if(empty($data))
		return $data;
	//简洁写法
	//return is_array($data)?array_map(deepslashes,$data):addslashes($data);
	//复杂写法

	if(is_array($data)){
		foreach($data as $k =>$vd){
			$data[$k]=deepslashes($vd);
		}
	}else{
		$data=addslashes($data);
	}
	return $data;


}

//批量实体转义,html,js等特殊字符
function deepspecialchars($data){
	if (empty($data)){
		return $data;
	}
	return is_array($data) ? array_map('deepspecialchars', $data) : htmlspecialchars($data);
}

function proFrSql($data){
	return deepslashes(deepspecialchars($data));
	//return $data;
}
?>