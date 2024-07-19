<div class="panel">
    <div class="panel-heading">
        <div class="title-env"><h3 class="title"><?php echo __('Import/Export Questions'); ?></h3></div>
        <div class="btn-group">
            <?php echo $this->Html->link('<span class="fa fa-arrow-left"></span>&nbsp;' . __('Back To Questions'), array('controller' => 'Questions', 'action' => 'index'), array('escape' => false, 'class' => 'btn btn-info')); ?>
            <?php echo $this->Html->link('<span class="fa fa-upload"></span>&nbsp;' . __('Export Questions'), array('controller' => 'Iequestions', 'action' => 'exportquestions'), array('escape' => false, 'class' => 'btn btn-success')); ?>
        </div>
    </div>
    <div class="panel-body"><?php echo $this->Session->flash(); ?>
        <?php echo $this->Form->create('Iequestion', array('url' => array('controller' => 'Iequestions', 'action' => 'import'), 'name' => 'post_req', 'id' => 'post_req', 'class' => 'form-horizontal', 'type' => 'file')); ?>
        <div class="form-group">
            <label for="site_name" class="col-sm-3 control-label"><?php echo __('Select File'); ?></label>
            <div class="col-sm-3">
                <?php echo $this->Form->input('file', array('type' => 'file', 'label' => false, 'div' => false)); ?>
            </div>
            <div
                    class="col-sm-6"><?php echo $this->Html->link(__('Click here to download excel file format'), array('controller' => 'Iequestions', 'action' => 'download'), array('escape' => false, 'class' => 'text-danger')); ?></div>
        </div>
        <div class="form-group">
            <label for="site_name" class="col-sm-3 control-label"><?php echo __('Group'); ?></label>
            <div class="col-sm-9">
                <?php echo $this->Form->select('QuestionGroup.group_name', $group_id, array('multiple' => true, 'class' => 'form-control multiselectgrp', 'div' => false)); ?>
            </div>
        </div>
        <div class="form-group">
            <label for="site_name" class="col-sm-3 control-label"><?php echo __('Subject'); ?></label>
            <div class="col-sm-9">
                <?php $urlSubject = $this->Html->url(array('controller' => 'Questions', 'action' => 'topic'));
                echo $this->Form->input('subject_id', array('options' => array($subject_id), 'id' => 'subjectId', 'rel' => $urlSubject, 'empty' => __('Please Select'), 'class' => 'form-control', 'div' => false, 'label' => false)); ?>
            </div>
        </div>
        <div class="form-group">
            <label for="site_name" class="col-sm-3 control-label"><?php echo __('Topic'); ?></label>
            <div class="col-sm-9">
                <?php $urlTopic = $this->Html->url(array('controller' => 'Questions', 'action' => 'stopic'));
                echo $this->Form->select('topic_id', $topic, array('id' => 'topicId', 'rel' => $urlTopic, 'required' => true, 'class' => 'form-control', 'empty' => __('Select'))); ?>
            </div>
        </div>
        <div class="form-group">
            <label for="site_name" class="col-sm-3 control-label"><?php echo __('Sub Topic'); ?></label>
            <div class="col-sm-9">
                <?php echo $this->Form->select('stopic_id', $stopic, array('id' => 'stopicId', 'required' => false, 'class' => 'form-control', 'empty' => __('Select'))); ?>
            </div>
        </div>
        <div class="form-group text-left">
            <div class="col-sm-offset-3 col-sm-7">
                <?php echo $this->Form->button('<span class="fa fa-download"></span>&nbsp;' . __('Import Questions'), array('class' => 'btn btn-success', 'escpae' => false)); ?>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#subjectId').change(function () {
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
                        $('#topicId').html(response);
                    }
                },
                error: function (e) {

                }
            });
        });

        $('#topicId').change(function () {
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
                        $('#stopicId').html(response);
                    }
                },
                error: function (e) {

                }
            });
        });

    });
</script>
