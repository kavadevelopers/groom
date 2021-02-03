<?php
class Areas extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->auth->check_session();
	}

	public function index()
	{
		$data['_title']		= "Areas";
		$data['list']		= $this->db->order_by('id','desc')->get('areas')->result_array();
		$this->load->theme('areas/index',$data);
	}

	public function add()
	{
		$data['_title']				= "Add Area";
		$data['service_partners']	= $this->db->get_where('z_service',['df' => '','block' => '','verified' => 'Verified'])->result_array();
		$this->load->theme('areas/add',$data);
	}

	public function edit($id)
	{
		$data['_title']				= "Edit Area";
		$data['service_partners']	= $this->db->get_where('z_service',['df' => '','block' => '','verified' => 'Verified'])->result_array();
		$data['single']				= $this->db->get_where('areas',['id' => $id])->row_array();
		$this->load->theme('areas/edit',$data);
	}

	public function save()
	{
		$data = [
			'name'			=> $this->input->post('name'),
			'services'		=> implode(',', $this->input->post('service')),
			'latlon'		=> rtrim($this->input->post('latlon'),'-')
		];
		$this->db->insert('areas',$data);
		$this->session->set_flashdata('msg', 'Area Added');
		redirect(base_url('areas'));
	}

	public function update()
	{
		$data = [
			'name'			=> $this->input->post('name'),
			'services'		=> implode(',', $this->input->post('service')),
			'latlon'		=> rtrim($this->input->post('latlon'),'-')
		];
		$this->db->where('id',$this->input->post('id'))->update('areas',$data);
		$this->session->set_flashdata('msg', 'Area Updated');
		redirect(base_url('areas'));
	}

	public function delete($id)
	{
		$this->db->where('id',$id)->delete('areas');
		$this->session->set_flashdata('msg', 'Area Deleted');
		redirect(base_url('areas'));	
	}
}