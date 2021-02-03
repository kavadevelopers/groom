<div class="page-header">
    <div class="align-items-end">
        <div class="row">
            <div class="col-md-6">
                <div class="page-header-title">
                    <div class="d-inline">
                        <h4><?= $_title ?></h4>
                    </div>
                </div>
            </div>
            <div class="col-md-6 text-right">
                 <a href="<?= base_url('orders/').$type ?>" class="btn btn-danger btn-mini"><i class="fa fa-arrow-left"></i> Back</a>  
            </div>
        </div>
    </div>
</div>
<?php $address = $this->db->get_where('address',['userid' => $customer['id']])->row_array(); ?>
<?php $cus_images = $this->db->get_where('corder_images',['order_id' => $order['id']])->result_array(); ?>
<?php $rating = $this->db->get_where('corder_review',['orderid' => $order['id']])->row_array(); ?>
<?php $dri_images = $this->db->get_where('corder_delivery_images',['order_id' => $order['id']])->result_array(); ?>
<div class="page-body">
    <div class="row">
    	<div class="col-md-6">
		    <div class="card user-card-full">
		        <div class="row m-l-0 m-r-0">
		            <div class="col-sm-12">
		                <div class="card-block">
		                    <h6 class="m-b-20 p-b-5 b-b-default f-w-600">Customer Information</h6>
		                    <div class="row">
		                    	<div class="col-sm-6">
		                            <p class="m-b-10 f-w-600">Name</p>
		                            <h6 class="text-muted f-w-400"><?= $customer['fname'].' '.$customer['lname'] ?></h6>
		                        </div>
		                        <div class="col-sm-6">
		                            <p class="m-b-10 f-w-600">Mobile</p>
		                            <h6 class="text-muted f-w-400"><?= $customer['mobile'] ?></h6>
		                        </div>
		                    </div>
		                    <div class="row">
		                        <div class="col-sm-12">
		                            <p class="m-b-10 f-w-600">Address</p>
		                            <h6 class="text-muted f-w-400">
		                            	<?= $address['flat_no'] ?>, <?= $address['street_no'] ?>, <?= $address['address_line'] ?>
		                            </h6>
		                        </div>
		                    </div>
		                    <div class="row">
		                        <div class="col-sm-6">
		                            <p class="m-b-10 f-w-600">Subscription</p>
		                            <h6 class="text-muted f-w-400">
		                            	<?= ucfirst(checkSubscriptionExpiration($customer['sub_expired_on'])) ?>
		                            </h6>
		                        </div>
		                        <div class="col-sm-6">
		                            <p class="m-b-10 f-w-600">Gender</p>
		                            <h6 class="text-muted f-w-400"><?= $customer['gender'] ?></h6>
		                        </div>
		                    </div>
		                    <?php if($rating){ ?>
			                    <div class="row">
			                        <div class="col-sm-12">
			                            <p class="m-b-10 f-w-600">Customer Ratings</p>
			                            <div class="row">
			                            	<table style="width: 100%;">
			                            		<tr>
			                            			<td class="text-center" style="width:33.33%;">
			                            				<?php if($rating['rating'] == 1.00){ ?>
			                            					<img src="<?= base_url('asset/images/review/1.png') ?>" style="width:70px;">		
			                            				<?php }else{ ?>
			                            					<img src="<?= base_url('asset/images/review/11.png') ?>" style="width:50px;">		
			                            				<?php } ?>
			                            			</td>
			                            			<td class="text-center" style="width:33.33%;">
			                            				<?php if($rating['rating'] == 2.00){ ?>
			                            					<img src="<?= base_url('asset/images/review/2.png') ?>" style="width:70px;">		
			                            				<?php }else{ ?>
			                            					<img src="<?= base_url('asset/images/review/22.png') ?>" style="width:50px;">		
			                            				<?php } ?>	
			                            			</td>
			                            			<td class="text-center" style="width:33.33%;">
			                            				<?php if($rating['rating'] == 3.00){ ?>
			                            					<img src="<?= base_url('asset/images/review/3.png') ?>" style="width:70px;">		
			                            				<?php }else{ ?>
			                            					<img src="<?= base_url('asset/images/review/33.png') ?>" style="width:50px;">		
			                            				<?php } ?>		
			                            			</td>
			                            		</tr>
			                            		<tr>
			                            			<?php if($rating['rating'] == 1.00){ ?>
		                            					<th class="text-center">Disappointed</th>
		                            				<?php }else{ ?>
		                            					<td class="text-center">Disappointed</td>
		                            				<?php } ?>
		                            				<?php if($rating['rating'] == 2.00){ ?>
		                            					<th class="text-center">Happy</th>
		                            				<?php }else{ ?>
		                            					<td class="text-center">Happy</td>
		                            				<?php } ?>
		                            				<?php if($rating['rating'] == 3.00){ ?>
		                            					<th class="text-center">Delighted</th>
		                            				<?php }else{ ?>
		                            					<td class="text-center">Delighted</td>
		                            				<?php } ?>
			                            		</tr>
			                            	</table>
			                            </div>
			                        </div>
			                    </div>
			                    <h6 class="m-b-20 p-b-5 b-b-default f-w-600"></h6>
			                    <div class="row">
			                        <div class="col-sm-12">
			                            <p class="m-b-10 f-w-600">Rating Description</p>
			                            <h6 class="text-muted f-w-400">
			                            	<?= nl2br($rating['description']) ?>
			                            </h6>
			                        </div>
			                    </div>
			                <?php } ?>
		                </div>
		            </div>
		        </div>
		    </div>
		    <div class="card user-card-full">
		        <div class="row m-l-0 m-r-0">
		            <div class="col-sm-12">
		                <div class="card-block">
		                    <h6 class="m-b-20 p-b-5 b-b-default f-w-600">Order Information</h6>
		                    <div class="row">
		                        <div class="col-sm-6">
		                            <p class="m-b-10 f-w-600">Order Type</p>
		                            <h6 class="text-muted f-w-400"><?= ucfirst($order['type']) ?></h6>
		                        </div>
		                        <div class="col-sm-6">
		                            <p class="m-b-10 f-w-600">Category</p>
		                            <h6 class="text-muted f-w-400"><?= _get_category($order['category'])['name'] ?></h6>
		                        </div>
		                    </div>
		                    <div class="row">
		                        <div class="col-sm-12">
		                            <p class="m-b-10 f-w-600">Description</p>
		                            <h6 class="text-muted f-w-400">
		                            	<?= nl2br($order['descr']) ?>
		                            </h6>
		                        </div>
		                    </div>
		                    <div class="row">
		                        <div class="col-sm-6">
		                            <p class="m-b-10 f-w-600">Order Status</p>
		                            <h6 class="text-muted f-w-400"><?= $order['notes'] ?></h6>
		                        </div>
		                        <div class="col-sm-6">
		                            <p class="m-b-10 f-w-600">Order Date</p>
		                            <h6 class="text-muted f-w-400"><?= getPretyDateTime($order['created_at']) ?></h6>
		                        </div>
		                    </div>
		                    <div class="row">
		                        <div class="col-sm-6">
		                            <p class="m-b-10 f-w-600">Service Provider</p>
		                            <h6 class="text-muted f-w-400"><?= get_service($order['service'])['fname'] ?> <?= get_service($order['service'])['lname'] ?></h6>
		                        </div>
		                        <div class="col-sm-6">
		                            <p class="m-b-10 f-w-600">Order Amount</p>
		                            <h6 class="text-muted f-w-400"><?= rs().$order['price'] ?></h6>
		                        </div>
		                    </div>
		                    <div class="row">
		                        <div class="col-sm-6">
		                            <p class="m-b-10 f-w-600">Driver 1</p>
		                            <h6 class="text-muted f-w-400"><?= get_delivery($order['driver'])['fname'] ?> <?= get_delivery($order['driver'])['lname'] ?></h6>
		                        </div>
		                        <div class="col-sm-6">
		                            <p class="m-b-10 f-w-600">Driver 2</p>
		                            <h6 class="text-muted f-w-400"><?= get_delivery($order['driver2'])['fname'] ?> <?= get_delivery($order['driver2'])['lname'] ?></h6>
		                        </div>
		                    </div>
		                    <div class="row">
		                        <div class="col-sm-6">
		                            <p class="m-b-10 f-w-600">Payment Type</p>
		                            <h6 class="text-muted f-w-400"><?= ucfirst($order['payment_type']) ?></h6>
		                        </div>
		                        <div class="col-sm-6">
		                            <p class="m-b-10 f-w-600">Transaction Id</p>
		                            <h6 class="text-muted f-w-400"><?= $order['tra_id'] ?></h6>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>
		<div class="col-md-3">
		    <div class="card user-card-full">
		        <div class="row m-l-0 m-r-0">
		            <div class="col-sm-12">
		                <div class="card-block">
		                    <h6 class="m-b-20 p-b-5 b-b-default f-w-600">User Images</h6>
		                    <?php foreach ($cus_images as $key => $value) { ?>
	                    		<div class="col-md-12">
		                    		<img src="<?= base_url('uploads/order/').$value['image'] ?>" style="width: 100%;">
		                    		<h6 class="m-b-20 p-b-5 b-b-default f-w-600 "></h6>
		                    	</div>
	                    	<?php } ?>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>
		<div class="col-md-3">
		    <div class="card user-card-full">
		        <div class="row m-l-0 m-r-0">
		            <div class="col-sm-12">
		                <div class="card-block">
		                    <h6 class="m-b-20 p-b-5 b-b-default f-w-600">Delivery Boy Images</h6>
		                    <div class="row">
		                    	<?php foreach ($dri_images as $key => $value) { ?>
		                    		<div class="col-md-12">
			                    		<p class="m-b-10 f-w-600 text-muted"><?= $value['imgtype'] ?></p>
			                    		<img src="<?= base_url('uploads/order/').$value['image'] ?>" style="width: 100%;">
			                    		<h6 class="m-b-20 p-b-5 b-b-default f-w-600 "></h6>
			                    	</div>
		                    	<?php } ?>
		                    </div>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>
    </div>
</div>

