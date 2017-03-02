<?php
class MySmarty{
	private $template_dir;//模板文件目录
	//给类声明属性，存储外部变量信息。
	public $tpl_var=array();
	public function __construct(){
		 $this->template_dir = VIEW_PATH.strtolower(PLATFORM).DS.str_replace('Controller','',get_class($this)).DS;
		 //echo $this->template_dir;
	}
	//该方法将外部变量的值赋给内部属性
	public function assign($k,$v){
		$this->tpl_var[$k]=$v;
	}
	public function display($tpl = null){
		if($tpl === null){
			$file = $this->compile(ACTION.'.html');
		}else
			$file=$this->compile($tpl.'.html');
		require $file;
	}
	public function compile($tpl){
		//缓存
		$com_file=CACHE_PATH.strtolower(PLATFORM).'_'.CONTROLLER.'_'.$tpl.".php";
		$tpl_file=$this->template_dir.$tpl;
		/*
		if(file_exists($com_file) && filemtime($com_file)>filemtime($tpl_file)){
			return $com_file;
		}
		else{*/
			$cont= file_get_contents($tpl_file);
			//echo $cont;

			//替换{$title} -> <?php echo $title;
			$vle=$GLOBALS['config']['left_delim'].'\$';//{$
			$vl=$GLOBALS['config']['left_delim'];//{
			$vr=$GLOBALS['config']['right_delim'];//}
			$pattern  = array(
				"#$vle([a-zA-Z0-9_]+?)\s*$vr#",//非数组变量替换
				"#$vle([a-zA-Z0-9_]+?)(\[.+?)\s*$vr#",//数组变量替换
				"#$vle([a-zA-Z0-9_]+?).([a-zA-Z0-9_]+?)\s*$vr#",//数组变量替换
				"#__([A-Z]+)__#",//__PUBLIC__
				"#$vl\s*include:(.+?)\s*$vr#i",
				"#$vl\s*if\s+(.+?)\s*$vr#i",
				"#$vl\s*else\s*$vr#i",
				"#$vl\s*elseif\s+(.+?)\s*$vr#i",
				"#$vl\s*endif\s*$vr#i",
				"#$vl\s*/if\s*$vr#i",
				"#$vl\s*foreach\s+([a-zA-Z]+?)(\[.+?\])?:(.+?)\s*$vr#i",
				"#$vl\s*endforeach\s*$vr#i",
				"#$vl\s*/foreach\s*$vr#i",
				"#$vl\s*U\((.+)\)$vr#",//U函数替换规则
				);
			$replacement  =array(
				'<?php echo $\\1; ?>',
				'<?php echo $\\1\\2; ?>',
				'<?php echo $\\1["\\2"]; ?>',
				'<?php echo \\1_PATH; ?>',
				'<?php include showUrl("\\1"); ?>',
				"<?php if(\\1): ?>",
				"<?php else: ?>",
				"<?php elseif(\\1): ?>",
				"<?php endif; ?>",
				"<?php endif; ?>",
				'<?php if(count($\\1\\2)>0):\$autoindex=0;foreach($\\1\\2 as $\\3):\$autoindex++; $\\3 =$\\3; ?>',
				"<?php endforeach;endif; ?>",
				"<?php endforeach;endif; ?>",
				"<?php echo U(\\1);?>",
				);

			$cont =preg_replace($pattern ,$replacement,$cont);

			//声明assign函数的变量
			$str = "<?php ";
			foreach($this->tpl_var as $key=>$value){
				$str .= "$".$key.'=$this->tpl_var["'.$key.'"];';
			}
			$str .="?>";
			$cont = "<?php/*声明assign函数渲染的变量*/?>".$str.$cont;

			file_put_contents($com_file,$cont);
			return $com_file;
		//}

	}
}

?>