<?php echo $this->Session->flash(); ?>
<div class="row">
    <div class="col-md-2">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h4><?php echo __('My Profile'); ?></h4>
            </div>
            <div class="list-group">
                <?php echo $this->Html->link(__("Profile"), array('controller' => 'Profiles', 'action' => 'index'), array('class' => 'list-group-item active')); ?>
                <?php echo $this->Html->link(__("Edit Profile"), array('controller' => 'Profiles', 'action' => 'editProfile'), array('class' => 'list-group-item')); ?>
                <?php echo $this->Html->link(__("Change Photo"), array('controller' => 'Profiles', 'action' => 'changePhoto'), array('class' => 'list-group-item')); ?>
                <?php echo $this->Html->link(__('Change Password'), array('controller' => 'Profiles', 'action' => 'changePass'), array('class' => 'list-group-item')); ?>
            </div>
        </div>
    </div>
    <div class="col-md-10">
        <div class="page-title-breadcrumb">
            <div class="page-header pull-left">
                <div class="page-title"><?php echo __('My Profile'); ?></div>
            </div>
        </div>
        <div class="panel">
            <div class="panel-body">
            <div class="row">
                <table class="table table-striped table-bordered">
                    <tr class="text-primary">
                        <td><strong>
                                <small><?php echo __('Full Name'); ?></small>
                            </strong></td>
                        <td><strong>
                                <small><?php echo __('Phone Number'); ?></small>
                            </strong></td>
                    </tr>
                    <tr>
                        <td><strong>
                                <small><?php echo h($post['Student']['name']); ?></small>
                            </strong></td>
                        <td><strong>
                                <small><?php echo h($post['Student']['phone']); ?></small>
                            </strong></td>
                    </tr>
                    <tr class="text-primary">
                        <td><strong>
                                <small><?php echo __('Registered Email'); ?></small>
                            </strong></td>
                        <td><strong>
                                <small><?php echo __('Alternate Number'); ?></small>
                            </strong></td>
                    </tr>
                    <tr>
                        <td><strong>
                                <small><?php echo h($post['Student']['email']); ?></small>
                            </strong></td>
                        <td><strong>
                                <small><?php echo h($post['Student']['guardian_phone']); ?></small>
                            </strong></td>
                    </tr>
                    </tr>
                    <tr class="text-primary">
                        <td><strong>
                                <small><?php echo __('Enrolment No'); ?><strong>
                                        <small></td>
                        <td><strong>
                                <small><?php echo __('Admission Date'); ?></small>
                            </strong></td>
                    </tr>
                    <tr>
                        <td><strong>
                                <small><?php echo h($post['Student']['enroll']); ?></small>
                            </strong></td>
                        <td><strong>
                                <small><?php echo $this->Time->format($sysDay . $dateSep . $sysMonth . $dateSep . $sysYear, $post['Student']['created']); ?></small>
                            </strong></td>
                    </tr>
                    <tr class="text-primary">
                        <td colspan="2"><strong>
                                <small><?php echo __('Address'); ?><strong>
                                        <small></td>
                    </tr>
                    <tr>
                        <td colspan="2"><strong>
                                <small><?php echo h($post['Student']['address']); ?></small>
                            </strong></td>
                    </tr>
                    <tr class="text-primary">
                        <td><strong>
                                <small><?php echo __('Groups'); ?> <strong>
                                        <small></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <small><strong>
                                    <?php foreach ($groupSelect as $groupValue):
                                        echo h($groupValue['Groups']['group_name']); ?> |
                                    <?php endforeach;
                                    unset($groupValue); ?>
                            </small>
                            </strong></td>
                    </tr>
                </table>
            </div>
       </div>
    </div>
</div>