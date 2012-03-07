<?php
// system/application/models/mborrowrecord.php
class MBorrowRecord extends CI_Model{
	var $bookId = '';
	var $stockId = '';
	var $senterUid = '';
	var $senterName = '';
	var $senterPostcode = '';
	var $senterProvince = '';
	var $senterCity = '';
	var $senterDistrict = '';
	var $senterAddress = '';
	var $senterPhone = '';
	var $receiverUid = '';
	var $receiverName = '';
	var $receiverPostcode = '';
	var $receiverProvince = '';
	var $receiverCity = '';
	var $receiverDistrict = '';
	var $receiverAddress = '';
	var $receiverPhone = '';
	var $message = '';
	var $expressType = '';
	var $expressId = '';
	var $sentTime = '';
	var $receiveTime = '';
	var $statue = '';
	var $time0 = '';
	var $time1 = '';
	var $time2 = '';
	var $time3 = '';
	var $time4 = '';
	var $finishMessage = '';
	var $finishTime = '';
	
	function __construct(){
        parent::__construct();
    }
	
	// 获取指定id的借书记录
	function getById($id){
		$query = $this->db->get_where('borrowrecord', array('id' => $id), 1, 0);
		if ( $query->row() ){
			return $query->row();
		} else {
			return false;
		}
	}
	
	// 获取指定stockId的借书记录
	function getByStockId($stockId){
		$query = $this->db->get_where('borrowrecord', array('stockId' => $stockId), 1, 0);
		if ( $query->row() ){
			return $query->row();
		} else {
			return false;
		}
	}
  
	// 获取制定receiverUid和stockId的借书记录
	function getByReceiverUidAndStockId($receiverUid, $stockId){
		$this->db->where('receiverUid', $receiverUid);
		$this->db->where('stockId', $stockId);
		$this->db->where_in('statue', array(4, 5));
		$query = $this->db->get('borrowrecord', 1, 0);
		if ( $query->row() ){
			return $query->row();
		} else {
			return false;
		}
	}

	// 获取正在进行中的借书记录
	function getUnfishedByStockId($stockId){
		$this->db->where('stockId', $stockId);
		$this->db->where('statue <', 5);
		$query = $this->db->get('borrowrecord', 1, 0);
		if ( $query->row() ){
			return $query->row();
		} else {
			return false;
		}
	}
	
	// 获取已完成的借书记录
	function getFinishedByBookId($bookId){
		$this->db->where('bookId', $bookId);
		$this->db->where_in('statue', array(5, 6));
		$query = $this->db->get('borrowrecord');
		if ( $query->row() ){
			return $query->result();
		} else {
			return array();
		}
	}
  
	// 获取指定状态的借书记录
	function getRecordsByStatue($statues){
		$this->db->where_in('statue', $statues);
		$query = $this->db->get('borrowrecord');
		if ( $query->row() ){
			return $query->result();
		} else {
			return array();
		}
	}
	
	// 获取某用户所有的借入书记录
	function getByReceiver($receiverUid){
		$this->db->where('statue !=', 0);
		$this->db->where('receiverUid',  $receiverUid);
		$query = $this->db->get('borrowrecord');
		if ( $query->row() ){
			return $query->result();
		} else {
			return array();
		}
	}
	
	// 删除借书记录
	function deleteRecord($recordId){
		$this->db->where('id', $recordId);
		$this->db->delete('borrowrecord'); 
	}
	
	// 获取Statue=4的借书记录
	function getStatue4Record($senterUid){
		$this->db->order_by('id', 'desc');
		$query = $this->db->get_where('borrowrecord', array('senterUid' => $senterUid, 'statue' => 4));
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
	
	// 获取某用户借过的书
	function getBorrowedRecord($receiverUid, $limit=100, $offset=0){
		$this->db->order_by('id', 'desc');
		$query = $this->db->get_where('borrowrecord', array('receiverUid' => $receiverUid, 'statue' => 5), $limit, $offset);
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
	
	// 获取某用户借过的书 数量
	function getBorrowedCount($receiverUid){
		$query = $this->db->get_where('borrowrecord', array('receiverUid' => $receiverUid, 'statue' => 5));
		return $query->num_rows();
	}
	
	// 获取库存数
	function getNumber($statue = '0'){
		if ($statue == 'all'){
			return $this->db->count_all('borrowrecord');
		}
		
		$sql[] = 'SELECT * FROM bf_borrowrecord';
		$sql[] = "WHERE statue = '$statue'";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		return $query->num_rows();
	}

  // ADMIN，获取借书记录
  function getRecords($statue, $limit=100, $offset=0){
    if ($statue==5){
      $this->db->order_by('time4', 'desc');
    } else{
      $this->db->order_by('time'.$statue, 'desc');
    }
    $query = $this->db->get_where('borrowrecord', array('statue' => $statue), $limit, $offset);
    if ($query->row()){
      return $query->result();
    } else {
      return array();
    }
  }

	// ADMIN，获取record4&record5
	function getRecords4and5($limit=100, $offset=0){
		$this->db->order_by('time4', 'desc');
		$this->db->where_in('statue', array('4', '5'));
		$query = $this->db->get('borrowrecord', $limit, $offset);
    if ($query->row()){
      return $query->result();
    } else {
      return array();
    }
	}
	
	//ADMIN,获得流通中的摆摆卷
	function getBaiBaiTranCount(){
		return $this->getNumber('1')+$this->getNumber('2')+$this->getNumber('3');
	}
	
	# Step 2, senter选择receiver
	function updateSenter($borrowRequest, $stockId, $senterAddress, $senter){
		$this->bookId = $borrowRequest->bookId;
		$this->receiverUid = $borrowRequest->userId;
		$this->message = $borrowRequest->message;
		$this->time0 = $borrowRequest->time;
		$this->stockId = $stockId;
		$this->senterUid = $senterAddress->uid;
		$this->senterName = $senterAddress->name;
		$this->senterPostcode = $senterAddress->postcode;
		$this->senterProvince = $senterAddress->province;
		$this->senterCity = $senterAddress->city;
		$this->senterDistrict = $senterAddress->district;
		$this->senterAddress = $senterAddress->address;
		$this->senterPhone = $senter->mobile;
		$this->time1 = time();
		$this->statue = 1;

		$this->db->insert('borrowrecord',$this);
		return $this->db->insert_id();
	}
	
	# Step 3, receiver 提供收货地址
	function updateReceiverAddress($recordId, $receiverAddress, $receiver){
		$data = array(
			'receiverUid'          => $receiverAddress->uid,
			'receiverName'      => $receiverAddress->name,
			'receiverPostcode' => $receiverAddress->postcode,
			'receiverProvince'  => $receiverAddress->province,
			'receiverCity'         => $receiverAddress->city,
			'receiverDistrict'    => $receiverAddress->district,
			'receiverAddress'  => $receiverAddress->address,
			'receiverPhone'     => $receiver->mobile,
			'time2'                   => time(),
			'statue'               => 2
        );

		$this->db->where('id', $recordId);
		$this->db->update('borrowrecord', $data); 
	}
	
	# Step3, Back
	function deleteSenter($recordId){
		$data = array(
            'stockId'              => '',
			'senterUid'          => '',
			'senterName'      => '',
			'senterPostcode' => '',
			'senterProvince'  => '',
			'senterCity'         => '',
			'senterDistrict'    => '',
			'senterAddress'  => '',
			'senterPhone'     => '',
			'statue'               => 0
        );

		$this->db->where('id', $recordId);
		$this->db->update('borrowrecord', $data); 
	}
	
	# Step 4, senter 填写发货信息
	function updateSentInfo($recordId,  $expressType, $expressId, $sentTime){
		$data = array(
			'expressType'     => $expressType,
			'expressId'          => $expressId,
			'sentTime'           => $sentTime,
			'time3'                   => time(),
			'statue'               => 3
        );

		$this->db->where('id', $recordId);
		$this->db->update('borrowrecord', $data); 
	}
	
	# Step5, receiver 确认收货
	function updateReceiveInfo($recordId, $receiveTime){
		$data = array(
			'receiveTime' => $receiveTime,
			'time4'             => time(),
			'statue'         => 4
		);
		
		$this->db->where('id', $recordId);
		$this->db->update('borrowrecord', $data); 
	}
	
	# Step 6, senter 确认奖励
	function finish($recordId){
		$data = array(
			'statue'         => 5
		);
		
		$this->db->where('id', $recordId);
		$this->db->update('borrowrecord', $data); 
	}
  
  // 还书入库
  function returnbook($recordId){
    $data = array(
      'statue' => 6
    );
    
    $this->db->where('id', $recordId);
    $this->db->update('borrowrecord', $data); 
  }
  
  // 更新送书赠言
  function updateFinishMessage($recordId, $finishMessage){
    $data = array(
      'finishMessage' => $finishMessage,
      'finishTime' => time(),
    );
    
    $this->db->where('id', $recordId);
    $this->db->update('borrowrecord', $data); 
  }
  
  // 获取节省钱数
  function getSavedMoney(){
    $sql[] = "SELECT bf_book.*";
    $sql[] = "FROM bf_borrowrecord,bf_book";
    $sql[] = "WHERE bf_borrowrecord.statue >= 4";
    $sql[] = "AND bf_borrowrecord.bookId = bf_book.id";
    $sql = implode(' ', $sql);
    $query = $this->db->query($sql);
    
    $books = $query->result();
    $savedMoney = 0;
    foreach($books as $book){
      $savedMoney += $book->price - 10;
    }
    return $savedMoney;
  }
  
  // 获取需要处理的交易数量
  function getToDoCount($userId){
  	$sql[] ="SELECT * FROM bf_borrowrecord";
		$sql[] = "WHERE ( receiverUid = $userId AND statue IN (1,3) )";
		$sql[] = "OR ( senterUid = $userId AND statue IN (2,4) )";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		return $query->num_rows();
  }

	function getBorrowRequestToMe($uid , $type='available'){
		$sql[] = "SELECT bf_book.id, bf_book.name, bf_user.nickname, bf_user.email, '$type' type";
		$sql[] = "FROM bf_stock, bf_borrowrecord, bf_user, bf_book";
		$sql[] = "WHERE bf_stock.readerId = $uid";
		$sql[] = "AND bf_stock.statue = '$type'";
		$sql[] = "AND bf_stock.bookId = bf_book.id";
		$sql[] = "AND bf_book.id = bf_borrowrecord.bookId";
		$sql[] = "AND bf_borrowrecord.statue = 0";
		$sql[] = "AND bf_borrowrecord.receiverUid = bf_user.uid";
		$sql[] = "AND bf_user.borrowQuote >= 1";
		$sql[] = "ORDER BY bf_borrowrecord.time0 DESC";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		if ($query->row()){
      return $query->result();
    } else {
      return array();
    }
	}
}