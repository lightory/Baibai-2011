<?php
// system/application/models/mhistory.php
class MHistory extends CI_Model{
	function __construct(){
        parent::__construct();
    }

	function getDonateStocks($userId){
		$sql[] = "SELECT bf_stock.*, bf_borrowrecord.sentTime";
		$sql[] = "FROM bf_borrowrecord, bf_stock";
		$sql[] = "WHERE bf_borrowrecord.senterUid = $userId";
		$sql[] = "AND bf_borrowrecord.statue in (4,5,6)";
		$sql[] = "AND bf_borrowrecord.stockId = bf_stock.id";
		$sql[] = "AND bf_stock.ownerId = $userId";
		$sql[] = "ORDER BY bf_stock.transforTime DESC";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
  
	function getBorrowStocks($userId){
		$sql[] = "SELECT bf_stock.*, record1.senterUid, record2.receiverUid, record1.receiveTime, record2.sentTime";
		$sql[] = "FROM bf_borrowrecord record1, bf_borrowrecord record2, bf_stock";
		$sql[] = "WHERE record1.receiverUid = $userId";
		$sql[] = "AND record1.statue in (4,5,6)";
		$sql[] = "AND record1.stockId = record2.stockId";
		$sql[] = "AND record2.senterUid = $userId";
		$sql[] = "AND record2.statue in (4,5,6)";
		$sql[] = "AND record1.stockId = bf_stock.id";
		$sql[] = "ORDER BY record2.sentTime DESC";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
}