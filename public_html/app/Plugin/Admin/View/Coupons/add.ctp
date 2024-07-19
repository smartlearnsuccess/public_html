<script type="text/javascript">
    $(document).ready(function () {
        $('#start_date').datetimepicker({
            locale: '<?php echo $configLanguage;?>',
            format: '<?php echo $dpFormat;?>'
        });
        $('#end_date').datetimepicker({
            locale: '<?php echo $configLanguage;?>', format: '<?php echo $dpFormat;?>', useCurrent: false //Important! See issue #1075
        });
        $("#start_date").on("dp.change", function (e) {
            $('#end_date').data("DateTimePicker").minDate(e.date);
        });
        $("#end_date").on("dp.change", function (e) {
            $('#start_date').data("DateTimePicker").maxDate(e.date);
        });
    });
</script>
<div class="panel panel-custom">
    <div class="panel-heading"><?php echo __('Add Coupons'); ?></div>
    <div class="panel-body"><?php echo $this->Session->flash(); ?>
        <?php echo $this->Form->create('Coupon', array('class' => 'form-horizontal')); ?>
        <div class="form-group">
            <label for="subject_name" class="col-sm-3 control-label">
                <small><?php echo __('Name'); ?></small>
            </label>
            <div class="col-sm-9">
                <?php echo $this->Form->input('name', array('label' => false, 'class' => 'form-control', 'placeholder' => __('A short name for the coupon'), 'div' => false)); ?>
            </div>
        </div>
        <div class="form-group">
            <label for="subject_name" class="col-sm-3 control-label">
                <small><?php echo __('Description'); ?></small>
            </label>
            <div class="col-sm-9">
                <?php echo $this->Form->input('description', array('label' => false, 'class' => 'form-control', 'placeholder' => __('A description of the coupon for the customer'), 'div' => false)); ?>
            </div>
        </div>
        <div class="form-group">
            <label for="subject_name" class="col-sm-3 control-label">
                <small><?php echo __('Coupon Amount'); ?></small>
            </label>
            <div class="col-sm-7">
                <?php echo $this->Form->input('amount', array('label' => false, 'class' => 'form-control', 'placeholder' => __('The value of the discount for the coupon, either fixed or select percent at the end of list'), 'div' => false)); ?>
            </div>
            <div class="col-sm-2">
                <?php echo $this->Form->select('discount_type', array('Amount' => 'Amount', 'Percent' => 'Percent'), array('empty' => false, 'label' => false, 'class' => 'form-control', 'div' => false)); ?>
            </div>
        </div>
        <div class="form-group">
            <label for="subject_name" class="col-sm-3 control-label">
                <small><?php echo __('Coupon Minimum Order'); ?></small>
            </label>
            <div class="col-sm-9">
                <?php echo $this->Form->input('min_amount', array('label' => false, 'class' => 'form-control', 'placeholder' => __('The minimum order value before the coupon is valid'), 'div' => false)); ?>
            </div>
        </div>
        <div class="form-group">
            <label for="subject_name" class="col-sm-3 control-label">
                <small><?php echo __('Coupon Code'); ?></small>
            </label>
            <div class="col-sm-9">
                <?php echo $this->Form->input('code', array('label' => false, 'class' => 'form-control', 'placeholder' => __('You can enter your own code here, or leave blank for an auto generated one.'), 'div' => false)); ?>
            </div>
        </div>
        <div class="form-group">
            <label for="subject_name" class="col-sm-3 control-label">
                <small><?php echo __('No. of Coupon'); ?></small>
            </label>
            <div class="col-sm-9">
                <?php echo $this->Form->input('coupon_no', array('label' => false, 'class' => 'form-control', 'placeholder' => __('The maximum number of times the coupon can be used, leave blank if you want no limit.'), 'div' => false)); ?>
            </div>
        </div>
        <div class="form-group">
            <label for="subject_name" class="col-sm-3 control-label">
                <small><?php echo __('Uses per Customer'); ?></small>
            </label>
            <div class="col-sm-9">
                <?php echo $this->Form->input('per_customer', array('label' => false, 'class' => 'form-control', 'placeholder' => __('Number of times a user can use the coupon, leave blank for no limit.'), 'div' => false)); ?>
            </div>
        </div>
        <div class="form-group">
            <label for="subject_name" class="col-sm-3 control-label">
                <small><?php echo __('Start Date'); ?></small>
            </label>
            <div class="col-sm-9">
                <div class="input-group date" id="start_date">
                    <?php echo $this->Form->input('start_date', array('type' => 'text', 'label' => false, 'class' => 'form-control', 'placeholder' => __('The date the coupon will be valid from'), 'div' => false)); ?>
                    <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="subject_name" class="col-sm-3 control-label">
                <small><?php echo __('End Date'); ?></small>
            </label>
            <div class="col-sm-9">
                <div class="input-group date" id="end_date">
                    <?php echo $this->Form->input('end_date', array('type' => 'text', 'label' => false, 'class' => 'form-control', 'placeholder' => __('The date the coupon expires'), 'div' => false)); ?>
                    <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                </div>
            </div>
        </div>
        <div class="form-group text-left">
            <div class="col-sm-offset-3 col-sm-7">
                <?php echo $this->Form->button('<span class="fa fa-plus-circle"></span>&nbsp;' . __('Save'), array('class' => 'btn btn-success', 'escpae' => false)); ?>
                <?php echo $this->Html->link('<span class="fa fa-close"></span>&nbsp;' . __('Close'), array('action' => 'index'), array('class' => 'btn btn-danger', 'escape' => false)); ?>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>