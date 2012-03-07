<?php
// system/application/models/mbook.php
class MBook extends CI_Model{
	var $isbn = '';
	var $doubanId = '';
	var $name = '';
	var $summary = '';
	var $pic = '';
	var $author = '';
	var $translator = '';
	var $publisher = '';
	var $pubdate = '';
	var $price = '';
	var $time ='';
	
	function __construct(){
        parent::__construct();
    }
	
	// 获取最新的书籍列表
	function get($limit=10, $offset=0){
		$this->db->order_by('id', 'desc');
		$query = $this->db->get('book',  $limit, $offset);
		if ($query->row()){
			return $query->result();
		} else {
			return array();;
		}
	}
	
	// 获取指定id的书籍
	function getById($id){
		$query = $this->db->get_where('book', array('id' => $id), 1, 0);
		if ($query->row()){
			return $query->row();
		} else {
			return false;
		}
	}
	
	// 获取指定isbn的书籍
	function getByIsbn($isbn){
		$query = $this->db->get_where('book', array('isbn' => trim($isbn)), 1, 0);
		if ($query->row()){
			return $query->row();
		} else {
			return false;
		}
	}
	
	// 获取指定doubanId的书籍
	function getByDoubanId($doubanId){
		$query = $this->db->get_where('book', array('doubanId' => trim($doubanId)), 1, 0);
		if ($query->row()){
			return $query->row();
		} else {
			return false;
		}
	}
	
	// 添加书籍
	function insert($book) {
		if (strpos($book->pic, 'default')) {
			$book->pic = '';
		}
		
		$this->isbn = $book->isbn;
		$this->doubanId = $book->doubanId;
		$this->name = $book->name;
		$this->summary = $book->summary;
		$this->pic = $book->pic;
		$this->author = $book->author;
		$this->translator = $book->translator;
		$this->publisher = isset($book->publisher) ? $book->publisher : '';
		$this->pubdate = isset($book->pubdate) ? $book->pubdate : '';
		$this->price = isset($book->price) ? $book->price : '20';
		$this->time = time();

		$this->db->insert('book',$this);
		return $this->db->insert_id();
	}
  
	function updateCover($bookId, $picFid, $picId){
		$CI = & get_instance();
		$CI->load->helper('image');
		$CI->load->library('image_lib'); 
    
		// grabImage
		$book = $this->getById($bookId);
		$path = 'upload/book/cover/'.$picFid.'/';
		$filename = $picId.'.jpg';
		grabImage($book->pic, $path, $filename);
    
		// export 2 small size
		list($width, $height, $type, $attr) = getimagesize($path.$filename);
		$config['image_library'] = 'GD2';
		$config['source_image'] = $path.$filename;
		$config['new_image'] = $path.$picId.'_100.jpg';
		$config['width'] = 100;
		$config['height'] = round($height*100/$width);
		$CI->image_lib->initialize($config); 
		if (!$CI->image_lib->resize()){
			//echo $CI->image_lib->display_errors();
		}
    
		$config['image_library'] = 'GD2';
		$config['source_image'] = $path.$filename;
		$config['new_image'] = $path.$picId.'_70.jpg';
		$config['width'] = 70;
		$config['height'] = round($height*70/$width);
		$CI->image_lib->initialize($config); 
		if (!$CI->image_lib->resize()){
			//echo $CI->image_lib->display_errors();
		}
    
		// update database
		$data = array(
			'picFid' => $picFid,
			'picId' => $picId
		);
		$this->db->where('id', $bookId);
		$this->db->update('book', $data); 
	}
	
	// 获取某本书籍的库存量
	function getInventory($bookId){
		$query = $this->db->get_where('stock', array('bookId' => $bookId));
		return $query->num_rows();
	}
	
	// 获取某本书籍的可用库存量
	function getRealInventory($bookId){
		$this->db->where('statue', 'available');
		$this->db->where('readerStatue', 'live');
		$this->db->where('bookId', $bookId);
		$query = $this->db->get('stock');
		return $query->num_rows();
	}
	
	// 获取某本书籍的需求量
	function getNeeds($bookId){
		$sql[] = 'SELECT * FROM bf_borrowrequest,bf_user';
		$sql[] = "WHERE bf_borrowrequest.bookId = $bookId";
		$sql[] = 'AND bf_borrowrequest.userId = bf_user.uid';
		$sql[] = 'AND bf_user.borrowQuote > 0';
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		return $query->num_rows();
	}
	
	function getRandomWantedBooks($limit=10){
		$sql[] = 'SELECT DISTINCT bf_book.* FROM bf_book, bf_borrowrequest, bf_user';
		$sql[] = "WHERE bf_borrowrequest.userId = bf_user.uid";
		$sql[] = "AND bf_user.borrowQuote > 0";
		$sql[] = "AND bf_borrowrequest.bookId = bf_book.id";
		$sql[] = 'ORDER BY RAND()';
		$sql[] = "LIMIT $limit";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
	
	function getLastestWantedBooks($limit=10, $offset=0){
		$sql[] = 'SELECT DISTINCT bf_book.* FROM bf_book, bf_borrowrequest, bf_user';
		$sql[] = "WHERE bf_borrowrequest.userId = bf_user.uid";
		$sql[] = "AND bf_user.borrowQuote > 0";
		$sql[] = "AND bf_borrowrequest.bookId = bf_book.id";
		$sql[] = 'ORDER BY bf_borrowrequest.time DESC';
		$sql[] = "LIMIT $offset, $limit";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
	
	function getWantedBooksCount(){
		$sql[] = 'SELECT DISTINCT bf_book.* FROM bf_book, bf_borrowrequest, bf_user';
		$sql[] = "WHERE bf_borrowrequest.userId = bf_user.uid";
		$sql[] = "AND bf_user.borrowQuote > 0";
		$sql[] = "AND bf_borrowrequest.bookId = bf_book.id";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		return $query->num_rows();
	}
	
	// 随机索取 可借出的 书籍
	function getRandomAvailableBooks($limit=10){
		$sql[] = 'SELECT DISTINCT bf_book.* FROM bf_book,bf_stock';
		$sql[] = "WHERE bf_stock.statue = 'available'";
		$sql[] = 'AND bf_stock.bookId = bf_book.id';
		$sql[] = 'AND bf_book.id >= (SELECT floor(RAND()*(SELECT MAX(id) FROM bf_book)))';
		$sql[] = "LIMIT $limit";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
	
	function getRandomAvailableBooksByTag($tagName, $limit=10){
		$sql[] = 'SELECT DISTINCT bf_book.* FROM bf_book,bf_stock,bf_tag';
		$sql[] = "WHERE bf_tag.tag = '$tagName'";
		$sql[] = "AND bf_tag.bookId = bf_book.id";
		$sql[] = 'AND bf_stock.bookId = bf_book.id';
		$sql[] = "AND bf_stock.statue = 'available'";
		$sql[] = 'AND bf_book.id >= (SELECT floor(RAND()*(SELECT MAX(id) FROM bf_book)))';
		$sql[] = "LIMIT $limit";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
	
	// 获取 最近 可借出的 书籍
	function getLastestAvailableBooks($limit=10, $offset=0){
		$sql[] = 'SELECT DISTINCT bf_book.*,bf_stock.finishTime FROM bf_book,bf_stock';
		$sql[] = "WHERE bf_stock.statue = 'available'";
		$sql[] = 'AND bf_stock.bookId = bf_book.id';
		$sql[] = 'ORDER BY bf_stock.finishTime DESC';
		$sql[] = "LIMIT $offset,$limit";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
	
	// 获取 热门的 可借出的 书籍
	function getHottestAvailableBooks($limit=10, $offset=0){
		$sql[] = 'SELECT bf_book.*,count(stock.id) count FROM bf_book,bf_stock';
		$sql[] = "WHERE bf_stock.statue = 'available'";
		$sql[] = 'AND bf_stock.bookId = bf_book.id';
		$sql[] = 'GROUP BY bf_stock.bookId';
		$sql[] = 'ORDER BY count DESC,bf_stock.finishTime DESC';
		$sql[] = "LIMIT $offset,$limit";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
	
	// 搜索
	function search($q, $limit=10, $offset=0){
		$q = $this->db->escape_like_str($q);
		$sql[] = 'SELECT * FROM bf_book';
		$sql[] = "WHERE name LIKE '%$q%'";
		$sql[] = "OR author LIKE '%$q%'";
		$sql[] = "OR translator LIKE '%$q%'";
		$sql[] = "OR publisher LIKE '%$q%'";
		$sql[] = "LIMIT $offset,$limit";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
	
	// 搜索结果量
	function searchCount($q){
		$q = $this->db->escape_like_str($q);
		$sql[] = 'SELECT * FROM bf_book';
		$sql[] = "WHERE name LIKE '%$q%'";
		$sql[] = "OR author LIKE '%$q%'";
		$sql[] = "OR translator LIKE '%$q%'";
		$sql[] = "OR publisher LIKE '%$q%'";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		return $query->num_rows();
	}
	
	// 判断用户是否是求书者
	function isBorrower($receiverUid, $bookId){
		$query = $this->db->get_where('borrowrecord', array('receiverUid' => $receiverUid, 'bookId' => $bookId), 1, 0);
		if ( ($record = $query->row() ) && ($record->statue < 5 ) ){
			return $record;
		} else {
			return false;
		}
	}
	
	function isWanter($userId, $bookId) {
		$query = $this->db->get_where('borrowrequest', array('userId' => $userId, 'bookId' => $bookId), 1, 0);
		if ( $request = $query->row() ){
			return $request;
		} else {
			return false;
		}
	}
	
	// 某用户是否正拥有此书
	function isReader($readerId, $bookId){
		$query = $this->db->get_where('stock', array('readerId' => $readerId, 'bookId' => $bookId), 1, 0);
		if ($query->row()){
			return $query->row();
		} else {
			return false;
		}
	}
	
	// 某用户是否捐赠过此书
	function isOwner($ownerId, $bookId){
		$query = $this->db->get_where('stock', array('ownerId' => $ownerId, 'bookId' => $bookId), 1, 0);
		if ($query->row()){
			return $query->row();
		} else {
			return false;
		}
	}

	// 获取书籍总数
	function getNumber(){
		return $this->db->count_all('book');
	}
	
	// 获取某个tag的书籍
	function getBooksOfTag($tagName, $limit=10, $offset=0){
		$sql[] = 'SELECT bf_book.*,count(bf_book.id) count';
		$sql[] = 'FROM bf_tag,bf_book,bf_stock';
		$sql[] = "WHERE bf_tag.tag = '$tagName'";
		$sql[] = "AND bf_book.id = bf_tag.bookId";
		$sql[] = "AND bf_book.id = bf_stock.bookId";
		$sql[] = 'GROUP BY bf_book.id';
		$sql[] = 'ORDER BY bf_stock.finishTime DESC';
		$sql[] = "LIMIT $offset,$limit";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
  
	// 获取 区域内 某个tag的书籍
	function getBooksOfTagByArea($followProvinces, $followCities, $tagName, $limit=0, $offset=0){
		$sql[] = 'SELECT bf_book.*,count(bf_book.id) count';
		$sql[] = 'FROM bf_tag,bf_book,bf_stock,bf_address';
		$sql[] = "WHERE bf_tag.tag = '$tagName'";
		$sql[] = "AND bf_tag.bookId = bf_stock.bookId";
		$sql[] = 'AND bf_stock.readerId = bf_address.uid';
		$sql[] = "AND (bf_address.province IN ($followProvinces) OR bf_address.city IN ($followCities))";
		$sql[] = 'AND bf_stock.bookId = bf_book.id';
		$sql[] = 'GROUP BY bf_book.id';
		$sql[] = 'ORDER BY bf_stock.finishTime DESC';
		$sql[] = "LIMIT $offset,$limit";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
  
	// 获取某个Tag的书籍数量
	function getBooksCountOfTag($tagName){
		$CI = & get_instance();
		$CI->load->driver('cache', array('adapter' => 'memcached', 'backup' => 'file'));
		$CI->cache->memcached->is_supported();
		
		$cacheName = "tag{$tagName}-count";
		if ( ! $results = $CI->cache->get($cacheName)) {
			$sql[] = 'SELECT bf_book.*,count(bf_book.id) count';
			$sql[] = 'FROM bf_tag,bf_book,bf_stock';
			$sql[] = "WHERE bf_tag.tag = '$tagName'";
			$sql[] = "AND bf_book.id = bf_tag.bookId";
			$sql[] = "AND bf_book.id = bf_stock.bookId";
			$sql[] = 'GROUP BY bf_book.id';
			$sql = implode(' ', $sql);
			$query = $this->db->query($sql);
			$results = $query->num_rows();
			$CI->cache->save($cacheName, $results, 24*60*60);
		}
		return $results;
	}
  
	// 获取 区域内 某个Tag 书籍数量
	function getBooksCountOfTagByArea($followProvinces, $followCities, $tagName){
		$sql[] = 'SELECT DISTINCT bf_book.*';
		$sql[] = 'FROM bf_tag,bf_book,bf_stock,bf_address';
		$sql[] = "WHERE bf_tag.tag = '$tagName'";
		$sql[] = "AND bf_tag.bookId = bf_stock.bookId";
		$sql[] = 'AND bf_stock.readerId = bf_address.uid';
		$sql[] = "AND (bf_address.province IN ($followProvinces) OR bf_address.city IN ($followCities))";
		$sql[] = 'AND bf_stock.bookId = bf_book.id';
		$sql[] = 'GROUP BY bf_book.id';
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		return $query->num_rows();
	}
  
	// 获取持书人信息
	function getReadersFromDoubanId($doubanId){
		$sql[] = "SELECT bf_user.uid,bf_user.nickname,bf_user.email,bf_address.province";
		$sql[] = "FROM bf_user,bf_address,bf_book,bf_stock";
		$sql[] = "WHERE bf_book.doubanId = '$doubanId'";
		$sql[] = "AND bf_book.id = bf_stock.bookId";
		$sql[] = "AND bf_stock.readerId = bf_user.uid";
		$sql[] = "AND bf_address.uid = bf_user.uid";
		$sql[] = "AND bf_address.def = '1'";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
  
	// 在读的书中，有多少本有人想借
	function getOnhandNoticeCount($userId){
		$sql[] = "SELECT * FROM bf_stock, bf_borrowrequest, bf_user";
		$sql[] = "WHERE bf_stock.readerId = $userId";
		$sql[] = "AND bf_stock.statue = 'reading'";
		$sql[] = "AND bf_borrowrequest.bookId = bf_stock.bookId";
		$sql[] = "AND bf_borrowrequest.userId = bf_user.uid";
		$sql[] = "AND bf_user.borrowQuote > 0";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		return $query->num_rows();
	}
  
	// 可借的书中，有多少本有人想借
	function getAvailableNoticeCount($userId){
		$sql[] = "SELECT * FROM bf_stock, bf_borrowrequest, bf_user";
		$sql[] = "WHERE bf_stock.readerId = $userId";
		$sql[] = "AND bf_stock.statue = 'available'";
		$sql[] = "AND bf_borrowrequest.bookId = bf_stock.bookId";
		$sql[] = "AND bf_borrowrequest.userId = bf_user.uid";
		$sql[] = "AND bf_user.borrowQuote > 0";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		return $query->num_rows();
	}
  
	// 获取相关书籍
	function getRelatedBooks($bookId, $limit=10){
		$CI = & get_instance();
		$CI->load->driver('cache', array('adapter' => 'memcached', 'backup' => 'file'));
		$CI->cache->memcached->is_supported();
		
		$cacheName = "book{$bookId}-{$limit}relatedBooks";
		if ( ! $results = $CI->cache->get($cacheName)) {
			$sql[] = "SELECT bf_book.*,COUNT(t2.bookId) count FROM bf_tag t1,bf_tag t2,bf_book";
			$sql[] = "WHERE t1.bookId = $bookId";
			$sql[] = "AND t2.tag = t1.tag";
			$sql[] = "AND t2.bookId = bf_book.id";
			$sql[] = "AND bf_book.id != $bookId";
			$sql[] = "GROUP BY bf_book.id";
			$sql[] = "ORDER By count DESC";
			$sql[] = "LIMIT 0, $limit";
			$sql = implode(' ', $sql);
			$query = $this->db->query($sql);
			if ($query->row()){
				$results =  $query->result();
			} else {
				$results = array();
			}
			
			$CI->cache->save($cacheName, $results, 24*60*60);
		}
		
		return $results;
	}
	
	function getSwitchBooksByDonate($bookId, $limit=10){
		$sql[] = "SELECT DISTINCT bf_book.* FROM bf_book, bf_stock, bf_borrowrequest";
		$sql[] = "WHERE bf_borrowrequest.bookId = {$bookId}";
		$sql[] = "AND bf_borrowrequest.userId = bf_stock.readerId";
		$sql[] = "AND bf_stock.statue = 'available'";
		$sql[] = "AND bf_stock.bookId = bf_book.id";
		$sql[] = "ORDER BY RAND()";
		$sql[] = "LIMIT 0, $limit";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
	
	function getSwitchBooksByBorrow($bookId, $limit=10){
		$sql[] = "SELECT DISTINCT bf_book.* FROM bf_book, bf_stock, bf_borrowrecord";
		$sql[] = "WHERE bf_stock.bookId = {$bookId}";
		$sql[] = "AND bf_stock.statue = 'available'";
		$sql[] = "AND bf_borrowrecord.receiverUid = bf_stock.readerId";
		$sql[] = "AND bf_borrowrecord.statue = 0";
		$sql[] = "AND bf_borrowrecord.bookId = bf_book.id";
		$sql[] = "ORDER BY RAND()";
		$sql[] = "LIMIT 0, $limit";
		$sql = implode(' ', $sql);
		$query = $this->db->query($sql);
		if ($query->row()){
			return $query->result();
		} else {
			return array();
		}
	}
}