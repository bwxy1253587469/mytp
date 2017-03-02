<?php/*声明assign函数渲染的变量*/?><?php $roleData=$this->tpl_var["roleData"];$titleURL=$this->tpl_var["titleURL"];$title=$this->tpl_var["title"];?><?php include showUrl("Index/header"); ?>
<div class="main-div">
    <form name="main_form" method="POST" action="<?php echo SELF_PATH; ?>" enctype="multipart/form-data">
        <table cellspacing="1" cellpadding="3" width="100%">
        	<tr>
                <td class="label">所在角色：</td>
                <td>
                	<?php foreach ($roleData as $k => $v): ?>
                    <input type="checkbox" name="role_id[]" value="<?php echo $v["id"]; ?>" /> <?php echo $v["role_name"]; ?>
                    <?php endforeach; ?>
                </td>
            </tr>
            <tr>
                <td class="label">账号：</td>
                <td>
                    <input  type="text" name="username" value="" />
                </td>
            </tr>
            <tr>
                <td class="label">密码：</td>
                <td>
                    <input type="password" size="25" name="password" />
                </td>
            </tr>
            <tr>
                <td class="label">确认密码：</td>
                <td>
                    <input type="password" size="25" name="cpassword" />
                </td>
            </tr>
            <tr>
                <td class="label">是否启用</td>
                <td>
                	<input type="radio" name="is_use" value="1" checked="checked" />启用 
                	<input type="radio" name="is_use" value="0"  />禁用 
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