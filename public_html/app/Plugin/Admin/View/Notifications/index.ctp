<div class="page-title">
    <div class="title-env"><h1 class="title"><?php echo __('Send Notification'); ?></h1></div>
</div>
<div class="panel">
    <div class="panel-body"><?php echo $this->Session->flash(); ?>
        <?php echo $this->Form->create('Notification', array('url' => array('action' => 'index'), 'type' => 'post', 'name' => 'post_req', 'id' => 'post_req', 'class' => 'form-horizontal')); ?>
        <div class="form-group">
            <label for="group_name" class="col-sm-2 control-label">
                <small><?php echo __('Title'); ?></small>
            </label>
            <div class="col-sm-10">
                <?php echo $this->Form->input('title', array('label' => false, 'class' => 'form-control', 'placeholder' => __('Title'), 'div' => false, 'required'=>true)); ?>
            </div>           
        </div>
        <div class="form-group">
            <label for="group_name" class="col-sm-2 control-label">
                <small><?php echo __('Message'); ?></small>
            </label>
            <div class="col-sm-10">
                <?php echo $this->Form->textarea('message', array('class' => 'form-control', 'placeholder' => __('Message'), 'label' => false, 'required'=>true)); ?>
            </div>
        </div>
        <div class="form-group text-left">
            <div class="col-sm-offset-2 col-sm-7">
                <?php echo $this->Form->button('<span class="fa fa-send"></span>&nbsp;' . __('Send'), array('class' => 'btn btn-success', 'escape' => false)); ?>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>