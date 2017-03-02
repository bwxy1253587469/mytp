<?php


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

function f1(){
	echo "f1 helper";
}
/*
$arr=array("Is your name O'reilly?" ,"Is your name O'reilly?" ,"Is your name O'reilly?");
var_dump($arr);
var_dump(deepslashes($arr));*/
?>