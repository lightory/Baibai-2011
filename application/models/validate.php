<?php
// system/application/models/validate.php
class Validate extends CI_Model{
	var $CI;
	var $adminUids = array(1,2,3,10);
	
	function __construct(){
		$this->CI = & get_instance();
		$this->CI->load->model('MUser');
		$this->CI->load->model('MBook');
		
		if ( !$this->CI->session->userdata('uid') && get_cookie('email') && get_cookie('password') ){
			if ( $user = $this->CI->MUser->loginValid(get_cookie('email'), get_cookie('password') ) ){
				$this->CI->session->set_userdata('uid',$user->uid);
				$this->CI->session->set_userdata('nickname',$user->nickname);
				$this->CI->session->set_userdata('email',$user->email);
				$this->CI->session->set_userdata('password',$user->password);
				
				$this->CI->load->model('MUserLogin');
				$this->MUserLogin->insert($user->uid);
			}
		}
		
   	parent::__construct();
	}
	
	// 验证未登录
	function isNotLogin(){
		if ($this->CI->session->userdata('uid')) {
			$this->CI->session->set_flashdata('error', '您已经登录');
			redirect('home/');
		}
	}
	
	// 验证已登录
	function isLogin($type = 'normal'){
		switch($type){
			case 'normal':
				if (!$this->CI->session->userdata('uid')) {
					$this->CI->session->set_flashdata('error', '请先登录再进行操作');
					$redirectAfterLogin = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
					$this->CI->session->set_userdata('redirectAfterLogin', $redirectAfterLogin);
					redirect('account/login/');
				}
				break;
			case 'fancybox':
				if (!$this->CI->session->userdata('uid')) {
					$this->CI->session->set_flashdata('title', 'OOPS...很抱歉');
					$this->CI->session->set_flashdata('message', "对不起，您需要先登录才能进行此项操作。");
					$this->CI->session->set_flashdata('redirectUrl', site_url('account/login/') );
					redirect("alert/failure");
					//redirect('account/login/fancy/');
				}
				break;
		}
	}
	
	// 验证是管理员
	function isAdmin(){
		if ( !in_array($this->session->userdata('uid'), $this->adminUids) ) {
			$this->CI->session->set_flashdata('error', '您没有权限访问此页面');
			redirect('home/');
		}
	}
	
	// 验证不是求书者
	function isNotBookWanter($bookId){
		if ( $this->CI->MBook->isWanter($this->CI->session->userdata('uid'), $bookId) || $this->CI->MBook->isBorrower($this->CI->session->userdata('uid'), $bookId) ){
			$this->CI->session->set_flashdata('title', 'OOPS...很抱歉');
			$this->CI->session->set_flashdata('message', '对不起，您正在借这本书，无法执行此项操作。');
			redirect("alert/failure");
		}
	}
	
	// 验证不是持书者
	function isNotBookReader($bookId){
		if ( $this->CI->MBook->isReader($this->CI->session->userdata('uid'), $bookId) ){
			$this->CI->session->set_flashdata('title', 'OOPS...很抱歉');
			$this->CI->session->set_flashdata('message', '对不起，您已经拥有此书，无法执行此项操作。');
			redirect("alert/failure");
		}
	}
}