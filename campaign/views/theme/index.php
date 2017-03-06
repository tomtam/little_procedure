<div class="layout_rightmain">
            <div class="r-top">
               <div class="panel-main panel-write">
                <div class="form pd10">
                  <table border="0" cellspacing="0" cellpadding="0" width="100%">
                    <tbody>
                    <tr>
                        <td align="right">标题：</td>
                        <td align="left"><input type="text" name="title" value="<?php echo $title;?>" id="title" class="input input-long i-hint" placeholder="输入标题检索..."></td>
                        <td align="right"><a href="#" class="btn" onclick="searchTheme();">查询</a></td>
                        <td align="left"></td>
                        <td align="right"></td>
                        <td align="left"></td>
                    </tr>
                  </tbody></table>
                </div>
              </div>
            </div>
            <div class="r-middle">
                <a href="/theme/add" class="btn" />添加主题</a>
            </div>
            <div class="r-bottom">
                <div class="list-table">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <th width="10%"><span>标题</span></th>
                        <th width="8%"><span>图片</span></th>
                		<th width="12%"><span>添加时间</span></th>
                		<th width="25%"><span>描述</span></th>
                		<th width="16%"><span>活动列表</span></th>
                        <th width="10%"><span>置顶</span></th>
                        <th width="14%"><span>操作</span></th>
              		</tr>
              <?php foreach ($list as $theme):?>
              <tr>
                <td><?php echo $theme['title'];?></td>
                <td><img src="/upload/<?php echo $theme['picUrl']?>" style="max-width: 100px;"></td>
                <td><?php echo date("Y-m-d H:i:s", $theme['createTime']);?></td>
                <td><?php echo $theme['introduction']?></td>
                <td>
                	<?php if(count($theme['campList'])):?>
                		<?php foreach ($theme['campList'] as $camp):?>
                			<?php echo $camp['title'];?> <a href="javascript:void(0);" onclick="delCamp(<?php echo $camp['id']?>, <?php echo $theme['id']?>);">删除</a><br>
                		<?php endforeach;?>
                	<?php else: ?>
                		暂无活动
                	<?php endif;?>
                </td>
                <td>
                	<a class="t-link" style="cursor: pointer;" onclick="zhiding(<?php echo $theme['id'];?>)">置顶</a>
                	<?php if($theme['isStick'] != 0):?>
                		<font color="red">(已置顶)</font>|<a href="#" onclick="cancelZhiding(<?php echo $theme['id'];?>)">取消置顶</a>
                	<?php endif;?>
                </td>
                <td>
                	<a class="i-operate" href="/theme/update?id=<?php echo $theme['id'];?>" title="更新">更新</a>|
                	<a class="i-operate" href="#" onclick="del(<?php echo $theme['id'];?>)" title="删除">删除</a>
                </td>
              </tr>
              <?php endforeach;?>
              <tr>
                  <td colspan="2">
                  	<?php if(!$totalPage):?>
                  		无数据！
                  	<?php endif;?>
                  </td>
                  <td colspan="9">
                  	  <?php if($totalPage):?>
                      <div class="list-page">
                        <div class="i-total">共有<?php echo $count ? $count : 0;?>条记录</div>
                        <div class="i-num"> 
                            <span class="select-txt">每页显示：<?php echo $perNum;?>条</span>
                        </div>
                                                           跳转到：<input type="text" name="page" value="" style="width:30px;" onkeydown="intoPage(this);">
                        <input type="hidden" id="select_value">
                        <div class="i-list">
                            	<?php if($page > 1):?> 
                            		<span><a href="/theme/index?page=1&title=<?php echo $title;?>">首页</a></span>
                            	<?php endif;?>
                            	<?php foreach ($pageArr as $pageVal):?>
                            		<?php if($page == $pageVal):?>
                            			<span class="active"><?php echo $pageVal;?></span>
                            		<?php else: ?>
                            			<a href="/theme/index?page=<?php echo $pageVal;?>&title=<?php echo $title;?>"><?php echo $pageVal;?></a>
                            		<?php endif;?>
                            	<?php endforeach;?>
                            	<?php if($page < $totalPage):?> 
                            		<a href="/theme/index?page=<?php echo $totalPage;?>&title=<?php echo $title;?>">末页</a> 
                            	<?php endif;?>
                        </div>
                        <div class="clear"></div>
                      </div> 
                      <?php endif;?> 
                  </td>
              </tr>
            </table>
          </div>
    </div>
</div>
<div class="clear"></div>
<script type="text/javascript" src="/js/My97DatePicker/WdatePicker.js"></script>
<script>
function intoPage(obj){
	var page = obj.value;
	if (event.keyCode==13){
		location.href = "/theme/index?page="+page+"&title=<?php echo $title;?>";
	}
}
function zhiding(id){
	$.ajax({
	    url:'/theme/stick',
	    type:'POST', 
	    async:true,    //或false,是否异步
	    data:{
	        id:id
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
function cancelZhiding(id){
	$.ajax({
	    url:'/theme/cancel-stick',
	    type:'POST', 
	    async:true,    //或false,是否异步
	    data:{
	        id:id
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
function del(id){
	if(!confirm("您确定删除此条数据？")){
		return false;
	}
	$.ajax({
	    url:'/theme/delete',
	    type:'POST', 
	    async:true,    //或false,是否异步
	    data:{
	        id:id
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
function searchTheme(){
	var title = $("#title").val();
	location.href="/theme/index?title="+title;
}
function delCamp(campId, themeId){
	$.ajax({
	    url:'/theme/camp-del',
	    type:'POST', 
	    async:true,    //或false,是否异步
	    data:{
	        campId:campId,
	        themeId:themeId
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
