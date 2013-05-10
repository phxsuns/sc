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
				//$cat = isset($hot_tags) ? $hot_tags : array();
				$t_cat = array('人物','风景','动物','建筑','体育','美食','职场','商务','风土人情');
				$t_src = array('自拍','自画','购买');
				$t_type = array('照片','插画','创意','小培系列');
				$t_sex = array('男','女');
				$t_pnum = array('1人','2人','多人');
				$t_color = array('棕色','粉色','红色','黄色','绿色','蓝色','灰色');
				$tags = isset($tags) ? $tags : array();
				//$cat = array_diff($cat,$c + $s);
				$a_tc = array();$a_ts = array();$a_tt = array();$a_tse = array();$a_tpn = array();$a_tco = array();//$a_th = array();
				$cc = $ss = $hh = $tt = $se = $pn = $co = -1;
				foreach ($tags as $v) {
					$c_index = array_search($v,$t_cat);
					if($c_index === false) $a_tc[] = $v;
					else $cc = $c_index;
					$s_index = array_search($v,$t_src);
					if($s_index === false) $a_ts[] = $v;
					else $ss = $s_index;
					$t_index = array_search($v,$t_type);
					if($t_index === false) $a_tt[] = $v;
					else $tt = $t_index;
					$se_index = array_search($v,$t_sex);
					if($se_index === false) $a_tse[] = $v;
					else $se = $se_index;
					$pn_index = array_search($v,$t_pnum);
					if($pn_index === false) $a_tpn[] = $v;
					else $pn = $pn_index;
					$co_index = array_search($v,$t_color);
					if($co_index === false) $a_tco[] = $v;
					else $co = $co_index;
					// $h_index = array_search($v,$cat);
					// if($h_index === false) $a_th[] = $v;
					// else $hh = $h_index;
				}
			?>
			<?php //if($flag != 'search'): ?>
			<div class="navinfo">
				<div class="row">
					<div class="row-title">分类：</div>
					<div class="row-info">
						<?php
							$tmp_tc = implode(' ',$a_tc);
							$selected_tc = ($flag != 'search' && $cc < 0) ? ' class="selected"' : '';
							echo $tmp_tc ? '<a href="/tag/'.rawurlencode($tmp_tc).'"'.$selected_tc.'>全部</a>' : '<a href="/"'.$selected_tc.'>全部</a>';

							$tmp_tc = $tmp_tc ? ' '.$tmp_tc : '';
							foreach ($t_cat as $value) {
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
							$tmp_ts = implode(' ',$a_ts);
							$selected_ts = ($flag != 'search' && $ss < 0) ? ' class="selected"' : '';
							echo $tmp_ts ? '<a href="/tag/'.rawurlencode($tmp_ts).'"'.$selected_ts.'>全部</a>' : '<a href="/"'.$selected_ts.'>全部</a>';

							$tmp_ts = $tmp_ts ? ' '.$tmp_ts : '';
							foreach ($t_src as $value) {
								if(array_search($value,$tags) !== false) $selected = ' class="selected"';
								else $selected = '';
								echo '<a href="/tag/'.rawurlencode($value.$tmp_ts).'"'.$selected.'>'.$value.'</a>';
							}
						?>
					</div>
				</div>
				<div class="row">
					<div class="row-title">类型：</div>
					<div class="row-info">
						<?php
							$tmp_tt = implode(' ',$a_tt);
							$selected_tt = ($flag != 'search' && $tt < 0) ? ' class="selected"' : '';
							echo $tmp_tt ? '<a href="/tag/'.rawurlencode($tmp_tt).'"'.$selected_tt.'>全部</a>' : '<a href="/"'.$selected_tt.'>全部</a>';

							$tmp_tt = $tmp_tt ? ' '.$tmp_tt : '';
							foreach ($t_type as $value) {
								if(array_search($value,$tags) !== false) $selected = ' class="selected"';
								else $selected = '';
								echo '<a href="/tag/'.rawurlencode($value.$tmp_tt).'"'.$selected.'>'.$value.'</a>';
							}
						?>
					</div>
				</div>
				<div class="row">
					<div class="row-title">性别：</div>
					<div class="row-info">
						<?php
							$tmp_se = implode(' ',$a_tse);
							$selected_se = ($flag != 'search' && $se < 0) ? ' class="selected"' : '';
							echo $tmp_se ? '<a href="/tag/'.rawurlencode($tmp_se).'"'.$selected_se.'>全部</a>' : '<a href="/"'.$selected_se.'>全部</a>';

							$tmp_se = $tmp_se ? ' '.$tmp_se : '';
							foreach ($t_sex as $value) {
								if(array_search($value,$tags) !== false) $selected = ' class="selected"';
								else $selected = '';
								echo '<a href="/tag/'.rawurlencode($value.$tmp_se).'"'.$selected.'>'.$value.'</a>';
							}
						?>
					</div>
				</div>
				<div class="row">
					<div class="row-title">人数：</div>
					<div class="row-info">
						<?php
							$tmp_pn = implode(' ',$a_tpn);
							$selected_pn = ($flag != 'search' && $pn < 0) ? ' class="selected"' : '';
							echo $tmp_pn ? '<a href="/tag/'.rawurlencode($tmp_pn).'"'.$selected_pn.'>全部</a>' : '<a href="/"'.$selected_pn.'>全部</a>';

							$tmp_pn = $tmp_pn ? ' '.$tmp_pn : '';
							foreach ($t_pnum as $value) {
								if(array_search($value,$tags) !== false) $selected = ' class="selected"';
								else $selected = '';
								echo '<a href="/tag/'.rawurlencode($value.$tmp_pn).'"'.$selected.'>'.$value.'</a>';
							}
						?>
					</div>
				</div>
				<div class="row">
					<div class="row-title">颜色：</div>
					<div class="row-info">
						<?php
							$color = array('棕色'=>'#B29488','粉色'=>'#ED86AC','红色'=>'#ED8690','黄色'=>'#FFF082','绿色'=>'#D1F295','蓝色'=>'#86C8ED','灰色'=>'#E4E4E4');
							$color_selected = array('棕色'=>'#642910','粉色'=>'#DA0D59','红色'=>'#DA0D20','黄色'=>'#FFE005','绿色'=>'#A3E52B','蓝色'=>'#0D90DA','灰色'=>'#C9C9C9');

							$tmp_co = implode(' ',$a_tco);
							$selected_co= ($flag != 'search' && $co < 0) ? ' class="selected"' : '';
							echo $tmp_co ? '<a href="/tag/'.rawurlencode($tmp_co).'"'.$selected_co.'>全部</a>' : '<a href="/"'.$selected_co.'>全部</a>';

							$tmp_co = $tmp_co ? ' '.$tmp_co : '';
							foreach ($t_color as $value) {
								if(array_search($value,$tags) !== false){
									$selected = ' class="selected"';
									$itemcolor = $color_selected[$value];
								}else{
									$selected = '';
									$itemcolor = $color[$value];
								}
								echo '<a href="/tag/'.rawurlencode($value.$tmp_co).'"'.$selected.' title="'.$value.'"><span class="navinfo-color" style="background:'.$itemcolor.'"></span></a>';
							}
						?>
					</div>
				</div>
				<!--<div class="row">
					<div class="row-title">热门：</div>
					<div class="row-info">
						<?php
							/*$tmp_th = implode(' ',$a_th);
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
							}*/
						?>
					</div>
				</div>-->
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