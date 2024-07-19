<div <?php if (!$isError){ ?>class="container"<?php } ?>>
    <div class="panel panel-custom mrg">
        <div class="panel-heading"><?php echo __('Edit Exam'); ?><?php if (!$isError) { ?>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><?php } ?>
        </div>
        <div class="panel-body"><?php echo $this->Session->flash(); ?>
            <?php echo $this->Form->create('Exam', array('name' => 'post_req', 'id' => 'post_req', 'class' => 'form-horizontal')); ?>
            <?php foreach ($Exam as $k => $post): $id = $post['Exam']['id'];
                $form_no = $k + 1; ?>
                <script type="text/javascript">
                    $(document).ready(function () {
                        $('#start_date<?php echo $id;?>').datetimepicker({
                            locale: '<?php echo $configLanguage;?>',
                            format: '<?php echo $dpFormat;?> HH:mm'
                        });
                        $('#end_date<?php echo $id;?>').datetimepicker({
                            locale: '<?php echo $configLanguage;?>',
                            format: '<?php echo $dpFormat;?> HH:mm',
                            useCurrent: false //Important! See issue #1075
                        });
                        $("#start_date<?php echo $id;?>").on("dp.change", function (e) {
                            $('#end_date<?php echo $id;?>').data("DateTimePicker").minDate(e.date);
                        });
                        $("#end_date<?php echo $id;?>").on("dp.change", function (e) {
                            $('#start_date<?php echo $id;?>').data("DateTimePicker").maxDate(e.date);
                        });
                        $('#<?php echo $k;?>ExamPaidExam').click(function () {
                            $('#<?php echo $k;?>paidExam').hide();
                        });
                        $('#<?php echo $k;?>ExamPaidExam1').click(function () {
                            $('#<?php echo $k;?>paidExam').show();
                        });
                        $('#<?php echo $k;?>paidExam').hide();
                        <?php if($post['Exam']['paid_exam'] == 1){?>
                        $('#<?php echo $k;?>paidExam').show();<?php }?>
                    });
                </script>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <small class="text-danger"><strong><?php echo __('Form'); ?><?php echo $form_no ?></strong>
                        </small>
                    </div>
                    <div class="panel-body"><?php echo $this->Session->flash(); ?>

                        <div class="form-group">
                            <label for="group_name" class="col-sm-2 control-label"><?php echo __('Package'); ?></label>
                            <div class="col-sm-4">
                                <?php $pg2 = array();
                                foreach ($post['Package'] as $packageName):
                                    $pg2[] = $packageName['id'];
                                endforeach;
                                unset($packageName); ?>
                                <?php echo $this->Form->select("$k.ExamsPackage.package_id", $package, array('value' => $pg2, 'multiple' => true, 'label' => false, 'class' => 'form-control multiselectgrp', 'div' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="group_name"
                                   class="col-sm-2 control-label"><?php echo __('Exam Name'); ?></label>
                            <div class="col-sm-4">
                                <?php echo $this->Form->input("$k.Exam.name", array('label' => false, 'class' => 'form-control', 'placeholder' => __('Exam Name'), 'div' => false)); ?>
                            </div>
                            <label for="group_name"
                                   class="col-sm-2 control-label"><?php echo __('Passing Percentage'); ?></label>
                            <div class="col-sm-4">
                                <?php echo $this->Form->input("$k.Exam.passing_percent", array('label' => false, 'class' => 'form-control', 'placeholder' => __('Passing Percentage'), 'div' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="group_name"
                                   class="col-sm-2 control-label"><?php echo __('Instruction'); ?></label>
                            <div class="col-sm-10">
                                <?php echo $this->Tinymce->input("$k.Exam.instruction", array('label' => false, 'class' => 'form-control', 'placeholder' => __('Instruction'), 'div' => false), array('language' => $configLanguage, 'directionality' => $dirType), 'full'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="group_name" class="col-sm-2 control-label"><?php echo __('Syllabus'); ?></label>
                            <div class="col-sm-10">
                                <?php echo $this->Tinymce->input("$k.Exam.syllabus", array('label' => false, 'class' => 'form-control', 'placeholder' => __('syllabus'), 'div' => false), array('language' => $configLanguage, 'directionality' => $dirType), 'full'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="group_name"
                                   class="col-sm-2 control-label"><?php echo __('Exam Duration (Min.)'); ?></label>
                            <div class="col-sm-4">
                                <?php echo $this->Form->input("$k.Exam.duration", array('label' => false, 'class' => 'form-control', 'placeholder' => __('0 for unlimited duration'), 'div' => false)); ?>
                            </div>
                            <label for="group_name"
                                   class="col-sm-2 control-label"><?php echo __('Attempt Count'); ?></label>
                            <div class="col-sm-4">
                                <?php echo $this->Form->input("$k.Exam.attempt_count", array('label' => false, 'class' => 'form-control', 'placeholder' => __('0 for unlimited attempt'), 'div' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="group_name"
                                   class="col-sm-2 control-label"><?php echo __('Start Date'); ?></label>
                            <div class="col-sm-4">
                                <div class="input-group date" id="start_date<?php echo $id; ?>">
                                    <?php echo $this->Form->input("$k.Exam.start_date", array('type' => 'text', 'value' => $this->Time->format($sysDay . $dateSep . $sysMonth . $dateSep . $sysYear . $dateGap . 'H' . $timeSep . $sysMin, $post['Exam']['start_date']), 'label' => false, 'class' => 'form-control', 'div' => false)); ?>
                                    <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                </div>
                            </div>
                            <label for="group_name" class="col-sm-2 control-label"><?php echo __('End Date'); ?></label>
                            <div class="col-sm-4">
                                <div class="input-group date" id="end_date<?php echo $id; ?>">
                                    <?php echo $this->Form->input("$k.Exam.end_date", array('type' => 'text', 'value' => $this->Time->format($sysDay . $dateSep . $sysMonth . $dateSep . $sysYear . $dateGap . 'H' . $timeSep . $sysMin, $post['Exam']['end_date']), 'label' => false, 'class' => 'form-control', 'div' => false)); ?>
                                    <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="group_name"
                                   class="col-sm-2 control-label"><?php echo __('Show Answer Sheet'); ?></label>
                            <div class="col-sm-4">
                                <?php echo $this->Form->input("$k.Exam.declare_result", array('type' => 'radio', 'options' => array("Yes" => __('Yes'), "No" => __('No')), 'default' => 'Yes', 'legend' => false, 'before' => '<label class="radio-inline">', 'separator' => '</label><label class="radio-inline">', 'after' => '</label>', 'label' => false, 'div' => false)); ?>
                            </div>
                            <label for="group_name"
                                   class="col-sm-2 control-label"><?php echo __('Select Group'); ?></label>
                            <div class="col-sm-4">
                                <?php $gp2 = array();
                                foreach ($post['Group'] as $groupName):
                                    $gp2[] = $groupName['id']; ?>
                                <?php endforeach;
                                unset($groupName); ?>
                                <?php echo $this->Form->select("$k.ExamGroup.group_name", $group_id, array('value' => $gp2, 'multiple' => true, 'label' => false, 'class' => 'form-control multiselectgrp', 'div' => false));
                                unset($gp2); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="group_name"
                                   class="col-sm-2 control-label"><?php echo __('Negative Marking'); ?></label>
                            <div class="col-sm-4">
                                <?php echo $this->Form->input("$k.Exam.negative_marking", array('type' => 'radio', 'options' => array("Yes" => __('Yes'), "No" => __('No')), 'default' => 'Yes', 'legend' => false, 'before' => '<label class="radio-inline">', 'separator' => '</label><label class="radio-inline">', 'after' => '</label>', 'label' => false, 'div' => false)); ?>
                            </div>
                            <label for="group_name"
                                   class="col-sm-2 control-label"><?php echo __('Random Question'); ?></label>
                            <div class="col-sm-4">
                                <?php echo $this->Form->input("$k.Exam.ques_random", array('type' => 'radio', 'options' => array("1" => __('Yes'), "0" => __('No')), 'default' => '0', 'legend' => false, 'before' => '<label class="radio-inline">', 'separator' => '</label><label class="radio-inline">', 'after' => '</label>', 'label' => false, 'div' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group">
								    <label for="group_name" class="col-sm-2 control-label">
									<small><?php echo __('Exam Mode'); ?></small>
								    </label>
								    <div class="col-sm-4">
									<?php echo $this->Form->input("$k.Exam.exam_mode", array('type' => 'radio', 'options' => array("D" => __('Default'), "T" => __('Section Wise Timer')), 'default' => '', 'legend' => false, 'before' => '<label class="radio-inline">', 'separator' => '</label><label class="radio-inline">', 'after' => '</label>', 'label' => false, 'div' => false)); ?>
								    </div>
								</div>
								
                        <?php if ($frontExamPaid > 0) { ?>
                            <div class="form-group">
                                <label for="group_name"
                                       class="col-sm-2 control-label"><?php echo __('Paid Exam'); ?></label>
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input("$k.Exam.paid_exam", array('type' => 'radio', 'options' => array("1" => __('Yes'), "" => __('No')), 'default' => '', 'legend' => false, 'before' => '<label class="radio-inline">', 'separator' => '</label><label class="radio-inline">', 'after' => '</label>', 'label' => false, 'div' => false)); ?>
                                </div>
                                <div id="<?php echo $k; ?>paidExam">
                                    <label for="group_name"
                                           class="col-sm-2 control-label"><?php echo __('Amount'); ?></label>
                                    <div class="col-sm-4">
                                        <?php echo $this->Form->input("$k.Exam.amount", array('label' => false, 'class' => 'form-control', 'placeholder' => __('Amount'), 'div' => false)); ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="form-group">
                            <label for="group_name"
                                   class="col-sm-2 control-label"><?php echo __('Result After Finish'); ?></label>
                            <div class="col-sm-4">
                                <?php echo $this->Form->input("$k.Exam.finish_result", array('type' => 'radio', 'options' => array("1" => __('Yes'), "0" => __('No')), 'default' => '0', 'legend' => false, 'before' => '<label class="radio-inline">', 'separator' => '</label><label class="radio-inline">', 'after' => '</label>', 'label' => false, 'div' => false)); ?>
                            </div>
                            <label for="group_name"
                                   class="col-sm-2 control-label"><?php echo __('Browser Tolerance'); ?></label>
                            <div class="col-sm-4">
                                <?php echo $this->Form->input("$k.Exam.browser_tolrance", array('type' => 'radio', 'options' => array("1" => __('Yes'), "0" => __('No')), 'default' => '1', 'legend' => false, 'before' => '<label class="radio-inline">', 'separator' => '</label><label class="radio-inline">', 'after' => '</label>', 'label' => false, 'div' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="group_name"
                                   class="col-sm-2 control-label"><?php echo __('Instant Result'); ?></label>
                            <div class="col-sm-4">
                                <?php echo $this->Form->input("$k.Exam.instant_result", array('type' => 'radio', 'options' => array("1" => __('Yes'), "0" => __('No')), 'default' => '0', 'legend' => false, 'before' => '<label class="radio-inline">', 'separator' => '</label><label class="radio-inline">', 'after' => '</label>', 'label' => false, 'div' => false)); ?>
                            </div>
                            <label for="group_name"
                                   class="col-sm-2 control-label"><?php echo __('Option Shuffle'); ?></label>
                            <div class="col-sm-4">
                                <?php echo $this->Form->input("$k.Exam.option_shuffle", array('type' => 'radio', 'options' => array("1" => __('Yes'), "0" => __('No')), 'default' => '1', 'legend' => false, 'before' => '<label class="radio-inline">', 'separator' => '</label><label class="radio-inline">', 'after' => '</label>', 'label' => false, 'div' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="group_name" class="col-sm-2 control-label">
                                <small><?php echo __('Multi Language'); ?></small>
                            </label>
                            <div class="col-sm-4">
                                <?php echo $this->Form->input("$k.Exam.multi_language", array('type' => 'radio', 'options' => array("1" => __('Yes'), "0" => __('No')), 'default' => '1', 'legend' => false, 'before' => '<label class="radio-inline">', 'separator' => '</label><label class="radio-inline">', 'after' => '</label>', 'label' => false, 'div' => false)); ?>
                            </div>
							<label for="group_name" class="col-sm-2 control-label">
                                <small><?php echo __('Math Editor'); ?></small>
                            </label>
                            <div class="col-sm-4">
                                <?php echo $this->Form->input("$k.Exam.math_editor_type", array('type' => 'radio', 'options' => array("1" => __('Yes'), "0" => __('No')), 'default' => '1', 'legend' => false, 'before' => '<label class="radio-inline">', 'separator' => '</label><label class="radio-inline">', 'after' => '</label>', 'label' => false, 'div' => false)); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group text-left">
                    <div class="col-sm-offset-3 col-sm-7">
                        <?php echo $this->Form->input("$k.Exam.id", array('type' => 'hidden')); ?>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php unset($post); ?>
            <div class="form-group text-left">
                <div class="col-sm-offset-3 col-sm-7">
                    <?php echo $this->Form->button('<span class="fa fa-refresh"></span>&nbsp;' . __('Update'), array('class' => 'btn btn-success', 'escpae' => false)); ?>
                    <?php if (!$isError) { ?>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><span
                                class="fa fa-remove"></span>&nbsp;<?php echo __('Cancel'); ?></button><?php } else {
                        echo $this->Html->link('<span class="fa fa-close"></span>&nbsp;' . __('Close'), array('action' => 'index'), array('class' => 'btn btn-danger', 'escape' => false));
                    } ?> </div>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
