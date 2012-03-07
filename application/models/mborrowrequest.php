<?php
// system/application/models/mborrowrequest.php
class MBorrowRequest extends CI_Model {
	var $userId = '';
	var $bookId = '';
	var $message = '';
	var $time = '';
	var $allowUserIds = '';
	
	function __construct(){
        parent::__construct();
    }

	function insert($bookId, $userId, $message, $allowUserIds = 'ALL'){
		$this->bookId = $bookId;
		$this->userId = $userId;
		$this->message = $message;
		$this->time = time();
		$this->allowUserIds = $allowUserIds;
		
		$this->db->insert('borrowrequest', $this);
		return $this->db->insert_id();
	}
	
	function delete($requestId){
		$this->db->where('id', $requestId);
		$this->db->delete('borrowrequest'); 
	}
	
	function updateMessage($requestId, $userId, $newMessage, $allowUserIds = 'ALL'){
		$data = array(
            'message' => $newMessage,
			'allowUserIds' => $allowUserIds
        );

		$this->db->where('id', $requestId);
		$this->db->where('userId', $userId);
		$this->db->update('borrowrequest', $data); 
	}

	function getById($id){
		$query = $this->db->get_where('borrowrequest', array('id' => $id), 1, 0);
		if ( $query->row() ){
			return $query->row();
		} else {
			return false;
		}
	}

	function getCountByUserId($userId){
		$query = $this->db->get_where('borrowrequest', array('userId' => $userId));
		return $query->num_rows();
	}
	
	function getUserWishes($userId, $limit=100, $offset=0){
		$this->db->order_by('id', 'desc');
		$query = $this->db->get_where('borrowrequest', array('userId' => $userId), $limit, $offset);
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
	
	function getByBook($bookId){
		$query = $this->db->get_where('borrowrequest', array('bookId' => $bookId));
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
	
	function getUserMessageByBook($bookId, $userId){
		$query = $this->db->get_where('borrowrequest', array('bookId' => $bookId, 'userId' => $userId), 1, 0);
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
	
	function getByUserIdAndBookId($userId, $bookId){
		$this->db->where('userId', $userId);
		$this->db->where('bookId', $bookId);
		$query = $this->db->get('borrowrequest', 1, 0);
		if ( $query->row() ){
			return $query->row();
		} else {
			return false;
		}
	}
	
	function getNumber(){
		return $this->db->count_all('borrowrequest');
	}
	
	function getRequests(){
		$this->db->order_by('time', 'desc');
		$query = $this->db->get('borrowrequest', 500, 0);
		if ( $query->row() ){
			return $query->result();
		} else {
			return array();
		}
	}
}