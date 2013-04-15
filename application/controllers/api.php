<?php
class Api extends CI_Controller{

	private $uid;
	private $uname;

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

	public function post_add(){
		$post_info = $this->input->post('info',TRUE);
		$post_ori = $this->input->post('ori',TRUE);
		$post_tag = $this->input->post('tag',TRUE);

		//根据索引匹配处理
		$r = true;
		for($i=0;$i<count($post_info);$i++){
			$row = array();
			$row['path'] = $this->_do_images($post_info[$i]);
			$row['title'] = $post_ori[$i];
			$row['intro'] = $post_ori[$i];
			$row['user'] = $this->uid;
			$row['attach'] = $post_info[$i];
			$row['tags'] = $post_tag[$i];
			$result = $this->Post->post_add($row);
			if(!$result) $r = false;
		}

		if($r) $returnAarray = array('status' => 'ok');
		else $returnAarray = array('status' => 'failed');
		print json_encode($returnAarray);

	}

	public function post_del(){
		$id = (int) $this->input->get('id',TRUE);
		$r = false;
		if($id){
			$detail = $this->Post->show_detail($id);
			if(count($detail) > 0){
				$this->_del_images($detail["attach_path"],$detail["attach_name"],$detail["attach_type"]);
				$r = $this->Post->post_del($id);
			}
		}
		if($r) $returnAarray = array('status' => 'ok');
		else $returnAarray = array('status' => 'failed');
		print json_encode($returnAarray);
		
	}

	public function post_edit(){
		$post_id = $this->input->post('id',TRUE);
		$post_tags = $this->input->post('tags',TRUE);
		$post_intro = $this->input->post('intro',TRUE);

		$data = array(
			'tag' => $post_tags,
			'intro' => $post_intro
		);
		$r = $this->Post->post_edit($post_id,$data);

		if($r) $returnAarray = array('status' => 'ok');
		else $returnAarray = array('status' => 'failed');
		print json_encode($returnAarray);
	}

	//添加时对图片处理
	private function _do_images($file){
		
		//文件名和扩展名分离
		$file_name = substr($file, 0, 32);
		$file_ext = substr($file, 33, 3);
		
		//处理目标路径
		$this->load->helper('date');
		$date_to_dir = mdate('%Y%m');
		$path = './attach/'.$date_to_dir.'/';
		if(!is_dir($path)){
		   mkdir($path);
		}

		//移动文件
		//源文件
		rename('./attach/temp/'.$file, $path.$file);
		//预览文件
		if($file_ext == 'jpg' || $file_ext == 'png' || $file_ext == 'gif'){
			$file_v = $file_name.'_v.'.$file_ext;
			rename('./attach/temp/'.$file_v, $path.$file_v);
			$file_ori_view = $file;
			$file_d = $file_name.'_d.'.$file_ext;
		}else{//生成jpg后的文件
			$file_jpg = $file_name.'.jpg';
			$file_jpg_v = $file_name.'_v.jpg';
			rename('./attach/temp/'.$file_jpg, $path.$file_jpg);
			rename('./attach/temp/'.$file_jpg_v, $path.$file_jpg_v);
			$file_ori_view = $file_jpg;
			$file_d = $file_name.'_d.jpg';
		}
		
		
		//detail图先判断大小
		$this->load->helper('common');
		$size = get_img_size($path.$file_ori_view);

		if($size['width'] > 980){
			$this->load->library('image_lib');	
			$config['image_library'] = 'gd2';
			$config['width'] = 980;
			$config['master_dim'] = 'width';
			$config['maintain_ratio'] = TRUE;
			$config['height'] = $size['height'];//必须设置，否则压缩异常
			$config['source_image'] = $path.$file_ori_view;
			$config['new_image'] = $path.$file_d;
			$this->image_lib->initialize($config);
			$this->image_lib->resize();
			$this->image_lib->clear();
		}else{
			copy($path.$file_ori_view,$path.$file_d);
		}
		
		return $date_to_dir;
	}

	private function _del_images($path,$file,$type){
		$path = './attach/'.$path.'/';
		$r = false;
		if($type == 'psd' || $type == 'bmp'){
			$r1 = @unlink($path.$file.'.'.$type);
			$r2 = @unlink($path.$file.'.jpg');
			$r3 = @unlink($path.$file.'_v.jpg');
			$r4 = @unlink($path.$file.'_d.jpg');
			$r = $r1 && $r2 && $r3 && $r4;
		}else{
			$r1 = @unlink($path.$file.'.'.$type);
			$r2 = @unlink($path.$file.'_v.'.$type);
			$r3 = @unlink($path.$file.'_d.'.$type);
			$r = $r1 && $r2 && $r3;
		}
		return $r;
	}

}