<?php
class Servicecms extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->auth->check_session();
	}

	public function terms()
	{
		$data['_title']		= "Service App - Terms and Conditions";
		$data['content']	= $this->db->get_where('pages',['id' => '8'])->row_array()['content'];
		$this->load->theme('cms/service/terms',$data);
	}

	public function terms_save()
	{
		$data = [
			'content' => $this->input->post('content')
		];
		$this->db->where('id','8')->update('pages',$data);

		$this->session->set_flashdata('msg', 'Page Updated');
		redirect(base_url('servicecms/terms'));
	}

	public function privacy()
	{
		$data['_title']		= "Service App - Privacy Policy";
		$data['content']	= $this->db->get_where('pages',['id' => '9'])->row_array()['content'];
		$this->load->theme('cms/service/privacy',$data);
	}

	public function privacy_save()
	{
		$data = [
			'content' => $this->input->post('content')
		];
		$this->db->where('id','9')->update('pages',$data);

		$this->session->set_flashdata('msg', 'Page Updated');
		redirect(base_url('servicecms/privacy'));
	}

	public function about()
	{
		$data['_title']		= "Service App - About App";
		$data['content']	= $this->db->get_where('pages',['id' => '10'])->row_array()['content'];
		$this->load->theme('cms/service/about',$data);
	}

	public function about_save()
	{
		$data = [
			'content' => $this->input->post('content')
		];
		$this->db->where('id','10')->update('pages',$data);

		$this->session->set_flashdata('msg', 'Page Updated');
		redirect(base_url('servicecms/about'));
	}

	public function faq()
	{
		$data['_title']		= "Service App - FAQ's";
		$data['list']	= $this->db->get_where('faq_service')->result_array();
		$data['_e']		= "0";
		$this->load->theme('cms/service/faq',$data);
	}

	public function save_faq()
	{
		$data = [
			'que'	=> $this->input->post('que'),
			'ans'	=> $this->input->post('ans'),
		];
		$this->db->insert('faq_service',$data);

		$this->session->set_flashdata('msg', 'FAQ Added');
		redirect(base_url('servicecms/faq'));
	}

	public function edit_faq($id)
	{
		$data['_title']		= "Service App - FAQ's";
		$data['list']	= $this->db->get_where('faq_service')->result_array();
		$data['faq']	= $this->db->get_where('faq_service',['id' => $id])->row_array();
		$data['_e']		= "1";
		$this->load->theme('cms/service/faq',$data);
	}

	public function update_faq()
	{
		$data = [
			'que'	=> $this->input->post('que'),
			'ans'	=> $this->input->post('ans'),
		];
		$this->db->where('id',$this->input->post('id'))->update('faq_service',$data);

		$this->session->set_flashdata('msg', 'FAQ Updated');
		redirect(base_url('servicecms/faq'));
	}

	public function delete_faq($id)
	{
		$this->db->where('id',$id)->delete('faq_service');
		$this->session->set_flashdata('msg', 'FAQ Deleted');
		redirect(base_url('servicecms/faq'));
	}
}
?>