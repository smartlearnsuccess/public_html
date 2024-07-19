<?php if ($type == 'crm') {
	echo $this->html->link('<span class="fa fa-arrow-left"></span>&nbsp;' . __('Back'), array('action' => 'index'), array('class' => 'btn btn-default', 'escape' => false));
}
$bookmarkUrl = $this->Html->Url(array('controller' => 'Results', 'action' => 'bookmark', $id,$studentId)); ?>
<script type="text/javascript">
    function navigation(quesNo) {
        $('.exam-panel').hide();
        $('#quespanel' + quesNo).show();
    }

    function callPrev(quesNo) {
        if (quesNo != 0) quesNo--;
        $('.exam-panel').hide();
        $('#quespanel' + quesNo).show();
    }

    function callNext(quesNo) {
        if ($('#totalQuestion').text() != quesNo) quesNo++;
        $('.exam-panel').hide();
        $('#quespanel' + quesNo).show();
    }

    function callComparePrev(rank) {
        rank--;
        $('.compare').hide();
        $('#comppanel' + rank).show(20, 'linear');
    }

    function callCompareNext(rank) {
        rank++;
        $('.compare').hide();
        $('#comppanel' + rank).show(20, 'linear');
    }

    function callBoomark(quesNo) {
        $.ajax({
            method: "POST", url: '<?php echo $bookmarkUrl;?>', data: '&id=' + quesNo, beforeSend: function () {
                $('#exam-loading').show();
            }
        }).done(function (data) {
            if (data == 'Y') {
                $('#navbtn' + quesNo).removeClass('btn-success');
                $('#bookmark' + quesNo).removeClass('btn-success');
                $('#bookmark' + quesNo).addClass('btn-danger');
                $('#bookmark' + quesNo).html('<span class="fa fa-star-o"></span> Unbookmark');
            } else {
                $('#navbtn' + quesNo).removeClass('btn-success');
                $('#bookmark' + quesNo).removeClass('btn-danger');
                $('#bookmark' + quesNo).addClass('btn-success');
                $('#bookmark' + quesNo).html('<span class="fa fa-star"></span> Bookmark');
            }
            $('#exam-loading').hide();
        });
    }

</script>
<script type="text/javascript">
    window.onbeforeunload = function () {
        opener.location.reload();
    };
    $(document).ready(function () {
        $(this).bind("contextmenu", function (e) {
            e.preventDefault();
        });
    });
</script>
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

	.show-grid {
		padding-top: 10px;
		padding-bottom: 10px;
		background-color: rgba(235, 240, 243, .15);
		border: 1px solid #00bcd4;
	}

	.rtest_heading {
		margin: 10px;
	}

	.exam-panel {
		overflow-y: auto;
	}

	.exam-panel-inner {
		overflow-y: scroll;
		height: 350px;
	}

	.fixed-row-height {
		min-height: 69px;
	}
</style>
<script type="text/javascript">
    function changeLang(id, langLength) {
        $('.lang-' + id).show();
        for (i = 1; i <= langLength; i++) {
            if (id != i) {
                $('.lang-' + i).hide();
            }
        }
        $('.examLang').val(id);
    }
	<?php if($examDetails['Exam']['multi_language'] != 1){?>
    $(document).ready(function () {

        for (i = 1; i <= <?php echo $fullLanguageCount;?>; i++) {
            if (i != 1) {
                $('.lang-' + i).hide();
            }
        }
    });
	<?php }?>
</script>
<?php $i = 0;


$first_ques = '0';
$quesNo_b = array();
$finalPost = array();
foreach ($post as $k => $ques) {
	if ($ques['ExamStat']['bookmark'] == "Y") {
		$quesNo_b[$i] = $ques['ExamStat']['ques_no'];
		if ($first_ques == '') {
			$first_ques = $ques['ExamStat']['ques_no'];
		}
		$i++;
		$finalPost[] = $ques;
	}
}
$examDetails['Result']['total_question'] = $i;
?>
<script type="text/javascript">
    $(document).ready(function () {
        $('.exam-panel').hide();
        $('#quespanel<?php echo $first_ques;?>').show();
        $('.compare').hide();
        $('#comppanel0').show();
    });
</script>
<div style="display: none;"><label id="totalQuestion"><?php echo count($finalPost); ?></label></div>
<br/><br/>
<div class="">
	<div class="col-md-12 my-result" style="padding: 10px;">
		<!-- Tab panes -->
		<div class="">
			<?php if ($examDetails['Exam']['declare_result'] == "Yes"){ ?>
			<div class="tab-pane active" id="solution">
				<div class="rtest_heading">
					<strong><?php echo __('Bookmark For'); ?>  </strong><?php echo h($examDetails['Exam']['name']); ?>
					<span class="exam-ViewIn" style="margin-left: 50px;"><?php echo __('View In'); ?>
                        :<?php echo $this->Form->select('lang', $langArr, array('empty' => false, 'class' => 'examLang', 'onchange' => "changeLang(this.value,$languageCount)")); ?></span>
				</div>
				<div class="col-sm-12">
					<?php foreach ($finalPost

					as $quesSerial => $ques):
					$quesNo = $ques['ExamStat']['ques_no']; ?>

					<?php
					if ($ques['ExamStat']['bookmark'] == "Y"){
					?>
					<div class="exam-panel" id="quespanel<?php echo $quesSerial; ?>">

						<div class="exam-panel-inner">
							<div><strong>Subject Name
									: </strong><span><b> <?php echo $ques['Question']['Subject']['subject_name']; ?></b></span>
							</div>
							<?php if ($ques['Question']['passage_id']) {
								$questionStyle = 'style="width: 48%;"'; ?>
								<div id="exam-Passage" class="exam-divPassage"
									 style="display: block;overflow-y: scroll;height: 65px;">
								<?php $passageArr = explode("%^&", $ques['Question']['Passage']['passage']);
								foreach ($passageArr as $langIndex => $item) {
									$langIndex++; ?>
									<div class="lang-<?php echo $langIndex; ?>"><?php echo $item; ?></div>
								<?php } ?>
								</div><?php } ?>
							<div class="">
								<table class="table table-bordered table-condensed">
									<?php
									if ($ques['Question']['Qtype']['type'] == "M") {
										$options = array();
										$optionKeyArr = explode(",", $ques['ExamStat']['options']);
										$index = 0;
										foreach ($optionKeyArr as $value) {
											$optKey = "option" . $value;
											if (strlen($ques['Question'][$optKey]) > 0) {
												$index++;
												$options[$value] = str_replace("<script", "", $ques['Question'][$optKey]);
											}
										}
										unset($value, $key);
										$correctAnswer = array();
										$userAnswer = array();
										if (strlen($ques['Question']['answer']) > 1) {
											$correctAnswerExp = explode(",", $ques['Question']['answer']);
											foreach ($correctAnswerExp as $option):
												$correctAnswer[] = "Option" . (array_search($option, array_keys($options)) + 1);
											endforeach;
											unset($option);
											$correctAnswer = implode(",", $correctAnswer);

											$userAnswerExp = explode(",", $ques['ExamStat']['option_selected']);
											foreach ($userAnswerExp as $option):
												$userAnswer[] = "Option" . (array_search($option, array_keys($options)) + 1);
											endforeach;
											unset($option);
											$userAnswer = implode(",", $userAnswer);
										} else {
											if ($ques['ExamStat']['option_selected']) {
												$userAnswer = "Option" . (array_search($ques['ExamStat']['option_selected'], array_keys($options)) + 1);
											}
											$correctAnswer = "Option" . (array_search($ques['Question']['answer'], array_keys($options)) + 1);
										}
									}
									if ($ques['Question']['Qtype']['type'] == "T") {
										$userAnswer = $ques['ExamStat']['true_false'];
										$correctAnswer = $ques['Question']['true_false'];
									}
									if ($ques['Question']['Qtype']['type'] == "F") {
										$userAnswer = $ques['ExamStat']['fill_blank'];
										$correctAnswer = $ques['Question']['fill_blank'];
									}
									if ($ques['Question']['Qtype']['type'] == "S") {
										$userAnswer = $ques['ExamStat']['answer'];
										$correctAnswer = "";
									}
									if ($ques['ExamStat']['ques_status'] == "R")
										$quesStatus = "text-success";
									elseif ($ques['ExamStat']['ques_status'] == "W")
										$quesStatus = "text-danger";
									else
										$quesStatus = "text-info";

									?>
									<tr class="<?php echo $quesStatus; ?>">
										<td colspan="3">
											<div class="lang-1">
												<?php echo '<strong>' . __('Question') . ': ' . $quesNo . '</strong>&nbsp;&nbsp;' . str_replace("<script", "", $ques['Question']['question']); ?>
											</div>
											<?php if (is_array($ques['Question']['QuestionsLang'])){
											foreach ($ques['Question']['QuestionsLang'] as $k => $item){
											$k += 2; ?>
											<div
												class="lang-<?php echo $k; ?>"><?php echo str_replace("<script", "", $item['question']); ?></div>
							</div>
						<?php }
						unset($k, $item);
						}
						?>
							</td></tr>
							<?php if (strlen($ques['Question']['hint']) > 0) { ?>
								<tr>
									<td colspan="3">
										<div class="mrg-left"><strong><?php echo __('Hint'); ?>: </strong>
											<span class="lang-1">
										<?php echo str_replace("<script", "", $ques['Question']['hint']); ?>
									</span>
											<?php if (is_array($ques['Question']['QuestionsLang'])) {
												foreach ($ques['Question']['QuestionsLang'] as $k => $item) {
													$k += 2; ?>
													<span class="lang-<?php echo $k; ?>">
										<?php echo str_replace("<script", "", $item['hint']); ?>
											</span>
												<?php }
												unset($k, $item);
											}
											?>
										</div>
									</td>
								</tr>
							<?php } ?>
							<tr>
								<td colspan="3">
									<?php
									if ($ques['Question']['Qtype']['type'] == "M") {
										$correctImg = "";
										$incorrectImg = "";
										$optionSerial = 0;
										foreach ($options as $opt => $option):$optionSerial++;
											if (strlen($ques['Question']['answer']) > 1) {
												$correctImg = "";
												$incorrectImg = "";
												foreach (explode(",", $ques['ExamStat']['option_selected']) as $value) {
													if ($opt == $value && $ques['ExamStat']['ques_status'] == 'W') {
														$incorrectImg = $this->Html->image('incorrect_icon.png');
														break;
													}
												}
												unset($value);
												foreach (explode(",", $ques['ExamStat']['correct_answer']) as $value) {
													if ($opt == $value) {
														$incorrectImg = $this->Html->image('correct_icon.png');
														break;
													}
												}
												unset($value);
											} else {
												if ($opt == $ques['ExamStat']['correct_answer']) {
													$correctImg = $this->Html->image('correct_icon.png');
												} else {
													$correctImg = "";
												}
												if ($opt == $ques['ExamStat']['option_selected'] && $ques['ExamStat']['ques_status'] == 'W') {
													$incorrectImg = $this->Html->image('incorrect_icon.png');
												} else {
													$incorrectImg = "";
												}
											}
											echo '<p>' . $optionSerial . '. ' . $incorrectImg . $correctImg . ' ';
											echo '<span class="lang-1">' . $option . '</span>';

											if (is_array($ques['Question']['QuestionsLang'])) {
												foreach ($ques['Question']['QuestionsLang'] as $k => $item) {
													$k += 2;
													?>
													<span class="lang-<?php echo $k; ?>">
										<?php echo str_replace("<script", "", $item["option{$opt}"]); ?>
											</span>
												<?php }
												unset($k, $item);
											}
											echo '</p>';
										endforeach;
										unset($option);
									}
									if ($ques['Question']['Qtype']['type'] == "T") {
										$correctImgTrue = "";
										$correctImgFalse = "";
										$incorrectImgTrue = "";
										$incorrectImgFalse = "";
										if ($ques['Question']['true_false'] == "True") {
											$correctImgTrue = $this->Html->image('correct_icon.png');
										} else {
											$correctImgFalse = $this->Html->image('correct_icon.png');
										}
										if ($ques['ExamStat']['ques_status'] == 'W' && $ques['ExamStat']['true_false'] == "True") {
											$incorrectImgTrue = $this->Html->image('incorrect_icon.png');
										}
										if ($ques['ExamStat']['ques_status'] == 'W' && $ques['ExamStat']['true_false'] == "False") {
											$incorrectImgFalse = $this->Html->image('incorrect_icon.png');
										}
										echo $correctImgTrue . $incorrectImgTrue . __('True') . ' / ' . $correctImgFalse . $incorrectImgFalse . __('False');
									}
									?>
								</td>
							</tr>
							<tr>
								<td><?php if ($ques['ExamStat']['ques_status'] == NULL && !$ques['ExamStat']['answer']) echo '<strong class="text-info">' . __('Not Attempt') . '</strong>'; else echo '<strong class="text-warning">' . __('Attempt') . '</strong>'; ?></strong></td>
								<?php if ($ques['ExamStat']['ques_status'] == 'R') { ?>
									<td><strong class="text-success"><?php echo __('Correct'); ?></strong>
									</td><?php } ?>
								<?php if ($ques['ExamStat']['ques_status'] == 'W'){ ?>
								<td><strong class="text-danger"><?php echo __('Incorrect'); ?></strong></td>

							</tr>
						<tr>
						<td colspan="3"><strong><?php echo __('Your Answers'); ?> :</strong>&nbsp;<strong
							class="text-danger"><?php echo __($userAnswer); ?></strong>
						<?php if ($ques['Question']['Qtype']['type'] != "S") { ?><br>
							<strong><?php echo __('Correct Answer'); ?> :</strong>&nbsp;<strong
								class="text-success"><?php echo __($correctAnswer); ?></strong><?php } ?>
						</td><?php }else{ ?>
						<?php if ($ques['Question']['Qtype']['type'] != "S"){ ?></tr>
							<tr>
								<td colspan="3"><strong><?php echo __('Correct Answer'); ?> :</strong>&nbsp;<strong
										class="text-success"><?php echo __($correctAnswer); ?></strong></td><?php }
								} ?>
							</tr>
							<tr>
								<td colspan="3"><strong><?php echo __('Max Marks'); ?>
										:</strong>&nbsp;&nbsp;<?php echo $ques['ExamStat']['marks']; ?></td>
							</tr>
							<tr>
								<td colspan="3"><strong><?php echo __('Marks Scored'); ?>
										:</strong>&nbsp;&nbsp;<?php echo $ques['ExamStat']['marks_obtained']; ?></td>
							</tr>
							<tr>
								<td colspan="3"><strong><?php echo __('Time Taken'); ?>
										:</strong>&nbsp;&nbsp;<?php echo $this->Function->secondsToWords($ques['ExamStat']['time_taken'], '-'); ?>
								</td>
							</tr>
							<?php if ($ques['Question']['explanation']) { ?>
								<tr>
									<td colspan="3"><strong><?php echo __('Solution'); ?> :</strong>&nbsp;&nbsp;
										<span class="lang-1">
									<?php echo str_replace("<script", "", $ques['Question']['explanation']); ?>
								</span>
										<?php if (is_array($ques['Question']['QuestionsLang'])) {
											foreach ($ques['Question']['QuestionsLang'] as $k => $item) {
												$k += 2; ?>
												<span
													class="lang-<?php echo $k; ?>"><?php echo str_replace("<script", "", $item['explanation']); ?></span>
											<?php }
											unset($k, $item);
										}
										?>
									</td>
								</tr>
							<?php } ?>
							</table>
						</div>
					</div>
					<div class="col-sm-2 col-xs-6">
						<?php if ($quesNo_b[0] != $quesNo) {
							echo $this->Form->button('&larr;' . __('Prev'), array('type' => 'button', 'onclick' => "callPrev($quesSerial);", 'class' => 'btn btn-default btn-sm btn-block', 'escape' => false));
						} ?>
					</div>
					<div class="col-sm-2 col-xs-6">
						<?php if ($quesNo_b[$i - 1] != $quesNo) {
							echo $this->Form->button(__('Next') . '&rarr;', array('type' => 'button', 'onclick' => "callNext($quesSerial);", 'class' => 'btn btn-default btn-sm btn-block', 'escape' => false));
						} ?>
					</div>
					<div class="col-sm-3 col-xs-12">
						<?php if ($ques['ExamStat']['bookmark'] == "Y") {
							$btnBookmark = '<span class="fa fa-star-o"></span>' . __('Unbookmark');
							$btnColor = 'btn-danger';
						} else {
							$btnBookmark = '<span class="fa fa-star"></span> ' . __('Bookmark');
							$btnColor = 'btn-success';
						}
						echo $this->Form->button($btnBookmark, array('type' => 'button', 'onclick' => "callBoomark($quesNo);", 'id' => "bookmark$quesNo", 'class' => "btn $btnColor btn-sm btn-block", 'escape' => false)); ?>
					</div>
				</div>
				<?php
				} endforeach;
				unset($ques); ?>
			</div>
		</div>
		<?php } ?>
		<script type="text/javascript">
            $(document).ready(function () {
				<?php foreach ($langArr as $k=> $item) {?>
                $('.lang-<?php echo $k;?>').hide();
				<?php } unset($item);?>
                $('.lang-1').show();
            });
		</script>
