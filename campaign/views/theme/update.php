<div class="layout_rightmain panel-write">
    <div class="page-title">主题更新</div>
    <div class="panel-main">
        <div class="form pd10">
        	<form action="/theme/update-do" method="post"  name="themeUpdate" enctype="multipart/form-data" >
        		<input type="hidden" value="<?php echo $info['id'];?>" name="id">
              <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tr>
                    <td width="20%" align="right"><span class="require">* </span>标题：</td>
                  <td width="80%"  align="left"><input type="text" name="title" value="<?php echo $info['title'];?>" id="title" class="input" style="width:300px;"/>
                    <span></span></td>
                </tr>
                <tr>
                	<td align="right"><span class="require">* </span>头像图片：</td>
                	<td align="left">
                		<img src="/upload/<?php echo $info['picUrl'];?>" style="max-width:100px;">
                	</td>
                </tr>
                <tr>
                	<td align="right"><span class="require">* </span>更新图片：</td>
                	<td align="left">
                		<input type="file" name="headImg">&nbsp;&nbsp;&nbsp;
                	</td>
                </tr>
                <tr>
                  <td align="right"><span class="require">* </span>主题介绍：</td>
                  <td align="left"><textarea name="themeIntroduction" id="themeIntroduction"/><?php echo $info['introduction'];?></textarea>
                </tr>
                <tr>
                  <td align="right">&nbsp;</td>
                  <td align="left"><a class="btn btn-large" id="themeAddButton">更新</a>  <a class="btn btn-cancel btn-large" onclick="history.go(-1);">取消</a> </td>
                </tr>
              </table>
             </form>
        </div>
      </div>            
</div>
<script type="text/javascript" src="/js/My97DatePicker/WdatePicker.js"></script>
<script>
	$(document).ready(function(){
		$("#themeAddButton").click(function(){
			$("form").submit();
		});
	});
	function addImageInput(obj){
		$(obj).parent().append("<br>" + '<input type="file" name="campImg[]">&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);"  onclick="addImageInput(this);" style="cursor: pointer;">再添加一张</a>');
		$(obj).remove();
	};
</script>