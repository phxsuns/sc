<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page{
	
	var $base_url		= ''; //当前路径
	var $total_rows 	= 0; //总行数
	var $per_page 		= 0; //每页行数
	
	var $uri_segment 	= 3;//url第几段开始检测页码
	
	var $num_pn			= 2;//页码数字幅度
	var $page_class 	= 'pagination';//组件输出时类名
	var $prev_class		= 'prev';
	var $next_class		= 'next';
	var $num_class		= 'num';
	var $dot_class		= 'dot';
	
	public function __construct($params = array()){
		if (count($params) > 0){
			$this->init($params);
		}
	}
	
	public function init($params = array()){
		if (count($params) > 0){
			foreach ($params as $key => $val){
				if (isset($this->$key)){
					$this->$key = $val;
				}
			}
		}		
	}
	
	public function get_cur_page(){
		$CI =& get_instance();
		
		$uri = $CI->uri->uri_to_assoc($this->uri_segment);
		return isset($uri['page']) ? (int) $uri['page'] : 1;
	}
	
	public function get_total_pages(){
		return ceil($this->total_rows / $this->per_page);
	}
	
	public function is_exist_page(){
		$cur_page = $this->get_cur_page();
		$total_pages = $this->get_total_pages();
		if($cur_page < 1 || $cur_page > $total_pages) return false;
		else return true;
	}
	
	public function output(){
		$CI =& get_instance();
		//url分析
		$uri = $CI->uri->uri_to_assoc($this->uri_segment);
		
		//获取当前页码
		$cur_page = $this->get_cur_page();
		
		//获取总页码
		$total_pages = $this->get_total_pages();
		
		//数字页码
		$num = '';
		$num_start = $num_s = $cur_page - $this->num_pn;
		$num_end = $num_e = $cur_page + $this->num_pn;
		
		$num_ds = $num_start - 1;
		$num_de = $total_pages - $num_end;
		if($num_ds < 0 && $num_ds + $num_de >= 0){
			$num_start = 1;
			$num_end = $num_end - $num_ds;
		}
		else if($num_ds < 0 && $num_ds + $num_de < 0){
			$num_start = 1;
			$num_end = $total_pages;
		}
		else if($num_de < 0 && $num_ds + $num_de >= 0){
			$num_end = $total_pages;
			$num_start = $num_start + $num_de;
		}
		else if($num_de < 0 && $num_ds + $num_de < 0){
			$num_end = $total_pages;
			$num_start = 1;
		}
			
		for($i = $num_start; $i <= $num_end; $i++){
			if($i == $cur_page){
				$num .= '<span class="'.$this->num_class.'">'.$i.'</span>';
			}else{
				$uri['page'] = $i;
				$num_url = $CI->uri->assoc_to_uri($uri);
				$num .= '<a class="'.$this->num_class.'" href="'.$this->base_url.$num_url.'">'.$i.'</a>';
			}
		}
		
		$num_start_end = $this->num_pn * 2 + 1;
		if($num_start > 2){
			$num_prevs = ($cur_page - $num_start_end >= $this->num_pn + 1) ? $cur_page - $num_start_end : $this->num_pn + 1;
			$uri['page'] = $num_prevs;
			$num_prevs_url = $CI->uri->assoc_to_uri($uri);
			$num = '<a class="'.$this->dot_class.'" href="'.$this->base_url.$num_prevs_url.'">…</a>'.$num;
		}
		if($num_end < $total_pages - 1){
			$num_nexts = ($cur_page + $num_start_end <= $total_pages - $this->num_pn) ? $cur_page + $num_start_end : $total_pages - $this->num_pn;
			$uri['page'] = $num_nexts;
			$num_nexts_url = $CI->uri->assoc_to_uri($uri);
			$num = $num.'<a class="'.$this->dot_class.'" href="'.$this->base_url.$num_nexts_url.'">…</a>';
		}
		if($num_start != 1){
			$uri['page'] = 1;
			$num_url = $CI->uri->assoc_to_uri($uri);
			$num = '<a class="'.$this->num_class.'" href="'.$this->base_url.$num_url.'">1</a>'.$num;
		}
		if($num_end != $total_pages){
			$uri['page'] = $total_pages;
			$num_url = $CI->uri->assoc_to_uri($uri);
			$num = $num.'<a class="'.$this->num_class.'" href="'.$this->base_url.$num_url.'">'.$total_pages.'</a>';
		}
		
		//上下页
		$num_prev = $cur_page - 1;
		$uri['page'] = $num_prev;
		$url_prev = $CI->uri->assoc_to_uri($uri);
		
		$num_next = $cur_page + 1;
		$uri['page'] = $num_next;
		$url_next = $CI->uri->assoc_to_uri($uri);
		
		$prev = '<a class="'.$this->prev_class.'" href="'.$this->base_url.$url_prev.'">上一页</a>';
		$next = '<a class="'.$this->next_class.'" href="'.$this->base_url.$url_next.'">下一页</a>';
		if($cur_page == 1) $prev = '<span class="'.$this->prev_class.'">上一页</span>';
		if($cur_page == $total_pages) $next = '<span class="'.$this->next_class.'">下一页</span>';
		
		$rt = $prev.$num.$next;
		$rt = '<div class="'.$this->page_class.'">'.$rt.'</div>';
		return $rt;
	}
}