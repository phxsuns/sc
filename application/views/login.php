<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>素材库 - 登录</title>
	<link href="/static/css/bootstrap.css" rel="stylesheet" />
	<script src="/static/js/jquery.js"></script>
	<style>
	.login{
		width:550px;
		margin: 200px auto;
	}
	</style>
</head>
<body id="login">

<div class="login">
	<form class="form-horizontal"  action="/login/go/" method="post">
		<div class="control-group">
			<label class="control-label" for="inputId">用户名</label>
			<div class="controls">
				<input type="text" id="inputId" placeholder="用户名" name="username">
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="inputPassword">密　码</label>
			<div class="controls">
				<input type="password" id="inputPassword" placeholder="密　码" name="password">
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<button type="submit" class="btn btn-primary">登 录</button>
			</div>
		</div>
	</form>
</div>

<script>
</script>
</body>
</html>