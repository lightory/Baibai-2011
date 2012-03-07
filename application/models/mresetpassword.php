<?php
// system/application/models/mresetpassword.php
class MResetPassword extends CI_Model{
	var $email = '';
	var $confirm = '';
	var $time = '';
	
	function __construct(){
        parent::__construct();
    }
	
	function getByConfirm($confirm){
		$query = $this->db->get_where('resetpassword', array('confirm' => $confirm), 1, 0);
		if ($query->row()){
			return $query->row();
		} else {
			return false;
		}
	}
	
	function insert($email){
		if (!($user=$this->MUser->getByEmail($email)) || !($user->nickname)){
			return false;
		}
		
		if ($this->isEmailExist($email)){
			$time = time();
			$data = array(
				'confirm' => md5($time),
				'time' => $time
			);
			
			$this->update($email, $data);
			return $data['confirm'];
		}
		
		$time = time();
		$this->email = $email;
		$this->confirm = md5($time);
		$this->time = $time;
		
		$this->db->insert('resetpassword',$this);
		return $this->confirm;
	}
	
	function update($email, $data){
		/*
		$data = array(
            'name'       => $argName,
			'bio'        => $argBio
        );
		*/
		$this->db->where('email', $email);
		$this->db->update('resetpassword', $data); 
	}
	
	function delete($email){
		$this->db->where('email', $email);
		$this->db->delete('resetpassword'); 
	}
	
	function isEmailExist($email){
		$query = $this->db->get_where('resetpassword', array('email' => $email), 1, 0);
		if ($query->row()){
			return true;
		} else {
			return false;
		}
	}
}