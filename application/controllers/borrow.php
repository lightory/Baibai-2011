<?php

class Borrow extends CI_Controller {
    function __construct(){
		parent::__construct();
		$this->Validate->isLogin();
		if($this->session->userdata('uid')==1){
			$this->output->enable_profiler(TRUE);
		}
	}
	
	function index(){
		show_404();
	}
	
	function redirect(){
		$data['redirectUrl'] = $this->session->flashdata('redirectUrl');
		$this->load->view('borrow/components/redirect', $data);
	}
	
	function request($bookId = 0) {
		$this->load->model('MUser');
		$this->load->model('MAddress');
		$this->load->model('MBook');
		$this->load->model('MStock');
		$this->load->model('MBorrowRequest');
		$this->load->model('MUserOAuth');
		$this->load->helper('gravatar');
		
		$me = $this->MUser->getByUid($this->session->userdata('uid'));
		$me->address = $this->MAddress->getDefaultByUid($me->uid);
		if ($bookId != 0) {
			$book = $this->MBook->getById($bookId);
		} else {
			$book = null;
		}
		
		if ($bookId != 0) {
			$borrowRequests = $this->MBorrowRequest->getUserMessageByBook($bookId, $me->uid);
			if (!empty($borrowRequests)) $myRequest = $borrowRequests[0];
		}
		
		$headerData['title'] = "申请借阅";
        $headerData['current'] = 'lib';
		$data['me'] = $me;
		$data['book'] = $book;
		$data['readers'] = $this->MStock->getByBookId($bookId);
		$data['myRequest'] = isset($myRequest) ? $myRequest : null;
		$data['myLinkedProviders'] = $this->MUserOAuth->getUserLinkedProviders($me->uid);

        $this->load->view('header', $headerData);
        $this->load->view('borrow/request', $data);
        $this->Common->footer();
	} 
	
	function request_do($bookId) {	
		$this->load->model('Mail');
		$this->load->model('MUser');
		$this->load->model('MAddress');
		$this->load->model('MBook');
		$this->load->model('MStock');
		$this->load->model('MBorrowRequest');
		$this->load->model('MBorrowRecord');
		$this->load->model('MNotification');
		$this->load->helper('gravatar');
		
		// 如果手上有此书，STOP
		if ( $this->MBook->isReader($this->session->userdata('uid'), $bookId) ){
			$this->session->set_flashdata('error', '对不起，您已经拥有此书，无法执行此项操作。');
			redirect("borrow/request/{$bookId}/");
		}
		
		// POST PARAMs
		$receiverUid = $this->session->userdata('uid');
		$message = $this->input->post('message');
		if ($this->input->post('scope')) {
			$allowUserIds = implode(',', $this->input->post('scope2'));
		} else {
			$allowUserIds = 'ALL';
		}
		
		// Edit
		$borrowRequests = $this->MBorrowRequest->getUserMessageByBook($bookId, $receiverUid);
		if (!empty($borrowRequests)) {
			$myRequest = $borrowRequests[0];
			$this->MBorrowRequest->updateMessage($myRequest->id, $receiverUid, $message, $allowUserIds);
			redirect("book/subject/{$bookId}/");
		}
		
		# Insert
		$requestId = $this->MBorrowRequest->insert($bookId, $receiverUid, $message, $allowUserIds);
		
		// 如果限额=0，STOP
		$user = $this->MUser->getByUid($this->session->userdata('uid'));
		if ($user->borrowQuote > 0){
			# Send Mail&Notification To Available Stocks' Reader
			$availableStocks = $this->MStock->getAvailableStocks($bookId);
			$receiver = $this->MUser->getByUid($receiverUid);
			$receiver->address = $this->MAddress->getDefaultByUid($receiverUid);
			foreach ($availableStocks as $stock){
				if ($this->input->post('scope') && !in_array($stock->readerId, $this->input->post('scope2'))) {
					continue;
				}
				
				$reader = $this->MUser->getByUid($stock->readerId);
				$book = $this->MBook->getById($stock->bookId);
				$this->Mail->someoneWantYourBook($reader->email, $receiver, $reader, $book);
      
				$noticeContent = "<a href=\"". $this->MUser->getUrl($receiver->uid) ."\">".$receiver->nickname."</a>";
				$noticeContent .= $receiver->isMobileValidate ? "<img src=\"".site_url('include/style/img/')."mobile.png\" style=\"position:relative; top:2px; margin:0 2px;\" />" : "<img src=\"".site_url('include/style/img/')."mobilenone.png\" style=\"position:relative; top:2px; margin:0 2px;\" />";
				$noticeContent .= " 向您借 <a href=\"". site_url("book/subject/$book->id/") ."\">《".$book->name."》</a><br/>{$message}";
				$notice->receiverUid = $reader->uid;
				$notice->avatar = getGravatar($receiver->email, 20);
				$notice->content = $noticeContent;
				$notice->actionName = "借给TA";
				$notice->actionUrl = site_url("book/choosereceiver/$requestId/");
				$notice->time = time();
				$this->MNotification->insert($notice);
			}
		}
		
		// Load OAuth Lib&Helper
		$this->load->model('MUserOAuth');
		$this->load->helper('oauth');
		// Load Book
		$book = $this->MBook->getById($bookId);
		$book->url = site_url("book/subject/$book->id/");
		// Update tsina
		if($this->input->post('tsina')=='true'){
			$userOAuth = $this->MUserOAuth->get($this->session->userdata('uid'), 'tsina');
			$status = "我想借《{$book->name}》：{$message} {$book->url} @摆摆书架";
			update_tsina($status, $userOAuth);
		}
		// Update douban
		if($this->input->post('douban')=='true'){
			$userOAuth = $this->MUserOAuth->get($this->session->userdata('uid'), 'douban');
			$status = "我想借《{$book->name}》：{$message} {$book->url} @摆摆书架";
			update_douban($status, $userOAuth);
		}
		// Update tqq
		if($this->input->post('tqq')=='true'){
			$userOAuth = $this->MUserOAuth->get($this->session->userdata('uid'), 'tqq');
			$status = "我想借《{$book->name}》：{$message} {$book->url} @baibaibook";
			update_tqq($status, $userOAuth);
		}
		
		// Redirect
		redirect("borrow/request_success/{$bookId}/");
	}
	
	function request_cancel_do($bookId){
		// delete
		$this->load->model('MBorrowRequest');
		$request = $this->MBorrowRequest->getByUserIdAndBookId($this->session->userdata('uid'), $bookId);
		$this->MBorrowRequest->delete($request->id);
		
		// Redirect
		$this->session->set_flashdata('info', '恭喜您，已成功取消求书。');
		redirect("book/subject/{$bookId}/");
	}
	
	function request_success($bookId){
		$this->load->model('MBook');
		$this->load->model('MUser');
		
		$user = $this->MUser->getByUid($this->session->userdata('uid'));
		$book = $this->MBook->getById($bookId);
		
		if($user->borrowQuote>0){
			$slides1['title'] = '您可能还想借';
			$slides1['books'] = $this->MBook->getRelatedBooks($bookId, 20);
			$slides1['id'] = 'slides1';
			
			$data['slides1'] = $slides1;
		} else{
			$slides1['title'] = "捐赠《{$book->name}》的人想借";
			$slides1['tips'] = "捐赠《{$book->name}》的人很想借这些书，如果您恰巧有一本闲置，不如捐上来，能很快获得摆摆券";
			$slides1['books'] = $this->MBook->getSwitchBooksByBorrow($bookId, 20);
			$slides1['id'] = 'slides1';
		
			$slides2['title'] = '大家想借';
			$slides2['tips'] = '大家很想借这些书，如果您恰巧有一本闲置，不如捐上来，能很快获得摆摆券';
			$slides2['books'] = $this->MBook->getRandomWantedBooks(20);
			$slides2['id'] = 'slides2';
			
			$data['slides1'] = $slides1;
			$data['slides2'] = $slides2;
		}
		
		$headerData['title'] = "发送借阅请求成功";
        $headerData['current'] = 'lib';
        $data['user'] = $user;
		$data['book'] = $book;
		$data['bookRealInventory'] = $this->MBook->getRealInventory($book->id);
		$data['bookInventory'] = $this->MBook->getInventory($book->id);

        $this->load->view('header', $headerData);
        $this->load->view('borrow/request_success', $data);
        $this->Common->footer();
	}
	
	function donate_success($bookId){
        $this->load->model('MBook');

		$slides1['title'] = '您可能想借';
		$slides1['books'] = $this->MBook->getSwitchBooksByDonate($bookId, 20);
		$slides1['id'] = 'slides1';
		
		$slides2['title'] = '大家想借';
		$slides2['tips'] = '大家都很想借这些书，如果您恰巧有一本闲置，不如捐上来，能很快获得摆摆券';
		$slides2['books'] = $this->MBook->getRandomWantedBooks(5);
		$slides2['id'] = 'slides2';
		$slides2['viewMoreLink'] = site_url("book/wanted/");
	   
        $headerData['title'] = "捐赠成功";
        $headerData['current'] = 'lib';
        $data['bookNeeds'] = $this->MBook->getNeeds($bookId);
		$data['book'] = $this->MBook->getById($bookId);
		$data['slides1'] = $slides1;
		$data['slides2'] = $slides2;

        $this->load->view('header', $headerData);
        $this->load->view('borrow/donate_success', $data);
        $this->Common->footer();
	}
	
	function getisbn_do($type = 'donate'){
		$this->load->model('MBook');
		$this->load->model('MTag');
		$isbn = str_replace(' ', '', $this->input->post('isbn'));

		// 如果数据库中无此书，从豆瓣读取
		if (!$this->MBook->getByIsbn($isbn)){
			$this->load->helper('douban');
			$book = getBookDetail($isbn);
			if($book == false){
				$this->session->set_flashdata('error', "对不起，没有查询到这本书的数据。建议您核对ISBN号是否输入有误。");
				if ($type == 'donate') {
					// Finish Later
					// redirect("book/donate/{$isbn}/");
				} elseif ($type == 'borrow') {
					$book = $this->MBook->getByIsbn($isbn);
					redirect("borrow/request/");
				}
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
		$book = $this->MBook->getByIsbn($isbn);

		if ($type == 'donate') {
			// Finish Later
			// redirect("book/donate/{$isbn}/");
		} elseif ($type == 'borrow') {
			redirect("borrow/request/{$book->id}/");
		}
	}
}