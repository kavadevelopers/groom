<?php
class Service_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	public function getServiceData($id)
	{
	    $customer = $this->db->get_where('service_provider',['id' => $id])->row_array();
	    if($customer['rtype'] == 'email' || $customer['rtype'] == 'phone'){
	        if($customer['profile_pic'] != "" && $customer['profile_pic'] != NULL){
	            $customer['profile_pic'] = base_url('uploads/service/').$customer['profile_pic'];
	        }else{
	            $customer['profile_pic'] = base_url('uploads/common/profile.png');
	        }
	    }else{
	        if($customer['profile_pic'] != "" && $customer['profile_pic'] != NULL){
	            $customer['profile_pic'] = $customer['profile_pic'];
	        }else{
	            $customer['profile_pic'] = base_url('uploads/common/profile.png');
	        }
	    }
	    $customer['usertype']   			= "service";
	    $customer['address']    			= $this->db->get_where('service_address',['user' => $id])->row_array();
	    $customer['verified_profile']		= $this->is_verified($id);
	    $customer['other_details']			= $this->db->get_where('service_provider_details',['user' => $id])->row_array();
	    return $customer;
	}

	public function is_verified($id)
	{
		$verified = $this->db->get_where('service_verified',['user' => $id,'status' => '1'])->row_array();
		if($verified){
			return "1";
		}else{
			return "0";
		}
	}

	public function getNotificationSetting($user)
	{
		$old = $this->db->get_where('service_notification',['user' => $user])->row_array();
		if($old){
			return $old;
		}else{
			$data = [
				'm_email'		=> 0,
				'm_text'		=> 0,
				'm_push'		=> 1,
				'r_email'		=> 1,
				'r_text'		=> 1,
				'r_push'		=> 1,
				'pt_email'		=> 1,
				'pt_text'		=> 1,
				'pt_push'		=> 1,
				'pc_email'		=> 1,
				'pc_text'		=> 1,
				'pc_push'		=> 1
			];
			return $data;
		}
	}
}