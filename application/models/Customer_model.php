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
	            if(filter_var($customer['profile_pic'], FILTER_VALIDATE_URL)){
	            	$customer['profile_pic'] = $customer['profile_pic'];
	        	}else{
	        		$customer['profile_pic'] = base_url('uploads/customer/').$customer['profile_pic'];
	        	}
	        }else{
	            $customer['profile_pic'] = base_url('uploads/common/profile.png');
	        }
	    }
	    $customer['usertype']   		= "customer";
	    $customer['address']    		= $this->db->get_where('customer_address',['user' => $id])->row_array();
	    $customer['verified_profile']	= $this->is_verified($id);
	    $customer['occupations']		= $this->getOccupations($customer);
	    $customer['skills']				= $this->getSkills($customer);
	    $customer['educations']			= $this->getEducations($id);

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

	public function getOccupations($customer)
	{
		if($customer['occupations'] != NULL){
			$ar = [];
			foreach (explode(',', $customer['occupations']) as $key => $value) {
				$occ = getOccupations($value);
				array_push($ar, $occ);
			}
			return $ar;
		}else{
			return NULL;
		}
	}

	public function getSkills($customer)
	{
		if($customer['skills'] != NULL){
			$ar = [];
			foreach (explode(',', $customer['skills']) as $key => $value) {
				$occ = getSkills($value);
				array_push($ar, $occ);
			}
			return $ar;
		}else{
			return NULL;
		}
	}

	public function getEducations($id)
	{
		return $this->db->get_where('customer_education',['user' => $id])->result_array();
	}
}