<div <?php if (!$isError){ ?>class="container"<?php } ?>>
    <div class="panel panel-custom mrg">
        <div class="panel-heading"><?php echo __('Edit Packages'); ?><?php if (!$isError) { ?>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><?php } ?>
        </div>
        <div class="panel-body"><?php echo $this->Session->flash(); ?>
            <?php echo $this->Form->create('Package', array('class' => 'form-horizontal', 'type' => 'file')); ?>
            <?php foreach ($Package as $k => $post): $id = $post['Package']['id'];
                $form_no = $k; ?>
                <script type="text/javascript">
                    $(document).ready(function () {
                        $('#<?php echo $k;?>PackagePackageTypeF').click(function () {
                            $('#<?php echo $k;?>paidExam').hide();
                        });
                        $('#<?php echo $k;?>PackagePackageTypeP').click(function () {
                            $('#<?php echo $k;?>paidExam').show();
                        });
                        <?php if($this->request->data[$k]['Package']['package_type'] == "F"){?>
                        $('#<?php echo $k;?>paidExam').hide();
                        $('#<?php echo $k;?>PackageShowAmount').val(0);
                        $('#<?php echo $k;?>PackageAmount').val(0);
                        <?php }else{?>
                        $('#<?php echo $k;?>paidExam').show();
                        <?php }?>
                    });
                </script>
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>
                            <small class="text-danger"><?php echo __('Form'); ?><?php echo $form_no ?></small>
                        </strong></div>
                    <div class="panel-body"><?php echo $this->Session->flash(); ?>
                        <div class="form-group">
                            <label for="subject_name" class="col-sm-3 control-label">
                                <small><?php echo __('Type'); ?></small>
                            </label>
                            <div class="col-sm-9">
                                <?php echo $this->Form->input("$k.Package.package_type", array('type' => 'radio', 'options' => $packageTypeArr, 'default' => '', 'required' => true, 'legend' => false, 'before' => '<label class="radio-inline">', 'separator' => '</label><label class="radio-inline">', 'after' => '</label>', 'label' => false, 'div' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="group_name" class="col-sm-3 control-label">
                                <small><?php echo __('Exams'); ?></small>
                            </label>
                            <div class="col-sm-9">
                                <?php $pg2 = array();
                                foreach ($post['Exam'] as $packageName):
                                    $pg2[] = $packageName['id'];
                                endforeach;
                                unset($packageName); ?>
                                <?php echo $this->Form->select("$k.Exam.Exam", $exam, array('name' => "data[$k][Exam][Exam]", 'value' => $pg2, 'multiple' => true, 'label' => false, 'class' => 'form-control multiselectgrp', 'div' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="subject_name" class="col-sm-3 control-label">
                                <small><?php echo __('Package Name'); ?></small>
                            </label>
                            <div class="col-sm-9">
                                <?php echo $this->Form->input("$k.Package.name", array('label' => false, 'class' => 'form-control', 'placeholder' => __('Package Name'), 'div' => false)); ?>
                            </div>
                        </div>
                        <div id="<?php echo $k; ?>paidExam">
                            <div class="form-group">
                                <label for="subject_name" class="col-sm-3 control-label">
                                    <small><?php echo __('Amount'); ?></small>
                                </label>
                                <div class="col-sm-9">
                                    <?php echo $this->Form->input("$k.Package.show_amount", array('label' => false, 'class' => 'form-control', 'placeholder' => __('Amount'), 'div' => false)); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="subject_name" class="col-sm-3 control-label">
                                    <small><?php echo __('Discounted Amount'); ?></small>
                                </label>
                                <div class="col-sm-9">
                                    <?php echo $this->Form->input("$k.Package.amount", array('label' => false, 'class' => 'form-control', 'placeholder' => __('Discounted Amount'), 'div' => false)); ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="subject_name" class="col-sm-3 control-label">
                                <small><?php echo __('Expired (Days)'); ?></small>
                            </label>
                            <div class="col-sm-9">
                                <?php echo $this->Form->input("$k.Package.expiry_days", array('label' => false, 'class' => 'form-control', 'placeholder' => __('0 for unlimited days'), 'div' => false, 'min'=>0)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="subject_name" class="col-sm-3 control-label">
                                <small><?php echo __('Status'); ?></small>
                            </label>
                            <div class="col-sm-9">
                                <?php echo $this->Form->select("$k.Package.status", array("Active" => "Active", "Inactive" => "Inactive"), array('empty' => null, 'label' => false, 'class' => 'form-control', 'div' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="group_name"
                                   class="col-sm-3 control-label"><?php echo __('Upload Photo'); ?></label>
                            <div class="col-sm-9">
                                <?php echo $this->Form->input("$k.Package.photo", array('type' => 'file', 'label' => false, 'div' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="subject_name" class="col-sm-3 control-label">
                                <small><?php echo __('Description'); ?></small>
                            </label>
                            <div class="col-sm-9">
                                <?php echo $this->Tinymce->input("$k.Package.description", array('class' => 'form-control', 'label' => false, 'placeholder' => __('Description')), array('language' => $configLanguage, 'directionality' => $dirType), 'full'); ?>
                            </div>
                        </div>
                        <div class="form-group text-left">
                            <div class="col-sm-offset-3 col-sm-7">
                                <?php echo $this->Form->input("$k.Package.id", array('type' => 'hidden')); ?>
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
