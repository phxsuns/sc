<?php
class Detail extends CI_Controller{

	function __construct(){
		parent::__construct();
	}

	//首页
	function index(){
		echo '0';
	}

	//搜索页
	function show(){
		$v =  $this->uri->segment(2);
		echo $v;
		echo '___1';
	}

}