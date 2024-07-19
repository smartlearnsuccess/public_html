<?php
if($mathEditor && $examDetails['Exam']['math_editor_type']=="1"){
	echo $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-MML-AM_HTMLorMML');
}
if($mathEditor && $examDetails['Exam']['math_editor_type']=="1"){
	?><script type="text/x-mathjax-config">MathJax.Hub.Config({extensions: ["tex2jax.js"],jax: ["input/TeX", "output/HTML-CSS"],tex2jax: {inlineMath: [["$", "$"],["\\(", "\\)"]]}});</script>
<?php }?>
<style type="text/css">
	tr.mytr {
		float: left;
		width: 25%;
		border-top: 1px solid;
	}

	@media only screen and (max-width: 900px) {
		tr.mytr {
			float: none;
			width: 50%;
			border-top: 1px solid;
		}
	}

	@media only screen and (max-width: 600px) {
		tr.mytr {
			float: none;
			width: 100%;
			border-top: 1px solid;
		}
	}

	@media screen and (max-width: 600px) {
		.row.my-result > div > ul > li {
			width: 140px;
		}

		.right {
			float: right;
		}

		.center {
			text-align: left;
		}

		table#cart tbody td .form-control {
			width: 20%;
			display: inline !important;
		}

		table#cart thead {
			display: none;
		}

		table#cart tbody td {
			display: block;
			padding: .6rem;
			min-width: 320px;
		}

		/* table#cart tbody tr td:first-child { background: #333; color: #fff; }*/
		table#cart tbody td:before {
			content: attr(data-th);
			font-weight: bold;
			/*display: inline-block; */
			/* width: 8rem;*/
		}

		table#cart tfoot td {
			display: block;
		}

		table#cart tfoot td .btn {
			display: block;
		}
	}

	.page-header-topbar {
		display: none;
	}

	.page-content {
		margin: 0;
	}

	.sidebar-main.sidebar {
		display: none;
	}

	div#footer {
		display: none;
	}

	.row.my-result {
		padding-top: 0px !important;
		margin: 0;
	}

	.content {
		padding: 0px !important;
	}

	.exam-panel {
		overflow-y: hidden !important;
	}


	table#cart tbody td {
		min-width: 0px !important;
	}

	.optionSerial {
		float: left;
		margin-top: 2px;
		max-width: 88%
	}

	i.material-icons.clear {
		color: red;
		font-size: 17px;
	}

	i.material-icons.check {
		color: #26ce26;
		font-size: 17px;
	}

	.tab-content {
		color: #191919;
	}

	p {
		overflow: hidden;
		margin-bottom: 5px;
	}

	.subjectNamecls {
		color: #fff;
	}

	.subjecthed {
		text-decoration: none;
	}
	.btn-xs, .btn-group-xs>.btn {
		padding: 1px 2px;
		font-size: 11px;
	}
	.myonp{
		line-height: 30px;
	}
	.icon_true_false{
		display: inline;
		margin-right: 2px;
	}
</style>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<?php
$bookmarkUrl = $this->Html->Url(array('controller' => 'Results', 'action' => 'bookmark', $id,$studentId)); ?>
<script type="text/javascript">
    function navigation(quesNo) {
        $('.exam-panel').hide();
        $('#quespanel' + quesNo).show();
        $("html, body").animate({scrollTop: -200}, 600);
    }

    function callPrev(quesNo) {
        if (quesNo != 1) quesNo--;
        $('.exam-panel').hide();
        $('#quespanel' + quesNo).show();
        //$("html, body").animate({scrollTop: 15}, 600);
    }

    function callNext(quesNo) {
        if ($('#totalQuestion').text() != quesNo) quesNo++;
        $('.exam-panel').hide();
        $('#quespanel' + quesNo).show();
        //$("html, body").animate({scrollTop: 15}, 600);
        return false;
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
            }
            else {
                $('#navbtn' + quesNo).removeClass('btn-success');
                $('#bookmark' + quesNo).removeClass('btn-danger');
                $('#bookmark' + quesNo).addClass('btn-success');
                $('#bookmark' + quesNo).html('<span class="fa fa-star"></span> Bookmark');
            }
            $('#exam-loading').hide();
        });
    }

    $(document).ready(function () {
        $('.exam-panel').hide();
        $('#quespanel1').show();
        $('.compare').hide();
        $('#comppanel0').show();
    });

</script>
<style type="text/css">
	.col-md-12 {
		padding: 5px;
	}

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
</style>
<script>
    // $(window).load(function() {
    // $("html, body").animate({scrollTop: 15}, 600);
    // });
    // $(window).scroll(function() {
    // var y = $(window).scrollTop();
    // if(y==0){
    //     $("html, body").animate({scrollTop: 15}, 600);
    // }
    // });
</script>
<div style="display: none;"><label id="totalQuestion"><?php echo $examDetails['Result']['total_question']; ?></label>
</div>

<div class="my-result">
	<div class="" style="padding: 0;">

		<div class="" id="solution">
			<div style="height: 15px;">&nbsp;</div>
			<div style="margin: 5px;">
				<span class="exam-ViewIn" style="margin-left: 50px;"><?php echo __('View In'); ?>
                        :<?php echo $this->Form->select('lang', $langArr, array('empty' => false, 'class' => 'examLang', 'onchange' => "changeLang(this.value,$languageCount)")); ?></span>
			</div>
			<div class="col-sm-9">
				<?php foreach ($post

				as $k => $ques):
				$quesNo = $ques['ExamStat']['ques_no']; ?>
				<div class="exam-panel" id="quespanel<?php echo $quesNo; ?>">
					<div class="exam-panel-inner"  style="height:430px;overflow-y: scroll;">
						<?php if ($ques['Question']['passage_id']) {
							$questionStyle = 'style="width: 48%;"'; ?>
							<div id="exam-Passage" class="exam-divPassage"
								 style="display: block;overflow-y: scroll;height: 150px;">
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
											<?php echo '<strong style="float: left;">' . __('Question') . ': ' . $quesNo . '&nbsp;&nbsp;</strong><div style="float: none;width: 98%;">' . str_replace("<script", "", $ques['Question']['question']); ?></div>


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
												$incorrectImg = $this->Html->image('incorrect_icon.png',array('class'=>'icon_true_false'));
												break;
											}
										}
										unset($value);
										foreach (explode(",", $ques['ExamStat']['correct_answer']) as $value) {
											if ($opt == $value) {
												$incorrectImg = $this->Html->image('correct_icon.png',array('class'=>'icon_true_false'));
												break;
											}
										}
										unset($value);
									} else {
										if ($opt == $ques['ExamStat']['correct_answer']) {
											$correctImg = $this->Html->image('correct_icon.png',array('class'=>'icon_true_false'));
										} else {
											$correctImg = "";
										}
										if ($opt == $ques['ExamStat']['option_selected'] && $ques['ExamStat']['ques_status'] == 'W') {
											$incorrectImg = $this->Html->image('incorrect_icon.png',array('class'=>'icon_true_false'));
										} else {
											$incorrectImg = "";
										}
									}
									//echo '<p>'.$optionSerial.'. '.$incorrectImg.$correctImg.' ';
									//echo '<span class="lang-1">'.$option.'</span>';
									echo '<div class="myonp">' . $optionSerial . '. ' . $incorrectImg . $correctImg . '<span class="lang-1">' . $option . '</span>';
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
									echo '</div>';
								endforeach;
								unset($option);
							}
							if ($ques['Question']['Qtype']['type'] == "T") {
								$correctImgTrue = "";
								$correctImgFalse = "";
								$incorrectImgTrue = "";
								$incorrectImgFalse = "";
								if ($ques['Question']['true_false'] == "True") {
									$correctImgTrue = $this->Html->image('correct_icon.png',array('class'=>'icon_true_false'));
								} else {
									$correctImgFalse = $this->Html->image('correct_icon.png',array('class'=>'icon_true_false'));
								}
								if ($ques['ExamStat']['ques_status'] == 'W' && $ques['ExamStat']['true_false'] == "True") {
									$incorrectImgTrue = $this->Html->image('incorrect_icon.png',array('class'=>'icon_true_false'));
								}
								if ($ques['ExamStat']['ques_status'] == 'W' && $ques['ExamStat']['true_false'] == "False") {
									$incorrectImgFalse = $this->Html->image('incorrect_icon.png',array('class'=>'icon_true_false'));
								}
							foreach ($languageArr as $k => $item) {
								$k += 1;
								echo '<span class="lang-' . $k . '">' . $correctImgTrue . $incorrectImgTrue . $item['Language']['value1'] . '</span> <span class="lang-' . $k . '">/ ' . $correctImgFalse . $incorrectImgFalse . $item['Language']['value2'] . '</span>';
							}
							}
							?>
						</td>
					</tr>
					<tr>
						<td><?php if ($ques['ExamStat']['answered']=="1"){
								echo '<strong class="text-info">' . __('Attempt') . '</strong>';
							} else{
							echo '<strong class="text-warning">' . __('Not Attempt') . '</strong>'; ?></strong></td>
						<?php } ?>
						<?php if ($ques['ExamStat']['ques_status'] == 'R') { ?>
							<td><strong class="text-success"><?php echo __('Correct'); ?></strong></td><?php } ?>
						<?php if ($ques['ExamStat']['ques_status'] == 'W'){ ?>
						<td><strong class="text-danger"><?php echo __('Incorrect'); ?></strong></td>

					</tr>
				<tr>
				<td colspan="3"><strong><?php echo __('Your Answers'); ?> :</strong>&nbsp;<strong
					class="text-danger"><?php echo $userAnswer; ?></strong>
				<?php if ($ques['Question']['Qtype']['type'] != "S") { ?><br>
					<strong><?php echo __('Correct Answer'); ?> :</strong>&nbsp;<strong
						class="text-success"><?php echo $correctAnswer; ?></strong><?php } ?>
				</td><?php }else{ ?>
				<?php if ($ques['Question']['Qtype']['type'] != "S"){ ?></tr>
					<tr>
						<td colspan="3"><strong><?php echo __('Correct Answer'); ?> :</strong>&nbsp;<strong
								class="text-success"><?php echo $correctAnswer; ?></strong></td><?php }
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
			<div style="position: absolute; top: 430px; z-index:999999;">
				<?php
				if ($quesNo != 1) {
					echo $this->Form->button('&larr;' . __('Prev'), array('type' => 'button', 'onclick' => "callPrev($quesNo);", 'class' => 'btn btn-default btn-sm btn-block', 'style' => 'background: #FF4081;height: 40px;width: 125px;box-shadow: 1px 2px 3px #888888;color: #fff;border: none;float: left;margin: 10px 10px 0px 10px;', 'escape' => false));
				} ?>

				<?php if (count($post) != $quesNo) {
					echo $this->Form->button(__('Next') . '&rarr;', array('type' => 'button', 'onclick' => "callNext($quesNo);", 'class' => 'btn btn-default btn-sm btn-block', 'style' => 'background: #FF4081;height: 40px;width: 125px;box-shadow: 1px 2px 3px #888888;color: #fff;border: none;margin-bottom:0px;margin-top:10px;', 'escape' => false));
				} ?>
			</div>
			<div style="position: absolute; top: 470px; z-index: 999999; width: 92%;">
				<?php if ($ques['ExamStat']['bookmark'] == "Y") {
					$btnBookmark = '<span class="fa fa-star-o"></span>' . __('Unbookmark');
					$btnColor = 'btn-danger';
				} else {
					$btnBookmark = '<span class="fa fa-star"></span> ' . __('Bookmark');
					$btnColor = 'btn-success';
				}
				echo $this->Form->button($btnBookmark, array('type' => 'button', 'onclick' => "callBoomark($quesNo);", 'id' => "bookmark$quesNo", 'class' => "btn $btnColor btn-sm btn-block", 'escape' => false,'style'=>'margin-top:20px;')); ?>
			</div>

		</div>
		<?php endforeach;
		unset($ques); ?>
	</div>
	<br><br><br>
	<div class="col-sm-3">
		<div class="panel-group" style="overflow: hidden;padding: 10px;box-shadow: 0px 2px 5px #888888;margin-top: 45px;"
			 id="accordion">
			<?php $i = 0;
			foreach ($userSectionQuestion as $subjectName => $quesArr):$i++;
				$subjectNameId = str_replace(array(" ", ",", "&", "amp;", "[", "]"), "", h($subjectName));
				?>
				<div class="panel panel-default">
					<div class="panel-heading" style="background: #08BBE2;color: #fff;">
						<a data-toggle="collapse" class="subjecthed" data-parent="#accordion"
						   href="#<?php echo $subjectNameId; ?>"><h4
								class="panel-title subjectNamecls"><?php echo h($subjectName); ?></h4></a>
					</div>
					<div id="<?php echo $subjectNameId; ?>"
						 class="panel-collapse collapse<?php if ($i == 1) { ?> in<?php } ?>">
						<div class="panel-body" style="border: 1px solid #08BBE2;">
							<div class="row">
								<?php foreach ($quesArr as $value):$quesNo = $value['ExamStat']['ques_no'];
									if ($value['ExamStat']['ques_status'] == "R") {
										$btnColor = "btn-success";
									} elseif ($value['ExamStat']['ques_status'] == "W") {
										$btnColor = "btn-danger";
									} elseif ($value['ExamStat']['ques_status'] == "" || $value['ExamStat']['ques_status'] == NULL) {
										$btnColor = "btn-warning";
									} else {
										$btnColor = "btn-default";
									}    ?>
									<div class="col-md-3 cols-sm-3 col-xs-3 mrg-1"><?php echo $this->Form->button($quesNo, array('type' => 'button', 'onclick' => "navigation($quesNo)", 'id' => "navbtn$quesNo", 'class' => "btn btn-circle $btnColor btn-sm navigation")); ?></div>
								<?php endforeach;
								unset($quesArr); ?>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach;
			unset($i);
			unset($value); ?>
		</div>
		<div style="padding: 25px;"></div>

	</div>
</div>
<script type="text/javascript">
    $("img").addClass("img-responsive");
</script>
<script type="text/javascript">
    $(document).ready(function () {
		<?php foreach ($langArr as $k=> $item) {?>
        $('.lang-<?php echo $k;?>').hide();
		<?php } unset($item);?>
        $('.lang-1').show();
    });
</script>
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
