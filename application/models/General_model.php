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
}
?>