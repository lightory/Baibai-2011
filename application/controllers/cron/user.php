<?php
class User extends CI_Controller {
	function __construct(){
		parent::__construct();
	}
	
	function index(){
		echo '';
	}
	
	// 发送邀请限额
	function sentInviteQuote(){
		$this->load->model('MUser');
		$this->load->model('MNotification');
	
		$current = time();
		$yesterday = $current - 24*60*60;
		
		$users = $this->MUser->getSentBookList($yesterday, $current);
		foreach($users as $user){
			if($user->inviteQuote<2){
				$addNumber = $user->count;
				$this->MUser->addInviteQuote($user->uid, $addNumber);
				
				// Send Notification to Senter
        		$notification->receiverUid = $user->uid;
        		$notification->avatar = 'http://www.gravatar.com/avatar/ba034163a60d54a96b214b284b152062?s=20';
        		$notification->content = "恭喜您获得 $addNumber 个邀请限额，可以邀请朋友加入摆摆 :-)";
        		$notification->time = $current;
        		$this->MNotification->insert($notification);
			}
		}
	}
  
  // 删除两周没注册的用户
  function deleteUnregistedUser(){
    $this->load->model('MTempUser');
    $this->load->library('email');
    
    $currentTime = time();
    $users = $this->MTempUser->getAll(1000);
    foreach($users as $user){
      if( $currentTime - $user->time > 14*24*60*60 ){
        $this->MTempUser->delete($user->email);
      } elseif ( $currentTime - $user->time > 7*24*60*60 ){
        $url = site_url("account/register/$user->code/");
        $title = '摆摆书架邀请您参加内测';
        $content = "<p>恭喜您获得摆摆书架的内测资格～</p>";
        $content .= "<p>请点击以下链接完成注册：</p>";
        $content .= "<p><a href=\"$url\">$url</a></p>";
        $this->email->baibaiSend($user->email, $title, $content);
      }
    }
  }
}