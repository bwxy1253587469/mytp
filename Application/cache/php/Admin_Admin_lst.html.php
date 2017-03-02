<?php/*声明assign函数渲染的变量*/?><?php $data=$this->tpl_var["data"];$titleURL=$this->tpl_var["titleURL"];$title=$this->tpl_var["title"];?><?php include showUrl("Index/header"); ?>
<!-- 搜索 -->
<div class="form-div search_form_div">
    <form method="GET" name="search_form">
		<p>
			账号：
	   		<input type="text" name="username" size="30" value="" />
		</p>
		<p>
			是否启用：
			<input type="radio" value="-1" name="is_use" checked="checked" /> 全部 
			<input type="radio" value="1" name="is_use" checked="checked" /> 启用 
			<input type="radio" value="0" name="is_use" checked="checked" /> 禁用 
		</p>
		<p><input type="submit" value=" 搜索 " class="button" /></p>
    </form>
</div>
<!-- 列表 -->
<div class="list-div" id="listDiv">
	<table cellpadding="3" cellspacing="1">
    	<tr>
            <th >账号</th>
            <th width="70">是否启用</th>
			<th width="60">操作</th>
        </tr>
		<?php if(count($data)>0):$autoindex=0;foreach($data as $v):$autoindex++; $v =$v; ?>            
			<tr class="tron">
				<td><?php echo $v['username']; ?></td>
				<td style="cursor:pointer;" admin_id="<?php echo $v['id']; ?>" class="is_use"><?php echo $v['is_use']==1?'启用':'禁用'; ?></td>
		        <td align="center">
		        	<a href="<?php echo U('edit','id ='.$v['id']);?>" title="编辑">编辑</a>
	                <a href="<?php echo U('del','id ='.$v['id']);?>" onclick="return confirm('确定要删除吗？');" title="移除" style="<?php if($v['id']==1) echo 'display:none';?>">移除</a> 
	                
		        </td>
	        </tr>
        <?php endforeach;endif; ?>
	</table>
</div>
<script>
// 为启用的td加一个事件
$(".is_use").click(function(){
	// 先获取点击的记录的ID
	var id = $(this).attr("admin_id");
	if(id == 1)
	{
		alert("超级管理员不能修改！");
		return false;
	}
	var _this = $(this);
	$.ajax({
		type : "GET",
		// 默认U函数生成的地址是带.html后缀的：/index.php/Admin/Admin/ajaxUpdateIsuse.html/id/3，这样请求这个地址会报错无法请求，所以需要让U生成的地址不要带.html后缀
		// 也就是说，如果在AJAX时使用了U函数并且后面还要再传参数就需要设置第三个参数为FALSE不生成.html后缀
		url : "",
		success : function(data)
		{
			if(data == 0)
				_this.html("禁用");
			else
				_this.html("启用");
		}
	});
});
</script>
<?php include showUrl("Index/footer"); ?>