$(function(){
	
	//搜索框
	$('#search .searchtxt').bind({
		'focus': function(){
			if($(this).val() == '') $(this).animate({'width':280});
		},
		'blur': function(){
			if($(this).val() == '') $(this).animate({'width':150});
			else $(this).width(280);
		}
	}).trigger('blur');

	//搜索
	$('#search').submit(function(e){
		e.preventDefault();
		//URL跳转
		var key = $(this).find('.searchtxt').val();
		var url = key ? '/search/' + encodeURIComponent(key) : '/';
		window.location.href = url;
	});

	//浏览器兼容性
	//IE678，提示
	if (!$.support.leadingWhitespace){
		$('<div class="container" style="text-align:center;border:1px solid #FFD1BF;background-color: #FFEBE2;color: #9B0013;padding:5px;">您正在使用的是非标准浏览器。推荐使用Chrome和FireFox访问本站。</div>').prependTo($('body')).hide().slideDown();
	}


});