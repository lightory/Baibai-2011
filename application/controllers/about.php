<?php
class About extends CI_Controller {
	function __construct(){
		parent::__construct();
	}
	
	function index(){
		$this->faq();
	}
	
	function faq(){
		$headerData['title'] = '常见问题';
		
		$this->load->view('header', $headerData);
		$this->load->view('about/faq');
		$this->Common->footer();
	}
	
	function firstlogin(){
		$headerData['title'] = '常见问题';
		$data['firstlogin'] = true;
		
		$this->load->view('header', $headerData);
		$this->load->view('about/faq', $data);
		$this->Common->footer();
	}
}