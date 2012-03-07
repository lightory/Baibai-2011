<?php
// system/application/models/mtempuser.php
class MTempUser extends CI_Model{
	var $email = '';
	var $introducerUid = '';
	var $borrowQuote = '';
	var $time = '';
	var $code = '';
	
	function __construct(){
        parent::__construct();
    }
	
	function newTempUser($introducerUid, $email, $borrowQuote=0){
		$this->introducerUid = $introducerUid;
		$this->email = $email;
		$this->borrowQuote = $borrowQuote;
		$this->time = time();
		$this->code = md5($introducerUid.$email.time());
		
		$this->db->insert('tempuser', $this);
		return $this;
	}
	
	function getByCode($code){
		$query = $this->db->get_where('tempuser', array('code' => $code), 1, 0);
		if ($query->row()){
			return $query->row();
		} else {
			return false;
		}
	}
	
	function delete($email){
		$this->db->where('email', $email);
		$this->db->delete('tempuser');
	}
	
	function getCount(){
		return $this->db->count_all('tempuser');
	}
	
	function getAll($limit=50, $offset=0){
		$sql[] = 'SELECT * FROM bf_tempuser';
		$sql[] = "ORDER BY time DESC";
		$sql[] = "LIMIT $offset,$limit";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
	
	function getByIntroducer($introducerUid){
		$query = $this->db->get_where('tempuser', array('introducerUid' => $introducerUid, ));
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
}