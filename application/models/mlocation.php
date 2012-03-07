<?php
// system/application/models/mlocation.php
class MLocation extends CI_Model{
	var $type = '';
	var $url = '';
	var $name = '';
	
	function __construct(){
		parent::__construct();
	}
	
	function getByUrl($url){
		$query = $this->db->get_where('location', array('url' => $url), 1, 0);
		if ($query->row()){
			return $query->row();
		} else {
			return false;
		}
	}
	
	function getByCityName($cityName){
		$query = $this->db->get_where('location', array('type' => 'city', 'name' => $cityName), 1, 0);
		if ($query->row()){
			return $query->row();
		} else {
			return false;
		}
	}
}