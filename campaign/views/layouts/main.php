<?php
use campaign\models\Login;
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>管理后台</title>
<link href="/css/style.css" rel="stylesheet" type="text/css">
<script src="http://libs.baidu.com/jquery/1.9.0/jquery.js"></script>
<script src="/js/common.js" type="text/javascript"></script>
</head>
<body>
    <div class="header">
      <div class="h-logo"><a href="list.html" title="老王的管理后台"><img src="/images/qaup_logo.png" width="130" height="40"  alt=""/></a></div>
      <div class="h-nav">
          <div class="h-outer">
          	<div class="link">
          		
          	</div>
          </div>
          <div class="h-outer">
              <div class="link h-noborder">
                      <span class="select-txt2"><?php echo Yii::$app->session[Login::USERNAME_SESSION] ? Yii::$app->session[Login::USERNAME_SESSION] : "无用户";?></span>
                      <a class="select-open"></a>
                      <div class="option" style="display: none;"><a href="/login/logout">退出系统</a> </div>
              </div> 
          </div>
      </div>
      <div class="clear"></div>
    </div>
    <div class="layout_content">
        <div class="layout_leftnav">
            <div class="nav-vertical">
              <ul class="accordion">
                <li> 
                    <a href="#"><i class="ui-opt"></i>收起左边栏</a>
                    <div class="text">展开侧边栏</div>
                </li>
                <li> 
                    <a href="/campaign" <?php echo Yii::$app->controller->id == "campaign" ? "class='active'" : "";?>><i class="ui-list"></i>活动列表</a>
                    <div class="text">活动列表</div>
                </li>
                <li> 
                    <a href="/order" <?php echo Yii::$app->controller->id == "order" ? "class='active'" : "";?>><i class="ui-stat"></i>订单管理</a>
                    <div class="text">订单管理</div>
                </li>
                <li> 
                    <a href="/user" <?php echo Yii::$app->controller->id == "user" ? "class='active'" : "";?>><i class="ui-analyse"></i>用户注册</a>
                    <div class="text">用户注册</div>
                </li>
                <!-- 
                <li> 
                    <a href="textbox.html"><i class="ui-manage"></i>权限管理</a>
                    <div class="text">权限管理</div>
                </li>
                <li> 
                    <a href="#"><i class="ui-asset"></i>权限审批</a>
                    <div class="text">权限审批</div>
                </li>
                <li> 
                    <a href="#"><i class="ui-custom"></i>用户定制</a>
                    <div class="text">用户定制</div>
                </li>
                <li> 
                    <a href="#"><i class="ui-view"></i>日志查看</a>
                    <div class="text">日志查看</div>
                </li>
                 -->
              </ul>

            </div>
        </div>
        <?php echo $content;?>
    </div>
</body>
</html>