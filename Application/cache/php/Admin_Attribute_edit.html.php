<?php/*声明assign函数渲染的变量*/?><?php $data=$this->tpl_var["data"];$typeId=$this->tpl_var["typeId"];$typeData=$this->tpl_var["typeData"];$titleURL=$this->tpl_var["titleURL"];$title=$this->tpl_var["title"];?><?php include showUrl("Index/header"); ?>
<div class="main-div">
    <form name="main_form" method="POST" action="" enctype="multipart/form-data" >
    	<input type="hidden" name="id" value="<?php echo $data['id']; ?>" />
        <table cellspacing="1" cellpadding="3" width="100%">
        	<tr>
                <td class="label">所在的类型的id：</td>
                <td>
                    <select name="type_id">
						<option value="">选择类型</option>
						<?php foreach ($typeData as $k => $v): 
							if($v['id'] == $data['type_id'])
								$select = 'selected="selected"';
							else 
								$select = '';
						?>
						<option <?php echo $select; ?> value="<?php echo $v["id"]; ?>"><?php echo $v["type_name"]; ?></option>
						<?php endforeach; ?>
					</select>
                </td>
            </tr>
            <tr>
                <td class="label">属性名称：</td>
                <td>
                    <input  type="text" name="attr_name" value="<?php echo $data['attr_name']; ?>" />
                </td>
            </tr>
            <tr>
                <td class="label">属性的类型：</td>
                <td>
                	<input type="radio" name="attr_type" value="0" <?php if($data['attr_type'] == '0') echo 'checked="checked"'; ?> />唯一 
                	<input type="radio" name="attr_type" value="1" <?php if($data['attr_type'] == '1') echo 'checked="checked"'; ?> />可选 
                </td>
            </tr>
            <tr>
                <td class="label">属性的可选值：</td>
                <td>
                	<textarea name="attr_option_values" rows="3" class="60"><?php echo $data['attr_option_values']; ?></textarea>
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
</script>
<?php include showUrl("Index/footer"); ?>