<?php/*声明assign函数渲染的变量*/?><?php $data=$this->tpl_var["data"];$titleURL=$this->tpl_var["titleURL"];$title=$this->tpl_var["title"];?><?php include showUrl("Index/header"); ?>
<div class="main-div">
    <form name="main_form" method="POST" action="" enctype="multipart/form-data" >
    	<input type="hidden" name="id" value="<?php echo $data['id']; ?>" />
		<input type="hidden" name="old_logo" value="<?php echo $data['logo']; ?>" />
        <table cellspacing="1" cellpadding="3" width="100%">
            <tr>
                <td class="label">品牌名称：</td>
                <td>
                    <input  type="text" name="brand_name" value="<?php echo $data['brand_name']; ?>" />
                </td>
            </tr>
            <tr>
                <td class="label">品牌网站地址：</td>
                <td>
                    <input  type="text" name="site_url" value="<?php echo $data['site_url']; ?>" />
                </td>
            </tr>
            <tr>
                <td class="label">品牌logo：</td>
                <td>
                	<input type="file" name="logo" /><br /> 
                	<img src ="<?php echo UPLOAD_PATH;?>/<?php echo $data['logo']; ?>" width ="50px"> </td>
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