<?php/*声明assign函数渲染的变量*/?><?php $titleURL=$this->tpl_var["titleURL"];$title=$this->tpl_var["title"];?><?php include showUrl("Index/header"); ?>
<div class="main-div">
    <form name="main_form" method="POST" action="<?php echo SELF_PATH; ?>" enctype="multipart/form-data">
        <table cellspacing="1" cellpadding="3" width="100%">
            <tr>
                <td class="label">级别名称：</td>
                <td>
                    <input  type="text" name="level_name" value="" />
                </td>
            </tr>
            <tr>
                <td class="label">积分下限：</td>
                <td>
                    <input  type="text" name="bottom_num" value="" />
                </td>
            </tr>
            <tr>
                <td class="label">积分上限：</td>
                <td>
                    <input  type="text" name="top_num" value="" />
                </td>
            </tr>
            <tr>
                <td class="label">折扣率，以百分比，如：9折=90：</td>
                <td>
                    <input  type="text" name="rate" value="100" />
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