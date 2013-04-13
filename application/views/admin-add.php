<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>素材库 - 添加素材</title>
	<link href="/static/css/bootstrap.css" rel="stylesheet" />
	<link href="/static/css/admin.css" rel="stylesheet" />
	<script src="/static/js/jquery.js"></script>
	<script src="/static/js/admin-add.js"></script>
</head>
<body id="admin">
	<div class="adminnav">
		<div class="adminnavbox">
			<ul class="navi clearfix">
				<li><a href="/">返回主站</a></li>
				<li class="active"><a href="/admin/add">添加素材</a></li>
				<li><a href="/login/logout">退出登录</a></li>
			</ul>
		</div>
	</div>
	<div class="adminbox">
		<div class="add-action">
			<button class="btn btn-large btn-primary" type="button" id="btnAdd" onclick="$('#fileAdd').click();">添加素材</button>
			<input type="file" id="fileAdd" style="visibility: hidden;" multiple>
		</div>
		<div class="add-list" id="fileList" style="display:none;">
			<table class="table table-hover">
				<thead>
					<tr>
						<th width="270">预览</th>
						<th>标签设定（请用英文逗号隔开）</th>
						<th width="100">&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					<!-- <tr>
						<td><img src="http://ddt.xhao.org/attach/temp/948cbb915c590396cbf9e378014b623b_v.JPG" width="220"></td>
						<td>
							<input type="text" placeholder="" class="input-xxlarge" name="0_tag">
							<input type="hidden" class="input-xxlarge" name="0_img" value="948cbb915c590396cbf9e378014b623b.jpg">
						</td>
						<td><button class="btn btn-small btn-danger btn-rm" type="button">移除</button></td>
					</tr>
					<tr>
						<td><img src="http://ddt.xhao.org/attach/temp/948cbb915c590396cbf9e378014b623b_v.JPG" width="220"></td>
						<td>
							<input type="text" placeholder="" class="input-xxlarge" name="0_tag">
							<input type="hidden" class="input-xxlarge" name="0_img" value="948cbb915c590396cbf9e378014b623b.jpg">
						</td>
						<td><button class="btn btn-small btn-danger btn-rm" type="button">移除</button></td>
					</tr>
					<tr>
						<td><img src="http://ddt.xhao.org/attach/temp/948cbb915c590396cbf9e378014b623b_v.JPG" width="220"></td>
						<td>
							<input type="text" placeholder="" class="input-xxlarge" name="0_tag">
							<input type="hidden" class="input-xxlarge" name="0_img" value="948cbb915c590396cbf9e378014b623b.jpg">
						</td>
						<td><button class="btn btn-small btn-danger btn-rm" type="button">移除</button></td>
					</tr> -->
				</tbody>
			</table>
			<div class="add-save">
				<button class="btn btn-primary" type="button" id="btnSave">保存到素材库</button>
			</div>
		</div>
		
	</div>

	<div id="mask" class="mask"></div>

	<div class="modal add-success" id="popSaveOk">
		<div class="modal-header">
			<h3>素材保存成功</h3>
		</div>
		<div class="modal-body">
			<p>本次新增素材 <span class="label label-important num-save">0</span> 个</p>
			<p>接着您打算？</p>
		</div>
		<div class="modal-footer">
			<a href="/" class="btn">返回主站查看</a>
    		<a href="#" class="btn btn-primary" id="btnSaveOk">继续添加素材</a>
		</div>
	</div>

	<div class="modal add-loading" id="popLoading">
		<div class="modal-body">
			<div class="loading">处理中(0/0)</div>
		</div>
	</div>

</body>
</html>