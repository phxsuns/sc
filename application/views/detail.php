<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>素材库 - 阿里学院UED</title>

	<link rel="shortcut icon" type="image/x-icon" href="/static/favicon.ico">
	<link rel="icon" type="image/x-icon" href="/static/favicon.ico">

	<link rel="stylesheet" href="/static/css/common.css" type="text/css">
	<link rel="stylesheet" href="/static/css/global.css" type="text/css">
	<link rel="stylesheet" href="/static/css/detail.css" type="text/css">
	<script src="/static/js/jquery.js"></script>
	<script src="/static/js/global.js"></script>
	<script src="/static/js/detail.js"></script>
</head>
<body>

	<?php $this->load->view('header',array());?>

	<div id="nav">
		<div class="container">
			<div class="navline"></div>
		</div>
	</div>

	<div id="main">
		<div class="container">
			<div class="btn">
				<a class="big-btn" href="/download?n=<?php echo implode('.', $tags); ?>&f=<?=$src ?>" target="_blank"><i class="icon icon-download"></i>源文件下载</a>
				<a class="big-btn" href="<?=$image ?>" target="_blank"><i class="icon icon-src"></i>大图浏览</a>
			</div>
			<div class="info">
				<h2><?php echo implode(' ', $tags); ?></h2>
				<p><span class="info-tit">素材类型：</span><?=$type ?></p>
				<p><span class="info-tit">入库日期：</span><?=$date ?></p>
				<p><span class="info-tit">浏览次数：</span><?=$view ?></p>
				<p><span class="info-tit">详细描述：</span><?=$intro ?></p>
				<?php if(isset($login) && $login): ?><div class="info-admin">
					<a href="/admin/edit/<?=$id ?>" class="btn-edit" data-id="<?=$id ?>">编辑</a>
					<a href="javascript:;" class="btn-del" data-id="<?=$id ?>">删除</a>
				</div><?php endif; ?>
			</div>
			<div class="view">
				<img src="<?=$image_d ?>">
			</div>
		</div>
	</div>

	<?php $this->load->view('footer',array());?>

</body>
</html>