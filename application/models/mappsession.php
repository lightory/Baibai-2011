<?php
// system/application/models/mappsession.php
class MAppSession extends CI_Model{
  	var $uid = '';
  	var $time = '';
  	var $type = '';
  
  	function __construct(){
        parent::__construct();
    }
  
  	function newSession($uid, $type){
  		if ( $this->isExist($uid, $type) ){
  			return false;
  		}
  	
    	$this->uid = $uid;
    	$this->type = $type;
    	$this->time = mdate('%Y-%m-%d', time());

    	$this->db->insert('appsession',$this);
    	return $this;
  	}

	function isExist($uid, $type){
		$time = mdate('%Y-%m-%d', time());
		$query = $this->db->get_where('appsession', array('uid' => $uid, 'type' => $type, 'time' => $time),  1, 0);
		if ($query->row()){
			return true;
		} else {
			return false;
		}
	}
}