<?php/*声明assign函数渲染的变量*/?><?php $typeId=$this->tpl_var["typeId"];$typeData=$this->tpl_var["typeData"];$data=$this->tpl_var["data"];$page=$this->tpl_var["page"];$titleURL=$this->tpl_var["titleURL"];$title=$this->tpl_var["title"];?><?php include showUrl("Index/header"); ?>
<p>
按商品类型显示：<select name = "type_id">
	<option value="">选择类型</option>
	<?php foreach ($typeData as $k => $v): 
		if($v['id'] == $typeId)
			$select = 'selected="selected"';
		else 
			$select = '';
	?>
	<option <?php echo $select; ?> value="<?php echo $v["id"]; ?>"><?php echo $v["type_name"]; ?></option>
	<?php endforeach; ?>
</select>
</p>
<!-- 列表 -->
<div class="list-div" id="listDiv">
	<table cellpadding="3" cellspacing="1">
    	<tr>
            <th >属性名称</th>
            <th >属性的类型</th>
            <th >属性的可选值</th>
            <th >所在的类型的id</th>
			<th width="60">操作</th>
        </tr>
		<?php foreach ($data as $k => $v): ?>            
			<tr class="tron">
				<td><?php echo $v['attr_name']; ?></td>
				<td><?php echo $v['attr_type']; ?></td>
				<td><?php echo $v['attr_option_values']; ?></td>
				<td><?php echo $v['type_id']; ?></td>
		        <td align="center">
		        	<a href="<?php echo U('edit','id ='.$v['id'].'&type_id ='.$typeId);?>" title="编辑">编辑</a> |
	                <a href="<?php echo U('del','id ='.$v['id'].'&type_id ='.$typeId);?>" onclick="return confirm('确定要删除吗？');" title="移除">移除</a> 
		        </td>
	        </tr>
        <?php endforeach; ?> 
		<tr><td align="right" nowrap="true" colspan="11" height="30"><?php echo $page; ?></td></tr> 
	</table>
</div>
<script>
$("select[name='type_id']").change(function(){
	location.href="<?php echo U('Attribute/lst');?>?id="+this.value;
})
</script>
<?php include showUrl("Index/footer"); ?>