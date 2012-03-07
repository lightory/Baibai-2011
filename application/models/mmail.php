<?php
// system/application/models/mmil.php
class MMail extends CI_Model{
	var $senterUid = '';
	var $receiverUid = '';
	var $content = '';
	var $time = '';
	var $statue = '';
	var $senterStatue = '';
	var $receiverStatue = '';
  
	function __construct(){
		parent::__construct();
    }
  
	function insert($senterUid, $receiverUid, $content){
		$this->senterUid = $senterUid;
		$this->receiverUid = $receiverUid;
		$this->content = $content;
		$this->time = time();
		$this->statue = 'unread';
		$this->senterStatue = 'show';
		$this->receiverStatue = 'show';

		$this->db->insert('mail',$this);
		return $this->db->insert_id();
	}
  
	function markAsRead($receiverUid){
		$data = array(
			'statue' => 'read'
		);

		$this->db->where('receiverUid', $receiverUid);
		$this->db->update('mail', $data); 
	}
  
	function hide($id, $userId){
		$query = $this->db->get_where('mail', array('id' => $id, 'receiverUid' => $userId));
		if ($query->row()){
			$data = array(
				'receiverStatue' => 'hide'
			);
			$this->db->where('id', $id);
			$this->db->update('mail', $data); 
			return true;
		}
    
		$query2 = $this->db->get_where('mail', array('id' => $id, 'senterUid' => $userId));
		if ($query2->row()){
			$data = array(
				'senterStatue' => 'hide'
			);
			$this->db->where('id', $id);
			$this->db->update('mail', $data); 
			return true;
		}
	}
  
	function getUserInboxMail($receiverUid){
		$this->db->order_by('id', 'desc');
		$query = $this->db->get_where('mail', array('receiverUid' => $receiverUid, 'receiverStatue' => 'show'));
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
  
	function getUserInboxUnreadCount($receiverUid){
		$query = $this->db->get_where('mail', array('receiverUid' => $receiverUid, 'receiverStatue' => 'show', 'statue' => 'unread'));
		return $query->num_rows();
	}
  
	function getUserInboxCount($receiverUid){
		$query = $this->db->get_where('mail', array('receiverUid' => $receiverUid, 'receiverStatue' => 'show'));
		return $query->num_rows();
	}
  
	function getUserOutboxMail($senterUid){
		$this->db->order_by('id', 'desc');
		$query = $this->db->get_where('mail', array('senterUid' => $senterUid, 'senterStatue' => 'show'));
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
  
	function getUserOutboxCount($senterUid){
		$query = $this->db->get_where('mail', array('senterUid' => $senterUid, 'senterStatue' => 'show'));
		return $query->num_rows();
	}
}