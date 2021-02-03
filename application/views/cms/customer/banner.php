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
        <div class="col-md-4">
            <div class="card">
                <form method="post" action="<?= base_url('customercms/save_banner') ?>" enctype="multipart/form-data">
                    <div class="card-block">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Banner <span class="-req">*</span></label>
                                <input name="image" type="file" class="form-control" onchange="readFileImage(this)" value="<?= set_value('image'); ?>" required>
                                <?= form_error('image') ?>
                            </div>
                        </div>                
                    </div>
                    <div class="card-footer text-right">
                        <button class="btn btn-success">
                            <i class="fa fa-plus"></i> Add
                        </button>
                    </div>
                </form>
            </div>
        </div>
        

        <div class="col-md-8">
            <div class="card">
                <div class="card-block table-responsive">
                    <table class="table table-striped table-bordered table-mini table-dt">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Image</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($list as $key => $value) { ?>
                                <tr>
                                    <td class="text-center"><?= $key + 1 ?></td>
                                    <td class="text-center">
                                        <img src="<?= base_url('uploads/banner/').$value['image'] ?>" style="max-width: 80px;">
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= base_url('customercms/delete_banner/').$value['id'] ?>" class="btn btn-danger btn-mini btn-delete">
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