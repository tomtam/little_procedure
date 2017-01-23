<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>管理后台</title>
<link href="/css/style.css" rel="stylesheet" type="text/css">
<link href="/css/buttoncss.css" rel="stylesheet" type="text/css">
<link href="/css/bootcss.css" rel="stylesheet">
<link href="/css/responsive.css" rel="stylesheet">
<script src="http://libs.baidu.com/jquery/1.9.0/jquery.js"></script>
<script src="/js/common.js" type="text/javascript"></script>
</head>
<body>
    <div class="header">
      <div class="h-nav">
          <div class="h-outer">
          	<div class="link">
          		
          	</div>
          </div>
          <div class="h-outer">
          </div>
      </div>
      <div class="clear"></div>
    </div>
    <div class="layout_content">
    	
  <form class="form-horizontal" action="/login/do" style="margin:10% 28%;" name="loginForm" method="post" onclick="return false;">
    <fieldset>
      <div id="legend" class="">
        <legend class=""></legend>
      </div>
    <div class="control-group">
          <!-- Text input-->
          <label class="control-label" for="input01"></label>
          <div class="controls">
            <input type="text" placeholder="用户名" name="userName" class="input-xlarge">
            <p class="help-block"></p>
          </div>
        </div><div class="control-group">
          <!-- Text input-->
          <label class="control-label" for="input01"></label>
          <div class="controls">
            <input type="password" placeholder="输入密码" name="passWord" class="input-xlarge">
            <p class="help-block"></p>
          </div>
        </div>
        <div class="control-group">

          <!-- Appended input-->
          <label class="control-label"></label>
          <div class="controls">
            <div class="input-append">
              <input class="span2" placeholder="验证码" name="vcode" type="text">
              <span class=""><img src="/login/verify-code" onclick="this.src='/login/verify-code?random='+ Math.random()"></span>
            </div>
            <p class="help-block"></p>
          </div>
    <div class="control-group">
          <label class="control-label"></label>

          <!-- Button -->
          <div class="controls">
            <button class="btn btn-success" onclick="login();">登陆</button>
          </div>
        </div>

    </fieldset>
  </form>
    </div>
    <script>
		function login(){
			var username = $("input[name='userName']").val();
			var password = $("input[name='passWord']").val();
			var vcode = $("input[name='vcode']").val();
			$.ajax({
			    url:'/login/do',
			    type:'POST', 
			    async:true,    //或false,是否异步
			    data:{
			        userName:username,
			        passWord:password,
			        vcode:vcode,
			    },
			    timeout:5000,    //超时时间
			    dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text
			    success:function(data,textStatus,jqXHR){
				    if(data.code == 200){
						location.href="/campaign/index";
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
</body>
</html>