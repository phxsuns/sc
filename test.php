<?php
$keys = array('xxx','中文');
print_r(str_split('中文'));
?>

<html>
	<head>
		<meta charset="utf-8">
		<title>测试</title>
		<script src="/static/js/jquery.js"></script>
		<script>
			$(document).ready(function(){
				$('form').append('<input type="text" name="go">');
			});
		</script>
	</head>
	<body>
		<form action="" method="post">
			<input type="submit" value="submit">
		</form>
	</body>
</html>