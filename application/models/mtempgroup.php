<?php
// system/application/models/mtempgroup.php
class MTempGroup extends CI_Model{
	var $url = '';
	var $name = '';
	var $bio = '';
	var $userId = '';
	var $time = '';
  
	function __construct(){
        parent::__construct();
    }
  
	function insert($name, $url, $bio, $userId){
		$this->url = $url;
		$this->name = $name;
		$this->bio = $bio;
		$this->userId = $userId;
		$this->time = time();

		$this->db->insert('tempgroup',$this);
		return $this->db->insert_id();
	}
}