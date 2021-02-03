<div class="page-header">
    <div class="row align-items-end">
        <div class="col-md-12">
            <div class="page-header-title">
                <div class="d-inline">
                    <h4><?= $_title ?></h4>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="page-body">
    <div class="row">
    	<div class="col-md-12">
            <div class="card">
                <div class="card-block table-responsive">
                    <table class="table table-striped table-bordered table-mini table-dt">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>Customer</th>
                                <th>Plan Name</th>
                                <th class="text-right">Price</th>
                                <th class="text-center">Time</th>
                                <th class="text-center">Transaction Id</th>
                                <th class="text-center">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($list as $key => $value) { ?>
                                <tr>
                                    <td class="text-center">#<?= $value['id'] ?></td>
                                    <th><?= get_customer($value['userid'])['fname'] ?> <?= get_customer($value['userid'])['lname'] ?></th>
                                    <th><?= $value['plan_name'] ?></th>
                                    <td class="text-right"><?= rs().$value['price'] ?></td>
                                    <td class="text-center"><?= $value['month'] ?> Month</td>
                                    <td class="text-center"><?= $value['tra_id'] ?></td>
                                    <td class="text-center"><?= getPretyDateTime($value['created_at']) ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>    
        </div>
	</div>
</div>