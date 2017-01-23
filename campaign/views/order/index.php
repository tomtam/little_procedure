<div class="layout_rightmain">
            <div class="r-top">
               <div class="panel-main panel-write">
                <div class="form pd10">
                  <table border="0" cellspacing="0" cellpadding="0" width="100%">
                    <tbody>
                    <tr>
                        <td align="right">标题：</td>
                        <td align="left"><input type="text" name="title" value="<?php echo $title;?>" id="title" class="input input-long i-hint" placeholder="输入标题检索..."></td>
                        <td align="right">用户名：</td>
                        <td align="left">
                        	<input type="text" name="userName" value="<?php echo $userName;?>" id="title" class="input input-long i-hint" placeholder="输入用户名...">
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
                        <td align="right"><a href="#" class="btn" onclick="searchOrder();">查询</a></td>
                        <td align="left"></td>  
                        <td align="right"></td>
                        <td>&nbsp;</td>
                    </tr>
                  </tbody></table>
                </div>
              </div>
            </div>
            <div class="r-middle">
            </div>
            <div class="r-bottom">
                <div class="list-table">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <th width="12%"><span>标题</span></th>
                        <th width="10%"><span>用户名</span></th>
                		<th width="13%"><span>活动机构</span></th>
                		<th width="15%"><span>订单时间</span></th>
                		<th width="36%"><span>订单信息</span></th>
                        <th width="14%"><span>订单状态</span></th>
              		</tr>
              <?php foreach ($list as $order):?>
              <tr>
                <td><?php echo $order['campTitle'];?></td>
                <td><?php echo $order['userName']?></td>
                <td><?php echo $order['origin']?></td>
                <td><?php echo date("Y-m-d H:i:s", $order['createTime']);?></td>
                <td>
                	价 格：<?php echo $order['price'];?>元</br>
                	数 量：<?php echo $order['num'];?></br>
                	总 价：<?php echo $order['amount']?>元</br>
                	评 价：<?php echo $order['evaluateStatus'] ? $order['evaluateCount'] : "未评价";?></br>
                </td>
                <td>
                	<?php echo $order['status'];?>
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
                            		<span><a href="/order/index?page=1&title=<?php echo $title;?>&beginTime=<?php echo $beginTime;?>&endTime=<?php echo $endTime;?>&userName=<?php echo $userName;?>">首页</a></span>
                            	<?php endif;?>
                            	<?php foreach ($pageArr as $pageVal):?>
                            		<?php if($page == $pageVal):?>
                            			<span class="active"><?php echo $pageVal;?></span>
                            		<?php else: ?>
                            			<a href="/order/index?page=<?php echo $pageVal;?>&title=<?php echo $title;?>&beginTime=<?php echo $beginTime;?>&endTime=<?php echo $endTime;?>&userName=<?php echo $userName;?>"><?php echo $pageVal;?></a>
                            		<?php endif;?>
                            	<?php endforeach;?>
                            	<?php if($page < $totalPage):?> 
                            		<a href="/order/index?page=<?php echo $totalPage;?>&title=<?php echo $title;?>&beginTime=<?php echo $beginTime;?>&endTime=<?php echo $endTime;?>&userName=<?php echo $userName;?>">末页</a> 
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
function searchOrder(){
	var beginTime = $("#beginTime").text().trim();
	var endTime   = $("#endTime").text().trim();

	var title = $("#title").val();
	var userName = $("#userName").val();
	location.href="/order/index?beginTime="+beginTime+"&endTime="+endTime+"&title="+title+"&userName="+userName;
}
</script>
