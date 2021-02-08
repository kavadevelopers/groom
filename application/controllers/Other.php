<?php
class Other extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->auth->check_session();
	}


	public function send_app_notification()
	{
		$this->rights->redirect([1]);
		$data['_title']		= "Send Push Notifications To mobile devices";
		$this->load->theme('other/push_notification',$data);	
	}

	public function send_pushnotification()
	{
		$tokens = [];
		if($this->input->post('user_type') == "customer"){
			$users = $this->db->select('token')->where('df','')->where('token !=','')->get('z_customer')->result_array();
			foreach ($users as $key => $value) {
				array_push($tokens, $value['token']);
			}
		}

		if($this->input->post('user_type') == "service"){
			$users = $this->db->select('token')->where('df','')->where('token !=','')->get('z_service')->result_array();
			foreach ($users as $key => $value) {
				array_push($tokens, $value['token']);
			}
		}

		if($this->input->post('user_type') == "delivery"){
			$users = $this->db->select('token')->where('df','')->where('token !=','')->get('z_delivery')->result_array();
			foreach ($users as $key => $value) {
				array_push($tokens, $value['token']);
			}
		}

		sendPush(
			$tokens,
			$this->input->post('title'),
			$this->input->post('message'),
			"promotional",
			""
		);	

		$this->session->set_flashdata('msg', 'Notifications Sent');
		redirect(base_url('other/send_app_notification'));
	}
}