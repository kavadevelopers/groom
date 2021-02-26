<?php
class Authservice extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function verify_profile()
	{
		if($this->input->post('doctype') && $this->input->post('user') && isset($_FILES ['doc'])){	
			$config['upload_path'] = './uploads/service/doc/';
		    $config['allowed_types']	= '*';
		    $config['max_size']      = '0';
		    $config['overwrite']     = FALSE;
		    $this->load->library('upload', $config);
			if(isset($_FILES ['doc']) && $_FILES['doc']['error'] == 0){
				$doc = microtime(true).".".pathinfo($_FILES['doc']['name'], PATHINFO_EXTENSION);
				$config['file_name'] = $doc;
		    	$this->upload->initialize($config);
		    	if($this->upload->do_upload('doc')){
		    		if($this->db->get_where('service_verified',['user' => $this->input->post('user')])->row_array()){
		    			$data = [
			    			'doctpye'	=> $this->input->post('doctype'),
			    			'image'		=> $doc,
			    			'user'		=> $this->input->post('user')
			    		];
			    		$this->db->where('user',$this->input->post('user'))->update('service_verified',$data);
		    		}else{
			    		$data = [
			    			'doctpye'	=> $this->input->post('doctype'),
			    			'image'		=> $doc,
			    			'user'		=> $this->input->post('user')
			    		];
			    		$this->db->insert('service_verified',$data);
			    	}
			    	retJson(['_return' => true,'msg' => 'Data uploaded']);		
		    	}else{
		    		retJson(['_return' => false,'msg' => 'File Upload Error']);		
		    	}
			}else{
	    		retJson(['_return' => false,'msg' => 'File Upload Error']);		
	    	}
		}else{
			retJson(['_return' => false,'msg' => '`user`,`doctype` and `doc` are Required']);
		}
	}

	public function getprofile()
	{
		if($this->input->post('user')){

			retJson(['_return' => true,'data' => $this->service_model->getServiceData($this->input->post('user'))]);	

		}else{
			retJson(['_return' => false,'msg' => '`user` is Required']);
		}
	}

	public function address()
	{
		if($this->input->post('lat') && $this->input->post('lon') && $this->input->post('user')){	
			$old = $this->db->get_where('service_address',['user' => $this->input->post('user')])->row_array();
			if($old){
				$city = "";$state = "";$country = "";$area = "";$street = "";
				if($this->input->post('city')){ $city = $this->input->post('city'); }else{ $city = ""; }
				if($this->input->post('state')){ $state = $this->input->post('state'); }else{ $state = ""; }
				if($this->input->post('country')){ $country = $this->input->post('country'); }else{ $country = ""; }
				if($this->input->post('area')){ $area = $this->input->post('area'); }else{ $area = ""; }
				if($this->input->post('street')){ $street = $this->input->post('street'); }else{ $street = ""; }

				$data = [
					'lat'		=> roundLatLon($this->input->post('lat')),
					'lon'		=> roundLatLon($this->input->post('lon')),
					'city'		=> $city,
					'state'		=> $state,
					'country'	=> $country,
					'area'		=> $area,
					'street'	=> $street,
					'user'		=> $this->input->post('user')
				];
				$this->db->where('user',$this->input->post('user'))->update('service_address',$data);
			}else{
				$city = "";$state = "";$country = "";$area = "";$street = "";
				if($this->input->post('city')){ $city = $this->input->post('city'); }else{ $city = ""; }
				if($this->input->post('state')){ $state = $this->input->post('state'); }else{ $state = ""; }
				if($this->input->post('country')){ $country = $this->input->post('country'); }else{ $country = ""; }
				if($this->input->post('area')){ $area = $this->input->post('area'); }else{ $area = ""; }
				if($this->input->post('street')){ $street = $this->input->post('street'); }else{ $street = ""; }
				$data = [
					'lat'		=> roundLatLon($this->input->post('lat')),
					'lon'		=> roundLatLon($this->input->post('lon')),
					'city'		=> $city,
					'state'		=> $state,
					'country'	=> $country,
					'area'		=> $area,
					'street'	=> $street,
					'user'		=> $this->input->post('user')
				];
				$this->db->insert('service_address',$data);
			}
		}else{
			retJson(['_return' => false,'msg' => '`lat`,`lon` and `user`(userid) are Required,Optionals are(city,state,country,area,street)']);
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
							if($user['approved'] == '1'){
								$firebase = [
									'token'		=> $this->input->post('firebase_token'),
									'device'	=> $this->input->post('device'),
									'device_id'	=> $this->input->post('device_id'),
									'user'		=> $user['id'],
									'cat'		=> _nowDateTime()
								];
								$this->db->insert('service_firebase',$firebase);
								retJson(['_return' => true,'msg' => 'Login Success','data' => $this->service_model->getServiceData($user['id'])]);
							}else{
								retJson(['_return' => false,'msg' => 'Account not approved yet. please contact administrator']);			
							}		
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
				if($this->input->post('phone') && $this->input->post('ccode')){
					$user = $this->db->get_where('service_provider',['phone' => $this->input->post('phone'),'ccode' => $this->input->post('ccode'),'rtype' => 'phone','verified' => '1'])->row_array();
					if($user){
						if($user['approved'] == '1'){
							$otp = generateOtp($user['id'],'service','login');
							retJson(['_return' => true,'msg' => 'Please Verify OTP.','otp' => $otp,'user' => $user['id']]);	
						}else{
							retJson(['_return' => false,'msg' => 'Account not approved yet. please contact administrator']);			
						}
					}else{
						retJson(['_return' => false,'msg' => 'Phone No. Not Registered']);	
					}
				}else{
					retJson(['_return' => false,'msg' => '`phone` id Required']);					
				}
			}else if($this->input->post('type') == 'facebook' || $this->input->post('type') == 'google'){
				if($this->input->post('social_id')){
					$old = $this->db->get_where('service_provider',['social_id' => $this->input->post('social_id'),'rtype' => $this->input->post('type'),'df' => ''])->row_array();
					if($old){
						if($old['approved'] == '1'){
							$firebase = [
								'token'		=> $this->input->post('firebase_token'),
								'device'	=> $this->input->post('device'),
								'device_id'	=> $this->input->post('device_id'),
								'user'		=> $old['id'],
								'cat'		=> _nowDateTime()
							];
							$this->db->insert('service_firebase',$firebase);
							retJson(['_return' => true,'msg' => 'Login Success','data' => $this->service_model->getServiceData($old['id'])]);
						}else{
							retJson(['_return' => false,'msg' => 'Account not approved yet. please contact administrator']);			
						}
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
				if($this->input->post('phone') && $this->input->post('ccode')){
					$user = $this->db->get_where('service_provider',['phone' => $this->input->post('phone'),'ccode' => $this->input->post('ccode'),'rtype' => 'email','df' => ''])->row_array();
					if($user){
						$otp = @generateOtp($user['id'],'service','forget_password');
						retJson(['_return' => true,'msg' => 'Reset password OTP sent to your phone no.','otp' => $otp,'user' => $user['id']]);
					}else{
						retJson(['_return' => false,'msg' => 'Cant find user with this phone no.']);	
					}
				}else{
					retJson(['_return' => false,'msg' => '`phone` and `ccode` are Required']);		
				}
			}
		}else{	
			retJson(['_return' => false,'msg' => '`type`(email,phone) is Required']);
		}
	}

	public function registerapi()
	{
		if($this->input->post('type')){
			if ($this->input->post('type') == 'email') {
				if($this->input->post('fname') && $this->input->post('lname') && $this->input->post('email') && $this->input->post('business') && $this->input->post('password') && $this->input->post('phone') && $this->input->post('services') && $this->input->post('desc') && $this->input->post('ccode')){
					$old = $this->db->get_where('service_provider',['rtype' => 'email','email' => $this->input->post('email'),'df' => '']);
					$oldp = $this->db->get_where('service_provider',['rtype' => 'email','phone' => $this->input->post('phone'),'ccode' => $this->input->post('ccode'),'df' => '']);
					if($old->num_rows() == 0){
						if($oldp->num_rows() == 0){
							$config['upload_path'] = './uploads/service/';
						    $config['allowed_types']	= '*';
						    $config['max_size']      = '0';
						    $config['overwrite']     = TRUE;
						    $this->load->library('upload', $config);
						    if(isset($_FILES ['profileimg']) && $_FILES['profileimg']['error'] == 0){
						    	$config['file_name'] = microtime(true).".".pathinfo($_FILES['profileimg']['name'], PATHINFO_EXTENSION);
						    	$this->upload->initialize($config);
						    	if($this->upload->do_upload('profileimg')){
						    		$profileFileName = $config['file_name'];
						    	}else{
						    		$profileFileName = "";
						    	}
						    }else{
					    		$profileFileName = "";
					    	}
							$data = [
								'rtype'			=> 'email',
								'firstname'		=> $this->input->post('fname'),
								'lastname'		=> $this->input->post('lname'),
								'email'			=> $this->input->post('email'),
								'ccode'			=> $this->input->post('ccode'),
								'phone'			=> $this->input->post('phone'),
								'business'		=> $this->input->post('business'),
								'services'		=> $this->input->post('services'),
								'descr'			=> $this->input->post('desc'),
								'password'		=> md5($this->input->post('password')),
								'profile_pic'	=> $profileFileName,
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
					retJson(['_return' => false,'msg' => '`fname`,`lname`,`email`,`business`,`phone`,`ccode`,`services`,`desc` and `password` are Required,`profileimg` is Optional']);	
				}			
			}elseif ($this->input->post('type') == 'phone') {
				if($this->input->post('phone') && $this->input->post('ccode')){
					$old = $this->db->get_where('service_provider',['phone' => $this->input->post('phone'),'ccode' => $this->input->post('ccode'),'df' => '','rtype' => 'phone'])->row_array();
					if($old){
						if($old['verified'] == "1"){
							retJson(['_return' => false,'msg' => 'Phone No. Already Exists']);	
						}else{
							$data = [
								'rtype'		=> 'phone',
								'firstname'	=> "",
								'lastname'	=> "",
								'ccode'		=> $this->input->post('ccode'),
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
							'ccode'		=> $this->input->post('ccode'),
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
					retJson(['_return' => false,'msg' => '`phone` and `ccode` are Required']);	
				}
			}elseif ($this->input->post('type') == 'googlefb') {
				if($this->input->post('type') && $this->input->post('fname') && $this->input->post('lname') && $this->input->post('business') && $this->input->post('phone') && $this->input->post('services') && $this->input->post('desc') && $this->input->post('social_id') && $this->input->post('ccode')){
					$old = $this->db->get_where('service_provider',['social_id' => $this->input->post('social_id'),'rtype' => $this->input->post('type'),'df' => ''])->row_array();
					if(!$old){
						$data = [
							'rtype'			=> $this->input->post('type'),
							'social_id'		=> $this->input->post('social_id'),
							'firstname'		=> $this->input->post('fname'),
							'lastname'		=> $this->input->post('lname'),
							'ccode'			=> $this->input->post('ccode'),
							'phone'			=> $this->input->post('phone'),
							'business'		=> $this->input->post('business'),
							'services'		=> $this->input->post('services'),
							'descr'			=> $this->input->post('desc'),
							'verified'		=> '1',
							'approved'		=> '1',
							'cat'			=> _nowDateTime()
						];
						$this->db->insert('service_provider',$data);
						$user = $this->db->insert_id();
						retJson(['_return' => true,'msg' => 'Sign Up Successful.','data' => $this->service_model->getServiceData($user)]);
					}else{
						retJson(['_return' => false,'msg' => 'Already Registered']);	
					}
				}else{
					retJson(['_return' => false,'msg' => '`type`(facebook,google),`social_id`,`fname`,`lname`,`business`,`phone`,`ccode`,`services` and `desc` are Required']);	
				}
			}
		}else{
			retJson(['_return' => false,'msg' => '`type`(phone,email,googlefb) is Required']);
		}
	}
}