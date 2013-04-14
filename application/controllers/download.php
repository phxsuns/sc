<?php
class Download extends CI_Controller{

	function __construct(){
		parent::__construct();
	}

	function index(){
		$name = $this->input->get('n',TRUE);
		$file = $this->input->get('f',TRUE);

		$ext = substr($file, -3);
		
		$temp = explode('/', $file);
		$filename = $name ? $name.'.'.$ext : end($temp);

		$this->load->helper('download');
		$data = file_get_contents('.'.$file);
		force_download($filename, $data);

	}

}