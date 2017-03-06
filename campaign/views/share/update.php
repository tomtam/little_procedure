<?php
use campaign\models\Share;
?>
<div class="layout_rightmain panel-write">
    <div class="page-title">分享更新</div>
    <div class="panel-main">
        <div class="form pd10">
        	<form action="/share/update-do" method="post"  name="themeAdd" enctype="multipart/form-data" >
        		<input type="hidden" value="<?php echo $info['id']?>" name="id">
              <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tr>
                    <td width="20%" align="right"><span class="require">* </span>标题：</td>
                  <td width="80%"  align="left"><input type="text" name="title" id="title" value="<?php echo $info['title'];?>" class="input" style="width:300px;"/>
                    <span></span></td>
                </tr>
                <tr>
                	<td width="20%" align="right"><span class="require">* </span>类型：</td>
                	<td width="80%"  align="left">
                		<select name="shareType" id="shareType" class="select select-long" onchange="ImgVideo(this);">
                        		<option>请选择</option>
                        		<?php foreach($typeArr as $typeId=>$typeName):?>
                        			<option value="<?php echo $typeId;?>" <?php echo $info['shareType']==$typeId ? "selected='selected'" : "";?>><?php echo $typeName;?></option>
                        		<?php endforeach;?>
                        	</select>
                    <span></span></td>
                </tr>
                <tr>
                	<td align="right"><span class="require">* </span>原数据：</td>
                	<td align="left">
                		<?php if($info['shareType'] == Share::SHARE_TYPE_VIDEO):?>
                			<?php echo $info['content'];?>
                		<?php elseif($info['shareType'] == Share::SHARE_TYPE_IMG):?>
                			<?php foreach ($info['content'] as $img):?>
                				<span style="float: left;margin-right:8px;">
                    				<img src="/upload/<?php echo $img;?>" style="max-width:100px;max-height:80px;"></br>
                    				<a href="javascript:void(0);" onclick="delImg('<?php echo $img;?>', <?php echo $info['id'];?>)" style="cursor:pointer;">删除</a>
                    			</span>
                			<?php endforeach;?>
                		<?php endif;?>
                	</td>
                </tr>
                <?php if($info['shareType'] == Share::SHARE_TYPE_IMG):?>
                    <tr id="shareImgTr">
                    	<td align="right"><span class="require">* </span>图片：</td>
                    	<td align="left">
                    		<input type="file" name="shareImg[]">&nbsp;&nbsp;&nbsp;<a class="addImageInput" href="#" onclick="addImageInput(this);" style="cursor: pointer;">再添加一张</a>
                    	</td>
                    </tr>
                <?php elseif($info['shareType'] == Share::SHARE_TYPE_VIDEO):?>
                    <tr id="shareVideoTr">
                    	<td align="right"><span class="require">* </span>视频源地址：</td>
                    	<td align="left" width="80%">
                    		<input type="text" name="shareVideo" value="<?php echo htmlspecialchars($info['content']);?>" style="width:300px;">&nbsp;&nbsp;&nbsp;
                    	</td>
                    </tr>
                <?php endif;?>
                <tr>
                  <td align="right"><span class="require">* </span>摘要：</td>
                  <td align="left"><textarea name="detail" id="detail"/><?php echo $info['detail'];?></textarea>
                </tr>
                <tr>
                  <td align="right">&nbsp;</td>
                  <td align="left"><a class="btn btn-large" id="shareAddButton">更新</a>  <a class="btn btn-cancel btn-large" onclick="history.go(-1);">取消</a> </td>
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
	function delImg(img, id){
		$.ajax({
		    url:'/share/del-img',
		    type:'POST', 
		    async:true,    //或false,是否异步
		    data:{
		        id:id,
		        img:img
		    },
		    timeout:5000,    //超时时间
		    dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text
		    success:function(data,textStatus,jqXHR){
			    if(data.code == 200){
					location.reload();
				}else{
					alert(data.info);
					return false;
				}
		        console.log(data)
		        console.log(textStatus)
		        console.log(jqXHR)
		    },
		    error:function(xhr,textStatus){
		        console.log('错误')
		        console.log(xhr)
		        console.log(textStatus)
		    },
		    complete:function(){
		        console.log('结束')
		    }
		})
	}
</script>