<?php
class Weekly extends CI_Controller {
	function __construct(){
		parent::__construct();
	}
  
	function index(){
		echo 'weekly index';
	}

	function view($weeklyId){
		$this->load->model('MStock');
		$this->load->model('MAddress');
		$this->load->model('MWeekly');
		$this->load->model('MBook');
		
		$weekly = $this->MWeekly->getById($weeklyId);
		
		$books = array();
		$books[] = $this->MBook->getById($weekly->book1Id);
		$books[] = $this->MBook->getById($weekly->book2Id);
		$books[] = $this->MBook->getById($weekly->book3Id);
		$books[] = $this->MBook->getById($weekly->book4Id);
		$books[] = $this->MBook->getById($weekly->book5Id);
		$books[] = $this->MBook->getById($weekly->book6Id);
		$books[] = $this->MBook->getById($weekly->book7Id);
		$books[] = $this->MBook->getById($weekly->book8Id);
		$books[] = $this->MBook->getById($weekly->book9Id);
		$books[] = $this->MBook->getById($weekly->book10Id);
		
		$data['weekly'] = $weekly;
		$data['books'] = $books;
		$data['stockNumber'] = $this->MStock->getNumber();
		$data['cityNumber'] = $this->MAddress->getCitiesCount();
		
		$this->load->view('page/weekly', $data);
	}
}