<?php/*声明assign函数渲染的变量*/?><?php $data=$this->tpl_var["data"];$titleURL=$this->tpl_var["titleURL"];$title=$this->tpl_var["title"];?><?php include showUrl("Index/header"); ?>
<!-- 列表 -->
<div class="list-div" id="listDiv">
	<table cellpadding="3" cellspacing="1">
    	<tr>
            <th >类型名称</th>
			<th width="120">操作</th>
        </tr>
		<?php if(count($data)>0):$autoindex=0;foreach($data as $v):$autoindex++; $v =$v; ?>            
			<tr class="tron">
				<td><?php echo $v['type_name']; ?></td>
		        <td align="center">
		        	<a href="<?php echo U('Attribute/lst','id ='.$v['id']);?>">属性列表</a> |
		        	<a href="<?php echo U('edit','id ='.$v['id']);?>" title="编辑">编辑</a> |
	                <a href="<?php echo U('del','id ='.$v['id']);?>" onclick="return confirm('确定要删除吗？');" title="移除">移除</a> 
		        </td>
	        </tr>
        <?php endforeach;endif; ?>
	</table>
</div>
<script>
</script>
<?php include showUrl("Index/footer"); ?>