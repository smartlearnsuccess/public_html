<?php
$startSectionNo=1;
$examDuration = $post['Exam']['duration'];
$viewUrl = $this->Html->url(array('controller' => 'Exams', 'action' => 'submit', $examId, $examResultId));
$targetUrl = $this->Html->url(array('controller' => 'Ajaxcontents', 'action' => 'examwarning', $examResultId));
$finishUrl = $this->Html->url(array('controller' => 'Exams', 'action' => 'finish', $examId, 'warn'));
?>
<script type="text/javascript">
    $(document).ready(function () {
        $('.exam-panel').hide();
		<?php if($post['Exam']['multi_language'] != 1){?>
        for (i = 1; i <= <?php echo $fullLanguageCount;?>; i++) {
            if (i != 1) {
                $('.lang-' + i).hide();
            }
        }
		<?php }?>
        $('form input').on('keypress', function (e) {
            return e.which !== 13;
        });
    });
    var i = 0;

    function makeProgress() {
        if (i < 100) {
            setTimeout("makeProgress()", 100);
            i = i + 3;
            if (i > 100) {
                i = 100;
            }
            $(".progress-bar").css("width", i + "%").text();
            $(".progress_percentage").css("width", i + "%").text(i + " %");
            $("body").css("background-color", "#ffffff");
        }
    }

    function showExamWindow() {
        timerUpdate();
        $('#exam-loading-start').hide();
        $('#exam_top_header').fadeIn();
        $('#printajax').fadeIn();
    }
    makeProgress();
</script>
<?php if($post['Exam']['pause_exam']=="1"){?>
	<script type="text/javascript">
        $(window).on('unload', function() {
            var URL = "<?php echo $this->Html->url(array('controller'=>'Exams','action'=>'pause',$post['Exam']['id']));?>";
            var data = "";
            navigator.sendBeacon(URL, data);
        });
        function submitPause() {
            window.location='<?php echo $this->Html->url(array('controller'=>'Exams','action'=>'pause',$post['Exam']['id']));?>';
        }
	</script>
	<style type="text/css">
		.pause_screen {
			display: block;
		}
	</style>
<?php }?>
<div id="loadCalc" style="display:none;position:fixed;z-index:999;padding-top:1%;"></div>
<div style="display: none;"><label id="totalQuestion"><?php echo $totalQuestion; ?></label></div>
<div style="display: none;"><label id="totalSectionQuestion"><?php echo $totalSectionQuestion; ?></label></div>
<div style="display: none;"><label id="startQuestionNo"><?php echo $startQuestionNo; ?></label></div>
<div style="display: none;"><label
		id="saveUrl"><?php echo $this->Html->url(array('controller' => 'Exams', 'action' => 'save', $examId)); ?></label>
</div>
<div style="display: none;"><label
		id="resetUrl"><?php echo $this->Html->url(array('controller' => 'Exams', 'action' => 'resetAnswer', $examId)); ?></label>
</div>
<div style="display: none;"><label
		id="reviewAnswerUrl"><?php echo $this->Html->url(array('controller' => 'Exams', 'action' => 'reviewAnswer', $examId)); ?></label>
</div>
<div style="display: none;"><label
		id="attemptTimeUrl"><?php echo $this->Html->url(array('controller' => 'Exams', 'action' => 'attemptTime', $examId)); ?></label>
</div>
<div style="display: none;"><label
		id="examUrl"><?php echo $this->Html->url(array('controller' => 'Exams', 'action' => 'start', $examId)); ?></label>
</div>

<div class="center_screen" id="exam-loading-start"
	 style="display: block">
	<div style="font-size: 22px;font-weight: bolder;text-align: center; color: #a1a09e;"><?php echo __('Loading'); ?>...<span
			class="progress_percentage">1%</span></div>
	<div class="progress">
		<div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="1"
			 aria-valuemin="0" aria-valuemax="100" style="width: 2%">
			<span class="sr-only">2% Complete</span>
		</div>
	</div>
</div>
<div id="printajax" style="display: none;">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="col-md-9 col-sm-12 col-xs-12">
			<?php echo $this->Session->flash(); ?>
			<div class="menu_header">
				<button type="button" class="exam_new_bars hidden-lg hidden-md collapsed" data-toggle="collapse"
						data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="fa fa-bars fa fa-bars fa-3x"></span>
				</button>
				<div class="exam_new_heading exam-heading"><?php echo h($post['Exam']['name']); ?></div>
			</div>
			<?php if ($post['Exam']['calculator'] == "1") { ?>
				<div style="float:right">
					<div title="<?php echo __('Calculator'); ?>" onclick="loadCalculator()"
						 style="margin-top:10px;cursor: pointer;">
						<i class="fa fa-calculator fa-2x"></i>
					</div>
				</div>
			<?php } ?>
			<?php if ($post['Exam']['exam_mode'] == "T") { ?>
				<div class="exam-sections">
					<?php $temp = 0;

					$subjectHighlight = null;
					foreach ($userSectionSubject as $subjectName => $quesArr):$temp++;
						$subjectNameId = str_replace(" ", "", h($subjectName));
						if ($quesArr[0]['ExamStat']['is_section'] == "1") {
							$subjectHighlight = "exam-subjectHighlight";
							$startSectionNo=$temp;
						} else {
							$subjectHighlight = null;
						} ?>
						<div class="exam-dpopup"><a id="btnSection<?php echo $quesArr[0]['ExamStat']['tsubject_id']; ?>"
													class="exam-SubjectButton <?php echo $subjectHighlight; ?>"
													<?php if ($quesArr[0]['ExamStat']['is_section'] == "1"){ ?>onclick="SectionButtonClick(<?php echo $quesArr[0]['ExamStat']['tsubject_id']; ?>)"<?php } ?>><?php echo $subjectName; ?></a>
						</div>
					<?php endforeach;
					unset($i);
					unset($value, $temp); ?>
				</div>
			<?php } else {?>
				<div class="exam-sections">
					<?php $temp = 0;
					$subjectHighlight = null;
					foreach ($userSectionQuestion as $subjectName => $quesArr):$temp++;
						$subjectNameId = str_replace(" ", "", h($subjectName));
						if ($temp == 1) {
							$subjectHighlight = "exam-subjectHighlight";
						} else {
							$subjectHighlight = null;
						} ?>
						<div class="exam-dpopup"><a id="btnSection<?php echo $temp; ?>"
													class="exam-SubjectButton <?php echo $subjectHighlight; ?>"
													onclick="SectionButtonClick(<?php echo $temp; ?>)"><?php echo $subjectName; ?></a>
						</div>
					<?php endforeach;
					unset($i);
					unset($value, $temp); ?>
				</div>
			<?php } ?>
			<?php $mainSubjectName = null;
			$subTempId = 0;
			$subjectSection = null;
			global $currQuesNo;
			foreach ($userExamQuestionArr as $k => $userExamQuestion):
			if ($post['Exam']['exam_mode'] == "T"){
				$quesNo = $userExamQuestion['ExamStat']['ques_no'];
				$currQuesNo = $quesNo;
				$tempSubjectName = $userExamQuestion['Question']['Subject']['subject_name'];
				if ($tempSubjectName != $mainSubjectName) {
					$mainSubjectName = $tempSubjectName;
					$subTempId = $userExamQuestion['Question']['Subject']['id'];
					$subjectSection = "subject$subTempId";
				} else {
					$subjectSection = "sub$subTempId";
				} ?>
				<?php if ($k == 0){ ?>
				<script type="text/javascript">
                    $(document).ready(function () {
						<?php if($quesNo == 1){?>
                        firstNavigation('<?php echo $quesNo; ?>', 1);
						<?php }else{?>
                        nextNavigation('<?php echo $quesNo; ?>');
						<?php }?>
                    });
				</script>
			<?php }
			} else {
			$quesNo = $userExamQuestion['ExamStat']['ques_no'];
			$currQuesNo = $quesNo;
			$tempSubjectName = $userExamQuestion['Question']['Subject']['subject_name'];
			if ($tempSubjectName != $mainSubjectName) {
				$mainSubjectName = $tempSubjectName;
				$subTempId = $subTempId + 1;
				$subjectSection = "subject$subTempId";
			} else {
				$subjectSection = "sub$subTempId";
			}
			if ($k == 0){ ?>
				<script type="text/javascript">
                    $(document).ready(function () {
						<?php if($quesNo == 1){?>
                        firstNavigation('<?php echo $quesNo; ?>', 1);
						<?php }else{?>
                        nextNavigation('<?php echo $quesNo; ?>');
						<?php }?>
                    });
				</script>
			<?php } ?>
			<?php } ?>
				<div class="exam-panel <?php echo $subjectSection; ?>" id="quespanel<?php echo $quesNo; ?>">
					<?php echo $this->Form->create('Exam', array('url' => array('controller' => 'Exams', 'action' => "finish", $examId), 'name' => "post_req-$quesNo", 'id' => "post_req-$quesNo")); ?>
					<div style="display: none;"><label
							id="questype<?php echo $quesNo; ?>"><?php echo $userExamQuestion['Question']['Qtype']['type']; ?></label>
					</div>
					<div class="exam-QuestionHeader">
						<div class="exam-QuestionNo"><?php echo __('Question No.'); ?><span
								id="exam-lblQuestionNo"><?php echo $userExamQuestion['ExamStat']['ques_no']; ?></span>
						</div>
						<span style="float: right;padding-left: 5px;">
	<a class="btn btn-primary btn-xs exam-review" onclick="markForReview(<?php echo $quesNo; ?>);"><i
			class="fa fa-flag"></i></a>
							</span>
						<span style="float: right;padding-left: 5px;">
	<a class="btn btn-primary btn-xs exam-cresponse" onclick="resetAnswer(<?php echo $quesNo; ?>);"><i
			class="fa fa-recycle"></i></a>
</span>
						<div class="exam-Marks"><i class="fa fa-check"></i><span id="exam-lblRightMark"
																				 style="color:Green;"><?php echo $userExamQuestion['ExamStat']['marks']; ?></span>
							&nbsp; <i class="fa fa-remove"></i><span id="exam-lblNegativeMark"
																	 style="color:Red;"><?php echo $userExamQuestion['Question']['negative_marks']; ?></span>
						</div>
						<div class="exam-ViewIn"><?php echo __('View In'); ?>
							:<?php echo $this->Form->select('lang', $langArr, array('empty' => false, 'class' => 'examLang', 'onchange' => "changeLang(this.value,$languageCount)")); ?>
						</div>
					</div>
					<div class="exam-Question"><?php if ($userExamQuestion['Question']['passage_id']) {
							$questionStyle = 'style="width: 46%;"'; ?>
							<div id="exam-Passage" class="exam-divPassage" style="display: block;">
							<span id="exam-lblPassage" style="font-weight: bold; display: none;"></span>
							<div id="exam-divPassageText">
								<?php $passageArr = explode("%^&", $userExamQuestion['Question']['Passage']['passage']);
								foreach ($passageArr as $langIndex => $item) {
									$langIndex++; ?>
									<div class="lang-<?php echo $langIndex; ?>"><?php echo $item; ?></div>
								<?php } ?>
							</div>
							</div><?php } else {
							$questionStyle = 'style="width: 100%;"';
						} ?>
						<div class="exam-questionBox" id="exam-questionBox" <?php echo $questionStyle; ?>>
							<table class="table">
								<thead>
								<tr>
									<td>
										<div class="">
											<div
												class="lang-1"><?php echo str_replace("<script", "", $userExamQuestion['Question']['question']); ?></div>
											<?php if (is_array($userExamQuestion['Question']['QuestionsLang'])){
											foreach ($userExamQuestion['Question']['QuestionsLang'] as $k => $item){
											$k += 2; ?>
											<div
												class="lang-<?php echo $k; ?>"><?php echo str_replace("<script", "", $item['question']); ?></div>
										</div>
										<?php }
										}
										unset($k, $item); ?>
									</td>
								</tr>
								</thead>
								<?php if (strlen($userExamQuestion['Question']['hint']) > 0) { ?>
									<tr class="lang-1">
										<td>
											<div class="mrg-left lang-1"><strong><?php echo __('Hint'); ?>
													: </strong><?php echo str_replace("<script", "", $userExamQuestion['Question']['hint']); ?>
											</div>
										</td>
									</tr>
								<?php } ?>
								<?php if (is_array($userExamQuestion['Question']['QuestionsLang'])) {
									foreach ($userExamQuestion['Question']['QuestionsLang'] as $k => $item) {
										$k += 2;
										if (strlen($item['hint']) > 0) { ?>
											<tr class="lang-<?php echo$k;?>">
												<td>
													<div class="mrg-left <?php echo $k; ?>">
														<strong><?php echo __('Hint'); ?>
															: </strong><?php echo str_replace("<script", "", $item['hint']); ?>
													</div>
												</td>
											</tr>
										<?php }
									}
									unset($k, $item);
								} ?>
								<?php if ($userExamQuestion['Question']['Qtype']['type'] == "M") {
									$options = array();
									$optColor1_1 = '<span>';
									$optColor1_2 = '<span>';
									$optColor1_3 = '<span>';
									$optColor1_4 = '<span>';
									$optColor1_5 = '<span>';
									$optColor1_6 = '<span>';
									$optColor2 = '</span>';
									if ($post['Exam']['instant_result'] == 1 && $userExamQuestion['ExamStat']['answered'] == 1) {
										if (strlen($userExamQuestion['Question']['answer']) > 1) {
											$selDanger = '<span class="text-danger"><b>';
											$selSuccess = '<span class="text-success"><b>';
											foreach (explode(",", $userExamQuestion['ExamStat']['option_selected']) as $value) {
												$opt = $value;
												$varName1 = 'optColor1' . '_' . $opt;
												$$varName1 = $selDanger;
											}
											unset($value);
											foreach (explode(",", $userExamQuestion['ExamStat']['correct_answer']) as $value) {
												$opt = $value;
												$varName1 = 'optColor1' . '_' . $opt;
												$$varName1 = $selSuccess;
											}
											unset($value);
										} else {
											$selDanger = '<span class="text-danger"><b>';
											$selSuccess = '<span class="text-success"><b>';
											$opt = $userExamQuestion['ExamStat']['option_selected'];
											$varName1 = 'optColor1' . '_' . $opt;
											$$varName1 = $selDanger;
											$opt = $userExamQuestion['ExamStat']['correct_answer'];
											$varName1 = 'optColor1' . '_' . $opt;
											$$varName1 = $selSuccess;
										}
									}
									$optionKeyArr = explode(",", $userExamQuestion['ExamStat']['options']);
									foreach ($optionKeyArr as $value) {
										$optKey = "option" . $value;
										$doptCol = 'optColor1' . '_' . $value;
										$langOptionArr = array();
										if (strlen($userExamQuestion['Question'][$optKey]) > 0) {
											$langOptionArr[] = $$doptCol . str_replace("<script", "", $userExamQuestion['Question'][$optKey]) . $optColor2;
											if (is_array($userExamQuestion['Question']['QuestionsLang'])) {
												foreach ($userExamQuestion['Question']['QuestionsLang'] as $k => $item) {
													$k += 2;
													$langOptionArr[] = $$doptCol . str_replace("<script", "", $item[$optKey]) . $optColor2;
												}
												unset($k, $item);
											}
											$options[$value] = $langOptionArr;
										}
									}
									unset($value);
									?>
									<tr>
										<td>
											<?php if (strlen($userExamQuestion['Question']['answer']) > 1) {
												foreach ($options as $k => $value):?>
													<div class="checkbox"><label><input
																name="data[Exam][option_selected][]"
																value="<?php echo $k; ?>"
																<?php if (in_array($k, explode(",", $userExamQuestion['ExamStat']['option_selected']))) {
																	echo 'checked=checked';
																} ?>
																type="checkbox">
															<?php foreach ($value as $optionIndex => $item) {
																$optionIndex++; ?>
																<span
																	class="lang-<?php echo $optionIndex; ?>"><?php echo $item; ?></span>
															<?php };
															unset($item); ?></label>
													</div>
												<?php endforeach;
												unset($value, $k);
											} else {
												foreach ($options as $k => $value):?>
													<div class="radio"><label><input
																name="data[Exam][option_selected]"
																value="<?php echo $k; ?>"
																<?php if ($userExamQuestion['ExamStat']['option_selected'] == $k) {
																	echo 'checked=checked';
																} ?>align="" type="radio">
															<?php foreach ($value as $optionIndex => $item) {
																$optionIndex++; ?>
																<span
																	class="lang-<?php echo $optionIndex; ?>"><?php echo $item; ?></span>
															<?php };
															unset($item); ?>
													</div>
												<?php endforeach;
												unset($value, $k);
											}
											?>
										</td>
									</tr>
								<?php } ?>
								<?php if ($userExamQuestion['Question']['Qtype']['type'] == "T") {
									if ($post['Exam']['instant_result'] == 1 && strlen($userExamQuestion['ExamStat']['true_false']) > 0) {
										if ($userExamQuestion['ExamStat']['true_false'] == $userExamQuestion['ExamStat']['correct_answer']) {
											$trueColor = "text-success custom_text_bold";
											$falseColor = "";
										} else {
											if ($userExamQuestion['ExamStat']['correct_answer'] == "True") {
												$trueColor = "text-success custom_text_bold";
												$falseColor = "text-danger custom_text_bold";
											} else {
												$trueColor = "text-danger custom_text_bold";
												$falseColor = "text-success custom_text_bold";
											}
										}
									} else {
										$trueColor = "";
										$falseColor = "";
									}
									?>
									<tr>
										<td>
											<div class="radio"><label><input
														name="data[Exam][true_false]"
														value="True" <?php if ($userExamQuestion['ExamStat']['true_false'] == "True") {
														echo 'checked=checked';
													} ?> type="radio">
													<?php
													foreach ($languageArr as $k => $item) {
														$k += 1; ?>
														<span
															class="<?php echo $trueColor; ?> lang-<?php echo $k; ?>"><?php echo $item['Language']['value1']; ?></span>
													<?php } ?>
												</label></div>
											<div class="radio"><label><input
														name="data[Exam][true_false]"
														value="False" <?php if ($userExamQuestion['ExamStat']['true_false'] == "False") {
														echo 'checked=checked';
													} ?> type="radio"> <?php
													foreach ($languageArr as $k => $item) {
														$k += 1; ?>
														<span
															class="<?php echo $falseColor; ?> lang-<?php echo $k; ?>"><?php echo $item['Language']['value2']; ?></span>
													<?php } ?></label></div>
										</td>
									</tr>
								<?php } ?>
								<?php if ($userExamQuestion['Question']['Qtype']['type'] == "F") {
									?>
									<tr>
										<td>
											<?php echo $this->Form->input('fill_blank', array('value' => $userExamQuestion['ExamStat']['fill_blank'], 'label' => false, 'autocomplete' => 'off')); ?>
											<?php if ($post['Exam']['instant_result'] == 1 && strlen($userExamQuestion['ExamStat']['fill_blank']) > 0) {
												if ($userExamQuestion['ExamStat']['fill_blank'] == $userExamQuestion['ExamStat']['correct_answer']) {
													echo '<span class="text-success custom_text_bold">' . $userExamQuestion['ExamStat']['correct_answer'] . '</span>';
												} else {
													echo '<span class="text-danger custom_text_bold">' . $userExamQuestion['ExamStat']['correct_answer'] . '</span>';
												}
											} ?>
										</td>
									</tr>
								<?php } ?>
								<?php if ($userExamQuestion['Question']['Qtype']['type'] == "S") {
									?>
									<tr>
										<td>
											<?php echo $this->Form->input('answer', array('type' => 'textarea', 'value' => $userExamQuestion['ExamStat']['answer'], 'label' => false, 'class' => 'form-control', 'rows' => '7')); ?>
										</td>
									</tr>
								<?php } ?>
							</table>
						</div>
					</div>
					<?php echo $this->Form->end(); ?>

					<div class="" style="position: relative;bottom: 0;">
						<?php if ($userExamQuestion['Exam']['instant_result'] == 1) {?>
						<div class="col-sm-5 col-xs-5">
							<?php }else{?>
							<div class="col-sm-6 col-xs-6">
								<?php }?>
								<a class="btn btn-primary btn-block"
								   onclick="callPrev(<?php echo $quesNo; ?>);"><i
											class="fa fa-backward"></i>&nbsp;<?php echo __('Previous & Save'); ?></a>
							</div>
							<?php if ($userExamQuestion['Exam']['instant_result'] == 1) {?>
								<div class="col-sm-2 col-xs-2">
									<?php echo $this->Html->link(__('Instant Result'), array(), array('class' => "btn btn-primary btn-block exam-review"));?>
								</div>
							<?php } ?>
							<?php if ($userExamQuestion['Exam']['instant_result'] == 1) {?>
							<div class="col-sm-5 col-xs-5">
								<?php }else{?>
								<div class="col-sm-6 col-xs-6">
									<?php }?>
									<?php if ($maxQuestionCount > 0) {
										?>
										<a class="btn btn-primary btn-block"
										   onclick="callNext(<?php echo $quesNo; ?>);"><?php echo __('Next & Save'); ?></a>
									<?php } ?>
									<a class="btn btn-primary btn-block"
									   onclick="callUserAnswerSaveNext(<?php echo $quesNo; ?>);"><?php echo __('Next & Save'); ?>&nbsp;<i
												class="fa fa-forward"></i></a>
								</div>
							</div>
				</div>
			<?php endforeach;
			unset($k, $userExamQuestion, $mainSubjectName, $subTempId, $subjectSection); ?>
		</div>
		<div id="navbar" class="exam_question_palette ">
			<div class="col-md-3 col-sm-12 col-xs-12">
				<div id="timer">
					<div id="maincount"></div>
				</div>
				<div class="exam-student-name"><?php echo $userValue['Student']['name']; ?></div>
				<div id="exam-divQuestionPalleteTitle">
					<div id="exam-divQuestionPalleteSection"></div>
					<b><?php echo __('Question Palette'); ?>:</b>
				</div>
				<div id="exam-divQuestionPallete">
					<div class="exam-PalleteButtons">
						<?php foreach ($userSectionQuestion as $subjectName => $quesArr):
							$subjectNameId = str_replace(" ", "", h($subjectName));
							foreach ($quesArr as $value):$quesNo = $value['ExamStat']['ques_no'];
								if ($quesNo == 1) $btnType = "btn-default"; else$btnType = ""; ?>
								<div
									class="col-md-2 col-sm-2 col-xs-2 mrg-1"><?php echo $this->Form->button($quesNo, array('type' => 'button', 'onclick' => "navigation($quesNo)", 'id' => "navbtn$quesNo", 'class' => "exam-ButtonNotVisited")); ?></div>
							<?php endforeach;
							unset($quesArr); ?>
						<?php endforeach;
						unset($i);
						unset($value); ?>
					</div>
				</div>
				<div class="exam-divLegend">
					<b><?php echo __('Legend'); ?>:</b>
					<br>
					<table>
						<tbody>
						<tr>
							<td>
								<button class="exam-ButtonAnswered" onclick="FilterPaletteButtons('ans'); return false;"
										id="countAnswered">0
								</button>
								<?php echo __('Answered'); ?>
							</td>
							<td>
								<button class="exam-ButtonNotAnswered"
										onclick="FilterPaletteButtons('notans'); return false;" id="countNotAnswered">0
								</button>
								<?php echo __('Not Answered'); ?>
							</td>
						</tr>
						<tr>
							<td>
								<button class="exam-ButtonNotAnsweredMarked"
										onclick="FilterPaletteButtons('notansmarked'); return false;"
										id="countNotAnswerMarked">0
								</button>
								<?php echo __('Marked'); ?>
							</td>
							<td>
								<button class="exam-ButtonNotVisited"
										onclick="FilterPaletteButtons('notvisit'); return false;"
										id="countNotVisited"><?php echo $totalQuestion; ?></button>
								<?php echo __('Not Visited'); ?>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<button class="exam-ButtonAnsweredMarked"
										onclick="FilterPaletteButtons('ansmarked'); return false;" id="countAnsMarked">0
								</button>
								<?php echo __('Answered &amp; Marked for Review'); ?>
							</td>
						</tr>
						</tbody>
					</table>
					<div class="exam-divQuestionFilter">
						&nbsp;&nbsp;<?php echo __('Filter'); ?>:
						<select id="exam-ddlQStatus">
							<option selected="selected" value="all"><?php echo __('All'); ?></option>
							<option value="notvisit"><?php echo __('Not Visited'); ?></option>
							<option value="notans"><?php echo __('Not Answered'); ?></option>
							<option value="notansmarked"><?php echo __('Marked for review'); ?></option>
							<option value="ansmarked"><?php echo __('Answered &amp; marked for review'); ?></option>
							<option value="ans"><?php echo __('Answered'); ?></option>

						</select>
					</div>
				</div>
				<div class="" style="padding-top: 10px;">
					<div class="col-xs-6">
						<button id="btnQuestionPaper"
								class="btn btn-primary btn-sm btn-block"><?php echo __('Question Paper'); ?></button>
					</div>
					<div class="col-xs-6">
						<button id="btnInstructions"
								class="btn btn-primary btn-sm btn-block"><?php echo __('Instructions'); ?></button>
					</div>
					<div class="col-xs-12"></div>
					<div class="col-xs-6">
						<button id="btnProfile"
								class="btn btn-primary btn-sm btn-block"><?php echo __('Profile'); ?></button>
					</div>
					<?php
					$isSubmitButton=false;
					if($post['Exam']['exam_mode'] == "T"){
						if($remainingSection>0){?>
					<div class="col-xs-6">
						<?php echo $this->Form->button(__('Submit Section'), array('type'=>'button','id'=>'exam_submit_section','class'=>"btn btn-danger btn-sm btn-block"));?></div>
							<?php
						}else{
							$isSubmitButton=true;
						}
					}else{
						$isSubmitButton=true;
					}
					if($isSubmitButton==true){
					?>
					<div class="col-xs-6">
						<button id="submit-btn" class="btn btn-danger btn-sm btn-block"
								onclick="show_modal('<?php echo $viewUrl; ?>')"><?php echo __('Submit'); ?></button>
					</div>
					<?php }?>
				</div>
			</div>
		</div>
	</div>
	<?php echo $this->Form->create('Exam', array('url' => array('controller' => 'Exams', 'action' => "lang", $examId), 'id' => 'langfrm'));
	echo $this->Form->hidden('lang', array('id' => 'lang'));
	echo $this->Form->end(null); ?>
	<div class="modal fade" id="targetModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
		 aria-hidden="true">
		<div class="modal-content">
		</div>
	</div>
	<div class="modal fade" id="myModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title"><i
							class="fa fa-exclamation-triangle"></i>&nbsp;<?php echo __('Navigated Away'); ?></h4>
				</div>
				<div class="modal-body">
					<p>
					<blockquote><?php echo $userValue['Student']['name']; ?>
						, <?php echo __('you had navigated away from the test window. This will be reported to Moderator'); ?></blockquote>
					</p>
					<p>
					<blockquote><span
							class="text-danger"><?php echo __('Do not repeat this behaviour'); ?></span> <?php echo __('Otherwise you may get disqualified'); ?>
					</blockquote>
					</p>
					<div class="text-center">
						<button type="button" class="btn btn-default"
								data-dismiss="modal"><?php echo __('Continue'); ?></button>
					</div>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</div>
<div id="instructionBlock" class="exam-overlay" style="padding: 0 20px;display: none;">
	<div class="exam-LangaugeSelection">
		<?php //echo ('View In');?>:
		<?php //echo$this->Form->select('lang',$langArr,array('empty'=>false,'onchange'=>'changeLang(this.value)','value'=>$lang));?>
	</div>
	<br unselectable="on">
	<div class="exam-InstructionHeader" unselectable="on">
		<span id="exam-lblInstructionHeader" unselectable="on"></span>
	</div>
	<div id="exam-divInstructions">
		<table border="0" width="100%" cellspacing="0" cellpadding="0" height="50">
			<tbody>
			<tr>
				<td align="left" valign="top">
					<h2><?php echo __('Instructions For') . ' ' . $post['Exam']['name']; ?></h2>
					<p><?php echo str_replace("<script", "", $post['Exam']['instruction']); ?></p></td>
			</tr>
			</tbody>
		</table>
	</div>
	<center>
		<div class="exam-divbackButton">
			<button id="instructionBack" class="btn btn-primary btn-block"><?php echo __('Back'); ?></button>
		</div>
	</center>
</div>
<div id="userInfo" class="popup exam-overlay" style="display: none;">
	<center>
		<div id="userInfoInner">
			<table style="width:100%;">
				<tbody>
				<tr>
					<td class="tdhead"><?php echo __('Name'); ?>:&nbsp;</td>
					<td class="tdvalue"><?php echo $userValue['Student']['name']; ?></td>
				</tr>
				<tr>
					<td class="tdhead"><?php echo __('Email'); ?>:</td>
					<td class="tdvalue"><?php echo $userValue['Student']['email']; ?></td>
				</tr>
				</tbody>
			</table>
		</div>
		<div class="exam-divbackButton">
			<button id="userinfoBack" class="btn btn-primary btn-block"><?php echo __('Back'); ?></button>
		</div>
	</center>
</div>
<div id="QuestionPaperBlock" class="popup exam-overlay" style="display: none;">
	<div class="exam-QuestionPaperHeader">
		<center><h2><?php echo __('Question Paper'); ?></h2></center>
	</div>
	<div id="QuestionPaperData">
		<table class="exam-QPQuestion">
			<?php $passageTempId = null;
			$passageMainId = null;
			foreach ($userExamQuestionArr as $k => $userExamQuestion):
				$passageMainId = $userExamQuestion['Question']['passage_id'];
				if ($userExamQuestion['Question']['passage_id'] && $passageMainId != $passageTempId) {
					$passageTempId = $passageMainId; ?>
					<tr>
						<td class="exam-tdnum"></td>
						<td><p><b><?php echo __('Instruction'); ?>
									: </b> <?php $passageArr = explode("%^&", $userExamQuestion['Question']['Passage']['passage']);
								foreach ($passageArr

								as $langIndex => $item) {
								$langIndex++; ?>
							<div class="lang-<?php echo $langIndex; ?>"><?php echo $item; ?></div>
							<?php } ?></p></td>
					</tr>
				<?php } ?>
				<tr>
					<td class="exam-tdnum"><?php echo __('Q') . $userExamQuestion['ExamStat']['ques_no']; ?>.</td>
					<td>
						<table border="0" width="100%" cellspacing="0" cellpadding="0" height="50">
							<tbody>
							<tr>
								<td align="left">
									<div class="">
										<div
											class="lang-1"><?php echo str_replace("<script", "", $userExamQuestion['Question']['question']); ?></div>
										<?php if (is_array($userExamQuestion['Question']['QuestionsLang'])){
										foreach ($userExamQuestion['Question']['QuestionsLang'] as $k => $item){
										$k += 2; ?>
										<div
											class="lang-<?php echo $k; ?>"><?php echo str_replace("<script", "", $item['question']); ?></div>
									</div>
									<?php }
									}
									unset($k, $item); ?>
								</td>
							</tr>
							</tbody>
						</table>
					</td>
				</tr>
				<tr>
					<td class="exam-tdnum"></td>
					<td>
						<table border="0" width="100%" cellspacing="0" cellpadding="0">
							<tbody>
							<tr>
								<td width="40" align="center">
									<?php if ($userExamQuestion['Question']['Qtype']['type'] == "M") {
									$options = array();
									$optColor1_1 = '<span>';
									$optColor1_2 = '<span>';
									$optColor1_3 = '<span>';
									$optColor1_4 = '<span>';
									$optColor1_5 = '<span>';
									$optColor1_6 = '<span>';
									$optColor2 = '</span>';
									if ($post['Exam']['instant_result'] == 1 && $userExamQuestion['ExamStat']['answered'] == 1) {
										if (strlen($userExamQuestion['Question']['answer']) > 1) {
											$selDanger = '<span class="text-danger"><b>';
											$selSuccess = '<span class="text-success"><b>';
											foreach (explode(",", $userExamQuestion['ExamStat']['option_selected']) as $value) {
												$opt = $value;
												$varName1 = 'optColor1' . '_' . $opt;
												$$varName1 = $selDanger;
											}
											unset($value);
											foreach (explode(",", $userExamQuestion['ExamStat']['correct_answer']) as $value) {
												$opt = $value;
												$varName1 = 'optColor1' . '_' . $opt;
												$$varName1 = $selSuccess;
											}
											unset($value);
										} else {
											$selDanger = '<span class="text-danger"><b>';
											$selSuccess = '<span class="text-success"><b>';
											$opt = $userExamQuestion['ExamStat']['option_selected'];
											$varName1 = 'optColor1' . '_' . $opt;
											$$varName1 = $selDanger;
											$opt = $userExamQuestion['ExamStat']['correct_answer'];
											$varName1 = 'optColor1' . '_' . $opt;
											$$varName1 = $selSuccess;
										}
									}
									$optionKeyArr = explode(",", $userExamQuestion['ExamStat']['options']);
									foreach ($optionKeyArr as $value) {
										$optKey = "option" . $value;
										$doptCol = 'optColor1' . '_' . $value;
										$langOptionArr = array();
										if (strlen($userExamQuestion['Question'][$optKey]) > 0) {
											$langOptionArr[] = $$doptCol . str_replace("<script", "", $userExamQuestion['Question'][$optKey]) . $optColor2;
											if (is_array($userExamQuestion['Question']['QuestionsLang'])) {
												foreach ($userExamQuestion['Question']['QuestionsLang'] as $k => $item) {
													$k += 2;
													$langOptionArr[] = $$doptCol . str_replace("<script", "", $item[$optKey]) . $optColor2;
												}
												unset($k, $item);
											}
											$options[$value] = $langOptionArr;
										}
									}
									unset($value);
									?>
							<tr>
								<td>
									<?php if (strlen($userExamQuestion['Question']['answer']) > 1) {
										foreach ($options as $k => $value):?>
											<div class="checkbox"><label><input
														name="data[<?php echo $quesNo; ?>][Exam][option_selected][]"
														value="<?php echo $k; ?>"
														type="checkbox"> <?php foreach ($value as $optionIndex => $item) {
														$optionIndex++; ?>
														<span
															class="lang-<?php echo $optionIndex; ?>"><?php echo $item; ?></span>
													<?php };
													unset($item); ?></label>
											</div>
										<?php endforeach;
										unset($value, $k);
									} else {
										foreach ($options as $k => $value):?>
											<div class="radio"><label><input
														name="data[<?php echo $quesNo; ?>][Exam][option_selected]"
														value="<?php echo $k; ?>" type="radio">
													<?php foreach ($value as $optionIndex => $item) {
														$optionIndex++; ?>
														<span
															class="lang-<?php echo $optionIndex; ?>"><?php echo $item; ?></span>
													<?php };
													unset($item); ?>
											</div>
										<?php endforeach;
										unset($value, $k);
									}
									?>
								</td>
							</tr>
							<?php } ?>
							<?php if ($userExamQuestion['Question']['Qtype']['type'] == "T") {
								?>
								<tr>
									<td>
										<div class="radio"><label><input
													name="data[<?php echo $quesNo; ?>][Exam][true_false]"
													value="True" type="radio">
												<?php
												foreach ($languageArr as $k => $item) {
													$k += 1; ?>
													<span
														class="lang-<?php echo $k; ?>"><?php echo $item['Language']['value1']; ?></span>
												<?php } ?>
											</label></div>
										<div class="radio"><label><input
													name="data[<?php echo $quesNo; ?>][Exam][true_false]"
													value="False" type="radio"> <?php
												foreach ($languageArr as $k => $item) {
													$k += 1; ?>
													<span
														class="lang-<?php echo $k; ?>"><?php echo $item['Language']['value2']; ?></span>
												<?php } ?></label></div>
									</td>
								</tr>
							<?php } ?>
							<?php if ($userExamQuestion['Question']['Qtype']['type'] == "F") {
								?>
								<tr>
									<td>
										<?php echo $this->Form->input("$quesNo.Exam.fill_blank", array('value' => $userExamQuestion['ExamStat']['fill_blank'], 'label' => false, 'autocomplete' => 'off')); ?>
									</td>
								</tr>
							<?php } ?>
							<?php if ($userExamQuestion['Question']['Qtype']['type'] == "S") {
								?>
								<tr>
									<td>
										<?php echo $this->Form->input("$quesNo.Exam.answer", array('type' => 'textarea', 'value' => $userExamQuestion['ExamStat']['answer'], 'label' => false, 'class' => 'form-control', 'rows' => '7')); ?>
									</td>
								</tr>
							<?php } ?>
							</tbody>
						</table>
					</td>
				</tr>
			<?php endforeach;
			unset($k, $userExamQuestion, $passageTempId, $passageMainId); ?>
		</table>
		<br><br><br>
		<center>
			<div class="exam-divbackButton">
				<button id="questionPaperBack" class="btn btn-primary btn-block"><?php echo __('Back'); ?></button>
			</div>
		</center>
	</div>
</div>
<div style="display: none;"><label id="startSectionNo"><?php echo $startSectionNo; ?></label></div>
<style type="text/css">
	.modal-backdrop {
		background-color: #ff0000;
	}

	.modal-backdrop.in {
		opacity: .8;
	}

	.progress-bar-striped, .progress-striped .progress-bar {
		background-image: -webkit-linear-gradient(45deg, rgba(255, 255, 255, .15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .15) 50%, rgba(255, 255, 255, .15) 75%, transparent 75%, transparent);
		background-image: -o-linear-gradient(45deg, rgba(255, 255, 255, .15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .15) 50%, rgba(255, 255, 255, .15) 75%, transparent 75%, transparent);
		background-image: linear-gradient(45deg, rgba(255, 255, 255, .15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .15) 50%, rgba(255, 255, 255, .15) 75%, transparent 75%, transparent);
		-webkit-background-size: 40px 40px;
		background-size: 40px 40px;
	}
</style>
<script type="text/javascript">
    $(document).ready(function () {
		<?php foreach ($langArr as $k=> $item) {?>
        $('.lang-<?php echo $k;?>').hide();
		<?php } unset($item);?>
        $('.lang-<?php echo $lang;?>').show();
        $('.examLang').val(<?php echo $lang;?>);
		<?php foreach ($userExamQuestionArr as $k => $userExamQuestion):
		$quesNo = $userExamQuestion['ExamStat']['ques_no'];
		if($userExamQuestion['ExamStat']['answered'] == 1 && $userExamQuestion['ExamStat']['review'] == 0){
		?>ChangeClassOFPaletteButton('ans', <?php echo $quesNo;?>);
		<?php }
		elseif($userExamQuestion['ExamStat']['opened'] == 1 && $userExamQuestion['ExamStat']['answered'] == 0 && $userExamQuestion['ExamStat']['review'] == 0){
		?>ChangeClassOFPaletteButton('notans', <?php echo $quesNo;?>);
		<?php }
		elseif($userExamQuestion['ExamStat']['answered'] == 0 && $userExamQuestion['ExamStat']['review'] == 1){
		?>ChangeClassOFPaletteButton('notansmarked', <?php echo $quesNo;?>);
		<?php }
		elseif($userExamQuestion['ExamStat']['answered'] == 1 && $userExamQuestion['ExamStat']['review'] == 1){
		?>ChangeClassOFPaletteButton('ansmarked', <?php echo $quesNo;?>);
		<?php }
		endforeach;unset($userExamQuestion);?>
        $('#exam_submit_section').click(function () {
            exam_submit_section();
        });
    });
</script>
<?php $endTime = $this->Time->format('M d, Y H:i:s', $this->Time->fromString($examResult['ExamResult']['start_time']) + ($post['Exam']['duration'] * 60));
$startTime = $this->Time->format('M d, Y H:i:s', $this->Time->fromString($examResult['ExamResult']['start_time']));
$expiryUrl = $this->Html->url(array('controller' => 'Exams', 'action' => "finish/$examId"));
$serverTimeUrl = $this->Html->url(array('crm' => false, 'controller' => 'ServerTimes', 'action' => 'index'));
?>
<script type="text/javascript">
	<?php if($post['Exam']['browser_tolrance'] == 1){?>
    $(window).on("blur", function (e) {
        $.ajax({
            method: "GET",
            cache: false,
            url: '<?php echo $targetUrl;?>'
        })
            .done(function (response) {
                if (response == "Yes") {
                    window.location = '<?php echo $this->Html->url(array('controller' => 'Exams', 'action' => 'finish', $post['Exam']['id'], 'null'));?>/' + currentQuesNo();
                } else {
                    $('#myModal').modal({
                        backdrop: 'static',
                        keyboard: false
                    })
                }
            });
    });
	<?php }?>
    function timerUpdate() {
        $.ajax({
            method: "GET",
            url: '<?php echo $this->Html->url(array('controller' => 'Exams', 'action' => 'startTime', $examId));?>'
        })
            .done(function (data) {
                var dataArr = JSON.parse(data);
				<?php if($examDuration > 0){ ?>
                liftoffTime = new Date(dataArr.endTime);
                $("#maincount").countdown({
                    until: liftoffTime,
                    format: 'HMS',
                    serverSync: serverTime,
                    alwaysExpire: true,
					compact: false,
                    onExpiry: liftOff
                });
				<?php if ($post['Exam']['exam_mode'] == "T") { ?>
                liftoffTime1 = new Date(dataArr.overallTime);
                $("#maincount1").countdown({
                    until: liftoffTime1,
                    format: 'HMS',
                    compact: true,
                    serverSync: serverTime
                });
				<?php }?>
				<?php } else{ ?>
                startTime = new Date(dataArr.startTime);
                $('#maincount').countdown({since: startTime,compact: false, format: 'HMS', serverSync: serverTime});
				<?php }?>
            });
    }

    function serverTime() {
        var time = null;
        $.ajax({
            url: "<?php echo $serverTimeUrl;?>",
            async: false, dataType: 'text',
            success: function (text) {
                time = new Date(text);
            }, error: function (http, message, exc) {
                time = new Date();
            }
        });
        return time;
    }

    function liftOff() {
        window.location = '<?php echo $this->Html->url(array('controller' => 'Exams', 'action' => 'finish', $post['Exam']['id'], 'null'));?>/' + currentQuesNo()
    }
    function exam_submit_section() {
        $.msgBox({
            title: '<?php echo __('Are You Sure?');?>',
            content: '<?php echo __('You want to skip this section ? Once you skip this section you will not show again this section');?>',
            type: "confirm",
            buttons: [{value: "Yes"}, {value: "No"}],
            success: function (result) {
                if (result == "Yes") {
                    window.location='<?php echo $this->Html->url(array('controller'=>'Exams','action'=>'finish',$post['Exam']['id'],'Y'));?>';
                }
            }
        });
    }
	$( window ).load(function() {
		showExamWindow();
	});
</script>
