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

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Razorpay Key <span class="-req">*</span></label>
                            <input name="razorpay_key" type="text" class="form-control" placeholder="Razorpay Key" value="<?= set_value('razorpay_key',get_setting()['razorpay_key']); ?>" >
                            <?= form_error('razorpay_key') ?>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>2Factor API Key <span class="-req">*</span></label>
                            <input name="twofecturekey" type="text" class="form-control" placeholder="Razorpay Key" value="<?= set_value('twofecturekey',get_setting()['twofecturekey']); ?>" >
                            <?= form_error('twofecturekey') ?>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Admin Email for Receive Order Details <span class="-req">*</span></label>
                            <input name="admin_receive_email" type="text" class="form-control" placeholder="Admin Email for Receive Order Details" value="<?= set_value('admin_receive_email',get_setting()['admin_receive_email']); ?>" >
                            <?= form_error('admin_receive_email') ?>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Google Map Api Key <span class="-req">*</span></label>
                            <input name="gmap_api" type="text" class="form-control" placeholder="Google Map Api Key" value="<?= set_value('gmap_api',get_setting()['gmap_api']); ?>" >
                            <?= form_error('gmap_api') ?>
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
                            <label>Company UPI ID <span class="-req">*</span></label>
                            <input name="upi_id" type="text" class="form-control" placeholder="" value="<?= set_value('upi_id',get_setting()['upi_id']); ?>" >
                            <?= form_error('upi_id') ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Company UPI QR CODE <span class="-req">*</span></label>
                            <input name="upi_qr" type="file" onchange="readFileImage(this)" class="form-control" placeholder="">
                            <?= form_error('upi_qr') ?>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <img style="width: 100%;" src="<?= base_url('uploads/').get_setting()['upi_qr'] ?>">
                    </div>
                </div>
            </div>
            <div class="card-block">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Android Customer App Version <span class="-req">*</span></label>
                            <input name="cust_ver" type="text" class="form-control" placeholder="" value="<?= set_value('cust_ver',get_setting()['cust_ver']); ?>" >
                            <?= form_error('cust_ver') ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Android Service App Version <span class="-req">*</span></label>
                            <input name="serv_ver" type="text" class="form-control" placeholder="" value="<?= set_value('serv_ver',get_setting()['serv_ver']); ?>" >
                            <?= form_error('serv_ver') ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Android Delivery App Version <span class="-req">*</span></label>
                            <input name="deli_ver" type="text" class="form-control" placeholder="" value="<?= set_value('deli_ver',get_setting()['deli_ver']); ?>" >
                            <?= form_error('deli_ver') ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>iOS Customer App Version <span class="-req">*</span></label>
                            <input name="icust_ver" type="text" class="form-control" placeholder="" value="<?= set_value('icust_ver',get_setting()['icust_ver']); ?>" >
                            <?= form_error('icust_ver') ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>iOS Service App Version <span class="-req">*</span></label>
                            <input name="iserv_ver" type="text" class="form-control" placeholder="" value="<?= set_value('iserv_ver',get_setting()['iserv_ver']); ?>" >
                            <?= form_error('iserv_ver') ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>iOS Delivery App Version <span class="-req">*</span></label>
                            <input name="ideli_ver" type="text" class="form-control" placeholder="" value="<?= set_value('ideli_ver',get_setting()['ideli_ver']); ?>" >
                            <?= form_error('ideli_ver') ?>
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