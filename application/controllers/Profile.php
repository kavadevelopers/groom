<?php
class Profile extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->auth->check_session();
	}


	public function index()
	{
		$data['_title']		= "Edit Profile";		
		$this->load->theme('profile',$data);	
	}

	public function save()
	{
		$this->form_validation->set_error_delimiters('<div class="val-error">', '</div>');
		$this->form_validation->set_rules('name', 'Name','trim|required');
		$this->form_validation->set_rules('mobile', 'Mobile','trim|required');
		$this->form_validation->set_rules('email', 'Email','trim|required|valid_email|xss_clean');
		$this->form_validation->set_rules('gender', 'Gender','trim|required');
		$this->form_validation->set_rules('password', 'Password','trim|min_length[5]');

		if ($this->form_validation->run() == FALSE)
		{
			$data['_title']	= 'Edit Profile';
			$this->load->theme('profile',$data);	
		}
		else
		{ 
			$data = [
				'name'			=> $this->input->post('name'),
				'email'			=> $this->input->post('email'),
				'mobile'		=> $this->input->post('mobile'),
				'gender'		=> $this->input->post('gender')
			];

			$this->db->where('id',get_user()['id'])->update('user',$data);

			if(!empty($this->input->post('password'))){
				$data = [
					'password'	=> md5($this->input->post('password'))
				];
				$this->db->where('id',get_user()['id'])->update('user',$data);				
			}

			$this->session->set_flashdata('msg', 'Profile Updated');
	        redirect(base_url('profile'));
		}
	}
}
?>