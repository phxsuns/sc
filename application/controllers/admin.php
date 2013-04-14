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

	//编辑页面
	function edit(){
		$id =  (int) $this->uri->segment(3);
		if($id <= 0) redirect('/admin');

		$data = array();

		$this->load->database();	
		$this->load->model('Post');

		$data["id"] = $id;

		//读取信息
		$detail = $this->Post->show_detail($id);
		
		if(!count($detail)) redirect('/');

		$type = $detail['attach_type'];
		$ext = ($type == 'jpg' || $type == 'gif' || $type == 'png') ? $type : 'jpg';
		$filename = $detail['attach_name'];
		$path = '/attach/'.$detail['attach_path'].'/';

		$data["image_v"] = $path.$filename.'_v.'.$ext;
		$data["intro"] = $detail['post_intro'];
		$data["tag"] = $detail['tags'];
		$data["tags"] = explode(',', $detail['tags']);
		$data["title"] = $detail['post_title'];

		$this->load->view('admin-edit',$data);
	}

}