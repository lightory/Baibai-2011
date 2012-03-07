<?php
// system/application/models/mmappsession.php
class MMAppSession extends CI_Model{
	var $sessionId = '';
	var $uid = '';
	var $time = '';
  
	function __construct(){
		parent::__construct();
    }
  
	function newSession($uid){
		$this->deleteSession($uid);
    
		$this->sessionId = md5($uid.time());
		$this->uid = $uid;
		$this->time = time();

		$this->db->insert('mappsession',$this);
		return $this;
	}
  
	function deleteSession($uid){
		$this->db->where('uid', $uid);
		$this->db->delete('mappsession'); 
	}
  
	function getById($sessionId){
		$query = $this->db->get_where('mappsession', array('sessionId' => $sessionId), 1, 0);
		if ($query->row()){
			return $query->row();
		} else {
			return false;
		}
	}
}