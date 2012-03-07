<?php
// system/application/models/memailnotify.php
class MEmailNotify extends CI_Model{
	var $uid = '';
	var $weekly = '';
	var $mail = '';
	
	function __construct(){
  	parent::__construct();
  }
	
	function getByUid($uid){
		$query = $this->db->get_where('emailnotify', array('uid' => $uid), 1, 0);
		if ($query->row()){
			return $query->row();
		} else {
			return false;
		}
	}
	
	function update($uid, $weekly, $mail){
		$data = array(
    	'weekly' => $weekly,
			'mail' => $mail
    );

		$this->db->where('uid', $uid);
		$this->db->update('emailnotify', $data);
	}
	
	function insert($uid, $weekly='1', $mail='1'){
		$this->uid = $uid;
		$this->weekly = $weekly;
		$this->mail = $mail;
		
		$this->db->insert('emailnotify',$this);
	}
}