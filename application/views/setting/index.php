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
    <div class="card">
        <form method="post" action="<?= base_url('setting/save') ?>" enctype="multipart/form-data">
            <div class="card-block">
                <div class="row">

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Company Name <span class="-req">*</span></label>
                            <input name="company" type="text" class="form-control" value="<?= set_value('company',get_setting()['name']); ?>" >
                            <?= form_error('company') ?>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Support Email <span class="-req">*</span></label>
                            <input name="support_email" type="text" class="form-control" value="<?= set_value('support_email',get_setting()['support_email']); ?>" >
                            <?= form_error('support_email') ?>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Support Mobile <span class="-req">*</span></label>
                            <input name="support_mobile" type="text" class="form-control" value="<?= set_value('support_mobile',get_setting()['support_mobile']); ?>" >
                            <?= form_error('support_mobile') ?>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Firebase Server Key <span class="-req">*</span></label>
                            <input name="fserverkey" type="text" class="form-control" value="<?= set_value('fserverkey',get_setting()['fserverkey']); ?>" >
                            <?= form_error('fserverkey') ?>
                        </div>
                    </div>
                    
                </div>
            </div>
            <div class="card-block">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>SMTP Host <span class="-req">*</span></label>
                            <input name="mail_host" type="text" class="form-control" placeholder="" value="<?= set_value('mail_host',get_setting()['mail_host']); ?>" >
                            <?= form_error('mail_host') ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>SMTP Username <span class="-req">*</span></label>
                            <input name="mail_username" type="text" class="form-control" placeholder="" value="<?= set_value('mail_username',get_setting()['mail_username']); ?>" >
                            <?= form_error('mail_username') ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>SMTP Password <span class="-req">*</span></label>
                            <input name="mail_pass" type="text" class="form-control" placeholder="" value="<?= set_value('mail_pass',get_setting()['mail_pass']); ?>" >
                            <?= form_error('mail_pass') ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>SMTP Port <span class="-req">*</span></label>
                            <input name="mail_port" type="text" class="form-control" placeholder="" value="<?= set_value('mail_port',get_setting()['mail_port']); ?>" >
                            <?= form_error('mail_port') ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-block">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Android App Version <span class="-req">*</span></label>
                            <input name="android_ver" type="text" class="form-control" placeholder="" value="<?= set_value('android_ver',get_setting()['android_ver']); ?>" >
                            <?= form_error('android_ver') ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>iOS App Version <span class="-req">*</span></label>
                            <input name="ios_ver" type="text" class="form-control" placeholder="" value="<?= set_value('ios_ver',get_setting()['ios_ver']); ?>" >
                            <?= form_error('ios_ver') ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <button class="btn btn-success" type="submit">
                    <i class="fa fa-save"></i> Save
                </button>
            </div>
        </form>
    </div>
</div>