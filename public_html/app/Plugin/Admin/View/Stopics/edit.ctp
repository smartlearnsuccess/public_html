<div <?php if (!$isError){ ?>class="container"<?php } ?>>
    <div class="panel panel-custom mrg">
        <div class="panel-heading"><?php echo __('Edit Sub Topics'); ?><?php if (!$isError) { ?>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><?php } ?>
        </div>
        <div class="panel-body"><?php echo $this->Session->flash(); ?>
            <?php echo $this->Form->create('Stopic', array('class' => 'form-horizontal')); ?>
            <?php foreach ($Stopic as $k => $post): $id = $post['Stopic']['id'];
                $form_no = $k;
                $seltopic = "topic$k"; ?>
                <script type="text/javascript">
                    $(document).ready(function () {
                        $('#subjectId<?php echo $k;?>').change(function () {
                            var selectedValue = $(this).val();
                            var targeturl = $(this).attr('rel') + '?id=' + selectedValue;
                            $.ajax({
                                type: 'get',
                                url: targeturl,
                                beforeSend: function (xhr) {
                                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                                },
                                success: function (response) {
                                    if (response) {
                                        $('#topicId<?php echo $k;?>').html(response);
                                    }
                                },
                                error: function (e) {

                                }
                            });
                        });
                    });
                </script>

                <div class="panel panel-default">
                    <div class="panel-heading"><strong>
                            <small class="text-danger"><?php echo __('Form'); ?><?php echo $form_no ?></small>
                        </strong></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="group_name" class="col-sm-3 control-label"><?php echo __('Subject'); ?></label>
                            <div class="col-sm-9">
                                <?php $url = $this->Html->url(array('controller' => 'Stopics', 'action' => 'topic'));
                                echo $this->Form->select("$k.Stopic.subject_id", $subject, array('id' => "subjectId$k", 'rel' => $url, 'class' => 'form-control', 'empty' => __('Please Select'))); ?>

                            </div>
                        </div>
                        <div class="form-group">
                            <label for="group_name" class="col-sm-3 control-label"><?php echo __('Topic'); ?></label>
                            <div class="col-sm-9">
                                <?php echo $this->Form->select("$k.Stopic.topic_id", $$seltopic, array('id' => "topicId$k", 'required' => true, 'empty' => 'Please Select', 'class' => 'form-control', 'div' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="group_name"
                                   class="col-sm-3 control-label"><?php echo __('Sub Topic Name'); ?></label>
                            <div class="col-sm-9">
                                <?php echo $this->Form->input("$k.Stopic.name", array('label' => false, 'class' => 'form-control', 'placeholder' => __('Sub Topic Name'), 'div' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group text-left">
                            <div class="col-sm-offset-3 col-sm-6">
                                <?php echo $this->Form->input("$k.Stopic.id", array('type' => 'hidden')); ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php unset($post); ?>
            <div class="form-group text-left">
                <div class="col-sm-offset-3 col-sm-6">
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