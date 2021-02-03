<?php
class Deliverycms extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->auth->check_session();
	}

	public function terms()
	{
		$data['_title']		= "Delivery App - Terms and Conditions";
		$data['content']	= $this->db->get_where('pages',['id' => '5'])->row_array()['content'];
		$this->load->theme('cms/delivery/terms',$data);
	}

	public function terms_save()
	{
		$data = [
			'content' => $this->input->post('content')
		];
		$this->db->where('id','5')->update('pages',$data);

		$this->session->set_flashdata('msg', 'Page Updated');
		redirect(base_url('deliverycms/terms'));
	}

	public function privacy()
	{
		$data['_title']		= "Delivery App - Privacy Policy";
		$data['content']	= $this->db->get_where('pages',['id' => '6'])->row_array()['content'];
		$this->load->theme('cms/delivery/privacy',$data);
	}

	public function privacy_save()
	{
		$data = [
			'content' => $this->input->post('content')
		];
		$this->db->where('id','6')->update('pages',$data);

		$this->session->set_flashdata('msg', 'Page Updated');
		redirect(base_url('deliverycms/privacy'));
	}

	public function about()
	{
		$data['_title']		= "Delivery App - About App";
		$data['content']	= $this->db->get_where('pages',['id' => '7'])->row_array()['content'];
		$this->load->theme('cms/delivery/about',$data);
	}

	public function about_save()
	{
		$data = [
			'content' => $this->input->post('content')
		];
		$this->db->where('id','7')->update('pages',$data);

		$this->session->set_flashdata('msg', 'Page Updated');
		redirect(base_url('deliverycms/about'));
	}

	public function faq()
	{
		$data['_title']		= "Delivery App - FAQ's";
		$data['list']	= $this->db->get_where('faq_delivery')->result_array();
		$data['_e']		= "0";
		$this->load->theme('cms/delivery/faq',$data);
	}

	public function save_faq()
	{
		$data = [
			'que'	=> $this->input->post('que'),
			'ans'	=> $this->input->post('ans'),
		];
		$this->db->insert('faq_delivery',$data);

		$this->session->set_flashdata('msg', 'FAQ Added');
		redirect(base_url('deliverycms/faq'));
	}

	public function edit_faq($id)
	{
		$data['_title']		= "Delivery App - FAQ's";
		$data['list']	= $this->db->get_where('faq_delivery')->result_array();
		$data['faq']	= $this->db->get_where('faq_delivery',['id' => $id])->row_array();
		$data['_e']		= "1";
		$this->load->theme('cms/delivery/faq',$data);
	}

	public function update_faq()
	{
		$data = [
			'que'	=> $this->input->post('que'),
			'ans'	=> $this->input->post('ans'),
		];
		$this->db->where('id',$this->input->post('id'))->update('faq_delivery',$data);

		$this->session->set_flashdata('msg', 'FAQ Updated');
		redirect(base_url('deliverycms/faq'));
	}

	public function delete_faq($id)
	{
		$this->db->where('id',$id)->delete('faq_delivery');
		$this->session->set_flashdata('msg', 'FAQ Deleted');
		redirect(base_url('deliverycms/faq'));
	}
}
?>