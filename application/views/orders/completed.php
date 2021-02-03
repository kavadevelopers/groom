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
                                <th>Service Provider</th>
                                <th>Driver</th>
                                <th class="text-right">Price</th>
                                <th class="text-center">Type</th>
                                <th class="text-center">Category</th>
                                <th>Discription</th>
                                <th>Status</th>
                                <th class="text-center">Order Time</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($list as $key => $value) { ?>
                                <tr>
                                    <td class="text-center">#<?= $value['order_id'] ?></td>
                                    <th><?= get_customer($value['userid'])['fname'] ?> <?= get_customer($value['userid'])['lname'] ?></th>
                                    <th><?= get_service($value['service'])['fname'] ?> <?= get_service($value['service'])['lname'] ?></th>
                                    <th>
                                        <?= get_delivery($value['driver'])['fname'] ?> <?= get_delivery($value['driver'])['lname'] ?><br>
                                        <?= get_delivery($value['driver2'])['fname'] ?> <?= get_delivery($value['driver2'])['lname'] ?>
                                    </th>
                                    <td class="text-center"><?= rs().$value['price'] ?></td>
                                    <td class="text-center"><?= ucfirst($value['type']) ?><br><?= $value['order_type'] == "later"?"<b>Later</b>":"" ?></td>
                                    <td class="text-center"><?= _get_category($value['category'])['name'] ?></td>
                                    <td><?= subStrr($value['descr'],25) ?></td>
                                    <td><?= $value['notes'] ?></td>
                                    <td class="text-center"><?= getPretyDateTime($value['created_at']) ?><br><?= $value['order_type'] == "later"?"Delivery Date : ".$value['delivery_date']:"" ?></td>
                                    <td class="text-center">
                                        <a href="<?= base_url('orders/view/').$value['id'] ?>/completed" class="btn btn-success btn-mini" title="View">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="<?= base_url('orders/delete/').$value['id'] ?>/completed" class="btn btn-danger btn-mini btn-delete" title="Delete">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>    
        </div>
	</div>
</div>