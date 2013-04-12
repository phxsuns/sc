<?php
class Api extends CI_Controller{

	function __construct(){
		parent::__construct();
		
		$this->load->library('session');
		$this->load->helper('url');
		
		$this->uid = $this->session->userdata('uid');
		$this->uname = $this->session->userdata('uname');
		
		if(!$this->uid || !$this->uname){
			redirect('/login/');
		}

		$this->load->database();	
		$this->load->model('Post');
	}

	public function index(){
		redirect('/');
	}

}