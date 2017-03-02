<?php/*声明assign函数渲染的变量*/?><?php $data=$this->tpl_var["data"];$priData=$this->tpl_var["priData"];$titleURL=$this->tpl_var["titleURL"];$title=$this->tpl_var["title"];?><?php include showUrl("Index/header"); ?>
<div class="main-div">
    <form name="main_form" method="POST" action="<?php echo SELF_PATH; ?>" enctype="multipart/form-data" >
    	<input type="hidden" name="id" value="<?php echo $data['id']; ?>" />
        <table cellspacing="1" cellpadding="3" width="100%">
            <tr>
                <td class="label">角色名称：</td>
                <td>
                    <input  type="text" name="role_name" value="<?php echo $data['role_name']; ?>" />
                </td>
            </tr>
            <tr>
                <td class="label">权限列表：</td>
                <td>
                	<?php foreach ($priData as $k => $v): 
                		if(strpos(','.$data['pri_id'].',', ','.$v['id'].',') !== FALSE)
                			$check = 'checked="checked"';
                		else 
                			$check = '';
                	?>
                    <?php echo str_repeat('-', $v['level'] * 8); ?>
                	<input <?php echo $check; ?> level="<?php echo $v["level"]; ?>" type="checkbox" name="pri_id[]" value="<?php echo $v["id"]; ?>" /><?php echo $v["pri_name"]; ?> <br />
                    <?php endforeach; ?>
                </td>
            </tr>
            <tr>
                <td colspan="99" align="center">
                    <input type="submit" class="button" value=" 确定 " />
                    <input type="reset" class="button" value=" 重置 " />
                </td>
            </tr>
        </table>
    </form>
</div>
<script>
// 为所有的选择框绑定点击事件
$(":checkbox").click(function(){
	// 先取出当前权限的level值是多少
	var cur_level = $(this).attr("level");
	// 判断是选中还是取消
	if($(this).attr("checked"))
	{
		var tmplevel = cur_level; // 给一个临时的变量后面要进行减操作
		// 先取出这个复选框所有前面的复选框
		var allprev = $(this).prevAll(":checkbox");
		// 循环每一个前面的复选框判断是不是上级的
		$(allprev).each(function(k,v){
			// 判断是不是上级的权限
			if($(v).attr("level") < tmplevel)
			{
				tmplevel--; // 再向上提一级
				$(v).attr("checked", "checked");
			}
		});
		// 所有子权限也选中
		// 先取出这个复选框所有前面的复选框
		var allprev = $(this).nextAll(":checkbox");
		// 循环每一个前面的复选框判断是不是上级的
		$(allprev).each(function(k,v){
			// 判断是不是上级的权限
			if($(v).attr("level") > cur_level)
				$(v).attr("checked", "checked");
			else
				return false;   // 遇到一个平级的权限就停止循环后面的不用再判断了
		});
	}
	else
	{
		// 先取出这个复选框所有前面的复选框
		var allprev = $(this).nextAll(":checkbox");
		// 循环每一个前面的复选框判断是不是上级的
		$(allprev).each(function(k,v){
			// 判断是不是上级的权限
			if($(v).attr("level") > cur_level)
				$(v).removeAttr("checked");
			else
				return false;   // 遇到一个平级的权限就停止循环后面的不用再判断了
		});
	}
});
</script>
<?php include showUrl("Index/footer"); ?>