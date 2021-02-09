<?php
class General_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	public function get_setting()
	{
		return $this->db->get_where('setting',['id' => '1'])->row_array();
	}


	public function getCategoryThumb($category)
	{
		$cate = $this->db->get_where('categories',['id' => $category])->row_array();
		if($cate){
			if($cate['image'] != ""){
				if(file_exists(FCPATH.'uploads/category/'.$cate['image'])){
					return base_url('uploads/category/'.$cate['image']);
				}else{
					return base_url('uploads/common/thumbnail.png');
				}
			}else{
				return base_url('uploads/common/thumbnail.png');
			}
		}else{
			return base_url('uploads/common/thumbnail.png');
		}
	}

	public function send_forget_email($name,$to,$otp)
	{
		$msg = $this->load->view('mail/reset_password',['name' => $name,'otp' => $otp],true);
	    $this->load->library('email');
	    $config = array(
	        'protocol'      => 'SMTP',
	        'smtp_host' => get_setting()['mail_host'],
	        'smtp_port' => get_setting()['mail_port'],
	        'smtp_user' => get_setting()['mail_username'],
	        'smtp_pass' => get_setting()['mail_pass'],
	        'mailtype'      => 'html',
	        'charset'       => 'utf-8'
	    );
	    $this->email->initialize($config);
	    $this->email->set_mailtype("html");
	    $this->email->set_newline("\r\n");
	    $this->email->to($to);
	    $this->email->from(get_setting()['mail_username']);
	    $this->email->subject($sub);
	    $this->email->message($msg);
	    if($this->email->send()){
	        //echo "ok";
	    }else{
	        //echo $CI->email->print_debugger();
	    }
	}
}
?>