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
        <form method="post" action="<?= base_url('deliverycms/privacy_save') ?>">
            <div class="card-block">
                <div class="row">

                    <div class="col-md-12">
                        <div class="form-group">
                            <textarea name="content" type="text" id="editor" class="form-control" value="" ><?= $content ?></textarea>
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

<script src="<?= base_url('asset/assets/ckeditor/ckeditor.js') ?>"></script>

<script type="text/javascript">
	var toolbarGroups = [
	    { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
	    { name: 'forms', groups: [ 'forms' ] },
	    '/',
	    { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
	    { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
	    '/',
	    { name: 'styles', groups: [ 'styles' ] },
	    { name: 'colors', groups: [ 'colors' ] },
	    { name: 'tools', groups: [ 'tools' ] },
	    { name: 'others', groups: [ 'others' ] }
	];
    CKEDITOR.replace( 'editor',{
	    toolbar : 'Basic',
	    toolbarGroups,
   	});
</script>