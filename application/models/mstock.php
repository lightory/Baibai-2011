<?php
// system/application/models/mstock.php
class MStock extends CI_Model{
	var $bookId = '';
	var $ownerId = '';
	var $readerId = '';
	var $time = '';
	var $transforTime = '';
	var $finishTime = '';
	var $statue = '';
	var $readerStatue = '';
	
	function __construct(){
        parent::__construct();
    }

	function getById($id){
		$query = $this->db->get_where('stock', array('id' => $id), 1, 0);
		if ($query->row()){
			return $query->row();
		} else {
			return false;
		}
	}
	
	function getDonateRecord($userId, $limit=100, $offset=0){
		$this->db->order_by('id', 'desc');
		$this->db->where('ownerId' , $userId);
		$this->db->where('readerId !=', $userId);
		$query = $this->db->get('stock', $limit, $offset);
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
	
	function getDonateCount($userId){
		$this->db->where('ownerId' , $userId);
		$this->db->where('readerId !=', $userId);
		$query = $this->db->get('stock');
		return $query->num_rows();
	}
	
	function getTotalDonateCount($uid){
		$this->db->where('ownerId' , $uid);
		$query = $this->db->get('stock');
		return $query->num_rows();
	}
	
	function getByOwnerId($ownerId){
		$query = $this->db->get_where('stock', array('ownerId' => $ownerId));
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
	
	function getByReaderId($readerId, $limit=100, $offset=0){
		$this->db->order_by('transforTime', 'desc');
		$this->db->where('statue', 'reading');
		$this->db->where('readerId', $readerId);
		$query = $this->db->get('stock', $limit, $offset);
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
	
	function getReadingCount($readerId){
		$this->db->where('statue', 'reading');
		$this->db->where('readerId', $readerId);
		$query = $this->db->get('stock');
		return $query->num_rows();
	}
	
	function getAvailableByReaderId($readerId, $limit=100, $offset=0){
		$this->db->order_by('finishTime', 'desc');
		$this->db->where('statue', 'available');
		$this->db->where('readerId', $readerId);
		$query = $this->db->get('stock', $limit, $offset);
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
	
	function getAvailableCount($readerId){
		$this->db->where('statue', 'available');
		$this->db->where('readerId', $readerId);
		$query = $this->db->get('stock');
		return $query->num_rows();
	}
	
	function getTransferByUser($readerId){
		$this->db->order_by('id', 'desc');
		$query = $this->db->get_where('stock', array('readerId' => $readerId, 'statue' => 'transfor'));
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
	
	function getByBookId($bookId){
		$this->db->order_by('id', 'desc');
		$query = $this->db->get_where('stock', array('bookId' => $bookId));
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
	
	function insert($bookId, $userId){
		$this->bookId = $bookId;
		$this->ownerId = $userId;
		$this->readerId = $userId;
		$this->time = time();
		$this->transforTime = time();
		$this->finishTime = time();
		$this->statue = 'available';
		$this->readerStatue = 'live';
		
		$this->db->insert('stock',$this);
		return $this->db->insert_id();
	}
	
	function delete($bookId, $userId){
		$this->db->where('bookId', $bookId);
		$this->db->where('ownerId', $userId);
		$this->db->where('readerId', $userId);
		$this->db->delete('stock'); 
	}
	
	function getNumber($statue = 'all', $distinct=false){
		if ($statue == 'all'){
			return $this->db->count_all('stock');
		}
		
		$sql[] = 'SELECT * FROM bf_stock';
		$sql[] = "WHERE readerStatue = 'live'";
		$sql[] = "AND statue = '$statue'";
		if($distinct){
			$sql[] = 'GROUP BY bf_stock.bookId';
		}
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		return $query->num_rows();
	}
  
	function getNumberByArea($followProvinces, $followCities){
		$sql[] = 'SELECT * FROM bf_stock,bf_address';
		$sql[] = 'WHERE bf_stock.readerId = bf_address.uid';
		$sql[] = "AND (bf_address.province IN ($followProvinces) OR bf_address.city IN ($followCities))";
		$sql[] = 'AND bf_stock.statue = "available"';
		$sql[] = 'GROUP BY bf_stock.bookId';
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		return $query->num_rows();
	}
	
	function changeStatue($stockId, $statue){
		$data = array(
			'statue' => $statue
    );

		$this->db->where('id', $stockId);
		$this->db->update('stock', $data); 
	}
	
	function changeReader($stockId, $newReaderId){
		$data = array(
			'readerId' => $newReaderId,
			'transforTime' => time(),
			'statue' => 'reading',
    );

		$this->db->where('id', $stockId);
		$this->db->update('stock', $data); 
	}
	
	function finishReading($stockId, $readerId){
		$stock = $this->getById($stockId);
		if ( $stock->readerId != $readerId ){
			return false;
		}
		
		$data = array(
			'finishTime' => time(),
			'statue' => 'available'
   	);
		
		$this->db->where('id', $stockId);
		$this->db->update('stock', $data); 
	}
  
	// set stock available
	function setAvailable($stockId){
		$data = array(
			'finishTime' => time(),
			'statue' => 'available'
		);
    
		$this->db->where('id', $stockId);
		$this->db->update('stock', $data); 
	}
	
	function getAvailableStocks($bookId){
		$this->db->where('bookId', $bookId);
		$this->db->where('statue', 'available');
		$this->db->where('readerStatue', 'live');
		$query = $this->db->get('stock');
		if ($query->row()){
			return $query->result();
		} else {
			return array();;
		}
	}
  
	// 获取某库存书的传递人数
	function getReadersCount($stockId){
		$sql[] = "SELECT * FROM bf_borrowrecord";
		$sql[] = "WHERE bf_borrowrecord.stockId = $stockId";
		$sql[] = "AND bf_borrowrecord.statue>=4";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		return $query->num_rows();
	}
  
	// 获取某库存书抵达的城市
	function getReadersCityCount($stockId){
		$sql[] = "SELECT DISTINCT bf_borrowrecord.receiverCity FROM bf_borrowrecord";
		$sql[] = "WHERE bf_borrowrecord.stockId = $stockId";
		$sql[] = "AND bf_borrowrecord.statue>=4";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		return $query->num_rows();
	}

	function getBorrowRecords($stockId){
		$this->db->where('stockId', $stockId);
		$this->db->where_in('statue', array(4, 5, 6));
		$this->db->order_by('time4', 'asc');
		$query = $this->db->get('borrowrecord');
		if ($query->row()){
			return $query->result();
		} else {
			return array();;
		}
	}
	
	function updateReaderStatue($readerId, $readerStatue){
		$data = array(
			'readerStatue' => $readerStatue
		);
    
		$this->db->where('readerId', $readerId);
		$this->db->where('statue', 'available');
		$this->db->update('stock', $data);
	}
	
	function deleteUserDonateStatue($userId){
		$sql[] = "DELETE FROM bf_stock";
		$sql[] = "WHERE ownerId = '$userId'";
		$sql[] = "AND readerId = '$userId'";
		$sql[] = "AND statue = 'available'";
		$sql[] = "AND time = transforTime";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
	}
}