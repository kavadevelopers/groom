<?php
class Authcustomer extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function verify_gftpe()
	{
		if($this->input->post('userid') && $this->input->post('ver_google') && $this->input->post('ver_fb') && $this->input->post('ver_twi') && $this->input->post('ver_phone') && $this->input->post('ver_email')){
			$this->db->where('id',$this->input->post('userid'))->update('customer',[
				'ver_google'			=> $this->input->post('ver_google'),
				'ver_fb'				=> $this->input->post('ver_fb'),
				'ver_twi'				=> $this->input->post('ver_twi'),
				'ver_phone'				=> $this->input->post('ver_phone'),
				'ver_email'				=> $this->input->post('ver_email')
			]);
			retJson(['_return' => true,'data' => $this->customer_model->getCustomerData($this->input->post('userid'))]);	
		}else{
			retJson(['_return' => false,'msg' => '`userid`,`ver_google`,`ver_fb`,`ver_twi`,`ver_phone`,`ver_email` are Required']);
		}
	}

	public function save_certificate()
	{
		if($this->input->post('userid') && $this->input->post('certificate') && $this->input->post('certificate_from') && $this->input->post('certificate_year') && $this->input->post('web_link')){
			$this->db->where('id',$this->input->post('userid'))->update('customer',[
				'certificate'			=> $this->input->post('certificate'),
				'certificate_from'		=> $this->input->post('certificate_from'),
				'certificate_year'		=> $this->input->post('certificate_year'),
				'web_link'				=> $this->input->post('web_link')
			]);
			retJson(['_return' => true,'data' => $this->customer_model->getCustomerData($this->input->post('userid'))]);	
		}else{
			retJson(['_return' => false,'msg' => '`userid`,`certificate`,`certificate_from`,`certificate_year`,`web_link` are Required']);
		}
	}

	public function education()
	{
		if($this->input->post('type')){
			if($this->input->post('type') == "new"){
				if($this->input->post('userid') && $this->input->post('country') && $this->input->post('college') && $this->input->post('title') && $this->input->post('month') && $this->input->post('year')){
					$this->db->insert('customer_education',[
						'country'	=> $this->input->post('country'),
						'college'	=> $this->input->post('college'),
						'title'		=> $this->input->post('title'),
						'month'		=> $this->input->post('month'),
						'year'		=> $this->input->post('year'),
						'user'		=> $this->input->post('userid'),
						'cat'		=> _nowDateTime()
					]);
					retJson(['_return' => true,'data' => $this->customer_model->getCustomerData($this->input->post('userid'))]);	
				}else{
					retJson(['_return' => false,'msg' => '`userid`,`country`,`college`,`title`,`month`,`year` are Required']);
				}
			}else if($this->input->post('type') == "update"){
				if($this->input->post('eduid') && $this->input->post('country') && $this->input->post('college') && $this->input->post('title') && $this->input->post('month') && $this->input->post('year')){
					$this->db->where('id',$this->input->post('eduid'))->update('customer_education',[
						'country'	=> $this->input->post('country'),
						'college'	=> $this->input->post('college'),
						'title'		=> $this->input->post('title'),
						'month'		=> $this->input->post('month'),
						'year'		=> $this->input->post('year')
					]);
					retJson(['_return' => true,'data' => $this->customer_model->getCustomerData($this->input->post('userid'))]);	
				}else{
					retJson(['_return' => false,'msg' => '`eduid`,`country`,`college`,`title`,`month`,`year` are Required']);
				}
			}else if($this->input->post('type') == "delete"){
				if($this->input->post('eduid')){
					$this->db->where('id',$this->input->post('eduid'))->delete('customer_education');
					retJson(['_return' => true,'data' => $this->customer_model->getCustomerData($this->input->post('userid'))]);	
				}else{
					retJson(['_return' => false,'msg' => '`eduid` is Required']);
				}
			}
		}else{
			retJson(['_return' => false,'msg' => '`type`(new,update,delete) is Required']);
		}
	}

	public function save_occupation()
	{
		if($this->input->post('userid') && $this->input->post('occupations') && $this->input->post('skills') && $this->input->post('ex_level')){
			$this->db->where('id',$this->input->post('userid'))->update('customer',[
				'occupations'	=> $this->input->post('occupations'),
				'skills'		=> $this->input->post('skills'),
				'ex_level'		=> $this->input->post('ex_level')
			]);
			retJson(['_return' => true,'data' => $this->customer_model->getCustomerData($this->input->post('userid'))]);	
		}else{
			retJson(['_return' => false,'msg' => '`userid`,`occupations` (comma saparated ids of occupation),`skills` (comma saparated ids of occupation),`ex_level` are Required']);
		}
	}
	
	public function save_personal_info()
	{
		if($this->input->post('userid') && $this->input->post('fname') && $this->input->post('lname') && $this->input->post('description') && $this->input->post('languages')){

			$this->db->where('id',$this->input->post('userid'))->update('customer',[
				'firstname'	=> $this->input->post('fname'),
				'lastname'	=> $this->input->post('lname'),
				'descr'		=> $this->input->post('description'),
				'languages'	=> $this->input->post('languages')
			]);

			if(isset($_FILES ['profile_pic'])){
				$config['upload_path'] = './uploads/customer/';
			    $config['allowed_types']	= '*';
			    $config['max_size']      = '0';
			    $config['overwrite']     = TRUE;
			    $this->load->library('upload', $config);
			    if(isset($_FILES ['profile_pic']) && $_FILES['profile_pic']['error'] == 0){
			    	$config['file_name'] = microtime(true).".".pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
			    	$this->upload->initialize($config);
			    	if($this->upload->do_upload('profile_pic')){
			    		$this->db->where('id',$this->input->post('userid'))->update('customer',[
							'profile_pic'	=> $config['file_name']
						]);
			    	}
			    }
			}
			retJson(['_return' => true,'data' => $this->customer_model->getCustomerData($this->input->post('userid'))]);	
		}else{
			retJson(['_return' => false,'msg' => '`userid`,`fname`,`lname`,`description`,`languages`(English-Basic,Hindi-Basic) are Required,`profile_pic`(multi part) is optional']);
		}
	}

	public function verify_profile()
	{
		if($this->input->post('doctype') && $this->input->post('user') && isset($_FILES ['doc'])){	
			$config['upload_path'] = './uploads/customer/doc/';
		    $config['allowed_types']	= '*';
		    $config['max_size']      = '0';
		    $config['overwrite']     = FALSE;
		    $this->load->library('upload', $config);
			if(isset($_FILES ['doc']) && $_FILES['doc']['error'] == 0){
				$doc = microtime(true).".".pathinfo($_FILES['doc']['name'], PATHINFO_EXTENSION);
				$config['file_name'] = $doc;
		    	$this->upload->initialize($config);
		    	if($this->upload->do_upload('doc')){
		    		if($this->db->get_where('customer_verified',['user' => $this->input->post('user')])->row_array()){
		    			$data = [
			    			'doctpye'	=> $this->input->post('doctype'),
			    			'image'		=> $doc,
			    			'user'		=> $this->input->post('user')
			    		];
			    		$this->db->where('user',$this->input->post('user'))->update('customer_verified',$data);
		    		}else{
			    		$data = [
			    			'doctpye'	=> $this->input->post('doctype'),
			    			'image'		=> $doc,
			    			'user'		=> $this->input->post('user')
			    		];
			    		$this->db->insert('customer_verified',$data);
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

			retJson(['_return' => true,'data' => $this->customer_model->getCustomerData($this->input->post('user'))]);	

		}else{
			retJson(['_return' => false,'msg' => '`user` is Required']);
		}
	}

	public function address()
	{
		if($this->input->post('lat') && $this->input->post('lon') && $this->input->post('user')){	
			$old = $this->db->get_where('customer_address',['user' => $this->input->post('user')])->row_array();
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
				$this->db->where('user',$this->input->post('user'))->update('customer_address',$data);
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
				$this->db->insert('customer_address',$data);
			}
		}else{
			retJson(['_return' => false,'msg' => '`lat`,`lon` and `user`(userid) are Required,Optionals are(city,state,country,area,street)']);
		}
	}

	public function reset_password()
	{
		if($this->input->post('user') && $this->input->post('otp') && $this->input->post('password')){	
			$otp = $this->db->get_where('z_otp',['user' => $this->input->post('user'),'otp' => $this->input->post('otp'),'otptype' => 'forget_password','usertype' => 'customer','used' => '0'])->row_array();
			if($otp){
				$this->db->where('id',$this->input->post('user'))->update('customer',['password' => md5($this->input->post('password'))]);
				$this->db->where('user',$this->input->post('user'))->where('otptype','forget_password')->where('usertype','customer')->update('z_otp',['used' => '1']);
				retJson(['_return' => true,'msg' => 'Password changed.']);	
			}else{
				retJson(['_return' => false,'msg' => 'OTP not match']);	
			}
		}else{
			retJson(['_return' => false,'msg' => '`user`,`otp` and `password` are Required']);
		}
	}

	public function forget_password()
	{
		if($this->input->post('type')){
			if($this->input->post('type') == "email"){
				if($this->input->post('email')){
					$user = $this->db->get_where('customer',['email' => $this->input->post('email'),'rtype' => 'email','df' => ''])->row_array();
					if($user){
						$otp = @generateOtp($user['id'],'customer','forget_password',true);
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
					$user = $this->db->get_where('customer',['phone' => $this->input->post('phone'),'ccode' => $this->input->post('ccode'),'rtype' => 'email','df' => ''])->row_array();
					if($user){
						$otp = @generateOtp($user['id'],'customer','forget_password');
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
		if($this->input->post('type') && $this->input->post('firebase_token') && $this->input->post('device') && $this->input->post('device_id') && $this->input->post('desc')){
			if($this->input->post('type') == 'email'){
				if($this->input->post('email') && $this->input->post('password')){
					$user = $this->db->get_where('customer',['email' => $this->input->post('email'),'rtype' => 'email'])->row_array();
					if($user){
						if($user['password'] == md5($this->input->post('password'))){
							$firebase = [
								'desc'		=> $this->input->post('desc'),
								'token'		=> $this->input->post('firebase_token'),
								'device'	=> $this->input->post('device'),
								'device_id'	=> $this->input->post('device_id'),
								'user'		=> $user['id'],
								'cat'		=> _nowDateTime()
							];
							$this->db->insert('customer_firebase',$firebase);
							retJson(['_return' => true,'msg' => 'Login Success','data' => $this->customer_model->getCustomerData($user['id'])]);		
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
					$user = $this->db->get_where('customer',['phone' => $this->input->post('phone'),'ccode'		=> $this->input->post('ccode'),'rtype' => 'phone','verified' => '1'])->row_array();
					if($user){
						$otp = generateOtp($user['id'],'customer','login');
						retJson(['_return' => true,'msg' => 'Please Verify OTP.','otp' => $otp,'user' => $user['id']]);	
					}else{
						retJson(['_return' => false,'msg' => 'Phone No. Not Registered']);	
					}
				}else{
					retJson(['_return' => false,'msg' => '`phone` and `ccode` are Required']);					
				}
			}else if($this->input->post('type') == 'facebook' || $this->input->post('type') == 'google'){
				if($this->input->post('social_id') && $this->input->post('fname') && $this->input->post('lname') && $this->input->post('email')){
					if($this->input->post('type') == 'facebook'){ $veriGF = 'ver_fb'; }else{ $veriGF = 'ver_google'; }
					$old = $this->db->get_where('customer',['social_id' => $this->input->post('social_id'),'rtype' => $this->input->post('type'),'df' => ''])->row_array();
					if($old){
						$firebase = [
							'desc'		=> $this->input->post('desc'),
							'token'		=> $this->input->post('firebase_token'),
							'device'	=> $this->input->post('device'),
							'device_id'	=> $this->input->post('device_id'),
							'user'		=> $old['id'],
							'cat'		=> _nowDateTime()
						];
						$this->db->insert('customer_firebase',$firebase);
						retJson(['_return' => true,'msg' => 'Login Success','data' => $this->customer_model->getCustomerData($old['id'])]);	
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
							$veriGF 		=> '1',
							'cat'			=> _nowDateTime()
						];
						$this->db->insert('customer',$data);
						$user = $this->db->insert_id();
						$firebase = [
							'desc'		=> $this->input->post('desc'),
							'token'		=> $this->input->post('firebase_token'),
							'device'	=> $this->input->post('device'),
							'device_id'	=> $this->input->post('device_id'),
							'user'		=> $user,
							'cat'		=> _nowDateTime()
						];
						$this->db->insert('customer_firebase',$firebase);
						retJson(['_return' => true,'msg' => 'Login Success','data' => $this->customer_model->getCustomerData($user)]);	
					}
				}else{
					retJson(['_return' => false,'msg' => '`social_id` (id of google or facebook),`fname`,`lname` and `email` are Required. `profile_url` is optional']);	
				}
			}else{
				retJson(['_return' => false,'msg' => 'Not Allowed.']);		
			}
		}else{
			retJson(['_return' => false,'msg' => '`type` (facebook,google,email,phone),`device`,`device_id`,`desc` and `firebase_token` are Required']);	
		}
	}

	public function registerapi()
	{
		if ($this->input->post('type') && $this->input->post('type') == 'email') {
			if($this->input->post('fname') && $this->input->post('lname') && $this->input->post('email') && $this->input->post('password') && $this->input->post('phone') && $this->input->post('ccode')){
				$old = $this->db->get_where('customer',['rtype' => 'email','email' => $this->input->post('email'),'df' => '']);
				$oldp = $this->db->get_where('customer',['rtype' => 'email','phone' => $this->input->post('phone'),'ccode' => $this->input->post('ccode'),'df' => '']);
				if($old->num_rows() == 0 && $oldp->num_rows() == 0){
					$config['upload_path'] = './uploads/customer/';
				    $config['allowed_types']	= '*';
				    $config['max_size']      = '0';
				    $config['overwrite']     = TRUE;
				    $this->load->library('upload', $config);
				    
					$data = [
						'rtype'		=> 'email',
						'firstname'	=> $this->input->post('fname'),
						'lastname'	=> $this->input->post('lname'),
						'email'		=> $this->input->post('email'),
						'ccode'		=> $this->input->post('ccode'),
						'phone'		=> $this->input->post('phone'),
						'password'	=> md5($this->input->post('password')),
						'profile_pic'	=> $profileFileName,
						'verified'	=> '1',
						'cat'		=> _nowDateTime()
					];
					$this->db->insert('customer',$data);
					retJson(['_return' => true,'msg' => 'Sign Up Successful.']);
				}else{
					retJson(['_return' => false,'msg' => 'Email Already Exists.']);
				}
			}else{
				retJson(['_return' => false,'msg' => '`fname`,`lname`,`email`,`phone`,`ccode` and `password` are Required,`profileimg` is Optional']);	
			}	
		}else if($this->input->post('type') && $this->input->post('type') == 'phone'){
			if($this->input->post('phone') && $this->input->post('ccode')){
				$old = $this->db->get_where('customer',['phone' => $this->input->post('phone'),'ccode' => $this->input->post('ccode'),'df' => '','rtype' => 'phone'])->row_array();
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
						$this->db->where('id',$old['id'])->update('customer',$data);
						$otp = generateOtp($old['id'],'customer','register_phone');
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
					$this->db->insert('customer',$data);
					$user = $this->db->insert_id();
					$otp = generateOtp($user,'customer','register_phone');
					retJson(['_return' => true,'msg' => 'Please Verify OTP.','user' => $user,'otp' => $otp]);
				}
			}else{
				retJson(['_return' => false,'msg' => '`phone` and `ccode` are Required']);	
			}
		}else{
			retJson(['_return' => false,'msg' => '`type`(email,phone) is Required']);	
		}
	}
}
