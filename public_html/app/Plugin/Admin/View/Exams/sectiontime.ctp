<div class="container">
    <div class="panel panel-custom mrg">
        <div class="panel-body"><?php echo $this->Session->flash(); ?>
            <div class="panel-heading"><?php echo __('Section Wise Time'); ?>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="panel-body">
                <?php echo $this->Form->create('ExamsSubject', array('name' => 'post_req', 'id' => 'post_req', 'class' => 'form-horizontal')); ?>
                <?php foreach ($post as $k => $value): ?>
                    <div class="form-group">
                        <label for="group_name"
                               class="col-sm-4 control-label"><strong><?php echo $value['Subject']['subject_name']; ?>
                                :</strong></label>
                        <div class="col-sm-2">
                            <?php if (isset($value['ExamsSubject']['duration'])) {
                                $sectionDuration = $value['ExamsSubject']['duration'];
                            } else {
                                $sectionDuration = $value['Subject']['section_time'];
                            }
                            echo $this->Form->input("$k.ExamsSubject.duration", array('type' => 'number', 'value' => $sectionDuration, 'label' => false, 'class' => 'form-control', 'placeholder' => __('Duration (Mins)'), 'div' => false, 'required' => true, 'min' => 1)); ?>
                            <?php echo $this->Form->input("$k.ExamsSubject.subject_id", array('type' => 'hidden', 'value' => $value['Subject']['id'])); ?>
                            <?php echo $this->Form->input("$k.ExamsSubject.exam_id", array('type' => 'hidden', 'value' => $examId)); ?>
                            <?php echo $this->Form->input("$k.ExamsSubject.id", array('type' => 'hidden')); ?>
                        </div>
                        <div class="col-sm-1"><strong><?php echo __('Mins');?></strong></div>
                    </div>
                <?php endforeach; ?>

                <div class="form-group text-left">
                    <div class="col-sm-offset-4 col-sm-7">
                        <button type="submit" class="btn btn-success"><span
                                class="fa fa-plus-circle"></span>&nbsp;<?php echo __('Save'); ?></button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><span
                                class="fa fa-remove"></span>&nbsp;<?php echo __('Cancel'); ?></button>
                    </div>
                </div>
            </div>
        </div>

        <?php echo $this->Form->end(null); ?>
    </div>
</div>
