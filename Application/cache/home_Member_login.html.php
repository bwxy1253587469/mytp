<?php/*声明assign函数渲染的变量*/?><?php $page_keywords=$this->tpl_var["page_keywords"];$page_description=$this->tpl_var["page_description"];$page_title=$this->tpl_var["page_title"];$show_nav=$this->tpl_var["show_nav"];$page_css=$this->tpl_var["page_css"];$page_js=$this->tpl_var["page_js"];?><?php include showUrl("index/head"); ?>
<!-- 登录主体部分start -->
	<div class="login w990 bc mt10">
		<div class="login_hd">
			<h2>用户登录<?php if(isset($_SESSION['openid'])) echo '请先手动登录一次，把QQ绑定到你登录的账号上'; ?></h2>
			<b></b>
		</div>
		<div class="login_bd">
			<div class="login_form fl">
				<form action="" method="post">
					<ul>
						<li>
							<label for="">Email：</label>
							<input type="text" class="txt" name="email" />
						</li>
						<li>
							<label for="">密码：</label>
							<input type="password" class="txt" name="password" />
						</li>
						<li>
							<label for="">验证码：</label>
							<input class="txt" type="text"  name="chkcode" /><br />
						</li>
						<li>
							<label for="">&nbsp;</label>
							<img onclick="this.src='<?php echo U('captcha'); ?>?'+Math.random();" style="cursor:pointer;" src="<?php echo U('captcha'); ?>" alt="" />
							<span>看不清？<a onclick="$(this).parent().prev('img').trigger('click');" href="javascript:void(0);">换一张</a></span>
						</li>
						<li>
							<label for="">&nbsp;</label>
							<input type="submit" value="" class="login_btn" />
						</li>
					</ul>
				</form>

				<div class="coagent mt15">
					<dl>
						<dt>使用合作网站登录商城：</dt>
						<dd class="qq"><a href="javascript:void(0);" onclick="toQzoneLogin();"><span></span>QQ</a></dd>
						<dd class="weibo"><a href=""><span></span>新浪微博</a></dd>
						<dd class="yi"><a href=""><span></span>网易</a></dd>
						<dd class="renren"><a href=""><span></span>人人</a></dd>
						<dd class="qihu"><a href=""><span></span>奇虎360</a></dd>
						<dd class=""><a href=""><span></span>百度</a></dd>
						<dd class="douban"><a href=""><span></span>豆瓣</a></dd>
					</dl>
				</div>
			</div>
			
			<div class="guide fl">
				<h3>还不是商城用户</h3>
				<p>现在免费注册成为商城用户，便能立刻享受便宜又放心的购物乐趣，心动不如行动，赶紧加入吧!</p>

				<a href="regist.html" class="reg_btn">免费注册 >></a>
			</div>

		</div>
	</div>
	<!-- 登录主体部分end -->
<script>
function toQzoneLogin()
{
	window.open("/qq/oauth/qq_login.php","TencentLogin","width=450,height=320,menubar=0,scrollbars=1, resizable=1,status=1,titlebar=0,toolbar=0,location=1");
} 
</script>
<?php include showUrl("index/foot"); ?>