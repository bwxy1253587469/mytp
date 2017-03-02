<?php/*声明assign函数渲染的变量*/?><?php $attr=$this->tpl_var["attr"];$attrvalue=$this->tpl_var["attrvalue"];$num=$this->tpl_var["num"];$titleURL=$this->tpl_var["titleURL"];$title=$this->tpl_var["title"];?><?php include showUrl("Index/header"); ?>
<!-- 列表 -->
<div class="list-div" id="listDiv">
<form action="" method="POST">
	<table cellpadding="3" cellspacing="1">
    	<tr>
    		<?php foreach ($attr as $k => $v): ?>
    		<th><?php echo $v['attr_name']; ?></th>
    		<?php endforeach; ?>
            <th width="80">库存</th>
			<th width="30">操作</th>
        </tr>
				<input type="hidden" value="<?php echo $_GET['id']; ?>" name="goods_id"/>
				<?php if(!empty($num))foreach ($num as $k => $v){
				$goods_attr_id = explode(',',$v['goods_attr_id']);
				?>
		        <tr>
		        	<?php foreach ($goods_attr_id as $kg => $vg):?>
						<?php foreach ($attrvalue as $ka => $va):?>
						<?php if($va['id'] == $vg || empty($vg)){
						echo '<td>
							<select name="goods_attr_id['.$k.'][]">
							<option value="">请选择</option>';
							for($i=0;$i<count($attrvalue);$i++){
								$va['id']!=$attrvalue[$i]['id']?$select ='':$select ="selected=selected";
								if($va['attr_id']==$attrvalue[$i]['attr_id'])
									echo "<option $select  value=".$attrvalue[$i]['id'].">".$attrvalue[$i]['attr_value']."</option>";
							}
						}
						?>
						<?php endforeach; ?>
		    		</select>
		    		</td>
		    		<?php endforeach;?>
		            <td><input type="text" size="8" name="goods_number[<?php echo $k;?>]" value="<?php echo $v['goods_number']; ?>" /></td>
					<td><input type="button" onclick="addnew(this);" value="<?php echo $k==0?'+':'-';?>" /></td>
		        </tr>
				<?php }
					else {foreach ($attr as $k => $v): ?>
			<td><select name="goods_attr_id[<?php echo $k;?>][]"><option value="">请选择</option>
			<?php foreach($attrvalue as $ka => $va){
					if($v['id'] == $va['attr_id'])
						echo "<option value=".$va['id'].">".$va['attr_value']."</option>";
				}
			?>
    		<?php endforeach; ?>
			<td><input type="text" size="8" name="goods_number[]" value="0" /></td>
			<td><input type="button" onclick="addnew(this);" value="+" /></td>
			<?php }?>
	    	
        <tr id="btn"><td colspan="<?php echo count($attr)+2; ?>" align="center"><input type="submit" value="提交" /></td></tr>
	</table>
</form>
</div>
<script>
function addnew(btn)
{
	// 先获取点击的按钮所在的tr
	var tr = $(btn).parent().parent();
	if($(btn).val() == "+")
	{
		// 克隆tr
		var newtr = tr.clone();
		// 把+变-
		newtr.find(":button").val("-");
		// 把到btn所在的TR前面
		$("#btn").before(newtr);
	}
	else
		tr.remove();
}
</script>
<?php include showUrl("Index/footer"); ?>