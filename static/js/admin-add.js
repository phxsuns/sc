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
			if(data.status == 'ok'){
				var d = data.data;
				var html = '<tr data-info="'+d.name + d.type+'" data-ori="'+data.name+'">\
								<td><div class="thumbnail"><div class="thumbnailbox"><img src="/attach/temp/'+d.name+'_v' + d.ext+'"></div></div></td>\
								<td>\
									<input type="text" class="input-xxlarge input-tag h-tags">\
								</td>\
								<td><button class="btn btn-small btn-danger btn-rm" type="button">移除</button></td>\
							</tr>';
				var $html = $(html);
				$html.appendTo($('#fileList table tbody'));
				formatTags($html.find('.h-tags'));
			}
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
	if($('#fileList tbody tr')[0]) $('#fileList').show();
	$('#mask').fadeOut();
	$('#popLoading').fadeOut();
}


//标签优化
var formatTags = function(dom){
	var $inputTags = dom.hide();
	var $ulTags = $('<ul class="h-tagslist clearfix"><li class="h-tagslist-add"><input type="text"><a href="#">+</a></li></ul>').insertAfter($inputTags);

	var taglist = $inputTags.val().split(',');
	for(var i = 0; i < taglist.length ; i++){
		if(taglist[taglist.length - 1 - i]) $('<li><span>'+taglist[taglist.length - 1 - i]+'</span><a href="#">×</a></li>').prependTo($ulTags);
	}

	//同步到文本框
	$ulTags.bind('sync',function(){
		var val = '';
		$(this).find('li span').each(function(){
			var txt = $(this).text();
			val += val ? ',' : '';
			if(txt) val += txt;
		});
		$inputTags.val(val);
	});

	//加减事件
	$ulTags.delegate('a','click',function(e){
		e.preventDefault();
		$me = $(this);
		if($me.parent().hasClass('h-tagslist-add')){
			var $input = $me.prev();
			var val = $input.val();
			val = val.replace(/(^\s*)|(\s*$)/g, "").replace(',',' ');
			if(val){
				$me.parent().before('<li><span>'+val+'</span><a href="#">×</a></li>');
				$input.val('');
			}
		}else{
			$me.parent().remove();
		}
		$ulTags.trigger('sync');
	});

	//回车事件
	$ulTags.find('.h-tagslist-add input').bind('keyup',function(e){
		if(e.keyCode == 13) $(this).next().trigger('click');
	});

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
