</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>

    
    <script type="text/javascript" src="<?= base_url() ?>asset/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>asset/bower_components/popper.js/js/popper.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>asset/bower_components/bootstrap/js/bootstrap.min.js"></script>
    <!-- jquery slimscroll js -->
    <script type="text/javascript" src="<?= base_url() ?>asset/bower_components/jquery-slimscroll/js/jquery.slimscroll.js"></script>
    <!-- modernizr js -->
    <script type="text/javascript" src="<?= base_url() ?>asset/bower_components/modernizr/js/modernizr.js"></script>

    <script src="<?= base_url() ?>asset/assets/js/pcoded.min.js"></script>
    <script src="<?= base_url() ?>asset/assets/js/vartical-layout.min.js"></script>
    <script src="<?= base_url() ?>asset/assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>asset/assets/js/script.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>asset/assets/js/SmoothScroll.js"></script>


    <!-- bootstrap-datepicker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
    <script src="<?= base_url() ?>asset/bower_components/daterangepicker/daterangepicker.js"></script>
    <script src="<?= base_url() ?>asset/bower_components/datepicker/bootstrap-datepicker.js"></script>

    <script src="<?= base_url() ?>asset/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="<?= base_url() ?>asset/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="<?= base_url() ?>asset/assets/pages/data-table/js/jszip.min.js"></script>
    <script src="<?= base_url() ?>asset/assets/pages/data-table/js/pdfmake.min.js"></script>
    <script src="<?= base_url() ?>asset/assets/pages/data-table/js/vfs_fonts.js"></script>
    <script src="<?= base_url() ?>asset/bower_components/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="<?= base_url() ?>asset/bower_components/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="<?= base_url() ?>asset/bower_components/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="<?= base_url() ?>asset/bower_components/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="<?= base_url() ?>asset/bower_components/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
    
    <!-- pnotify js -->
    <script type="text/javascript" src="<?= base_url() ?>asset/bower_components/pnotify/js/pnotify.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>asset/bower_components/pnotify/js/pnotify.desktop.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>asset/bower_components/pnotify/js/pnotify.buttons.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>asset/bower_components/pnotify/js/pnotify.confirm.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>asset/bower_components/pnotify/js/pnotify.callbacks.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>asset/bower_components/pnotify/js/pnotify.animate.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>asset/bower_components/pnotify/js/pnotify.history.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>asset/bower_components/pnotify/js/pnotify.mobile.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>asset/bower_components/pnotify/js/pnotify.nonblock.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>asset/bower_components/sweetalert/js/sweetalert.min.js"></script>

    <script src="<?= base_url(); ?>asset/bower_components/select2/js/select2.full.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>asset/assets/croppie/croppie.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>asset/script.js"></script>

    <!-- Masking js -->
    <script src="<?= base_url() ?>asset/assets/pages/form-masking/inputmask.js"></script>
    <script src="<?= base_url() ?>asset/assets/pages/form-masking/jquery.inputmask.js"></script>
    <script src="<?= base_url() ?>asset/assets/pages/form-masking/autoNumeric.js"></script>
    <script src="<?= base_url() ?>asset/assets/pages/form-masking/form-mask.js"></script>


    <script type="text/javascript">
        <?php if(!empty($this->session->flashdata('error'))){ ?>
            PNOTY('<?= $this->session->flashdata('error'); ?>','error');
        <?php $this->session->set_flashdata('error',''); } ?>
        <?php if(!empty($this->session->flashdata('success'))){ ?>
            PNOTY('<?= $this->session->flashdata('success'); ?>','success');
        <?php $this->session->set_flashdata('success',''); } ?>
        <?php if(!empty($this->session->flashdata('msg'))){ ?>
            PNOTY('<?= $this->session->flashdata('msg'); ?>','success');
        <?php $this->session->set_flashdata('msg',''); } ?>

        <?php if(!empty($this->session->flashdata('invoice'))){ ?>
            var popup = window.open('<?= base_url('pdf/invoice/').$this->session->flashdata('invoice') ?>','_blank');
            popupBlockerChecker.check(popup);
        <?php $this->session->set_flashdata('invoice',''); } ?>
    </script>

    <script type="text/javascript">
        function master_alert(string){
            PNOTY(string,'error');
            return false;
        }
        $(function(){
            $('.select2-container').addClass('m-t2');
        })
    </script>


    
    <script type="text/javascript">
        $(document).ready(function($) {
            if(window.innerWidth > 990){
                //$('#mobile-collapse').click(); 
            }
        });
    </script>

	</body>
</html>