<?php/*声明assign函数渲染的变量*/?><?php $data=$this->tpl_var["data"];$titleURL=$this->tpl_var["titleURL"];$title=$this->tpl_var["title"];?><?php include showUrl("Index/header"); ?>
<!-- 搜索 -->
<div class="form-div search_form_div">
    <form method="GET" name="search_form">
		<p>
			品牌名称：
	   		<input type="text" name="brand_name" size="30" value="" />
		</p>
		<p><input type="submit" value=" 搜索 " class="button" /></p>
    </form>
</div>
<!-- 列表 -->
<div class="list-div" id="listDiv">
	<table cellpadding="3" cellspacing="1">
    	<tr>
            <th >品牌名称</th>
            <th >品牌网站地址</th>
            <th >品牌logo</th>
			<th width="60">操作</th>
        </tr>
		<?php if(count($data)>0):$autoindex=0;foreach($data as $v):$autoindex++; $v =$v; ?>            
			<tr class="tron">
				<td><?php echo $v['brand_name']; ?></td>
				<td><?php echo $v['site_url']; ?></td>
				<td><img src ="<?php echo UPLOAD_PATH;?>/<?php echo $v['logo']; ?>" width ="50px"></td>
		        <td align="center">
		        	<a href="<?php echo U('edit','id='.$v['id']);?>" title="编辑">编辑</a> |
	                <a href="<?php echo U('del','id='.$v['id']);?>" onclick="return confirm('确定要删除吗？');" title="移除">移除</a> 
		        </td>
	        </tr>
        <?php endforeach;endif; ?>
	</table>
</div>
<script>
</script>
<?php include showUrl("Index/footer"); ?>