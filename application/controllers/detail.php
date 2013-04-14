<?php
class Detail extends CI_Controller{

	private $login = false;

	function __construct(){
		parent::__construct();
		$this->load->helper('url');	
		$this->load->database();
		$this->load->model('Post');

		$this->load->library('session');
		
		$uid = $this->session->userdata('uid');
		$uname = $this->session->userdata('uname');

		if($uid && $uname){
			$this->login = true;
		}
	}

	//首页
	function index(){
		redirect('/');
	}

	//显示Detail页
	function show(){
		$id =  (int) $this->uri->segment(2);
		if($id <= 0) redirect('/');

		$data = array();
		$data["id"] = $id;


		//读取信息
		$detail = $this->Post->show_detail($id);
		
		if(!count($detail)) redirect('/');

		$type = $detail['attach_type'];
		$ext = ($type == 'jpg' || $type == 'gif' || $type == 'png') ? $type : 'jpg';
		$filename = $detail['attach_name'];
		$path = '/attach/'.$detail['attach_path'].'/';

		$data["type"] = $type;
		$data["view"] = $detail['post_view'];
		$data["image_d"] = $path.$filename.'_d.'.$ext;
		$data["image"] = $path.$filename.'.'.$ext;
		$data["src"] = $path.$filename.'.'.$type;
		$data["intro"] = $detail['post_intro'];
		$data["tags"] = explode(',', $detail['tags']);
		$data["date"] = date('Y-n-d',$detail['post_date']);
		$data["title"] = $detail['post_title'];

		//登录状态
		$data["login"] = $this->login;

		//访问统计
		$this->Post->set_view($id);

		$this->load->view('detail',$data);
	}

}