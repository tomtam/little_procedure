<div class="layout_rightmain panel-write">
    <div class="page-title">分享添加 </div>
    <div class="panel-main">
        <div class="form pd10">
        	<form action="/share/add-do" method="post"  name="themeAdd" enctype="multipart/form-data" >
              <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tr>
                    <td width="20%" align="right"><span class="require">* </span>标题：</td>
                  <td width="80%"  align="left"><input type="text" name="title" id="title" class="input" style="width:300px;"/>
                    <span></span></td>
                </tr>
                <tr>
                	<td width="20%" align="right"><span class="require">* </span>类型：</td>
                	<td width="80%"  align="left">
                		<select name="shareType" id="shareType" class="select select-long" onchange="ImgVideo(this);">
                        		<option>请选择</option>
                        		<?php foreach($typeArr as $typeId=>$typeName):?>
                        		<option value="<?php echo $typeId;?>"><?php echo $typeName;?></option>
                        		<?php endforeach;?>
                        	</select>
                    <span></span></td>
                </tr>
                <tr id="shareImgTr">
                	<td align="right"><span class="require">* </span>图片：</td>
                	<td align="left">
                		<input type="file" name="shareImg[]">&nbsp;&nbsp;&nbsp;<a class="addImageInput" href="#" onclick="addImageInput(this);" style="cursor: pointer;">再添加一张</a>
                	</td>
                </tr>
                <tr id="shareVideoTr">
                	<td align="right"><span class="require">* </span>视频源地址：</td>
                	<td align="left" width="80%">
                		<input type="text" name="shareVideo" value="" style="width:300px;">&nbsp;&nbsp;&nbsp;
                	</td>
                </tr>
                <tr>
                  <td align="right"><span class="require">* </span>摘要：</td>
                  <td align="left"><textarea name="detail" id="detail"/></textarea>
                </tr>
                <tr>
                  <td align="right">&nbsp;</td>
                  <td align="left"><a class="btn btn-large" id="shareAddButton">添加</a>  <a class="btn btn-cancel btn-large" onclick="history.go(-1);">取消</a> </td>
                </tr>
              </table>
             </form>
        </div>
      </div>            
</div>
<script>
	$(document).ready(function(){
		$("#shareAddButton").click(function(){
			$("form").submit();
		});
	});
	function addImageInput(obj){
		$(obj).parent().append("<br>" + '<input type="file" name="shareImg[]">&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);"  onclick="addImageInput(this);" style="cursor: pointer;">再添加一张</a>');
		$(obj).remove();
	};
	function ImgVideo(obj){
		if($(obj).val() == 1){
			$("#shareImgTr").hide();
			$("#shareVideoTr").show();
		}else if($(obj).val() == 2){
			$("#shareImgTr").show();
			$("#shareVideoTr").hide();
		}
	}
</script>