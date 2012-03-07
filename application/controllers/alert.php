<?php
class Alert extends CI_Controller {
	function __construct(){
		parent::__construct();
	}
	
	function index(){
		$this->success();
	}
	
	function success(){
		$this->load->view('alert/success');
	}
	
	function failure(){
		$this->load->view('alert/failure');
	}
}