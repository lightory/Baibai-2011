<?php
// system/application/models/mail.php
class Mail extends CI_Model{
	var $CI = '';
	var $to = '';
	var $subject = '';
	var $message = '';

	function __construct(){
		$this->CI = & get_instance();
		$this->CI->load->library('email');
		$this->CI->load->model('MUser');
		$this->CI->load->model('MBook');
    parent::__construct();
  }
	
	function _send(){
		$this->CI->email->baibaiSend($this->to, $this->subject, $this->message);
	}
	
	function invite($to, $user, $inviteUser){
		$this->to = $to;
		$url = site_url("account/register/$inviteUser->code/");
		$this->subject = "$user->nickname 邀请您注册摆摆书架";
		$this->message = "<p>您的好友 $user->nickname 邀请您注册摆摆书架。请点击以下链接完成注册：</p>";
		$this->message .= "<p><a href=\"$url?utm_source=email&utm_medium=email&utm_campaign=borrownotice\">$url</a></p>";
		$this->message .= "摆摆书架是一个社会化图书馆。所有藏书均来自会员捐赠、寄存在会员处，并且所有会员都可以随时借阅。";
			
		$this->_send();
	}
	
	function someoneWantYourBook($to, $receiver, $reader, $book){
		$this->to = $to;
		$url = site_url('mine/available/').'#book-'.$book->id;
		$this->subject = $receiver->nickname."(".$receiver->address->province.")向您借《$book->name".'》';
		$this->message = "<p>$receiver->nickname"."(".$receiver->address->province.")向您借《$book->name"."》，去看看 TA 说了什么吧：</p>";
		$this->message .= "<p><a href=\"$url?utm_source=email&utm_medium=email&utm_campaign=borrownotice\">$url</a></p>";
		
		$this->_send();
	}
	
	function someoneWantSendYouBook($record){
		$receiver = $this->CI->MUser->getByUid($record->receiverUid);
		$senter = $this->CI->MUser->getByUid($record->senterUid);
		$book = $this->CI->MBook->getById($record->bookId);
		
		$url = site_url('mine/');
		
		$this->to = $receiver->email;
		$this->subject = "有人愿意借给您《$book->name".'》啦~';
		$this->message = "<p>$senter->nickname 愿意借《$book->name"."》给您，赶紧将你的地址提供给TA吧～</p>";
		$this->message .= "<p>详情请点击：</p>";
		$this->message .= "<p><a href=\"$url?utm_source=email&utm_medium=email&utm_campaign=borrownotice\">$url</a></p>";
		
		$this->_send();
	}
	
	function getReceiverAddress($record){
		$receiver = $this->CI->MUser->getByUid($record->receiverUid);
		$senter = $this->CI->MUser->getByUid($record->senterUid);
		$book = $this->CI->MBook->getById($record->bookId);
	
		$url = site_url('mine/');
		
		$this->to = $senter->email;
		$this->subject = "快把《$book->name"."》寄给 $receiver->nickname 吧~";
		$this->message = "<p>$receiver->nickname 已经提供了收书地址，快寄出《$book->name"."》吧~</p>";
		$this->message .= "详情请点击以下链接：</p>";
		$this->message .= "<p><a href=\"$url?utm_source=email&utm_medium=email&utm_campaign=borrownotice\">$url</a></p>";
		
		$this->_send();
	}
	
	function notGetReceiverAddress($record){
		$receiver = $this->CI->MUser->getByUid($record->receiverUid);
		$senter = $this->CI->MUser->getByUid($record->senterUid);
		$book = $this->CI->MBook->getById($record->bookId);
	
		$url = site_url('mine/');
		
		$this->to = $senter->email;
		$this->subject = "$receiver->nickname 已经不需要《".$book->name."》了";
		$this->message = "<p>$receiver->nickname 已经不需要《$book->name"."》了，您可以将这本书借给其他人。详情请点击以下链接：</p>";
		$this->message .= "<p><a href=\"$url?utm_source=email&utm_medium=email&utm_campaign=borrownotice\">$url</a></p>";
		
		$this->_send();
	}
	
	function bookSent($record){
		$receiver = $this->CI->MUser->getByUid($record->receiverUid);
		$senter = $this->CI->MUser->getByUid($record->senterUid);
		$book = $this->CI->MBook->getById($record->bookId);
	
		$url = site_url('mine/');
		
		$this->to = $receiver->email;
		$this->subject = "$senter->nickname 已经寄出《$book->name".'》';
		$this->message = "<p>$senter->nickname 已经寄出《$book->name"."》。您收到书后，记得上摆摆确认下哦~详情请点击以下链接：</p>";
		$this->message .= "<p><a href=\"$url?utm_source=email&utm_medium=email&utm_campaign=borrownotice\">$url</a></p>";
		
		$this->_send();
	}
	
	function bookReceive($record){
		$receiver = $this->CI->MUser->getByUid($record->receiverUid);
		$senter = $this->CI->MUser->getByUid($record->senterUid);
		$book = $this->CI->MBook->getById($record->bookId);
	
		$url = site_url('mine/');
		
		$this->to = $senter->email;
		$this->subject = "$receiver->nickname 确认收到《$book->name".'》';
		$this->message = "<p>$receiver->nickname 确认收到《$book->name"."》，您已经获得一张摆摆券奖励~</p>";
		$this->message .= "<p>详情请点击以下链接：</p>";
		$this->message .= "<p><a href=\"$url?utm_source=email&utm_medium=email&utm_campaign=borrownotice\">$url</a></p>";
		
		$this->_send();
	}
}