<?php
class Dashboard extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->auth->check_session();
	}

	public function index()
	{
		$data['_title']		= "Dashboard";
		$this->load->theme('dashboard',$data);
	}
}
?>