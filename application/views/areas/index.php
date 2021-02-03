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
                 <a href="<?= base_url('areas/add') ?>" class="btn btn-primary btn-mini"><i class="fa fa-plus"></i> Add</a>  
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
                                <th>Area Name</th>
                                <th>Service Partner</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($list as $key => $value) { ?>
                            	<tr>
                            		<td><?= $value['name'] ?></td>
                            		<td><?= get_service($value['services'])['fname'] ?> <?= get_service($value['services'])['lname'] ?></td>
                            		<td class="text-center">
                            			<a href="<?= base_url('areas/edit/').$value['id'] ?>" class="btn btn-primary btn-mini" title="Edit">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        <a href="<?= base_url('areas/delete/').$value['id'] ?>" class="btn btn-danger btn-mini btn-delete" title="Delete">
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