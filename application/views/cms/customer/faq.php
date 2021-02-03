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

        <?php if($_e == 0){ ?>
            <div class="col-md-4">
                <div class="card">
                    <form method="post" action="<?= base_url('customercms/save_faq') ?>">
                        <div class="card-block">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Question <span class="-req">*</span></label>
                                    <input name="que" type="text" class="form-control" value="<?= set_value('que'); ?>" placeholder="Question" required>
                                    <?= form_error('que') ?>
                                </div>
                            </div> 
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Answer <span class="-req">*</span></label>
                                    <textarea name="ans" type="text" class="form-control" value="<?= set_value('ans'); ?>" placeholder="Answer" required></textarea>
                                    <?= form_error('ans') ?>
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
        <?php }else{ ?>
            <div class="col-md-4">
                <div class="card">
                    <form method="post" action="<?= base_url('customercms/update_faq') ?>">
                        <div class="card-block">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Question <span class="-req">*</span></label>
                                    <input name="que" type="text" class="form-control" value="<?= set_value('que',$faq['que']); ?>" placeholder="Question" required>
                                    <?= form_error('que') ?>
                                </div>
                            </div> 
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Answer <span class="-req">*</span></label>
                                    <textarea name="ans" type="text" class="form-control" value="" placeholder="Answer" required><?= set_value('ans',$faq['ans']); ?></textarea>
                                    <?= form_error('ans') ?>
                                </div>
                            </div>                  
                        </div>
                        <div class="card-footer text-right">
                            <a href="<?= base_url('customercms/faq') ?>" class="btn btn-danger">
                                <i class="fa fa-arrow-left"></i> Back
                            </a>
                            <button class="btn btn-success">
                                <i class="fa fa-save"></i> Save
                            </button>
                        </div>
                        <input type="hidden" name="id" value="<?= $faq['id'] ?>">
                    </form>
                </div>
            </div>
        <?php } ?>
        

        <div class="col-md-8">
            <div class="card">
                <div class="card-block table-responsive">
                    <table class="table table-striped table-bordered table-mini table-dt">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>Question</th>
                                <th>Answer</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($list as $key => $value) { ?>
                                <tr>
                                    <td class="text-center"><?= $key + 1 ?></td>
                                    <td><?= $value['que'] ?></td>
                                    <td><?= nl2br($value['ans']) ?></td>
                                    <td class="text-center">
                                        <a href="<?= base_url('customercms/edit_faq/').$value['id'] ?>" class="btn btn-primary btn-mini">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        <a href="<?= base_url('customercms/delete_faq/').$value['id'] ?>" class="btn btn-danger btn-mini btn-delete">
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