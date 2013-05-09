<?php
class Post extends CI_Model{
	
	function __construct(){
		parent::__construct();
	}
	//常规列表查询
	private function post_sql_str($tag, $uid, $order, $by, $from, $long){
		if($tag){
			$sql = "SELECT p.*,t.tag_name,pt.tags,a.attach_name,a.attach_type,a.attach_path,u.user_name FROM sc_tag t , (sc_post p LEFT JOIN sc_pt pt ON pt.pid = p.post_id LEFT JOIN sc_attach a ON a.attach_pid = p.post_id LEFT JOIN sc_user u ON u.user_id = p.post_user) WHERE t.tag_pid = p.post_id AND t.tag_name = ".$this->db->escape($tag);
		}else{
			$sql = "SELECT p.*,pt.tags,a.attach_name,a.attach_type,a.attach_path,u.user_name FROM sc_post p LEFT JOIN sc_pt pt ON pt.pid = p.post_id LEFT JOIN sc_attach a ON a.attach_pid = p.post_id LEFT JOIN sc_user u ON u.user_id = p.post_user WHERE 1=1";
		}
		if($uid) $sql .= " AND p.post_user =".$this->db->escape($uid);
		
		if($order){
			$sql_order = " ORDER BY ";
			$sql_by = $by ? "ASC" : "DESC";
			switch ($order){
				case 'date':
					$sql_order .= "p.post_date ";
					break;
				case 'favo':
					$sql_order .= "p.post_favo ";
					break;
				case 'view':
					$sql_order .= "p.post_view ";
					break;
				case 'id':
					$sql_order .= "p.post_id ";
					break;
				default:			
			}
			$sql .= $sql_order.$sql_by;
		}
		
		if($from){
			$sql .= " LIMIT ".$this->db->escape($from).",".$this->db->escape($long);
		}else if(!$from && $long > 0){
			$sql .= " LIMIT ".$this->db->escape($long);
		}
		return $sql;
	}
	
	public function post_get_list($tag = '', $uid = 0, $order = '' , $by = 0, $from = 0, $long = -1){
		$sql = $this->post_sql_str($tag, $uid, $order, $by, $from, $long);
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	
	public function post_get_count($tag = '', $uid = 0, $order = '', $by = 0, $from = 0, $long = -1){
		$sql = $this->post_sql_str($tag, $uid, $order, $by, $from, $long);
		$query = $this->db->query($sql);
		return $query->num_rows();
	}
	//目标查询
	public function post_get_detail($id){
		$sql = "SELECT p.*,pt.tags,a.attach_name,a.attach_type,a.attach_path,u.user_name,u.user_id FROM sc_post p LEFT JOIN sc_pt pt ON pt.pid = p.post_id LEFT JOIN sc_attach a ON a.attach_pid = p.post_id LEFT JOIN sc_user u ON u.user_id = p.post_user WHERE p.post_id=".$this->db->escape($id);
		$query = $this->db->query($sql);
		return $query->first_row('array');
	}
	//后台添加
	public function post_add($info){
		//插入主表数据处理
		$data = array(
			'post_title' => $info['title'],
			'post_intro' => $info['intro'],
			'post_user' => $info['user'],
			'post_date' => time()
		);
		$sql = $this->db->insert_string('sc_post',$data);
		$query = $this->db->query($sql);
		$result = $this->db->affected_rows($query);
		if(!$result) return false;
		$id = $this->db->insert_id($query);
		
		//插入附件表
		$data_attach = array(
			'attach_pid' => $id,
			'attach_name' => substr($info['attach'], 0, 32),
			'attach_type' => substr($info['attach'], 33, 3),
			'attach_path' => $info['path']
		);
		$sql = $this->db->insert_string('sc_attach',$data_attach);
		$query = $this->db->query($sql);
		$result = $this->db->affected_rows($query);
		if(!$result) return false;
		
		//处理tag

		$tag_list_temp = explode(',', $info['tags']);
		$tag_list = array();
		foreach ($tag_list_temp as $v){
			$v = rtrim(ltrim($v));
			if($v != '') $tag_list[] = $v;
		}
		if(count($tag_list)){

			//插入tag表
			$tag_list = array_unique($tag_list);
			$sql = $this->tag_insert_str($tag_list, $id);
			$query = $this->db->query($sql);
			$result = $this->db->affected_rows($query);
			if(!$result) return false;
		
		
			//插入关联表
			$data_tags = array(
				'pid' => $id,
				'tags' => implode(',', $tag_list)
			);
			$sql = $this->db->insert_string('sc_pt',$data_tags);
			$query = $this->db->query($sql);
			$result = $this->db->affected_rows($query);
			if(!$result) return false;
		}

		return true;
	}
	
	private function tag_insert_str($tag_list,$id){
		if(!count($tag_list)) return false;
		$sql = "INSERT sc_tag (tag_name,tag_pid) VALUES ";
		$flag = 0;
		foreach ($tag_list as $v){
			$sql .= $flag ? "," : "";
			$sql .= "(";
			$sql .= $this->db->escape($v);
			$sql .= ",";
			$sql .= $this->db->escape($id);
			$sql .= ")";
			$flag = 1;
		}
		return $sql;
	}
	
	//后台编辑
	public function post_edit($id,$info){
		$id = (int) $id;
		if(!$id) return false;
		
		//更新说明
		$data_post = array('post_intro' => $info['intro']);
		$data_post_where = 'post_id='.$id;
		$sql = $this->db->update_string('sc_post',$data_post,$data_post_where);
		$query = $this->db->query($sql);
		$result = $this->db->affected_rows($query);
		
		//更新标签
		$tags_temp = explode(',',$info['tag']);
		$tags = array();
		foreach ($tags_temp as $v){
			$v = rtrim(ltrim($v));
			if($v != '') $tags[] = $v;
		}

		if(count($tags)){
			$tags = array_unique($tags);
			$tags_ori = $this->tag_list_arr($id);
			$tags_ins = array_diff($tags, $tags_ori);
			$tags_del = array_diff($tags_ori, $tags);
			$r_ins = $this->tag_list_ins($tags_ins,$id);
			$r_del = $this->tag_list_del($tags_del,$id);
			//标签结果无法判定
			
			//处理tag更新关联表
			$data_tags = array('tags' => implode(',', $tags));
			$data_tags_where = "pid=".$this->db->escape($id);;
			$sql = $this->db->update_string('sc_pt',$data_tags,$data_tags_where);
			$query = $this->db->query($sql);
			$result = $this->db->affected_rows($query);
			//标签结果无法判定
		}
		
		return true;
	}
	
	private function tag_list_arr($id){
		$sql = "SELECT * FROM sc_tag WHERE tag_pid=".$this->db->escape($id);
		$query = $this->db->query($sql);
		$arr = array();
		$taglist = $query->result_array();
		foreach ($taglist as $a){
			$arr[] = $a['tag_name'];
		}
		return $arr;
	}
	private function tag_list_del($tags,$id){
		if(!count($tags)) return true;
		foreach ($tags as $k => $v) {
			$tags[$k] = $this->db->escape($v);
		}
		$tags_str = implode(',', $tags);
		$sql = "DELETE FROM sc_tag WHERE tag_pid=".$this->db->escape($id)." AND tag_name in(".$tags_str.")";
		$query = $this->db->query($sql);
		return $this->db->affected_rows($query);
	}
	private function tag_list_ins($tags,$id){
		if(!count($tags)) return true;
		$sql = $this->tag_insert_str($tags,$id);
		$query = $this->db->query($sql);
		return $this->db->affected_rows($query);
	}
	
	//后台删除
	public function post_del($id){
		$id = (int) $id;
		if(!$id) return false;
		
		$sql = array();
		$sql[] = "DELETE FROM sc_post WHERE post_id =".$id;
		$sql[] = "DELETE FROM sc_attach WHERE attach_pid=".$id;
		$sql[] = "DELETE FROM sc_tag WHERE tag_pid=".$id;
		$sql[] = "DELETE FROM sc_pt WHERE pid=".$id;
		
		$i = 0;
		$j = 0;
		foreach ($sql as $s){
			$query = $this->db->query($s);
			$result = $this->db->affected_rows($query);
			if(!$result) $i++;
			$j++;
		}
		
		if($i == $j) return false;
		
		return true;
	}
	
	//前台查询
	private function show_sql_str($tag,$start,$end,$from,$long){
		if($tag){
			$sql = "SELECT p.*,t.tag_name,pt.tags,a.attach_name,a.attach_type,a.attach_path,u.user_name FROM sc_tag t , (sc_post p LEFT JOIN sc_pt pt ON pt.pid = p.post_id LEFT JOIN sc_attach a ON a.attach_pid = p.post_id LEFT JOIN sc_user u ON u.user_id = p.post_user) WHERE t.tag_pid = p.post_id AND t.tag_name = ".$this->db->escape($tag);
		}else{
			$sql = "SELECT p.*,pt.tags,a.attach_name,a.attach_type,a.attach_path,u.user_name FROM sc_post p LEFT JOIN sc_pt pt ON pt.pid = p.post_id LEFT JOIN sc_attach a ON a.attach_pid = p.post_id LEFT JOIN sc_user u ON u.user_id = p.post_user WHERE 1=1";
		}
		if($start){
			$now = time();
			$start_time = $now - $start * 24 * 3600;
			$sql .= " AND p.post_date > ".$start_time;
			if($end){
				$end_time = $now - $end * 24 * 3600;
				$sql .= " AND p.post_date < ".$end_time;
			}
		}
		$sql .= ' ORDER BY p.post_date DESC';//排序简单处理
		if($from){
			$sql .= " LIMIT ".$this->db->escape($from).",".$this->db->escape($long);
		}else if(!$from && $long > 0){
			$sql .= " LIMIT ".$this->db->escape($long);
		}
		return $sql;
	}
	public function show_num($tag = '' ,$start = 0,$end = 0,$from = 0,$long = -1){
		$sql = $this->show_sql_str($tag, $start, $end, $from, $long);
		$query = $this->db->query($sql);
		return $query->num_rows();
	}
	public function show_list($tag = '' ,$start = 0,$end = 0,$from = 0,$long = -1){
		$sql = $this->show_sql_str($tag, $start, $end, $from, $long);
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	
	//detail查询
	public function show_detail($id = 0){
		if(!$id) return null;
		$sql = "SELECT p.*,pt.tags,a.attach_name,a.attach_type,a.attach_path,u.user_name FROM sc_post p LEFT JOIN sc_pt pt ON pt.pid = p.post_id LEFT JOIN sc_attach a ON a.attach_pid = p.post_id LEFT JOIN sc_user u ON u.user_id = p.post_user WHERE p.post_id=";
		$sql .= $this->db->escape($id);
		$query = $this->db->query($sql);
		return $query->row_array();
	}
	
	public function show_detail_prev($id = 0){
		if(!$id) return null;
		$sql = "SELECT p.*,pt.tags,a.attach_name,a.attach_type,a.attach_path,u.user_name FROM sc_post p LEFT JOIN sc_pt pt ON pt.pid = p.post_id LEFT JOIN sc_attach a ON a.attach_pid = p.post_id LEFT JOIN sc_user u ON u.user_id = p.post_user WHERE p.post_id<";
		$sql .= $this->db->escape($id);
		$sql .= " ORDER BY p.post_id DESC";
		$query = $this->db->query($sql);
		return $query->row_array();
	}
	
	public function show_detail_next($id = 0){
		if(!$id) return null;
		$sql = "SELECT p.*,pt.tags,a.attach_name,a.attach_type,a.attach_path,u.user_name FROM sc_post p LEFT JOIN sc_pt pt ON pt.pid = p.post_id LEFT JOIN sc_attach a ON a.attach_pid = p.post_id LEFT JOIN sc_user u ON u.user_id = p.post_user WHERE p.post_id>";
		$sql .= $this->db->escape($id);
		$sql .= " ORDER BY p.post_id ASC";
		$query = $this->db->query($sql);
		return $query->row_array();
	}
	public function show_detail_tags($id = 0){
		if(!$id) return null;
		$sql = "SELECT * FROM sc_pt WHERE pid=".$this->db->escape($id);
		$query = $this->db->query($sql);
		$r = $query->row_array();
		return explode(',', $r['tags']);
	}
	
	//搜索查询
	private function search_sql_str($tag_list,$from = 0,$long){
		$sql = "SELECT p.*,t.tag_name,pt.tags,a.attach_name,a.attach_type,a.attach_path,u.user_name,COUNT(t.tag_pid) AS c FROM sc_tag t , (sc_post p LEFT JOIN sc_pt pt ON pt.pid = p.post_id LEFT JOIN sc_attach a ON a.attach_pid = p.post_id LEFT JOIN sc_user u ON u.user_id = p.post_user) WHERE t.tag_pid = p.post_id ";
		
		
		if(count($tag_list) > 0){
			$count = 0;
			$sql .= 'AND (';
			foreach ($tag_list as $v){
				$sql .= $count ? " OR " : "";
				$sql .= "t.tag_name like '%".addslashes($v)."%'";
				$count++;
			}
			$sql .= ')';
		} 
		

		$sql.= " GROUP BY t.tag_pid";
		
		$sql .= ' ORDER BY c DESC ,p.post_date DESC';//排序简单处理
		
		//mysql limit -1 有些版本有bug，临时处理。
		if($long > 0){
			//if($from){
				$sql .= " LIMIT ".$this->db->escape($from).",".$this->db->escape($long);
			//}//else if(!$from && $long > 0){
			//	$sql .= " LIMIT ".$this->db->escape($long);
			//}
		}
		//print_r($sql);
		return $sql;
	}
	
	public function search_num($tag_list,$from = 0,$long = -1){
		$sql = $this->search_sql_str($tag_list,$from, $long);
		$query = $this->db->query($sql);
		return $query->num_rows();
	}
	
	public function search_list($tag_list,$from = 0,$long = -1){
		$sql = $this->search_sql_str($tag_list,$from, $long);
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	//多tag查询
	private function tag_sql_str($tag_list,$from = 0,$long){
		$sql = "SELECT p.*,t.tag_name,pt.tags,a.attach_name,a.attach_type,a.attach_path,u.user_name,COUNT(t.tag_pid) AS c FROM sc_tag t , (sc_post p LEFT JOIN sc_pt pt ON pt.pid = p.post_id LEFT JOIN sc_attach a ON a.attach_pid = p.post_id LEFT JOIN sc_user u ON u.user_id = p.post_user) WHERE t.tag_pid = p.post_id ";
		
		$count_tag_list = count($tag_list);
		if($count_tag_list > 0){
			$count = 0;
			$sql .= 'AND (';
			foreach ($tag_list as $v){
				$sql .= $count ? " OR " : "";
				$sql .= "t.tag_name = '".addslashes($v)."'";
				$count++;
			}
			$sql .= ')';
			$sql .= " GROUP BY t.tag_pid";
			$sql .= ' HAVING c = '.$count_tag_list;
		} 
		
		$sql .= ' ORDER BY p.post_date DESC';//排序简单处理
		
		//mysql limit -1 有些版本有bug，临时处理。
		if($long > 0){
			//if($from){
				$sql .= " LIMIT ".$this->db->escape($from).",".$this->db->escape($long);
			//}//else if(!$from && $long > 0){
			//	$sql .= " LIMIT ".$this->db->escape($long);
			//}
		}
		//print_r($sql);
		return $sql;
	}
	
	public function tag_num($tag_list,$from = 0,$long = -1){
		$sql = $this->tag_sql_str($tag_list,$from, $long);
		$query = $this->db->query($sql);
		return $query->num_rows();
	}
	
	public function tag_list($tag_list,$from = 0,$long = -1){
		$sql = $this->tag_sql_str($tag_list,$from, $long);
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	
	//投票操作
	public function set_favo($id){
		$sql = "UPDATE sc_post SET post_favo = post_favo + 1 WHERE post_id=".$this->db->escape($id);
		return $this->db->query($sql);
	}

	//访问统计操作
	public function set_view($id){
		$sql = "UPDATE sc_post SET post_view = post_view + 1 WHERE post_id=".$this->db->escape($id);
		return $this->db->query($sql);
	}
}
