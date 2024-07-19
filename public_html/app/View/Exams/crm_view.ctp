<div class="container">
    <div class="panel panel-custom mrg">
        <div class="panel-heading"><?php echo __($post['Exam']['type']); ?> <?php echo __('Details'); ?>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-6 pad"><strong class="text-primary"><?php echo __('Name'); ?></strong>
                    <strong class="text-success"><?php echo h($post['Exam']['name']); ?></strong>
                </div>
                <div class="col-sm-6 pad"><strong class="text-primary"><?php echo __('Type'); ?></strong>
                    <strong class="text-success"><?php echo __($post['Exam']['type']); ?></strong>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 pad"><strong class="text-primary"><?php echo __('Passing Percentage'); ?></strong>
                    <strong class="text-success"><?php echo $post['Exam']['passing_percent']; ?>%</strong>
                </div>
                <div class="col-sm-6 pad"><strong class="text-primary"><?php if ($post['Exam']['exam_mode'] == "D") {
					echo __('Duration');
				} ?></strong>
                    <strong
                        class="text-success"><?php echo __($this->Function->secondsToWords($post['Exam']['duration'] * 60)); ?></strong>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 pad"><strong class="text-primary"><?php echo __('Start Date'); ?></strong>
                    <strong
                        class="text-success"><?php echo $this->Time->format(str_replace(":s", "", $dtmFormat), $post['Exam']['start_date']); ?></strong>
                </div>
                <div class="col-sm-6 pad"><strong class="text-primary"><?php echo __('End Date'); ?></strong>
                    <strong
                        class="text-success"><?php echo $this->Time->format(str_replace(":s", "", $dtmFormat), $post['Exam']['end_date']); ?></strong>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 pad"><strong class="text-primary"><?php echo __('Negative Marking'); ?></strong>
                    <strong class="text-success"><?php echo __($post['Exam']['negative_marking']); ?></strong>
                </div>
                <div class="col-sm-6 pad"><strong class="text-primary"><?php if ($totalMarks > 0)
					echo __("Total Marks"); ?></strong>
                    <strong class="text-success"><?php if ($totalMarks > 0)
						echo $totalMarks; ?></strong>
                </div>
                <table class="table">
                    <tr>
                        <th><strong class="text-primary"><?php echo __('Subject'); ?></strong></th>
                        <th><strong class="text-primary"><?php echo __('Total Question'); ?></strong></th>
                        <?php if ($examCount) { ?>
                        <th><strong class="text-primary"><?php echo __('Questions Attempt Count'); ?></strong>
                        </th><?php } ?>
                        <?php if ($post['Exam']['exam_mode'] == "T") { ?>
                        <th><strong class="text-primary"><?php echo __('Duration'); ?></strong></th><?php } ?>
                    </tr>
                    <?php $totalQuestion = 0;
					$totalAttemptQuestion = 0;
					$totalDuration = 0;
					foreach ($subjectDetail as $k => $sd):
						$totalQuestion = $totalQuestion + $sd['total_question'];
						$totalAttemptQuestion = $totalAttemptQuestion + $sd['total_attempt_question'];
						$totalDuration = $totalDuration + $sd['duration'];
						?>
                    <tr>
                        <td><strong class="text-success"><?php echo h($k); ?></strong></td>
                        <td><strong class="text-success"><?php echo $sd['total_question']; ?></strong></td>
                        <?php if ($examCount) { ?>
                        <td><strong class="text-success"><?php echo $sd['total_attempt_question'] ?></strong>
                        </td><?php } ?>
                        <?php if ($post['Exam']['exam_mode'] == "T") { ?>
                        <td><strong class="text-success"><?php echo $sd['duration']; ?>
                                &nbsp;<?php echo __('Mins'); ?></strong>
                        </td><?php } ?>
                    </tr>
                    <?php endforeach; ?> <?php unset($sd); ?>
                    <tr>
                        <td><strong class="text-danger"><?php echo __('Total'); ?></strong></td>
                        <td><strong class="text-danger"><?php echo $totalQuestion; ?></strong></td>
                        <?php if ($examCount) { ?>
                        <td><strong class="text-danger"><?php echo $totalAttemptQuestion; ?></strong></td><?php } ?>
                        <?php if ($post['Exam']['exam_mode'] == "T") { ?>
                        <td><strong class="text-danger"><?php echo $totalDuration; ?>
                                &nbsp;<?php echo __('Mins'); ?></strong>
                        </td><?php } ?>
                    </tr>
                </table>
                <?php if (strlen($post['Exam']['syllabus']) > 0) { ?>
                <div style="padding: 10px;"><strong><?php echo __('Syllabus'); ?></strong></div>
                <div style="height: 190px;overflow: auto; overflow-y: scroll;padding: 10px;">
                    <?php echo $post['Exam']['syllabus']; ?>
                </div>
                <?php } ?>
                <?php if ($showType == 'free' || $showType == 'today') { ?>
                <div>
                    <?php //echo $this->Form->button('<span class="fa fa-forward"></span> ' . __('Attempt Now'), array('onclick' => "javascript:showpop_up('" . $this->Html->url(array('controller' => 'Exams', 'action' => 'guidelines', $id)) . "')", 'data-toggle' => 'tooltip', 'title' => __('Attempt Now'), 'escape' => false, 'class' => 'btn btn-success')); ?>

                    <?php echo $this->Form->button('<span class="fa fa-forward"></span> ' . __('Attempt Now'), array('onclick' => "javascript:showpop_up('" . $this->Html->url(array('controller' => 'Exams', 'action' => 'instruction', $id)) . "')", 'data-toggle' => 'tooltip', 'title' => __('Attempt Now'), 'escape' => false, 'class' => 'btn btn-success')); ?>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>