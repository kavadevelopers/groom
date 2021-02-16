<?php
class General extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function get_master_datas()
	{
		$occupations = $this->db->get_where('master_occupations',['df' => ''])->result_array();
		$skills = $this->db->get_where('master_skills',['df' => ''])->result_array();
		retJson(['_return' => true,'occupations' => $occupations,'skills' => $skills]);		
	}

	public function page()
	{
		if($this->input->post('page')){
			if($this->input->post('page') == "terms"){
				$page = $this->db->get_where('pages',['id' => '1'])->row_array();
				retJson(['_return' => true,'page' => $page]);		
			}else if($this->input->post('page') == "help"){
				$page = $this->db->get_where('pages',['id' => '2'])->row_array();
				retJson(['_return' => true,'page' => $page]);		
			}else{
				retJson(['_return' => false,'msg' => 'Please Enter Valid Page name']);		
			}
		}else{
			retJson(['_return' => false,'msg' => '`page`(terms,help) is Required']);	
		}
	}

	public function getcategories()
	{
		$categories = $this->db->get_where('categories',['df' => '','block' => ''])->result_array();
		foreach ($categories as $key => $value) {
			$categories[$key]['image']	= $this->general_model->getCategoryThumb($value['id']);
		}
		retJson(['_return' => true,'count' => count($categories),'list' => $categories]);		
	}

	public function verify_otp()
	{
		if($this->input->post('user') && $this->input->post('usertype') && $this->input->post('otp') && $this->input->post('otptype')){
			if($this->input->post('otptype') == 'login'){
				if($this->input->post('firebase_token') && $this->input->post('device') && $this->input->post('device_id')){
					$otp = $this->db->get_where('z_otp',['user' => $this->input->post('user'),'otp' => $this->input->post('otp'),'otptype' => $this->input->post('otptype'),'usertype' => $this->input->post('usertype'),'used' => '0'])->row_array();
					if($otp){
						$this->db->where('user',$this->input->post('user'))->where('otptype','login')->where('usertype','service')->update('z_otp',['used' => '1']);
						if($this->input->post('usertype') == 'service'){
							$firebase = [
								'token'		=> $this->input->post('firebase_token'),
								'device'	=> $this->input->post('device'),
								'device_id'	=> $this->input->post('device_id'),
								'user'		=> $this->input->post('user'),
								'cat'		=> _nowDateTime()
							];
							$this->db->insert('service_firebase',$firebase);
							retJson(['_return' => true,'msg' => 'Login Success','data' => $this->service_model->getServiceData($this->input->post('user'))]);
						}else{
							$firebase = [
								'token'		=> $this->input->post('firebase_token'),
								'device'	=> $this->input->post('device'),
								'device_id'	=> $this->input->post('device_id'),
								'user'		=> $this->input->post('user'),
								'cat'		=> _nowDateTime()
							];
							$this->db->insert('customer_firebase',$firebase);
							retJson(['_return' => true,'msg' => 'Login Success','data' => $this->customer_model->getCustomerData($this->input->post('user'))]);
						}
					}else{
						retJson(['_return' => false,'msg' => 'OTP Not Valid']);		
					}
				}else{
					retJson(['_return' => false,'msg' => '`device`,`device_id` and `firebase_token` are Required']);	
				}
			}
		}else{
			retJson(['_return' => false,'msg' => '`otptype` (register,login),`user` (User id),`usertype` (customer,service) and `otp` are Required']);
		}
	}
}