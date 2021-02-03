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
        </div>
    </div>
</div>

<div class="page-body">
    <div class="card">
        <form method="post" action="<?= base_url('delivery/update') ?>">
            <div class="card-block">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>First Name <span class="-req">*</span></label>
                            <input name="fname" type="text" class="form-control" value="<?= set_value('fname',$user['fname']); ?>" placeholder="First Name">
                            <?= form_error('fname') ?>
                        </div>
                    </div>  
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Last Name <span class="-req">*</span></label>
                            <input name="lname" type="text" class="form-control" value="<?= set_value('lname',$user['lname']); ?>" placeholder="Last Name">
                            <?= form_error('lname') ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Mobile <span class="-req">*</span></label>
                            <input name="mobile" type="text" class="form-control" value="<?= set_value('mobile',$user['mobile']); ?>" placeholder="Mobile">
                            <?= form_error('mobile') ?>
                        </div>
                    </div>   
                </div>
            </div>
            <div class="card-footer text-right">
                <a href="<?= base_url('delivery/approved') ?>" class="btn btn-danger">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
                <button class="btn btn-success" type="submit">
                    <i class="fa fa-save"></i> Save
                </button>
                <input type="hidden" name="id" value="<?= $user['id'] ?>">
            </div>
        </form>
    </div>
</div>