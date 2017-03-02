<?php
	//核心启动类
	class Framework{
		//启动项目
		public static function run(){
			ob_start();//开启ob缓存
            //var_dump($_SERVER);
			self::init();
			//判断ob缓存的静态文件是否过期
			//缓存文件路径名称
            $fname=CACHE_PATH.PLATFORM."_".CONTROLLER."_".ACTION.".html";
			//memcache缓存的使用
            /*
			self::sessionMemcached();
			self::getMemcached();
            */
			if(!file_exists($fname)||filemtime($fname)+$GLOBALS['config']['file_time']<time()){
				self::autoload();
				self::router();
				self::cache();//生成静态缓存文件
			}else{
				require $fname;
			}
		}
		//初始化方法
		public static function init(){
			//自定义预定义变量
			//定义路径，获取当前路径getcwd(),本地路径
			define("DS",DIRECTORY_SEPARATOR);
            define("ROOT",getcwd().DS);
            //define("ROOT",$_SERVER['DOCUMENT_ROOT'].DS);
			//http请求路径
			define("HTTP",'http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'].'?');
			define("HTTP_ROOT",str_replace('index.php?','',HTTP));
			define("PUBLIC_PATH",HTTP_ROOT.'Public');
			define("APP_PATH",ROOT."Application".DS);
			define("FRAMEWORK_PATH",ROOT."framework".DS);
			//define("PUBLIC_PATH",ROOT."public".DS);
			define("CONTROLLER_PATH",APP_PATH."controllers".DS);
			define("MODEL_PATH",APP_PATH."models".DS);
			define("VIEW_PATH",APP_PATH."views".DS);
			define("CONFIG_PATH",APP_PATH."config".DS);
			define("CORE_PATH",FRAMEWORK_PATH."core".DS);
			define("DB_PATH",FRAMEWORK_PATH."database".DS);
			define("HELPER_PATH",FRAMEWORK_PATH."helpers".DS);
			define("LIB_PATH",FRAMEWORK_PATH."libraries".DS);
			//缓存目录
            define("CACHE_PATH",APP_PATH."cache".DS);
            //define("CACHE_PATH",SAE_TMP_PATH .DS);
			//中间替换文件目录
			//define("CACHE_PHP_PATH",SAE_TMP_PATH .DS);
			define("SELF_PATH",isset($_SERVER['REDIRECT_URL'])?$_SERVER['REDIRECT_URL']:'');

			//解析URL参数,为了能够使用get传参
			//var_dump($_SERVER);
			if(isset($_SERVER [ 'PATH_INFO' ]))
			$url = explode('/',$_SERVER [ 'PATH_INFO' ]);
			define("PLATFORM",isset($url[1])? $url[1]:'home');
			define("CONTROLLER",isset($url[2])? ucfirst($url[2]):'Index');
			define("ACTION",isset($url[3])? $url[3]:'index');
			unset($url);

			//当前controller，view路径
			define("CUR_CONTROLLER_PATH",CONTROLLER_PATH.strtolower(PLATFORM).DS);
			define("CUR_VIEW_PATH",VIEW_PATH.PLATFORM.DS.CONTROLLER.DS);

			//手动加载核心类
			require_once CORE_PATH."MySmarty.class.php";
			require_once CORE_PATH."Controller.class.php";
			require_once CORE_PATH."Model.class.php";
			require_once DB_PATH."Mysql.class.php";
			//手动加载自定义函数
			include_once LIB_PATH."function.class.php";
			$GLOBALS['config'] = include CONFIG_PATH."config.php";
			//文件上传http路径
			define("UPLOAD_PATH",HTTP_ROOT.$GLOBALS['config']['file_upload']);
			//文件上传本地路径
			define("UPLOAD",ROOT.$GLOBALS['config']['file_upload']);
		}

		//路由方法
		public static function router(){
			//确定类名和方法名
			$controller_name=CONTROLLER."Controller";
			$action_name=ACTION."Action";
			//实例化控制器，然后调用相应的方法
			$controller=new $controller_name;
			//method_exists($controller,$action_name)?$controller->$action_name():$controller->showHtml();
			$controller->$action_name();
		}

		//注册自动加载方法
		public static function autoload(){
			spl_autoload_register(array(__CLASS__,"load"));
		}

		//加载方法
		public static  function load($classname){
			//var_dump(CUR_CONTROLLER_PATH);
			//只负责加载application下面的控制器类，模型类。如GoodsController，GoodsModel.
			if(substr($classname,-10)=='Controller'){
				require CUR_CONTROLLER_PATH.ucfirst($classname).".class.php";
			}elseif(substr($classname,-5)=='Model'){
				require MODEL_PATH.ucfirst($classname).".class.php";
			}else{
				//暂时没有
			}
		}

		//缓存文件处理
		public static function cache(){
			//缓存文件路径名称
            $fname=CACHE_PATH.PLATFORM."_".CONTROLLER."_".ACTION.".html";
			$content = ob_get_contents();          //取得php页面输出的全部内容
            //self::addMemcached($content);
			$fp = fopen($fname, "w");  //创建一个文件，并打开，准备写入   
			fwrite($fp, $content);                 //把php页面的内容全部写入output00001.html，然后…… 
			fclose($fp);
		}


		/***************************memcache缓存函数*********************************************/
		//session入memcache
		public static function sessionMemcached(){
			# memcache扩展定义好的memcache，session存储处理器
			ini_set('session.save_handler', 'memcache');
			# 所使用的memcached服务器信息
			ini_set('session.save_path', 'tcp://127.0.0.1:11211');
			// ini_set('session.save_path', 'tcp://127.0.0.1:11211;tcp://host2:port2;tcp://host3:port3');
		}
		public static function getMemcached(){
			if(!strcasecmp(PLATFORM,'home')&& !strcasecmp(CONTROLLER,'index') && !strcasecmp(ACTION,'index')){
			$memcache = new Memcache();
			$host = '127.0.0.1';
			$port = '11211';
			$memcache->connect($host, $port);
				if($str = $memcache->get(PLATFORM."_".CONTROLLER."_".ACTION)){
				echo $str;
				$memcache->close();
				echo "这是memcache缓存";
				exit;
				}
			}
		}
		public static function addMemcached($str){
			if(!strcasecmp(PLATFORM,'home')&& !strcasecmp(CONTROLLER,'index') && !strcasecmp(ACTION,'index')){
				$memcache = new Memcache();
				$host = '127.0.0.1';
				$port = '11211';
				$memcache->connect($host, $port);
				$memcache->set(PLATFORM."_".CONTROLLER."_".ACTION,$str,0,$GLOBALS['config']['file_time']);
				$memcache->close();
			}
		}
		/***************************memcache缓存函数*********************************************/





	}
?>