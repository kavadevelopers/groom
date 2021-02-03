<?php
class Ordersupport extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$data['_title']		= "Order Support";
		$data['list']		= $this->db->get('order_support')->result_array();
		$this->load->theme('ordersupport/index',$data);
	}

	public function delete($id)
	{
		$this->db->where('id',$id)->delete('order_support');
		$this->session->set_flashdata('msg', 'Customer Support Deleted');
		redirect(base_url('ordersupport'));
	}
}