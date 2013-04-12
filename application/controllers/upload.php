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
			$convert_result = true;
			if($ext == '.bmp' || $ext == '.psd'){
				$this->load->library('image_reader');
				$this->image_reader->init(array('src'=>$src,'dest'=>'./attach/temp/'.$name.'.jpg'));
				$convert_result = $this->image_reader->write();

				$ext = '.jpg';
				$src = './attach/temp/'.$name.$ext;
				$src_v = './attach/temp/'.$name.'_v'.$ext;
			}

			//压缩图片生成缩略图
			if($upload_result && $convert_result){
				$this->_create_view($src,$src_v);
			}
			
			//返回json
			
			$upload_result_str = ($upload_result && $convert_result) ? 'ok' : 'failed';
			
			$data = array();
			
			if($upload_result && $convert_result){
				$data['name'] = $name;
				$data['ext'] = $ext;
				$data['type'] = strtolower($filedata['file_ext']);
			}else{
				$data['err'] = $upload_error ? $upload_error : '格式转换失败';
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

	function remove(){
		$info = $this->input->get('info',TRUE);
		$tmp = explode(".",$info);
		$r = false;
		if($tmp[1] == 'psd' || $tmp[1] == 'bmp'){
			$r1 = unlink('./attach/temp/'.$tmp[0].'.'.$tmp[1]);
			$r2 = unlink('./attach/temp/'.$tmp[0].'.jpg');
			$r3 = unlink('./attach/temp/'.$tmp[0].'_v.jpg');
			$r = $r1 && $r2 && $r3;
		}else{
			$r1 = unlink('./attach/temp/'.$tmp[0].'.'.$tmp[1]);
			$r2 = unlink('./attach/temp/'.$tmp[0].'_v.'.$tmp[1]);
			$r = $r1 && $r2;
		}
		if($r) $returnAarray = array('status' => 'ok');
		else $returnAarray = array('status' => 'failed');
		print json_encode($returnAarray);
	}

	private function _create_view($src,$src_v){

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