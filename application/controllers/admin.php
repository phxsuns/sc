<?php
class Admin extends CI_Controller{
	private $uname;
	private $uid;
	
	function __construct(){
		parent::__construct();
		
		$this->load->library('session');
		$this->load->helper('url');
		
		$this->uid = $this->session->userdata('uid');
		$this->uname = $this->session->userdata('uname');
		
		if(!$this->uid || !$this->uname){
			redirect('/login/');
		}
	}

	//初始页面，快速进入
	function index(){
		redirect('/admin/add/');
	}

	//添加功能单独页面
	function add(){
		$data = array();
		$this->load->view('admin-add',$data);
	}

}