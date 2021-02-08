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
                                <th>Name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th class="text-center">Gender</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($list as $key => $value) { ?>
                                <tr>
                                    <td><?= $value['name'] ?></td>
                                    <td><?= $value['username'] ?></td>
                                    <td><?= $value['email'] ?></td>
                                    <td><?= $value['mobile'] ?></td>
                                    <td class="text-center">
                                        <?= ucfirst($value['gender']) ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if($value['block'] == ""){ ?>
                                            <a href="<?= base_url('users/status/').$value['id'] ?>/yes" class="btn btn-success btn-mini" onclick="return confirm('Are you sure?')" title="Click to block user">
                                                Active
                                            </a>
                                        <?php }else{ ?>
                                            <a href="<?= base_url('users/status/').$value['id'] ?>" class="btn btn-danger btn-mini" onclick="return confirm('Are you sure?')" title="Click Unblock user">
                                                Blocked
                                            </a>
                                        <?php } ?>
                                        <a href="<?= base_url('users/edit/').$value['id'] ?>" class="btn btn-primary btn-mini">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        <a href="<?= base_url('users/delete/').$value['id'] ?>" class="btn btn-danger btn-mini btn-delete">
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