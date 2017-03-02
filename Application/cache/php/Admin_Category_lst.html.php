<?php/*声明assign函数渲染的变量*/?><?php $titleURL=$this->tpl_var["titleURL"];$title=$this->tpl_var["title"];$data=$this->tpl_var["data"];?><?php include showUrl("Index/header"); ?>
<!-- 列表 -->
<div class="list-div" id="listDiv">
	<table cellpadding="3" cellspacing="1">
    	<tr>
            <th >分类名称</th>
            <th >上级分类的ID，0：代表顶级</th>
			<th width="60">操作</th>
        </tr>
		<?php if(count($data)>0):$autoindex=0;foreach($data as $v):$autoindex++; $v =$v; ?>            
			<tr class="tron">
				<td><?php echo str_repeat('-', 8*$v['level']); ?><?php echo $v['cat_name']; ?></td>
				<td><?php echo $v['parent_id']; ?></td>
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