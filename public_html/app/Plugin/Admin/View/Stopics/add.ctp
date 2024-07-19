<div class="panel panel-custom">
    <div class="panel-heading"><?php echo __('Add Sub Topics'); ?></div>
    <div class="panel-body"><?php echo $this->Session->flash(); ?>
        <?php echo $this->Form->create('Stopic', array('class' => 'form-horizontal')); ?>
        <div class="form-group">
            <label for="group_name" class="col-sm-3 control-label">
                <small><?php echo __('Subject'); ?></small>
            </label>
            <div class="col-sm-9">
                <?php $url = $this->Html->url(array('controller' => 'Stopics', 'action' => 'topic'));
                echo $this->Form->select('subject_id', $subject, array('id' => 'subjectId', 'rel' => $url, 'required' => true, 'class' => 'form-control', 'empty' => __('Please Select'))); ?>
            </div>
        </div>

        <div class="form-group">
            <label for="group_name" class="col-sm-3 control-label">
                <small><?php echo __('Topic'); ?></small>
            </label>
            <div class="col-sm-9">
                <?php echo $this->Form->select('topic_id', $topic, array('id' => 'topicId', 'required' => true, 'empty' => 'Please Select', 'class' => 'form-control', 'div' => false)); ?>
            </div>
        </div>
        <div class="form-group">
            <label for="group_name" class="col-sm-3 control-label">
                <small><?php echo __('Sub Topic Name'); ?></small>
            </label>
            <div class="col-sm-9">
                <?php echo $this->Form->input('name', array('label' => false, 'class' => 'form-control', 'placeholder' => __('Sub Topic Name'), 'div' => false)); ?>
            </div>
        </div>
        <div class="form-group text-left">
            <div class="col-sm-offset-3 col-sm-6">
                <?php echo $this->Form->button('<span class="fa fa-plus-circle"></span>&nbsp;' . __('Save'), array('class' => 'btn btn-success', 'escpae' => false)); ?>
                <?php echo $this->Html->link('<span class="fa fa-close"></span>&nbsp;' . __('Close'), array('action' => 'index'), array('class' => 'btn btn-danger', 'escape' => false)); ?>
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

    });
</script>  