<?php
class MY_Email extends CI_Email {
    function __construct()
    {
        parent::__construct();
    }
	
	function baibaiSend($to, $subject, $message, $from='no-reply@bookfor.us'){
		$site_url = site_url();
		$message .= "<p>摆摆书架 <a href=\"$site_url\">$site_url</a></p>";
	
		$config['mailtype'] ='html';
		$config['useragent'] = 'bookfor.us';
		$config['newline'] = "\r\n";
		$config['crlf'] = "\r\n";
		$config['charset'] = "utf-8";
		
		/* # Use Gmail
		$config['protocol'] = 'smtp';
		$config['smtp_host'] = 'ssl://smtp.gmail.com';
		$config['smtp_user'] = 'baibaibook@gmail.com';
		$config['smtp_pass'] = 'baibaibook!';
		$config['smtp_port'] = '465';
		$config['smtp_timeout'] = '5';
	  */
		
		# Use Sendmail
		$config['protocol'] = 'sendmail';
		$config['mailpath'] = '/usr/sbin/sendmail';
		
		$this->initialize($config);
		
		if (ENVIRONMENT == 'Development') {
			$to = 'lightory@gmail.com';
			$subject = "本地测试邮件：{$subject}";
		}
	
		$this->from($from, '摆摆书架');
		$this->to($to); 
		$this->subject($subject);
		$this->message($message); 
		$this->send();
	}
}