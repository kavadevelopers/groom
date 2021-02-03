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
    	<div class="col-md-12">
            <div class="card">
                <div class="card-block table-responsive">
                    <table class="table table-striped table-bordered table-mini table-dt">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>Customer</th>
                                <th>Service Provider</th>
                                <th class="text-center">Type</th>
                                <th class="text-center">Category</th>
                                <th>Discription</th>
                                <th>Status</th>
                                <th class="text-center">Order Time</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($list as $key => $value) { ?>
                                <tr>
                                    <td class="text-center">#<?= $value['order_id'] ?></td>
                                    <th><?= get_customer($value['userid'])['fname'] ?> <?= get_customer($value['userid'])['lname'] ?></th>
                                    <th><?= get_service($value['service'])['fname'] ?> <?= get_service($value['service'])['lname'] ?></th>
                                    <td class="text-center"><?= ucfirst($value['type']) ?><br><?= $value['order_type'] == "later"?"<b>Later</b>":"" ?></td>
                                    <td class="text-center"><?= _get_category($value['category'])['name'] ?></td>
                                    <td><?= subStrr($value['descr'],25) ?></td>
                                    <td><?= $value['notes'] ?></td>
                                    <td class="text-center"><?= getPretyDateTime($value['created_at']) ?><br><?= $value['order_type'] == "later"?"Delivery Date : ".$value['delivery_date']:"" ?></td>
                                    <td class="text-center">
                                        <a href="<?= base_url('orders/view/').$value['id'] ?>/new" class="btn btn-success btn-mini" title="View">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <button class="btn btn-secondary btn-mini" title="Edit Price" onclick="changePrice('<?= $value["id"] ?>','new','<?= $value["price"]  ?>')">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <a href="<?= base_url('orders/complete/').$value['id'] ?>/new" class="btn btn-info btn-mini" title="Complete Order" onclick="return confirm('Are you sure want to Complete Order ?')">
                                            <i class="fa fa-check"></i>
                                        </a>
                                        <a href="<?= base_url('orders/cancel/').$value['id'] ?>/new" class="btn btn-warning btn-mini" title="Cancel Order" onclick="return confirm('Are you sure want to Cancel Order ?')">
                                            <i class="fa fa-times"></i>
                                        </a>
                                        <button class="btn btn-primary btn-mini assignServiceBtn" data-id="<?= $value["id"] ?>" data-type="<?= $value["type"] ?>" data-category="<?= $value["category"] ?>" title="Assign Service Provider">
                                            <i class="fa fa-send"></i>
                                        </button>
                                        <a href="<?= base_url('orders/delete/').$value['id'] ?>/new" class="btn btn-danger btn-mini btn-delete" title="Delete">
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

<div class="modal fade" id="modalAssignService" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form method="post" action="<?= base_url('orders/assign_service') ?>">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Assign Service Provider</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Service Provider</label>
                        <select class="form-control select2n" name="service" id="modalServiceProvider" required>
                            <option value="">-- Select --</option>
                            <?php foreach (getServiceProviders() as $skey => $svalue) { ?>
                                <option value="<?= $svalue['id'] ?>"><?= $svalue['fname'] ?> <?= $svalue['lname'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group" id="modalPriceContainer" style="display: none;">
                        <label>Price</label>
                        <input type="text" name="price" class="form-control decimal-num" placeholder="Price" id="modalPrice">
                    </div>
                    <input type="hidden" name="id" id="modalOrderId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="modal fade" id="modalChangePrice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form method="post" action="<?= base_url('orders/update_price') ?>">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Change Amount</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Amount</label>
                        <input type="text" name="price" id="priceModal" class="form-control decimal-num" placeholder="Amount">
                    </div>
                    <input type="hidden" name="id" id="modalEditOrderId">
                    <input type="hidden" name="type" id="modalEditOrderType">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    $(function(){
        $('.assignServiceBtn').click(function() {
            _this = $(this);
            _this.html('<i class="fa fa-circle-o-notch fa-spin"></i>');
            _this.attr('disabled',true);
            category    = _this.data('category');
            id      = _this.data('id');
            _type      = _this.data('type');
            $('#modalServiceProvider').html('');
            $.ajax({
                type: "POST",
                data : {id : id,category : category},
                url : "<?= base_url('orders/get_service_provider'); ?>",
                cache : false,
                success: function(out)
                {
                    _this.html('<i class="fa fa-send"></i>');
                    _this.removeAttr('disabled');
                    $('#modalServiceProvider').html(out);
                    $('.select2n').select2({
                        dropdownParent: $('#modalAssignService .modal-content')
                    });
                    $('#modalAssignService').modal('show');
                    $('#modalOrderId').val(id);
                    if(_type == 'delivery'){
                        $('#modalPriceContainer').show(); 
                        $('#modalPrice').val(''); 
                        $('#modalPrice').prop('required',true); 
                    }else{
                        $('#modalPriceContainer').hide(); 
                        $('#modalPrice').val(''); 
                        $('#modalPrice').removeProp('required');
                    }
                }
            });
        });
    });
    function changePrice(id,type,price){
        $('#modalEditOrderId').val(id);
        $('#modalEditOrderType').val(type);
        $('#priceModal').val(price);
        $('#modalChangePrice').modal('show');
    }
</script>