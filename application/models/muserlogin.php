<?php
// system/application/models/muserlogin.php
class MUserLogin extends CI_Model{
	var $userId = '';
	var $time = '';
	var $type = '';
	
	function __construct(){
  	parent::__construct();
	}
	
	function insert($userId, $type='webiste'){
		$this->userId = $userId;
		$this->time = time();
		$this->type = $type;

		$this->db->insert('userlogin', $this);
		return $this->db->insert_id();
	}
	
	function getUserLastLogin($userId){
		$this->db->order_by('time', 'desc');
		$query = $this->db->get_where('userlogin', array('userId'=>$userId), 1, 0);
		return $query->row();
	}
	
	function getUsersLastLogin($userStatue){
		$sql[] = "SELECT `bf_userlogin`.userId, MAX(`bf_userlogin`.time) maxTime";
		$sql[] = "FROM `bf_userlogin`,`bf_user`";
		$sql[] = "WHERE `bf_user`.statue = '$userStatue'";
		$sql[] = "AND `bf_user`.uid = `bf_userlogin`.userId";
		$sql[] = "GROUP BY `bf_userlogin`.userId";
		$sql[] = "ORDER BY maxTime ASC";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
}