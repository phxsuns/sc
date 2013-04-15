<?php
class Sc extends CI_Controller{

	private $per_page = 20;//每页条数

	private $login = false;

	function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->model('Post');
		$this->load->library('page');

		$this->load->library('session');
		
		$uid = $this->session->userdata('uid');
		$uname = $this->session->userdata('uname');

		if($uid && $uname){
			$this->login = true;
		}
	}

	//首页
	function index(){
		$data = array();
		$data["flag"] = 'home';

		//获取列表数据
		$this->page->init(array('uri_segment'=>1));
		$cur_page = $this->page->get_cur_page();//当前页码

		$total_rows = $this->Post->show_num('');//获得总条数
		$list = $this->Post->show_list('',0,0,($cur_page - 1) * $this->per_page,$this->per_page);//获得数据

		//处理列表数据
		$data["list"] = $this->_do_list($list);

		//生成页码
		$config['base_url'] = '/';
		$config['total_rows'] = $total_rows;
		$config['per_page'] = $this->per_page;
		$config['num_pn'] = 5;
		$this->page->init($config);
		$pagination = $this->page->output();

		$data["pager"] = $pagination;

		//热门标签
		$data["hot_tags"] = $this->_get_hot_tags();

		//登录状态
		$data["login"] = $this->login;

		$this->load->view('slist',$data);
	}

	//搜索页
	function search(){
		$key = rawurldecode($this->uri->segment(2));
		$data = array();
		$data["flag"] = 'search';
		$data["key"] = $key;

		$keys = explode(' ', $key);
		$k = array();
		foreach ($keys as $value) {
			if($value != '') $k[] = $value;
		}
		$data["keys"] = $k;

		//获取列表数据
		$this->page->init(array('uri_segment'=>3));
		$cur_page = $this->page->get_cur_page();//当前页码

		$total_rows = $this->Post->search_num($k);//获得总条数
		$list = $this->Post->search_list($k,($cur_page - 1) * $this->per_page,$this->per_page);//获得数据

		//处理列表数据
		$data["list"] = $this->_do_list($list);

		//生成页码
		$config['base_url'] = '/search/'.rawurlencode($key).'/';
		$config['total_rows'] = $total_rows;
		$config['per_page'] = $this->per_page;
		$config['num_pn'] = 5;
		$this->page->init($config);
		$pagination = $this->page->output();

		$data["pager"] = $pagination;

		$data["total"] = $total_rows;

		//热门标签
		$data["hot_tags"] = $this->_get_hot_tags();

		//登录状态
		$data["login"] = $this->login;

		$this->load->view('list',$data);
	}

	//分类索引
	function tag(){
		$tag = rawurldecode($this->uri->segment(2));
		$data = array();
		$data["flag"] = 'tag';
		$data["tag"] = $tag;

		//获取列表数据
		$this->page->init(array('uri_segment'=>3));
		$cur_page = $this->page->get_cur_page();//当前页码

		$total_rows = $this->Post->show_num($tag);//获得总条数
		$list = $this->Post->show_list($tag,0,0,($cur_page - 1) * $this->per_page,$this->per_page);//获得数据

		//处理列表数据
		$data["list"] = $this->_do_list($list);

		//生成页码
		$config['base_url'] = '/tag/'.rawurlencode($tag).'/';
		$config['total_rows'] = $total_rows;
		$config['per_page'] = $this->per_page;
		$config['num_pn'] = 5;
		$this->page->init($config);
		$pagination = $this->page->output();

		$data["pager"] = $pagination;

		//热门标签
		$data["hot_tags"] = $this->_get_hot_tags();

		//登录状态
		$data["login"] = $this->login;

		$this->load->view('slist',$data);
	}

	//处理列表数据
	private function _do_list($list){
		$outlist = array();
		foreach ($list as $v){
			$row = array();
			$row['id'] = $v['post_id'];
			$row['title'] = $v['post_title'];
			$row['intro'] = $v['post_intro'];
			$row['user'] = $v['user_name'];
			$row['date'] = date('Y-n-d',$v['post_date']);
			$row['time'] = $v['post_date'];
			$row['view'] = $v['post_view'];
			//$row['favo'] = $v['post_favo'];
			$row['tags'] = explode(',', $v['tags']);
			$row['type'] = $v['attach_type'];
			$ext = ($v['attach_type'] == 'jpg' || $v['attach_type'] == 'gif' || $v['attach_type'] == 'png') ? $v['attach_type'] : 'jpg';
			$row['image_v'] = '/attach/'.$v['attach_path'].'/'.$v['attach_name']."_v.".$ext;
			$row['image'] = '/attach/'.$v['attach_path'].'/'.$v['attach_name'].".".$ext;
			$row['src'] = '/attach/'.$v['attach_path'].'/'.$v['attach_name'].".".$v['attach_type'];
			$outlist[] = $row;
		}
		return $outlist;
	}

	//获取热门关标签
	private function _get_hot_tags(){
		$this->load->model('Tag');
		$tags_list = $this->Tag->hot_tags(20);
		$r = array();
		foreach ($tags_list as $v) {
			$r[] = $v['tag_name'];
		}
		return $r;
	}

}