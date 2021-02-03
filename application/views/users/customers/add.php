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
        <form method="post" action="<?= base_url('customers/save') ?>">
            <div class="card-block">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>First Name <span class="-req">*</span></label>
                            <input name="fname" type="text" class="form-control" value="<?= set_value('fname'); ?>" placeholder="First Name">
                            <?= form_error('fname') ?>
                        </div>
                    </div>  
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Last Name <span class="-req">*</span></label>
                            <input name="lname" type="text" class="form-control" value="<?= set_value('lname'); ?>" placeholder="Last Name">
                            <?= form_error('lname') ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Mobile <span class="-req">*</span></label>
                            <input name="mobile" type="text" class="form-control" value="<?= set_value('mobile'); ?>" placeholder="Mobile">
                            <?= form_error('mobile') ?>
                        </div>
                    </div>  
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Password <span class="-req">*</span></label>
                            <input name="password" type="text" class="form-control" value="<?= set_value('password'); ?>" placeholder="Password">
                            <?= form_error('password') ?>
                        </div>
                    </div> 
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Gender <span class="-req">*</span></label>
                            <select class="form-control" name="gender">
                                <option value="">Select Gender</option>
                                <option value="Male" <?= set_value('gender')=='Male'?'selected':'' ?>>Male</option>
                                <option value="Female" <?= set_value('gender')=='Female'?'selected':'' ?>>Female</option>
                            </select>
                            <?= form_error('gender') ?>
                        </div>
                    </div>  
                </div>
            </div>
            <div class="card-footer text-right">
                <a href="<?= base_url('customers') ?>" class="btn btn-danger">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
                <button class="btn btn-success" type="submit">
                    <i class="fa fa-save"></i> Save
                </button>
            </div>
        </form>
    </div>
</div>