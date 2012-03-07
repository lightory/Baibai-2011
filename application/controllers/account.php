<?php

class Account extends CI_Controller {
	function __construct(){
		parent::__construct();
		if($this->session->userdata('uid')==1){
			$this->output->enable_profiler(TRUE);
		}
	}
	
	function index(){
		$this->Validate->isNotLogin();
		
		$this->load->model('MBook');
		$this->load->model('MStock');
		$this->load->helper('string');
		$this->load->library('rss');
	   
		// Load blogPost
		$config["url"]="http://blog.bookfor.us/feed/";
		
		$this->rss->initialize($config);  
		$blogPosts = $this->rss->getRss();
	   
		$headerData['title'] = '摆摆书架';
		$headerData['styles'] = array('page_index.css');
		$data['blogPosts'] = $blogPosts;
		
		// slideBooks
		$slideBooks['title'] = '随机推荐';
		$slideBooks['books'] = $this->MBook->getLastestAvailableBooks('20', 0, true);
		$data['slideBooks'] = $slideBooks;
	   
		$this->load->view('header', $headerData);
		$this->load->view('index', $data);
		$this->Common->footer();
	}
	
	function register($inviteCode = '0'){
		$headerData['title'] = '注册';
		$headerData['current'] = 'home';
		
		$this->load->model('MTempUser');
		$data['ok'] = false;
		if ( ($inviteCode != '0') && ($user = $this->MTempUser->getByCode($inviteCode) ) ){
			$data['ok'] = true;
			$data['user'] = $user;
		}
		
		$this->load->view('header', $headerData);
		$this->load->view('account/register', $data);
		$this->Common->footer();
	}
	
	function login($type='normal'){
		$this->Validate->isNotLogin();
		
		if($type=='fancy'){
			$this->load->view('account/login_fancy');
		} else{
			$headerData['title'] = '登录';
			$headerData['current'] = 'home';

			$this->load->view('header', $headerData);
			$this->load->view('account/login');
			$this->Common->footer();
		}
	}
	
	function resetpassword($confirm='0'){
		$this->Validate->isNotLogin();
	
		$headerData['title'] = '重设密码';
		$headerData['current'] = 'home';
		$data['confirm'] = $confirm;
		
		$this->load->view('header', $headerData);
		$this->load->view('account/resetpassword', $data);
		$this->Common->footer();
	}
	
	function home(){	
		$this->Validate->isLogin();
		$userId = $this->session->userdata('uid');
		redirect("user/$userId");
	}
	
	function view($username){
		$this->load->model('MUser');
		$this->load->model('MFollow');
		$this->load->model('MStock');
		$this->load->model('MBorrowRequest');
		$this->load->model('MBorrowRecord');
		$this->load->model('MBook');

		$user = $this->MUser->getByUsername($username);
		if(!$user){
			$user = $this->MUser->getByUid($username); 
			if (!$user){ show_404('misc/404'); }
			redirect("user/$user->username");
		}
		
		if ( ($this->session->userdata('uid')) && ($this->session->userdata('uid') == $user->uid) ){
			$data['isSelf'] = true;
			// change Statue to Live
			if('away'==$user->statue){
				$this->MStock->updateReaderStatue($user->uid, 'live');
				$this->MUser->updateStatue($user->uid, 'live');
				$data['statueUpdated'] = true;
			}
		} else{
			$data['isSelf'] = false;
		}
			
		# Load 我想借的书
		$wishRecords = $this->MBorrowRequest->getUserWishes($user->uid, 6, 0);
		$wishBooks = array();
		foreach ($wishRecords as $record){
			$book = $this->MBook->getById($record->bookId);
			$wishBooks[] = $book;
		}
			
		# Load 已借入的书
		$onhandStocks = $this->MStock->getByReaderId($user->uid, 6, 0);
		$onhandBooks = array();
		foreach ($onhandStocks as $stock){
			$book = $this->MBook->getById($stock->bookId);
			$onhandBooks[] = $book;
		}
			
		# Load 可借出的书
		$availableStocks = $this->MStock->getAvailableByReaderId($user->uid, 6, 0);
		$availableBooks = array();
		foreach ($availableStocks as $stock){
			$book = $this->MBook->getById($stock->bookId);
			$availableBooks[] = $book;
		}
			
		$headerData['title'] = $user->nickname;
		$headerData['current'] = 'home';
		$data['messages'] = array();
		$data['user'] = $user;
		$data['onhandBooks'] = $onhandBooks;
		$data['wishBooks'] = $wishBooks;
		$data['availableBooks'] = $availableBooks;
		$data['onhandCount'] = $this->MStock->getReadingCount($user->uid);
		$data['wishesCount'] = $this->MBorrowRequest->getCountByUserId($user->uid);
		$data['availableCount'] = $this->MStock->getAvailableCount($user->uid);
		$data['onhandNoticeCount'] = $this->MBook->getOnhandNoticeCount($user->uid);
		$data['availableNoticeCount'] = $this->MBook->getAvailableNoticeCount($user->uid);
			
		if ($data['isSelf']){
			// Load Message
			$this->load->model('message');
			$this->load->model('MNotification');
			$data['messages'] = $this->message->load();
			$data['messageCount'] = sizeof($data['messages']);
			$data['notifications'] = $this->MNotification->getByReceiver($this->session->userdata('uid'));
			$data['isSelf'] = true;
		}
			
		$this->load->view('header', $headerData);
		$this->load->view('account/view', $data);
		$this->Common->footer();
	}
	
	function wishes($username = '0'){
	  if($username == '0'){
		  $this->Validate->isLogin();
			$username = $this->session->userdata('uid'); 
    }
		
		$this->load->model('MBorrowRequest');
		$this->load->model('MBorrowRecord');
		$this->load->model('MBook');
		$this->load->model('MStock');
		$this->load->model('MUser');
		
		$user = $this->MUser->getByUsername($username);
		if(!$user){
			$user = $this->MUser->getByUid($username); 
			if (!$user){ show_404(); }
			redirect("user/$user->username/wishes/");
		}
		
		$data['isSelf'] = false;
		if ( $user->uid == $this->session->userdata('uid') ){
			$data['isSelf'] = true;
		}	
		
		$stocks = $this->MBorrowRequest->getUserWishes($user->uid);
		$books = array();
		foreach($stocks as $stock){
			$books[] = $this->MBook->getById($stock->bookId);
		}
		
		$headerData['title'] = $user->nickname.'想借的书';
		$headerData['current'] = 'home';
		$data['books'] = $books;
		$data['messages'] = array();
		$data['user'] = $user;
		$data['onhandCount'] = $this->MStock->getReadingCount($user->uid);
		$data['wishesCount'] = $this->MBorrowRequest->getCountByUserId($user->uid);
		$data['availableCount'] = $this->MStock->getAvailableCount($user->uid);
		$data['onhandNoticeCount'] = $this->MBook->getOnhandNoticeCount($user->uid);
		$data['availableNoticeCount'] = $this->MBook->getAvailableNoticeCount($user->uid);
			
		if ($data['isSelf']){
			// Load Message
			$this->load->model('message');
			$this->load->model('MNotification');
			$data['messages'] = $this->message->load();
			$data['messageCount'] = sizeof($data['messages']);
			$data['notifications'] = $this->MNotification->getByReceiver($this->session->userdata('uid'));
		}
		
		$this->load->view('header', $headerData);
		$this->load->view('account/wishes', $data);
		$this->Common->footer();
	}
	
	function onhand($username = '0'){
		if($username == '0'){
      $this->Validate->isLogin();
			$username = $this->session->userdata('uid'); 
    }
		
		$this->load->model('MStock');
		$this->load->model('MBook');
		$this->load->model('MBorrowRequest');
		$this->load->model('MBorrowRecord');
		$this->load->model('MUser');
		
		$user = $this->MUser->getByUsername($username);
		if(!$user){
			$user = $this->MUser->getByUid($username); 
			if (!$user){ show_404(); }
			redirect("user/$user->username/onhand/");
		}
		
		$data['isSelf'] = false;
		if ( $user->uid == $this->session->userdata('uid') ){
			$data['isSelf'] = true;
		}		
		
		$stocks = $this->MStock->getByReaderId($user->uid);
		$books = array();
		foreach($stocks as $stock){
			$book = $this->MBook->getById($stock->bookId);
			$book->stockId = $stock->id;
			$book->transforTime = $stock->transforTime;
			$books[] = $book;
		}
		
		$headerData['title'] = $user->nickname.'已借入的书';
		$headerData['current'] = 'home';
		$data['books'] = $books;
		$data['messages'] = array();
		$data['user'] = $user;
		$data['onhandCount'] = $this->MStock->getReadingCount($user->uid);
		$data['wishesCount'] = $this->MBorrowRequest->getCountByUserId($user->uid);
		$data['availableCount'] = $this->MStock->getAvailableCount($user->uid);
    $data['onhandNoticeCount'] = $this->MBook->getOnhandNoticeCount($user->uid);
    $data['availableNoticeCount'] = $this->MBook->getAvailableNoticeCount($user->uid);
			
		if ($data['isSelf']){
			// Load Message
			$this->load->model('message');
			$this->load->model('MNotification');
			$data['messages'] = $this->message->load();
			$data['messageCount'] = sizeof($data['messages']);
			$data['notifications'] = $this->MNotification->getByReceiver($this->session->userdata('uid'));
		}
		
		$this->load->view('header', $headerData);
		$this->load->view('account/onhand', $data);
		$this->Common->footer();
	}
	
	function available($username = '0'){
		if($username == '0'){
      $this->Validate->isLogin();
			$username = $this->session->userdata('uid'); 
    }

		$this->load->model('MStock');
		$this->load->model('MBook');
		$this->load->model('MBorrowRequest');
		$this->load->model('MBorrowRecord');
		$this->load->model('MUser');
		
		$user = $this->MUser->getByUsername($username);
		if(!$user){
			$user = $this->MUser->getByUid($username); 
			if (!$user){ show_404(); }
			redirect("user/$user->username/available/");
		}
		
		$data['isSelf'] = false;
		if ( $user->uid == $this->session->userdata('uid') ){
			$data['isSelf'] = true;
		}		
		
		$stocks = $this->MStock->getAvailableByReaderId($user->uid);
		$books = array();
		foreach($stocks as $stock){
			$book = $this->MBook->getById($stock->bookId);
			$book->finishTime = $stock->finishTime;
			$books[] = $book;
		}
		
		$headerData['title'] = $user->nickname.'可借出的书';
		$headerData['current'] = 'home';
		$data['books'] = $books;
		$data['user'] = $user;
		$data['onhandCount'] = $this->MStock->getReadingCount($user->uid);
		$data['wishesCount'] = $this->MBorrowRequest->getCountByUserId($user->uid);
		$data['availableCount'] = $this->MStock->getAvailableCount($user->uid);
    $data['onhandNoticeCount'] = $this->MBook->getOnhandNoticeCount($user->uid);
    $data['availableNoticeCount'] = $this->MBook->getAvailableNoticeCount($user->uid);

		if ($data['isSelf']){
			// Load Message Count
			$this->load->model('message');
			$this->load->model('MNotification');
			$data['messages'] = $this->message->load();
			$data['messageCount'] = sizeof($data['messages']);
			$data['notifications'] = $this->MNotification->getByReceiver($this->session->userdata('uid'));
		}
		
		$this->load->view('header', $headerData);
		$this->load->view('account/available', $data);
		$this->Common->footer();
	}
	
	function notice(){
		$this->Validate->isLogin();
		
		$this->load->model('MStock');
		$this->load->model('MBook');
		$this->load->model('MBorrowRequest');
		$this->load->model('MBorrowRecord');
		$this->load->model('MUser');
		
		$userId = $this->session->userdata('uid');
		
		// Load Message
		$this->load->model('message');
		$data['messages'] = $this->message->load();
		
		$headerData['title'] = '系统通知';
		$headerData['current'] = 'home';
		$data['userId'] = $userId;
		$data['user'] = $this->MUser->getByUid($userId);
		$data['onhandCount'] = $this->MStock->getReadingCount($userId);
		$data['wishesCount'] = $this->MBorrowRequest->getCountByUserId($user->uid);
		$data['availableCount'] = $this->MStock->getAvailableCount($userId);
    $data['onhandNoticeCount'] = $this->MBook->getOnhandNoticeCount($userId);
    $data['availableNoticeCount'] = $this->MBook->getAvailableNoticeCount($userId);
		
		$this->load->view('header', $headerData);
		$this->load->view('account/notice', $data);
		$this->Common->footer();
	}
	
	function myinvites(){
		$this->Validate->isLogin();
		
		$this->load->model('MUser');
		$this->load->model('MTempUser');
		$this->load->helper('gravatar');
		
		$registerUsers1 = $this->MUser->getByIntroducer( $this->session->userdata('uid') );
		$registerUsers = array();
		$unregisterUsers = $this->MTempUser->getByIntroducer( $this->session->userdata('uid') );
		foreach ( $registerUsers1 as $user ){
			$user->avatar = getGravatar( $user->email, 45);
			$registerUsers[] = $user;
		}
		
		$headerData['title'] = '我邀请的朋友';
    $headerData['current'] = 'home';
		$data['registerUsers'] = $registerUsers;
		$data['unregisterUsers'] = $unregisterUsers;
		$data['registerUsersCount'] = sizeof($registerUsers);
		$data['unregisterUsersCount'] = sizeof($unregisterUsers);
		$data['totalCount'] = $data['registerUsersCount'] + $data['unregisterUsersCount'];
		
		$this->load->view('header', $headerData);
		$this->load->view('account/myinvites', $data);
		$this->Common->footer();
	}
	
	// DOs
	function login_do(){		
		$email = $this->input->post('email');
		$password  = $this->input->post('password');
		
		$this->load->model('MUser');
		
		if ($user = $this->MUser->loginValid($email, md5($password) )){
			$this->session->set_userdata('uid',$user->uid);
			$this->session->set_userdata('nickname',$user->nickname);
			$this->session->set_userdata('email',$user->email);
			$this->session->set_userdata('password',$user->password);
			
			if ( $this->input->post('remember') ){
				set_cookie('email', $user->email, '1872000'); 
				set_cookie('password', $user->password, '1872000'); 
			}
			
			$this->load->model('MUserLogin');
			$this->MUserLogin->insert($user->uid);
			
			if($redirectAfterLogin = $this->session->userdata('redirectAfterLogin')){
				header('Location: '.$redirectAfterLogin);
			} else{
				redirect('home/');
			}
		} else {
			$this->session->set_flashdata('error', '你的email和密码不符，请再试一次');
			redirect('account/login/');
		}
	}
	
	function logout_do(){
		$this->session->unset_userdata('uid');
		$this->session->sess_destroy();
		$this->session->set_flashdata('info', '已退出摆摆');
		
		delete_cookie("uid");
		delete_cookie("nickname");
		delete_cookie("email");
		delete_cookie("password");
		
		redirect();
	}
	
	function resetpassword_do($confirm=0){
		$this->load->model('MUser');
		$this->load->model('MResetPassword');
	
		if ($email = $this->input->post('email')){
			if ($confirm = $this->MResetPassword->insert($email)){
				$url = site_url("account/resetpassword/$confirm/");
				$user = $this->MUser->getByEmail($email);
				$name = $user->nickname;
				
				# 邮件标题及内容
				$subject = "重设 $name 在摆摆书架的密码";
				$message = "<p>亲爱的$name:</p>";
				$message .= "<p>您的密码重设要求已经得到验证。请点击以下链接输入您新的密码：</p>";
				$message .= "<p><a href=\"$url\">$url</a></p>";
			
				$this->load->library('email');
				$this->email->baibaiSend($email, $subject, $message);
			
				$this->session->set_flashdata('info', '您的重设密码已受理，请查收邮件进行验证。');
				redirect('account/resetpassword/1/');
			} else{
				$this->session->set_flashdata('error', '这个email地址没有注册过');
				redirect('account/resetpassword/');
			}
		} elseif ($password = $this->input->post('password')){
			$key = $this->MResetPassword->getByConfirm($confirm);
			$email = $key->email;
			
			$this->MUser->updatePasswordByEmail($email, $password);
			$this->MResetPassword->delete($email);
			
			redirect('account/login/');
		}
	}
	
	function invite_do(){
		$this->Validate->isLogin();
		
		$this->load->model('MUser');
		$this->load->model('MTempUser');
		
		$user = $this->MUser->getByUid($this->session->userdata('uid'));
		
		if ($user->inviteQuote < 1){
			$this->session->set_flashdata('error', '您的邀请限额不够。');
			redirect('home/');
		}
		
		$uid = $user->uid;
		$nickname = $user->nickname;
		$email = $this->input->post('email');
		if ($this->MUser->isInvited($email)){
			$this->session->set_flashdata('error', '您的好友已经注册摆摆。');
			redirect('mine/');
		}
		
		if ($inviteUser = $this->MTempUser->newTempUser($uid, $email)){			
			// Send Invite Mail
			$this->load->model('Mail');
			$this->Mail->invite($inviteUser->email, $user, $inviteUser);
			
			$this->MUser->minusInviteQuote($uid);
			
			$this->session->set_flashdata('info', '已经邀请您的好友。');
			redirect('mine/');
		} else{
			$this->session->set_flashdata('error', '出现错误，请重试。');
			redirect('mine/');
		}
	}
	
	/*
		TODO 没有使用，或许可以删掉
	*/
	function send_invitation_do($userId){
		$this->Validate->isLogin();
		
		$this->load->model('MUser');
		$inviteUser = $this->MUser->getByUid($userId);
		$user = $this->MUser->getByUid($this->session->userdata('uid'));
		
		if ( $inviteUser->introducerUid != $this->session->userdata('uid') ){
			$this->session->set_flashdata('error', '没有权限。');
			redirect('account/myinvites');
		}
		
		// Send Invite Mail
		$this->load->model('Mail');
		$this->Mail->invite($inviteUser->email, $user, $inviteUser);
		
		// Redirect
		$this->session->set_flashdata('info', '已经邀请您的好友。');
		redirect('account/myinvites');
	}
	
	function register_do(){
		$this->load->model('MUser');
		$this->load->model('MTempUser');
		$this->load->model('MAddress');
		
		$invitation = $this->input->post('invitation');
		if ($tempUser = $this->MTempUser->getByCode($invitation) ){
			// Register User
			$mobile = $this->input->post('phone');
			$nickname = $this->input->post('nickname');
			$password = $this->input->post('password');
      
			if (!$nickname){
				$this->session->set_flashdata('error', '请填写昵称');
				redirect("account/register/{$invitation}/");
			}
			
			if ($this->MUser->isMobileBind($mobile)) {
				$this->session->set_flashdata('error', '该手机号码已经被其他人使用');
				redirect("account/register/{$invitation}/");
			}
      
			$userId = $this->MUser->register($tempUser->email, $mobile, $nickname, md5($password), $tempUser->introducerUid, $tempUser->borrowQuote);
			
			$this->MTempUser->delete($tempUser->email);
			
			$user = $this->MUser->getByUid($userId);
			
			// Register Default Address
			$uid = $user->uid;
			$name = $this->input->post('name');
			$postcode = $this->input->post('district') ? $this->input->post('district') : $this->input->post('city');
			$postcodeTrue = $this->input->post('postcode');
			$province = $this->input->post('province2');
			$city = $this->input->post('city2');
			$district = $this->input->post('district2');
			$address = $this->input->post('address');
			$this->MAddress->register($uid, $name, $postcode, $postcodeTrue, $province, $city, $district, $address, $mobile);

			// Add Default EmailNotify Setting
			$this->load->model('MEmailNotify');
			$this->MEmailNotify->insert($uid);
			
			$this->load->model('MUserLogin');
			$this->MUserLogin->insert($user->uid);
			
			// Send Mobile Validate Notification
			$this->load->model('MNotification');
			$notification->receiverUid = $uid;
			$notification->content = "<a href=\"" . site_url("setting/mobile/") . "\">验证手机号码，立即获得第一张摆摆券</a>";
			$this->MNotification->insert($notification);
			
			// Login & Redirect
			$this->session->set_userdata('uid',$user->uid);
			$this->session->set_userdata('nickname',$user->nickname);
			$this->session->set_userdata('email',$user->email);
			$this->session->set_userdata('password',$user->password);
			redirect('about/firstlogin/');
		}
	}
	
	/*
	function mailtest(){
		$this->load->library('email');

		$this->email->from('noreply@bookfor.us', '摆摆书架');
		$this->email->to('lightory@gmail.com'); 

		$this->email->subject('请重设您在摆摆书架的密码');
		$this->email->message("其实这是一个测试<a href=\"" . site_url() . "\">摆摆书架</a>"); 

		$this->email->send();
	}
	*/
	
	function notification_read_do($notificationId){
		$this->Validate->isLogin();
		
		$this->load->model('MNotification');
		$this->MNotification->delete($notificationId, $this->session->userdata('uid'));
		
		// Redirect
		redirect('home/');
	}
	
	function apply(){
		$this->Validate->isNotLogin();
		
		$this->load->library('rss');
   
   	// Load blogPost
   	$config["url"]="http://blog.bookfor.us/feed/";  
   	$this->rss->initialize($config);  
   	$blogPosts = $this->rss->getRss();
	
		$headerData['title'] = '申请内测资格';
		$data['blogPosts'] = $blogPosts;
		
		$this->load->view('header', $headerData);
		$this->load->view('account/apply', $data);
		$this->Common->footer();
	}
	
	function apply_do(){
		$this->Validate->isNotLogin();
		
		$this->load->model('MApplyUser');
		
		$nickname = $this->input->post('nickname');
		$email = $this->input->post('email');
		$address = $this->input->post('address');
		$blog = $this->input->post('blog');
		$donate = $this->input->post('donate');
		$bio = $this->input->post('bio');
		
		if($this->MApplyUser->isEmailExist($email)){
			$this->session->set_flashdata('error', '该email已存在，请不要重复申请。');
			redirect('account/apply/');
		}
		
		$this->MApplyUser->newApply($nickname, $email, $address, $blog, $donate, $bio);
		$this->session->set_flashdata('error', '已经提交内测申请，我们会在合适的时候给你发送邀请邮件。');
		redirect('account/apply/');
	}
	
	function follow_do($uid){
		$this->Validate->isLogin();
		
		$this->load->model('MUser');
		$this->load->model('MFollow');
		$this->load->model('MNotification');
		$this->load->helper('gravatar');
		
		$this->MFollow->insert($this->session->userdata('uid'), $uid);	
		
		// Notice To Followed User
		$actionUser = $this->MUser->getByUid($this->session->userdata('uid'));
		$notice->receiverUid = $uid;
		$notice->avatar = getGravatar($actionUser->email);
		$notice->content = "<a href=\"". site_url("user/{$actionUser->username}/") ."\">{$actionUser->nickname}</a> 关注了你。";
		$this->MNotification->insert($notice);
		
		// Redirect
		redirect("user/{$uid}/");
	}
	
	function unfollow_do($uid){
		$this->Validate->isLogin();
		
		$this->load->model('MFollow');
		
		$this->MFollow->delete($this->session->userdata('uid'), $uid);	
		
		// Redirect
		redirect("user/{$uid}/");
	}
	
	function follows($username){
		$this->load->model('MUser');
		$this->load->model('MFollow');
		$this->load->helper('gravatar');
		
		$user = $this->MUser->getByUsername($username);
		if(!$user){
			$user = $this->MUser->getByUid($username); 
			if (!$user){ show_404('misc/404'); }
			redirect("user/{$user->username}/follows/");
		}
		
		if ( ($this->session->userdata('uid')) && ($this->session->userdata('uid') == $user->uid) ){
			$data['isSelf'] = true;
		} else{
			$data['isSelf'] = false;
		}
		
		$headerData['title'] = "{$user->nickname}关注的人";
		$headerData['current'] = 'home';
		$data['user'] = $user;
		$data['contacts'] = $this->MFollow->getUserFollowings($user->uid, 1000);
		
		$this->load->view('header', $headerData);
		$this->load->view('account/follows', $data);
		$this->Common->footer();
	}
	
	function fans($username){
		$this->load->model('MUser');
		$this->load->model('MFollow');
		$this->load->helper('gravatar');
		
		$user = $this->MUser->getByUsername($username);
		if(!$user){
			$user = $this->MUser->getByUid($username); 
			if (!$user){ show_404('misc/404'); }
			redirect("user/{$user->username}/follows/");
		}
		
		if ( ($this->session->userdata('uid')) && ($this->session->userdata('uid') == $user->uid) ){
			$data['isSelf'] = true;
		} else{
			$data['isSelf'] = false;
		}
		
		$headerData['title'] = "关注{$user->nickname}的人";
		$headerData['current'] = 'home';
		$data['user'] = $user;
		$data['contacts'] = $this->MFollow->getUserFollowers($user->uid, 1000);
		
		$this->load->view('header', $headerData);
		$this->load->view('account/fans', $data);
		$this->Common->footer();
	}
}

/* End of file account.php */
/* Location: ./system/application/controllers/account.php */
