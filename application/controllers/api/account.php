<?php
class Account extends CI_Controller {
	function __construct(){
		parent::__construct();
	}
	
	function getNoticeCount(){
		$this->load->model('MUser');
		$this->load->model('MNotification');
		$this->load->model('MBorrowRecord');
		$this->load->model('MAppSession');
	
		header("Access-Control-Allow-Origin: *");
		
		$email = $this->input->post('email');
    	$password = $this->input->post('password');
    	$type = $this->input->post('type');
    	
		if($user = $this->MUser->loginValid($email, $password)){
			$this->MAppSession->newSession($user->uid, $type);
		
			$num1 = $this->MBorrowRecord->getToDoCount($user->uid);
			$num2 = $this->MNotification->getCountByReceiver($user->uid);
			echo $num1+$num2;
    	} else{
    		echo '-1';
    	}
	}
}