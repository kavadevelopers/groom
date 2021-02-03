<?php
class Subscriptions extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->auth->check_session();
	}

	public function index()
	{
		$data['_title']		= "Subscriptions";
		$data['list']		= $this->db->order_by('id','desc')->limit(150)->get_where('extend_subscription')->result_array();
		$this->load->theme('subscriptions/index',$data);	
	}
}