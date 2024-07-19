<?php

if ($mathEditor)
    $editorType = "math";
else
    $editorType = "full";

?>
<div <?php if (!$isError){ ?>class="container"<?php } ?>>
    <div class="panel panel-custom mrg">
        <div class="panel-heading"><?php echo __('Edit Packages'); ?><?php if (!$isError) { ?>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><?php } ?>
        </div>
        <div class="panel-body"><?php echo $this->Session->flash(); ?>
            <?php echo $this->Form->create('Homesection', array('class' => 'form-horizontal', 'type' => 'file')); ?>
            <?php foreach ($Homesection as $k => $post): $id = $post['Homesection']['id'];
                $form_no = $k; ?>
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>
                            <small class="text-danger"><?php echo __('Form'); ?>1</small>
                        </strong></div>
                    <div class="panel-body"><?php echo $this->Session->flash(); ?>
                        <div class="form-group">
                            <label for="subject_name" class="col-sm-3 control-label">
                                <small><?php echo __('Sections'); ?></small>
                            </label>
                            <div class="col-sm-9">
                                <?php echo $this->Form->input("$k.Homesection.section", array('label' => false, 'class' => 'form-control', 'placeholder' => __('section Name'), 'div' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="subject_name" class="col-sm-3 control-label">
                                <small><?php echo __('Heading'); ?></small>
                            </label>
                            <div class="col-sm-9">
                                <?php echo $this->Form->input("$k.Homesection.sections_heading", array('label' => false, 'class' => 'form-control', 'placeholder' => __('Heading'), 'div' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="subject_name" class="col-sm-3 control-label">
                                <small><?php echo __('Content'); ?></small>
                            </label>
                            <div class="col-sm-9">
                                <?php echo $this->Tinymce->input("$k.Homesection.sections_content", array('label' => false, 'class' => 'form-control', 'div' => false, 'placeholder' => __('Content')), array('language' => $configLanguage, 'directionality' => $dirType), $editorType); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="subject_name" class="col-sm-3 control-label">
                                <small><?php echo __('Show content'); ?></small>
                            </label>
                            <div class="col-sm-9">
                                <?php echo $this->Form->input("$k.Homesection.content", array('type' => 'radio', 'options' => array("1" => __('Yes'), "0" => __('No')), 'default' => '1', 'legend' => false, 'before' => '<label class="radio-inline">', 'separator' => '</label><label class="radio-inline">', 'after' => '</label>', 'label' => false, 'div' => false)); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="subject_name" class="col-sm-3 control-label">
                                <small><?php echo __('Show image'); ?></small>
                            </label>
                            <div class="col-sm-9">
                                <?php echo $this->Form->input("$k.Homesection.image", array('type' => 'radio', 'options' => array("1" => __('Yes'), "0" => __('No')), 'default' => '1', 'legend' => false, 'before' => '<label class="radio-inline">', 'separator' => '</label><label class="radio-inline">', 'after' => '</label>', 'label' => false, 'div' => false)); ?>
                            </div>
                        </div>

                        <div class="form-group text-left">
                            <div class="col-sm-offset-3 col-sm-7">
                                <?php echo $this->Form->input("$k.Homesection.id", array('type' => 'hidden')); ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php unset($post); ?>
            <div class="form-group text-left">
                <div class="col-sm-offset-3 col-sm-7">
                    <?php echo $this->Form->button('<span class="fa fa-refresh"></span>&nbsp;' . __('Update'), array('class' => 'btn btn-success', 'escape' => false)); ?>
                    <?php if (!$isError) { ?>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><span
                                class="fa fa-remove"></span>&nbsp;<?php echo __('Cancel'); ?></button><?php } else {
                        echo $this->Html->link('<span class="fa fa-close"></span>&nbsp;' . __('Close'), array('action' => 'index'), array('class' => 'btn btn-danger', 'escape' => false));
                    } ?>
                </div>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>
