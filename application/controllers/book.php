<?php

class Book extends CI_Controller {
	function __construct(){
		parent::__construct();
		if($this->session->userdata('uid')==1){
			$this->output->enable_profiler(TRUE);
		}
	}
	
	function index(){
		$this->all();
	}
  
	function all($offset=0){
		$this->load->model('MUser');
		$this->load->model('MAddress');
		$this->load->model('MBook');
		$this->load->model('MStock');
		$this->load->model('MTag');
		$this->load->model('MLocation');
		$this->load->helper('gravatar');
		$this->load->library('pagination');

		$headerData['title'] = '书架 | 摆摆书架';
		$headerData['current'] = 'lib';
		$data['lastestAvailableBooks'] = $this->MBook->getLastestAvailableBooks('20', $offset, false);
		$data['users'] = $this->MUser->getDonateList();

		// get Location
		if($userId = $this->session->userdata('uid')){
			$address = $this->MAddress->getDefaultByUid($userId);
			$location = $this->MLocation->getByCityName($address->city);
			$data['location'] = $location;
		}
		
		// slideBooks
		$slideBooks['title'] = '随机推荐';
		$slideBooks['books'] = $this->MBook->getRandomAvailableBooks('20', false);
		$data['slideBooks'] = $slideBooks;
    
		$config['total_rows'] = $this->MStock->getNumber('available',true);
		$config['full_tag_open'] = '<p class="pages">';
		$config['full_tag_close'] = '</p>';
		$config['uri_segment'] = 2;
		$config['per_page'] = '20'; 
		$config['num_links'] = 3;
		$config['base_url'] = site_url("book/");
		$this->pagination->initialize($config);

		$this->load->view('header', $headerData);
		$this->load->view('book/index', $data);
		$this->Common->footer();
	}
	
	function wanted($offset=0){
		$this->load->model('MUser');
		$this->load->model('MAddress');
		$this->load->model('MBook');
		$this->load->model('MStock');
		$this->load->model('MTag');
		$this->load->model('MLocation');
		$this->load->helper('gravatar');
		$this->load->library('pagination');

		$headerData['title'] = '大家想借 | 摆摆书架';
		$headerData['current'] = 'lib';
		$data['lastestWantedBooks'] = $this->MBook->getLastestWantedBooks('20', $offset, false);
		$data['users'] = $this->MUser->getDonateList();

		// get Location
		if($userId = $this->session->userdata('uid')){
			$address = $this->MAddress->getDefaultByUid($userId);
			$location = $this->MLocation->getByCityName($address->city);
			$data['location'] = $location;
		}
    
		$config['total_rows'] = $this->MBook->getWantedBooksCount();
		$config['full_tag_open'] = '<p class="pages">';
		$config['full_tag_close'] = '</p>';
		$config['uri_segment'] = 3;
		$config['per_page'] = '20'; 
		$config['num_links'] = 4;
		$config['base_url'] = site_url("book/wanted/");
		$this->pagination->initialize($config);

		$this->load->view('header', $headerData);
		$this->load->view('book/wanted', $data);
		$this->Common->footer();
	}

	function tag($tagName, $offset=0){    
        $this->load->model('MUser');
        $this->load->model('MAddress');
        $this->load->model('MBook');
        $this->load->model('MStock');
        $this->load->model('MTag');
        $this->load->model('MLocation');
        $this->load->helper('gravatar');
        $this->load->library('pagination');
    
        $headerData['title'] = "标签：$tagName";
        $headerData['current'] = 'lib';
        $data['title'] = "标签：$tagName";
		$data['users'] = $this->MUser->getDonateList();
		$tagName = str_replace('\'','\\\'', $tagName);
		$data['tagName'] = $tagName;
		$data['lastestAvailableBooks'] = $this->MBook->getBooksOfTag($tagName, 20, $offset);
		$data['tags'] = $this->MTag->getTags();

		// get Location
		if($userId = $this->session->userdata('uid')){
			$address = $this->MAddress->getDefaultByUid($userId);
			$location = $this->MLocation->getByCityName($address->city);
			$data['location'] = $location;
		}
		
		// slideBooks
		$slideBooks['title'] = '随机推荐';
		$slideBooks['books'] = $this->MBook->getRandomAvailableBooksByTag($tagName, '20');
		$data['slideBooks'] = $slideBooks;
        
		$config['total_rows'] = $this->MBook->getBooksCountOfTag($tagName);
		$config['full_tag_open'] = '<p class="pages">';
		$config['full_tag_close'] = '</p>';
		$config['uri_segment'] = 4;
		$config['per_page'] = '20'; 
		$config['num_links'] = 4;
		$config['base_url'] = site_url("book/tag/$tagName/");
		$this->pagination->initialize($config); 
    
		$this->load->view('header', $headerData);
		$this->load->view('book/index', $data);
		$this->Common->footer();
	}
	
	function search($q='', $offset=0){
		$this->load->model('MBook');
		$this->load->model('MTag');
		$this->load->library('pagination');
		
		$q = $q ? $q : $this->input->post('keyword');
		
		$headerData['title'] = "搜索：$q";
		$headerData['current'] = 'lib';
		
		$data['title'] = "搜索：$q";
		$data['books'] = $this->MBook->search($q, 10, $offset);
		$data['tags'] = $this->MTag->getTags();
		$data['scope'] = 'all';
		
		$config['full_tag_open'] = '<p class="pages">';
		$config['full_tag_close'] = '</p>';
		$config['uri_segment'] = 4;
		$config['per_page'] = '10'; 
		$config['base_url'] = site_url("book/search/$q/");
		$config['total_rows'] = $this->MBook->searchCount($q);
		$this->pagination->initialize($config); 
		
		$this->load->view('header', $headerData);
		$this->load->view('book/list', $data);
		$this->Common->footer();
	}
	
	function subject($id){
		if (!is_numeric($id)) {
			show_404();
		}
		
		$this->load->model('MBook');
    	$this->load->model('MStock');
    	$this->load->model('MTag');
		$this->load->model('MBorrowRecord');
    	$this->load->model('MUser');
    	$this->load->model('MAddress');
		$this->load->model('MGroup');
		$this->load->helper('gravatar');
    	$this->load->helper('string');
       
		$book = $this->MBook->getById($id);
		$stocks = $this->MStock->getByBookId($id);
		
		// 如果书籍不存在，404
		if (!$book){
			show_404();
		}
    
    	// 如果没保存tag，保存tag
    	if (!$this->MTag->isSystemTagExist($book->id)){
       		$this->load->helper('douban');
       		$bookInfo = getBookDetail($book->isbn);
       		if(isset($bookInfo->tags)){
         		foreach($bookInfo->tags as $tag){
           			$this->MTag->insert(0, $book->id, $tag);
         		}
       		}
    	}
		
		# 判断用户与此书的联系
		$data['userStatue'] = 'none';
		if ($userId = $this->session->userdata('uid')) {
			if ($data['record'] = $this->MBook->isWanter($userId, $id)){
				$data['userStatue'] = 'wanter';
				/*
				if ($data['record']->statue==0){
					$data['recordStatue'] = 'none';
				} else{
					$data['recordStatue'] = 'transfor';
				}
				*/
			} elseif ($this->MBook->isBorrower($userId, $id)) {
				$data['userStatue'] = 'borrower';
			} elseif ($data['stock'] = $this->MBook->isReader($userId, $id)){
				$data['userStatue'] = 'reader';
        		if ($this->MBook->isOwner($userId, $id)){
          			$data['userStatue2'] = 'owner';
        		}
			} elseif ($data['stock'] = $this->MBook->isOwner($userId, $id)){
				$data['userStatue'] = 'owner';
			}
		}
		
		$headerData['title'] = $book->name . ' - 摆摆书架';
		$headerData['current'] = 'lib';
		$data['book'] = $book;
    	$data['needs'] = $this->MBook->getNeeds($id);
		$data['stockNumber'] = $this->MBook->getInventory($id);
		$data['availableStockNumber'] = $this->MBook->getRealInventory($id);
		$data['stocks'] = $this->MStock->getByBookId($id); 
    	$data['tags'] = $this->MTag->getTagOfBook($id);
    	if($this->session->userdata('uid')){
      		$data['myTagsOfBook'] = $this->MTag->getMyTagOfBook($this->session->userdata('uid'), $id);
      		$data['myTags'] = $this->MTag->getTagOfUser($this->session->userdata('uid'));
    	}
    	$data['relatedBooks'] = $this->MBook->getRelatedBooks($id);
		
		$this->load->view('header', $headerData);
		$this->load->view('book/subject', $data);
		$this->Common->footer();
	}
	
	function stock($stockId){
		if (!is_numeric($stockId)) {
			show_404();
		}
		
		$this->load->model('MBook');
		$this->load->model('MStock');
		$this->load->model('MUser');
		$this->load->helper('gravatar');
		
		$stock = $this->MStock->getById($stockId);
		$book = $this->MBook->getById($stock->bookId);
		$borrowRecords = $this->MStock->getBorrowRecords($stockId);
		
		if(sizeof($borrowRecords)==0){
			show_404();
		}
		
		$headerData['title'] = $book->name . ' - 摆摆书架';
		$headerData['current'] = 'lib';
		$data['book'] = $book;
		$data['stock'] = $stock;
		$data['borrowRecords'] = $borrowRecords;
		
		$this->load->view('header', $headerData);
		$this->load->view('book/stock', $data);
		$this->Common->footer();
	}
	
	function isbn($isbn){
		if (!is_numeric($isbn)) {
			show_404();
		}
		
		$this->load->model('MBook');
		$book = $this->MBook->getByIsbn($isbn);
		
		redirect("book/subject/$book->id");
	}
	
	function doubanId($doubanId){
		if (!is_numeric($doubanId)) {
			show_404();
		}
		
		$this->load->model('MBook');
		$this->load->model('MTag');
		
		# 如果数据库中无此书，从豆瓣读取
		if (!$this->MBook->getByDoubanId($doubanId)){
			$this->load->helper('douban');
			$book = getBookDetailByDoubanId($doubanId);
			$bookId = $this->MBook->insert($book);
      
 			// add Tags
			foreach($book->tags as $tag){
				$this->MTag->insert(0, $bookId, $tag);
 			}
         
			// save Image
			$picFid = mdate('%Y%m', time());
			$picId = $bookId;
			$this->MBook->updateCover($bookId, $picFid, $picId);
		}
		$book = $this->MBook->getByDoubanId($doubanId);
		
		redirect("book/subject/$book->id");
	}
	
	function donate($isbn='step1'){
		$this->output->enable_profiler(FALSE);
		
		# 如果未登录，STOP
		$this->Validate->isLogin('fancybox');
	
		if ('step1' == $isbn){ // Step 1
			$this->load->view('book/donate');
		}else{ // Step 2
			$this->load->model('MBook');
			$this->load->model('MTag');
			$this->load->model('MUserOAuth');
      
			$data['book'] = $this->MBook->getByIsbn($isbn);
			$data['myTagsOfBook'] = $this->MTag->getMyTagOfBook($this->session->userdata('uid'), $data['book']->id);
			$data['myLinkedProviders'] = $this->MUserOAuth->getUserLinkedProviders($this->session->userdata('uid'));
			
			// 如果已经想借此书，STOP
			$this->Validate->isNotBookWanter($data['book']->id);
			
			$this->load->view('book/donate2', $data);
		} 
	}
	
	function donate_do($bookId){
		# 如果未登录，STOP
		$this->Validate->isLogin('fancybox');
		
		// 如果已经想借此书，STOP
		$this->Validate->isNotBookWanter($bookId);
    
		// 如果手上有这本书，STOP
		if ($this->MBook->isReader($this->session->userdata('uid'), $bookId)){
			$this->session->set_flashdata('title', 'OOPS...很抱歉');
			$this->session->set_flashdata('message', "对不起，您手上已经有这本书了，无法继续捐赠。");
			redirect("alert/failure");
		}
		
		# 在数据库中添加库存书记录
		$this->load->model('MStock');
		$userId = $this->session->userdata('uid');
		$stockId = $this->MStock->insert($bookId, $userId);
    
		/*
		// Tagging
		$this->load->model('MTag');
		$userId = $this->session->userdata('uid');
		$oldTags = $this->input->post('oldTags');
		$newTags = $this->input->post('tag');
		$this->MTag->updateMyTagging($userId, $bookId, $oldTags, $newTags);
		*/

		// Load OAuth Lib&Helper
		$this->load->model('MUserOAuth');
		$this->load->helper('oauth');
		// Load Book
		$book = $this->MBook->getById($bookId);
		$book->url = site_url("book/subject/$book->id/");
		// Update tsina
		if($this->input->post('tsina')=='true'){
			$userOAuth = $this->MUserOAuth->get($userId, 'tsina');
			$status = "我在 @摆摆书架 上架了一本《{$book->name}》，欢迎借阅 {$book->url}";
			update_tsina($status, $userOAuth);
		}
		// Update douban
		if($this->input->post('douban')=='true'){
			$userOAuth = $this->MUserOAuth->get($userId, 'douban');
			$status = "我在 #摆摆书架# 上架了一本《{$book->name}》，欢迎借阅 {$book->url}";
			update_douban($status, $userOAuth);
		}
		// Update tqq
		if($this->input->post('tqq')=='true'){
			$userOAuth = $this->MUserOAuth->get($userId, 'tqq');
			$status = "我在 @baibaibook 上架了一本《{$book->name}》，欢迎借阅 {$book->url}";
			update_tqq($status, $userOAuth);
		}
		
		// Redirect
		$this->session->set_flashdata('redirectUrl', site_url("borrow/donate_success/$bookId/") );
		redirect("borrow/redirect");
	}
	
	function donate_delete_do($bookId){
		$this->Validate->isLogin();
    
		$this->load->model('MStock');
		$this->load->model('MBook');
    
		$userId = $this->session->userdata('uid');
		if ( ($this->MBook->isOwner($userId, $bookId)) && ($this->MBook->isReader($userId, $bookId)) ){
			# Delete
			$this->MStock->delete($bookId, $this->session->userdata('uid'));
      
			# Redirect
			$this->session->set_flashdata('info', '恭喜您，已成功取消赠书。');
			redirect("book/subject/$bookId");
		}
	}
	
	function choosereceiver($requestId){
		$this->output->enable_profiler(FALSE);
		
		# 如果未登录，STOP
		$this->Validate->isLogin('fancybox');
		
		$this->load->model('MBorrowRequest');
		$this->load->model('MBorrowRecord');
		$this->load->model('MUser');
		$this->load->model('MAddress');
		$this->load->model('MBook');
		$this->load->helper('gravatar');
    
		// 如果 request 不存在
		if (!($request = $this->MBorrowRequest->getById($requestId))){
			$this->session->set_flashdata('title', 'OOPS...很抱歉');
			$this->session->set_flashdata('message', "TA 可能已经借到这本书（或摆摆券不足），请将您手上的书借给其它人吧 :)");
			redirect("alert/failure");
		}
		
		# 如果用户没有此书，STOP
		$senterUid = $this->session->userdata('uid');
		if ( !( $stock = $this->MBook->isReader($senterUid, $request->bookId) ) ){
			$this->session->set_flashdata('title', 'OOPS...很抱歉');
			$this->session->set_flashdata('message', "对不起，您手上没有这本书。");
			redirect("alert/failure");
		}
		
		// 如果书已经在transfor，STOP
		if ( $stock->statue == 'transfor' ){
			$this->session->set_flashdata('title', 'OOPS...很抱歉');
			$this->session->set_flashdata('message', "对不起，您已经将这本书送给了其它人。");
			redirect("alert/failure");
		}
		
		$data['request'] = $request;
		$data['receiver'] = $this->MUser->getByUid($data['request']->userId);
		$data['receiver']->avatar = getGravatar($data['receiver']->email, 45);
		$data['book'] = $this->MBook->getById($data['request']->bookId);
		
		$this->load->view('book/send2_choosereceiver', $data);
	}
	
	
	function offeraddress($recordId){
		$this->output->enable_profiler(FALSE);
		
		# 如果未登录，STOP
		$this->Validate->isLogin('fancybox');
		
		$this->load->model('MBorrowRecord');
		$this->load->model('MBook');
		$this->load->model('MUser');
		$this->load->model('MAddress');
		$this->load->helper('gravatar');
		
		# Load Data
		$data['record'] = $this->MBorrowRecord->getById($recordId);
		$data['book'] = $this->MBook->getById($data['record']->bookId);
		$data['senter'] = $this->MUser->getByUid($data['record']->senterUid);
		$data['senter']->avatar = getGravatar($data['senter']->email, 32);
		$data['senterAddress'] = $this->MAddress->getDefaultByUid($data['record']->senterUid);
		$data['receiver'] = $this->MUser->getByUid($data['record']->receiverUid);
		$data['receiverAddress'] = $this->MAddress->getDefaultByUid($this->session->userdata('uid'));
		
		$this->load->view('book/send3_offeraddress', $data);
	}
	
	function getaddress($recordId){
		$this->output->enable_profiler(FALSE);
		
		# 如果未登录，STOP
		$this->Validate->isLogin('fancybox');
		
		$this->load->model('MBorrowRecord');
		$this->load->model('MBook');
		$this->load->model('MUser');
		$this->load->model('MAddress');
		$this->load->helper('gravatar');
		
		# Load Data
		$data['record'] = $this->MBorrowRecord->getById($recordId);
		$data['book'] = $this->MBook->getById($data['record']->bookId);
		$data['receiver'] = $this->MUser->getByUid($data['record']->receiverUid);
		$data['receiver']->avatar = getGravatar($data['receiver']->email, 16);
		$data['receiverAddress'] = $this->MAddress->getDefaultByUid( $data['record']->receiverUid );
		
		$this->load->view('book/send4_getaddress', $data);
	}
	
	function fillexpress($recordId){
		$this->output->enable_profiler(FALSE);
		
		# 如果未登录，STOP
		$this->Validate->isLogin('fancybox');
		
		$this->load->model('MBorrowRecord');
    $this->load->model('MBook');
    $this->load->model('MUser');
    $this->load->model('MAddress');
		$this->load->model('MUserOAuth');
    $this->load->helper('gravatar');
    
    # Load Data
    $data['record'] = $this->MBorrowRecord->getById($recordId);
    $data['book'] = $this->MBook->getById($data['record']->bookId);
    $data['receiver'] = $this->MUser->getByUid($data['record']->receiverUid);
    $data['receiver']->avatar = getGravatar($data['receiver']->email, 16);
    $data['receiverAddress'] = $this->MAddress->getDefaultByUid( $data['record']->receiverUid );
		
		# Load Data
		$data['record'] = $this->MBorrowRecord->getById($recordId);
		$data['book'] = $this->MBook->getById($data['record']->bookId);
		
		$data['myLinkedProviders'] = $this->MUserOAuth->getUserLinkedProviders($this->session->userdata('uid'));
		
		$this->load->view('book/send5_fillexpress', $data);
	}
	
	function receivebook($recordId){
		$this->output->enable_profiler(FALSE);
		
		# 如果未登录，STOP
		$this->Validate->isLogin('fancybox');
		
		$this->load->model('MBorrowRecord');
		$this->load->model('MBook');
		$this->load->model('MUser');
    $this->load->model('MAddress');
		$this->load->model('MUserOAuth');
    $this->load->helper('gravatar');
		
		# Load Data
		$data['record'] = $this->MBorrowRecord->getById($recordId);
		$data['book'] = $this->MBook->getById($data['record']->bookId);
		$data['senter'] = $this->MUser->getByUid($data['record']->senterUid);
		$data['myLinkedProviders'] = $this->MUserOAuth->getUserLinkedProviders($this->session->userdata('uid'));
		
		$this->load->view('book/send6_receivebook', $data);
	}
	
	function getreward($recordId){
		$this->output->enable_profiler(FALSE);
		
		# 如果未登录，STOP
		$this->Validate->isLogin('fancybox');
		
		$this->load->model('MBorrowRecord');
		$this->load->model('MBook');
		$this->load->model('MUser');
		$this->load->helper('gravatar');
		
		# Load Data
		$data['record'] = $this->MBorrowRecord->getById($recordId);
		$data['book'] = $this->MBook->getById($data['record']->bookId);
		$data['receiver'] = $this->MUser->getByUid($data['record']->receiverUid);
		$data['receiver']->avatar = getGravatar($data['receiver']->email, 32);
		
		$this->load->view('book/send7_getreward', $data);
	}
	
	// DOs
	function getisbn_do($type='donate'){
		$this->load->model('MBook');
		$this->load->model('MTag');
		$isbn = str_replace(' ', '', $this->input->post('isbn'));
		
		# 如果数据库中无此书，从豆瓣读取
		if (!$this->MBook->getByIsbn($isbn)){
			$this->load->helper('douban');
			$book = getBookDetail($isbn);
			if($book==false){
				$this->session->set_flashdata('title', 'OOPS...很抱歉');
				$this->session->set_flashdata('message', "对不起，没有查询到这本书的数据。建议您核对ISBN号是否输入有误。");
				redirect("alert/failure");
			}
			if (!$this->MBook->getByIsbn($book->isbn)) {
			   $bookId = $this->MBook->insert($book);
         
				// add Tags
				foreach($book->tags as $tag){
					$this->MTag->insert(0, $bookId, $tag);
				}
         
				// save Image
				if (!strpos($book->pic, 'default')) {
					$picFid = mdate('%Y%m', time());
					$picId = $bookId;
					$this->MBook->updateCover($bookId, $picFid, $picId);
				}
			}
			$isbn = $book->isbn;
		}
		
		if ($type == 'donate') {
			redirect("book/donate/{$isbn}/");
		} elseif ($type == 'borrow') {
			$book = $this->MBook->getByIsbn($isbn);
			//redirect("book/iwantborrow/{$book->id}/");
			$this->session->set_flashdata('redirectUrl', site_url("book/subject/{$book->id}/")."#borrow");
			redirect("borrow/redirect");
		}
	}

	function choosereceiver_do($requestId){
		# 如果未登录，STOP
		$this->Validate->isLogin('fancybox');
		
		$this->load->model('MBorrowRequest');
		$this->load->model('MBorrowRecord');
		$this->load->model('MBook');
		$this->load->model('MStock');
		$this->load->model('MAddress');
		$this->load->model('MUser');
		$this->load->model('MNotification');
		$this->load->helper('gravatar');
		
		# 如果用户没有此书，STOP
		$senterUid = $this->session->userdata('uid');
		$request = $this->MBorrowRequest->getById($requestId);
		if ( !( $stock = $this->MBook->isReader($senterUid, $request->bookId) ) ){
			$this->session->set_flashdata('title', 'OOPS...很抱歉');
			$this->session->set_flashdata('message', "对不起，您手上没有这本书。");
			redirect("alert/failure");
		}
		
		// 如果书已经在transfor，STOP
		if ( $stock->statue == 'transfor' ){
			$this->session->set_flashdata('title', 'OOPS...很抱歉');
			$this->session->set_flashdata('message', "对不起，您已经将这本书送给了其它人。");
			redirect("alert/failure");
		}
		
		# 减1借书限额
		$user = $this->MUser->getByUid($request->userId);
		if ($user->borrowQuote >= 1){
			$this->MUser->minusBorrowQuote($request->userId);
		} else{
			# 失败提示信息
			$this->session->set_flashdata('title', 'OOPS...很抱歉');
			$this->session->set_flashdata('message', '对不起，对方的摆摆券不足，请借给其它用户。');
			redirect("alert/failure");
		}
		
		# New BorrowRecord
		$senter = $this->MUser->getByUid($senterUid);
		$senterAddress = $this->MAddress->getDefaultByUid($senterUid);
		$recordId = $this->MBorrowRecord->updateSenter($request, $stock->id, $senterAddress, $senter);
		$this->MStock->changeStatue($stock->id, 'transfor');
		$this->MBorrowRequest->delete($request->id);
		
		// Send Email To Receiver
		$this->load->model('Mail');
		$record = $this->MBorrowRecord->getById($recordId);
		$this->Mail->someoneWantSendYouBook($record);

		# 成功提示信息
		$this->session->set_flashdata('title', 'WOW!!恭喜您!');
		$this->session->set_flashdata('message', '您已经成功选择借书对象，请等待对方提供收书地址。');
		//$this->session->set_flashdata('redirectUrl', site_url('home/#available') );
		redirect("alert/success");
	}
	
	function offeraddress_do($recordId){
		# 如果未登录，STOP
		$this->Validate->isLogin('fancybox');
		
		$this->load->model('MBorrowRecord');
		$this->load->model('MAddress');
		$this->load->model('MUser');
		
		# 如果当前用户不是Receiver，STOP
		$record = $this->MBorrowRecord->getById($recordId);
		if ( $record->receiverUid != $this->session->userdata('uid')){
			$this->session->set_flashdata('title', 'OOPS...很抱歉');
			$this->session->set_flashdata('message', "对不起，您没有权限执行此项操作。");
			redirect("alert/failure");
		}
		
		# Update
		$receiver = $this->MUser->getByUid($record->receiverUid);
		$receiverAddress = $this->MAddress->getDefaultByUid($record->receiverUid);
		$this->MBorrowRecord->updateReceiverAddress($recordId, $receiverAddress, $receiver);
		
		// Mail To Senter
		$this->load->model('Mail');
		$this->Mail->getReceiverAddress($record);
		
		# 成功提示信息
		$this->session->set_flashdata('title', 'WOW!!恭喜您!');
		$this->session->set_flashdata('message', '您已经提供收书地址，请等待对方将书寄出。');
		$this->session->set_flashdata('redirectUrl', site_url('account/home/') );
		redirect("alert/success");
	}
	
	function offeraddress_back_do($recordId){
		# 如果未登录，STOP
		$this->Validate->isLogin('fancybox');
		
		$this->load->model('MBorrowRecord');
		$this->load->model('MStock');
		$this->load->model('MUser');
		
		# 如果当前用户不是Receiver，STOP
		$record = $this->MBorrowRecord->getById($recordId);
		if ( $record->receiverUid != $this->session->userdata('uid')){
			$this->session->set_flashdata('title', 'OOPS...很抱歉');
			$this->session->set_flashdata('message', "对不起，您没有权限执行此项操作。");
			redirect("alert/failure");
		}
		
		// Mail To Senter
		$this->load->model('Mail');
		$this->Mail->notGetReceiverAddress($record);
		
		# Update Stock
		$this->MStock->changeStatue($record->stockId, 'available');
		
		# delete Record
    $this->MBorrowRecord->deleteRecord($record->id);
		
		# Add Borrow Quote
		$this->MUser->addBorrowQuote($record->receiverUid);
		
		# 成功提示信息
		$this->session->set_flashdata('title', 'WOW!!恭喜您!');
		$this->session->set_flashdata('message', '您放弃了此次借书机会，请等待其他人借书给您。');
		$this->session->set_flashdata('redirectUrl', site_url('account/home/') );
		redirect("alert/success");
	}

	function fillexpress_do($recordId){
		# 如果未登录，STOP
		$this->Validate->isLogin('fancybox');
		
		$this->load->model('MBorrowRecord');
		
		# 如果当前用户不是Senter，STOP
		$record = $this->MBorrowRecord->getById($recordId);
		if ( $record->senterUid != $this->session->userdata('uid')){
			$this->session->set_flashdata('title', 'OOPS...很抱歉');
			$this->session->set_flashdata('message', "对不起，您没有权限执行此项操作。");
			redirect("alert/failure");
		}
		
		# Update
		$expressType = ($this->input->post('expressType')!='其它') ? $this->input->post('expressType') : $this->input->post('expressType2');
		$expressId     = $this->input->post('expressId');
		$sentTime       = mdate('%Y-%m-%d', time());
		$this->MBorrowRecord->updateSentInfo($recordId,  $expressType, $expressId, $sentTime);
    
    // Update Message
    $finishMessage = nl2br($this->input->post('finishMessage'));
    $this->MBorrowRecord->updateFinishMessage($recordId, $finishMessage);
		
		// Mail To Receiver
		$this->load->model('Mail');
		$this->Mail->bookSent($record);
		
		// Load OAuth Lib&Helper
		$this->load->model('MUserOAuth');
		$this->load->helper('oauth');
		// Load Book
		$book = $this->MBook->getById($record->bookId);
		$book->url = site_url("book/subject/$book->id/");
		// Update tsina
		if($this->input->post('tsina')=='true'){
			$userOAuth = $this->MUserOAuth->get($this->session->userdata('uid'), 'tsina');
			$status = "我借出了一本《{$book->name}》 {$book->url} @摆摆书架";
			update_tsina($status, $userOAuth);
		}
		// Update douban
		if($this->input->post('douban')=='true'){
			$userOAuth = $this->MUserOAuth->get($this->session->userdata('uid'), 'douban');
			$status = "我借出了一本《{$book->name}》 {$book->url} #摆摆书架#";
			update_douban($status, $userOAuth);
		}
		// Update tqq
		if($this->input->post('tqq')=='true'){
			$userOAuth = $this->MUserOAuth->get($this->session->userdata('uid'), 'tqq');
			$status = "我借出了一本《{$book->name}》 {$book->url} @baibaibook";
			update_tqq($status, $userOAuth);
		}
		
		# 成功提示信息
		$this->session->set_flashdata('title', 'WOW!!恭喜您!');
		$this->session->set_flashdata('message', '您已经提交发书信息，请等待对方收到书后进行确认。');
		$this->session->set_flashdata('redirectUrl', site_url('account/home/') );
		redirect("alert/success");
	}
	
	function receivebook_do($recordId){
		# 如果未登录，STOP
		$this->Validate->isLogin('fancybox');
		
		$this->load->model('MBorrowRecord');
		$this->load->model('MStock');
		$this->load->model('MBook');
		$this->load->model('MUser');
		
		# 如果当前用户不是Receiver，STOP
		$record = $this->MBorrowRecord->getById($recordId);
		if ( ($record->receiverUid != $this->session->userdata('uid')) || ($record->statue>=4) ){
			$this->session->set_flashdata('title', 'OOPS...很抱歉');
			$this->session->set_flashdata('message', "对不起，您没有权限执行此项操作。");
			redirect("alert/failure");
		}
		
		# Update
		$receiveTime = mdate('%Y-%m-%d', time());
		$this->MBorrowRecord->updateReceiveInfo($recordId, $receiveTime);
		$this->MUser->addBorrowQuote($record->senterUid);
		$this->MStock->changeReader($record->stockId, $record->receiverUid);
		
		// Mail To Senter
		$this->load->model('Mail');
		$this->Mail->bookReceive($record);
		
		// Load OAuth Lib&Helper
		$this->load->model('MUserOAuth');
		$this->load->helper('oauth');
		// Load Book
		$book = $this->MBook->getById($record->bookId);
		$book->url = site_url("book/subject/$book->id/");
		// Update tsina
		if($this->input->post('tsina')=='true'){
			$userOAuth = $this->MUserOAuth->get($this->session->userdata('uid'), 'tsina');
			$status = "我借到了一本《{$book->name}》 {$book->url} @摆摆书架";
			update_tsina($status, $userOAuth);
		}
		// Update douban
		if($this->input->post('douban')=='true'){
			$userOAuth = $this->MUserOAuth->get($this->session->userdata('uid'), 'douban');
			$status = "我借到了一本《{$book->name}》{$book->url} #摆摆书架#";
			update_douban($status, $userOAuth);
		}
		// Update tqq
		if($this->input->post('tqq')=='true'){
			$userOAuth = $this->MUserOAuth->get($this->session->userdata('uid'), 'tqq');
			$status = "我借到了一本《{$book->name}》{$book->url} @baibaibook";
			update_tqq($status, $userOAuth);
		}
		
		# 成功提示信息
		$this->session->set_flashdata('title', 'WOW!!');
		$this->session->set_flashdata('message', '感谢您确认收书，对方将获得一张摆摆券奖励。');
		$this->session->set_flashdata('redirectUrl', site_url('account/home/') );
		redirect("alert/success");
	}
	
	function getreward_do($recordId){
		# 如果未登录，STOP
		$this->Validate->isLogin('fancybox');
		
		$this->load->model('MBorrowRecord');
		$this->load->model('MStock');
		
		# 如果当前用户不是Senter，STOP
		$record = $this->MBorrowRecord->getById($recordId);
		if ( $record->senterUid != $this->session->userdata('uid')){
			$this->session->set_flashdata('title', 'OOPS...很抱歉');
			$this->session->set_flashdata('message', "对不起，您没有权限执行此项操作。");
			redirect("alert/failure");
		}
		
		# Update
		$this->MBorrowRecord->finish($recordId);
	}
  
  function returnbook($stockId){
		$this->output->enable_profiler(FALSE);
	
		$this->load->model('MUserOAuth');
	
		$data['stockId'] = $stockId;
		$data['myLinkedProviders'] = $this->MUserOAuth->getUserLinkedProviders($this->session->userdata('uid'));
		$this->load->view('book/returnbook', $data);
	}
	
	function returnbook_do($stockId){
		# 如果未登录，STOP
		$this->Validate->isLogin();

		# Update
		$this->load->model('MStock');
		$this->load->model('MBorrowRecord');
		$record = $this->MBorrowRecord->getByReceiverUidAndStockId($this->session->userdata('uid'), $stockId);
		$this->MBorrowRecord->returnbook($record->id);
		$this->MStock->finishReading($stockId, $this->session->userdata('uid'));
		
		// Load OAuth Lib&Helper
		$this->load->model('MUserOAuth');
		$this->load->helper('oauth');
		// Load Book
		$book = $this->MBook->getById($record->bookId);
		$book->url = site_url("book/subject/$book->id/");
		// Update tsina
		if($this->input->post('tsina')=='true'){
			$userOAuth = $this->MUserOAuth->get($this->session->userdata('uid'), 'tsina');
			$status = "我在 @摆摆书架 上架了一本《{$book->name}》，欢迎借阅 {$book->url}";
			update_tsina($status, $userOAuth);
		}
		// Update douban
		if($this->input->post('douban')=='true'){
			$userOAuth = $this->MUserOAuth->get($this->session->userdata('uid'), 'douban');
			$status = "我在 #摆摆书架# 上架了一本《{$book->name}》，欢迎借阅 {$book->url}";
			update_douban($status, $userOAuth);
		}
		// Update tqq
		if($this->input->post('tqq')=='true'){
			$userOAuth = $this->MUserOAuth->get($this->session->userdata('uid'), 'tqq');
			$status = "我在 @baibaibook 上架了一本《{$book->name}》，欢迎借阅 {$book->url}";
			update_tqq($status, $userOAuth);
		}
		
		// Redirect
		$this->session->set_flashdata('redirectUrl', site_url("borrow/donate_success/{$book->id}/") );
		redirect("borrow/redirect");
	}
  
  function tagging_do($bookId){
    # 如果未登录，STOP
    $this->Validate->isLogin();
    
    $this->load->model('MTag');
    
    $userId = $this->session->userdata('uid');
    $oldTags = $this->input->post('oldTags');
    $newTags = $this->input->post('newTags');
    
    $this->MTag->updateMyTagging($userId, $bookId, $oldTags, $newTags);
    //$this->session->set_flashdata('title', 'WOW!');
    //$this->session->set_flashdata('message', "恭喜您，已经成功更新标签。");
    //$this->session->set_flashdata('redirectUrl', site_url("book/subject/$bookId") );
    redirect("book/subject/$bookId/");
  }
  
  function tags(){
    $this->load->model('MTag');
    
    $headerData['title'] = "所有标签";
    $headerData['current'] = 'lib';
    $data['tags'] = $this->MTag->getTagsForTagsCloud('200');
    
    $this->load->view('header', $headerData);
    $this->load->view('book/tags', $data);
    $this->Common->footer();
  }
  
  // 物流追踪
  function trackexpress($expressType, $expressId){
  	$data['expressType'] = $expressType;
  	$data['expressId'] = $expressId;
  
  	switch($expressType){
  		case '申通':
  			redirect('http://115.238.100.211:8081/result.aspx?wen='.$expressId);
  			break;
      case '圆通':
        $data['postURL'] = 'http://www.ems183.cn/yto.asp';
        $data['name'] = 'No';
        $this->load->view('book/trackexpress', $data);
        break;
  		case '韵达':
  			$data['postURL'] = 'http://222.73.105.196/go.php';
  			$data['name'] = 'wen';
			  $this->load->view('book/trackexpress', $data);
  			break;
  		case '中通':
  			$data['postURL'] = 'http://www.zto.cn/bill.aspx';
  			$data['name'] = 'ID';
			  $this->load->view('book/trackexpress', $data);
  			break;
  		case '顺丰':
  			redirect('http://www.sf-express.com/tabid/68/Default.aspx');
  			break;
			case '邮政':
				$data['postURL'] = 'http://my.kiees.cn/post.php';
        $data['name'] = 'wen';
        $this->load->view('book/trackexpress', $data);
        break;
  	}
  }

	function messages($type, $bookId){
		$this->output->enable_profiler(FALSE);
		
		$this->load->model('MBook');
		$this->load->model('MAddress');
		if ( $userId = $this->session->userdata('uid') ) {
			$this->load->model('MBorrowRequest');
			$this->load->model('MUser');
			$this->load->helper('gravatar');
			
			switch ($type){
				case 'all':
					if ( $this->MBook->isReader($userId, $bookId) ){
						$borrowRequests = $this->MBorrowRequest->getByBook($bookId);
					}
					break;
				case 'mine':
					$borrowRequests = $this->MBorrowRequest->getUserMessageByBook($bookId, $userId);
					break;
			}
			
			$data['borrowRequests'] = $borrowRequests;
			$data['type'] = $type;
			
			$this->load->view('book/messages', $data);
		}
	}
}

/* End of file book.php */
/* Location: ./system/application/controllers/book.php */