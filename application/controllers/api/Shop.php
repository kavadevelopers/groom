<?php
class Shop extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function create()
	{
		if($this->input->post('user') && $this->input->post('type') && $this->input->post('address') && $this->input->post('lat') && $this->input->post('lon') && $this->input->post('shop_type')  && $this->input->post('services')  && $this->input->post('desc') && $this->input->post('images')){

			$data = [
				'shopid'			=> $this->general_model->getShopId(),
				'user'				=> $this->input->post('user'),
				'type'				=> $this->input->post('type'),
				'address'			=> $this->input->post('address'),
				'lat'				=> $this->input->post('lat'),
				'lon'				=> $this->input->post('lon'),
				'shop_type'			=> $this->input->post('shop_type'),
				'services'			=> $this->input->post('services'),
				'descr'				=> $this->input->post('descr'),
				'cat'				=> _nowDateTime()
			];
			$this->db->insert('shop',$data);
			$shopId = $this->db->insert_id();
			foreach ($this->input->post('images') as $ikey => $ivalue) {
				$img = $ivalue;
				$img = str_replace('data:image/png;base64,', '', $img);
				$img = str_replace(' ', '+', $img);
				$data = base64_decode($img);
				$file = microtime(true).'.png';
				file_put_contents('./uploads/shop/'.$file, $data);		
				$img = [
					'image'		=> $file,
					'shop'		=> $shopId,
					'user'		=> $this->input->post('user')
				];
				$this->db->insert('shop_images',$img);
			}

			retJson(['_return' => true,'msg' => 'Shop Created']);

		}else{
			retJson(['_return' => false,'msg' => '`user`,`type`(retail),`address`,`lat`,`lon`,`shop_type`(products,services,both),`services`(if multiple add comma saprated(1,2,3)),`desc` and `images` (base64 images index like `images[]`) are Required']);
		}
	}
}