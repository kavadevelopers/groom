<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index()
	{
		if($this->db->get_where('setting',['id' => '1'])->row_array()['cunstruction'] == '0'){
			if($this->session->userdata('id')){
				redirect(base_url('dashboard'));
			}
			else
			{
				$this->load->helper('cookie');
				$this->load->view('login');
			}
		}else{
			redirect(base_url('welcome/working_on_updates'));
		}
	}

	public function working_on_updates()
	{
		if($this->db->get_where('setting',['id' => '1'])->row_array()['cunstruction'] == '1'){
			$this->load->view('off');
		}else{
			redirect(base_url('welcome'));	
		}
	}
}
