<?php

class Admin extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->Validate->isAdmin();
	}
	
	function phpinfo(){
		phpinfo();
	}
	
	function index(){
		$this->load->model('MUser');
		$this->load->model('MTempUser');
		$this->load->model('MBook');
		$this->load->model('MStock');
		$this->load->model('MBorrowRequest');
		$this->load->model('MBorrowRecord');
		
		// 用户数据
		$data['registerUserCount'] = $this->MUser->getCount();
		$data['unregisterUserCount'] = $this->MTempUser->getCount();
		$data['inviteQuoteCount'] = $this->MUser->getToalInviteQuote();
		
		// 书籍数据
		$data['bookCount'] = $this->MBook->getNumber();
		$data['totalStockCount'] = $this->MStock->getNumber();
		$data['availableStockCount'] = $this->MStock->getNumber('available');
		$data['readingStockCount'] = $this->MStock->getNumber('reading');
		$data['transforStockCount'] = $this->MStock->getNumber('transfor');
		
		// 借书行为数据
		$data['statue6RecordCount'] = $this->MBorrowRecord->getNumber(6);
		$data['statue5RecordCount'] = $this->MBorrowRecord->getNumber(5);
		$data['statue4RecordCount'] = $this->MBorrowRecord->getNumber(4);
		$data['statue3RecordCount'] = $this->MBorrowRecord->getNumber(3);
		$data['statue2RecordCount'] = $this->MBorrowRecord->getNumber(2);
		$data['statue1RecordCount'] = $this->MBorrowRecord->getNumber(1);
		$data['statue0RecordCount'] = $this->MBorrowRequest->getNumber();
		
		//摆摆卷
		$data['baibaijuanSaveCount'] = $this->MUser->getBaiBaiSaveCount();
		$data['baibaijuanTranCount'] = $this->MBorrowRecord->getBaiBaiTranCount();
		$query = $this->db->get('bf_user');
	
		$this->load->view('admin/menu');
		$this->load->view('admin/index', $data);
	}

	function books($offset=0){
		$this->load->model('MBook');
		$this->load->model('MTag');
		$this->load->library('pagination');
    
		$config['full_tag_open'] = '<p class="pages">';
		$config['full_tag_close'] = '</p>';
		$config['uri_segment'] = 2;
		$config['per_page'] = '50'; 
		$config['base_url'] = site_url("admin/books/");
		$config['total_rows'] = $this->MBook->getNumber();
		$this->pagination->initialize($config);
    
		$data['books'] = $this->MBook->get(50, $offset);
    
		$this->load->view('admin/menu');
		$this->load->view('admin/book/list', $data);
	}
  
	function updatetag_do($bookId){
		$this->load->model('MTag');
    
		$userId = '0';
		$oldTags = $this->input->post('oldTags');
		$newTags = $this->input->post('newTags');
    
		$this->MTag->updateMyTagging($userId, $bookId, $oldTags, $newTags);
	}
	
	function user($username){
		$this->load->model('MUser'); 
		$this->load->model('MStock');
		$this->load->model('MBorrowRecord');
		$this->load->model('MEventLog');
		$this->load->model('MUserLogin');
		$this->config->load('baibai');

		$user = $this->MUser->getByUsername($username);
		if(!$user){
			$user = $this->MUser->getByUid($username); 
			if (!$user){ show_404('misc/404'); }
			redirect("admin/user/$user->username");
		}
		
		$lastLogin = $this->MUserLogin->getUserLastLogin($user->uid);	
		$gifts = $this->MEventLog->getByUserId($user->uid, $this->config->item('EVENT_CATEGORY_GIFTBAIBAIQUAN'));
		
		$data['user'] = $user;		
		$data['lastLogin'] = $lastLogin->time;
		$data['url'] = $this->MUser->getUrl($user->uid);
		$data['gifts'] = $gifts;
		
		$this->load->view('admin/menu');
		$this->load->view('admin/user/generalinfo', $data);
	}

	function users(){
		$this->load->model('MUser');
		$this->load->model('MStock');
		$this->load->model('MBorrowRequest');
		$this->load->model('MBorrowRecord');
		
		// Load Register Users Data
		$registerUsers = $this->MUser->getAll();
		$registerUsers2 = array();
		foreach ($registerUsers as $user){
			$user->realDonateCount = $this->MStock->getDonateCount($user->uid);
			$user->donateCount = $this->MStock->getTotalDonateCount($user->uid);
			$user->wishesCount = $this->MBorrowRequest->getCountByUserId($user->uid);
			$user->readingCount = $this->MStock->getReadingCount($user->uid);
			$user->availableCount = $this->MStock->getAvailableCount($user->uid);
			$user->borrowedCount = $this->MBorrowRecord->getBorrowedCount($user->uid);
			$registerUsers2[] = $user;
		}
		
		$data['registerUsers'] = $registerUsers2;
		
		$this->load->view('admin/menu');
		$this->load->view('admin/user/list', $data);
	}
	
	function userinvite(){
		$this->load->model('MTempUser');
		
		$data['unregisterUsers'] = $this->MTempUser->getAll();
	
		$this->load->view('admin/menu');
		$this->load->view('admin/user/invite', $data);
	}
	//赠送摆摆卷
	function gift_do($uid){
		$this->load->model('MUser');
		$this->load->model('MEventLog');
		$this->load->model('MNotification');
		$this->config->load('baibai');
		
		$count = intval($this->input->post('giftCount'));
		$reason = $this->input->post('giftReason');
		
		$event->uid = $uid;
		$event->title = '赠送'.$count.'张摆摆卷';
		$event->content = $reason;
		$event->category = $this->config->item('EVENT_CATEGORY_GIFTBAIBAIQUAN');
		
		$notification->receiverUid = $uid;
		$notification->content = "恭喜您，获得了系统赠送的 {$count} 张摆摆券！赶紧去<a href=\"". site_url("book/") ."\">书架</a>找找自己想读的书吧~";
		
		$this->MUser->addSomeBorrowQuote($uid, $count);
		$this->MEventLog->insert($event);
		$this->MNotification->insert($notification);
		
		redirect('admin/user/'.$uid);
	}
	
	function invite_do(){
		$this->load->model('MTempUser');
		$this->load->library('email');
		
		$email = $this->input->post('email');
		$title = $this->input->post('emailTitle');
		$content = $this->input->post('emailContent');
		
		if ($inviteUser = $this->MUser->isInvited($email)){
			if($this->MUser->getByEmail($email)){
				$this->session->set_flashdata('error', '此人已经注册摆摆。');
				redirect('admin/userinvite/');
			} else{
				$url = site_url("account/register/$inviteUser->code/");
				$content .= "<p>请点击以下链接完成注册：</p>";
				$content .= "<p><a href=\"$url\">$url</a></p>";
				$this->email->baibaiSend($email, $title, $content);
				
				$this->session->set_flashdata('info', '已经邀请您的好友。');
				redirect('admin/userinvite/');
			}
		}
		
		if ($inviteUser = $this->MTempUser->newTempUser(0, $email)){			
			// Send Invite Mail
			$url = site_url("account/register/$inviteUser->code/");
			$content .= "<p>请点击以下链接完成注册：</p>";
			$content .= "<p><a href=\"$url\">$url</a></p>";
			$this->email->baibaiSend($email, $title, $content);
			
			$this->session->set_flashdata('info', '已经邀请您的好友。');
			redirect('admin/userinvite/');
		} else{
			$this->session->set_flashdata('error', '出现错误，请重试。');
			redirect('admin/userinvite/');
		}
	}

	function reinvite_do($uid){
		$this->load->model('MUser');
		$this->load->library('email');
    
		if ($inviteUser = $this->MUser->getByUid($uid)){
			if($this->MUser->getByEmail($inviteUser->email)){
				$this->session->set_flashdata('error', '此人已经注册摆摆。');
				redirect('admin/userinvite/');
			} else{
				$url = site_url("account/register/$inviteUser->code/");
				$title = "摆摆书架邀请您参加内测";
				$content = "<p>请点击以下链接完成注册：</p>";
				$content .= "<p><a href=\"$url\">$url</a></p>";
 				$this->email->baibaiSend($inviteUser->email, $title, $content);
        
 				$this->MUser->updateInviteTime($uid);
        
				$this->session->set_flashdata('info', '已经邀请您的好友。');
				redirect('admin/userinvite/');
			}
 		}
	}

	function applyusers($later='0'){
		$this->load->model('MApplyUser');
		
		$data['users'] = $this->MApplyUser->getAll($later);
	
		$this->load->view('admin/menu');
		$this->load->view('admin/user/applyusers', $data);
	}
	
	function invite_applyuser_do(){
		$this->load->model('MApplyUser');
		$this->load->model('MTempUser');
		$this->load->model('MUser');
		$this->load->library('email');
		
		$email = $this->input->post('email');
		$nickname = $this->input->post('nickname');
		$oldEmail = $this->input->post('oldEmail');
		
		$title = $nickname.'，摆摆书架邀请您参加内测';
		$content = '';
		
		if ($inviteUser = $this->MUser->isInvited($email)){
			$this->session->set_flashdata('error', '此人已经注册摆摆。');
		}
		
		if ($inviteUser = $this->MTempUser->newTempUser(0, $email)) {			
			// Send Invite Mail
			$url = site_url("account/register/$inviteUser->code/");
			$content .= "<p>请点击以下链接完成注册：</p>";
			$content .= "<p><a href=\"$url\">$url</a></p>";
			$this->email->baibaiSend($email, $title, $content);
			
			$this->session->set_flashdata('info', '已经邀请您的好友。');
		} else{
			$this->session->set_flashdata('error', '出现错误，请重试。');
		}
		echo $oldEmail;
		$this->MApplyUser->delete($oldEmail);
	}
	
	function later_applyuser_do(){
		$this->load->model('MApplyUser');
		
		$email = $this->input->post('email');
		
		$this->MApplyUser->markAsInviteLater($email);
	}
	
	function delete_applyuser_do(){
		$this->load->model('MApplyUser');
		
		$email = $this->input->post('email');
		
		$this->MApplyUser->delete($email);
	}
  
	function record0(){
		$this->load->model('MBorrowRequest');
		$this->load->model('MUser');
		$this->load->model('MStock');
    
		$requests = $this->MBorrowRequest->getRequests();
    
		$data['requests'] = $requests;
    
		$this->load->view('admin/menu');
		$this->load->view('admin/borrowrecord/statue0', $data);
	}
  
	function record1(){
		$this->load->model('MBorrowRecord');
		$this->load->model('MUser');
		$this->load->model('MStock');
    
		$records = $this->MBorrowRecord->getRecords(1);
    
		$data['records'] = $records;
    
		$this->load->view('admin/menu');
		$this->load->view('admin/borrowrecord/statue1', $data);
	}
  
	function record2(){
		$this->load->model('MBorrowRecord');
		$this->load->model('MUser');
		$this->load->model('MStock');
    
		$records = $this->MBorrowRecord->getRecords(2);
    
		$data['records'] = $records;
    
		$this->load->view('admin/menu');
		$this->load->view('admin/borrowrecord/statue2', $data);
	}
  
  function record3(){
    $this->load->model('MBorrowRecord');
    $this->load->model('MUser');
    $this->load->model('MStock');
    
    $records = $this->MBorrowRecord->getRecords(3);
    
    $data['records'] = $records;
    
    $this->load->view('admin/menu');
    $this->load->view('admin/borrowrecord/statue3', $data);
  }
  
	function record4(){
		$this->load->model('MBorrowRecord');
		$this->load->model('MUser');
		$this->load->model('MStock');
    
		$data['records'] = $this->MBorrowRecord->getRecords4and5();
    
		$this->load->view('admin/menu');
		$this->load->view('admin/borrowrecord/statue4', $data);
	}
	
	function weekly($weeklyId = 0) {
		$this->load->model('MWeekly');
		
		if (0 == $weeklyId) {
			$data['weeklyList'] = $this->MWeekly->getAll();
			$this->load->view('admin/menu');
			$this->load->view('admin/weekly/all', $data);
		} else {
			
		}
	}
	
	function weekly_send($weeklyId, $startUid=1){
		$this->load->model('MUser');
		$this->load->model('MBorrowRecord');
		$this->load->model('MEmailNotify');
		$this->load->model('MWeekly');
		$this->load->model('MBook');
		$this->load->model('MStock');
		$this->load->model('MAddress');
		$this->load->library('email');
		$this->load->helper('gravatar');
		
		$finalUid = $this->MUser->getMaxUserId();
		$step = 20;
		$endUid = ($startUid+$step-1)<$finalUid ? $startUid+$step-1 : $finalUid;
		echo $startUid.'<br/>';
		
		$weekly = $this->MWeekly->getById($weeklyId);
		$title = "摆摆特刊第{$weeklyId}期：{$weekly->name}";
		$book1 = $this->MBook->getById($weekly->book1Id);
		$book2 = $this->MBook->getById($weekly->book2Id);
		$book3 = $this->MBook->getById($weekly->book3Id);
		$book4 = $this->MBook->getById($weekly->book4Id);
		$book5 = $this->MBook->getById($weekly->book5Id);
		$book6 = $this->MBook->getById($weekly->book6Id);
		$book7 = $this->MBook->getById($weekly->book7Id);
		$book8 = $this->MBook->getById($weekly->book8Id);
		$book9 = $this->MBook->getById($weekly->book9Id);
		$book10 = $this->MBook->getById($weekly->book10Id);
		
		if ($weekly->isSent) {
			return 0;
		}

		$data['title'] = $title;
		$data['weekly'] = $weekly;
		$data['books'] = array($book1, $book2, $book3, $book4, $book5, $book6, $book7, $book8, $book9, $book10);
		$data['stockNumber'] = $this->MStock->getNumber();
		$data['cityNumber'] = $this->MAddress->getCitiesCount();
		
		for($i=$startUid; $i<=$endUid; $i++){
			$notifySetting = $this->MEmailNotify->getByUid($i);
			if((!$notifySetting) || (!$notifySetting->weekly)){
				continue;
			}
			
			$user = $this->MUser->getByUid($i);
			
			$borrowRequests = $this->MBorrowRecord->getBorrowRequestToMe($i, 'available');
			if(sizeof($borrowRequests)<2){
				$borrowRequests2 = $this->MBorrowRecord->getBorrowRequestToMe($i, 'reading');
				$borrowRequests = array_merge($borrowRequests, $borrowRequests2);
			}
			$borrowRequests = array_slice($borrowRequests, 0, 2);
			
			$data['borrowRequests'] = $borrowRequests;
			$data['user'] = $user;
			
			$to = $user->email;
			$subject = $title;
			$message = $this->load->view('email/weekly', $data, TRUE);
			
			$this->email->baibaiSend($to, $subject, $message);
		}
		
		if($endUid<$finalUid){
			$data['newStartUid'] = $endUid+1;
			$this->load->view('admin/menu');
			$this->load->view('admin/weekly/send', $data);
		} else{
			echo 'finish';
		}
	}
}

/* End of file admin.php */
/* Location: ./system/application/controllers/admin.php */