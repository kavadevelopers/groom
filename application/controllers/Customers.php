<?php
class Customers extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->auth->check_session();
	}

	public function index()
	{
		$data['_title']		= "Customers";
		$data['list']		= $this->db->get_where('z_customer',['df' => ''])->result_array();
		$this->load->theme('users/customers/index',$data);	
	}

	public function add()
	{
		$data['_title']		= "Add Customer";
		$this->load->theme('users/customers/add',$data);		
	}

	public function edit($id)
	{
		$data['_title']		= "Edit Customer";
		$data['user']		= get_customer($id);
		$this->load->theme('users/customers/edit',$data);		
	}

	public function save()
	{
		$this->form_validation->set_error_delimiters('<div class="val-error">', '</div>');
		$this->form_validation->set_rules('fname', 'First Name','trim|required');
		$this->form_validation->set_rules('lname', 'Last Name','trim|required');
		$this->form_validation->set_rules('mobile', 'Mobile','trim|required|regex_match[/^[0-9]{10}$/]|min_length[10]|max_length[10]|callback_check_mobile');
		$this->form_validation->set_rules('password', 'Password','trim|required');
		$this->form_validation->set_rules('gender', 'Gender','trim|required');
		if ($this->form_validation->run() == FALSE)
		{
			$data['_title']		= "Add Customer";
			$this->load->theme('users/customers/add',$data);	
		}
		else
		{ 
			if($this->input->post('gender') == "Male"){
				$image = "male.png";
			}else{
				$image = "female.png";
			}
			$data = [
				'fname'			=> $this->input->post('fname'),
				'lname'			=> $this->input->post('lname'),
				'mobile'		=> $this->input->post('mobile'),
				'password'		=> md5($this->input->post('password')),
				'gender'		=> $this->input->post('gender'),
				'image'			=> $image,
				'deviceid'		=> '',
				'token'			=> '',
				'df'			=> '',
				'block'			=> '',
				'verified'		=> 'Verified',
				'registered_at'	=> date('Y-m-d H:i:s'),
				'sub_expired_on'=> getTommorrow(),
				'otp'			=> ""
			];
			$this->db->insert('z_customer',$data);
			$this->session->set_flashdata('msg', 'Customer Added');
			redirect(base_url('customers'));
		}
	}

	public function update()
	{
		$this->form_validation->set_error_delimiters('<div class="val-error">', '</div>');
		$this->form_validation->set_rules('fname', 'First Name','trim|required');
		$this->form_validation->set_rules('lname', 'Last Name','trim|required');
		$this->form_validation->set_rules('mobile', 'Mobile','trim|required|regex_match[/^[0-9]{10}$/]|min_length[10]|max_length[10]|callback_check_mobile_edit');
		$this->form_validation->set_rules('password', 'Password','trim');
		$this->form_validation->set_rules('gender', 'Gender','trim|required');
		if ($this->form_validation->run() == FALSE)
		{
			$data['_title']		= "Edit Customer";
			$data['user']		= get_customer($this->input->post('id'));
			$this->load->theme('users/customers/edit',$data);	
		}
		else
		{ 
			if($this->input->post('gender') == "Male"){
				$image = "male.png";
			}else{
				$image = "female.png";
			}
			$data = [
				'fname'			=> $this->input->post('fname'),
				'lname'			=> $this->input->post('lname'),
				'mobile'		=> $this->input->post('mobile'),
				'gender'		=> $this->input->post('gender')
			];

			if($this->input->post('password') != ""){
				$this->db->where('id',$this->input->post('id'))->update('z_customer',['password'		=> md5($this->input->post('password'))]);				
			}

			$this->db->where('id',$this->input->post('id'))->update('z_customer',$data);
			$this->session->set_flashdata('msg', 'Customer Updated');
			redirect(base_url('customers'));
		}
	}

	public function delete($id)
	{
		$this->db->where('id',$id)->update('z_customer',['df' => 'deleted']);
		$this->session->set_flashdata('msg', 'Customer Deleted');
		redirect(base_url('customers'));
	}

	public function block($id,$flag = false)
	{
		$fg = "";
		if ($flag) {
			$fg = "yes";
		}
		$this->db->where('id',$id)->update('z_customer',['block' => $fg]);
		$this->session->set_flashdata('msg', 'Customer Status Changed');
		redirect(base_url('customers'));
	}

	public function paid($id,$flag = false)
	{
		$fg = "";
		if ($flag) {
			$fg = "yes";
		}
		$this->db->where('id',$id)->update('z_customer',['free' => $fg]);
		$this->session->set_flashdata('msg', 'Customer Status Changed');
		redirect(base_url('customers'));
	}

	public function check_mobile()
	{
		if($this->db->get_where('z_customer',['mobile' => $this->input->post('mobile'),'df' => ''])->row_array()){
			$this->form_validation->set_message('check_mobile', 'Mobile Already Exists');
        	return false;
		}else{
			return true;
		}
	}

	public function check_mobile_edit()
	{
		if($this->db->get_where('z_customer',['mobile' => $this->input->post('mobile'),'id !=' => $this->input->post('id'),'df' => ''])->row_array()){
			$this->form_validation->set_message('check_mobile_edit', 'Mobile Already Exists');
        	return false;
		}else{
			return true;
		}
	}
}