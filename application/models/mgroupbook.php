<?php
// system/application/models/mgroupmember.php
class MGroupBook extends CI_Model{
  var $groupId = '';
  var $bookId = '';
  var $userId = '';
  var $time = '';
  
  function __construct(){
        parent::__construct();
    }
  
  function addBook($groupId, $bookId, $userId){
		if ($this->isBookExist($groupId, $bookId)){
			return false;
		}
	
    $this->groupId = $groupId;
    $this->bookId = $bookId;
    $this->userId = $userId;
    $this->time = time();

    $this->db->insert('groupbook',$this);
    return true;
  }
  
  function removeBook($groupId, $bookId){
    $this->db->where('bookId', $bookId);
    $this->db->where('groupId', $groupId);
    $this->db->delete('groupbook'); 
  }

  function isBookExist($groupId, $bookId){
    $this->db->where('groupId', $groupId);
    $this->db->where('bookId', $bookId);
    $query = $this->db->get('groupbook');
    if ($query->row()){
      return true;
    } else {
      return false;
    }
  }

	function isMyColletion($groupId, $bookId, $userId){
		$this->db->where('groupId', $groupId);
    $this->db->where('bookId', $bookId);
		$this->db->where('userId', $userId);
    $query = $this->db->get('groupbook');
    if ($query->row()){
      return true;
    } else {
      return false;
    }
	}
}