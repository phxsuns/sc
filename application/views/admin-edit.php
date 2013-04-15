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