<?php
class Sc extends CI_Controller{

	function __construct(){
		parent::__construct();
	}

	//首页
	function index(){
		$data = array();
		$this->load->view('list',$data);
	}

	//搜索页
	function search(){
		$v =  $this->uri->segment(2);
		// echo $v;
		// echo '___1';
		$data = array();
		$this->load->view('list',$data);
	}

	//分类索引
	function tag(){

	}

}