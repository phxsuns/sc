$(function(){

	$('.btn-del').on('click',function(e){
		e.preventDefault();
		var $me = $(this);
		var r = confirm('是否要删除？');
		if(r){
			var id = $me.attr('data-id');
			$.get('/api/post_del/',{id:id},function(data){
				if(data && data.status == 'ok'){
					$me.parents('.item').slideUp();
				}
			},'json');
		}
	});

});