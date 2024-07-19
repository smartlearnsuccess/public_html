<script type="text/javascript">
    $(document).ready(function () {
        $('#PackagePackageTypeF').click(function () {
            $('#paidExam').hide();
            $('#PackageShowAmount').val(0);
            $('#PackageAmount').val(0);
        });
        $('#PackagePackageTypeP').click(function () {
            $('#PackageShowAmount').val(null);
            $('#PackageAmount').val(null);
            $('#paidExam').show();
        });
    });
</script>
<div class="panel panel-custom">
    <div class="panel-heading"><?php echo __('Add Packages'); ?></div>
    <div class="panel-body"><?php echo $this->Session->flash(); ?>
        <?php echo $this->Form->create('Package', array('class' => 'form-horizontal', 'type' => 'file')); ?>
        <div class="form-group">
            <label for="subject_name" class="col-sm-3 control-label">
                <small><?php echo __('Type'); ?></small>
            </label>
            <div class="col-sm-9">
                <?php echo $this->Form->input('package_type', array('type' => 'radio', 'options' => $packageTypeArr, 'default' => '', 'required' => true, 'legend' => false, 'before' => '<label class="radio-inline">', 'separator' => '</label><label class="radio-inline">', 'after' => '</label>', 'label' => false, 'div' => false)); ?>
            </div>
        </div>
        <div class="form-group">
            <label for="group_name" class="col-sm-3 control-label">
                <small><?php echo __('Exams'); ?></small>
            </label>
            <div class="col-sm-9">
                <?php echo $this->Form->select("Exam.Exam", $exam, array('multiple' => true, 'label' => false, 'class' => 'form-control multiselectgrp', 'div' => false)); ?>
            </div>
        </div>
        <div class="form-group">
            <label for="subject_name" class="col-sm-3 control-label">
                <small><?php echo __('Name'); ?></small>
            </label>
            <div class="col-sm-9">
                <?php echo $this->Form->input('name', array('label' => false, 'class' => 'form-control', 'placeholder' => __('Name'), 'div' => false)); ?>
            </div>
        </div>
        <div id="paidExam">
            <div class="form-group">
                <label for="subject_name" class="col-sm-3 control-label">
                    <small><?php echo __('Amount'); ?></small>
                </label>
                <div class="col-sm-9">
                    <?php echo $this->Form->input('show_amount', array('label' => false, 'class' => 'form-control', 'placeholder' => __('Amount'), 'div' => false)); ?>
                </div>
            </div>
            <div class="form-group">
                <label for="subject_name" class="col-sm-3 control-label">
                    <small><?php echo __('Discounted Amount'); ?></small>
                </label>
                <div class="col-sm-9">
                    <?php echo $this->Form->input('amount', array('label' => false, 'class' => 'form-control', 'placeholder' => __('Discounted Amount'), 'div' => false)); ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="subject_name" class="col-sm-3 control-label">
                <small><?php echo __('Expired (Days)'); ?></small>
            </label>
            <div class="col-sm-9">
                <?php echo $this->Form->input('expiry_days', array('label' => false, 'class' => 'form-control', 'placeholder' => __('0 for unlimited days'), 'div' => false, 'min'=>0)); ?>
            </div>
        </div>
        <div class="form-group">
            <label for="group_name" class="col-sm-3 control-label">
                <small><?php echo __('Upload Photo'); ?></small>
            </label>
            <div class="col-sm-9">
                <?php echo $this->Form->input('photo', array('type' => 'file', 'label' => false, 'div' => false)); ?>
            </div>
        </div>
        <div class="form-group">
            <label for="subject_name" class="col-sm-3 control-label">
                <small><?php echo __('Description'); ?></small>
            </label>
            <div class="col-sm-9">
                <?php echo $this->Tinymce->input('description', array('class' => 'form-control', 'label' => false, 'placeholder' => __('Description')), array('language' => $configLanguage, 'directionality' => $dirType), 'full'); ?>
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