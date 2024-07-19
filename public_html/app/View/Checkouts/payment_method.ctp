<script type="text/javascript">
    function showPaymentStatus(status) {
        Android.showStatus(false);
    }
    $(document).ready(function () {
        $("#paypal").click(function () {
            $("#loadingImage").show();
        });
        $("#ccavenue").click(function () {
            $("#loadingImage").show();
        });
        $("#payu").click(function () {
            $("#loadingImage").show();
        });
    });
</script>
<style type="text/css">
    .error_message {
        color: #ff0000;
        font-weight: 600;
        margin-bottom: 3px;
    }

    .success_message {
        color: #5fa724;
        font-weight: 600;
        margin-bottom: 3px;
    }
</style>
<section class="section mt80">
    <div class="container">
        <div class="col-sm-12" style="min-height: 420px;">
            <?php echo $this->Session->flash(); ?>
            <div class="col-sm-4">
                <div class="panel-heading">
                    <div class="widget">
                        <h2 class="widget-title"><?php echo __('Your Details'); ?></h2>
                    </div>
                </div>
                <div><strong><?php echo $userValue['Student']['name']; ?></strong></div>
                <div><?php echo $userValue['Student']['address']; ?></div>
                <div><?php echo __('Mobile'); ?> : <?php echo $userValue['Student']['phone']; ?></div>
                <div><?php echo __('Email'); ?> : <?php echo $userValue['Student']['email']; ?></div>
                <div><?php echo __('Total Amount'); ?> : <?php echo $currency . $totalAmount; ?></div>
            </div>
            <div class="col-sm-8">
                <div class="page-heading">
                    <div class="widget">
                        <h2 class="widget-title"><?php echo __('Payment Gateway'); ?></h2>
                    </div>
                </div>
				<center><div id="loadingImage" style="display: none;"><?php echo $this->Html->image('loading-lg.gif'); ?></div></center>
                <?php if ($products) {
                    if ($totalAmount == 0) {
                        ?>
                        <div
                                class="mrg-sm"><?php echo $this->Html->link(__('Free Checkout'), array('controller' => 'Checkouts', 'action' => 'paymentGateway', 'FREE'), array('class' => 'btn btn-lg btn-success', 'escape' => false)); ?></div><?php } else {
                        ?>
                        <?php
                        if ($CAE == true) { ?>
                            <div
                                    class="mrg-sm"><?php echo $this->Html->link($this->Html->image('ccavenue.png'), array('controller' => 'Checkouts', 'action' => 'paymentGateway', 'CCAVENUE'), array('class' => 'img-thumbnail', 'escape' => false, 'id' => 'ccavenue')); ?></div><?php } ?>
                        <?php if ($PME == true) { ?>
                            <div
                                    class="mrg-sm"><?php echo $this->Html->link($this->Html->image('payumoney.png'), array('controller' => 'Checkouts', 'action' => 'paymentGateway', 'PAYUMONEY'), array('class' => 'img-thumbnail', 'escape' => false, 'id' => 'payu')); ?></div><?php } ?>
                        <?php if ($PPL == true) { ?>
                            <div
                                    class="mrg-sm"><?php echo $this->Html->link($this->Html->image('paypal.png'), array('controller' => 'Checkouts', 'action' => 'paymentGateway', 'PAYPAL'), array('class' => 'img-thumbnail', 'escape' => false,'id'=>'paypal')); ?></div><?php }
                    }
                } ?>
            </div>
        </div>
    </div>
</section>
