<?php
$this->Js->JqueryEngine->jQueryObject = 'jQuery';
// Paginator options
$this->Paginator->options(array(
	'update' => '#resultDiv',
	'evalScripts' => true,
));
?>
<div id="resultDiv">
	<?php echo $this->Session->flash(); ?>
	<div class="page-title-breadcrumb">
		<div class="page-header pull-left">
			<div class="page-title"><?php echo __('My Results'); ?></div>
		</div>
	</div>
	<?php $page_params = $this->Paginator->params();
	$limit = $page_params['limit'];
	$page = $page_params['page'];
	$serial_no = 1 * $limit * ($page - 1) + 1;
	?>
	<div class="row"><?php echo $this->element('pagination', array('IsSearch' => 'No', 'IsDropdown' => 'No')); ?></div>
	<?php foreach ($Result as $post): ?>
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="col-sm-2 pad"><?php echo __('Exam Name'); ?> : <span
						class="exam_value"><?php echo h($post['Exam']['name']); ?></span></div>
				<div class="col-sm-3 pad"><?php echo __('Attempt'); ?> : <span
						class="exam_value"><?php echo $this->Time->format($dtmFormat, $post['Result']['start_time']); ?></span>
				</div>
				<div class="col-sm-2 pad"><?php echo __('Marks Scored'); ?> : <span
						class="exam_value"><?php echo CakeNumber::precision($post['Result']['obtained_marks'], 2); ?>/<?php echo CakeNumber::precision($post['Result']['total_marks'], 2); ?></span>
				</div>
				<div class="col-sm-2 pad"><?php echo __('Percentage'); ?> : <span
						class="exam_value"><?php echo $this->Number->toPercentage($post['Result']['percent']); ?></span>
				</div>
				<div class="col-sm-1 pad"><span
						class="label label-<?php if ($post['Result']['result'] == "Pass") echo "success"; else echo "danger"; ?>"><?php if ($post['Result']['result'] == "Pass") {
							echo __('PASSED');
						} else {
							echo __('FAILED');
						} ?></span></div>
				<div class="col-sm-2 pad"><?php if ($post['Exam']['declare_result'] == 'Yes') {
						echo $this->Html->link('<span class="fa fa-arrows-alt"></span>&nbsp;', array('action' => 'view', $post['Result']['id']), array('class' => 'btn btn-default', 'escape' => false, 'data-toggle' => 'tooltip', 'title' => __('View Details'))); ?>
						<?php echo $this->Html->link('<span class="fa fa-print"></span>&nbsp;', array('action' => 'printresult', $post['Result']['id']), array('class' => 'btn btn-default', 'escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Print'), 'target' => '_blank'));
					} ?>
					<?php if ($siteCertificate == 1 && $post['Result']['result'] == "Pass") echo $this->Html->link('<span class="fa fa-certificate"></span>&nbsp;', array('action' => 'certificate', $post['Result']['id'], 'ext' => 'pdf'), array('data-toggle' => 'tooltip', 'title' => __('Certificate'), 'class' => 'btn btn-info', 'escape' => false)); ?>
				</div>
			</div>
		</div>
	<?php endforeach;
	unset($post); ?>
	<?php echo $this->element('pagination', array('IsSearch' => 'No', 'IsDropdown' => 'No')); ?>
</div>
