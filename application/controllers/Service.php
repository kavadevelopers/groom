<?php
class Service extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->auth->check_session();
	}

	public function new()
	{
		$data['_title']		= "Service - New";
		$data['list']		= $this->db->get_where('z_service',['df' => '','approved' => '0'])->result_array();
		$this->load->theme('users/service/new',$data);	
	}

	public function add()
	{
		$data['_title']		= "Service - Add";
		$this->load->theme('users/service/add',$data);	
	}

	public function rejected()
	{
		$data['_title']		= "Service - Rejected";
		$data['list']		= $this->db->get_where('z_service',['df' => '','approved' => '2'])->result_array();
		$this->load->theme('users/service/rejected',$data);	
	}

	public function approved()
	{
		$data['_title']		= "Service - Approved";
		$data['list']		= $this->db->get_where('z_service',['df' => '','approved' => '1'])->result_array();
		$this->load->theme('users/service/approved',$data);	
	}

	public function online()
	{
		$data['_title']		= "Service - Online";
		$data['list']		= $this->db->get_where('z_service',["verified" => 'Verified','approved' => '1','block' => '','active' => '1','token !=' => '','df' => ''])->result_array();
		$this->load->theme('users/service/online',$data);	
	}

	public function offline()
	{
		$data['_title']		= "Service - Offline";
		$data['list']		= $this->db->get_where('z_service',['block' => '','active' => '0','df' => ''])->result_array();
		$this->load->theme('users/service/online',$data);	
	}

	public function edit($id)
	{
		$data['_title']		= "Edit Service";
		$data['user']		= get_service($id);
		$this->load->theme('users/service/edit',$data);		
	}

	public function delete($id)
	{
		$this->db->where('id',$id)->update('z_service',['df' => 'deleted']);
		$this->session->set_flashdata('msg', 'Service User Deleted');
		redirect(base_url('service/new'));
	}

	public function approve($id)
	{
		$this->db->where('id',$id)->update('z_service',['approved' => '1']);
		$this->session->set_flashdata('msg', 'Service User Approved');
		redirect(base_url('service/new'));	
	}

	public function reject($id)
	{
		$this->db->where('id',$id)->update('z_service',['approved' => '2']);
		$this->session->set_flashdata('msg', 'Service User Rejected');
		redirect(base_url('service/new'));	
	}

	public function rapprove($id)
	{
		$this->db->where('id',$id)->update('z_service',['approved' => '1']);
		$this->session->set_flashdata('msg', 'Service User Approved');
		redirect(base_url('service/rejected'));	
	}

	public function rdelete($id)
	{
		$this->db->where('id',$id)->update('z_service',['df' => 'deleted']);
		$this->session->set_flashdata('msg', 'Service User Deleted');
		redirect(base_url('service/rejected'));
	}

	public function areject($id)
	{
		$this->db->where('id',$id)->update('z_service',['approved' => '2']);
		$this->session->set_flashdata('msg', 'Service User Rejected');
		redirect(base_url('service/approved'));	
	}

	public function adelete($id)
	{
		$this->db->where('id',$id)->update('z_service',['df' => 'deleted']);
		$this->session->set_flashdata('msg', 'Service User Deleted');
		redirect(base_url('service/approved'));
	}


	public function block($id,$flag = false)
	{
		$fg = "";
		if ($flag) {
			$fg = "yes";
		}
		$this->db->where('id',$id)->update('z_service',['block' => $fg]);
		$this->session->set_flashdata('msg', 'Service User Status Changed');
		redirect(base_url('service/approved'));
	}

	public function save()
	{
		$this->form_validation->set_error_delimiters('<div class="val-error">', '</div>');
		$this->form_validation->set_rules('fname', 'First Name','trim|required');
		$this->form_validation->set_rules('lname', 'Last Name','trim|required');
		$this->form_validation->set_rules('mobile', 'Mobile','trim|required|regex_match[/^[0-9]{10}$/]|min_length[10]|max_length[10]|callback_check_mobile');
		$this->form_validation->set_rules('gender', 'Gender','trim|required');
		$this->form_validation->set_rules('address', 'Address','trim|required');
		$this->form_validation->set_rules('category', 'Category','trim|required');
		$this->form_validation->set_rules('business', 'Business','trim|required');
		if ($this->form_validation->run() == FALSE)
		{
			$data['_title']		= "Service - Add";
			$this->load->theme('users/service/add',$data);	
		}
		else
		{ 
			$data = [
				'fname'			=> $this->input->post('fname'),
				'lname'			=> $this->input->post('lname'),
				'mobile'		=> $this->input->post('mobile'),
				'address'		=> $this->input->post('address'),
				'business'		=> $this->input->post('business'),
				'category'		=> $this->input->post('category'),
				'gender'		=> $this->input->post('gender'),
				'deviceid'		=> '',
				'token'			=> '',
				'df'			=> '',
				'block'			=> '',
				'approved'		=> '0',
				'registered_at'	=> date('Y-m-d H:i:s'),
				'otp'			=> "",
				'verified'		=> 'Verified'
			];
			$this->db->insert('z_service',$data);
			$this->session->set_flashdata('msg', 'Service Provider Added');
			redirect(base_url('service/new'));
		}
	}

	public function update()
	{
		$this->form_validation->set_error_delimiters('<div class="val-error">', '</div>');
		$this->form_validation->set_rules('fname', 'First Name','trim|required');
		$this->form_validation->set_rules('lname', 'Last Name','trim|required');
		$this->form_validation->set_rules('mobile', 'Mobile','trim|required|regex_match[/^[0-9]{10}$/]|min_length[10]|max_length[10]|callback_check_mobile_edit');
		$this->form_validation->set_rules('gender', 'Gender','trim|required');
		$this->form_validation->set_rules('address', 'Address','trim|required');
		$this->form_validation->set_rules('category', 'Category','trim|required');
		$this->form_validation->set_rules('business', 'Business','trim|required');
		if ($this->form_validation->run() == FALSE)
		{
			$data['_title']		= "Service - Edit";
			$data['user']		= get_service($this->input->post('id'));
			$this->load->theme('users/service/edit',$data);	
		}
		else
		{ 
			$data = [
				'fname'			=> $this->input->post('fname'),
				'lname'			=> $this->input->post('lname'),
				'mobile'		=> $this->input->post('mobile'),
				'address'		=> $this->input->post('address'),
				'business'		=> $this->input->post('business'),
				'category'		=> $this->input->post('category'),
				'gender'		=> $this->input->post('gender')
			];
			$this->db->where('id',$this->input->post('id'))->update('z_service',$data);
			$this->session->set_flashdata('msg', 'Service Provider Updated');
			redirect(base_url('service/approved'));
		}
	}

	public function check_mobile()
	{
		if($this->db->get_where('z_service',['mobile' => $this->input->post('mobile'),'df' => ''])->row_array()){
			$this->form_validation->set_message('check_mobile', 'Mobile Already Exists');	
        	return false;
		}else{
			return true;
		}
	}

	public function check_mobile_edit()
	{
		if($this->db->get_where('z_service',['mobile' => $this->input->post('mobile'),'id !=' => $this->input->post('id'),'df' => ''])->row_array()){
			$this->form_validation->set_message('check_mobile_edit', 'Mobile Already Exists');
        	return false;
		}else{
			return true;
		}
	}
}