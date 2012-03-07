<?php
class Misc extends CI_Controller {
	function __construct(){
		parent::__construct();
	}
  
	function index(){
		echo 'misc index';
	}

	function e404(){
		set_status_header('404');
		
		$headerData['title'] = '404 Not Found';
		$headerData['styles'] = array('page_404.css');
		
		$CI = & get_instance();
		$CI->load->view('header', $headerData);
		$CI->load->view('page/404');
    	$CI->Common->footer();
	}
}