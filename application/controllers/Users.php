<?php
class Users extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->auth->check_session();
	}

	public function index()
	{
		$data['_title']		= "Users";		
		$data['list']		= $this->db->get_where('user',['df' => '','id !=' => '1'])->result_array();
		$this->load->theme('users/index',$data);	
	}

	public function add()
	{
		$data['_title']			= "Add User";	
		$data['rights_list']	= $this->db->order_by('module_name','asc')->get('user_rights')->result_array(); 	
		$this->load->theme('users/add',$data);		
	}

	public function delete($id)
	{
		$this->db->where('id',$id)->update('user',['df' => 'yes']);
		$this->session->set_flashdata('msg', 'User Deleted');
	    redirect(base_url('users'));
	}

	public function edit($id = false)
	{
		if($id){
			if(get_user_byid($id)){
				$data['_title']			= "Edit User";	
				$data['rights_list']	= $this->db->order_by('module_name','asc')->get('user_rights')->result_array(); 	
				$data['user']			= get_user_byid($id); 	
				$this->load->theme('users/edit',$data);					
			}else{
				redirect(base_url('users'));
			}
		}else{
			redirect(base_url('users'));	
		}
	}

	public function status($id,$type = false)
	{
		if($type){
			$this->db->where('id',$id)->update('user',['block' => 'yes']);
		}else{
			$this->db->where('id',$id)->update('user',['block' => '']);
		}
		$this->session->set_flashdata('msg', 'User status has been changed');
	    redirect(base_url('users'));
	}

	public function save()
	{
		$this->form_validation->set_error_delimiters('<div class="val-error">', '</div>');
		$this->form_validation->set_rules('name', 'Full Name', 'trim|required|min_length[5]|max_length[200]');
		$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[5]|callback_username_unique');
		$this->form_validation->set_rules('gender', 'Gender', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		$this->form_validation->set_rules('phone', 'Phone', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean|max_length[100]');
		if ($this->form_validation->run() == FALSE)
		{
			$data['_title']			= "Add User";	
			$data['rights_list']	= $this->db->order_by('module_name','asc')->get('user_rights')->result_array(); 	
			$this->load->theme('users/add',$data);		
		}
		else
		{ 
			$rights = '';
			foreach ($this->input->post('rights') as $key => $value) {
				$rights .= $value.',';
			}
			$rights = rtrim($rights,',');

			$data = [
				'user_type'			=> '1',
				'name'				=> $this->input->post('name'),
				'username'			=> $this->input->post('username'),
				'password'			=> md5($this->input->post('password')),
				'email'				=> $this->input->post('email'),
				'mobile'			=> $this->input->post('phone'),	
				'gender'			=> $this->input->post('gender'),	
				'rights'			=> $rights	
			];
			$this->db->insert('user',$data);

			$this->session->set_flashdata('msg', 'User Added.');
	        redirect(base_url('users'));
		}
	}

	public function update()
	{
		$this->form_validation->set_error_delimiters('<div class="val-error">', '</div>');
		$this->form_validation->set_rules('name', 'Full Name', 'trim|required|min_length[5]|max_length[200]');
		$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[5]|callback_username_unique');
		$this->form_validation->set_rules('gender', 'Gender', 'trim|required');
		$this->form_validation->set_rules('phone', 'Phone', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean|max_length[100]');
		if ($this->form_validation->run() == FALSE)
		{
			$data['_title']			= "Edit User";	
			$data['rights_list']	= $this->db->order_by('module_name','asc')->get('user_rights')->result_array(); 	
			$data['user']			= get_user_byid($this->input->post('id')); 	
			$this->load->theme('users/edit',$data);		
		}
		else
		{ 
			$rights = '';
			foreach ($this->input->post('rights') as $key => $value) {
				$rights .= $value.',';
			}
			$rights = rtrim($rights,',');

			$data = [
				'name'				=> $this->input->post('name'),
				'username'			=> $this->input->post('username'),
				'email'				=> $this->input->post('email'),
				'mobile'			=> $this->input->post('phone'),	
				'gender'			=> $this->input->post('gender'),	
				'rights'			=> $rights	
			];
			$this->db->where('id',$this->input->post('id'))->update('user',$data);

			if($this->input->post('password')){
				$data = [
					'password'			=> md5($this->input->post('password'))
				];
				$this->db->where('id',$this->input->post('id'))->update('user',$data);				
			}

			$this->session->set_flashdata('msg', 'User Updated.');
	        redirect(base_url('users'));
		}
	}

	public function username_unique(){
		if($this->input->post('id')){
			$user = $this->db->get_where('user',['username' => $this->input->post('username'),'id !=' => $this->input->post('id'),'df' => ''])->row_array();
		}else{			
			$user = $this->db->get_where('user',['username' => $this->input->post('username'),'df' => ''])->row_array();
		}
		if($user){
			$this->form_validation->set_message(__FUNCTION__ , 'Username Already Exists');
            return false;
		}
		else{
			return true;
		}
	}
}