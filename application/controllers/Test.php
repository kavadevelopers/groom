<?php
class Test extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->auth->check_session();
	}

	public function index()
	{
		print_r(checkSinglePoligon('18.548161','73.959882','3'));
	}
}