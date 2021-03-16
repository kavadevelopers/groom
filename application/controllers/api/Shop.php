<?php
class Shop extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function get_prod_serv_single()
	{
		if($this->input->post('product')){	
			$this->db->where('id',$this->input->post('product'));
			$this->db->where('df','');
			$data = $this->db->get('shop_products')->row_array();
			$images = [];
			foreach ($this->db->get_where('shop_product_image',['product' => $data['id']])->result_array() as $Ikey => $Ivalue) {
				array_push($images,['image' => base_url('uploads/product/').$Ivalue['image']]);
			}
			$data['images'] = $images;

			retJson(['_return' => true,'data' => $data]);
		}else{
			retJson(['_return' => false,'msg' => '`product` is Required']);
		}
	}

	public function get_prod_serv()
	{
		if($this->input->post('shop')){	
			if($this->input->post('start') !== null && $this->input->post('limit')){
				$this->db->limit($this->input->post('limit'), $this->input->post('start'));
			}
			if($this->input->post('type')){
				$this->db->where('type',$this->input->post('type'));	
			}
			$this->db->where('shop',$this->input->post('shop'));
			$this->db->where('df','');
			$list = $this->db->get('shop_products')->result_array();

			foreach ($list as $key => $value) {
				$images = [];
				foreach ($this->db->get_where('shop_product_image',['product' => $value['id']])->result_array() as $Ikey => $Ivalue) {
					array_push($images,['image' => base_url('uploads/product/').$Ivalue['image']]);
				}
				$list[$key]['images'] = $images;
			}

			retJson(['_return' => true,'list' => $list]);
		}else{
			retJson(['_return' => false,'msg' => '`shop` is Required,`type`(service,product),`start`,`limit` are optional']);
		}
	}

	public function create_prod_serv()
	{
		if($this->input->post('user') && $this->input->post('shop') && $this->input->post('type')){	
			if($this->input->post('type') == "service"){
				if($this->input->post('title') && $this->input->post('desc') && $this->input->post('tag') && $this->input->post('duration') && $this->input->post('price') && $this->input->post('images')){
					$data = [
						'shop'		=> $this->input->post('shop'),
						'user'		=> $this->input->post('user'),
						'type'		=> 'service',
						'category'	=> NULL,
						'title'		=> $this->input->post('title'),
						'descr'		=> $this->input->post('desc'),
						'tag'		=> $this->input->post('tag'),
						'duration'	=> $this->input->post('duration'),
						'price'		=> $this->input->post('price'),
						'brand'		=> NULL,
						'size'		=> NULL,
						'cat'		=> _nowDateTime()
					];
					$this->db->insert('shop_products',$data);
					$productId = $this->db->insert_id();

					foreach ($this->input->post('images') as $ikey => $ivalue) {
						$img = $ivalue;
						$img = str_replace('data:image/png;base64,', '', $img);
						$img = str_replace(' ', '+', $img);
						$data = base64_decode($img);
						$file = microtime(true).'.png';
						file_put_contents('./uploads/product/'.$file, $data);		
						$img = [
							'image'		=> $file,
							'product'	=> $productId,
							'user'		=> $this->input->post('user')
						];
						$this->db->insert('shop_product_image',$img);
					}
					retJson(['_return' => true,'msg' => 'Service Created']);
				}else{
					retJson(['_return' => false,'msg' => '`title`,`desc`,`tag`(if multiple add comma saperated),`duration`,`price` and `images` are Required']);		
				}
			}else if($this->input->post('type') == "product"){
				if($this->input->post('title') && $this->input->post('desc') && $this->input->post('tag') && $this->input->post('price') && $this->input->post('images') && $this->input->post('category') && $this->input->post('brand') && $this->input->post('size')){

					$data = [
						'shop'		=> $this->input->post('shop'),
						'user'		=> $this->input->post('user'),
						'type'		=> 'product',
						'category'	=> $this->input->post('category'),
						'title'		=> $this->input->post('title'),
						'descr'		=> $this->input->post('desc'),
						'tag'		=> $this->input->post('tag'),
						'duration'	=> NULL,
						'price'		=> $this->input->post('price'),
						'brand'		=> $this->input->post('brand'),
						'size'		=> $this->input->post('size'),
						'cat'		=> _nowDateTime()
					];
					$this->db->insert('shop_products',$data);
					$productId = $this->db->insert_id();

					foreach ($this->input->post('images') as $ikey => $ivalue) {
						$img = $ivalue;
						$img = str_replace('data:image/png;base64,', '', $img);
						$img = str_replace(' ', '+', $img);
						$data = base64_decode($img);
						$file = microtime(true).'.png';
						file_put_contents('./uploads/product/'.$file, $data);		
						$img = [
							'image'		=> $file,
							'product'	=> $productId,
							'user'		=> $this->input->post('user')
						];
						$this->db->insert('shop_product_image',$img);
					}
					retJson(['_return' => true,'msg' => 'Product Created']);

				}else{
					retJson(['_return' => false,'msg' => '`title`,`desc`,`tag`(if multiple add comma saperated),`price`,`category`,`brand`,`size` and `images` are Required']);		
				}
			}
			else{
				retJson(['_return' => false,'msg' => '`type` not found']);
			}
		}else{
			retJson(['_return' => false,'msg' => '`user`,`type`(product,service) and `shop` are Required']);
		}	
	}

	public function getshop()
	{
		if($this->input->post('shop')){	
			$this->db->where('id',$this->input->post('shop'));
			$data = $this->db->get('shop')->row_array();
			$images = [];
			foreach ($this->db->get_where('shop_images',['shop' => $data['id']])->result_array() as $Ikey => $Ivalue) {
				array_push($images,['image' => base_url('uploads/shop/').$Ivalue['image']]);
			}
			$data['images'] = $images;
			retJson(['_return' => true,'data' => $data]);
		}else{
			retJson(['_return' => false,'msg' => '`shop` are Required']);
		}
	}

	public function getshops()
	{
		if($this->input->post('user')){	
			$this->db->where('user',$this->input->post('user'));
			$this->db->where('df','');
			$list = $this->db->get('shop')->result_array();

			foreach ($list as $key => $value) {
				$images = [];
				foreach ($this->db->get_where('shop_images',['shop' => $value['id']])->result_array() as $Ikey => $Ivalue) {
					array_push($images,['image' => base_url('uploads/shop/').$Ivalue['image']]);
				}
				$list[$key]['images'] = $images;
			}

			retJson(['_return' => true,'list' => $list]);
		}else{
			retJson(['_return' => false,'msg' => '`user` are Required']);
		}
	}

	public function create()
	{
		if($this->input->post('user') && $this->input->post('type') && $this->input->post('address') && $this->input->post('lat') && $this->input->post('lon') && $this->input->post('shop_type')  && $this->input->post('services')  && $this->input->post('desc') && $this->input->post('images')){

			$data = [
				'shopid'			=> $this->general_model->getShopId(),
				'user'				=> $this->input->post('user'),
				'type'				=> $this->input->post('type'),
				'address'			=> $this->input->post('address'),
				'lat'				=> roundLatLon($this->input->post('lat')),
				'lon'				=> roundLatLon($this->input->post('lon')),
				'shop_type'			=> $this->input->post('shop_type'),
				'services'			=> $this->input->post('services'),
				'descr'				=> $this->input->post('descr'),
				'df'				=> '',
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