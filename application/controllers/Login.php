<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function index()
	{
		if($this->input->post("user")){
			$this->load->helper('cookie');

			$user = trim($this->input->post("user"));
			$pass = trim($this->input->post("pass"));
			$this->load->model('login_model');
			$return = $this->login_model->login_Ath( $user , md5($pass));
	 
			if($return[0] == 0){
				$this->session->set_userdata( array( 'id' => $return[2],'user_type'=>$return[3]) );
				
				if($this->input->post("check") == '1'){

		    		$this->input->set_cookie(array("name" => "username", "value" => $user, "expire" => time()+(60*60*24*30))); 
		    		$this->input->set_cookie(array("name" => "password", "value" => $pass, "expire" => time()+(60*60*24*30)));

		    	}
		    	else
		    	{
		    		delete_cookie("username");
		    		delete_cookie("password");
		    	}
			}
			echo json_encode($return);
		}else{
			redirect(base_url());
		}
	}

	public function logout()
	{
	    $user_data = $this->session->all_userdata();
	        
	        
	    $this->session->unset_userdata($user_data['id']);
	           
	        
	    $this->session->sess_destroy();
	    redirect(base_url());
	}
}