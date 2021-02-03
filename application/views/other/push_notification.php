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
        <form method="post" action="<?= base_url('other/send_pushnotification') ?>">
            <div class="card-block">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>User Type <span class="-req">*</span></label>
                            <select class="form-control" name="user_type" required>
                                <option value="">-- Select --</option>
                                <option value="customer">Customers</option>
                                <option value="service">Service Providers</option>
                                <option value="delivery">Drivers</option>
                            </select>
                        </div>
                    </div> 
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Title <span class="-req">*</span></label>
                            <input name="title" type="text" class="form-control" value="<?= set_value('title'); ?>" placeholder="Title" required>
                            <?= form_error('title') ?>
                        </div>
                    </div>  
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Message <span class="-req">*</span></label>
                            <textarea name="message" type="text" class="form-control" value="<?= set_value('message'); ?>" placeholder="Message" rows="7" required></textarea>
                            <?= form_error('message') ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <button class="btn btn-success" type="submit">
                    <i class="fa fa-send"></i> Send
                </button>
            </div>
        </form>
    </div>
</div>