<?php
class Login extends CI_Controller{
	
	function __construct(){
		parent::__construct();
		
		$this->load->library('session');
		$this->load->helper('url');
		
		
	}
	
	public function index(){
		
		$uid = $this->session->userdata('uid');
		$uname = $this->session->userdata('uname');
		
		if($uid && $uname){
			redirect('/admin/');
		}
		
		$this->load->view('login');
	}
	
	public function go(){
		
		$this->load->database();
		$this->load->model('User');
		$login_username = $this->input->post('username',TRUE);
		$login_password = $this->input->post('password',TRUE);
		$r = $this->User->user_login($login_username,$login_password);
		if($r){
			$this->session->set_userdata('uid',$r);
			$this->session->set_userdata('uname',$login_username);
			redirect('/admin/');
		}
		else {
			redirect('login');
		}
	}
	
	public function logout(){
		$this->session->set_userdata('uid');
		$this->session->set_userdata('uname');
		redirect('login');
	}
}