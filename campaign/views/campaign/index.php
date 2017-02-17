<div class="layout_rightmain">
            <div class="r-top">
               <div class="panel-main panel-write">
                <div class="form pd10">
                  <table border="0" cellspacing="0" cellpadding="0" width="100%">
                    <tbody>
                    <tr>
                        <td align="right">标题：</td>
                        <td align="left"><input type="text" name="title" value="<?php echo $title;?>" id="title" class="input input-long i-hint" placeholder="输入标题检索..."></td>
                        <td align="right">来源：</td>
                        <td align="left">
                        	<select name="origin" id="origin" class="select select-long">
                        		<option>请选择</option>
                        		<?php foreach($originArr as $origin):?>
                        		<option value="<?php echo $origin['origin'];?>" <?php echo $originVal==$origin['origin'] ? 'selected' : '';?>><?php echo $origin['origin'];?></option>
                        		<?php endforeach;?>
                        	</select>
                        </td>
                        <td align="right"></td>
                        <td align="left"></td>
                    </tr>
                    <tr>
                        <td align="right">开始时间：</td>
                        <td align="left">
                            <div class="datepicker">
                            	<span id="beginTime"><?php echo $beginTime;?></span>
                                <span><img onclick="WdatePicker({el:'beginTime'})" src="/js/My97DatePicker/skin/datePicker.gif" width="16" height="22" align="absmiddle"></span>
                            </div> 到 
                            <div class="datepicker">
                                <span id="endTime"><?php echo $endTime;?></span>
                                <span><img onclick="WdatePicker({el:'endTime'})" src="/js/My97DatePicker/skin/datePicker.gif" width="16" height="22" align="absmiddle"></span>
                            </div>
                        </td>
                        <td align="right"><a href="#" class="btn" onclick="searchCamp();">查询</a></td>
                        <td align="left"></td>  
                        <td align="right"></td>
                        <td>&nbsp;</td>
                    </tr>
                  </tbody></table>
                </div>
              </div>
            </div>
            <div class="r-middle">
                <a href="/campaign/add" class="btn" />添加活动</a>
            </div>
            <div class="r-bottom">
                <div class="list-table">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <th width="10%"><span>标题</span></th>
                        <th width="8%"><span>目的地</span></th>
                		<th width="8%"><span>来源</span></th>
                		<th width="12%"><span>添加时间</span></th>
                		<th width="32%"><span>其余信息</span></th>
                        <th width="16%"><span>置顶</span></th>
                        <th width="14%"><span>操作</span></th>
              		</tr>
              <?php foreach ($list as $camp):?>
              <tr>
                <td><?php echo $camp['title'];?></td>
                <td><?php echo $camp['destination']?></td>
                <td><?php echo $camp['origin']?></td>
                <td><?php echo $camp['updateTime']?></td>
                <td>
                	开始时间：<?php echo date("Y-m-d", $camp['beginTime']);?></br>
                	结束时间：<?php echo date("Y-m-d", $camp['endTime']);?></br>
                	价       格：<?php echo $camp['price']?>元</br>
                	天       数：<?php echo $camp['dayNum'];?>天</br>
                	种 类：<?php echo $camp['campType'];?></br>
                	人 数：<?php echo $camp['totalNum'];?></br>
                </td>
                <td>
                	<a class="t-link" style="cursor: pointer;" onclick="zhiding(<?php echo $camp['id'];?>)">置顶</a>
                	<?php if($camp['isStick'] != 0):?>
                		<font color="red">(已置顶)</font>|<a href="#" onclick="cancelZhiding(<?php echo $camp['id'];?>)">取消置顶</a>
                	<?php endif;?>
                </td>
                <td>
                	<a class="i-operate" href="/campaign/update?id=<?php echo $camp['id'];?>" title="更新">更新</a>|
                	<a class="i-operate" href="#" onclick="del(<?php echo $camp['id'];?>)" title="删除">删除</a>
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
                        <input type="hidden" id="select_value">
                        <div class="i-list">
                            	<?php if($page > 1):?> 
                            		<span><a href="/campaign/index?page=1&title=<?php echo $title;?>&beginTime=<?php echo $beginTime;?>&endTime=<?php echo $endTime;?>&origin=<?php echo $originVal;?>">首页</a></span>
                            	<?php endif;?>
                            	<?php foreach ($pageArr as $pageVal):?>
                            		<?php if($page == $pageVal):?>
                            			<span class="active"><?php echo $pageVal;?></span>
                            		<?php else: ?>
                            			<a href="/campaign/index?page=<?php echo $pageVal;?>&title=<?php echo $title;?>&beginTime=<?php echo $beginTime;?>&endTime=<?php echo $endTime;?>&origin=<?php echo $originVal;?>"><?php echo $pageVal;?></a>
                            		<?php endif;?>
                            	<?php endforeach;?>
                            	<?php if($page < $totalPage):?> 
                            		<a href="/campaign/index?page=<?php echo $totalPage;?>&title=<?php echo $title;?>&beginTime=<?php echo $beginTime;?>&endTime=<?php echo $endTime;?>&origin=<?php echo $originVal;?>">末页</a> 
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
function zhiding(id){
	$.ajax({
	    url:'/campaign/stick',
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
	    url:'/campaign/cancel-stick',
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
	    url:'/campaign/delete',
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
function searchCamp(){
	var beginTime = $("#beginTime").text().trim();
	var endTime   = $("#endTime").text().trim();

	var title = $("#title").val();
	var origin = $("#origin").val();
	location.href="/campaign/index?beginTime="+beginTime+"&endTime="+endTime+"&title="+title+"&origin="+origin;
}
</script>
