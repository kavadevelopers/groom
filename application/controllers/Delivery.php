<?php
class Delivery extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->auth->check_session();
	}

	public function new()
	{
		$data['_title']		= "Delivery - New";
		$data['list']		= $this->db->get_where('z_delivery',['df' => '','approved' => '0'])->result_array();
		$this->load->theme('users/delivery/new',$data);	
	}

	public function add()
	{
		$data['_title']		= "Delivery - Add";
		$this->load->theme('users/delivery/add',$data);	
	}

	public function rejected()
	{
		$data['_title']		= "Delivery - Rejected";
		$data['list']		= $this->db->get_where('z_delivery',['df' => '','approved' => '2'])->result_array();
		$this->load->theme('users/delivery/rejected',$data);	
	}

	public function approved()
	{
		$data['_title']		= "Delivery - Approved";
		$data['list']		= $this->db->get_where('z_delivery',['df' => '','approved' => '1'])->result_array();
		$this->load->theme('users/delivery/approved',$data);	
	}

	public function online()
	{
		$data['_title']		= "Delivery - Online";
		$data['list']		= $this->db->get_where('z_delivery',["verified" => 'Verified','approved' => '1','block' => '','active' => '1','token !=' => '','df' => ''])->result_array();
		$this->load->theme('users/delivery/online',$data);	
	}

	public function offline()
	{
		$data['_title']		= "Delivery - Offline";
		$data['list']		= $this->db->get_where('z_delivery',['block' => '','active' => '0','df' => ''])->result_array();
		$this->load->theme('users/delivery/online',$data);	
	}

	public function edit($id)
	{
		$data['_title']		= "Edit Delivery";
		$data['user']		= get_delivery($id);
		$this->load->theme('users/delivery/edit',$data);		
	}

	public function delete($id)
	{
		$this->db->where('id',$id)->update('z_delivery',['df' => 'deleted']);
		$this->session->set_flashdata('msg', 'Delivery User Deleted');
		redirect(base_url('delivery/new'));
	}

	public function approve($id)
	{
		$this->db->where('id',$id)->update('z_delivery',['approved' => '1']);
		$this->session->set_flashdata('msg', 'Delivery User Approved');
		redirect(base_url('delivery/new'));	
	}

	public function reject($id)
	{
		$this->db->where('id',$id)->update('z_delivery',['approved' => '2']);
		$this->session->set_flashdata('msg', 'Delivery User Rejected');
		redirect(base_url('delivery/new'));	
	}

	public function rapprove($id)
	{
		$this->db->where('id',$id)->update('z_delivery',['approved' => '1']);
		$this->session->set_flashdata('msg', 'Delivery User Approved');
		redirect(base_url('delivery/rejected'));	
	}

	public function rdelete($id)
	{
		$this->db->where('id',$id)->update('z_delivery',['df' => 'deleted']);
		$this->session->set_flashdata('msg', 'Delivery User Deleted');
		redirect(base_url('delivery/rejected'));
	}

	public function areject($id)
	{
		$this->db->where('id',$id)->update('z_delivery',['approved' => '2']);
		$this->session->set_flashdata('msg', 'Delivery User Rejected');
		redirect(base_url('delivery/approved'));	
	}

	public function adelete($id)
	{
		$this->db->where('id',$id)->update('z_delivery',['df' => 'deleted']);
		$this->session->set_flashdata('msg', 'Delivery User Deleted');
		redirect(base_url('delivery/approved'));
	}


	public function block($id,$flag = false)
	{
		$fg = "";
		if ($flag) {
			$fg = "yes";
		}
		$this->db->where('id',$id)->update('z_delivery',['block' => $fg]);
		$this->session->set_flashdata('msg', 'Delivery User Status Changed');
		redirect(base_url('delivery/approved'));
	}

	public function save()
	{
		$this->form_validation->set_error_delimiters('<div class="val-error">', '</div>');
		$this->form_validation->set_rules('fname', 'First Name','trim|required');
		$this->form_validation->set_rules('lname', 'Last Name','trim|required');
		$this->form_validation->set_rules('mobile', 'Mobile','trim|required|regex_match[/^[0-9]{10}$/]|min_length[10]|max_length[10]|callback_check_mobile');
		if ($this->form_validation->run() == FALSE)
		{
			$data['_title']		= "Delivery - Add";
			$this->load->theme('users/delivery/add',$data);	
		}
		else
		{ 
			$data = [
				'fname'			=> $this->input->post('fname'),
				'lname'			=> $this->input->post('lname'),
				'mobile'		=> $this->input->post('mobile'),
				'deviceid'		=> '',
				'token'			=> '',
				'df'			=> '',
				'block'			=> '',
				'approved'		=> '0',
				'registered_at'	=> date('Y-m-d H:i:s'),
				'otp'			=> '',
				'verified'		=> 'Verified'
			];
			$this->db->insert('z_delivery',$data);
			$this->session->set_flashdata('msg', 'Delivery Added');
			redirect(base_url('delivery/new'));
		}
	}

	public function update()
	{
		$this->form_validation->set_error_delimiters('<div class="val-error">', '</div>');
		$this->form_validation->set_rules('fname', 'First Name','trim|required');
		$this->form_validation->set_rules('lname', 'Last Name','trim|required');
		$this->form_validation->set_rules('mobile', 'Mobile','trim|required|regex_match[/^[0-9]{10}$/]|min_length[10]|max_length[10]|callback_check_mobile_edit');
		if ($this->form_validation->run() == FALSE)
		{
			$data['_title']		= "Delivery - Edit";
			$data['user']		= get_delivery($this->input->post('id'));
			$this->load->theme('users/delivery/edit',$data);	
		}
		else
		{ 
			$data = [
				'fname'			=> $this->input->post('fname'),
				'lname'			=> $this->input->post('lname'),
				'mobile'		=> $this->input->post('mobile')
			];
			$this->db->where('id',$this->input->post('id'))->update('z_delivery',$data);
			$this->session->set_flashdata('msg', 'Delivery Updated');
			redirect(base_url('delivery/approved'));
		}
	}

	public function check_mobile()
	{
		if($this->db->get_where('z_delivery',['mobile' => $this->input->post('mobile'),'df' => ''])->row_array()){
			$this->form_validation->set_message('check_mobile', 'Mobile Already Exists');	
        	return false;
		}else{
			return true;
		}
	}

	public function check_mobile_edit()
	{
		if($this->db->get_where('z_delivery',['mobile' => $this->input->post('mobile'),'id !=' => $this->input->post('id'),'df' => ''])->row_array()){
			$this->form_validation->set_message('check_mobile_edit', 'Mobile Already Exists');
        	return false;
		}else{
			return true;
		}
	}
}