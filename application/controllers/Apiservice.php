<?php
class Apiservice extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function update_latlon()
	{
		if($this->input->post('userid') && $this->input->post('lat') && $this->input->post('lon')){
			$old = $this->db->get_where('service_latlon',['user' => $this->input->post('userid')])->row_array();
			if($old){
				$data = [
					'lat'		=> $this->input->post('lat'),
					'lon'		=> $this->input->post('lon')
				];
				$this->db->where('id',$this->input->post('userid'))->update('service_latlon',$data);
			}else{
				$data = [
					'user'		=> $this->input->post('userid'),
					'lat'		=> $this->input->post('lat'),
					'lon'		=> $this->input->post('lon')
				];
				$this->db->insert('service_latlon',$data);
			}
			retJson(['_return' => true,'msg' => 'Lat - Lon Saved']);
		}else{
			retJson(['_return' => false,'msg' => '`userid`,`lat` and `lon` is Required']);
		}
	}

	public function active()
	{
		if($this->input->post('userid') && $this->input->post('status')){
			if($this->input->post('status') == "active"){
				$this->db->where('id',$this->input->post('userid'))->update('z_service',['active' => '1']);
				retJson(['_return' => true,'msg' => 'You are now Online.']);
			}else{
				$this->db->where('id',$this->input->post('userid'))->update('z_service',['active' => '0']);
				retJson(['_return' => true,'msg' => 'You are now Offline.']);
			}
		}else{
			retJson(['_return' => false,'msg' => '`userid` and `status` = (`inactive`and`active`) is Required']);
		}
	}

	public function alignment_work_done()
	{
		if($this->input->post('order_id') && $this->input->post('user_id') && $this->input->post('price') && $this->input->post('price') > 0){
			$this->db->where('id',$this->input->post('order_id'))->update('corder',
				['status_desc' => 'Work Done By Service Provicer','notes' => 'Work Done By Service Provider','price' => $this->input->post('price'),'return_order' => 'true']
			);
			$order = $this->db->get_where('corder',['id' => $this->input->post('order_id')])->row_array();
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
			retJson(['_return' => true,'msg' => 'Order Updated.']);		
		}else{
			retJson(['_return' => false,'msg' => '`order_id`,`price` and `user_id` are Required']);
		}
	}

	public function reject_alignment()
	{
		if($this->input->post('order_id')){
			$this->db->where('id',$this->input->post('order_id'))->update('corder',
				['status_desc' => 'Order Canceled By Service Provider','notes' => 'Order Canceled By Service Provider','status' => 'completed']
			);

			sendPush(
				[get_customer(get_order($this->input->post('order_id'))['userid'])['token']],
				"Order #".get_order($this->input->post('order_id'))['order_id'],
				"Order Canceled By Alignment",
				"order",
				$this->input->post('order_id')
			);

			$order = get_order($this->input->post('order_id'));

			if($order['driver2'] != ""){
				sendPush(
					[get_delivery(get_order($this->input->post('order_id'))['driver2'])['token']],
					"Order #".get_order($this->input->post('order_id'))['order_id'],
					"Order Canceled By Alignment. Please Drop Item At Customer Location.",
					"order",
					$this->input->post('order_id')
				);
			}else{
				sendPush(
					[get_delivery(get_order($this->input->post('order_id'))['driver'])['token']],
					"Order #".get_order($this->input->post('order_id'))['order_id'],
					"Order Canceled By Alignment. Please Drop Item At Customer Location.",
					"order",
					$this->input->post('order_id')
				);
			}

			retJson(['_return' => true,'msg' => 'Order Canceled.']);		
		}else{
			retJson(['_return' => false,'msg' => '`order_id` is Required']);
		}	
	}

	public function accept_alignment_with_price_time()
	{
		if($this->input->post('order_id') && $this->input->post('user_id') && $this->input->post('price') && $this->input->post('time') && $this->input->post('price') > 0){
			$this->db->where('id',$this->input->post('order_id'))->update('corder',
				['status_desc' => 'Price Added By Service Provider','notes' => 'Waiting For Price Confirmation','price' => $this->input->post('price'),'time' => $this->input->post('time')]
			);

			sendPush(
				[get_customer(get_order($this->input->post('order_id'))['userid'])['token']],
				"Order #".get_order($this->input->post('order_id'))['order_id'],
				"Price Added By Alignment. Please confirm.",
				"order",
				$this->input->post('order_id')
			);

			if($this->input->post('time') > 0.5){
				$this->db->where('id',$this->input->post('order_id'))->update('corder',
					['done_driver1' => 'yes']
				);

				sendPush(
					[get_delivery(get_order($this->input->post('order_id'))['driver'])['token']],
					"Order #".get_order($this->input->post('order_id'))['order_id'],
					"Order Completed.",
					"order",
					$this->input->post('order_id')
				);
			}else{
				sendPush(
					[get_delivery(get_order($this->input->post('order_id'))['driver'])['token']],
					"Order #".get_order($this->input->post('order_id'))['order_id'],
					"Wait For Alignment To do Work.",
					"order",
					$this->input->post('order_id')
				);
			}

			retJson(['_return' => true,'msg' => 'Price Added']);
		}else{
			retJson(['_return' => false,'msg' => '`order_id`,`price`,`time` and `user_id` are Required']);
		}
	}

	public function accept_alignment_order()
	{
		if($this->input->post('order_id') && $this->input->post('user_id')){
			$order = $this->db->get_where('corder',['id' => $this->input->post('order_id'),'status' => 'upcoming','df' => '','service' => ''])->row_array();
			if($order){
				$this->db->where('id',$this->input->post('order_id'))->update('corder',
					['service' => $this->input->post('user_id'),'status_desc' => 'Order Accepted By Service Provider','status' => 'ongoing','notes' => 'Driver Assigned']
				);

				sendPush(
					[get_customer(get_order($this->input->post('order_id'))['userid'])['token']],
					"Order #".get_order($this->input->post('order_id'))['order_id'],
					"Order Accepted By Alignment. Driver Assigned.",
					"order",
					$this->input->post('order_id')
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
						"New Alignment Request.",
						"order",
						$this->input->post('order_id')
					);	
				}

				retJson(['_return' => true,'msg' => 'Order Accepted.']);		
			}else{
				retJson(['_return' => false,'msg' => 'Order Already Accepted.']);
			}
		}else{
			retJson(['_return' => false,'msg' => '`order_id` and `user_id` are Required']);
		}	
	}

	public function service_work_done()
	{
		if($this->input->post('order_id') && $this->input->post('user_id') && $this->input->post('price')  && $this->input->post('price') > 0){
			$this->db->where('id',$this->input->post('order_id'))->update('corder',
				['status_desc' => 'Work Done Waiting for Payment','notes' => 'Work Done By Service Provider','price' => $this->input->post('price')]
			);

			sendPush(
				[get_customer(get_order($this->input->post('order_id'))['userid'])['token']],
				"Order #".get_order($this->input->post('order_id'))['order_id'],
				"Work Done By Service Provider. Please Make Payment",
				"order",
				$this->input->post('order_id')
			);

			retJson(['_return' => true,'msg' => 'Order Updated.']);		
		}else{
			retJson(['_return' => false,'msg' => '`order_id`,`price` and `user_id` are Required']);
		}
	}

	public function cancel_service_order()
	{
		if($this->input->post('order_id')){
			if($this->input->post('reason')){
				$this->db->where('id',$this->input->post('order_id'))->update('corder',
					['status_desc' => $this->input->post('reason'),'notes' => 'Order Canceled Reason','status' => 'completed']
				);
				if($this->input->post('service')){
					$this->db->where('id',$this->input->post('order_id'))->update('corder',
						['service' => $this->input->post('service')]
					);	
				}
				sendPush(
					[get_customer(get_order($this->input->post('order_id'))['userid'])['token']],
					"Order #".get_order($this->input->post('order_id'))['order_id'],
					"Order Canceled By Service Provider",
					"order",
					$this->input->post('order_id')
				);
			}else{
				$this->db->where('id',$this->input->post('order_id'))->update('corder',
					['status_desc' => 'Order Placed','notes' => 'Pending','price' => "0.00",'time' => "",'service' => "",'status' => 'upcoming']
				);	
				sendPush(
					[get_customer(get_order($this->input->post('order_id'))['userid'])['token']],
					"Order #".get_order($this->input->post('order_id'))['order_id'],
					"Order Canceled By Service Provider",
					"order",
					$this->input->post('order_id')
				);
			}
			retJson(['_return' => true,'msg' => 'Order Canceled.']);		
		}else{
			retJson(['_return' => false,'msg' => '`order_id` is Required']);
		}	
	}

	public function add_pricing_service_order()
	{
		if($this->input->post('order_id') && $this->input->post('user_id') && $this->input->post('price') && $this->input->post('time') && $this->input->post('price') > 0){
			$this->db->where('id',$this->input->post('order_id'))->update('corder',
				['status_desc' => 'Price Added By Service Provider','notes' => 'Waiting For Price Confirmation','price' => $this->input->post('price'),'time' => $this->input->post('time')]
			);

			sendPush(
				[get_customer(get_order($this->input->post('order_id'))['userid'])['token']],
				"Order #".get_order($this->input->post('order_id'))['order_id'],
				"Price Added By Service Provider.",
				"order",
				$this->input->post('order_id')
			);

			retJson(['_return' => true,'msg' => 'Order Updated.']);		
		}else{
			retJson(['_return' => false,'msg' => '`order_id`,`price`,`time` and `user_id` are Required']);
		}	
	}

	public function accept_service_order()
	{
		if($this->input->post('order_id') && $this->input->post('user_id')){
			$order = $this->db->get_where('corder',['id' => $this->input->post('order_id'),'status' => 'upcoming','df' => '','service' => ''])->row_array();
			if($order){
				$this->db->where('id',$this->input->post('order_id'))->update('corder',
					['service' => $this->input->post('user_id'),'status_desc' => 'Order Accepted By Service Provider','status' => 'ongoing','notes' => 'Coming For Visit']
				);

				sendPush(
					[get_customer(get_order($this->input->post('order_id'))['userid'])['token']],
					"Order #".get_order($this->input->post('order_id'))['order_id'],
					"Order Accepted By Service Provider. Coming For Visit",
					"order",
					$this->input->post('order_id')
				);

				retJson(['_return' => true,'msg' => 'Order Accepted.']);		
			}else{
				retJson(['_return' => false,'msg' => 'Order Already Accepted.']);
			}
		}else{
			retJson(['_return' => false,'msg' => '`order_id` and `user_id` are Required']);
		}	
	}

	public function order_packed()
	{
		if($this->input->post('user_id') && $this->input->post('order_id')){
			$this->db->where('id',$this->input->post('order_id'))->update('corder',['notes' => 'Shipped']);

			sendPush(
				[get_customer(get_order($this->input->post('order_id'))['userid'])['token']],
				"Order #".get_order($this->input->post('order_id'))['order_id'],
				"Order Packed Waiting For Driver",
				"order",
				$this->input->post('order_id')
			);

			sendPush(
				[get_delivery(get_order($this->input->post('order_id'))['driver'])['token']],
				"Order #".get_order($this->input->post('order_id'))['order_id'],
				"Order Packed Pickup your Order.",
				"order",
				$this->input->post('order_id')
			);

			retJson(['_return' => true,'msg' => 'Order Status Changed']);
		}else{
			retJson(['_return' => false,'msg' => '`user_id` and `order_id` is Required']);
		}
	}

	public function mydashboard()
	{
		if($this->input->post('user_id') && $this->input->post('category')){
			if($this->input->post('filter') == "week"){
				$start = date("Y-m-d", strtotime("last week monday"));
				$end = date("Y-m-d", strtotime("last week sunday"));
				$upcoming = $this->db->get_where('corder',['status' => "upcoming",'category' => $this->input->post('category'),'df' => '','created_at >=' => $start,'created_at <=' => $end])->num_rows();
				$ongoing = $this->db->get_where('corder',['status' => "ongoing",'service' => $this->input->post('user_id'),'df' => '','cancel' => '','created_at >=' => $start,'created_at <=' => $end])->num_rows();
				$compeleted = $this->db->get_where('corder',['status' => "completed",'service' => $this->input->post('user_id'),'df' => '','cancel' => '','created_at >=' => $start,'created_at <=' => $end])->num_rows();
				$canceled = $this->db->get_where('corder',['status' => "completed",'service' => $this->input->post('user_id'),'df' => '','cancel !=' => '','created_at >=' => $start,'created_at <=' => $end])->num_rows();	
				$cashCollected = $this->db->select_sum('price')->from('corder')->where('status','completed')->where('cancel','')->where('payment_type !=','payment_gateway')->where('df','')->where('service',$this->input->post('user_id'))->where('created_at >=',$start)->where('created_at <=',$end)->get()->row()->price;
				$bankCollected = $this->db->select_sum('price')->from('corder')->where('status','completed')->where('cancel','')->where('payment_type','payment_gateway')->where('df','')->where('service',$this->input->post('user_id'))->where('created_at >=',$start)->where('created_at <=',$end)->get()->row()->price;
			}else if($this->input->post('filter') == "month"){
				$start = date("Y-m-d", strtotime("first day of previous month"));
				$end = date("Y-m-d", strtotime("last day of previous month"));
				$upcoming = $this->db->get_where('corder',['status' => "upcoming",'category' => $this->input->post('category'),'df' => '','created_at >=' => $start,'created_at <=' => $end])->num_rows();
				$ongoing = $this->db->get_where('corder',['status' => "ongoing",'service' => $this->input->post('user_id'),'df' => '','cancel' => '','created_at >=' => $start,'created_at <=' => $end])->num_rows();
				$compeleted = $this->db->get_where('corder',['status' => "completed",'service' => $this->input->post('user_id'),'df' => '','cancel' => '','created_at >=' => $start,'created_at <=' => $end])->num_rows();
				$canceled = $this->db->get_where('corder',['status' => "completed",'service' => $this->input->post('user_id'),'df' => '','cancel !=' => '','created_at >=' => $start,'created_at <=' => $end])->num_rows();	
				$cashCollected = $this->db->select_sum('price')->from('corder')->where('status','completed')->where('cancel','')->where('payment_type !=','payment_gateway')->where('df','')->where('service',$this->input->post('user_id'))->where('created_at >=',$start)->where('created_at <=',$end)->get()->row()->price;
				$bankCollected = $this->db->select_sum('price')->from('corder')->where('status','completed')->where('cancel','')->where('payment_type','payment_gateway')->where('df','')->where('service',$this->input->post('user_id'))->where('created_at >=',$start)->where('created_at <=',$end)->get()->row()->price;
			}else{
				$upcoming = $this->db->get_where('corder',['status' => "upcoming",'category' => $this->input->post('category'),'df' => ''])->num_rows();
				$ongoing = $this->db->get_where('corder',['status' => "ongoing",'service' => $this->input->post('user_id'),'df' => '','cancel' => ''])->num_rows();
				$compeleted = $this->db->get_where('corder',['status' => "completed",'service' => $this->input->post('user_id'),'df' => '','cancel' => ''])->num_rows();
				$canceled = $this->db->get_where('corder',['status' => "completed",'service' => $this->input->post('user_id'),'df' => '','cancel !=' => ''])->num_rows();	
				$cashCollected = $this->db->select_sum('price')->from('corder')->where('status','completed')->where('cancel','')->where('payment_type !=','payment_gateway')->where('df','')->where('service',$this->input->post('user_id'))->get()->row()->price;
				$bankCollected = $this->db->select_sum('price')->from('corder')->where('status','completed')->where('cancel','')->where('payment_type','payment_gateway')->where('df','')->where('service',$this->input->post('user_id'))->get()->row()->price;
			}

			$cash = 0;
			if($cashCollected){
				$cash = $cashCollected;
			}
			$bank = 0;
			if($bankCollected){
				$bank = $bankCollected;
			}
			$lastWeek = 0;
			if($lastWeekCollection){
				$lastWeek = $lastWeekCollection;
			}
			$lastMonth = 0;
			if($lastMonthCollection){
				$lastMonth = $lastMonthCollection;
			}

			$ret = [
				'upcoming' 	=> $upcoming, 
				'ongoing' 	=> $ongoing, 
				'completed' => $compeleted, 
				'canceled' 	=> $canceled, 
				'cash' 		=> $cash,
				'bank' 		=> $bank
			];
			retJson(['_return' => true,'data' => $ret]);	
		}else{
			retJson(['_return' => false,'msg' => '`user_id` and `category` is Required']);
		}
	}

	public function accept_order()
	{
		if($this->input->post('order_id') && $this->input->post('price') && $this->input->post('user_id') && $this->input->post('price') > 0){
			$order = $this->db->get_where('corder',['id' => $this->input->post('order_id'),'status' => 'upcoming','df' => '','price' => '0.00'])->row_array();
			if($order){
				$this->db->where('id',$this->input->post('order_id'))->update('corder',
					['price' => $this->input->post('price'),'service' => $this->input->post('user_id'),'status_desc' => 'Price Added','notes' => 'Waiting']
				);

				sendPush(
					[get_customer(get_order($this->input->post('order_id'))['userid'])['token']],
					"Order #".get_order($this->input->post('order_id'))['order_id'],
					"Order Accepted By Service Provicer",
					"order",
					$this->input->post('order_id')
				);

				retJson(['_return' => true,'msg' => 'Order Accepted.']);		
			}else{
				retJson(['_return' => false,'msg' => 'Order Already Accepted.']);
			}
		}else{
			retJson(['_return' => false,'msg' => '`order_id`,`price` and `user_id` are Required']);
		}	
	}

	public function getorder()
	{
		if($this->input->post('order_id')){	
			$single = $this->db->get_where('corder',['id' => $this->input->post('order_id')])->row_array();
			if($single){
				$customer = $this->db->get_where('z_customer',['id' => $single['userid']])->row_array();
				$address = $this->db->get_where('address',['userid' => $single['userid']])->row_array();
				$single['customer_name'] = $customer['fname'].' '.$customer['lname'];
				$single['customer_mobile'] = $customer['mobile'];
				$single['address']	= $address;

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
				

				
				$images = $this->db->get_where('corder_images',['order_id' => $single['id']])->result_array();
				foreach ($images as $imageskey => $imagesvalue) {
					$images[$imageskey]['image']	= base_url('uploads/order/').$imagesvalue['image'];
				}
				$single['images']			=	$images;

				// $single['originalprice']		= $single['price'];
				// $single['cutoffprice']			= getServiceCutOff($single['price'],$single['category']);
				// $single['price']				= getServicePrice($single['price'],$single['category']);

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
		if($this->input->post('status') && $this->input->post('category') && $this->input->post('user_id')){
			$where = ['status' => "upcoming",'category' => $this->input->post('category'),'df' => ''];
			if($this->input->post('status') == "upcoming"){
				$where = ['status' => "upcoming",'category' => $this->input->post('category'),'df' => '','order_type !=' => 'later'];
			}
			if($this->input->post('status') == "ongoing"){
				$where = ['status' => "ongoing",'category' => $this->input->post('category'),'service' => $this->input->post('user_id'),'df' => ''];
			}
			if($this->input->post('status') == "completed"){
				$where = ['status' => "completed",'category' => $this->input->post('category'),'service' => $this->input->post('user_id'),'df' => ''];
			}
			$list = $this->db->order_by('id','desc')->get_where('corder',$where);
			$nlist = $list->result_array();
			foreach ($list->result_array() as $key => $value) {
				$customer = $this->db->get_where('z_customer',['id' => $value['userid']])->row_array();
				$address = $this->db->get_where('address',['userid' => $value['userid']])->row_array();
				$images = $this->db->get_where('corder_images',['order_id' => $value['id']])->result_array();
				$nlist[$key]['customer_name'] = $customer['fname'].' '.$customer['lname'];
				$nlist[$key]['address']		  = $address;
				foreach ($images as $imageskey => $imagesvalue) {
					$images[$imageskey]['image']	= base_url('uploads/order/').$imagesvalue['image'];
				}
				$nlist[$key]['images']			=	$images;

				// $nlist[$key]['originalprice']		= $nlist[$key]['price'];
				// $nlist[$key]['cutoffprice']			= getServiceCutOff($nlist[$key]['price'],$nlist[$key]['category']);
				// $nlist[$key]['price']				= getServicePrice($nlist[$key]['price'],$nlist[$key]['category']);
			}


			retJson(['_return' => true,'count' => $list->num_rows(),'list' => $nlist]);
		}else{
			retJson(['_return' => false,'msg' => '`status` = (`upcoming`,`ongoing`,`completed`),`user_id` and `category` are Required']);
		}
	}

	public function faq()
	{
		$list = $this->db->get('faq_service');
		retJson(['_return' => true,'count' => $list->num_rows(),'list' => $list->result_array()]);
	}

	public function about()
	{
		$content = $this->db->get_where('pages',['id' => '10'])->row_array();
		retJson(['_return' => true,'content' => $content['content']]);
	}

	public function privacy()
	{
		$content = $this->db->get_where('pages',['id' => '9'])->row_array();
		retJson(['_return' => true,'content' => $content['content']]);
	}

	public function terms()
	{
		$content = $this->db->get_where('pages',['id' => '8'])->row_array();
		retJson(['_return' => true,'content' => $content['content']]);
	}

	public function logout()
	{
		if($this->input->post('userid')){
			$this->db->where('id',$this->input->post('userid'))->update('z_service',['token' => ""]);
			retJson(['_return' => true,'msg' => 'Logout Successful']);
		}else{
			retJson(['_return' => false,'msg' => '`userid` is Required']);
		}
	}

	public function verify_login_otp()
	{
		if($this->input->post('userid') && $this->input->post('otp') && $this->input->post('token')){
			$user = $this->db->get_where('z_service',['id' => $this->input->post('userid')])->row_array();
			if($user && $user['loginotp'] == $this->input->post('otp')){
				$deviceid = "android";
				if($this->input->post('device')){
					$deviceid = $this->input->post('device');
				}
				$this->db->where('id',$user['id'])->update('z_service',['token' => $this->input->post('token'),'deviceid' => $deviceid]);
				$user = $this->db->get_where('z_service',['id' => $this->input->post('userid')])->row_array();
				retJson(['_return' => true,'msg' => 'Login Successful.','data' => $user]);
			}else{
				retJson(['_return' => false,'msg' => 'Please Enter Valid OTP.']);
			}
		}
		else{
			retJson(['_return' => false,'msg' => '`userid`,`token` and `otp` are Required']);
		}
	}

	public function resend_login_otp()
	{
		if($this->input->post('userid')){
			$otp = mt_rand(100000, 999999);
			$this->db->where('id',$this->input->post('userid'))->update('z_service',['loginotp' => $otp]);
			$user = $this->db->get_where('z_service',['id' => $this->input->post('userid')])->row_array();
			@sendOtp($user['mobile'],$otp);
			retJson(['_return' => true,'msg' => 'OTP Sent','otp' => $otp,'userid' => $this->input->post('userid')]);
		}
		else{
			retJson(['_return' => false,'msg' => '`userid` is Required']);
		}
	}

	public function login()
	{
		if($this->input->post('mobile')){
			$user = $this->db->get_where('z_service',['mobile' => $this->input->post('mobile'),'df' => '']);
			if($user->num_rows() > 0){
				$user = $user->row_array();
				if($user['verified'] == 'Verified' && $user['approved'] == '1'){
					if($user['block'] == ""){
						$otp = mt_rand(100000, 999999);
						$this->db->where('id',$user['id'])->update('z_service',['loginotp' => $otp]);
						@sendOtp($this->input->post('mobile'),$otp);
						retJson(['_return' => true,'msg' => 'Login Successful. Verify OTP.','otp' => $otp,'userid' => $user['id']]);
					}else{
						retJson(['_return' => false,'msg' => 'Account is Blocked.']);	
					}	
				}else{
					retJson(['_return' => false,'msg' => 'Mobile No. Not Verified.']);	
				}
			}else{
				retJson(['_return' => false,'msg' => 'Mobile No. Not Exists.']);	
			}
		}else{
			retJson(['_return' => false,'msg' => '`mobile` is Required']);
		}
	}

	public function resend_register_otp()
	{
		if($this->input->post('userid')){
			$otp = mt_rand(100000, 999999);
			$this->db->where('id',$this->input->post('userid'))->update('z_service',['otp' => $otp]);
			$user = $this->db->get_where('z_service',['id' => $this->input->post('userid')])->row_array();
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
			$user = $this->db->get_where('z_service',['id' => $this->input->post('userid')])->row_array();
			if($user && $user['otp'] == $this->input->post('otp')){
				$this->db->where('id',$this->input->post('userid'))->update('z_service',['verified' => 'Verified']);
				retJson(['_return' => true,'msg' => 'Registration Successful.']);
			}else{
				retJson(['_return' => false,'msg' => 'Please Enter Valid OTP.']);
			}
		}
		else{
			retJson(['_return' => false,'msg' => '`userid` and `otp` are Required']);
		}
	}

	public function getintouch()
	{
		if($this->input->post('fname') && $this->input->post('lname') && $this->input->post('mobile') && $this->input->post('address') && $this->input->post('business') && $this->input->post('category') && $this->input->post('gender')){
			$old = $this->db->get_where('z_service',['mobile' => $this->input->post('mobile'),'df' => '']);
			if($old->num_rows() == 0){
				$otp = mt_rand(100000, 999999);
				$data = [
					'fname'			=> $this->input->post('fname'),
					'lname'			=> $this->input->post('lname'),
					'mobile'		=> $this->input->post('mobile'),
					'address'		=> $this->input->post('address'),
					'business'		=> $this->input->post('business'),
					'category'		=> $this->input->post('category'),
					'gender'		=> $this->input->post('gender'),
					'deviceid'		=> '',
					'token'			=> '',
					'df'			=> '',
					'block'			=> '',
					'approved'		=> '0',
					'registered_at'	=> date('Y-m-d H:i:s'),
					'otp'			=> $otp
				];
				$this->db->insert('z_service',$data);
				$user = $this->db->insert_id();
				@sendOtp($this->input->post('mobile'),$otp);
				retJson(['_return' => true,'msg' => 'Registration Successful','otp' => $otp,'userid' => $user]);
			}else{
				$oldRow = $old->row_array();
				if($oldRow['verified'] == 'Not Verified'){
					$otp = mt_rand(100000, 999999);
					$data = [
						'fname'			=> $this->input->post('fname'),
						'lname'			=> $this->input->post('lname'),
						'mobile'		=> $this->input->post('mobile'),
						'address'		=> $this->input->post('address'),
						'business'		=> $this->input->post('business'),
						'category'		=> $this->input->post('category'),
						'gender'		=> $this->input->post('gender'),
						'deviceid'		=> '',
						'token'			=> '',
						'df'			=> '',
						'block'			=> '',
						'approved'		=> '0',
						'registered_at'	=> date('Y-m-d H:i:s'),
						'otp'			=> $otp
					];
					$this->db->where('id',$oldRow['id'])->update('z_service',$data);
					@sendOtp($this->input->post('mobile'),$otp);
					retJson(['_return' => true,'msg' => 'Registration Successful','otp' => $otp,'userid' => $oldRow['id']]);
				}else{
					retJson(['_return' => false,'msg' => 'Mobile No. Already Exists.']);
				}
			}
		}else{
			retJson(['_return' => false,'msg' => '`fname`,`lname`,`address`,`business`,`category`,`gender` and `mobile` are Required']);
		}
	}

	public function index()
	{
		retJson(['No Script Found Here']);
	}
}