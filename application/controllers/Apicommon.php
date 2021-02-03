<?php
class Apicommon extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function check_latlon($id)
	{
		echo "<pre>";
		print_r(getDeliveryNear($id));
	}

	public function check_latlon_customer($lat,$lon)
	{
		echo "<pre>";
		$coords = $this->db->get_where('areas',['id' => '3'])->row_array();
		print_r($this->polygon->checkSinglePoligon($lat,$lon,$coords['latlon']));
		exit;
		print_r(checkMultiPoligon($lat,$lon));
	}

	public function testpush($id)
	{
		sendPush(
			[get_customer($id)['token']],
			"Order #",
			"Item Dropped By Driver.",
			"order"
		);
	}

	public function change_order_price()
	{
		if($this->input->post('order_id') && $this->input->post('price')){
			$this->db->where('id',$this->input->post('order_id'))->update('corder',
				['price' => $this->input->post('price')]
			);
			retJson(['_return' => true,'msg' => 'Price Changed']);
		}else{
			retJson(['_return' => false,'msg' => '`order_id` and `price` are Required']);
		}
	}

	public function chatPush()
	{
		if($this->input->post('sender_id') && $this->input->post('receiver_id') && $this->input->post('sender_type') && $this->input->post('receiver_type') && $this->input->post('msg') && $this->input->post('order_id')){	
			
			if($this->input->post('sender_type') == "customer"){
				if($this->input->post('receiver_type') == "delivery"){
					$user = $this->db->get_where('z_delivery',['id' => $this->input->post('receiver_id')])->row_array();
					sendChatPush($user['token'],"New Message",$this->input->post('msg'),$this->input->post('sender_id'),$this->input->post('receiver_id'),$this->input->post('sender_type'),$this->input->post('receiver_type'),$this->input->post('order_id'));
				}

				if($this->input->post('receiver_type') == "service"){
					$user = $this->db->get_where('z_service',['id' => $this->input->post('receiver_id')])->row_array();
					sendChatPush($user['token'],"New Message",$this->input->post('msg'),$this->input->post('sender_id'),$this->input->post('receiver_id'),$this->input->post('sender_type'),$this->input->post('receiver_type'),$this->input->post('order_id'));
				}
			}

			if($this->input->post('sender_type') == "delivery"){
				if($this->input->post('receiver_type') == "customer"){
					$user = $this->db->get_where('z_customer',['id' => $this->input->post('receiver_id')])->row_array();
					sendChatPush($user['token'],"New Message",$this->input->post('msg'),$this->input->post('sender_id'),$this->input->post('receiver_id'),$this->input->post('sender_type'),$this->input->post('receiver_type'),$this->input->post('order_id'));
				}

				if($this->input->post('receiver_type') == "delivery"){
					$user = $this->db->get_where('z_delivery',['id' => $this->input->post('receiver_id')])->row_array();
					sendChatPush($user['token'],"New Message",$this->input->post('msg'),$this->input->post('sender_id'),$this->input->post('receiver_id'),$this->input->post('sender_type'),$this->input->post('receiver_type'),$this->input->post('order_id'));
				}

				if($this->input->post('receiver_type') == "service"){
					$user = $this->db->get_where('z_service',['id' => $this->input->post('receiver_id')])->row_array();
					sendChatPush($user['token'],"New Message",$this->input->post('msg'),$this->input->post('sender_id'),$this->input->post('receiver_id'),$this->input->post('sender_type'),$this->input->post('receiver_type'),$this->input->post('order_id'));
				}
			}

			if($this->input->post('sender_type') == "service"){
				if($this->input->post('receiver_type') == "customer"){
					$user = $this->db->get_where('z_customer',['id' => $this->input->post('receiver_id')])->row_array();
					sendChatPush($user['token'],"New Message",$this->input->post('msg'),$this->input->post('sender_id'),$this->input->post('receiver_id'),$this->input->post('sender_type'),$this->input->post('receiver_type'),$this->input->post('order_id'));
				}

				if($this->input->post('receiver_type') == "delivery"){
					$user = $this->db->get_where('z_delivery',['id' => $this->input->post('receiver_id')])->row_array();
					sendChatPush($user['token'],"New Message",$this->input->post('msg'),$this->input->post('sender_id'),$this->input->post('receiver_id'),$this->input->post('sender_type'),$this->input->post('receiver_type'),$this->input->post('order_id'));
				}

				if($this->input->post('receiver_type') == "service"){
					$user = $this->db->get_where('z_service',['id' => $this->input->post('receiver_id')])->row_array();
					sendChatPush($user['token'],"New Message",$this->input->post('msg'),$this->input->post('sender_id'),$this->input->post('receiver_id'),$this->input->post('sender_type'),$this->input->post('receiver_type'),$this->input->post('order_id'));
				}
			}			

		}else{
			retJson(['_return' => false,'msg' => '`sender_id`,`receiver_id`,`sender_type`,`receiver_type`,`msg` and `order_id` are Required']);
		}	
	}


	public function get_business_categories()
	{
		$where = ['df' => ''];
		if($this->input->post('type')){
			$where = ['df' => '','type' => $this->input->post('type'),'disable' => ""];
		}
		$query = $this->db->get_where('business_categories',$where);
		$list = $query->result_array();
		foreach ($list as $key => $value) {
			$list[$key]['image'] = getCategoryThumb($value['image']);
			$list[$key]['menu'] = getCategoryThumb($value['menu']);
		}
		retJson(['_return' => true,'count' => $query->num_rows(),'list' => $list]);
	}


	public function getsettings()
	{
		$data = [
			'_return' => true,
			'razorepay_key' => get_setting()['razorpay_key'],
			'support_email' => get_setting()['support_email'],
			'support_mobile' => get_setting()['support_mobile'],
			'support_mobile' => get_setting()['support_mobile'],
			'upi_id' 			=> get_setting()['upi_id'],
			'upi_qr' 			=> base_url('uploads/').get_setting()['upi_qr'],
			'cust_ver' 			=> get_setting()['cust_ver'],
			'serv_ver' 			=> get_setting()['serv_ver'],
			'deli_ver' 			=> get_setting()['deli_ver'],
			'icust_ver' 			=> get_setting()['icust_ver'],
			'iserv_ver' 			=> get_setting()['iserv_ver'],
			'ideli_ver' 			=> get_setting()['ideli_ver']
		];

		if($this->input->post('type') && $this->input->post('userid')){
			$user = $this->db->get_where('z_customer',['id' => $this->input->post('userid')])->row_array();
			$service = $this->db->get_where('z_service',['id' => $this->input->post('userid')])->row_array();
			$delivery = $this->db->get_where('z_delivery',['id' => $this->input->post('userid')])->row_array();
			if($this->input->post('type') == "customer" && $user){
				$data['subscription_status'] 	= checkSubscriptionExpiration($user['sub_expired_on']);
				$data['sub_expired_on'] 		= $user['sub_expired_on'];
				$data['token']					= $user['token'];
				$data['free']					= $user['free'];
				$data['current_order']			= getCustomerCurrentOrdersCount($this->input->post('userid'));
				$data['address']				= $this->db->get_where('address',['userid' => $this->input->post('userid')])->row_array();
			}

			if($this->input->post('type') == "delivery" && $delivery){
				$data['token']					= $delivery['token'];
			}

			if($this->input->post('type') == "service" && $service){
				$data['token']					= $service['token'];
			}
		}

		retJson(
			$data
		);
	}

}
