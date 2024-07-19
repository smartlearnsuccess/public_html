<?php
$this->Js->JqueryEngine->jQueryObject = 'jQuery';
// Paginator options
$this->Paginator->options(array(
    'update' => '#resultDiv',
    'evalScripts' => true,
));
?>
<div id="resultDiv">
    <div class="page-title">
        <div class="title-env"><h1 class="title"><?php echo __('Coupons'); ?></h1></div>
    </div>
    <div class="panel">
        <?php echo $this->Session->flash(); ?>
        <div class="panel-heading">
            <div class="btn-group">
                <?php $url = $this->Html->url(array('controller' => 'Coupons'));
                echo $this->Html->link('<span class="fa fa-plus-circle"></span>&nbsp;' . __('Add New Coupon'), array('controller' => 'Coupons', 'action' => 'add'), array('escape' => false, 'class' => 'btn btn-success')); ?>
                <?php echo $this->Html->link('<span class="fa fa-edit"></span>&nbsp;' . __('Edit'), 'javascript:void(0);', array('name' => 'editallfrm', 'id' => 'editallfrm', 'onclick' => "check_perform_edit('$url');", 'escape' => false, 'class' => 'btn btn-warning')); ?>
                <?php echo $this->Html->link('<span class="fa fa-trash"></span>&nbsp;' . __('Delete'), 'javascript:void(0);', array('name' => 'deleteallfrm', 'id' => 'deleteallfrm', 'onclick' => 'check_perform_delete();', 'escape' => false, 'class' => 'btn btn-danger')); ?>
                <?php echo $this->Html->link('<span class="fa fa-briefcase"></span>&nbsp;' . __('Used Coupons'), array('controller' => 'CouponsStudents', 'action' => 'index'), array('escape' => false, 'class' => 'btn btn-info')); ?>
            </div>
        </div>
        <?php echo $this->element('pagination');
        $page_params = $this->Paginator->params();
        $limit = $page_params['limit'];
        $page = $page_params['page'];
        $serialNo = 1 * $limit * ($page - 1) + 1; ?>
        <?php echo $this->Form->create(array('name' => 'deleteallfrm', 'url' => array('action' => 'deleteall'))); ?>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <tr>
                        <th><?php echo $this->Form->checkbox('checkbox', array('value' => 'deleteall', 'name' => 'selectAll', 'label' => false, 'id' => 'selectAll', 'hiddenField' => false)); ?></th>
                        <th><?php echo $this->Paginator->sort('id', __('#'), array('direction' => 'desc')); ?></th>
                        <th><?php echo $this->Paginator->sort('name', __('Coupon Name'), array('direction' => 'asc')); ?></th>
                        <th><?php echo $this->Paginator->sort('amount', __('Coupon Amount'), array('direction' => 'asc')); ?></th>
                        <th><?php echo $this->Paginator->sort('min_amount', __('Minimum Order'), array('direction' => 'asc')); ?></th>
                        <th><?php echo $this->Paginator->sort('code', __('Coupon Code'), array('direction' => 'asc')); ?></th>
                        <th><?php echo $this->Paginator->sort('coupon_no', __('No. of Coupon'), array('direction' => 'asc')); ?></th>
                        <th><?php echo $this->Paginator->sort('per_customer', __('Uses Per Customer'), array('direction' => 'asc')); ?></th>
                        <th><?php echo $this->Paginator->sort('end_date', __('Start & End Date'), array('direction' => 'asc')); ?></th>
                        <th><?php echo __('Status'); ?></th>
                        <th><?php echo __('Action'); ?></th>
                    </tr>
                    <?php foreach ($Coupon as $post):
                        $id = $post['Coupon']['id']; ?>
                        <tr>
                            <td><?php echo $this->Form->checkbox(false, array('value' => $post['Coupon']['id'], 'name' => 'data[Coupon][id][]', 'id' => "DeleteCheckbox$id", 'class' => 'chkselect')); ?></td>
                            <td><?php echo $serialNo++; ?></td>
                            <td><?php echo h($post['Coupon']['name']); ?></td>
                            <td><?php echo $post['Coupon']['amount'];
                                if ($post['Coupon']['discount_type'] == "Percent") {
                                    echo '%';
                                } ?></td>
                            <td><?php echo $post['Coupon']['min_amount']; ?></td>
                            <td><?php echo $post['Coupon']['code']; ?></td>
                            <td><?php if ($post['Coupon']['coupon_no']) {
                                    echo $post['Coupon']['coupon_no'];
                                } else {
                                    echo __('Unlimited');
                                } ?></td>
                            <td><?php if ($post['Coupon']['per_customer']) {
                                    echo $post['Coupon']['per_customer'];
                                } else {
                                    echo __('Unlimited');
                                } ?></td>
                            <td><?php echo $this->Time->format($dtFormat, $post['Coupon']['start_date']); ?>
                                <br> <?php echo __('to'); ?>
                                <br><?php echo $this->Time->format($dtFormat, $post['Coupon']['end_date']); ?></td>
                            <td><span
                                        class="label label-<?php if ($post['Coupon']['status'] == "Active") echo "success"; else echo "danger"; ?>"><?php echo $post['Coupon']['status']; ?>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                                            aria-expanded="false">
                                        <?php echo __('Action'); ?> <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><?php echo $this->Html->link('<span class="fa fa-server"></span>&nbsp;' . __('Used Coupons'), array('controller' => 'CouponsStudents', 'action' => 'index', 'name' => $post['Coupon']['code']), array('escape' => false)); ?></li>
                                        <li><?php echo $this->Html->link('<span class="fa fa-edit"></span>&nbsp;' . __('Edit'), 'javascript:void(0);', array('name' => 'editallfrm', 'onclick' => "check_perform_sedit('$url','$id');", 'escape' => false)); ?></li>
                                        <li><?php echo $this->Html->Link('<span class="fa fa-trash"></span>&nbsp;' . __('Delete'), 'javascript:void(0);', array('onclick' => "check_perform_sdelete('$id');", 'escape' => false)); ?></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php unset($post); ?>
                </table>
            </div>
            <?php echo $this->Form->end(); ?>
            <?php echo $this->element('pagination'); ?>
        </div>
    </div>

</div>