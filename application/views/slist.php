<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>在线图库 - 阿里学院UED</title>

	<link rel="shortcut icon" type="image/x-icon" href="/static/favicon.ico">
	<link rel="icon" type="image/x-icon" href="/static/favicon.ico">

	<link rel="stylesheet" href="/static/css/common.css" type="text/css">
	<link rel="stylesheet" href="/static/css/global.css" type="text/css">
	<link rel="stylesheet" href="/static/css/slist.css" type="text/css">
	<script src="/static/js/jquery.js"></script>
	<script src="/static/js/global.js"></script>
	<!--<script src="/static/js/slist.js"></script>-->
</head>
<body>

	<?php $this->load->view('header',array());?>

	<div id="nav">
		<div class="container">
			<div class="navline"></div>
			<div class="navinfo">
				<div class="row">
					<div class="row-title">热门标签：</div>
					<div class="row-info">
						<?php
							$cat = isset($hot_tags) ? $hot_tags : array();
							$tag = isset($tag) ? $tag : '';
							foreach ($cat as $value) {
								if($tag == $value) $selected = ' class="selected"';
								else $selected = '';
								echo '<a href="/tag/'.rawurlencode($value).'"'.$selected.'>'.$value.'</a>';
							}
						?>
					</div>
				</div>
			</div>
			<div class="navhr"></div>
		</div>
	</div>

	<div id="main">
		<div class="container">
			<?php if(count($list) > 0): ?>
			<div class="list clr">
				<?php foreach($list as $k => $v): ?><div class="item<?php if($k % 4 == 0) echo ' item-left'; ?>">
					<div class="item-view">
						<a href="/show/<?=$v['id'] ?>" target="_blank"><div class="thumbnail"><img src="<?=$v['image_v'] ?>"></div></a>
					</div>
					<div class="item-main">
						<h3><a href="/show/<?=$v['id'] ?>" target="_blank"><?php
							$tag = isset($tag) ? $tag : '';
							$r = array();
							foreach ($v['tags'] as $vv) {
								$r[] = str_replace($tag, '<span class="red">'.$tag.'</span>', $vv);
							}
							echo implode(' ', $r);
						?></a></h3>
						<p>
							<a href="/download?n=<?php echo implode('.', $v['tags']); ?>&f=<?=$v['src'] ?>" target="_blank" class="item-link">下载</a><a href="<?=$v['image'] ?>" target="_blank" class="item-link">大图</a>
							<span>类型：<?=$v['type'] ?></span>&nbsp;&nbsp;<span>时间：<?=date('n-d',$v['time']) ?></span>
						</p>
					</div>
				</div><?php endforeach; ?>
			</div>
			<div class="pager clr"><?=$pager ?></div>
			<?php else: ?>
			<div class="empty">Sorry~ 没有找到符合要求的图片...</div>
			<?php endif; ?>
		</div>
	</div>

	<?php $this->load->view('footer',array());?>

</body>
</html>