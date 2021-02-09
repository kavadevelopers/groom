<?php
class Categories extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function main()
	{
		$this->rights->redirect([2]);
		$data['_title']		= "Categories";
		$data['list']	= $this->db->get_where('categories',['df' => ''])->result_array();
		$data['_e']		= "0";
		$this->load->theme('categories/categories',$data);
	}

	public function edit_category($id)
	{
		$data['_title']		= "Categories";
		$data['list']	= $this->db->get_where('categories',['df' => ''])->result_array();
		$data['single']	= $this->db->get_where('categories',['id' => $id])->row_array();
		$data['_e']		= "1";
		$this->load->theme('categories/categories',$data);
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

		$data = [
			'name'		=> $this->input->post('name'),
			'image'		=> $file_name
		];
		$this->db->insert('categories',$data);

		$this->session->set_flashdata('msg', 'Category Added');
		redirect(base_url('categories/main'));
	}

	public function update()
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
	    		$old = $this->db->get_where('categories',['id' => $this->input->post('id')])->row_array();
	    		if(file_exists(FCPATH.'/uploads/category/'.$old['image'])){
	    			@unlink(FCPATH.'/uploads/category/'.$old['image']);
	    		}
	    		$data = [
					'image'		=> $file_name
				];
				$this->db->where('id',$this->input->post('id'))->update('categories',$data);
	    	}else{
	    		$file_name = "";
	    	}
		}
		$data = [
			'name'		=> $this->input->post('name')
		];
		$this->db->where('id',$this->input->post('id'))->update('categories',$data);

		$this->session->set_flashdata('msg', 'Category Updated');
		redirect(base_url('categories/main'));
	}

	public function delete_category($id)
	{
		$this->db->where('id',$id)->update('categories',['df' => 'yes']);
		$this->session->set_flashdata('msg', 'Category Deleted');
		redirect(base_url('categories/main'));
	}

	public function status_category($id,$status = false)
	{	
		$st = "";
		if($status){
			$st = "yes";
		}
		$this->db->where('id',$id)->update('categories',['block' => $st]);
		$this->session->set_flashdata('msg', 'Category Status Changed');
		redirect(base_url('categories/main'));
	}
}