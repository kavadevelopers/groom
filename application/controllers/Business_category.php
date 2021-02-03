<?php
class Business_category extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}


	public function index()
	{
		$data['_title']		= "Business Categories";
		$data['list']	= $this->db->get_where('business_categories',['df' => ''])->result_array();
		$data['_e']		= "0";
		$this->load->theme('business_categories',$data);
	}


	public function save()
	{
		$config['upload_path'] = './uploads/category/';
	    $config['allowed_types']	= '*';
	    $config['max_size']      = '0';
	    $config['overwrite']     = FALSE;
	    $file_name = "";
	    $this->load->library('upload', $config);
	    if (isset($_FILES ['image']) && $_FILES ['image']['error'] == 0) {
			$file_name = microtime(true).".".pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
			$config['file_name'] = $file_name;
	    	$this->upload->initialize($config);
	    	if($this->upload->do_upload('image')){
	    		
	    	}else{
	    		$file_name = "";
	    	}
		}

		$config['upload_path'] = './uploads/category/';
	    $config['allowed_types']	= '*';
	    $config['max_size']      = '0';
	    $config['overwrite']     = TRUE;
	    $pdfname = "";
	    $this->load->library('upload', $config);
	    if (isset($_FILES ['menu']) && $_FILES ['menu']['error'] == 0) {
			$pdfname = microtime(true).".".pathinfo($_FILES['menu']['name'], PATHINFO_EXTENSION);
			$config['file_name'] = $pdfname;
	    	$this->upload->initialize($config);
	    	if($this->upload->do_upload('menu')){
	    		
	    	}else{
	    		$pdfname = "";
	    	}
		}


		$data = [
			'name'		=> $this->input->post('name'),
			'type'		=> $this->input->post('type'),
			'cutoff'	=> $this->input->post('cutoff'),
			'btn'		=> $this->input->post('btn'),
			'disclaimer'	=> $this->input->post('disclaimer'),
			'start'		=> date('H:i:s',strtotime($this->input->post('from'))),
			'end'		=> date('H:i:s',strtotime($this->input->post('to'))),
			'image'		=> $file_name,
			'menu'		=> $pdfname
		];
		$this->db->insert('business_categories',$data);

		$this->session->set_flashdata('msg', 'Category Added');
		redirect(base_url('business_category'));
	}

	public function edit($id)
	{
		$data['_title']		= "Business Categories";
		$data['list']	= $this->db->get_where('business_categories',['df' => ''])->result_array();
		$data['single']	= $this->db->get_where('business_categories',['id' => $id])->row_array();
		$data['_e']		= "1";
		$this->load->theme('business_categories',$data);
	}

	public function status($id,$status)
	{
		$sta = "";
		if($status == "dis"){
			$sta = "yes";
		}
		$this->db->where('id',$id)->update('business_categories',['disable' => $sta]);
		$this->session->set_flashdata('msg', 'Status Changed');
		redirect(base_url('business_category'));
	}

	public function update()
	{
		$data = [
			'name'		=> $this->input->post('name'),
			'type'		=> $this->input->post('type'),
			'cutoff'	=> $this->input->post('cutoff'),
			'btn'		=> $this->input->post('btn'),
			'disclaimer'	=> $this->input->post('disclaimer'),
			'start'		=> date('H:i:s',strtotime($this->input->post('from'))),
			'end'		=> date('H:i:s',strtotime($this->input->post('to')))
		];
		$this->db->where('id',$this->input->post('id'))->update('business_categories',$data);

		$config['upload_path'] = './uploads/category/';
	    $config['allowed_types']	= '*';
	    $config['max_size']      = '0';
	    $config['overwrite']     = FALSE;
	    $this->load->library('upload', $config);
	    if (isset($_FILES ['image']) && $_FILES ['image']['error'] == 0) {
			$file_name = microtime(true).".".pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
			$config['file_name'] = $file_name;
	    	$this->upload->initialize($config);
	    	if($this->upload->do_upload('image')){
	    		$old = $this->db->get_where('business_categories',['id' => $this->input->post('id')])->row_array();
	    		if($old['image'] != "" && file_exists(FCPATH.'uploads/category/'.$old['image'])){
	    			@unlink(FCPATH.'/uploads/category/'.$old['image']);
	    		}
	    		$this->db->where('id',$this->input->post('id'))->update('business_categories',['image' => $file_name]);
	    	}
		}

		$config['upload_path'] = './uploads/category/';
	    $config['allowed_types']	= '*';
	    $config['max_size']      = '0';
	    $config['overwrite']     = TRUE;
	    $pdfname = "";
	    $this->load->library('upload', $config);
	    if (isset($_FILES ['menu']) && $_FILES ['menu']['error'] == 0) {
			$pdfname = microtime(true).".".pathinfo($_FILES['menu']['name'], PATHINFO_EXTENSION);
			$config['file_name'] = $pdfname;
	    	$this->upload->initialize($config);
	    	if($this->upload->do_upload('menu')){
	    		$this->db->where('id',$this->input->post('id'))->update('business_categories',['menu' => $pdfname]);
	    	}
		}

		$this->session->set_flashdata('msg', 'Category Updated');
		redirect(base_url('business_category'));
	}

	public function delete($id)
	{
		$this->db->where('id',$id)->update('business_categories',['df' => 'deleted']);
		$this->session->set_flashdata('msg', 'Category Deleted');
		redirect(base_url('business_category'));
	}
}