<?php
class Customer_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	public function getCustomerData($id)
	{
	    $customer = $this->db->get_where('customer',['id' => $id])->row_array();
	    if($customer['rtype'] == 'email' || $customer['rtype'] == 'phone'){
	        if($customer['profile_pic'] != "" && $customer['profile_pic'] != NULL){
	            $customer['profile_pic'] = base_url('uploads/customer/').$customer['profile_pic'];
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
	    $customer['usertype']   		= "customer";
	    $customer['address']    		= $this->db->get_where('customer_address',['user' => $id])->row_array();
	    $customer['verified_profile']	= $this->is_verified($id);
	    return $customer;
	}

	public function is_verified($id)
	{
		$verified = $this->db->get_where('customer_verified',['user' => $id,'status' => '1'])->row_array();
		if($verified){
			return "1";
		}else{
			return "0";
		}
	}
}