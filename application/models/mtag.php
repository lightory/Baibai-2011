<?php
// system/application/models/mtag.php
class MTag extends CI_Model{
  var $userId = '';
  var $bookId = '';
  var $tag = '';
  
	function __construct(){
        parent::__construct();
    }
  
	function insert($userId, $bookId, $tag){
		if(!$tag){
		return false;
		}
    
		$this->userId = $userId;
		$this->bookId = $bookId;
		$this->tag = $tag;
    
		$this->db->insert('tag',$this);
	}
  
	function delete($userId, $bookId, $tag){
		$this->db->where('userId', $userId);
		$this->db->where('bookId', $bookId);
		$this->db->where('tag', $tag);
		$this->db->delete('tag'); 
	}
  
	function isExist($userId, $bookId, $tag){
		$query = $this->db->get_where('tag', array('userId' => $userId, 'bookId' => $bookId, 'tag' => $tag), 1, 0);
		if ($query->row()){
		return true;
		} else {
		return false;
		}
	}
  
	function isSystemTagExist($bookId){
		$query = $this->db->get_where('tag', array('userId' => 0, 'bookId' => $bookId), 1, 0);
		if ($query->row()){
			return true;
		} else {
			return false;
		}
	}
  
	// 更新用户对某书的Tag
	function updateMyTagging($userId, $bookId, $oldTags, $newTags){
		$oldTags = explode(' ', trim(preg_replace("/([\s]{2,})/","\\1",$oldTags)));
		$newTags = explode(' ', trim(preg_replace("/([\s]{2,})/","\\1",$newTags))); 
    
		$deletedTags = array_diff($oldTags, $newTags);
		$addTags = array_diff($newTags, $oldTags);
    
		foreach ($deletedTags as $tag){
			$this->delete($userId, $bookId, $tag);
		}
    
		foreach ($addTags as $tag){
			$this->insert($userId, $bookId, $tag);
		}
	}
  
	// 获取用户对某书的Tag
	function getMyTagOfBook($userId, $bookId){
		$query = $this->db->get_where('tag', array('userId' => $userId, 'bookId' => $bookId));
		if ($query->row()){
			$results = $query->result();
			$tags = array();
			foreach($results as $result){
				$tags[] = $result->tag;
			} 
			return implode(' ', $tags);
		} else {
			return '';
		}
	}
  
	// 获取某书的Tag
	function getTagOfBook($bookId, $limit=10, $offset=0){
		$sql[] = 'SELECT *,count(bf_tag.tag) count';
		$sql[] = 'FROM bf_tag';
		$sql[] = "WHERE bf_tag.bookId = $bookId";
		$sql[] = 'GROUP BY bf_tag.tag';
		$sql[] = 'ORDER BY count DESC';
		$sql[] = "LIMIT $offset,$limit";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
  
	// 获取某用户的Tag
	function getTagOfUser($userId, $limit=10, $offset=0){
		$sql[] = 'SELECT *,count(bf_tag.tag) count';
		$sql[] = 'FROM bf_tag';
		$sql[] = "WHERE bf_tag.userId = $userId";
		$sql[] = 'GROUP BY bf_tag.tag';
		$sql[] = 'ORDER BY count DESC';
		$sql[] = "LIMIT $offset,$limit";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
  
	// 获取全部Tag
	function getTags($limit=10, $offset=0){
		$sql[] = 'SELECT *,count(DISTINCT bf_tag.bookId) count';
		$sql[] = 'FROM bf_tag';
		$sql[] = 'GROUP BY bf_tag.tag';
		$sql[] = 'ORDER BY count DESC';
		$sql[] = "LIMIT $offset,$limit";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
  
	function getTagsByArea($followProvinces, $followCities, $limit=10, $offset=0){
		$sql[] = 'SELECT *,count(DISTINCT bf_tag.bookId) count';
		$sql[] = 'FROM bf_tag,bf_book,bf_stock,bf_address';
		$sql[] = "WHERE (bf_address.province IN ($followProvinces) OR bf_address.city IN ($followCities))";
		$sql[] = 'AND bf_address.uid = bf_stock.readerId';
		$sql[] = 'AND bf_stock.bookId = bf_book.id';
		$sql[] = 'AND bf_book.id = bf_tag.bookId';
		$sql[] = 'GROUP BY bf_tag.tag';
		$sql[] = 'ORDER BY count DESC';
		$sql[] = "LIMIT $offset,$limit";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
  
	function getTagsForTagsCloud($limit=50){
		$sql[] = 'SELECT *,count(DISTINCT bf_tag.bookId) count';
		$sql[] = 'FROM bf_tag';
		$sql[] = 'GROUP BY bf_tag.tag';
		$sql[] = 'ORDER BY count DESC';
		$sql[] = "LIMIT $limit";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
  
	function getTagsForTagsCloudByArea($followProvinces, $followCities, $limit=50){
		$sql[] = 'SELECT bf_tag.*,count(DISTINCT bf_tag.bookId) count';
		$sql[] = 'FROM bf_tag,bf_stock,bf_book,bf_address';
		$sql[] = "WHERE (bf_address.province IN ($followProvinces) OR bf_address.city IN ($followCities))";
		$sql[] = 'AND bf_address.uid = bf_stock.readerId';
		$sql[] = 'AND bf_stock.bookId = bf_book.id';
		$sql[] = 'AND bf_book.id = bf_tag.bookId';
		$sql[] = 'GROUP BY bf_tag.tag';
		$sql[] = 'ORDER BY count DESC';
		$sql[] = "LIMIT $limit";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
}