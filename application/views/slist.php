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
	<script src="/static/js/lazyload.js"></script>
	<script src="/static/js/global.js"></script>
	<script src="/static/js/slist.js"></script>
</head>
<body>

	<?php $this->load->view('header',array());?>

	<div id="nav">
		<div class="container">
			<div class="navline"></div>
			<?php 
				$cat = isset($hot_tags) ? $hot_tags : array();
				$c = array('人物','静物','生活','艺术','自然','城市','旅游','动物','科技','食物','商务','职场','行为','中国',);
				$s = array('UED自拍','自购图片','淘女郎');
				$tags = isset($tags) ? $tags : array();
				$cat = array_diff($cat,$c + $s);
				$tc = array();$ts = array();$th= array();$cc = $ss = $hh = -1;
				foreach ($tags as $v) {
					$c_index = array_search($v,$c);
					if($c_index === false) $tc[] = $v;
					else $cc = $c_index;
					$s_index = array_search($v,$s);
					if($s_index === false) $ts[] = $v;
					else $ss = $s_index;
					$h_index = array_search($v,$cat);
					if($h_index === false) $th[] = $v;
					else $hh = $h_index;
				}
			?>
			<?php //if($flag != 'search'): ?>
			<div class="navinfo">
				<div class="row">
					<div class="row-title">分类：</div>
					<div class="row-info">
						<?php
							$tmp_tc = implode(' ',$tc);
							$selected_tc = ($flag != 'search' && $cc < 0) ? ' class="selected"' : '';
							echo $tmp_tc ? '<a href="/tag/'.rawurlencode($tmp_tc).'"'.$selected_tc.'>全部</a>' : '<a href="/"'.$selected_tc.'>全部</a>';

							$tmp_tc = $tmp_tc ? ' '.$tmp_tc : '';
							foreach ($c as $value) {
								if(array_search($value,$tags) !== false) $selected = ' class="selected"';
								else $selected = '';
								echo '<a href="/tag/'.rawurlencode($value.$tmp_tc).'"'.$selected.'>'.$value.'</a>';
							}
						?>
					</div>
				</div>
				<div class="row">
					<div class="row-title">来源：</div>
					<div class="row-info">
						<?php
							$tmp_ts = implode(' ',$ts);
							$selected_ts = ($flag != 'search' && $ss < 0) ? ' class="selected"' : '';
							echo $tmp_ts ? '<a href="/tag/'.rawurlencode($tmp_ts).'"'.$selected_ts.'>全部</a>' : '<a href="/"'.$selected_ts.'>全部</a>';

							$tmp_ts = $tmp_ts ? ' '.$tmp_ts : '';
							foreach ($s as $value) {
								if(array_search($value,$tags) !== false) $selected = ' class="selected"';
								else $selected = '';
								echo '<a href="/tag/'.rawurlencode($value.$tmp_ts).'"'.$selected.'>'.$value.'</a>';
							}
						?>
					</div>
				</div>
				<div class="row">
					<div class="row-title">热门：</div>
					<div class="row-info">
						<?php
							$tmp_th = implode(' ',$th);
							$selected_th = ($flag != 'search' && $hh < 0) ? ' class="selected"' : '';
							echo $tmp_th ? '<a href="/tag/'.rawurlencode($tmp_th).'"'.$selected_th.'>全部</a>' : '<a href="/"'.$selected_th.'>全部</a>';

							$tmp_th = $tmp_th ? ' '.$tmp_th : '';
							$c_count = 0;
							foreach ($cat as $value) {
								if($c_count > 20) break;
								else $c_count++;

								if(array_search($value,$tags) !== false) $selected = ' class="selected"';
								else $selected = '';
								echo '<a href="/tag/'.rawurlencode($value.$tmp_th).'"'.$selected.'>'.$value.'</a>';
							}
						?>
					</div>
				</div>
			</div><?php //endif; ?>
			<div class="navhr"></div>
		</div>
	</div>

	<div id="main">
		<div class="container">
			<?php if($flag == 'search'): ?><div class="total">
				<span class="total-key"><em>“</em> <?=$key ?> <em>”</em></span>共找到 <?=$total ?> 个相关结果
			</div><?php endif; ?>
			<?php if(count($list) > 0): ?>
			<div class="list clr">
				<?php foreach($list as $k => $v): ?><div class="item<?php if($k % 4 == 0) echo ' item-left'; ?>">
					<div class="item-view">
						<a href="/show/<?=$v['id'] ?>" target="_blank"><div class="thumbnail"><img src="/static/images/icon-loading.gif" data-original="<?=$v['image_v'] ?>" class="lazy"></div></a>
					</div>
					<div class="item-main">
						<h3><a href="/show/<?=$v['id'] ?>" target="_blank"><?php
							$tags = isset($tags) ? $tags : '';
							$keys = isset($keys) ? $keys : array();
							$keys = $keys + $tags;
							$r = array();
							foreach ($v['tags'] as $vv) {
								foreach ($keys as $w) {
									$mr = preg_match('/'.$w.'/i',$vv,$m);
									if($mr) $vv = str_ireplace($w, '<span class="red">'.$m[0].'</span>', $vv);
								}
								$r[] = $vv;
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