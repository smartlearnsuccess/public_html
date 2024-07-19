<div <?php if (!$isError){ ?>class="container"<?php } ?>>
    <div class="panel panel-custom mrg">
        <div class="panel-heading"><?php echo __('Edit Coupons'); ?><?php if (!$isError) { ?>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><?php } ?>
        </div>
        <div class="panel-body"><?php echo $this->Session->flash(); ?>
            <?php echo $this->Form->create('Coupon', array('class' => 'form-horizontal')); ?>
            <?php foreach ($Coupon

            as $k => $post):
            $id = $post['Coupon']['id'];
            $form_no = $k + 1; ?>
            <script type="text/javascript">
                $(document).ready(function () {
                    $('#start_date<?php echo $id;?>').datetimepicker({
                        locale: '<?php echo $configLanguage;?>',
                        format: '<?php echo $dpFormat;?>'
                    });
                    $('#end_date<?php echo $id;?>').datetimepicker({
                        locale: '<?php echo $configLanguage;?>',
                        format: '<?php echo $dpFormat;?>',
                        useCurrent: false //Important! See issue #1075
                    });
                    $("#start_date<?php echo $id;?>").on("dp.change", function (e) {
                        $('#end_date<?php echo $id;?>').data("DateTimePicker").minDate(e.date);
                    });
                    $("#end_date<?php echo $id;?>").on("dp.change", function (e) {
                        $('#start_date<?php echo $id;?>').data("DateTimePicker").maxDate(e.date);
                    });
                });
            </script>
            <div class="panel panel-default">
                <div class="panel-heading"><strong>
                        <small class="text-danger"><?php echo __('Form'); ?><?php echo $form_no ?></small>
                    </strong></div>
                <div class="panel-body"><?php echo $this->Session->flash(); ?>
                    <div class="form-group">
                        <label for="subject_name" class="col-sm-3 control-label">
                            <small><?php echo __('Name'); ?></small>
                        </label>
                        <div class="col-sm-9">
                            <?php echo $this->Form->input("$k.Coupon.name", array('label' => false, 'class' => 'form-control', 'placeholder' => __('A short name for the coupon'), 'div' => false)); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="subject_name" class="col-sm-3 control-label">
                            <small><?php echo __('Description'); ?></small>
                        </label>
                        <div class="col-sm-9">
                            <?php echo $this->Form->input("$k.Coupon.description", array('label' => false, 'class' => 'form-control', 'placeholder' => __('A description of the coupon for the customer'), 'div' => false)); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="subject_name" class="col-sm-3 control-label">
                            <small><?php echo __('Coupon Amount'); ?></small>
                        </label>
                        <div class="col-sm-7">
                            <?php echo $this->Form->input("$k.Coupon.amount", array('label' => false, 'class' => 'form-control', 'placeholder' => __('The value of the discount for the coupon, either fixed or select percent at the end of list'), 'div' => false)); ?>
                        </div>
                        <div class="col-sm-2">
                            <?php echo $this->Form->select("$k.Coupon.discount_type", array('Amount' => 'Amount', 'Percent' => 'Percent'), array('empty' => false, 'label' => false, 'class' => 'form-control', 'default' => 'Amount', 'div' => false)); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="subject_name" class="col-sm-3 control-label">
                            <small><?php echo __('Coupon Minimum Order'); ?></small>
                        </label>
                        <div class="col-sm-9">
                            <?php echo $this->Form->input("$k.Coupon.min_amount", array('label' => false, 'class' => 'form-control', 'placeholder' => __('The minimum order value before the coupon is valid'), 'div' => false)); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="subject_name" class="col-sm-3 control-label">
                            <small><?php echo __('Coupon Code'); ?></small>
                        </label>
                        <div class="col-sm-9">
                            <?php echo $this->Form->input("$k.Coupon.code", array('label' => false, 'class' => 'form-control', 'placeholder' => __('You can enter your own code here, or leave blank for an auto generated one.'), 'div' => false)); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="subject_name" class="col-sm-3 control-label">
                            <small><?php echo __('No. of Coupon'); ?></small>
                        </label>
                        <div class="col-sm-9">
                            <?php echo $this->Form->input("$k.Coupon.coupon_no", array('label' => false, 'class' => 'form-control', 'placeholder' => __('The maximum number of times the coupon can be used, leave blank if you want no limit.'), 'div' => false)); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="subject_name" class="col-sm-3 control-label">
                            <small><?php echo __('Uses per Customer'); ?></small>
                        </label>
                        <div class="col-sm-9">
                            <?php echo $this->Form->input("$k.Coupon.per_customer", array('label' => false, 'class' => 'form-control', 'placeholder' => __('Number of times a user can use the coupon, leave blank for no limit.'), 'div' => false)); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="subject_name" class="col-sm-3 control-label">
                            <small><?php echo __('Start Date'); ?></small>
                        </label>
                        <div class="col-sm-9">
                            <div class="input-group date" id="start_date<?php echo $id; ?>">
                                <?php echo $this->Form->input("$k.Coupon.start_date", array('type' => 'text', 'value' => $this->Time->format($dtFormat, $post['Coupon']['start_date']), 'label' => false, 'class' => 'form-control', 'placeholder' => __('The date the coupon will be valid from'), 'div' => false)); ?>
                                <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="subject_name" class="col-sm-3 control-label">
                            <small><?php echo __('End Date'); ?></small>
                        </label>
                        <div class="col-sm-9">
                            <div class="input-group date" id="end_date<?php echo $id; ?>">
                                <?php echo $this->Form->input("$k.Coupon.end_date", array('type' => 'text', 'value' => $this->Time->format($dtFormat, $post['Coupon']['end_date']), 'label' => false, 'class' => 'form-control', 'placeholder' => __('The date the coupon expires'), 'div' => false)); ?>
                                <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="group_name" class="col-sm-3 control-label"><?php echo __('Status'); ?></label>
                        <div class="col-sm-9">
                            <?php echo $this->Form->select("$k.Coupon.status", array("Active" => __('Active'), "Suspend" => __('Suspend')), array('empty' => null, 'label' => false, 'class' => 'form-control', 'div' => false)); ?>
                        </div>
                        <div class="form-group text-left">
                            <div class="col-sm-offset-3 col-sm-7">
                                <?php echo $this->Form->input("$k.Coupon.id", array('type' => 'hidden')); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php unset($post); ?>
                <div class="form-group text-left">
                    <div class="col-sm-offset-3 col-sm-7">
                        <?php echo $this->Form->button('<span class="fa fa-refresh"></span>&nbsp;' . __('Update'), array('class' => 'btn btn-success', 'escape' => false)); ?>
                        <?php if (!$isError) { ?>
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><span
                                    class="fa fa-remove"></span>&nbsp;<?php echo __('Cancel'); ?></button><?php } else {
                            echo $this->Html->link('<span class="fa fa-close"></span>&nbsp;' . __('Close'), array('action' => 'index'), array('class' => 'btn btn-danger', 'escape' => false));
                        } ?>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
