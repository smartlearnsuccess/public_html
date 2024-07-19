<?php
$this->Js->JqueryEngine->jQueryObject = 'jQuery';
// Paginator options
$this->Paginator->options(array(
    'update' => '#resultDiv',
    'evalScripts' => true,
));
?>
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
<div id="resultDiv">
    <div class="page-title">
        <div class="title-env"><h1 class="title"><?php echo __('Transaction Report'); ?></h1></div>
    </div>
    <div class="panel">
        <?php echo $this->Session->flash(); ?>
        <div class="panel-heading">
            <?php echo $this->Form->create(array('name' => 'searchfrm', 'id' => 'searchfrm', 'url' => array('action' => "index"))); ?>
            <div class="row mrg">
                <div class="col-sm-2">
                    <?php echo $this->Form->select('status', array('Approved' => __('Success'), 'Pending' => __('Pending'), 'Cancelled' => __('Failed')), array('empty' => false, 'multiple' => true, 'class' => 'form-control multiselectgrp', 'div' => false, 'label' => false)); ?>
                </div>
                <div class="col-sm-3">
                    <?php echo $this->Form->select('payment_mode', array('ADM' => __('ADMINISTRATOR'),'FRE' => __('FREE'), 'CAE' => __('CC AVENUE'), 'PME' => __('PAYU MONEY'), 'PPL' => __('PAYPAL')), array('empty' => false, 'multiple' => true, 'class' => 'form-control multiselectgrp', 'div' => false, 'label' => false)); ?>
                </div>
                <div class="col-sm-2">
                    <?php echo $this->Form->input('keyword', array('class' => 'form-control', 'div' => false, 'placeholder' => __('name,email,phone'), 'label' => false)); ?>
                </div>
                <div class="col-sm-2">
                    <?php echo $this->Form->input('token', array('class' => 'form-control', 'div' => false, 'placeholder' => __('Transaction ID'), 'label' => false)); ?>
                </div>
                <div class="col-sm-3">
                    <?php echo $this->Form->input('transaction_id', array('type' => 'text', 'class' => 'form-control', 'div' => false, 'placeholder' => __('Payment Gateway Transaction ID'), 'label' => false)); ?>
                </div>
            </div>
            <div class="row mrg">
                <div class="col-sm-2">
                    <?php echo $this->Form->input('cart_amount', array('class' => 'form-control', 'div' => false, 'placeholder' => __('Cart Amount'), 'label' => false)); ?>
                </div>
                <div class="col-sm-2">
                    <?php echo $this->Form->input('transaction_amount', array('type' => 'number', 'class' => 'form-control', 'div' => false, 'placeholder' => __('TRN Amount'), 'label' => false)); ?>
                </div>
                <div class="col-sm-2">
                    <div class="input-group date" id="start_date">
                        <?php echo $this->Form->input('start_date', array('type' => 'text', 'label' => false, 'placeholder' => __('Date From'), 'class' => 'form-control', 'div' => false)); ?>
                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="input-group date" id="end_date">
                        <?php echo $this->Form->input('end_date', array('type' => 'text', 'label' => false, 'placeholder' => __('Date To'), 'class' => 'form-control', 'div' => false)); ?>
                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <?php echo $this->Form->button('<span class="fa fa-search"></span>&nbsp;' . __('Search'), array("id" => "searchbtn", "class" => "btn btn-success")); ?>
                    <?php echo $this->Html->link('<span class="fa fa-refresh"></span>&nbsp;' . __('Reset'), array('action' => 'index'), array('class' => 'btn btn-warning', 'escape' => false)); ?>
                    <?php echo $this->Html->link('<span class="fa fa-file-excel-o"></span>&nbsp;' . __('Export In Excel '), array('action' => 'excel') + $this->params['named'], array('escape' => false, 'class' => 'btn btn-info')); ?>
                </div>
            </div>
            <?php echo $this->Form->end(); ?>

        </div>
        <?php echo $this->element('pagination', array('IsSearch' => 'No'));
        $page_params = $this->Paginator->params();
        $limit = $page_params['limit'];
        $page = $page_params['page'];
        $serial_no = 1 * $limit * ($page - 1) + 1; ?>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <tr>
                        <th><?php echo $this->Paginator->sort('id', __('Sr. No.'), array('direction' => 'desc')); ?></th>
                        <th><?php echo $this->Paginator->sort('TransactionReport.status', __('Transaction Status'), array('direction' => 'asc')); ?></th>
                        <th><?php echo $this->Paginator->sort('TransactionReport.name', __('Payment Mode'), array('direction' => 'asc')); ?></th>
                        <th><?php echo $this->Paginator->sort('Student.name', __('User Name'), array('direction' => 'asc')); ?></th>
                        <th><?php echo $this->Paginator->sort('Student.email', __('User Email'), array('direction' => 'asc')); ?></th>
                        <th><?php echo $this->Paginator->sort('Student.phone', __('User Phone No.'), array('direction' => 'asc')); ?></th>
                        <th><?php echo $this->Paginator->sort('TransactionReport.token', __('Transaction ID'), array('direction' => 'asc')); ?></th>
                        <th><?php echo __('Packages Purchased'); ?></th>
                        <th><?php echo $this->Paginator->sort('TransactionReport.transaction_id', __('Payment Gateway Transaction ID'), array('direction' => 'asc')); ?></th>
                        <th><?php echo $this->Paginator->sort('TransactionReport.cart_amount', __('Cart Amount'), array('direction' => 'asc')); ?></th>
                        <th><?php echo $this->Paginator->sort('TransactionReport.amount', __('Transaction Amount'), array('direction' => 'asc')); ?></th>
                        <th><?php echo $this->Paginator->sort('TransactionReport.date', __('Transaction Date and Time'), array('direction' => 'asc')); ?></th>
                    </tr>
                    <?php foreach ($TransactionReport as $post):
                        $id = $post['TransactionReport']['id'];
                        $cartAmount = $post['TransactionReport']['cart_amount'];
                        $transactionAmount = $post['TransactionReport']['amount'];
                        ?>
                        <tr>
                            <td><?php echo $serial_no++; ?></td>
                            <td><?php if ($post['TransactionReport']['status'] == "Approved") {
                                    echo '<p class="payment-remark-success">' . __('Success') . '</p>';
                                } elseif ($post['TransactionReport']['status'] == "Pending") {
                                    echo $this->Form->postLink(__('Pending'), array('action' => 'paymentStatus', $id), array('confirm' => __('Are you sure you want to success selected record'), 'class' => 'btn btn-warning', 'escape' => false));
                                } else {
                                    echo $this->Form->postLink(__('Failed'), array('action' => 'paymentStatus', $id), array('confirm' => __('Are you sure you want to success selected record'), 'class' => 'btn btn-danger', 'escape' => false));
                                } ?></td>
                            <td><?php echo h(strtoupper($post['TransactionReport']['name'])); ?></td>
                            <td><?php echo h($post['Student']['name']); ?></td>
                            <td><?php echo h($post['Student']['email']); ?></td>
                            <td><?php echo h($post['Student']['phone']); ?></td>
                            <td><?php echo $post['TransactionReport']['token']; ?></td>
                            <td><?php echo $this->Function->showPackageName($post['Package']); ?></td>
                            <td><?php echo $post['TransactionReport']['transaction_id']; ?></td>
                            <td><?php echo $currency . $cartAmount; ?></td>
                            <td><?php echo $currency . $transactionAmount; ?></td>
                            <td><?php echo $this->Time->format($dtmFormat, $post['TransactionReport']['date']);; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php unset($post); ?>
                </table>
            </div>
            <?php echo $this->element('pagination', array('IsSearch' => 'No')); ?>
        </div>
    </div>
</div>
<style type="text/css">
    .payment-remark-danger {
        background-color: #ea2e49;
        color: #ffffff;
        padding: 5px;
        min-width: 10px;
        border-radius: 4px;
        text-align: center;
    }

    .payment-remark-success {
        background-color: #07bf29;
        color: #ffffff;
        padding: 5px;
        border-radius: 4px;
        text-align: center;
    }

    .payment-remark-pending {
        background-color: #ffba00;
        color: #ffffff;
        padding: 5px;
        border-radius: 4px;
        text-align: center;
    }
</style>
