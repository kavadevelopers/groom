<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth
{	

	public $CI;

    function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->database();
        $this->CI->load->helper('url');
    }

	public function check_session()
	{
        if($this->CI->db->get_where('setting',['id' => '1'])->row_array()['cunstruction'] == '0'){
            $admin = $this->CI->session->userdata('id');
            if(!$admin)
            {
            	$this->CI->session->set_flashdata('error', 'Your Session Is Expire Please Login Again.');
                redirect(base_url());
            }
        }else{
            redirect(base_url('welcome/working_on_updates'));
        }

    }

}