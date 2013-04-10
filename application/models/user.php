<?php
class User extends CI_Model{
	
	function __construct(){
		parent::__construct();
	}
	
	function user_login($username,$password){
		$sql = "SELECT * FROM seven_user WHERE user_name=".$this->db->escape($username);
		$query = $this->db->query($sql);
		$r = $query->row_array();
		$pw = $r['user_pw'];
		if(md5($password) == $pw) return $r['user_id'];
		else return 0;
	}
	
	function user_session($session_id){
		$sql = "SELECT * FROM seven_sessions WHERE session_id =".$this->db->escape($session_id);
		$query = $this->db->query($sql);
		return $query->num_rows() ? true : false;
	}
}