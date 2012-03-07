<?php
// system/application/models/mgrouppost.php
class MGroupPost extends CI_Model{
  var $topicId = '';
  var $userId = '';
  var $content = '';
  var $time = '';
  
  function __construct(){
        parent::__construct();
    }
  
  function addPost($topicId, $userId, $content){
    $this->topicId = $topicId;
    $this->userId = $userId;
    $this->content = $content;
    $this->time = time();

    $this->db->insert('grouppost', $this);
    return $this->db->insert_id();
  }
  
  function getByTopicId($topicId){
    $this->db->order_by('time', 'desc');
    $query = $this->db->get_where('grouptopic', array('id' => $topicId), 1, 0);
    if ($query->row()){
      return $query->row();
    } else {
      return false;
    }
  }
}