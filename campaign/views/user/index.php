<div class="layout_rightmain">
            <div class="r-top">
               <div class="panel-main panel-write">
                <div class="form pd10">
                  <table border="0" cellspacing="0" cellpadding="0" width="100%">
                    <tbody>
                    <tr>
                        <td align="right">用户名：</td>
                        <td align="left"><input type="text" name="userName" value="<?php echo $userName;?>" id="userName" class="input input-long i-hint" placeholder="输入标题检索..."></td>
                        <td align="left" width="50%"><a href="#" class="btn" onclick="searchUser();">查询</a></td>
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
                        <th width="20%"><span>用户名</span></th>
                		<th width="20%"><span>注册时间</span></th>
                		<th width="20%"><span>头像</span></th>
                		<th width="20%"><span>电话</span></th>
              		</tr>
              <?php foreach ($list as $user):?>
              <tr>
                <td><?php echo $user['name'];?></td>
                <td>
                	<?php echo date("Y-m-d H:i:s", $user['createTime']);?>
                </td>
                <td><img src="<?php echo $user['photoUrl'];?>" style="max-width:100px;"></td>
                <td><?php echo $user['phone'];?></td>
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
                            		<span><a href="/user/index?page=1&userName=<?php echo $userName;?>">首页</a></span>
                            	<?php endif;?>
                            	<?php foreach ($pageArr as $pageVal):?>
                            		<?php if($page == $pageVal):?>
                            			<span class="active"><?php echo $pageVal;?></span>
                            		<?php else: ?>
                            			<a href="/user/index?page=<?php echo $pageVal;?>&userName=<?php echo $userName;?>"><?php echo $pageVal;?></a>
                            		<?php endif;?>
                            	<?php endforeach;?>
                            	<?php if($page < $totalPage):?> 
                            		<a href="/user/index?page=<?php echo $totalPage;?>&userName=<?php echo $userName;?>">末页</a> 
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
function searchUser(){

	var userName = $("#userName").val();
	location.href="/user/index?userName="+userName;
}
</script>
