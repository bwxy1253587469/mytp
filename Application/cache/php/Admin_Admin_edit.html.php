<?php/*声明assign函数渲染的变量*/?><?php $rid=$this->tpl_var["rid"];$roleData=$this->tpl_var["roleData"];$data=$this->tpl_var["data"];$titleURL=$this->tpl_var["titleURL"];$title=$this->tpl_var["title"];?><?php include showUrl("Index/header"); ?>
<div class="main-div">
    <form name="main_form" method="POST" action="<?php echo SELF_PATH; ?>" enctype="multipart/form-data" >
    	<input type="hidden" name="id" value="<?php echo $data['id']; ?>" />
        <table cellspacing="1" cellpadding="3" width="100%">
        	<?php if($data['id'] > 1): ?>
        	<tr>
                <td class="label">所在角色：</td>
                <td>
                	<?php foreach ($roleData as $k => $v): 
                		if(strpos(','.$rid.',', ','.$v['id'].',') !== FALSE)
                			$check = 'checked="checked"';
                		else 	
                			$check = '';
                	?>
                    <input <?php echo $check; ?> type="checkbox" name="role_id[]" value="<?php echo $v["id"]; ?>" /> <?php echo $v["role_name"]; ?>
                    <?php endforeach; ?>
                </td>
            </tr>
            <?php endif; ?>
            <tr>
                <td class="label">账号：</td>
                <td>
                    <input  type="text" name="username" value="<?php echo $data['username']; ?>" />
                </td>
            </tr>
            <tr>
                <td class="label">密码：</td>
                <td>
                    <input type="password" size="25" name="password" />
                    留空代表不修改密码
                </td>
            </tr>
            <tr>
                <td class="label">确认密码：</td>
                <td>
                    <input type="password" size="25" name="cpassword" />
                </td>
            </tr>
            <?php if($data['id'] > 1): ?>
            <tr>
                <td class="label">是否启用</td>
                <td>
                	<input type="radio" name="is_use" value="1" <?php if($data['is_use'] == '1') echo 'checked="checked"'; ?> />启用 
                	<input type="radio" name="is_use" value="0" <?php if($data['is_use'] == '0') echo 'checked="checked"'; ?> />禁用 
                </td>
            </tr>
            <?php endif; ?>
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