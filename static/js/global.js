$(function(){
	
	//搜索框
	$('#search .searchtxt').bind({
		'focus': function(){
			if($(this).val() == '') $(this).animate({'width':280});
		},
		'blur': function(){
			if($(this).val() == '') $(this).animate({'width':150});
		}
	});

	//搜索
	$('#search').submit(function(e){
		e.preventDefault();
		//URL跳转
		var key = $(this).find('.searchtxt').val();
		var url = '/search/' + encodeURIComponent(key);
		window.location.href = url;
	});

});