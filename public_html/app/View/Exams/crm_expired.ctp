<?php echo $this->Session->flash();
$dateFormat=$sysDay.$dateSep.$sysMonth.$dateSep.$sysYear;?>
<?php echo $this->Html->css('accordion');
echo $this->Html->script('accordion'); ?>
<div class="page-title-breadcrumb">
    <div class="page-header pull-left">
	<div class="page-title"><?php echo __('My Exams');?></div>
    </div>
</div>
<div class="panel">
    <div class="panel-heading">
        <div class="btn-group">
			<?php echo$this->Html->link(__("Free Exam"),array('controller'=>'Exams','action'=>'free'),array('class'=>'btn btn-info btn-exam-name'));?>
			<?php echo$this->Html->link(__("Paid Exam"),array('controller'=>'Exams','action'=>'index'),array('class'=>'btn btn-info btn-exam-name'));?>
			<?php echo$this->Html->link(__('Upcoming Exam'),array('controller'=>'Exams','action'=>'upcoming'),array('class'=>'btn btn-info btn-exam-name'));?>
			<?php echo$this->Html->link(__('Expired Exam'),array('controller'=>'Exams','action'=>'expired'),array('class'=>'btn btn-success btn-exam-name'));?>
		</div>
    </div>
		<?php if($paidExamCount>$limit){
		$loopLimit=ceil($paidExamCount/$limit);
		for($i=0;$i<$loopLimit;$i++){
			$mainLimit=$i*$limit;
			$displayLimit=$i+1;
		echo $this->Html->link($displayLimit,array('action'=>'index',$mainLimit),array('class'=>'btn'));
		}
		}?>
	<?php if ($mainExam) { ?>
		<div><?php echo __('These are the exam(s) which has been expired'); ?></div>
		<div class="accordion-panel">
			<dl class="accordion">
				<?php $i = 0;
				foreach ($mainExam as $k => $item): $i++; ?>
					<dt><?php echo $this->Html->image($item['photo'], array('alt' => $item['name'], 'class' => 'img-circle', 'style' => "width:75px;height:75px;")); ?>
						&nbsp;<?php echo $item['name']; ?>
						<i class="plus-icon"></i></dt>
					<dd>
						<div class="content">
							<div class="panel-body">
								<?php echo $this->Function->showExamList("expired", $item['exam_detail'],$examResultArr, $currentDateTime, $currency, $dateFormat, $frontExamPaid, $examExpiry); ?>
							</div>
						</div>
					</dd>
				<?php endforeach;
				unset($k, $item); ?>
			</dl>
		</div>
	<?php } else { ?>
		<div style="background-color: #ffffff;color:#ff2f32;"><?php echo __('No Exams found'); ?></div>
	<?php } ?>
</div>
<div class="modal fade" id="targetModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
	 aria-hidden="true">
	<div class="modal-content">
	</div>
</div>
