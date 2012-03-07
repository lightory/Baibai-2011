<?php
// system/application/models/mweekly.php
class MWeekly extends CI_Model {
	var $name = '';
	var $desc = '';
	var $book1Id = '';
	var $book2Id = '';
	var $book3Id = '';
	var $book4Id = '';
	var $book5Id = '';
	var $book6Id = '';
	var $book7Id = '';
	var $book8Id = '';
	var $book9Id = '';
	var $book10Id = '';
	var $newsTitle = '';
	var $newsLink = '';
	var $pubdate = '';
	var $isSent = '';
	
	function __construct() {
        parent::__construct();
    }

	function getById($id) {
		$query = $this->db->get_where('weekly', array('id' => $id), 1, 0);
		if ($query->row()) {
			return $query->row();
		} else {
			return false;
		}
	}
	
	function getAll($limit = 100, $offset = 0) {
	    $this->db->order_by('id', 'desc');
	    $query = $this->db->get('weekly',  $limit, $offset);
		if ($query->row()){
	    	return $query->result();
	    } else {
	    	return array();
	    }
	}
}