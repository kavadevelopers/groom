<?php
class Service_provider extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->auth->check_session();
	}

	public function new()
	{
		$this->rights->redirect([3]);
		$data['_title']		= "Service Provider - New";		
		$data['list']		= $this->db->get_where('service_provider',['df' => '','verified' => '1','approved' => '0'])->result_array();
		$this->load->theme('service_provider/new',$data);	
	}

	public function approved()
	{
		$this->rights->redirect([3]);
		$data['_title']		= "Service Provider - Approved";		
		$data['list']		= $this->db->get_where('service_provider',['df' => '','approved' => '1'])->result_array();
		$this->load->theme('service_provider/approved',$data);	
	}

	public function rejected()
	{
		$this->rights->redirect([3]);
		$data['_title']		= "Service Provider - Rejected";		
		$data['list']		= $this->db->get_where('service_provider',['df' => '','verified' => '1','approved' => '2'])->result_array();
		$this->load->theme('service_provider/rejected',$data);	
	}	

	public function approve($id,$type)
	{
		$this->rights->redirect([3]);
		$this->db->where('id',$id)->update('service_provider',['approved' => '1']);
		$this->session->set_flashdata('msg', 'Service Provider Rejected');
	    redirect(base_url('service_provider/'.$type));
	}

	public function reject($id,$type)
	{
		$this->rights->redirect([3]);
		$this->db->where('id',$id)->update('service_provider',['approved' => '2']);
		$this->session->set_flashdata('msg', 'Service Provider Rejected');
	    redirect(base_url('service_provider/'.$type));
	}

	public function block($id,$status = false)
	{
		$this->rights->redirect([3]);
		$s = "";
		if($status){ $s = "yes"; }
		$this->db->where('id',$id)->update('service_provider',['block' => $s]);
		$this->session->set_flashdata('msg', 'Status Changed');
	    redirect(base_url('service_provider/approved'));
	}
}