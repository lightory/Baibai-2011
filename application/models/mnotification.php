<?php
// system/application/models/mnotification.php
class MNotification extends CI_Model{
	var $receiverUid = '';
	var $avatar = '';
	var $content = '';
	var $time = '';
	var $actionName = '';
	var $actionUrl = '';
	
	function __construct(){
        parent::__construct();
    }
	
	function insert($notification){
		$this->receiverUid = $notification->receiverUid;
		$this->avatar = isset($notification->avatar) ? $notification->avatar : '';
		$this->content = $notification->content;
		$this->time = time();
		$this->actionName = isset($notification->actionName) ? $notification->actionName : '';
		$this->actionUrl = isset($notification->actionUrl) ? $notification->actionUrl : '';
		
		$this->db->insert('notification',$this);
		return $this->db->insert_id();
	}
	
	function getByReceiver($receiverUid){
		$this->db->order_by('time', 'desc');
		$query = $this->db->get_where('notification', array('receiverUid' => $receiverUid));
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
	
	function getCountByReceiver($receiverUid){
		$this->db->order_by('time', 'desc');
		$query = $this->db->get_where('notification', array('receiverUid' => $receiverUid));
		return $query->num_rows();
	}
	
	function getById($id){
		$query = $this->db->get_where('notification', array('id' => $id), 1, 0);
		if ($query->row()){
			return $query->row();
		} else {
			return false;
		}
	}
	
	function delete($id, $uid){
		$notification = $this->getById($id);
		if ( $notification->receiverUid != $uid ){
			return false;
		}
	
		$this->db->where('id', $id);
		$this->db->delete('notification'); 
	}
}