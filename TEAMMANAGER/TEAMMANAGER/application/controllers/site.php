<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	function index()
	{	
		$this->template->setData("SelectedTab", "Home");
		
		$this->template->set("default_public");
		
		$this->load->view('public/index');
	}	
	
}