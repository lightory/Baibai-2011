<?php
// system/application/models/meventlog.php
class MEventLog extends CI_Model{	
	function __construct(){
		parent::__construct();
	}
  
	// 添加事件记录
	function insert($event){
		$this->uid = $event->uid;
		$this->title = $event->title;
		$this->content = $event->content;
		$this->category = $event->category;
		$this->time = time();

		$this->db->insert('eventlog', $this);
		return $this->db->insert_id();
	}
  
	//获取特定用户，特定类型的事件记录
	function getByUserId($uid, $category){
		$query = $this->db->get_where('eventlog', array('uid' => $uid, 'category' => $category));
		if ($query->result()){
			return $query->result();
		} else {
			return false;
		}
	}
	
	function getByTitle($title, $category) {
		$query = $this->db->get_where('eventlog', array('title' => $title, 'category' => $category));
		if ($query->result()){
			return $query->result();
		} else {
			return false;
		}
	}
	
	function get($uid, $title, $category) {
		$query = $this->db->get_where('eventlog', array('uid' => $uid, 'title' => $title, 'category' => $category));
		if ($query->result()){
			return $query->result();
		} else {
			return false;
		}
	}
}