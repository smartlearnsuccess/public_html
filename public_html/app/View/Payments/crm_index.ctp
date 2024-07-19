<?php
$this->Js->JqueryEngine->jQueryObject = 'jQuery';
// Paginator options
$this->Paginator->options(array(
	'update' => '#resultDiv',
	'evalScripts' => true,
));
?>
<script type="text/javascript">
    $(window).on('load', function () {
        showPaymentStatus('Failed');
    });

    function showPaymentStatus(status) {
        Android.showStatus(status);
    }
</script>
<div id="resultDiv">
	<?php echo $this->Session->flash(); ?>
	<?php echo $this->element('pagination', array('IsSearch' => 'No', 'IsDropdown' => 'No'));
	$pageParams = $this->Paginator->params();
	$limit = $pageParams['limit'];
	$page = $pageParams['page'];
	$serialNo = 1 * $limit * ($page - 1) + 1; ?>

	<div class="page-title-breadcrumb">
		<div class="page-header pull-left">
			<div class="page-title"><?php echo __('Payments'); ?></div>
		</div>
	</div>
	<div class="panel">
		<div class="panel-body">
			<div class="table-responsive">
				<table class="table table-striped">
					<tr>
						<th><?php echo __('S.No.'); ?></th>
						<th><?php echo __('Transaction ID'); ?></th>
						<th><?php echo __('Payment Gateway Transaction ID'); ?></th>
						<th><?php echo __('Amount'); ?></th>
						<th><?php echo __('Coupon Discount'); ?></th>
						<th><?php echo __('Net Amount'); ?></th>
						<th><?php echo __('Date & Time'); ?></th>
						<th><?php echo __('Payment Mode'); ?></th>
						<th><?php echo __('Remarks'); ?></th>
						<th><?php echo __('Details'); ?></th>
					</tr>
					<?php foreach ($Payment as $k => $post):
						$id = $post['Payment']['id'];
						$viewUrl = $this->Html->url(array('action' => 'view', $id)); ?>
						<tr>
							<td><?php echo $serialNo++; ?></td>
							<td><?php echo h($post['Payment']['token']); ?></td>
							<td><?php echo h($post['Payment']['transaction_id']); ?></td>
							<td><?php echo $currency . $this->Number->format(($post['Payment']['amount'] + $post['Payment']['coupon_amount']), 2); ?></td>
							<td><?php if ($post['Payment']['coupon_amount'] != null) {
									echo $currency . $post['Payment']['coupon_amount'];
								} else {
									echo '-';
								} ?></td>
							<td><?php echo $currency . $post['Payment']['amount']; ?></td>
							<td><?php echo $this->Time->format($dtmFormat, $post['Payment']['date']); ?></td>
							<td><?php echo h($post['Payment']['name']); ?></td>
							<td><?php if ($post['Payment']['status'] == "Approved") {
									echo '<p class="payment-remark-success">' . __('Success') . '</p>';
								} elseif ($post['Payment']['status'] == "Pending") {
									echo '<p class="payment-remark-pending">' . __('Pending') . '</p>';
								} else {
									echo '<p class="payment-remark-danger">' . __('Failed') . '</p>';
								} ?></td>
							<td><?php if ($post['Payment']['status'] == "Approved") {
									echo $this->Html->link('<span class="fa fa-arrows-alt"></span>&nbsp;' . __('View Details'), 'javascript:void(0);', array('onclick' => "show_modal('$viewUrl');", 'escape' => false, 'class' => 'btn btn-block btn-info', 'style' => 'font-size:12px;'));
								} else {
									if ($k == 0 && $cartCount > 0) {
										echo $this->Html->link(__('Retry'), array('crm' => false, 'controller' => 'Checkouts', 'action' => 'paymentMethod'), array('class' => 'btn btn-block btn-info', 'style' => 'font-size:12px;'));
									}
								} ?>
								<?php if ($post['Payment']['status'] == "Approved") {
									echo '<br>' . $this->Html->link('<span class="fa fa-forward"></span>&nbsp;' . __('Take Exam'), array('controller' => 'Exams', 'action' => 'index'), array('escape' => false, 'class' => 'btn btn-block btn-success', 'style' => 'font-size:12px;'));
								} ?></td>
						</tr>
					<?php endforeach;
					unset($post); ?>
				</table>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="targetModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-content">
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
