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
                 
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    
    <form method="post" action="<?= base_url('users/save') ?>">
        <div class="card">
            <div class="card-block">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Name <span class="-req">*</span></label>
                            <input name="name" type="text" class="form-control" value="<?= set_value('name'); ?>" placeholder="Name">
                            <?= form_error('name') ?>
                        </div>
                    </div>  
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Username <span class="-req">*</span></label>
                            <input name="username" type="text" class="form-control" value="<?= set_value('username'); ?>" placeholder="Username">
                            <?= form_error('username') ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Email <span class="-req">*</span></label>
                            <input name="email" type="text" class="form-control" value="<?= set_value('email'); ?>" placeholder="Email">
                            <?= form_error('email') ?>
                        </div>
                    </div>  
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Phone <span class="-req">*</span></label>
                            <input name="phone" type="text" class="form-control" value="<?= set_value('phone'); ?>" placeholder="Phone">
                            <?= form_error('phone') ?>
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
        </div>
        <div class="card">
            <div class="card-header">
                <h5>Select User Rights</h5>
            </div>
            <div class="card-block">
                <div class="row">
                    <?php foreach ($rights_list as $key => $value) { ?>
                        <div class="col-md-3">
                            <div class="checkbox-fade fade-in-primary d-">
                                <label>
                                    <input type="checkbox" name="rights[]" value="<?= $value['id'] ?>">
                                    <span class="cr"><i class="cr-icon icofont icofont-ui-check txt-primary"></i></span>
                                    <span class="text-inverse"><?= $value['module_name'] ?></span>
                                </label>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-footer text-right">
                <a href="<?= base_url('users') ?>" class="btn btn-danger">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
                <button class="btn btn-success" type="submit">
                    <i class="fa fa-save"></i> Save
                </button>
            </div>
        </div>
    </form>
</div>