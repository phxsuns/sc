<?php
class Tag extends CI_Model{
	
	function __construct(){
		parent::__construct();
	}
	
	function hot_tags($long = 10,$start = 0){
		$sql = "SELECT t.*,COUNT(t.tag_name) as c FROM sc_tag t GROUP BY t.tag_name ORDER BY c DESC,t.tag_id DESC LIMIT ".$start.",".$long;
		$query = $this->db->query($sql);
		return $query->result_array();
	}

}