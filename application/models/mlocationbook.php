<?php
// system/application/models/mlocationbook.php
class MLocationBook extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	// 获取 某城市 可借出的书籍
  function getAvailableBooksByCity($cityName, $order='desc', $limit=10, $offset=0){
    $sql[] = 'SELECT DISTINCT bf_book.* FROM bf_book,bf_stock,bf_address';
    $sql[] = "WHERE bf_stock.statue = 'available'";
    $sql[] = 'AND bf_stock.readerId = bf_address.uid';
    $sql[] = "AND bf_address.city = '$cityName'";
    $sql[] = 'AND bf_stock.bookId = bf_book.id';  
		switch($order){
			case 'desc':
				$sql[] = 'ORDER BY bf_stock.finishTime DESC';
				break;
			case 'rand':
				$sql[] = 'ORDER BY RAND()';
				break;
		}
    $sql[] = "LIMIT $offset,$limit";
    $sql = implode(' ', $sql);
    $query = $this->db->query($sql);
    if ($query->row()){
			return $query->result();
    } else {
			return array();
    }
  }

	function getAvailableBooksCountByCity($cityName){
		$sql[] = 'SELECT DISTINCT bf_book.* FROM bf_book,bf_stock,bf_address';
    $sql[] = "WHERE bf_stock.statue = 'available'";
    $sql[] = 'AND bf_stock.readerId = bf_address.uid';
    $sql[] = "AND bf_address.city = '$cityName'";
    $sql[] = 'AND bf_stock.bookId = bf_book.id';  
    $sql = implode(' ', $sql);
    $query = $this->db->query($sql);
    return $query->num_rows();
	}
}