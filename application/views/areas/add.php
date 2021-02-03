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
<?php if(!empty(get_setting()['gmap_api'])){ ?>
    <script src='https://maps.googleapis.com/maps/api/js?key=<?= get_setting()['gmap_api'] ?>&v=3.exp&amp;sensor=false&&libraries=drawing'></script>
<?php }else{ ?>
    <script src='https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=false&&libraries=drawing'></script>
<?php } ?>
<div class="page-body">
    <div class="card">
        <form method="post" action="<?= base_url('areas/save') ?>">
            <div class="card-block">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Area Name <span class="-req">*</span></label>
                            <input name="name" type="text" class="form-control" value="<?= set_value('name'); ?>" placeholder="Area Name" required>
                            <?= form_error('name') ?>
                        </div>
                        <div class="form-group">
                            <label>Service Providers<span class="-req">*</span></label>
                            <div class="form-control">
                                <?php foreach ($service_partners as $key => $value) { ?>
                                    <div class="checkbox-fade fade-in-primary d-">
                                        <label>
                                            <input type="checkbox" name="service[]" value="<?= $value['id'] ?>">
                                            <span class="cr"><i class="cr-icon icofont icofont-ui-check txt-primary"></i></span>
                                            <span class="text-inverse"><?= $value['fname'] ?> <?= $value['lname'] ?></span>
                                        </label>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div> 
                    <div class="col-md-8">
                        <div id="map-canvas"></div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <input type="hidden" name="latlon" id="latlon">
                <a href="<?= base_url('areas') ?>" class="btn btn-danger">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
                <button class="btn btn-success" type="submit">
                    <i class="fa fa-save"></i> Save
                </button>
            </div>
        </form>
    </div>
</div>


<style type="text/css">
    #map-canvas{
        width: auto;
        height: 400px;
    }
</style>

<script type="text/javascript">
    var bermudaTriangle;
    initialize();
    getPolygonCoords();
    function initialize() {
        var myLatLng = new google.maps.LatLng(18.453308, 73.812018);
        var mapOptions = {
            zoom: 10,
            center: myLatLng,
            mapTypeId: google.maps.MapTypeId.RoadMap
        };
        var map = new google.maps.Map(document.getElementById('map-canvas'),mapOptions);
        var triangleCoords = [
            new google.maps.LatLng(18.583129,73.73829),
            new google.maps.LatLng(18.419037,73.743783),
            new google.maps.LatLng(18.426855,74.028055),
            new google.maps.LatLng(18.601352,74.012949)
        ];
        bermudaTriangle = new google.maps.Polygon({
            paths: triangleCoords,
            draggable: true,
            editable: true,
            strokeColor: '#FF0000',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: '#FF0000',
            fillOpacity: 0.35
        });
        bermudaTriangle.setMap(map);
        google.maps.event.addListener(bermudaTriangle, "dragend", getPolygonCoords);
        google.maps.event.addListener(bermudaTriangle.getPath(), "insert_at", getPolygonCoords);
        google.maps.event.addListener(bermudaTriangle.getPath(), "remove_at", getPolygonCoords);
        google.maps.event.addListener(bermudaTriangle.getPath(), "set_at", getPolygonCoords);
    }
    function getPolygonCoords() {
        var len = bermudaTriangle.getPath().getLength();
        var htmlStr = "";
        for (var i = 0; i < len; i++) {
            htmlStr += bermudaTriangle.getPath().getAt(i).toUrlValue(5) + "-";
        }
        $('#latlon').val(htmlStr);
    }
</script>