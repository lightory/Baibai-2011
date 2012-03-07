<?php
// system/application/models/museroauth.php
class MUserOAuth extends CI_Model{
	var $uid = '';
	var $provider = '';
	var $request_token_secret = '';
	var $access_token = '';
	var $access_token_secret = '';
	var $user_id = '';
	
	function __construct(){
  	parent::__construct();
  }
	
	function insert($uid, $provider, $request_token_secret){
		$this->uid = $uid;
		$this->provider = $provider;
		$this->request_token_secret = $request_token_secret;
		$this->access_token = '';
		$this->access_token_secret = '';

		$this->db->insert('useroauth', $this);
		return $this->db->insert_id();
	}
	
	function updateRequestToken($uid, $provider, $request_token_secret){
		$data = array(
			'request_token_secret' => $request_token_secret
		);

		$this->db->where('uid', $uid);
		$this->db->where('provider', $provider);
		$this->db->update('useroauth', $data);
	}
	
	function updateAccessToken($uid, $provider, $access_token, $access_token_secret, $user_id){
		$data = array(
    	'access_token' => $access_token,
			'access_token_secret' => $access_token_secret,
			'user_id' => $user_id
		);

		$this->db->where('uid', $uid);
		$this->db->where('provider', $provider);
		$this->db->update('useroauth', $data);
	}
	
	function get($uid, $provider){
		$query = $this->db->get_where('useroauth', array('uid'=>$uid, 'provider'=>$provider));
		return $query->row();
	}
	
	function delete($uid, $provider){
		$this->db->where('uid', $uid);
		$this->db->where('provider', $provider);
		$this->db->delete('useroauth');
	}
	
	function getUserLinkedProviders($uid){
		$query = $this->db->get_where('useroauth', array('uid'=>$uid));
		$results = $query->result();
		$providers = array();
		foreach($results as $result){
			if($result->access_token && $result->access_token_secret){
				$providers[] = $result->provider;
			}
		}
		return $providers;
	}
}