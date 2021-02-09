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
}
?>