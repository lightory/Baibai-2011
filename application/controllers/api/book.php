<?php

class Book extends CI_Controller {
	function __construct(){
		parent::__construct();
	}
	
	function index(){
		echo 'index';
	}
	
	// 获取书籍库存量
	function getStocks(){
		$this->load->model('MBook');
		$this->load->model('MUser');
		$this->load->helper('gravatar');
		
		// load Post Data
		$type = $this->input->post('type');
		$keyword = $this->input->post('keyword');
		
		switch($type){
			case 'doubanId':
				$readers = $this->MBook->getReadersFromDoubanId($keyword);
				break;
		}
		
		$temp = array();
		foreach($readers as $reader){
			$reader->url = $this->MUser->getUrl($reader->uid);
			$reader->avatar = getGravatar($reader->email, 48);
			$reader->username = $reader->nickname;
			$reader->email = '';
			$temp[] = $reader;
		}
		$readers = $temp;
		
		header("Access-Control-Allow-Origin: *");
		echo json_encode($readers);
	}
}