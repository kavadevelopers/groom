<?php
class Authcustomer extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function logout()
	{
		if($this->input->post('user') && $this->input->post('firebase_token') && $this->input->post('device') && $this->input->post('device_id')){
			$this->db->where('user',$this->input->post('user'))->where('token',$this->input->post('firebase_token'))->where('device',$this->input->post('device'))->where('device_id',$this->input->post('device_id'))->delete('customer_firebase');
			retJson(['_return' => true,'msg' => 'Logout Success']);	
		}else{	
			retJson(['_return' => false,'msg' => '`user`,`device`,`device_id` and `firebase_token` are Required']);
		}
	}

	public function login()
	{
		if($this->input->post('type') && $this->input->post('firebase_token') && $this->input->post('device') && $this->input->post('device_id')){
			if($this->input->post('type') == 'email'){
				if($this->input->post('email') && $this->input->post('password')){
					$user = $this->db->get_where('customer',['email' => $this->input->post('email')])->row_array();
					if($user){
						if($user['password'] == md5($this->input->post('password'))){
							$firebase = [
								'token'		=> $this->input->post('firebase_token'),
								'device'	=> $this->input->post('device'),
								'device_id'	=> $this->input->post('device_id'),
								'user'		=> $user['id'],
								'cat'		=> _nowDateTime()
							];
							$this->db->insert('customer_firebase',$firebase);
							retJson(['_return' => true,'msg' => 'Login Success','data' => getCustomerData($user['id'])]);		
						}else{
							retJson(['_return' => false,'msg' => 'Email and Password not match']);		
						}
					}else{
						retJson(['_return' => false,'msg' => 'Email Not Registered']);	
					}
				}else{
					retJson(['_return' => false,'msg' => '`email` and `password` are Required']);			
				}
			}else if($this->input->post('type') == 'phone'){
				if($this->input->post('phone')){
					$user = $this->db->get_where('customer',['phone' => $this->input->post('phone')])->row_array();
					if($user){
						$otp = generateOtp($user['id'],'customer','login');
						retJson(['_return' => false,'msg' => 'Please Verify OTP.','otp' => $otp,'user' => $user['id']]);	
					}else{
						retJson(['_return' => false,'msg' => 'Phone No. Not Registered']);	
					}
				}else{
					retJson(['_return' => false,'msg' => '`phone` id Required']);					
				}
			}else if($this->input->post('type') == 'facebook' || $this->input->post('type') == 'google'){
				if($this->input->post('social_id') && $this->input->post('fname') && $this->input->post('lname') && $this->input->post('email')){
					$old = $this->db->get_where('customer',['social_id' => $this->input->post('social_id'),'rtype' => $this->input->post('type'),'df' => ''])->row_array();
					if($old){
						$firebase = [
							'token'		=> $this->input->post('firebase_token'),
							'device'	=> $this->input->post('device'),
							'device_id'	=> $this->input->post('device_id'),
							'user'		=> $old['id'],
							'cat'		=> _nowDateTime()
						];
						$this->db->insert('customer_firebase',$firebase);
						retJson(['_return' => true,'msg' => 'Login Success','data' => getCustomerData($old['id'])]);	
					}else{
						$profile_url = "";
						if($this->input->post('profile_url')){
							$profile_url = $this->input->post('profile_url');
						}
						$data = [
							'rtype'			=> $this->input->post('type'),
							'social_id'		=> $this->input->post('social_id'),
							'firstname'		=> $this->input->post('fname'),
							'lastname'		=> $this->input->post('lname'),
							'email'			=> $this->input->post('email'),
							'profile_pic'	=> $profile_url,
							'cat'			=> _nowDateTime()
						];
						$this->db->insert('customer',$data);
						$user = $this->db->insert_id();
						$firebase = [
							'token'		=> $this->input->post('firebase_token'),
							'device'	=> $this->input->post('device'),
							'device_id'	=> $this->input->post('device_id'),
							'user'		=> $user,
							'cat'		=> _nowDateTime()
						];
						$this->db->insert('customer_firebase',$firebase);
						retJson(['_return' => true,'msg' => 'Login Success','data' => getCustomerData($user)]);	
					}
				}else{
					retJson(['_return' => false,'msg' => '`social_id` (id of google or facebook),`fname`,`lname` and `email` are Required. `profile_url` is optional']);	
				}
			}else{
				retJson(['_return' => false,'msg' => 'Not Allowed.']);		
			}
		}else{
			retJson(['_return' => false,'msg' => '`type` (facebook,google,email,phone),`device`,`device_id` and `firebase_token` are Required']);	
		}
	}

	public function register()
	{
		if($this->input->post('fname') && $this->input->post('lname') && $this->input->post('email') && $this->input->post('password')){
			$old = $this->db->get_where('customer',['email' => $this->input->post('email'),'df' => '']);
			if($old->num_rows() == 0){
				$data = [
					'rtype'		=> 'email',
					'firstname'	=> $this->input->post('fname'),
					'lastname'	=> $this->input->post('lname'),
					'email'		=> $this->input->post('email'),
					'password'	=> md5($this->input->post('password')),
					'cat'		=> _nowDateTime()
				];
				$this->db->insert('customer',$data);
				retJson(['_return' => true,'msg' => 'Sign Up Successful.']);
			}else{
				retJson(['_return' => false,'msg' => 'Email Already Exists.']);
			}
		}else{
			retJson(['_return' => false,'msg' => '`fname`,`lname`,`email` and `password` are Required']);	
		}
	}
}
