<script type="text/javascript">
    $(window).on('load', function () {
        showPaymentStatus('<?php echo $mobileStatus;?>');
    });

    function showPaymentStatus(status) {
        Android.showStatus(status);
    }
</script>
<div class="col-sm-12">
    <div class="col-sm-9">
        <?php echo $this->Session->flash(); ?>
        <div class="page-heading">
            <div class="widget">
                <h2 class="widget-title"><?php echo __('Payment Status'); ?></h2>
            </div>
        </div>
    </div>
</div>