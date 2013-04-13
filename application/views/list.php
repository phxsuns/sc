<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>素材库 - 阿里学院UED</title>

    <link rel="shortcut icon" type="image/x-icon" href="/static/favicon.ico">
    <link rel="icon" type="image/x-icon" href="/static/favicon.ico">

    <link rel="stylesheet" href="/static/css/common.css" type="text/css">
    <link rel="stylesheet" href="/static/css/global.css" type="text/css">
    <link rel="stylesheet" href="/static/css/list.css" type="text/css">
    <script src="/static/js/jquery.js"></script>
    <script src="/static/js/global.js"></script>
</head>
<body>

	<?php $this->load->view('header',array('key'=>isset($key) ? $key : ''));?>

	<div id="nav">
		<div class="container">
			<div class="navline"></div>
			<div class="navinfo">
				<div class="row">
					<div class="row-title">热门标签：</div>
					<div class="row-info">
						<?php
							$cat = array('古典','可爱','手绘','时尚','学院','简约','节日庆典','炫酷','非主流');
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
			<?php if($flag == 'search'): ?><div class="total">
				<span class="total-key"><em>“</em> <?=$key ?> <em>”</em></span>共找到 <?=$total ?> 个相关结果
			</div><?php endif; ?>
			<?php if(count($list) > 0): ?>
			<div class="list">
				<?php foreach($list as $v): ?><div class="item">
					<div class="item-view">
						<a href="/show/<?=$v['id'] ?>"><div class="thumbnail"><img src="<?=$v['image_v'] ?>"></div></a>
					</div>
					<div class="item-btn">
						<a class="big-btn" href="/download?f=<?=$v['src'] ?>" target="_blank"><i class="icon icon-download"></i>源文件下载</a>
						<a class="big-btn" href="<?=$v['image'] ?>" target="_blank"><i class="icon icon-src"></i>大图浏览</a>
					</div>
					<div class="item-main">
						<h3><a href="/show/<?=$v['id'] ?>"><?php
							$tag = isset($tag) ? $tag : '';
							$keys = isset($keys) ? $keys : array();
							$keys[] = $tag;
							$r = array();
							foreach ($v['tags'] as $vv) {
								foreach ($keys as $w) {
									$vv = str_replace($w, '<span class="red">'.$w.'</span>', $vv);
								}
								$r[] = $vv;
							}
							echo implode(' ', $r);
						?></a></h3>
						<p>素材类型：<?=$v['type'] ?></p>
						<p>入库日期：<?=$v['date'] ?></p>
						<p>浏览次数：<?=$v['view'] ?></p>
						<p>详细描述：<?=$v['intro'] ?></p>
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