<style type="text/css">
    /* bootstrap hack: fix content width inside hidden tabs */
    .tab-content > .tab-pane, .pill-content > .pill-pane {
        display: block; /* undo display:none          */
        height: 0; /* height:0 is also invisible */
        overflow-y: hidden; /* no-overflow                */
    }

    .tab-content > .active, .pill-content > .active {
        height: auto; /* let the content decide it  */
    }

    /* bootstrap hack end */
</style>

<div class="row">
    <div class="col-md-12">
        <div class="page-title">
            <div class="title-env"><h1 class="title"><?php echo __('Payment Options'); ?></h1></div>
        </div>
        <ul class="nav nav-tabs">
            <li class="active"><a href="#paypal" data-toggle="tab"><?php echo __('Paypal'); ?></a></li>
            <li><a href="#ccavenue" data-toggle="tab"><?php echo __('CCAvenue'); ?></a></li>
            <li><a href="#payumoney" data-toggle="tab"><?php echo __('PayUmoney'); ?></a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">

            <div class="tab-pane active" id="paypal">
                <div class="panel">
                    <div class="panel-body"><?php echo $this->Session->flash(); ?>
                        <?php echo $this->Form->create('PaymentSetting', array('url' => array('action' => 'index'), 'type' => 'post', 'class' => 'form-horizontal')); ?>
                        <div class="form-group">
                            <label for="site_name"
                                   class="col-sm-3 control-label"><?php echo __('Name'); ?></label>
                            <div class="col-sm-9">
                                <?php echo $this->Form->input('name', array('value' => $post[0]['PaymentSetting']['name'], 'label' => false, 'class' => 'form-control', 'placeholder' => __('Name'), 'div' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="site_name" class="col-sm-3 control-label"><?php echo __('User Name'); ?></label>
                            <div class="col-sm-9">
                                <?php echo $this->Form->input('username', array('value' => $post[0]['PaymentSetting']['username'], 'label' => false, 'class' => 'form-control', 'placeholder' => __('User Name'), 'div' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="site_name" class="col-sm-3 control-label"><?php echo __('Password'); ?></label>
                            <div class="col-sm-9">
                                <?php echo $this->Form->input('password', array('value' => $post[0]['PaymentSetting']['password'], 'label' => false, 'class' => 'form-control', 'placeholder' => __('Password'), 'div' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="site_name" class="col-sm-3 control-label"><?php echo __('Signature'); ?></label>
                            <div class="col-sm-9">
                                <?php echo $this->Form->input('signature', array('value' => $post[0]['PaymentSetting']['signature'], 'label' => false, 'class' => 'form-control', 'placeholder' => __('Signature'), 'div' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="site_name"
                                   class="col-sm-3 control-label"><?php echo __('Sandbox Mode'); ?></label>
                            <div class="col-sm-9">
                                <?php echo $this->Form->input('sandbox_mode', array('checked' => $post[0]['PaymentSetting']['sandbox_mode'], 'label' => '&nbsp;' . __('True'), 'class' => '', 'div' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="site_name" class="col-sm-3 control-label"><?php echo __('Published'); ?></label>
                            <div class="col-sm-9">
                                <?php echo $this->Form->input('published', array('value' => $post[0]['PaymentSetting']['published'], 'type' => 'radio', 'options' => array("Yes" => __("Yes"), "No" => __("No")), 'legend' => false, 'before' => '<label class="radio-inline">', 'separator' => '</label><label class="radio-inline">', 'label' => false, 'div' => false, 'class' => '')); ?>
                            </div>
                        </div>
                        <?php echo $this->Form->input('type', array('type' => 'hidden', 'value' => 'PPL')); ?>
                        <div class="form-group text-left">
                            <div class="col-sm-offset-3 col-sm-7">
                                <?php echo $this->Form->button('<span class="fa fa-refresh"></span>&nbsp;' . __('Save Settings'), array('class' => 'btn btn-success', 'escpae' => false)); ?>
                            </div>
                        </div>
                        <?php echo $this->Form->end(); ?>
                    </div>
                </div>
            </div>


            <div class="tab-pane" id="ccavenue">
                <div class="panel">
                    <div class="panel-body"><?php echo $this->Session->flash(); ?>
                        <?php echo $this->Form->create('PaymentSetting', array('url' => array('action' => 'index'), 'type' => 'post', 'class' => 'form-horizontal')); ?>
                        <div class="form-group">
                            <label for="site_name"
                                   class="col-sm-3 control-label"><?php echo __('Name'); ?></label>
                            <div class="col-sm-9">
                                <?php echo $this->Form->input('name', array('value' => $post[1]['PaymentSetting']['name'], 'label' => false, 'class' => 'form-control', 'placeholder' => __('Name'), 'div' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="site_name"
                                   class="col-sm-3 control-label"><?php echo __('Merchant Id'); ?></label>
                            <div class="col-sm-9">
                                <?php echo $this->Form->input('username', array('value' => $post[1]['PaymentSetting']['username'], 'label' => false, 'class' => 'form-control', 'placeholder' => __('Merchant Id'), 'div' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="site_name"
                                   class="col-sm-3 control-label"><?php echo __('Access Code'); ?></label>
                            <div class="col-sm-9">
                                <?php echo $this->Form->input('password', array('value' => $post[1]['PaymentSetting']['password'], 'label' => false, 'class' => 'form-control', 'placeholder' => __('Access Code'), 'div' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="site_name"
                                   class="col-sm-3 control-label"><?php echo __('Working Key'); ?></label>
                            <div class="col-sm-9">
                                <?php echo $this->Form->input('signature', array('value' => $post[1]['PaymentSetting']['signature'], 'label' => false, 'class' => 'form-control', 'placeholder' => __('Working Key'), 'div' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="site_name"
                                   class="col-sm-3 control-label"><?php echo __('Gateway URL'); ?></label>
                            <div class="col-sm-9">
                                <?php echo $this->Form->input('gateway_url', array('value' => $post[1]['PaymentSetting']['gateway_url'], 'label' => false, 'class' => 'form-control', 'placeholder' => __('Gateway URL'), 'div' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="site_name" class="col-sm-3 control-label"><?php echo __('Published'); ?></label>
                            <div class="col-sm-9">
                                <?php echo $this->Form->input('published', array('value' => $post[1]['PaymentSetting']['published'], 'type' => 'radio', 'options' => array("Yes" => __("Yes"), "No" => __("No")), 'legend' => false, 'before' => '<label class="radio-inline">', 'separator' => '</label><label class="radio-inline">', 'label' => false, 'div' => false, 'class' => '')); ?>
                            </div>
                        </div>
                        <?php echo $this->Form->input('type', array('type' => 'hidden', 'value' => 'CAE')); ?>
                        <div class="form-group text-left">
                            <div class="col-sm-offset-3 col-sm-7">
                                <?php echo $this->Form->button('<span class="fa fa-refresh"></span>&nbsp;' . __('Save Settings'), array('class' => 'btn btn-success', 'escpae' => false)); ?>
                            </div>
                        </div>
                        <?php echo $this->Form->end(); ?>
                    </div>
                </div>
            </div>


            <div class="tab-pane" id="payumoney">
                <div class="panel">
                    <div class="panel-body"><?php echo $this->Session->flash(); ?>
                        <?php echo $this->Form->create('PaymentSetting', array('url' => array('action' => 'index'), 'type' => 'post', 'class' => 'form-horizontal')); ?>
                        <div class="form-group">
                            <label for="site_name"
                                   class="col-sm-3 control-label"><?php echo __('Name'); ?></label>
                            <div class="col-sm-9">
                                <?php echo $this->Form->input('name', array('value' => $post[2]['PaymentSetting']['name'], 'label' => false, 'class' => 'form-control', 'placeholder' => __('Name'), 'div' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="site_name"
                                   class="col-sm-3 control-label"><?php echo __('Merchant Id'); ?></label>
                            <div class="col-sm-9">
                                <?php echo $this->Form->input('username', array('value' => $post[2]['PaymentSetting']['username'], 'label' => false, 'class' => 'form-control', 'placeholder' => __('Merchant Id'), 'div' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="site_name"
                                   class="col-sm-3 control-label"><?php echo __('Merchant Key'); ?></label>
                            <div class="col-sm-9">
                                <?php echo $this->Form->input('password', array('value' => $post[2]['PaymentSetting']['password'], 'label' => false, 'class' => 'form-control', 'placeholder' => __('Merchant Key'), 'div' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="site_name"
                                   class="col-sm-3 control-label"><?php echo __('Merchant Salt'); ?></label>
                            <div class="col-sm-9">
                                <?php echo $this->Form->input('signature', array('value' => $post[2]['PaymentSetting']['signature'], 'label' => false, 'class' => 'form-control', 'placeholder' => __('Merchant Salt'), 'div' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="site_name"
                                   class="col-sm-3 control-label"><?php echo __('Service Provider'); ?></label>
                            <div class="col-sm-9">
                                <?php echo $this->Form->input('gateway_url', array('value' => $post[2]['PaymentSetting']['gateway_url'], 'label' => false, 'class' => 'form-control', 'placeholder' => __('Service Provider'), 'div' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="site_name"
                                   class="col-sm-3 control-label"><?php echo __('Header Authorization'); ?></label>
                            <div class="col-sm-9">
                                <?php echo $this->Form->input('authorization', array('value' => $post[2]['PaymentSetting']['authorization'], 'label' => false, 'class' => 'form-control', 'placeholder' => __('Header Authorization'), 'div' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="site_name"
                                   class="col-sm-3 control-label"><?php echo __('Sandbox Mode'); ?></label>
                            <div class="col-sm-9">
                                <?php echo $this->Form->input('sandbox_mode', array('checked' => $post[2]['PaymentSetting']['sandbox_mode'], 'label' => '&nbsp;' . __('True'), 'class' => '', 'div' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="site_name" class="col-sm-3 control-label"><?php echo __('Published'); ?></label>
                            <div class="col-sm-9">
                                <?php echo $this->Form->input('published', array('value' => $post[2]['PaymentSetting']['published'], 'type' => 'radio', 'options' => array("Yes" => __("Yes"), "No" => __("No")), 'legend' => false, 'before' => '<label class="radio-inline">', 'separator' => '</label><label class="radio-inline">', 'label' => false, 'div' => false, 'class' => '')); ?>
                            </div>
                        </div>
                        <?php echo $this->Form->input('type', array('type' => 'hidden', 'value' => 'PME')); ?>
                        <div class="form-group text-left">
                            <div class="col-sm-offset-3 col-sm-7">
                                <?php echo $this->Form->button('<span class="fa fa-refresh"></span>&nbsp;' . __('Save Settings'), array('class' => 'btn btn-success', 'escpae' => false)); ?>
                            </div>
                        </div>
                        <?php echo $this->Form->end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>