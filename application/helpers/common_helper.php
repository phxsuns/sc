<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//获得图片大小
if ( ! function_exists('get_img_size'))
{
	function get_img_size($url = '')
	{
		if(!$url) return array('width' => 0, 'height' => 0);
		
		$arr = getimagesize($url);
		return array(
			'width' => $arr[0],
			'height' => $arr[1]
		);
	}
}