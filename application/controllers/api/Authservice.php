<?php
class Authservice extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function registervia_googlefb()
	{
		if($this->input->post('type') && $this->input->post('fname') && $this->input->post('lname') && $this->input->post('business') && $this->input->post('password') && $this->input->post('phone') && $this->input->post('services') && $this->input->post('desc') && $this->input->post('social_id')){
			$data = [
				'rtype'			=> $this->input->post('type'),
				'social_id'		=> $this->input->post('social_id'),
				'firstname'		=> $this->input->post('fname'),
				'lastname'		=> $this->input->post('lname'),
				'phone'			=> $this->input->post('phone'),
				'business'		=> $this->input->post('business'),
				'services'		=> $this->input->post('services'),
				'desc'			=> $this->input->post('desc'),
				'verified'		=> '1',
				'cat'			=> _nowDateTime()
			];
			$this->db->insert('service_provider',$data);
			$user = $this->db->insert_id();
			retJson(['_return' => true,'msg' => 'Sign Up Successful.','data' => getServiceData($user)]);
		}else{
			retJson(['_return' => false,'msg' => '`type`(facebook,google),`social_id`,`fname`,`lname`,`business`,`phone`,`services`,`desc` and `password` are Required']);	
		}
	}

	public function login()
	{
		if($this->input->post('type') && $this->input->post('firebase_token') && $this->input->post('device') && $this->input->post('device_id')){
			if($this->input->post('type') == 'email'){
				if($this->input->post('email') && $this->input->post('password')){
					$user = $this->db->get_where('service_provider',['email' => $this->input->post('email'),'rtype' => 'email'])->row_array();
					if($user){
						if($user['password'] == md5($this->input->post('password'))){
							$firebase = [
								'token'		=> $this->input->post('firebase_token'),
								'device'	=> $this->input->post('device'),
								'device_id'	=> $this->input->post('device_id'),
								'user'		=> $user['id'],
								'cat'		=> _nowDateTime()
							];
							$this->db->insert('service_firebase',$firebase);
							retJson(['_return' => true,'msg' => 'Login Success','data' => getServiceData($user['id'])]);		
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
					$user = $this->db->get_where('service_provider',['phone' => $this->input->post('phone'),'rtype' => 'phone'])->row_array();
					if($user){
						$otp = generateOtp($user['id'],'service','login');
						retJson(['_return' => false,'msg' => 'Please Verify OTP.','otp' => $otp,'user' => $user['id']]);	
					}else{
						retJson(['_return' => false,'msg' => 'Phone No. Not Registered']);	
					}
				}else{
					retJson(['_return' => false,'msg' => '`phone` id Required']);					
				}
			}else if($this->input->post('type') == 'facebook' || $this->input->post('type') == 'google'){
				if($this->input->post('social_id')){
					$old = $this->db->get_where('customer',['social_id' => $this->input->post('social_id'),'rtype' => $this->input->post('type'),'df' => ''])->row_array();
					if($old){
						$firebase = [
							'token'		=> $this->input->post('firebase_token'),
							'device'	=> $this->input->post('device'),
							'device_id'	=> $this->input->post('device_id'),
							'user'		=> $old['id'],
							'cat'		=> _nowDateTime()
						];
						$this->db->insert('service_firebase',$firebase);
						retJson(['_return' => true,'msg' => 'Login Success','data' => getServiceData($old['id'])]);
					}else{
						retJson(['_return' => false,'msg' => 'Not Registered']);				
					}
				}else{
					retJson(['_return' => false,'msg' => '`social_id` is Required']);			
				}
			}else{
				retJson(['_return' => false,'msg' => 'Not Allowed.']);		
			}
		}else{
			retJson(['_return' => false,'msg' => '`type` (email,phone,facebook,google),`device`,`device_id` and `firebase_token` are Required']);	
		}
	}

	public function reset_password()
	{
		if($this->input->post('user') && $this->input->post('otp') && $this->input->post('password')){	
			$otp = $this->db->get_where('z_otp',['user' => $this->input->post('user'),'otp' => $this->input->post('otp'),'otptype' => 'forget_password','usertype' => 'service','used' => '0'])->row_array();
			if($otp){
				$this->db->where('id',$this->input->post('user'))->update('service_provider',['password' => md5($this->input->post('password'))]);
				$this->db->where('user',$this->input->post('user'))->where('otptype','forget_password')->where('usertype','service')->update('z_otp',['used' => '1']);
				retJson(['_return' => true,'msg' => 'Password changed.']);	
			}else{
				retJson(['_return' => false,'msg' => 'OTP not match']);	
			}
		}else{
			retJson(['_return' => false,'msg' => '`user`,`otp` are `password` are Required']);
		}
	}

	public function forget_password()
	{
		if($this->input->post('type')){
			if($this->input->post('type') == "email"){
				if($this->input->post('email')){
					$user = $this->db->get_where('service_provider',['email' => $this->input->post('email'),'rtype' => 'email','df' => ''])->row_array();
					if($user){
						$otp = @generateOtp($user['id'],'service','forget_password',true);
						$this->general_model->send_forget_email($user['firstname'].' '.$user['lastname'],$user['email'],$otp);
						retJson(['_return' => true,'msg' => 'Reset password OTP sent to your email','otp' => $otp,'user' => $user['id']]);
					}else{
						retJson(['_return' => false,'msg' => 'Cant find user with this email address.']);	
					}
				}else{
					retJson(['_return' => false,'msg' => '`email` is Required']);		
				}
			}else{
				if($this->input->post('phone')){
					$user = $this->db->get_where('service_provider',['phone' => $this->input->post('phone'),'rtype' => 'phone','df' => ''])->row_array();
					if($user){
						$otp = @generateOtp($user['id'],'service','forget_password');
						retJson(['_return' => true,'msg' => 'Reset password OTP sent to your phone no.','otp' => $otp,'user' => $user['id']]);
					}else{
						retJson(['_return' => false,'msg' => 'Cant find user with this phone no.']);	
					}
				}else{
					retJson(['_return' => false,'msg' => '`phone` is Required']);		
				}
			}
		}else{	
			retJson(['_return' => false,'msg' => '`type`(email,phone) is Required']);
		}
	}

	public function verify_registerphone()
	{
		if($this->input->post('user') && $this->input->post('otp')){
			$otp = $this->db->get_where('z_otp',['user' => $this->input->post('user'),'otp' => $this->input->post('otp'),'otptype' => 'register_phone','usertype' => 'service','used' => '0'])->row_array();
			if($otp){
				if($this->input->post('fname') && $this->input->post('lname') && $this->input->post('business') && $this->input->post('password') && $this->input->post('phone') && $this->input->post('services') && $this->input->post('desc')){
					$data = [
						'firstname'		=> $this->input->post('fname'),
						'lastname'		=> $this->input->post('lname'),
						'phone'			=> $this->input->post('phone'),
						'business'		=> $this->input->post('business'),
						'services'		=> $this->input->post('services'),
						'desc'			=> $this->input->post('desc'),
						'verified'		=> '1',
						'cat'			=> _nowDateTime()
					];
					$this->db->where('id',$this->input->post('user'))->update('service_provider',$data);
					$this->db->where('user',$this->input->post('user'))->where('otptype','register_phone')->where('usertype','service')->update('z_otp',['used' => '1']);
					retJson(['_return' => true,'msg' => 'Sign Up Successful.']);
				}else{
					retJson(['_return' => false,'msg' => '`fname`,`lname`,`business`,`phone`,`services`,`desc` and `password` are Required']);	
				}
			}else{
				retJson(['_return' => false,'msg' => 'OTP Not Valid']);		
			}
		}else{
			retJson(['_return' => false,'msg' => '`user`(user_id) and `otp` is Required']);	
		}	
	}

	public function registerviaphone()
	{
		if($this->input->post('phone')){
			$old = $this->db->get_where('service_provider',['phone' => $this->input->post('phone'),'df' => '','rtype' => 'phone'])->row_array();
			if($old){
				if($old['verified'] == "1"){
					retJson(['_return' => false,'msg' => 'Phone No. Already Exists']);	
				}else{
					$data = [
						'rtype'		=> 'phone',
						'firstname'	=> "",
						'lastname'	=> "",
						'phone'		=> $this->input->post('phone'),
						'verified'	=> '0',
						'cat'		=> _nowDateTime()
					];
					$this->db->where('id',$old['id'])->update('service_provider',$data);
					$otp = generateOtp($old['id'],'service','register_phone');
					retJson(['_return' => true,'msg' => 'Please Verify OTP.','user' => $old['id'],'otp' => $otp]);
				}
			}else{
				$data = [
					'rtype'		=> 'phone',
					'firstname'	=> "",
					'lastname'	=> "",
					'phone'		=> $this->input->post('phone'),
					'verified'	=> '0',
					'cat'		=> _nowDateTime()
				];
				$this->db->insert('service_provider',$data);
				$user = $this->db->insert_id();
				$otp = generateOtp($user,'service','register_phone');
				retJson(['_return' => true,'msg' => 'Please Verify OTP.','user' => $user,'otp' => $otp]);
			}
		}else{
			retJson(['_return' => false,'msg' => '`phone` is Required']);	
		}
	}

	public function register()
	{
		if($this->input->post('fname') && $this->input->post('lname') && $this->input->post('email') && $this->input->post('business') && $this->input->post('password') && $this->input->post('phone') && $this->input->post('services') && $this->input->post('desc')){
			$old = $this->db->get_where('service_provider',['rtype' => 'email','email' => $this->input->post('email'),'df' => '']);
			$oldp = $this->db->get_where('service_provider',['rtype' => 'email','phone' => $this->input->post('phone'),'df' => '']);
			if($old->num_rows() == 0){
				if($oldp->num_rows() == 0){
					$data = [
						'rtype'			=> 'email',
						'firstname'		=> $this->input->post('fname'),
						'lastname'		=> $this->input->post('lname'),
						'email'			=> $this->input->post('email'),
						'phone'			=> $this->input->post('phone'),
						'business'		=> $this->input->post('business'),
						'services'		=> $this->input->post('services'),
						'desc'			=> $this->input->post('desc'),
						'password'		=> md5($this->input->post('password')),
						'verified'		=> '1',
						'cat'			=> _nowDateTime()
					];
					$this->db->insert('service_provider',$data);
					retJson(['_return' => true,'msg' => 'Sign Up Successful.']);
				}else{
					retJson(['_return' => false,'msg' => 'Phone Already Exists.']);	
				}
			}else{
				retJson(['_return' => false,'msg' => 'Email Already Exists.']);
			}
		}else{
			retJson(['_return' => false,'msg' => '`fname`,`lname`,`email`,`business`,`phone`,`services`,`desc` and `password` are Required']);	
		}			
	}
}