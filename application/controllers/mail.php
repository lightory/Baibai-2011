<?php
class Mail extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('MUser');
		$this->load->helper('Gravatar');
		
		$this->Validate->isLogin();
  }
  
  function index(){
    $this->inbox();
  }
  
  function inbox(){
    $headerData['title'] = '站内信';
    $headerData['current'] = 'home';
		$headerData['styles'] = array('mail.css');
    $data['unreadInboxMailCount'] = 			$this->MMail->getUserInboxUnreadCount($this->session->userdata('uid'));
    $data['inboxMailCount'] = $this->MMail->getUserInboxCount($this->session->userdata('uid'));
    $data['outboxMailCount'] = $this->MMail->getUserOutboxCount($this->session->userdata('uid'));
    $data['mails'] = $this->MMail->getUserInboxMail($this->session->userdata('uid'));
    
    $this->MMail->markAsRead($this->session->userdata('uid'));
    
    $this->load->view('header', $headerData);
    $this->load->view('mail/inbox', $data);
    $this->Common->footer();
  }
  
  function outbox(){
    $headerData['title'] = '站内信';
    $headerData['current'] = 'home';
		$headerData['styles'] = array('mail.css');
    $data['unreadInboxMailCount'] = $this->MMail->getUserInboxUnreadCount($this->session->userdata('uid'));
    $data['inboxMailCount'] = $this->MMail->getUserInboxCount($this->session->userdata('uid'));
    $data['outboxMailCount'] = $this->MMail->getUserOutboxCount($this->session->userdata('uid'));
    $data['mails'] = $this->MMail->getUserOutboxMail($this->session->userdata('uid'));
    
    $this->load->view('header', $headerData);
    $this->load->view('mail/outbox', $data);
    $this->Common->footer();
  }
  
  function send($receiverUid){
    $this->load->model('MAddress');
    
    $receiver = $this->MUser->getByUid($receiverUid);
    $receiver->address = $this->MAddress->getDefaultByUid($receiverUid);
    $receiver->avatar = getGravatar($receiver->email, 32);
    
    $data['receiver'] = $receiver;
    
    $this->load->view('mail/send', $data);
  }
  
  function send_do($receiverUid){
    $senterUid = $this->session->userdata('uid');
    $content = nl2br($this->input->post('mailContent'));
    
    if ($this->MMail->insert($senterUid, $receiverUid, $content)){
			// send email to receiver
			$this->load->model('MEmailNotify');
			$notifySetting = $this->MEmailNotify->getByUid($receiverUid);
			if(($notifySetting) && ($notifySetting->mail)){
				$this->load->model('MUser');
				$this->load->library('email');
				
				$receiver = $this->MUser->getByUid($receiverUid);
				$senter = $this->MUser->getByUid($senterUid);
				
				$url = site_url("mail/");
				$url2 = site_url("setting/notify/");
        $title = $receiver->nickname.'，您有新站内信';
        $content = "<p>$senter->nickname 给您发来了新的站内信，请（登陆后）点击这里查看：<br/>";
        $content .= "<a href=\"$url\">$url</a></p>";
				$content .= "<br/><br/>";
				$content .= "如果您不希望继续收到此类邮件，请点击下面链接，设置取消（需要先登录）<br/>";
				$content .= "<a href=\"$url2\">$url2</a></p>";
        $this->email->baibaiSend($receiver->email, $title, $content);
			}
			
			// 成功提示信息
      $this->session->set_flashdata('title', 'WOW!!');
      $this->session->set_flashdata('message', '恭喜您，站内信已经发出。');
      redirect("alert/success");
    }
  }
  
  function delete_do($id){
    $this->MMail->hide($id, $this->session->userdata('uid'));
  }
}