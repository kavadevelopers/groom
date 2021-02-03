<?php
class Webcms extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->auth->check_session();
	}


	public function terms()
	{
		$data['_title']		= "Web - Terms and Conditions";
		$data['type']		= "terms";
		$data['content']	= $this->db->get_where('web',['id' => '1'])->row_array()['terms'];
		$this->load->theme('cms/web/page',$data);
	}

	public function privacy()
	{
		$data['_title']		= "Web - Privacy Policy";
		$data['type']		= "privacy";
		$data['content']	= $this->db->get_where('web',['id' => '1'])->row_array()['privacy'];
		$this->load->theme('cms/web/page',$data);
	}

	public function refund()
	{
		$data['_title']		= "Web - Refund and Cancellation";
		$data['type']		= "refund";
		$data['content']	= $this->db->get_where('web',['id' => '1'])->row_array()['refund'];
		$this->load->theme('cms/web/page',$data);
	}

	public function save()
	{
		if($this->input->post('type') == 'terms'){
			$data = [
				'terms' 	=> $this->input->post('content')
			];
			$this->db->where('id','1')->update('web',$data);
			$this->session->set_flashdata('msg', 'Page Updated');
			redirect(base_url('webcms/terms'));
		}

		if($this->input->post('type') == 'privacy'){
			$data = [
				'privacy' 	=> $this->input->post('content')
			];
			$this->db->where('id','1')->update('web',$data);
			$this->session->set_flashdata('msg', 'Page Updated');
			redirect(base_url('webcms/privacy'));
		}

		if($this->input->post('type') == 'refund'){
			$data = [
				'refund' 	=> $this->input->post('content')
			];
			$this->db->where('id','1')->update('web',$data);
			$this->session->set_flashdata('msg', 'Page Updated');
			redirect(base_url('webcms/refund'));
		}
	}
}