(function(){

/* 上传逻辑 */
var upload = function(file,cb,ok){

	var xhr = new XMLHttpRequest();
	
	xhr.onload =  function(){
		if(xhr.readyState == 4 && xhr.status == 200){
			try{
				var r = $.parseJSON(xhr.responseText);//console.log(r);
				r.name = file.name;
				cb(r);
			}catch(e){
				console.log(xhr.responseText);
			}
			ok();
		}
		
	};
	
	xhr.upload.onprogress = function(e){
		//uploadProgress(e.loaded,e.total,id);
	};

	if(xhr.upload){
		xhr.open("POST", '/upload', true);
		var formData = new FormData();
		formData.append('Filedata', file);
		//formData.append('proc_params', param.replace('$format',imageType));
		var data = formData;	
		xhr.send(data);
	}

};

var doUpload = function(files){
	if(!files.length) return;
	var okcount = 0;
	$('#mask').fadeIn();
	$('#popLoading').fadeIn().find('.loading').html('处理中(0/'+files.length+')');
	for(var i=0;i<files.length;i++){
		upload(files[i],function(data){
			//console.log(data);
			var d = data.data;
			var html = '<tr data-info="'+d.name + d.type+'" data-ori="'+data.name+'">\
							<td><div class="thumbnail"><div class="thumbnailbox"><img src="/attach/temp/'+d.name+'_v' + d.ext+'"></div></div></td>\
							<td>\
								<input type="text" class="input-xxlarge input-tag">\
							</td>\
							<td><button class="btn btn-small btn-danger btn-rm" type="button">移除</button></td>\
						</tr>';
			$('#fileList table tbody').append(html);
		},function(){
			okcount++;
			loading(okcount,files.length);
			if(okcount == files.length) completeUpload(okcount);
		});
	}
};

var loading = function(n,total){
	//var per = (n * 100 / total ) | 0;
	$('#popLoading .loading').html('处理中('+n+'/'+total+')');
};

var completeUpload = function(n){
	$('#fileList').show();
	$('#mask').fadeOut();
	$('#popLoading').fadeOut();
}



//Dom Ready
$(function(){

	$('#fileAdd').bind('change',function(){
		doUpload(this.files);
	});

	//移除逻辑
	$('#fileList').delegate('.btn-rm','click',function(e){
		var $this = $(this);
		var $tr = $this.parents('tr');
		var info = $tr.attr('data-info');
		$('#mask').fadeIn();
		$('#popLoading').fadeIn().find('.loading').html('处理中...');
		$.get('/upload/remove',{info:info},function(data){
			if(data.status == 'ok'){
				$tr.remove()
				$('#mask').fadeOut();
				$('#popLoading').fadeOut();
				if(!$('#fileList tbody tr')[0]) $('#fileList').hide();
			}
		},'json');
	});


	//提交逻辑
	$('#btnSave').bind('click',function(){
		var infoList = [];
		var oriList = [];
		var tagList = [];
		$('#fileList tbody tr').each(function(){
			var $this = $(this);
			infoList.push($this.attr('data-info'));
			oriList.push($this.attr('data-ori'));
			tagList.push($this.find('.input-tag').val());
		});
		$('#mask').fadeIn();
		$('#popLoading').fadeIn().find('.loading').html('处理中...');
		$.post('/api/post_add',{info:infoList,ori:oriList,tag:tagList},function(data){
			if(data.status == 'ok'){			
				$('#popLoading').hide();
				$('#popSaveOk .num-save').text(infoList.length);
				$('#popSaveOk').fadeIn();
			}
		},'json');
	});

	$('#btnSaveOk').bind('click',function(e){
		e.preventDefault();
		$('#fileList tbody').html('');
		$('#fileList').hide();
		$('#mask').fadeOut();
		$('#popSaveOk').fadeOut();
	});

});



})();