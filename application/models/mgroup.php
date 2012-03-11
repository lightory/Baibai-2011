<?php
// system/application/models/mgroup.php
class MGroup extends CI_Model{
	var $type = '';
	var $url = '';
	var $name = '';
	var $desc = '';
	var $picFid = '';
	var $picId = '';
	var $time = '';
  
	function __construct(){
		parent::__construct();
	}
  
	// 获取指定id的小组
	function getById($id){
		$query = $this->db->get_where('group', array('id' => $id), 1, 0);
		if ($query->row()){
			return $query->row();
		} else {
			return false;
		}
	}
  
	function getByUrl($url){
		$query = $this->db->get_where('group', array('url' => $url), 1, 0);
		if ($query->row()){
			return $query->row();
		} else {
			return false;
		}
	}
  
	// 获取小组最新主题
	function getLatestTopics($groupId, $limit=15, $offset=0){
		$this->db->where('groupId', $groupId);
		$this->db->order_by('activeTime', 'desc');
		$query = $this->db->get('grouptopic',  $limit, $offset);
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
  
	// 获取小组主题数
	function getTopicsCount($groupId){
		$this->db->where('groupId', $groupId);
 	  	$query = $this->db->get('grouptopic');
		return $query->num_rows();
	}
}