<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>素材库 - 编辑素材</title>
	<link href="/static/css/bootstrap.css" rel="stylesheet" />
	<link href="/static/css/admin.css" rel="stylesheet" />
	<script src="/static/js/jquery.js"></script>
	<script src="/static/js/admin-edit.js"></script>
</head>
<body id="admin">
	<div class="adminnav">
		<div class="adminnavbox">
			<ul class="navi clearfix">
				<li><a href="/">返回主站</a></li>
				<li><a href="/admin/add">添加素材</a></li>
				<li><a href="/login/logout">退出登录</a></li>
			</ul>
		</div>
	</div>
	<div class="adminbox">
		<div class="edit-table clearfix">
			<div class="thumbnail edit-image"><div class="thumbnailbox"><img src="<?=$image_v ?>"></div></div>
			<div class="edit-body">
				<div class="edit-row">
					<label class="edit-label" for="inputTitle">标题：</label>
					<div class="edit-control"><input type="text" id="inputTitle" class="input-xxlarge" disabled="disabled" value="<?=$title ?>"></div>
				</div>
				<div class="edit-row">
					<label class="edit-label" for="inputTags">可选标签：</label>
					<div class="edit-control">
						<div class="tag-box">
							<div class="tag-row"><label class="tag-type">分类：</label>
								<a class="tag-value">人物</a>
								<a class="tag-value">静物</a>
								<a class="tag-value">风景</a>
								<a class="tag-value">动物</a>
								<a class="tag-value">植物</a>
								<a class="tag-value">建筑</a>
								<a class="tag-value">体育</a>
								<a class="tag-value">美食</a>
								<a class="tag-value">职场</a>
								<a class="tag-value">商务</a>
							</div>
							<div class="tag-row"><label class="tag-type">来源：</label>
								<a class="tag-value">自拍</a>
								<a class="tag-value">自画</a>
								<a class="tag-value">购买</a>
							</div>
							<div class="tag-row"><label class="tag-type">类型：</label>
								<a class="tag-value">照片</a>
								<a class="tag-value">插画</a>
								<a class="tag-value">创意</a>
								<a class="tag-value">小培系列</a>
								<a class="tag-value">淘公仔</a>
							</div>
							<div class="tag-row"><label class="tag-type">性别：</label>
								<a class="tag-value">男</a>
								<a class="tag-value">女</a>
							</div>
							<div class="tag-row"><label class="tag-type">人数：</label>
								<a class="tag-value">1人</a>
								<a class="tag-value">2人</a>
								<a class="tag-value">多人</a>
							</div>
							<div class="tag-row"><label class="tag-type">颜色：</label>
								<a class="tag-value tag-color" title="棕色" style="background:#B29488;">棕色</a>
								<a class="tag-value tag-color" title="粉色" style="background:#ED86AC;">粉色</a>
								<a class="tag-value tag-color" title="红色"style="background:#ED8690;">红色</a>
								<a class="tag-value tag-color" title="黄色"style="background:#FFF082;">黄色</a>
								<a class="tag-value tag-color" title="绿色"style="background:#D1F295;">绿色</a>
								<a class="tag-value tag-color" title="蓝色"style="background:#86C8ED;">蓝色</a>
								<a class="tag-value tag-color" title="灰色"style="background:#E4E4E4;">灰色</a>
							</div>
						</div>
					</div>
				</div>
				<div class="edit-row">
					<label class="edit-label" for="inputTags">标签：</label>
					<div class="edit-control">
						<input type="text" id="inputTags" class="input-xxlarge h-tags" value="<?=$tag ?>">
					</div>
				</div>
				<div class="edit-row">
					<label class="edit-label" for="inputIntro">说明：</label>
					<div class="edit-control"><textarea id="inputIntro" class="input-xxlarge"><?=$intro ?></textarea></div>
				</div>
			</div>
		</div>
		<div class="edit-save"><button class="btn btn-primary" type="button" id="btnSave" data-id="<?=$id ?>">保存修改</button></div>
	</div>

	<div id="mask" class="mask"></div>

	<div class="modal edit-success" id="popSaveOk">
		<div class="modal-header">
			<h3>素材保存成功</h3>
		</div>
		<div class="modal-body">
			<p>您已经成功修改了素材信息</p>
			<p>接着您打算？</p>
		</div>
		<div class="modal-footer">
			<a href="/show/<?=$id ?>" class="btn">返回查看</a>
    		<a href="#" class="btn btn-primary" id="btnSaveOk">继续修改</a>
		</div>
	</div>

	<div class="modal edit-loading" id="popLoading">
		<div class="modal-body">
			<div class="loading">处理中...</div>
		</div>
	</div>

</body>
</html>