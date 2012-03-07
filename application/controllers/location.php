<?php

class Location extends CI_Controller {
	function __construct(){
		parent::__construct();
		if($this->session->userdata('uid')==1){
			$this->output->enable_profiler(TRUE);
		}
	}
	
	function index(){
		redirect('book/');
	}
	
	function view($locationUrl, $offset=0){
		$this->load->model('MLocation');
		$this->load->model('MLocationBook');
		$this->load->model('MUser');
		$this->load->model('MAddress');
		$this->load->model('MGroup');
		$this->load->model('MGroupTopic');
		$this->load->helper('gravatar');
		$this->load->library('pagination');
		
		if($location = $this->MLocation->getByUrl($locationUrl)){
			$cityName = $location->name;
		}else{
			redirect('book/');
		}
    
		$headerData['styles'] = array('group.css');
		$headerData['title'] = "{$cityName} | 摆摆书架";
		$headerData['current'] = 'lib';
		$data['location'] = $location;
		$data['lastestAvailableBooks'] = $this->MLocationBook->getAvailableBooksByCity($cityName, 'desc', '20', $offset);
		$data['users'] = $this->MUser->getDonateListByCity($cityName);
		
		// get group topics
		$group = $this->MGroup->getByUrl($location->url);
		$data['topics'] = $this->MGroup->getLatestTopics($group->id, 6);
		
		// slideBooks
		$slideBooks['title'] = '随机推荐';
		$slideBooks['books'] = $this->MLocationBook->getAvailableBooksByCity($cityName, 'rand', '20');
		$data['slideBooks'] = $slideBooks;
    
		$config['total_rows'] = $this->MLocationBook->getAvailableBooksCountByCity($cityName);
		$config['full_tag_open'] = '<p class="pages">';
		$config['full_tag_close'] = '</p>';
		$config['uri_segment'] = 3;
		$config['per_page'] = '20'; 
		$config['num_links'] = 4;
		$config['base_url'] = site_url("location/{$locationUrl}/");
		$this->pagination->initialize($config);

		$this->load->view('header', $headerData);
		$this->load->view('location/index', $data);
		$this->Common->footer();
	}
}