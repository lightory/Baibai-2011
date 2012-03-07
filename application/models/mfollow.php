<?php
// system/application/models/mfollow.php
class MFollow extends CI_Model{
	var $userId = '';
	var $followUserId = '';
	var $time = '';
	
	function __construct(){
  	parent::__construct();
  }

	function insert($userId, $followUserId){
		if($this->isExist($userId, $followUserId)){
			return false;
		}
		
		$this->userId = $userId;
		$this->followUserId = $followUserId;
		$this->time = time();

		$this->db->insert('follow',$this);
		return true;
	}
	
	function delete($userId, $followUserId){
		$this->db->where('userId', $userId);
		$this->db->where('followUserId', $followUserId);
		$this->db->delete('follow');
	}
	
	function isExist($userId, $followUserId){
		$query = $this->db->get_where('follow', array('userId' => $userId, 'followUserId' => $followUserId), 1, 0);
		if ($query->row()){
			return true;
		} else {
			return false;
		}
	}
	
	function getUserFollowings($userId, $limit=8, $offset=0){
		$sql[] = "SELECT bf_user.*";
		$sql[] = "FROM bf_user,bf_follow";
		$sql[] = "WHERE bf_follow.userId = $userId";
		$sql[] = "AND bf_follow.followUserId = bf_user.uid";
		$sql[] = "ORDER BY bf_follow.time DESC";
		$sql[] = "LIMIT $offset, $limit";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
	
	function getUserFollowers($userId, $limit=8, $offset=0){
		$sql[] = "SELECT bf_user.*";
		$sql[] = "FROM bf_user,bf_follow";
		$sql[] = "WHERE bf_follow.followUserId = $userId";
		$sql[] = "AND bf_follow.userId = bf_user.uid";
		$sql[] = "ORDER BY bf_follow.time DESC";
		$sql[] = "LIMIT $offset, $limit";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
	
	function getUserFollowingsCount($userId){
		$query = $this->db->get_where('follow', array('userId' => $userId));
    return $query->num_rows();
	}
	
	function getUserFollowersCount($userId){
		$query = $this->db->get_where('follow', array('followUserId' => $userId));
    return $query->num_rows();
	}
}