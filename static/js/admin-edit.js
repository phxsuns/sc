$(function(){

	$('#btnSave').bind('click',function(){
		var $me = $(this);
		var id = $me.attr('data-id');
		var tags = $('#inputTags').val();
		var intro = $('#inputIntro').val();
		$('#mask').fadeIn();
		$('#popLoading').fadeIn();
		$.post('/api/post_edit',{
			id:id,
			tags:tags,
			intro:intro
		},function(data){
			if(data.status == 'ok'){
				$('#popLoading').hide();
				$('#popSaveOk').fadeIn();
			}else{
				alert('服务器错误');
				$('#mask').hide();
				$('#popLoading').hide();
			}

		},'json');
	});

	$('#btnSaveOk').bind('click',function(e){
		e.preventDefault();
		$('#mask').fadeOut();
		$('#popSaveOk').fadeOut();
	});

});

//标签优化
(function(){

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

	$(function(){
		formatTags($('.h-tags'));
	});

})();

$(function(){
	$('a.tag-value').click(function(e){
		e.preventDefault();
		var v = $(this).html();
		$('.h-tagslist-add input').val(v);
		$('.h-tagslist-add a').trigger('click');
	});
})