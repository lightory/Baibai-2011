<?php
// system/application/models/mgrouptopic.php
class MGroupTopic extends CI_Model{
	var $groupId = '';
	var $userId = '';
	var $title = '';
	var $content = '';
	var $time = '';
	var $activeTime = '';
  
	function __construct(){
        parent::__construct();
    }
  
	function addTopic($groupId, $userId, $title, $content){
		$this->groupId = $groupId;
		$this->userId = $userId;
		$this->title = $title;
		$this->content = $content;
		$this->time = time();
		$this->activeTime = time();

		$this->db->insert('grouptopic', $this);
		return $this->db->insert_id();
	}
  
	function getById($topicId){
		$query = $this->db->get_where('grouptopic', array('id' => $topicId), 1, 0);
		if ($query->row()){
			return $query->row();
		} else {
			return false;
		}
	}
  
	function getTopicReplys($topicId){
		$this->db->order_by('time', 'asc');
		$query = $this->db->get_where('grouppost', array('topicId' => $topicId));
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
  
	function getTopicReplysCount($topicId){
		$query = $this->db->get_where('grouppost', array('topicId' => $topicId));
		return $query->num_rows();
	}
  
	function updateAcitveTime($topicId){
		$data = array(
			'activeTime' => time()
		);

		$this->db->where('id', $topicId);
		$this->db->update('grouptopic', $data); 
	}
  
	// 用户关注的小组主题
	function getUserFollowGroupTopics($userId, $limit=20, $offset=0){
		$sql[] = "SELECT bf_grouptopic.*,bf_group.name,bf_group.url";
		$sql[] = "FROM `bf_group`,bf_grouptopic";
		$sql[] = "WHERE 1 = bf_grouptopic.groupId";
		$sql[] = "AND 1 = bf_group.id";
		$sql[] = "ORDER BY bf_grouptopic.activeTime DESC";
		$sql[] = "LIMIT $offset, $limit";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
  
	// 用户关注的小组主题总数
	function getUserFollowGroupTopicsCount($userId){
		$sql[] = "SELECT bf_grouptopic.*";
		$sql[] = "FROM `bf_group`,bf_grouptopic";
		$sql[] = "WHERE 1 = bf_grouptopic.groupId";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		return $query->num_rows();
	}
  
	// 用户发表的主题
	function getUserTopics($userId, $limit=15, $offset=0){
		$sql[] = "SELECT bf_grouptopic.*,bf_group.name,bf_group.url";
		$sql[] = "FROM `bf_group`,bf_grouptopic";
		$sql[] = "WHERE bf_grouptopic.userId = $userId";
		$sql[] = "AND bf_grouptopic.groupId = bf_group.id";
		$sql[] = "ORDER BY bf_grouptopic.activeTime DESC";
		$sql[] = "LIMIT $offset, $limit";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
  
	function getUserTopicsCount($userId){
		$sql[] = "SELECT bf_grouptopic.*,bf_group.name,bf_group.url";
		$sql[] = "FROM `bf_group`,bf_grouptopic";
		$sql[] = "WHERE bf_grouptopic.userId = $userId";
		$sql[] = "AND bf_grouptopic.groupId = bf_group.id";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		return $query->num_rows();
	}
  
	// 用户回应的话题
	function getUserReplyTopics($userId, $limit=15, $offset=0){
		$sql[] = "SELECT DISTINCT bf_grouptopic.*,bf_group.name,bf_group.url";
		$sql[] = "FROM `bf_group`,bf_grouptopic,bf_grouppost";
		$sql[] = "WHERE bf_grouppost.userId = $userId";
		$sql[] = "AND bf_grouppost.topicId = bf_grouptopic.id";
		$sql[] = "AND bf_grouptopic.groupId = bf_group.id";
		$sql[] = "ORDER BY bf_grouptopic.activeTime DESC";
		$sql[] = "LIMIT $offset, $limit";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
  
	function getUserReplyTopicsCount($userId){
		$sql[] = "SELECT DISTINCT bf_grouptopic.*,bf_group.name,bf_group.url";
		$sql[] = "FROM `bf_group`,bf_grouptopic,bf_grouppost";
		$sql[] = "WHERE bf_grouppost.userId = $userId";
		$sql[] = "AND bf_grouppost.topicId = bf_grouptopic.id";
		$sql[] = "AND bf_grouptopic.groupId = bf_group.id";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		return $query->num_rows();
	}
}