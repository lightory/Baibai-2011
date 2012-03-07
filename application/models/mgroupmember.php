<?php
// system/application/models/mgroupmember.php
class MGroupMember extends CI_Model{
  var $groupId = '';
  var $userId = '';
  var $type = '';
  var $time = '';
  
  function __construct(){
        parent::__construct();
    }
  
  function addMember($userId, $groupId){
    $this->userId = $userId;
    $this->groupId = $groupId;
    $this->type = 'member';
    $this->time = time();

    $this->db->insert('groupmember',$this);
    return true;
  }
  
  function removeMember($userId, $groupId){
    $this->db->where('userId', $userId);
    $this->db->where('groupId', $groupId);
    $this->db->delete('groupmember'); 
  }
  
  function getGroupOwner($groupId){
    $sql[] = "SELECT bf_user.*";
    $sql[] = "FROM bf_groupmember,bf_user";
    $sql[] = "WHERE bf_groupmember.groupId = $groupId";
    $sql[] = "AND bf_groupmember.type = 'owner'";
    $sql[] = "AND bf_groupmember.userId = bf_user.uid";
    $sql = implode(' ', $sql);
    $query = $this->db->query($sql);
    if ($query->row()){
      return $query->row();
    } else {
      return false;
    }
  }
  
  function getGroupMembers($groupId){
    $sql[] = "SELECT bf_user.*";
    $sql[] = "FROM bf_groupmember,bf_user";
    $sql[] = "WHERE bf_groupmember.groupId = $groupId";
    $sql[] = "AND bf_groupmember.type = 'member'";
    $sql[] = "AND bf_groupmember.userId = bf_user.uid";
    $sql[] = "ORDER BY bf_groupmember.time DESC";
    $sql = implode(' ', $sql);
    $query = $this->db->query($sql);
    if ($query->row()){
      return $query->result();
    } else {
      return array();
    }
  }

	function isAdmin($groupId, $userId){
		$this->db->where('groupId', $groupId);
    $this->db->where('userId', $userId);
		$this->db->where_in('type', array('admin', 'owner'));
    $query = $this->db->get('groupmember');
    if ($query->row()){
      return true;
    } else {
      return false;
    }
	}
}