<?php
// system/application/models/mapplyuser.php
class MApplyUser extends CI_Model{
	var $nickname = '';
	var $email = '';
	var $address = '';
	var $blog = '';
	var $donate = '';
	var $bio = '';
	var $time = '';
	var $later = '';
	
	function __construct(){
        parent::__construct();
    }
	
	function newApply($nickname, $email, $address, $blog, $donate, $bio){
		$this->nickname = $nickname;
		$this->email = $email;
		$this->address = $address;
		$this->blog = $blog;
		$this->donate = $donate;
		$this->bio = $bio;
		$this->time = time();
		$this->later = 0;
		
		$this->db->insert('applyuser', $this);
		return $this;
	}
	
	function getByEmail($email){
		$query = $this->db->get_where('applyuser', array('email' => $email), 1, 0);
		if ($query->row()){
			return $query->row();
		}
	}
	
	function isEmailExist($email){
		$query = $this->db->get_where('applyuser', array('email' => $email), 1, 0);
		if ($query->row()){
			return true;
		}
		
		$query = $this->db->get_where('tempuser', array('email' => $email), 1, 0);
		if ($query->row()){
			return true;
		}
		
		$query = $this->db->get_where('user', array('email' => $email), 1, 0);
		if ($query->row()){
			return true;
		}
		
		return false;
	}
	
	function delete($email){
		$this->db->where('email', $email);
		$this->db->delete('applyuser');
	}
	
	function markAsInviteLater($email){
		$data = array(
			'later' => 1
		);
		$this->db->where('email', $email);
		$this->db->update('applyuser', $data);
	}
	
	function getAll($later=0){
		$query = $this->db->get_where('applyuser', array('later' => $later));
		if ($query->row()){
			return $query->result();
		}
		
		return array();
	}
}