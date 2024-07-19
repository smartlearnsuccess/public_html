<?php echo $this->Session->flash(); ?>
<div class="page-title-breadcrumb">
	<div class="page-header pull-left">
		<div class="page-title"><?php echo __('Leader Board'); ?></div>
	</div>
</div>
<div class="panel">
	<div class="panel-body">
		<div class="">
			<table class="table table-striped">
				<tr>
					<th><?php echo __('Photo'); ?></th>
					<th><?php echo __('Rank'); ?></th>
					<th><?php echo __('Name'); ?></th>
					<th><?php echo __('Average (%)'); ?></th>
					<th><?php echo __('Exam Given'); ?></th>
				</tr>
				<?php foreach ($leaderboard as $post):
					?>
					<tr>
						<td><?php echo $this->Html->image($post['student_photo'], array('title' => $post['student_name'], 'alt' => $post['student_name'], 'class' => 'img-responsive img-circle img-thumbnail', 'style' => 'height:50px;')); ?>
						</td>
						<td><?php echo $post['rank']; ?></td>
						<td><?php echo h($post['student_name']); ?></td>
						<td><?php echo $post['result_percent']; ?>%</td>
						<td><?php echo $post['exam_given']; ?></td>
					</tr>
				<?php endforeach;
				unset($post); ?>
			</table>
		</div>
	</div>
</div>