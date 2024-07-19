<div <?php if (!$isError){ ?>class="container"<?php } ?>>
    <div class="panel panel-custom mrg">
        <div class="panel-heading"><?php echo __('Edit Passage'); ?><?php if (!$isError) { ?>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><?php } ?>
        </div>
        <div class="panel">
            <div class="panel-body"><?php echo $this->Session->flash(); ?>
                <?php echo $this->Form->create('Passage', array('name' => 'post_req', 'id' => 'post_req', 'class' => 'form-horizontal')); ?>
                <?php foreach ($Passage as $k => $post):
                    $id = $post['Passage']['id'];
                    $form_no = $k + 1; ?>
                    <div class="panel panel-default">
                        <div class="panel-heading"><strong>
                                <small class="text-danger"><?php echo __('Form'); ?><?php echo $form_no ?></small>
                            </strong></div>
                        <div class="panel-body">
                            <?php $passageArr = explode("%^&", $post['Passage']['passage']);
                            foreach ($language as $languageIndex => $item) {
                                $languageName = $item['Language']['name'];
                                $languageId = $item['Language']['id'];
                                ?>
                                <div class="form-group">
                                    <label for="group_name"
                                           class="col-sm-3 control-label"><?php echo $languageName; ?></label>
                                    <div class="col-sm-9">
                                        <?php echo $this->Tinymce->input("$k.Passage.passage_$languageId", array('value' => $passageArr[$languageIndex], 'label' => false, 'class' => 'form-control', 'placeholder' => __('Passage in %s', $languageName), 'div' => false), array('language' => $configLanguage, 'directionality' => $dirType), 'full'); ?>
                                    </div>
                                </div>
                                <?php
                            } ?>
                            <div class="form-group text-left">
                                <div class="col-sm-offset-3 col-sm-7">
                                    <?php echo $this->Form->input("$k.Passage.id", array('type' => 'hidden')); ?>
                                </div>
                            </div>
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
                        } ?>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>