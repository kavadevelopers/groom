<div class="page-body">
    <div class="row">

        <div class="col-md-4">
		    <div class="card bg-c-yellow text-white">
		        <div class="card-block">
		            <div class="row align-items-center">
		                <div class="col">
		                    <p class="m-b-5">Customers</p>
		                    <h4 class="m-b-0"><?= userCount('z_customer') ?></h4>
		                </div>
		                <div class="col col-auto text-right">
		                    <i class="fa fa-address-card-o f-50 text-c-yellow"></i>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>

		<div class="col-md-4">
		    <div class="card bg-c-green text-white">
		        <div class="card-block">
		            <div class="row align-items-center">
		                <div class="col">
		                    <p class="m-b-5">Service Providers</p>
		                    <h4 class="m-b-0"><?= userCount('z_service') ?></h4>
		                </div>
		                <div class="col col-auto text-right">
		                    <i class="fa fa-wrench f-50 text-c-green"></i>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>

		<div class="col-md-4">
		    <div class="card bg-c-blue text-white">
		        <div class="card-block">
		            <div class="row align-items-center">
		                <div class="col">
		                    <p class="m-b-5">Delivery Boys</p>
		                    <h4 class="m-b-0"><?= userCount('z_delivery') ?></h4>
		                </div>
		                <div class="col col-auto text-right">
		                    <i class="fa fa-car f-50 text-c-blue"></i>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>

		<div class="col-xl-4 col-md-6">
		    <div class="card">
		        <div class="card-header">
		            <h5>Orders</h5>
		        </div>
		        <div class="card-block">
		            <canvas id="ordersChart" height="312" width="426" style="display: block; height: 250px; width: 341px;"></canvas>
		        </div>
		        <div class="card-footer ">
		            <div class="row text-center b-t-default">
		                <div class="col-4 b-r-default m-t-15">
		                    <h5><?= ordersCount('upcoming') ?></h5>
		                    <p class="text-muted m-b-0">New</p>
		                </div>
		                <div class="col-4 b-r-default m-t-15">
		                    <h5><?= ordersCount('ongoing') ?></h5>
		                    <p class="text-muted m-b-0">Ongoing</p>
		                </div>
		                <div class="col-4 m-t-15">
		                    <h5><?= ordersCount('completed') ?></h5>
		                    <p class="text-muted m-b-0">Completed</p>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>

		<div class="col-xl-4 col-md-6">
		    <div class="card">
		        <div class="card-header">
		            <h5>Service Providers</h5>
		        </div>
		        <div class="card-block">
		            <canvas id="serviceChart" height="312" width="426" style="display: block; height: 250px; width: 341px;"></canvas>
		        </div>
		        <div class="card-footer ">
		            <div class="row text-center b-t-default">
		                <div class="col-6 b-r-default m-t-15">
		                    <h5><?= getServiceProvidersOnOff('online') ?></h5>
		                    <p class="text-muted m-b-0">Online</p>
		                </div>
		                <div class="col-6 m-t-15">
		                    <h5><?= getServiceProvidersOnOff('offline') ?></h5>
		                    <p class="text-muted m-b-0">Offline</p>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>

		<div class="col-xl-4 col-md-6">
		    <div class="card">
		        <div class="card-header">
		            <h5>Delivery Boys</h5>
		        </div>
		        <div class="card-block">
		            <canvas id="deliveryChart" height="312" width="426" style="display: block; height: 250px; width: 341px;"></canvas>
		        </div>
		        <div class="card-footer ">
		            <div class="row text-center b-t-default">
		                <div class="col-6 b-r-default m-t-15">
		                    <h5><?= getDeliveryBoysOnOff('online') ?></h5>
		                    <p class="text-muted m-b-0">Online</p>
		                </div>
		                <div class="col-6 m-t-15">
		                    <h5><?= getDeliveryBoysOnOff('offline') ?></h5>
		                    <p class="text-muted m-b-0">Offline</p>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>

    </div>
    <div class="row">
    	<div class="col-md-12">
            <div class="card">
            	<div class="card-header">
            		<h5>Recent Orders</h5>
            	</div>
                <div class="card-block table-responsive">
                    <table class="table table-striped table-bordered table-mini">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>Customer</th>
                                <th class="text-center">Type</th>
                                <th class="text-center">Category</th>
                                <th>Discription</th>
                                <th>Status</th>
                                <th class="text-center">Order Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (getRecentOrders() as $newOrdersListkey => $newOrdersListvalue) { ?>
                                <tr>
                                    <td class="text-center">#<?= $newOrdersListvalue['order_id'] ?></td>
                                    <th><?= get_customer($newOrdersListvalue['userid'])['fname'] ?> <?= get_customer($newOrdersListvalue['userid'])['lname'] ?></th>
                                    <td class="text-center"><?= ucfirst($newOrdersListvalue['type']) ?></td>
                                    <td class="text-center"><?= _get_category($newOrdersListvalue['category'])['name'] ?></td>
                                    <td><?= subStrr($newOrdersListvalue['descr'],25) ?></td>
                                    <td><?= $newOrdersListvalue['notes'] ?></td>
                                    <td class="text-center"><?= getPretyDateTime($newOrdersListvalue['created_at']) ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>    
        </div>
    </div>
</div>



<script type="text/javascript" src="<?= base_url() ?>asset/bower_components/chart.js/js/Chart.js"></script>

<script type="text/javascript">
	var pieElem = document.getElementById("ordersChart");
    var data4 = {
        labels: ['New','Ongoing','Completed'],
        datasets: [{
            data: ['<?= ordersCount('upcoming') ?>','<?= ordersCount('ongoing') ?>','<?= ordersCount('completed') ?>'],
            backgroundColor: [
            	"#ff847c",
                "#feceab",
                "#99b898"
            ],
            hoverBackgroundColor: [
            	"#ff847ce6",
                "#feceabe6",
                "#99b898e6"
            ]
        }]
    };
    var myPieChart = new Chart(pieElem, {
        type: 'pie',
        data: data4
    });

    var pieElem = document.getElementById("deliveryChart");
    var data4 = {
        labels: ['Online','Offline'],
        datasets: [{
            data: ['<?= getDeliveryBoysOnOff('online') ?>','<?= getDeliveryBoysOnOff('offline') ?>'],
            backgroundColor: [
            	"#99b898",
                "#ff847c"
                
            ],
            hoverBackgroundColor: [
            	"#99b898e6",
                "#ff847ce6"
            ]
        }]
    };
    var myPieChart = new Chart(pieElem, {
        type: 'pie',
        data: data4
    });

    var pieElem = document.getElementById("serviceChart");
    var data4 = {
        labels: ['Online','Offline'],
        datasets: [{
            data: ['<?= getServiceProvidersOnOff('online') ?>','<?= getServiceProvidersOnOff('offline') ?>'],
            backgroundColor: [
            	"#99b898",
                "#ff847c"
                
            ],
            hoverBackgroundColor: [
            	"#99b898e6",
                "#ff847ce6"
            ]
        }]
    };
    var myPieChart = new Chart(pieElem, {
        type: 'pie',
        data: data4
    });
</script>