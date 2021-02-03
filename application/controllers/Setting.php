<?php
class Setting extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->auth->check_session();
	}

	public function index()
	{
		$data['_title']		= "Settings";
		$this->load->theme('setting/index',$data);
	}

	public function save()
	{
		$this->form_validation->set_error_delimiters('<div class="val-error">', '</div>');
		$this->form_validation->set_rules('company', 'Company Name','trim|required');
		$this->form_validation->set_rules('fserverkey', 'Firebase Server Key','trim|required');
		$this->form_validation->set_rules('razorpay_key', 'Razorpay Key','trim|required');
		$this->form_validation->set_rules('twofecturekey', '2Factor API Key','trim|required');
		$this->form_validation->set_rules('support_email', 'Support Email','trim|required');
		$this->form_validation->set_rules('support_mobile', 'Support Mobile','trim|required');
		// $this->form_validation->set_rules('gmap_api', 'Google Map Api Key','trim|required');

		$this->form_validation->set_rules('admin_receive_email', 'Admin Email for Receive Order Details','trim|required');
		$this->form_validation->set_rules('mail_host', 'SMTP Host','trim|required');
		$this->form_validation->set_rules('mail_username', 'SMTP Username','trim|required');
		$this->form_validation->set_rules('mail_pass', 'SMTP Password','trim|required');
		$this->form_validation->set_rules('mail_port', 'SMTP Port','trim|required');

		$this->form_validation->set_rules('upi_id', 'Company UPI ID','trim|required');

		$this->form_validation->set_rules('cust_ver', 'Customer App Version','trim|required');
		$this->form_validation->set_rules('serv_ver', 'Service App Version','trim|required');
		$this->form_validation->set_rules('deli_ver', 'Delivery App Version','trim|required');
		$this->form_validation->set_rules('icust_ver', 'Customer App Version','trim|required');
		$this->form_validation->set_rules('iserv_ver', 'Service App Version','trim|required');
		$this->form_validation->set_rules('ideli_ver', 'Delivery App Version','trim|required');

		if ($this->form_validation->run() == FALSE)
		{
			$data['_title']	= 'Settings';
			$this->load->theme('setting/index',$data);
		}
		else
		{ 
			$data = [
				'name'						=> $this->input->post('company'),
				'fserverkey'				=> $this->input->post('fserverkey'),
				'razorpay_key'				=> $this->input->post('razorpay_key'),
				'twofecturekey'				=> $this->input->post('twofecturekey'),
				'support_email'				=> $this->input->post('support_email'),
				'support_mobile'			=> $this->input->post('support_mobile'),
				'admin_receive_email'		=> $this->input->post('admin_receive_email'),
				'gmap_api'					=> $this->input->post('gmap_api'),
				'mail_host'					=> $this->input->post('mail_host'),
				'mail_username'				=> $this->input->post('mail_username'),
				'mail_pass'					=> $this->input->post('mail_pass'),
				'mail_port'					=> $this->input->post('mail_port'),
				'upi_id'					=> $this->input->post('upi_id'),
				'cust_ver'					=> $this->input->post('cust_ver'),
				'serv_ver'					=> $this->input->post('serv_ver'),
				'deli_ver'					=> $this->input->post('deli_ver'),
				'icust_ver'					=> $this->input->post('icust_ver'),
				'iserv_ver'					=> $this->input->post('iserv_ver'),
				'ideli_ver'					=> $this->input->post('ideli_ver')
			];
			$this->db->where('id','1');
			$this->db->update('setting',$data);

			$config['upload_path'] = './uploads/';
		    $config['allowed_types']	= '*';
		    $config['max_size']      = '0';
		    $config['overwrite']     = FALSE;
		    $this->load->library('upload', $config);
		    if (isset($_FILES ['upi_qr']) && $_FILES ['upi_qr']['error'] == 0) {
				$file_name = microtime(true).".".pathinfo($_FILES['upi_qr']['name'], PATHINFO_EXTENSION);
				$config['file_name'] = $file_name;
		    	$this->upload->initialize($config);
		    	if($this->upload->do_upload('upi_qr')){
		    		$old = $this->db->get_where('setting',['id' => '1'])->row_array();
		    		if($old['upi_qr'] != "" && file_exists(FCPATH.'uploads/'.$old['upi_qr'])){
		    			@unlink(FCPATH.'/uploads/'.$old['upi_qr']);
		    		}
		    		$this->db->where('id','1')->update('setting',['upi_qr' => $file_name]);
		    	}
			}
			$this->session->set_flashdata('msg', 'Settings Saved');
	        redirect(base_url('setting'));
		}
	}
}
?>