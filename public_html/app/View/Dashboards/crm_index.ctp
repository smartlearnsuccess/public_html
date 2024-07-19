<?php echo $this->Session->flash();
$dateFormat = $sysDay . $dateSep . $sysMonth . $dateSep . $sysYear; ?>
<?php if (strlen($testId) > 0) {
	if ($isMobile) {
		echo $this->Html->link('<i class="fa fa-check-circle"></i>&nbsp; ' . __('Click here to complete'), array('controller' => 'Exams', 'action' => 'start', $testId),array('class' => 'btn btn-success', 'escape' => false));
	}else{
		echo $this->Form->button('<i class="fa fa-check-circle"></i>&nbsp; ' . __('Click here to complete'), array('onclick' => "javascript:showpop_up('" . $this->Html->url(array('controller' => 'Exams', 'action' => 'start', $testId)) . "')", 'class' => 'btn btn-success', 'escape' => false));
	}
    //echo $this->Html->link('<i class="fa fa-check-circle"></i>&nbsp; ' . __('Click here to complete %s', $remExamName), array('controller' => 'Exams', 'action' => 'start', $testId), array('class' => 'btn btn-success', 'escape' => false));?>
<p>&nbsp;</p><?php } ?>
<div class="row">
    <div class="col-md-4 col-sm-4">
        <div class="panel">
            <div class="panel-body">
                <h3><?php echo __('My Exam Stats'); ?></h3>
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <td><strong><?php echo __('Total Exam Given'); ?> : </strong><strong
                                    class="text-success"><?php echo $totalExamGiven; ?></strong></td>
                        </tr>
                        <tr>
                            <td><strong><?php echo __('Absent Exams'); ?> : </strong><strong
                                    class="text-danger"><?php echo $userTotalAbsent; ?></strong></td>
                        </tr>
                        <tr>
                            <td><strong><?php echo __('Best Score in'); ?> : </strong><strong
                                    class="text-success"><?php echo h($bestScore); ?></strong></td>
                        </tr>
                        <tr>
                            <td><strong><?php echo __('On'); ?> : </strong><strong
                                    class="text-info"><?php echo $bestScoreDate ?></strong></td>
                        </tr>
                        <tr>
                            <td><strong><?php echo __('Failed in'); ?> : </strong><strong
                                    class="text-danger"><?php echo $failedExam; ?><?php echo __('Exam'); ?></strong>
                            </td>
                        </tr>
                        <tr>
                            <td><strong><?php echo __('Average Percentage'); ?> : </strong><strong
                                    class="text-info"><?php echo CakeNumber::toPercentage($averagePercent); ?></strong>
                            </td>
                        </tr>
                        <tr>
                            <td><strong><?php echo __('Your Rank'); ?> : </strong><strong
                                    class="text-info"><?php echo $rank; ?></strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8 col-sm-8">
        <div class="panel">
            <div class="panel-body">
                <h3><?php echo __('Month Wise Performance'); ?></h3>
                <div class="chart">
                    <div id="mywrapperdl"></div>
                    <?php echo $this->HighCharts->render("My Chartdl"); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-body">
                <h3><?php echo __('Exam Wise Performance'); ?> (<strong><span
                            class="text-info"><?php echo __('Top 10'); ?></span></strong>)</h3>
                <div class="chart">
                    <div id="mywrapperd2"></div>
                    <?php echo $this->HighCharts->render("My Chartd2"); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="targetModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-content">
    </div>
</div>