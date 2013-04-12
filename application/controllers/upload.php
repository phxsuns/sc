<?php
class Upload extends CI_Controller{
	
	private $is_user = false;
	
	function __construct(){
		parent::__construct();
		
		$this->load->library('session');
		$this->load->helper('url');
		
		$uid = $this->session->userdata('uid');
		$uname = $this->session->userdata('uname');
		
		if($uid && $uname){
			$this->is_user = true;
		}
	}
	
	function index(){
		if($this->is_user){
			//接受上传的文件到临时文件夹
			$config = array();
			$config['upload_path'] = './attach/temp/';
			$config['allowed_types'] = 'gif|jpg|png|bmp|psd';
			//$config['max_size'] = '8000';
			$config['encrypt_name'] = TRUE;
			
			$this->load->library('upload', $config);
			$upload_result = $this->upload->do_upload('Filedata');
			$filedata = $this->upload->data();
			$upload_error = $this->upload->display_errors();

			$name = $filedata['raw_name'];
			$ext = strtolower($filedata['file_ext']);

			//可见图地址
			$src = './attach/temp/'.$filedata['file_name'];
			//预览图地址
			$src_v = './attach/temp/'.$name.'_v'.$ext;

			//处理bmp和psd
			if($ext == '.bmp' || $ext == '.psd'){
				$ext = '.jpg';
				$src = '';
			}

			//压缩图片生成缩略图
			if($upload_result){
				$this->_create_veiw($src,$src_v);
			}
			
			//返回json
			
			$upload_result_str = $upload_result ? 'ok' : 'failed';
			
			$data = array();
			
			if($upload_result){
				$data['name'] = $name;
				$data['ext'] = $ext;
				$data['type'] = strtolower($filedata['file_ext']);
			}else{
				$data['err'] = $upload_error;
			}
			
			$returnAarray = array(
								'status' => $upload_result_str,
								'data' => $data
							);
		}
		else{
			$data['err'] = 'Please Login';
			$returnAarray = array(
								'status' => 'failed',
								'data' => $data
							);
		}
		print json_encode($returnAarray);
	}

	private function _create_veiw($src,$src_v){

		$this->load->helper('common');

		$size = get_img_size($src);
		//设置大小
		if($size["width"] < 250 && $size["height"] < 250){
			copy($src, $src_v);
		}else{
			$config = array();
			$config['image_library'] = 'gd2';
			$config['source_image'] = $src;
			$config['width'] = 250;
			$config['height'] = 250;
			$config['maintain_ratio'] = TRUE;
			$config['create_thumb'] = TRUE;
			$config['thumb_marker'] = '_v';
			
			$this->load->library('image_lib', $config); 
			
			$this->image_lib->resize();
		}

	}
}