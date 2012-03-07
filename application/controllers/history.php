<?php
class History extends CI_Controller {
	function __construct(){
		parent::__construct();
    $this->load->model('MUser');
		$this->load->model('MAddress');
		$this->load->model('MHistory');
    $this->load->model('MBook');
		$this->load->model('MStock');
    
    $this->Validate->isLogin();
  }
  
  function index(){
    $this->donate();
  }

	function donate(){
		$userId = $this->session->userdata('uid');
	
		$stocks = $this->MHistory->getDonateStocks($userId);
		
		$headerData['title'] = "我捐赠的书";
		$data['stocks'] = $stocks;
		
		$this->load->view('header', $headerData);
		$this->load->view('history/donate', $data);
		$this->Common->footer();
	}
  
  function borrow(){
		$userId = $this->session->userdata('uid');
	
		$stocks = $this->MHistory->getBorrowStocks($userId);
		
		$headerData['title'] = "我收到的书";
		$data['stocks'] = $stocks;
		
		$this->load->view('header', $headerData);
		$this->load->view('history/borrow', $data);
		$this->Common->footer();
	}
}