<?php
class Rights extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function check($rights)
	{
	    if($this->session->userdata('id') != '1'){
	        $counter = 0; 
	        $user = $this->db->get_where('user',['id' => $this->session->userdata('id')])->result_array()[0];
	        foreach (explode(',',$user['rights']) as $key => $value) {
	            if(in_array($value, $rights)){
	                $counter++;
	            }
	        }

	        if($counter > 0)
	        {
	            return true;
	        }
	        else{
	            return false;
	        }
	    }
	    else{
	        return true;
	    }
	}

	public function redirect($rights)
	{
		if(!$this->check($rights)){
			redirect(base_url('error404'));
		}
	}
}