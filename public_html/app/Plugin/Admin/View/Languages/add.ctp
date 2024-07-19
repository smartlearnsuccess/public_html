<div class="page-title">
    <div class="title-env"><h1 class="title"><?php echo __('Add Question Language'); ?></h1></div>
</div>
<div class="panel">
    <div class="panel-body"><?php echo $this->Session->flash(); ?>
        <?php echo $this->Form->create('Language', array('class' => 'form-horizontal')); ?>
        <div class="form-group">
            <label for="group_name" class="col-sm-3 control-label">
                <small><?php echo __('Name'); ?></small>
            </label>
            <div class="col-sm-9">
                <?php echo $this->Form->input('name', array('label' => false, 'class' => 'form-control', 'placeholder' => __('Name'), 'div' => false)); ?>
            </div>
        </div>
        <div class="form-group">
            <label for="group_name" class="col-sm-3 control-label">
                <small><?php echo __('True'); ?></small>
            </label>
            <div class="col-sm-9">
                <?php echo $this->Form->input('value1', array('label' => false, 'class' => 'form-control', 'placeholder' => __('Language True'), 'div' => false)); ?>
            </div>
        </div>
        <div class="form-group">
            <label for="group_name" class="col-sm-3 control-label">
                <small><?php echo __('False'); ?></small>
            </label>
            <div class="col-sm-9">
                <?php echo $this->Form->input('value2', array('label' => false, 'class' => 'form-control', 'placeholder' => __('Language False'), 'div' => false)); ?>
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