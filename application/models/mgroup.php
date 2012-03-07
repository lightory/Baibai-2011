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

	function updateBasicInfo($id, $name, $desc){
		$data = array(
    	'name' => $name,
			'desc' => $desc
    );

		$this->db->where('id', $id);
		$this->db->update('group', $data);
	}
	
	function updateIcon($id, $picFid, $picId){
		$data = array(
    	'picFid' => $picFid,
			'picId' => $picId
    );

		$this->db->where('id', $id);
		$this->db->update('group', $data);
	}
  
  // 获取小组成员数
  function getMembersCount($id){
    $query = $this->db->get_where('groupmember', array('groupId' => $id));
    return $query->num_rows();
  }
  
  // 获取成员和小组的关系
  function getUserType($userId, $groupId){
    $query = $this->db->get_where('groupmember', array('userId' => $userId, 'groupId' => $groupId), 1, 0);
    if ($result = $query->row()){
      return $result->type;
    } else {
      return '';
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
  
  // 获取用户加入的小组
  function getUserJoinedGroups($userId, $limit=8, $offset=0){
    $sql[] = "SELECT `bf_group`.*";
    $sql[] = "FROM `bf_group`,bf_groupmember";
    $sql[] = "WHERE bf_groupmember.userId = $userId";
    $sql[] = "AND bf_groupmember.groupId = bf_group.id";
    $sql[] = "ORDER BY bf_groupmember.time DESC";
    $sql[] = "LIMIT $offset, $limit";
    $sql = implode(' ', $sql);
    $query = $this->db->query($sql);
    if ($query->row()){
      return $query->result();
    } else {
      return array();
    }
  }

  // 获取所有小组
  function getGroups(){
    $sql[] = "SELECT *";
    $sql[] = "FROM `bf_group`";
    $sql[] = "ORDER BY time DESC";
    $sql = implode(' ', $sql);
    $query = $this->db->query($sql);
    if ($query->row()){
      return $query->result();
    } else {
      return array();
    }
  }
}