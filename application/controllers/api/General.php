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
			}else if($this->input->post('page') == "about_groom"){
				$page = $this->db->get_where('pages',['id' => '3'])->row_array();
				retJson(['_return' => true,'page' => $page]);		
			}else if($this->input->post('page') == "sharing"){
				$page = $this->db->get_where('pages',['id' => '4'])->row_array();
				retJson(['_return' => true,'page' => $page]);		
			}else if($this->input->post('page') == "datause"){
				$page = $this->db->get_where('pages',['id' => '5'])->row_array();
				retJson(['_return' => true,'page' => $page]);		
			}else{
				retJson(['_return' => false,'msg' => 'Please Enter Valid Page name']);		
			}
		}else{
			retJson(['_return' => false,'msg' => '`page`(terms,help,about_groom,sharing,datause) is Required']);	
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
								'desc'		=> $this->input->post('desc'),
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
								'desc'		=> $this->input->post('desc'),
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
			else if ($this->input->post('otptype') == 'register') {
				if($this->input->post('usertype') == 'service'){
					if($this->input->post('user') && $this->input->post('otp')){
						$otp = $this->db->get_where('z_otp',['user' => $this->input->post('user'),'otp' => $this->input->post('otp'),'otptype' => 'register_phone','usertype' => 'service','used' => '0'])->row_array();
						if($otp){
							if($this->input->post('fname') && $this->input->post('lname') && $this->input->post('business') && $this->input->post('phone') && $this->input->post('services') && $this->input->post('desc') && isset($_FILES ['profileimg'])){
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
									'firstname'		=> $this->input->post('fname'),
									'lastname'		=> $this->input->post('lname'),
									'business'		=> $this->input->post('business'),
									'services'		=> $this->input->post('services'),
									'descr'			=> $this->input->post('desc'),
									'profile_pic'	=> $profileFileName,
									'verified'		=> '1',
									'cat'			=> _nowDateTime()
								];
								$this->db->where('id',$this->input->post('user'))->update('service_provider',$data);
								$this->db->where('user',$this->input->post('user'))->where('otptype','register_phone')->where('usertype','service')->update('z_otp',['used' => '1']);
								retJson(['_return' => true,'msg' => 'Sign Up Successful.']);
							}else{
								retJson(['_return' => false,'msg' => '`fname`,`lname`,`business`,`phone`,`services`,`profileimg` and `desc` are Required']);	
							}
						}else{
							retJson(['_return' => false,'msg' => 'OTP Not Valid']);		
						}
					}else{
						retJson(['_return' => false,'msg' => '`user`(user_id) and `otp` is Required']);	
					}	
				}else{
					if($this->input->post('user') && $this->input->post('otp')){
						$otp = $this->db->get_where('z_otp',['user' => $this->input->post('user'),'otp' => $this->input->post('otp'),'otptype' => 'register_phone','usertype' => 'customer','used' => '0'])->row_array();
						if($otp){
							if($this->input->post('fname') && $this->input->post('lname')){
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
									'profile_pic'	=> $profileFileName,
									'firstname'	=> $this->input->post('fname'),
									'lastname'	=> $this->input->post('lname'),
									'verified'	=> '1',
									'ver_phone'	=> '1',
									'cat'		=> _nowDateTime()
								];
								$this->db->where('id',$this->input->post('user'))->update('customer',$data);
								$this->db->where('user',$this->input->post('user'))->where('otptype','register_phone')->where('usertype','customer')->update('z_otp',['used' => '1']);
								retJson(['_return' => true,'msg' => 'Sign Up Successful.']);
							}else{
								retJson(['_return' => false,'msg' => '`fname` and `lname` is Required']);		
							}
						}else{
							retJson(['_return' => false,'msg' => 'OTP Not Valid']);		
						}
					}else{
						retJson(['_return' => false,'msg' => '`user`(user_id) and `otp` is Required']);	
					}
				}
			}
		}else{
			retJson(['_return' => false,'msg' => '`otptype` (register,login),`user` (User id),`usertype` (customer,service) and `otp` are Required']);
		}
	}
}