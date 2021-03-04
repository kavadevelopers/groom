<?php
class Authservice extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function save_personalinfo()
	{	
		if($this->input->post('user') && $this->input->post('type')){
			if ($this->input->post('type') == "legal_name") {
				if($this->input->post('fname') && $this->input->post('lname')){
					$this->db->where('id',$this->input->post('user'))->update('service_provider',['firstname' => $this->input->post('fname'),'lastname' => $this->input->post('lname')]);
					retJson(['_return' => true,'msg' => 'Legal Name saved.']);	
				}else{
					retJson(['_return' => false,'msg' => '`fname` and `lname` are Required']);
				}
			}

			else if ($this->input->post('type') == "email") {
				if($this->input->post('email')){
					$user = $this->db->get_where('service_provider',['id' => $this->input->post('user')])->row_array();
					if($user['rtype'] == "email"){
						$old = $this->db->get_where('service_provider',['rtype' => 'email','email' => $this->input->post('email')])->row_array();
						if(!$old){
							$this->db->where('id',$this->input->post('user'))->update('service_provider',['email' => $this->input->post('email')]);
							retJson(['_return' => true,'msg' => 'Email saved.']);	
						}else{
							retJson(['_return' => false,'msg' => 'Email Address is Already exists.']);		
						}
					}else{
						$this->db->where('id',$this->input->post('user'))->update('service_provider',['email' => $this->input->post('email')]);
						retJson(['_return' => true,'msg' => 'Email saved.']);	
					}
				}else{
					retJson(['_return' => false,'msg' => '`email` is Required']);
				}	
			}

			else if ($this->input->post('type') == "phone") {
				if($this->input->post('phone') && $this->input->post('ccode')){
					$user = $this->db->get_where('service_provider',['id' => $this->input->post('user')])->row_array();
					if($user['rtype'] == "phone"){
						$old = $this->db->get_where('service_provider',['rtype' => 'phone','ccode' => $this->input->post('ccode'),'phone' => $this->input->post('phone')])->row_array();
						if(!$old){
							$this->db->where('id',$this->input->post('user'))->update('service_provider',['ccode' => $this->input->post('ccode'),'phone' => $this->input->post('phone')]);
							retJson(['_return' => true,'msg' => 'Phone saved.']);		
						}else{
							retJson(['_return' => false,'msg' => 'Phone no. is Already exists.']);		
						}
					}else{
						$this->db->where('id',$this->input->post('user'))->update('service_provider',['ccode' => $this->input->post('ccode'),'phone' => $this->input->post('phone')]);
						retJson(['_return' => true,'msg' => 'Phone saved.']);	
					}
				}else{
					retJson(['_return' => false,'msg' => '`phone` and `ccode`(Country Code) is Required']);
				}	
			}

			else if($this->input->post('type') == "gender"){
				if($this->input->post('gender')){
					$this->general_model->insertServiceDetails($this->input->post('user'));
					$this->db->where('user',$this->input->post('user'))->update('service_provider_details',['gender' => $this->input->post('gender')]);
				}else{
					retJson(['_return' => false,'msg' => '`gender` is Required']);
				}
			}

			else if($this->input->post('type') == "dob"){
				if($this->input->post('dob')){
					$this->general_model->insertServiceDetails($this->input->post('user'));
					$this->db->where('user',$this->input->post('user'))->update('service_provider_details',['dob' => dd($this->input->post('dob'))]);
				}else{
					retJson(['_return' => false,'msg' => '`dob` is Required']);
				}
			}

			else if($this->input->post('type') == "goverment"){
				if($this->input->post('goverment')){
					$this->general_model->insertServiceDetails($this->input->post('user'));
					$this->db->where('user',$this->input->post('user'))->update('service_provider_details',['goverment' => $this->input->post('goverment')]);
				}else{
					retJson(['_return' => false,'msg' => '`goverment` is Required']);
				}
			}

			else if($this->input->post('type') == "address"){
				if($this->input->post('address')){
					$this->general_model->insertServiceDetails($this->input->post('user'));
					$this->db->where('user',$this->input->post('user'))->update('service_provider_details',['address' => $this->input->post('address')]);
				}else{
					retJson(['_return' => false,'msg' => '`address` is Required']);
				}
			}

			else if($this->input->post('type') == "emergency_contact"){
				if($this->input->post('emergency_contact')){
					$this->general_model->insertServiceDetails($this->input->post('user'));
					$this->db->where('user',$this->input->post('user'))->update('service_provider_details',['emergency_contact' => $this->input->post('emergency_contact')]);
				}else{
					retJson(['_return' => false,'msg' => '`emergency_contact` is Required']);
				}
			}

			else{
				retJson(['_return' => false,'msg' => 'Type not found']);
			}
		}else{
			retJson(['_return' => false,'msg' => '`user` and `type`(legal_name,gender,dob,goverment,email,phone,address,emergency_contact) are Required']);
		}
	}

	public function change_password()
	{
		if($this->input->post('oldpassword') && $this->input->post('newpassword') && $this->input->post('user')){
			$user = $this->db->get_where('service_provider',['id' => $this->input->post('user')])->row_array();
			if($user){
				if($user['password'] == md5($this->input->post('oldpassword'))){
					$this->db->where('id',$this->input->post('user'))->update('service_provider',['password' => md5($this->input->post('newpassword'))]);
					retJson(['_return' => false,'msg' => 'Password changed.']);		
				}else{
					retJson(['_return' => false,'msg' => 'Old Password do not match']);		
				}
			}else{
				retJson(['_return' => false,'msg' => 'User not found']);	
			}
		}else{
			retJson(['_return' => false,'msg' => '`user`,`oldpassword` and `newpassword` are Required']);
		}
	}

	public function get_notifications_setting()
	{
		if($this->input->post('user')){
			$old = $this->db->get_where('service_provider',['id' => $this->input->post('user')])->row_array();
			if($old){
				retJson(['_return' => true,'data' => $this->service_model->getNotificationSetting($this->input->post('user'))]);
			}else{
				retJson(['_return' => false,'msg' => 'user not found']);
			}
		}else{	
			retJson(['_return' => false,'msg' => '`user` is Required']);
		}
	}

	public function manage_notifications()
	{
		if($this->input->post('user') && $this->input->post('m_email') && $this->input->post('m_text') && $this->input->post('m_push') && $this->input->post('r_email') && $this->input->post('r_text') && $this->input->post('r_push') && $this->input->post('pt_email') && $this->input->post('pt_text') && $this->input->post('pt_push') && $this->input->post('pc_email') && $this->input->post('pc_text') && $this->input->post('pc_push')){
			$user = $this->db->get_where('service_provider',['id' => $this->input->post('user')])->row_array();
			if($user){
				$old = $this->db->get_where('service_notification',['user' => $this->input->post('user')])->row_array();
				if($old){
					$data = [
						'user'			=> $this->input->post('user'),
						'm_email'		=> $this->input->post('m_email'),
						'm_text'		=> $this->input->post('m_text'),
						'm_push'		=> $this->input->post('m_push'),
						'r_email'		=> $this->input->post('r_email'),
						'r_text'		=> $this->input->post('r_text'),
						'r_push'		=> $this->input->post('r_push'),
						'pt_email'		=> $this->input->post('pt_email'),
						'pt_text'		=> $this->input->post('pt_text'),
						'pt_push'		=> $this->input->post('pt_push'),
						'pc_email'		=> $this->input->post('pc_email'),
						'pc_text'		=> $this->input->post('pc_text'),
						'pc_push'		=> $this->input->post('pc_push')
					];
					$this->db->where('user',$this->input->post('user'))->update('service_notification',$data);
				}else{
					$data = [
						'user'			=> $this->input->post('user'),
						'm_email'		=> $this->input->post('m_email'),
						'm_text'		=> $this->input->post('m_text'),
						'm_push'		=> $this->input->post('m_push'),
						'r_email'		=> $this->input->post('r_email'),
						'r_text'		=> $this->input->post('r_text'),
						'r_push'		=> $this->input->post('r_push'),
						'pt_email'		=> $this->input->post('pt_email'),
						'pt_text'		=> $this->input->post('pt_text'),
						'pt_push'		=> $this->input->post('pt_push'),
						'pc_email'		=> $this->input->post('pc_email'),
						'pc_text'		=> $this->input->post('pc_text'),
						'pc_push'		=> $this->input->post('pc_push')
					];
					$this->db->insert('service_notification',$data);
				}
				retJson(['_return' => true,'msg' => 'Notification Settings Saved']);	
			}else{
				retJson(['_return' => false,'msg' => 'user not found']);
			}
		}else{	
			retJson(['_return' => false,'msg' => '`user`,`m_email`,`m_text`,`m_push`,`r_email`,`r_text`,`r_push`,`pt_email`,`pt_text`,`pt_push`,`pc_email`,`pc_text`,`pc_push` are Required']);
		}
	}

	public function logout_device()
	{
		if($this->input->post('user') && $this->input->post('firebase_token')){
			$this->db->where('user',$this->input->post('user'))->where('token',$this->input->post('firebase_token'))->delete('service_firebase');
			retJson(['_return' => true,'msg' => 'Logout Success']);	
		}else{	
			retJson(['_return' => false,'msg' => '`user` and `firebase_token` are Required']);
		}
	}

	public function get_logged_devices()
	{
		if($this->input->post('user')){
			$devices = $this->db->get_where('service_firebase',['user' => $this->input->post('user')]);
			retJson(['_return' => true,'count' => $devices->num_rows(),'list' => $devices->result_array()]);	
		}else{
			retJson(['_return' => false,'msg' => '`user` is Required']);
		}	
	}

	public function logout()
	{
		if($this->input->post('user') && $this->input->post('firebase_token') && $this->input->post('device') && $this->input->post('device_id')){
			$this->db->where('user',$this->input->post('user'))->where('token',$this->input->post('firebase_token'))->where('device',$this->input->post('device'))->where('device_id',$this->input->post('device_id'))->delete('service_firebase');
			retJson(['_return' => true,'msg' => 'Logout Success']);	
		}else{	
			retJson(['_return' => false,'msg' => '`user`,`device`,`device_id` and `firebase_token` are Required']);
		}
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
		if($this->input->post('type') && $this->input->post('firebase_token') && $this->input->post('device') && $this->input->post('device_id') && $this->input->post('desc')){
			if($this->input->post('type') == 'email'){
				if($this->input->post('email') && $this->input->post('password')){
					$user = $this->db->get_where('service_provider',['email' => $this->input->post('email'),'rtype' => 'email'])->row_array();
					if($user){
						if($user['password'] == md5($this->input->post('password'))){
							if($user['approved'] == '1'){
								$firebase = [
									'desc'		=> $this->input->post('desc'),
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
								'desc'		=> $this->input->post('desc'),
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
			retJson(['_return' => false,'msg' => '`type` (email,phone,facebook,google),`device`,`device_id`,`desc` and `firebase_token` are Required']);	
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
			}elseif ($this->input->post('type') == 'facebook' || $this->input->post('type') == 'google') {
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
					retJson(['_return' => false,'msg' => '`social_id`,`fname`,`lname`,`business`,`phone`,`ccode`,`services` and `desc` are Required']);	
				}
			}else{
				retJson(['_return' => false,'msg' => '`type` not found']);	
			}
		}else{
			retJson(['_return' => false,'msg' => '`type`(phone,email,facebook,google) is Required']);
		}
	}
}