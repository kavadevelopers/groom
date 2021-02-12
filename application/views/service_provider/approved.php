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
                 <a href="<?= base_url('users/add') ?>" class="btn btn-primary btn-mini"><i class="fa fa-plus"></i> Add</a>  
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
                                <th>Register Type</th>
                                <th>Name</th>
                                <th>Business Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Services</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($list as $key => $value) { ?>
                                <tr>
                                    <td><?= ucfirst($value['rtype']) ?></td>
                                    <td><?= $value['firstname'].' '.$value['lastname'] ?></td>
                                    <td><?= $value['business'] ?></td>
                                    <td><?= $value['ccode'] ?>-<?= $value['phone'] ?></td>
                                    <td><?= $value['email'] ?></td>
                                    <td>
                                       <?php $servces = ""; foreach (explode(',', $value['services']) as $vl) {
                                           $servces .= getCategory($vl)['name'].",";
                                       } ?> 
                                       <?= rtrim($servces,','); ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if($value['block'] == ""){ ?>
                                            <a href="<?= base_url('service_provider/block/').$value['id'] ?>/yes" class="btn btn-success btn-mini" onclick="return confirm('Are you sure?');" title="Click to block user">
                                                Active
                                            </a>
                                        <?php }else{ ?>
                                            <a href="<?= base_url('service_provider/block/').$value['id'] ?>" class="btn btn-danger btn-mini" onclick="return confirm('Are you sure?');" title="Click to unblock user">
                                                Blocked
                                            </a>
                                        <?php } ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= base_url('service_provider/reject/').$value['id'] ?>/approved" class="btn btn-danger btn-mini" onclick="return confirm('Are you sure?');">
                                            Reject
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