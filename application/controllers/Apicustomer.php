<?php
class Apicustomer extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function get_business_categories_by_location()
	{	
		if($this->input->post('userid') && $this->input->post('type')){
			$address = $this->db->get_where('address',['userid' => $this->input->post('userid')])->row_array();
			if(checkMultiPoligon($address['latitude'], $address['longitude'])[0]){
				$services = explode(',',checkMultiPoligon($address['latitude'], $address['longitude'])[2]);
				$this->db->distinct();
				$this->db->select('category');
				$this->db->where_in('id', $services);
				$dis_cats = $this->db->get('z_service')->result_array();
				$disCats = [];
				foreach ($dis_cats as $key => $value) { array_push($disCats, $value['category']); } 

				$bacats = $this->db->where('type',$this->input->post('type'))->where('df','')->where('disable','')->where_in('id',$disCats)->get('business_categories');
				$list = $bacats->result_array();
				foreach ($list as $key => $value) {
					$list[$key]['image'] = getCategoryThumb($value['image']);
					$list[$key]['menu'] = getCategoryThumb($value['menu']);
				}
				retJson(['_return' => true,'count' => $bacats->num_rows(),'list' => $list]);
			}else{
				retJson(['_return' => true,'count' => 0]);
			}	
		}else{
			retJson(['_return' => false,'msg' => '`userid` and `type` is Required']);
		}
	}

	public function order_review()
	{
		if($this->input->post('userid') && $this->input->post('orderid') && $this->input->post('rating')){
			$desc = "";
			if($this->input->post('description')){
				$desc = $this->input->post('description');
			}
			$data = [
				'userid'		=> $this->input->post('description'),
				'orderid'		=> $this->input->post('orderid'),
				'rating'		=> $this->input->post('rating'),
				'description'	=> $desc,
				'created_at'	=> date('Y-m-d H:i:s')
			];
			$this->db->insert('corder_review',$data);
			$this->db->where('id',$this->input->post('orderid'))->update('corder',['rating' => '1']);
			retJson(['_return' => true,'msg' => 'Review has been submitted']);

		}else{
			retJson(['_return' => false,'msg' => '`userid`,`orderid` and `rating` are Required. `description`(optional)']);
		}
	}

	public function reject_alignment()
	{
		if($this->input->post('order_id') && $this->input->post('userid')){	
			$order = $this->db->get_where('corder',['id' => $this->input->post('order_id')])->row_array();

			$this->db->where('id',$this->input->post('order_id'))->update('corder',
				['status_desc' => 'Rejected By Customer','notes' => 'Rejected By Customer','status' => 'ongoing']
			);

			if($order['done_driver1'] == 'yes'){
				$cus = get_customer(get_order($this->input->post('order_id'))['userid'])['id'];
				if(getDeliveryNear($cus)[0]){
					$delivery_boy = getDeliveryNear($cus)[1];
					$this->db->where('id',$this->input->post('order_id'))->update('corder',
						['driver2' => $delivery_boy]
					);	
					sendPush(
						[get_delivery($delivery_boy)['token']],
						"Order #".get_order($this->input->post('order_id'))['order_id'],
						"Drop Item At Customer Location.",
						"order",
						$this->input->post('order_id')
					);	
				}
			}else{
				sendPush(
					[get_delivery(get_order($this->input->post('order_id'))['driver'])['token']],
					"Order #".get_order($this->input->post('order_id'))['order_id'],
					"Drop Item At Customer Location.",
					"order",
					$this->input->post('order_id')
				);
			}

		}else{
			retJson(['_return' => false,'msg' => '`order_id` and `userid` are Required']);
		}
	}

	public function extend_subscription()
	{
		if($this->input->post('userid') && $this->input->post('price') && $this->input->post('plan_name') && $this->input->post('month') && $this->input->post('tra_id')){	
			$data = [
				'userid'		=> $this->input->post('userid'),
				'price'			=> $this->input->post('price'),
				'plan_name'		=> $this->input->post('plan_name'),
				'month'			=> $this->input->post('month'),
				'tra_id'		=> $this->input->post('tra_id'),
				'created_at'	=> date('Y-m-d H:i:s')
			];
			$this->db->insert('extend_subscription',$data);
			$subId = $this->db->insert_id();
			$expireDate = date('Y-m-d',strtotime("+".$this->input->post('month')." month",strtotime(date('Y-m-d'))));
			$data = [
				'sub_expired_on'	=> $expireDate
			];
			$this->db->where('id',$this->input->post('userid'))->update('z_customer',$data);


			@addTransaction(
				'subscription',
				'razorpay',
				$this->input->post('price'),
				0.00,
				0,
				$this->input->post('userid'),
				$subId,
				date('Y-m-d')
			);

			retJson(['_return' => true,'msg' => 'Subscription Extended To '.vfd($expireDate)]);
		}else{
			retJson(['_return' => false,'msg' => '`userid`,`price`,`plan_name`,`tra_id` and `month` are Required']);
		}	
	}

	public function order_support()
	{
		if($this->input->post('order_id') && $this->input->post('user_id') && $this->input->post('name') && $this->input->post('email') && $this->input->post('subject') && $this->input->post('message')){	
			$data = [
				'user'			=> $this->input->post('user_id'),
				'orderid'		=> $this->input->post('order_id'),
				'name'			=> $this->input->post('name'),
				'email'			=> $this->input->post('email'),
				'subject'		=> $this->input->post('subject'),
				'message'		=> $this->input->post('message'),
				'created_at'	=> date('Y-m-d H:i:s')
			];
			$this->db->insert('order_support',$data);
			retJson(['_return' => true,'msg' => 'Request Sent.']);
		}else{
			retJson(['_return' => false,'msg' => '`order_id`,`name`,`email`,`subject`,`message` and `user_id` are Required']);
		}
	}

	public function pay_alignment_order()
	{
		if($this->input->post('order_id') && $this->input->post('userid')){	
			$tra_id = ""; $payment_gateway = ""; $payment_type = "";
			if($this->input->post('tra_id')){
				$tra_id = $this->input->post('tra_id');
			}

			if($this->input->post('payment_gateway')){
				$payment_gateway = $this->input->post('payment_gateway');
			}

			if($this->input->post('payment_type')){
				$payment_type = $this->input->post('payment_type');
			}

			$this->db->where('id',$this->input->post('order_id'))->update('corder',
				['status_desc' => 'Paid By Customer','notes' => 'Completed','status' => 'completed','done_driver1' => 'yes','done_driver2' => 'yes','tra_id' => $tra_id,'payment_gateway' => $payment_gateway,'payment_type' => $payment_type]
			);

			$orderRow = get_order($this->input->post('order_id'));
			@addTransaction(
				'order',
				$payment_gateway,
				$orderRow['price'],
				0.00,
				0,
				$this->input->post('userid'),
				$this->input->post('order_id'),
				date('Y-m-d')
			);

			sendPush(
				[get_customer(get_order($this->input->post('order_id'))['userid'])['token']],
				"Order #".get_order($this->input->post('order_id'))['order_id'],
				"Payment Received.",
				"order",
				$this->input->post('order_id')
			);

			sendPush(
				[get_service(get_order($this->input->post('order_id'))['service'])['token']],
				"Order #".get_order($this->input->post('order_id'))['order_id'],
				"Payment Received.",
				"order",
				$this->input->post('order_id')
			);

			sendPush(
				[get_delivery(get_order($this->input->post('order_id'))['driver'])['token']],
				"Order #".get_order($this->input->post('order_id'))['order_id'],
				"Payment Received.",
				"order",
				$this->input->post('order_id')
			);

			if(get_order($this->input->post('order_id'))['driver2'] != ""){
				sendPush(
					[get_delivery(get_order($this->input->post('order_id'))['driver2'])['token']],
					"Order #".get_order($this->input->post('order_id'))['order_id'],
					"Payment Received.",
					"order",
					$this->input->post('order_id')
				);
			}

			retJson(['_return' => true,'msg' => 'Status Changed']);
		}else{
			retJson(['_return' => false,'msg' => '`order_id`,`tra_id`,`payment_gateway`,`payment_type` and `userid` are Required']);
		}
	}

	public function confirm_alignment_price()
	{
		if($this->input->post('order_id') && $this->input->post('userid')){	
			$this->db->where('id',$this->input->post('order_id'))->update('corder',
				['status_desc' => 'Work In Progress','notes' => 'Work In Progress','status' => 'ongoing']
			);

			sendPush(
				[get_service(get_order($this->input->post('order_id'))['service'])['token']],
				"Order #".get_order($this->input->post('order_id'))['order_id'],
				"Price Accepted By Customer. Now Start Work",
				"order",
				$this->input->post('order_id')
			);


			retJson(['_return' => true,'msg' => 'Status Changed.']);
		}else{
			retJson(['_return' => false,'msg' => '`order_id` and `userid` are Required']);
		}
	}

	public function pay_service_order()
	{
		if($this->input->post('order_id') && $this->input->post('userid')){	

			$tra_id = ""; $payment_gateway = ""; $payment_type = "";
			if($this->input->post('tra_id')){
				$tra_id = $this->input->post('tra_id');
			}

			if($this->input->post('payment_gateway')){
				$payment_gateway = $this->input->post('payment_gateway');
			}

			if($this->input->post('payment_type')){
				$payment_type = $this->input->post('payment_type');
			}


			$this->db->where('id',$this->input->post('order_id'))->update('corder',
				['status_desc' => 'Paid By Customer','notes' => 'Order completed','status' => 'completed','tra_id' => $tra_id,'payment_gateway' => $payment_gateway,'payment_type' => $payment_type]
			);

			$orderRow = get_order($this->input->post('order_id'));
			@addTransaction(
				'order',
				$payment_gateway,
				$orderRow['price'],
				0.00,
				0,
				$this->input->post('userid'),
				$this->input->post('order_id'),
				date('Y-m-d')
			);

			sendPush(
				[get_service(get_order($this->input->post('order_id'))['service'])['token']],
				"Order #".get_order($this->input->post('order_id'))['order_id'],
				"Payment Successful.",
				"order",
				$this->input->post('order_id')
			);

			sendPush(
				[get_customer(get_order($this->input->post('order_id'))['userid'])['token']],
				"Order #".get_order($this->input->post('order_id'))['order_id'],
				"Payment Successful. Thankyou",
				"order",
				$this->input->post('order_id')
			);

			retJson(['_return' => true,'msg' => 'Order Completed.']);
		}else{
			retJson(['_return' => false,'msg' => '`order_id`,`tra_id`,`payment_gateway`,`payment_type` and `userid` are Required']);
		}
	}

	public function accept_reject_service_order_pricing()
	{
		if($this->input->post('order_id') && $this->input->post('userid') && $this->input->post('type')){
			if($this->input->post('type') == 'accept'){
				$this->db->where('id',$this->input->post('order_id'))->update('corder',
					['status_desc' => 'Work In Progress','notes' => 'Price Accepted By Customer']
				);

				sendPush(
					[get_service(get_order($this->input->post('order_id'))['service'])['token']],
					"Order #".get_order($this->input->post('order_id'))['order_id'],
					"Order Accepted By Customer",
					"order",
					$this->input->post('order_id')
				);

				retJson(['_return' => true,'msg' => 'Order Accepted.']);
			}else{


				sendPush(
					[get_service(get_order($this->input->post('order_id'))['service'])['token']],
					"Order #".get_order($this->input->post('order_id'))['order_id'],
					"Order Canceled By Customer",
					"order",
					$this->input->post('order_id')
				);

				$this->db->where('id',$this->input->post('order_id'))->update('corder',
					['status' => 'completed','status_desc' => 'Canceled By Customer.','cancel' => 'canceled','notes' => 'Canceled']
				);
				retJson(['_return' => true,'msg' => 'Order Canceled.']);
			}
		}else{
			retJson(['_return' => false,'msg' => '`type` = (`accept`,`reject`),`order_id` and `userid` are Required']);
		}
	}

	public function cancel_order()
	{
		if($this->input->post('order_id') && $this->input->post('userid')){
			$single = $this->db->get_where('corder',['id' => $this->input->post('order_id'),'userid' => $this->input->post('userid')])->row_array();
			if($single){
				$this->db->where('id',$this->input->post('order_id'))->update('corder',
					['status' => 'completed','status_desc' => 'Canceled By Customer.','cancel' => 'canceled','notes' => 'Canceled']
				);

				$order = get_order($this->input->post('order_id'));
				if($order['service'] != ""){
					sendPush(
						[get_service(get_order($this->input->post('order_id'))['service'])['token']],
						"Order #".get_order($this->input->post('order_id'))['order_id'],
						"Order Canceled By Customer.",
						"order",
						$this->input->post('order_id')
					);
				}

				if($order['driver'] != ""){
					sendPush(
						[get_delivery(get_order($this->input->post('order_id'))['driver'])['token']],
						"Order #".get_order($this->input->post('order_id'))['order_id'],
						"Order Canceled By Customer.",
						"order",
						$this->input->post('order_id')
					);
				}

				if($order['driver2'] != ""){
					sendPush(
						[get_delivery(get_order($this->input->post('order_id'))['driver2'])['token']],
						"Order #".get_order($this->input->post('order_id'))['order_id'],
						"Order Canceled By Customer.",
						"order",
						$this->input->post('order_id')
					);
				}

				retJson(['_return' => true,'msg' => 'Order Canceled.']);
			}else{
				retJson(['_return' => false,'msg' => 'Please Enter Valid Order Id']);
			}
		}else{
			retJson(['_return' => false,'msg' => '`order_id` and `userid` are Required']);
		}
	}

	public function accept_order()
	{
		if($this->input->post('type') && $this->input->post('order_id') && $this->input->post('userid')){
			if($this->input->post('type') == 'accept'){


				$tra_id = ""; $payment_gateway = ""; $payment_type = "";
				if($this->input->post('tra_id')){
					$tra_id = $this->input->post('tra_id');
				}

				if($this->input->post('payment_gateway')){
					$payment_gateway = $this->input->post('payment_gateway');
				}

				if($this->input->post('payment_type')){
					$payment_type = $this->input->post('payment_type');
				}

				$this->db->where('id',$this->input->post('order_id'))->update('corder',
					['status' => 'ongoing','status_desc' => 'Accepted By Customer.','notes' => 'Packaging','tra_id' => $tra_id,'payment_gateway' => $payment_gateway,'payment_type' => $payment_type]
				);

				$orderRow = get_order($this->input->post('order_id'));
				@addTransaction(
					'order',
					$payment_gateway,
					$orderRow['price'],
					0.00,
					0,
					$this->input->post('userid'),
					$this->input->post('order_id'),
					date('Y-m-d')
				);
				
				$cus = get_customer(get_order($this->input->post('order_id'))['userid'])['id'];
				if(getDeliveryNear($cus)[0]){
					$delivery_boy = getDeliveryNear($cus)[1];
					$this->db->where('id',$this->input->post('order_id'))->update('corder',
						['driver' => $delivery_boy]
					);	
					sendPush(
						[get_delivery($delivery_boy)['token']],
						"Order #".get_order($this->input->post('order_id'))['order_id'],
						"New Delivery Request",
						"order",
						$this->input->post('order_id')
					);	
				}

				sendPush(
					[get_service(get_order($this->input->post('order_id'))['service'])['token']],
					"Order #".get_order($this->input->post('order_id'))['order_id'],
					"Order Accepted By Customer",
					"order",
					$this->input->post('order_id')
				);

				retJson(['_return' => true,'msg' => 'Order Accepted.']);	
			}else if($this->input->post('type') == 'reject'){

				sendPush(
					[get_service(get_order($this->input->post('order_id'))['service'])['token']],
					"Order #".get_order($this->input->post('order_id'))['order_id'],
					"Order Rejected By Customer",
					"order",
					""
				);

				$this->db->where('id',$this->input->post('order_id'))->update('corder',
					['status' => 'upcoming','status_desc' => 'Order Placed.','price' => '0.00','service' => '','notes' => 'Pending']
				);
				retJson(['_return' => true,'msg' => 'Order Rejected.']);	
			}else{
				retJson(['_return' => false,'msg' => '`type` = (`accept`,`reject`) Please Enter Valid Type']);	
			}
		}else{
			retJson(['_return' => false,'msg' => '`type` = (`accept`,`reject`),`order_id`,`tra_id`,`payment_gateway`,`payment_type` and `userid` are Required']);
		}
	}

	public function getorder()
	{
		if($this->input->post('order_id')){	
			$single = $this->db->get_where('corder',['id' => $this->input->post('order_id')])->row_array();
			if($single){
				$customer = $this->db->get_where('z_customer',['id' => $single['userid']])->row_array();
				$address = $this->db->get_where('address',['userid' => $single['userid']])->row_array();
				$reviews = $this->db->get_where('corder_review',['orderid' => $single['id']])->row_array();
				$single['customer_name'] = $customer['fname'].' '.$customer['lname'];
				$single['address']	= $address;
				$images = $this->db->get_where('corder_images',['order_id' => $single['id']])->result_array();
				foreach ($images as $imageskey => $imagesvalue) {
					$images[$imageskey]['image']	= base_url('uploads/order/').$imagesvalue['image'];
				}
				$single['images']			=	$images;
				$single['review']			=	$reviews;

				$service = $this->db->get_where('z_service',['id' => $single['service']])->row_array();
				$single['service_id']				=	$single['service'];
				if($service){
					$single['service']		  	= $service['fname'].' '.$service['lname'];
					$serviceLatLon = $this->db->get_where('service_latlon',['user' => $single['service']])->row_array();
					if($serviceLatLon){
						$single['service_lat']		= $serviceLatLon['lat'];
						$single['service_lon']		= $serviceLatLon['lon'];
					}else{
						$single['service_lat']		= null;
						$single['service_lon']		= null;
					}
				}else{
					$service['service'] 		= "";
				}
				$single['delivery1_data']			= $this->db->get_where('z_delivery',['id' => $single['driver']])->row_array();
				$single['delivery2_data']			= $this->db->get_where('z_delivery',['id' => $single['driver2']])->row_array();
				$single['service_data']				= $this->db->get_where('z_service',['id' => $single['service']])->row_array();
				$single['customer_data']			= $this->db->get_where('z_customer',['id' => $single['userid']])->row_array();
				
				retJson(['_return' => true,'data' => $single]);				
			}else{
				retJson(['_return' => false,'msg' => 'Please Enter Valid Order Id']);
			}
		}else{
			retJson(['_return' => false,'msg' => '`order_id` is Required']);
		}
	}

	public function getorders()
	{
		if($this->input->post('userid') && $this->input->post('status')){
			if($this->input->post('status') == 'current'){
				$where = ['status !=' => 'completed','userid' => $this->input->post('userid'),'df' => ''];
			}else{
				$where = ['status' => 'completed','userid' => $this->input->post('userid'),'df' => ''];
			}
			$list = $this->db->order_by('id','desc')->get_where('corder',$where);
			$nlist = $list->result_array();
			foreach ($list->result_array() as $key => $value) {
				$customer = $this->db->get_where('z_customer',['id' => $value['userid']])->row_array();
				$service = $this->db->get_where('z_service',['id' => $value['service']])->row_array();
				$address = $this->db->get_where('address',['userid' => $value['userid']])->row_array();
				$reviews = $this->db->get_where('corder_review',['orderid' => $value['id']])->row_array();
				$images = $this->db->get_where('corder_images',['order_id' => $value['id']])->result_array();
				$nlist[$key]['customer_name'] = $customer['fname'].' '.$customer['lname'];
				$nlist[$key]['address']		  = $address;
				if($service){
					$nlist[$key]['service']		  	= $service['fname'].' '.$service['lname'];
				}else{
					$nlist[$key]['service'] 		= "";
				}
				foreach ($images as $imageskey => $imagesvalue) {
					$images[$imageskey]['image']	= base_url('uploads/order/').$imagesvalue['image'];
				}
				$nlist[$key]['images']			=	$images;
				$nlist[$key]['review']			=	$reviews;
			}
			retJson(['_return' => true,'count' => $list->num_rows(),'list' => $nlist]);
		}else{
			retJson(['_return' => false,'msg' => '`status` = (`current`,`past`) and `userid` are Required']);
		}
	}

	public function order()
	{
		if($this->input->post('userid') && $this->input->post('category') && $this->input->post('type')){
			// $servicesCount = $this->db->get_where('z_service',['category' => $this->input->post('category'),"verified" => 'Verified','approved' => '1','block' => '','active' => '1','token !=' => '','df' => ''])->num_rows();
			$servicesCount = serviceOnlineCount($this->input->post('userid'),$this->input->post('category'));
			$deliveryCount = 0;
			if(getDeliveryNear($this->input->post('userid'))[0])
			{
				$deliveryCount = 1;
			}
			if($this->input->post('order_type') != "later" && $this->input->post('type') == "delivery" && $servicesCount == 0){
				retJson(['_return' => false,'msg' => 'No Shop online at this time']);	
			}else if($this->input->post('order_type') != "later" && $this->input->post('type') == "delivery" && $deliveryCount == 0){
				retJson(['_return' => false,'msg' => 'No Driver online at this time']);	
			}else if($this->input->post('order_type') != "later" && $this->input->post('type') == "service" && $servicesCount == 0){
				retJson(['_return' => false,'msg' => 'No Service Provider online at this time']);	
			}else if($this->input->post('order_type') != "later" && $this->input->post('type') == "alignment" && $servicesCount == 0){
				retJson(['_return' => false,'msg' => 'No Alignment online at this time']);	
			}else if($this->input->post('order_type') != "later" && $this->input->post('type') == "alignment" && $deliveryCount == 0){
				retJson(['_return' => false,'msg' => 'No Driver online at this time']);	
			}else{

				$order_type = "";
				if($this->input->post('order_type')){
					$order_type = $this->input->post('order_type');
				}

				$delivery_date = "";
				if($this->input->post('delivery_date')){
					$delivery_date = $this->input->post('delivery_date');
				}

				$desc = "";
				if($this->input->post('desc')){
					$desc = $this->input->post('desc');
				}
				$last_id = $this->db->order_by('id','desc')->limit(1)->get('corder')->row_array();
				if($last_id){
					$order_id = mt_rand(10000000, 99999999).($last_id['id'] + 1);
				}else{
					$order_id = mt_rand(10000000, 99999999).'1';
				}
				$data = [
					'userid'		=> $this->input->post('userid'),
					'order_id'		=> $order_id,
					'type'			=> $this->input->post('type'),
					'category'		=> $this->input->post('category'),
					'descr'			=> $desc,
					'status'		=> 'upcoming',
					'status_desc'	=> 'Order Placed',
					'notes'			=> 'Pending',
					'order_type'	=> $order_type,
					'delivery_date'	=> $delivery_date,
					'created_at'	=> date('Y-m-d H:i:s')
				];
				$this->db->insert('corder',$data);
				$or_id = $this->db->insert_id();

				$config['upload_path'] = './uploads/order/';
			    $config['allowed_types']	= '*';
			    $config['max_size']      = '0';
			    $config['overwrite']     = FALSE;
			    $this->load->library('upload', $config);
				if(isset($_FILES ['img1']) && $_FILES['img1']['error'] == 0){
					$img1 = microtime(true).".".pathinfo($_FILES['img1']['name'], PATHINFO_EXTENSION);
					$config['file_name'] = $img1;
			    	$this->upload->initialize($config);
			    	if($this->upload->do_upload('img1')){
			    		$this->db->insert('corder_images',['order_id' => $or_id,'name' => 'img1','image' => $img1]);
			    	}
				}

				$config['upload_path'] = './uploads/order/';
			    $config['allowed_types']	= '*';
			    $config['max_size']      = '0';
			    $config['overwrite']     = FALSE;
			    $this->load->library('upload', $config);
				if(isset($_FILES ['img2']) && $_FILES['img2']['error'] == 0){
					$img2 = microtime(true).".".pathinfo($_FILES['img2']['name'], PATHINFO_EXTENSION);
					$config['file_name'] = $img2;
			    	$this->upload->initialize($config);
			    	if($this->upload->do_upload('img2')){
			    		$this->db->insert('corder_images',['order_id' => $or_id,'name' => 'img2','image' => $img2]);
			    	}
				}

				$config['upload_path'] = './uploads/order/';
			    $config['allowed_types']	= '*';
			    $config['max_size']      = '0';
			    $config['overwrite']     = FALSE;
			    $this->load->library('upload', $config);
				if(isset($_FILES ['img3']) && $_FILES['img3']['error'] == 0){
					$img3 = microtime(true).".".pathinfo($_FILES['img3']['name'], PATHINFO_EXTENSION);
					$config['file_name'] = $img3;
			    	$this->upload->initialize($config);
			    	if($this->upload->do_upload('img3')){
			    		$this->db->insert('corder_images',['order_id' => $or_id,'name' => 'img3','image' => $img3]);
			    	}
				}

				$config['upload_path'] = './uploads/order/';
			    $config['allowed_types']	= '*';
			    $config['max_size']      = '0';
			    $config['overwrite']     = FALSE;
			    $this->load->library('upload', $config);
				if(isset($_FILES ['img4']) && $_FILES['img4']['error'] == 0){
					$img4 = microtime(true).".".pathinfo($_FILES['img4']['name'], PATHINFO_EXTENSION);
					$config['file_name'] = $img4;
			    	$this->upload->initialize($config);
			    	if($this->upload->do_upload('img4')){
			    		$this->db->insert('corder_images',['order_id' => $or_id,'name' => 'img4','image' => $img4]);
			    	}
				}

				$config['upload_path'] = './uploads/order/';
			    $config['allowed_types']	= '*';
			    $config['max_size']      = '0';
			    $config['overwrite']     = FALSE;
			    $this->load->library('upload', $config);
				if(isset($_FILES ['img5']) && $_FILES['img5']['error'] == 0){
					$img5 = microtime(true).".".pathinfo($_FILES['img5']['name'], PATHINFO_EXTENSION);
					$config['file_name'] = $img5;
			    	$this->upload->initialize($config);
			    	if($this->upload->do_upload('img5')){
			    		$this->db->insert('corder_images',['order_id' => $or_id,'name' => 'img5','image' => $img5]);
			    	}
				}

				$config['upload_path'] = './uploads/order/';
			    $config['allowed_types']	= '*';
			    $config['max_size']      = '0';
			    $config['overwrite']     = FALSE;
			    $this->load->library('upload', $config);
				if(isset($_FILES ['img6']) && $_FILES['img6']['error'] == 0){
					$img6 = microtime(true).".".pathinfo($_FILES['img6']['name'], PATHINFO_EXTENSION);
					$config['file_name'] = $img6;
			    	$this->upload->initialize($config);
			    	if($this->upload->do_upload('img6')){
			    		$this->db->insert('corder_images',['order_id' => $or_id,'name' => 'img6','image' => $img6]);
			    	}
				}

				$services = $this->db->get_where('z_service',['category' => $this->input->post('category'),"verified" => 'Verified','approved' => '1','block' => '','active' => '1'])->result_array();
				$tokens = [];
				foreach ($services as $key => $value) {
					array_push($tokens, $value['token']);
				}
				sendPush($tokens,"Order #".$order_id,"New Order Arrived","order",$or_id);



				@sendEmail(
					get_setting()['admin_receive_email'],
					"Order Placed : #".$order_id,
					$this->load->view('mail/admin_new_order',['order' => $or_id],true)
				);
				retJson(['_return' => true,'msg' => 'Order Placed.','order' => $order_id,'order_id' => $or_id]);
			}
		}else{
			retJson(['_return' => false,'msg' => '`userid`,`category` and `type` are Required']);
		}
	}

	public function save_address()
	{
		if($this->input->post('userid') && $this->input->post('flat_no') && $this->input->post('street_no') && $this->input->post('address_line') && $this->input->post('latitude') && $this->input->post('longitude')){
			if(checkMultiPoligon($this->input->post('latitude'), $this->input->post('longitude'))[0]){
				$old = $this->db->get_where('address',['userid' => $this->input->post('userid')])->num_rows();
				if($old > 0){
					$data = [
						'flat_no'		=> $this->input->post('flat_no'),
						'street_no'		=> $this->input->post('street_no'),
						'address_line'	=> $this->input->post('address_line'),
						'latitude'		=> $this->input->post('latitude'),
						'longitude'		=> $this->input->post('longitude')
					];	
					$this->db->where('userid',$this->input->post('userid'))->update('address',$data);
					retJson(['_return' => true,'msg' => 'Address Updated.']);
				}else{
					$data = [
						'userid'		=> $this->input->post('userid'),
						'flat_no'		=> $this->input->post('flat_no'),
						'street_no'		=> $this->input->post('street_no'),
						'address_line'	=> $this->input->post('address_line'),
						'latitude'		=> $this->input->post('latitude'),
						'longitude'		=> $this->input->post('longitude')
					];	
					$this->db->insert('address',$data);
					retJson(['_return' => true,'msg' => 'Address Saved.']);
				}
			}else{
				retJson(['_return' => false,'msg' => 'We are not providing service in this area.']);	
			}
		}else{
			retJson(['_return' => false,'msg' => '`userid`,`flat_no`,`street_no`,`address_line`,`latitude` and `longitude` are Required']);
		}
	}

	public function getbanner()
	{
		$query = $this->db->get_where('banner');
		$list = $query->result_array();
		foreach ($list as $key => $value) {
			$list[$key]['image'] = base_url('uploads/banner/').$value['image'];
		}
		retJson(['_return' => true,'count' => $query->num_rows(),'list' => $list]);
	}

	public function edit_profile()
	{
		if($this->input->post('userid') && $this->input->post('fname') && $this->input->post('lname') && $this->input->post('gender')){
			$user = $this->db->get_where('z_customer',['id' => $this->input->post('userid')])->row_array();
			if($user){
				$data = [
					'fname'		=> $this->input->post('fname'),
					'lname'		=> $this->input->post('lname'),
					'gender'	=> $this->input->post('gender')
				];
				$this->db->where('id',$this->input->post('userid'))->update('z_customer',$data);

				$config['upload_path'] = './uploads/user/';
			    $config['allowed_types']	= '*';
			    $config['max_size']      = '0';
			    $config['overwrite']     = FALSE;
			    $this->load->library('upload', $config);
			    if (isset ( $_FILES ['image'] ) && $_FILES ['image']['error'] == 0) {
					$file_name = microtime(true).".".pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
					$config['file_name'] = $file_name;
			    	$this->upload->initialize($config);
			    	if($this->upload->do_upload('image')){
			    		$data = [
							'image'		=> $file_name
						];
						$this->db->where('id',$this->input->post('userid'))->update('z_customer',$data);

						if($user['image'] != 'male.png' && file_exists(FCPATH.'uploads/user/'.$user['image'])){
	   		            	@unlink(FCPATH.'/uploads/user/'.$user['image']);   
	 		        	}
			    	}
				}

				$user = $this->db->get_where('z_customer',['id' => $this->input->post('userid')])->row_array();
				
				if($user['image'] == "male.png" || $user['image'] == "female.png"){
					$user['image'] = "";
				}else{
					$user['image'] = base_url('uploads/user/').$user['image'];
				}
				retJson(['_return' => true,'msg' => 'Profile Updated.','data' => $user]);
			}else{
				retJson(['_return' => false,'msg' => 'User Not Found']);
			}
		}else{
			retJson(['_return' => false,'msg' => '`userid`,`fname`,`lname` and `gender` are Required']);
		}	
	}

	public function change_password()
	{
		if($this->input->post('userid') && $this->input->post('old_password') && $this->input->post('new_password')){
			$user = $this->db->get_where('z_customer',['id' => $this->input->post('userid')])->row_array();
			if($user && $user['password'] == md5($this->input->post('old_password'))){
				$this->db->where('id',$this->input->post('userid'))->update('z_customer',['password' => md5($this->input->post('new_password'))]);
				retJson(['_return' => true,'msg' => 'Password Changed.']);
			}else{
				retJson(['_return' => false,'msg' => 'Old Password Not Match.']);
			}
		}else{
			retJson(['_return' => false,'msg' => '`userid`,`old_password` and `new_password` are Required']);
		}
	}

	public function reset_password()
	{
		if($this->input->post('userid') && $this->input->post('otp') && $this->input->post('password')){
			$user = $this->db->get_where('z_customer',['id' => $this->input->post('userid')])->row_array();
			if($user && $user['otp'] == $this->input->post('otp')){
				$this->db->where('id',$this->input->post('userid'))->update('z_customer',['password' => md5($this->input->post('password'))]);
				retJson(['_return' => true,'msg' => 'Password Changed.']);
			}else{
				retJson(['_return' => false,'msg' => 'Please Enter Valid OTP.']);
			}
		}else{
			retJson(['_return' => false,'msg' => '`userid`,`otp` and `password` are Required']);
		}
	}

	public function forget_password()
	{
		if($this->input->post('mobile')){
			$user = $this->db->get_where('z_customer',['mobile' => $this->input->post('mobile'),'df' => '']);
			if($user->num_rows() > 0){
				$oldRow = $user->row_array();
				if($oldRow['verified'] == 'Verified'){
					$otp = mt_rand(100000, 999999);
					$this->db->where('id',$oldRow['id'])->update('z_customer',['otp' => $otp]);
					@sendOtp($this->input->post('mobile'),$otp);
					retJson(['_return' => true,'msg' => 'OTP Sent','otp' => $otp,'userid' => $oldRow['id']]);
				}else{
					retJson(['_return' => false,'msg' => 'Mobile No. Not Verified.']);
				}
			}else{
				retJson(['_return' => false,'msg' => 'Mobile No. Not Registered.']);
			}
		}else{
			retJson(['_return' => false,'msg' => '`mobile` is Required']);
		}	
	}

	public function faq()
	{
		$list = $this->db->get('faq_customer');
		retJson(['_return' => true,'count' => $list->num_rows(),'list' => $list->result_array()]);
	}

	public function how()
	{
		$content = $this->db->get_where('pages',['id' => '4'])->row_array();
		retJson(['_return' => true,'content' => $content['content']]);
	}

	public function about()
	{
		$content = $this->db->get_where('pages',['id' => '3'])->row_array();
		retJson(['_return' => true,'content' => $content['content']]);
	}

	public function privacy()
	{
		$content = $this->db->get_where('pages',['id' => '2'])->row_array();
		retJson(['_return' => true,'content' => $content['content']]);
	}

	public function terms()
	{
		$content = $this->db->get_where('pages',['id' => '1'])->row_array();
		retJson(['_return' => true,'content' => $content['content']]);
	}

	public function logout()
	{
		if($this->input->post('userid')){
			$this->db->where('id',$this->input->post('userid'))->update('z_customer',['token' => "",'deviceid' => '']);
			retJson(['_return' => true,'msg' => 'Logout Successful']);
		}else{
			retJson(['_return' => false,'msg' => '`userid` is Required']);
		}
	}

	public function login()
	{
		if($this->input->post('mobile') && $this->input->post('password') && $this->input->post('token')){
			$user = $this->db->get_where('z_customer',['mobile' => $this->input->post('mobile'),'df' => '']);
			if($user->num_rows() > 0){
				$user = $user->row_array();
				if($user['password'] === md5($this->input->post('password'))){
					if($user['verified'] == "Verified"){
						if($user['block'] == ""){

							$deviceid = "android";
							if($this->input->post('device')){
								$deviceid = $this->input->post('device');
							}
							$this->db->where('id',$user['id'])->update('z_customer',['token' => $this->input->post('token'),'deviceid' => $deviceid]);

							$user = $this->db->get_where('z_customer',['id' => $user['id']])->row_array();
							if($user['image'] == "male.png" || $user['image'] == "female.png"){
								$user['image'] = "";
							}else{
								$user['image'] = base_url('uploads/user/').$user['image'];
							}
							$address = $this->db->get_where('address',['userid' => $user['id']])->row_array();
							$ad = 0;
							if($address){
								$ad = 1;
							}
							$user['address']	= $ad;
							$user['subscription_status'] 	= checkSubscriptionExpiration($user['sub_expired_on']);
							$user['razorepay_key']  		= get_setting()['razorpay_key'];



							retJson(['_return' => true,'msg' => 'Login Successful','data' => $user]);
						}else{
							retJson(['_return' => false,'msg' => 'Account is Blocked.']);	
						}	
					}else{
						retJson(['_return' => false,'msg' => 'Mobile No. Not Verified.']);	
					}
				}else{
					retJson(['_return' => false,'msg' => 'Mobile No. and Password Not Match.']);	
				}
			}else{
				retJson(['_return' => false,'msg' => 'Mobile No. Not Exists.']);	
			}
		}else{
			retJson(['_return' => false,'msg' => '`mobile`,`password` and `token` are Required']);
		}
	}

	public function resend_register_otp()
	{
		if($this->input->post('userid')){
			$otp = mt_rand(100000, 999999);
			$this->db->where('id',$this->input->post('userid'))->update('z_customer',['otp' => $otp]);
			$user = $this->db->get_where('z_customer',['id' => $this->input->post('userid')])->row_array();
			@sendOtp($user['mobile'],$otp);
			retJson(['_return' => true,'msg' => 'OTP Sent','otp' => $otp,'userid' => $this->input->post('userid')]);
		}
		else{
			retJson(['_return' => false,'msg' => '`userid` is Required']);
		}
	}

	public function verify_otp()
	{
		if($this->input->post('userid') && $this->input->post('otp')){
			$user = $this->db->get_where('z_customer',['id' => $this->input->post('userid')])->row_array();
			if($user && $user['otp'] == $this->input->post('otp')){
				$this->db->where('id',$this->input->post('userid'))->update('z_customer',['verified' => 'Verified']);
				retJson(['_return' => true,'msg' => 'Registration Successful.']);
			}else{
				retJson(['_return' => false,'msg' => 'Please Enter Valid OTP.']);
			}
		}
		else{
			retJson(['_return' => false,'msg' => '`userid` and `otp` are Required']);
		}
	}

	public function register()
	{
		if($this->input->post('fname') && $this->input->post('lname') && $this->input->post('mobile') && $this->input->post('password') && $this->input->post('gender')){
			$old = $this->db->get_where('z_customer',['mobile' => $this->input->post('mobile'),'df' => '']);
			if($this->input->post('gender') == "Male"){
				$image = "male.png";
			}else{
				$image = "female.png";
			}
			if($old->num_rows() == 0){
				$otp = mt_rand(100000, 999999);
				$data = [
					'fname'			=> $this->input->post('fname'),
					'lname'			=> $this->input->post('lname'),
					'mobile'		=> $this->input->post('mobile'),
					'password'		=> md5($this->input->post('password')),
					'gender'		=> $this->input->post('gender'),
					'image'			=> $image,
					'deviceid'		=> '',
					'token'			=> '',
					'df'			=> '',
					'block'			=> '',
					'registered_at'	=> date('Y-m-d H:i:s'),
					'sub_expired_on'=> getTommorrow(),
					'otp'			=> $otp
				];
				$this->db->insert('z_customer',$data);
				$user = $this->db->insert_id();
				@sendOtp($this->input->post('mobile'),$otp);
				retJson(['_return' => true,'msg' => 'Registration Successful. Please Verify OTP.','otp' => $otp,'userid' => $user]);
			}else{
				$oldRow = $old->row_array();
				if($oldRow['verified'] == 'Not Verified'){
					$otp = mt_rand(100000, 999999);
					$data = [
						'fname'			=> $this->input->post('fname'),
						'lname'			=> $this->input->post('lname'),
						'mobile'		=> $this->input->post('mobile'),
						'password'		=> md5($this->input->post('password')),
						'gender'		=> $this->input->post('gender'),
						'image'			=> $image,
						'deviceid'		=> '',
						'token'			=> '',
						'df'			=> '',
						'block'			=> '',
						'registered_at'	=> date('Y-m-d H:i:s'),
						'sub_expired_on'=> getTommorrow(),
						'otp'			=> $otp
					];
					$this->db->where('id',$oldRow['id'])->update('z_customer',$data);
					@sendOtp($this->input->post('mobile'),$otp);
					retJson(['_return' => true,'msg' => 'Registration Successful','otp' => $otp,'userid' => $oldRow['id']]);
				}else{
					retJson(['_return' => false,'msg' => 'Mobile No. Already Exists.']);
				}
			}
		}else{
			retJson(['_return' => false,'msg' => '`fname`,`lname`,`mobile`,`password` and `gender` are Required']);
		}
	}

	public function newregister()
	{
		if($this->input->post('fname') && $this->input->post('lname') && $this->input->post('mobile') && $this->input->post('password') && $this->input->post('gender')){
			$old = $this->db->get_where('z_customer',['mobile' => $this->input->post('mobile'),'df' => '']);
			if($this->input->post('gender') == "Male"){
				$image = "male.png";
			}else{
				$image = "female.png";
			}
			if($old->num_rows() == 0){
				$otp = mt_rand(100000, 999999);
				$data = [
					'fname'			=> $this->input->post('fname'),
					'lname'			=> $this->input->post('lname'),
					'mobile'		=> $this->input->post('mobile'),
					'password'		=> md5($this->input->post('password')),
					'gender'		=> $this->input->post('gender'),
					'image'			=> $image,
					'deviceid'		=> '',
					'token'			=> '',
					'df'			=> '',
					'block'			=> '',
					'registered_at'	=> date('Y-m-d H:i:s'),
					'sub_expired_on'=> getTommorrow(),
					'otp'			=> $otp
				];
				$this->db->insert('z_customer',$data);
				$user = $this->db->insert_id();
				@sendOtp($this->input->post('mobile'),$otp);
				retJson(['_return' => true,'msg' => 'Registration Successful. Please Verify OTP.','otp' => $otp,'userid' => $user]);
			}else{
				$oldRow = $old->row_array();
				if($oldRow['verified'] == 'Not Verified'){
					$otp = mt_rand(100000, 999999);
					$data = [
						'fname'			=> $this->input->post('fname'),
						'lname'			=> $this->input->post('lname'),
						'mobile'		=> $this->input->post('mobile'),
						'password'		=> md5($this->input->post('password')),
						'gender'		=> $this->input->post('gender'),
						'image'			=> $image,
						'deviceid'		=> '',
						'token'			=> '',
						'df'			=> '',
						'block'			=> '',
						'registered_at'	=> date('Y-m-d H:i:s'),
						'sub_expired_on'=> getTommorrow(),
						'otp'			=> $otp
					];
					$this->db->where('id',$oldRow['id'])->update('z_customer',$data);
					@sendOtp($this->input->post('mobile'),$otp);
					retJson(['_return' => true,'msg' => 'Registration Successful','otp' => $otp,'userid' => $oldRow['id']]);
				}else{
					retJson(['_return' => false,'msg' => 'Mobile No. Already Exists.']);
				}
			}
		}else{
			retJson(['_return' => false,'msg' => '`fname`,`lname`,`mobile`,`password` and `gender` are Required']);
		}
	}

	public function index()
	{
		retJson(['No Script Found Here']);
	}
}

?>