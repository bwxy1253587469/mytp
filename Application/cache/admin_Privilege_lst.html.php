<?php/*声明assign函数渲染的变量*/?><?php $data=$this->tpl_var["data"];$titleURL=$this->tpl_var["titleURL"];$title=$this->tpl_var["title"];?><?php include showUrl("index/header"); ?>
<!-- 列表 -->
<div class="list-div" id="listDiv">
	<table cellpadding="3" cellspacing="1">
    	<tr>
            <th >权限名称</th>
            <th >模块名称</th>
            <th >控制器名称</th>
            <th >方法名称</th>
            <th >上级权限的ID，0：代表顶级权限</th>
			<th width="60">操作</th>
        </tr>
		<?php if(count($data)>0):$autoindex=0;foreach($data as $v):$autoindex++; $v =$v; ?>            
			<tr class="tron">
				<td><?php echo str_repeat('-', 8*$v['level']); ?><?php echo $v['pri_name']; ?></td>
				<td><?php echo $v['module_name']; ?></td>
				<td><?php echo $v['controller_name']; ?></td>
				<td><?php echo $v['action_name']; ?></td>
				<td><?php echo $v['parent_id']; ?></td>
		        <td align="center">
		        	<a href="<?php echo U('edit','id ='.$v['id']);?>" title="编辑">编辑</a> |
	                <a href="<?php echo U('del','id ='.$v['id']);?>" onclick="return confirm('确定要删除吗？');" title="移除">移除</a> 
		        </td>
	        </tr>
        <?php endforeach;endif; ?>
	</table>
</div>
<script>
</script>
<?php include showUrl("index/footer"); ?>