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