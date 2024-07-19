<div class="header-content text-center">
    <h1><?php echo __('Re-Send Email/Mobile Verification'); ?></h1>
</div>
<?php echo $this->Form->create('Emailverification', array('url' => array('controller' => 'Emailverifications', 'action' => 'resendsub'), 'name' => 'post_req', 'id' => 'post_req', 'class' => 'form-horizontal', 'role' => 'form')); ?>
<div class="body-content">
    <?php echo $this->Session->flash(); ?>
    <div class="list-group">
        <div class="list-group-item">
            <?php echo $this->Form->input('email', array('type' => 'text', 'required' => true, 'label' => false, 'class' => 'form-control', 'placeholder' => __('Email or Mobile Number'), 'div' => false)); ?>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-sm-5">
            <button type="submit"
                class="btn btn-success btn-circle btn-block btn-shadow mbs"><?php echo __('Submit'); ?></button>
        </div>
    </div>
    <hr>
    <div class="form-group">
        <p>
            <?php echo $this->Html->link(__('Click here to login'), array('controller' => 'Login', 'action' => 'index'), array('id' => 'btn-register', 'class' => 'btn-link', 'escape' => false)); ?>
        </p>
    </div>
</div>
<?php echo $this->Form->end(); ?>