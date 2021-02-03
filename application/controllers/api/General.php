<?php
class General extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}


	public function verify_otp()
	{
		if($this->input->post('user') && $this->input->post('usertype') && $this->input->post('otp') && $this->input->post('otptype')){
			if($this->input->post('otptype') == 'login'){
				if($this->input->post('firebase_token') && $this->input->post('device') && $this->input->post('device_id')){
					$otp = $this->db->get_where('z_otp',['user' => $this->input->post('user'),'otp' => $this->input->post('otp'),'otptype' => $this->input->post('otptype'),'usertype' => $this->input->post('usertype'),'used' => '0'])->row_array();
					if($otp){
						$this->db->where('id',$otp['id'])->update('z_otp',['used' => '1']);
						if($this->input->post('usertype') == 'customer'){
							$firebase = [
								'token'		=> $this->input->post('firebase_token'),
								'device'	=> $this->input->post('device'),
								'device_id'	=> $this->input->post('device_id'),
								'user'		=> $this->input->post('user'),
								'cat'		=> _nowDateTime()
							];
							$this->db->insert('customer_firebase',$firebase);
							retJson(['_return' => true,'msg' => 'Login Success','data' => getCustomerData($this->input->post('user'))]);
						}else{
							
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