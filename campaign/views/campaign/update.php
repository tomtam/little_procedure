<div class="layout_rightmain panel-write">
    <div class="page-title">活动修改 </div>
    <div class="panel-main">
        <div class="form pd10">
        	<form action="/campaign/update-do" method="post"  name="campAdd" enctype="multipart/form-data" >
        	<input type="hidden" value="<?php echo $info['id'];?>" name="id">
              <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tr>
                    <td width="20%" align="right"><span class="require">* </span>标题：</td>
                  <td width="80%"  align="left"><input type="text" name="title" value="<?php echo $info['title'];?>" id="title" class="input" style="width:300px;"/>
                    <span></span></td>
                </tr>
                <tr>
                    <td width="20%" align="right"><span class="require">* </span>集合地：</td>
                  <td width="80%"  align="left"><input type="text" name="rendezvous" value="<?php echo $info['rendezvous'];?>" id="rendezvous" class="input" placeholder="详细一点的集合地信息"/>
                    <span></span></td>
                </tr>
                <tr>
                    <td width="20%" align="right"><span class="require">* </span>目的地：</td>
                  <td width="80%"  align="left"><input type="text" name="destination" value="<?php echo $info['destination'];?>" id="destination" class="input" placeholder="详细一点的目的地信息"/>
                    <span></span></td>
                </tr>
                <tr>
                    <td width="20%" align="right"><span class="require">* </span>价格：</td>
                  <td width="80%"  align="left"><input type="text" name="price" id="price" value="<?php echo $info['price'];?>" class="input" placeholder="精确到小数点后两位"/>
                    <span></span></td>
                </tr>
                <tr>
                    <td width="20%" align="right"><span class="require">* </span>来源：</td>
                  <td width="80%"  align="left"><input type="text" name="origin" id="origin" class="input" value="<?php echo $info['origin'];?>" placeholder="活动所属机构"/>
                    <span></span></td>
                </tr>
                <tr>
                    <td width="20%" align="right"><span class="require">* </span>活动人数：</td>
                  <td width="80%"  align="left"><input type="text" name="totalNum" id="totalNum" value="<?php echo $info['totalNum'];?>" class="input" />
                    <span></span></td>
                </tr>
                <tr>
                    <td width="20%" align="right"><span class="require">* </span>开始时间：</td>
                  <td width="80%"  align="left"><input type="text" name="beginTime" id="beginTime" value="<?php echo date("Y-m-d", $info['beginTime']);?>" class="input" />
                    <span><img onclick="WdatePicker({el:'beginTime'})" src="/js/My97DatePicker/skin/datePicker.gif" width="16" height="22" align="absmiddle"></span></td>
                </tr>
                <tr>
                    <td width="20%" align="right"><span class="require">* </span>结束时间：</td>
                  <td width="80%"  align="left"><input type="text" name="endTime" id="endTime" value="<?php echo date("Y-m-d", $info['endTime']);?>" class="input" />
                    <span><img onclick="WdatePicker({el:'endTime'})" src="/js/My97DatePicker/skin/datePicker.gif" width="16" height="22" align="absmiddle"></span></td>
                </tr>
                <tr>
                  <td align="right"><p><span class="require">* </span>活动所在市：</p></td>
                  <td align="left"><input type="text" name="locationName" id="locationName"  value="<?php echo $info['locationName'];?>" class="input" placeholder="活动所在市，比如黄山市"/>
                    <span></span></td>
                </tr>
                <tr>
                  <td align="right"><p><span class="require">* </span>活动关键字：</p></td>
                  <td align="left"><input type="text" name="campKeyword" id="campKeyword"  class="input" value="<?php echo $info['campKeyword'];?>" placeholder="多个关键字请用逗号间隔"/>
                    <span></span></td>
                </tr>
                <tr>
                  <td align="right"><span class="require">* </span>活动种类：</td>
                  <td align="left">
                  	  <?php foreach($campTypeArr as $campTypeId=>$campTypeName): ?>
                      	  <input type="checkbox" <?php echo in_array($campTypeId, $info['campType']) ? "checked" : "";?> class="check <?php if($campTypeId%5==0){?>check-first<?php }?>" name="campType[]" value="<?php echo $campTypeId;?>"><?php echo $campTypeName;?>
						  <?php if($campTypeId%5==4){?><br /><?php }?>
                      <?php endforeach;?>
                  </td>
                </tr>
                <tr>
                	<td align="right"><span class="require">* </span>已添加图片：</td>
                	<td align="left" class="clearfix">
                		<?php foreach ($info['imageArr'] as $image):?>
                			<span style="float: left;margin-right:8px;">
                    			<img src="/upload/<?php echo $image['content'];?>" style="max-width:100px;"></br>
                    			<a href="javascript:void(0);" onclick="delImg(<?php echo $image['id'];?>)" style="cursor:pointer;">删除</a>
                    		</span>
                		<?php endforeach;?>
                		<?php if(!count($info['imageArr'])):?>
                			无图片
                		<?php endif;?>
                	</td>
                </tr>
                <tr>
                	<td align="right"><span class="require">* </span>新添加图片：</td>
                	<td align="left">
                		<input type="file" name="campImg[]">&nbsp;&nbsp;&nbsp;<a href="#" onclick="addImageInput(this);" style="cursor: pointer;">再添加一张</a>
                	</td>
                </tr>
                <tr>
                	<td align="right"><span class="require">* </span>头像图片：</td>
                	<td align="left">
                		<img src="/upload/<?php echo $info['headImg']['content'];?>" style="max-width:100px;"></br>
                	</td>
                </tr>
                <tr>
                	<td align="right"><span class="require">* </span>更新头像：</td>
                	<td align="left">
                		<input type="file" name="headImg">&nbsp;&nbsp;&nbsp;
                	</td>
                </tr>
                <tr>
                  <td align="right"><span class="require">* </span>线路介绍：</td>
                  <td align="left"><textarea name="lineIntroduction" id="lineIntroduction"/><?php echo $info['lineIntroduction'];?></textarea>
                </tr>
                <tr>
                  <td align="right"><span class="require">* </span>行程安排：</td>
                  <td align="left"><textarea name="scheduling" id="scheduling"/><?php echo $info['scheduling'];?></textarea>
                </tr>
                <tr>
                  <td align="right"><span class="require">* </span>费用说明：</td>
                  <td align="left"><textarea name="expenseExplanation" id="expenseExplanation"/><?php echo $info['expenseExplanation'];?></textarea>
                </tr>
                <tr>
                  <td align="right"><span class="require">* </span>更多介绍：</td>
                  <td align="left"><textarea name="moreIntroduction" id="moreIntroduction"/><?php echo $info['moreIntroduction'];?></textarea>
                </tr>
                <tr>
                  <td align="right">&nbsp;</td>
                  <td align="left"><a class="btn btn-large" id="campAddButton">修改</a>  <a class="btn btn-cancel btn-large" onclick="history.go(-1);">取消</a> </td>
                </tr>
              </table>
             </form>
        </div>
      </div>            
</div>
<script type="text/javascript" src="/js/My97DatePicker/WdatePicker.js"></script>
<script>
	$(document).ready(function(){
		$("#campAddButton").click(function(){
			if(!$("input[name='title']").val()){
                                alert("请填写标题");
                                return false;
                        }
                        if(!$("input[name='rendezvous']").val()){
                                alert("请填集合地");
                                return false;
                        }
                        if(!$("input[name='destination']").val()){
                                alert("请填写目的地");
                                return false;
                        }
                        if(!$("input[name='price']").val()){
                                alert("请填写价格");
                                return false;
                        }
			if(!$("input[name='origin']").val()){
                                alert("请填写来源");
                                return false;
                        }
                        if(!$("input[name='totalNum']").val()){
                                alert("请填写活动人数");
                                return false;
                        }
                        if(!$("input[name='beginTime']").val()){
                                alert("请填写开始时间");
                                return false;
                        }
                        if(!$("input[name='endTime']").val()){
                                alert("请填写结束时间");
                                return false;
                        }
                        if(!$("input[name='locationName']").val()){
                                alert("请填写活动所在市");
                                return false;
                        }
                        if(!$("input[name='campKeyword']").val()){
                                alert("请填写活动关键字");
                                return false;
                        }
			$("form").submit();
		});
	});
	function addImageInput(obj){
		$(obj).parent().append("<br>" + '<input type="file" name="campImg[]">&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);"  onclick="addImageInput(this);" style="cursor: pointer;">再添加一张</a>');
		$(obj).remove();
	};
</script>
