<?php
// system/application/models/maddress.php
class MAddress extends CI_Model{
	var $uid = '';
	var $name = '';
	var $postcode = '';
	var $postcodeTrue = '';
	var $province = '';
	var $city = '';
	var $district = '';
	var $address = '';
	var $def = '';
	
	function __construct(){
        parent::__construct();
    }
	
	// 获取指定uid的默认地址
	function getDefaultByUid($uid){
		$query = $this->db->get_where('address', array('uid' => $uid, 'def' => 1), 1, 0);
		if ($query->row()){
			return $query->row();
		} else {
			return false;
		}
	}
	
	// 获取指定uid的所有地址
	// 备用
	function getByUid($uid){
		$query = $this->db->get_where('address', array('uid' => $uid));
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
	
	// 新建一个地址
	function insert($uid, $name, $postcode, $postcodeTrue, $province, $city, $district, $address, $def){
		$this->uid = $uid;
		$this->name = $name;
		$this->postcode = $postcode;
		$this->postcodeTrue = $postcodeTrue;
		$this->province = $province;
		$this->city = $city;
		$this->district = $district;
		$this->address = $address;
		$this->def = $def;

		$this->db->insert('address',$this);
		return $this->db->insert_id();
	}
	
	function update($id, $data){
		/*
		$data = array(
            'name'       => $argName,
			'bio'        => $argBio
        );
		*/

		$this->db->where('id', $id);
		$this->db->update('address', $data); 
	}
	
	#---------------------------
	
	// 注册时，新建一个默认地址
	function register($uid, $name, $postcode, $postcodeTrue, $province, $city, $district, $address){
		$this->insert($uid, $name, $postcode, $postcodeTrue, $province, $city, $district, $address, 1);
	}
	
	// 修改地址
	function changeAddress($id, $name, $postcode, $postcodeTrue, $province, $city, $district, $address){
		$data = array(
			'name' => $name,
			'postcode' => $postcode,
			'postcodeTrue' => $postcodeTrue,
			'province' => $province,
			'city' => $city,
			'district' => $district,
			'address' => $address,
		);
		$this->update($id, $data);
	}
  
	// 获取总城市数
	function getCitiesCount(){
		$sql[] = "SELECT DISTINCT city FROM bf_address";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		return $query->num_rows();
	}
}