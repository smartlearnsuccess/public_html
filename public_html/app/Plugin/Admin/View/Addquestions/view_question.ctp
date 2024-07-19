<div class="page-title">
    <div class="title-env"><h1 class="title"><?php echo __('View Exam Questions'); ?></h1></div>
</div>
<div class="panel">
    <?php echo $this->Session->flash(); ?>

    <div class="panel">
        <div class="panel-heading">
            <?php echo $this->Form->create(array('name' => 'searchfrm', 'url' => array('action' => "viewQuestion", $exam_id))); ?>
            <div class="row mrg">
                <div class="col-sm-4">
                    <?php $urlSubject = $this->Html->url(array('controller' => 'Addquestions', 'action' => 'topic', __('All Topic')));
                    echo $this->Form->input('subject_id', array('options' => array($subjectId), 'id' => 'subjectId', 'rel' => $urlSubject, 'empty' => __('All Subject'), 'class' => 'form-control', 'id' => 'subjectId', 'div' => false, 'label' => false)); ?>
                </div>
                <div class="col-sm-4">
                    <?php $urlTopic = $this->Html->url(array('controller' => 'Addquestions', 'action' => 'stopic', __('All Sub Topic')));
                    echo $this->Form->select('topic_ids', $topic, array('id' => 'topicId', 'rel' => $urlTopic, 'empty' => __('All Topic'), 'class' => 'form-control', 'div' => false, 'label' => false)); ?>
                </div>
                <div class="col-sm-4">
                    <?php echo $this->Form->select('stopic_ids', $stopic, array('id' => 'stopicId', 'empty' => __('All Sub Topic'), 'class' => 'form-control', 'div' => false, 'label' => false)); ?>
                </div>
            </div>
            <div class="row mrg">
                <div class="col-md-4">
                    <?php echo $this->Form->input('qtype_id', array('options' => array($qtypeId), 'empty' => __('Question Type'), 'class' => 'form-control', 'div' => false, 'label' => false)); ?>
                </div>
                <div class="col-md-4">
                    <?php echo $this->Form->input('diff_id', array('options' => array($diffId), 'empty' => __('Difficulty Level'), 'class' => 'form-control', 'div' => false, 'label' => false)); ?>
                </div>
                <div class="col-sm-4">
                    <?php echo $this->Form->input('keyword', array('placeholder' => __('Question, Answer'), 'class' => 'form-control', 'div' => false, 'label' => false)); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="btn-group">
                        <?php echo $this->Html->link('<span class="fa fa-arrow-left"></span>&nbsp;' . __('Back To Exam'), array('controller' => 'Exams', 'action' => 'index'), array('escape' => false, 'class' => 'btn btn-info')); ?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <button type="submit" class="btn btn-success"><span
                                class="fa fa-search"></span>&nbsp;<?php echo __('Search'); ?></button>
                    <?php echo $this->Html->link('<span class="fa fa-refresh"></span>&nbsp;' . __('Reset'), array('action' => 'viewQuestion', $exam_id  ), array('class' => 'btn btn-warning', 'escape' => false)); ?>

                </div>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
        <?php
        $serial_no = 1; ?>
        <?php echo $this->Form->create(array('name' => 'deleteallfrm', 'id' => 'addquestionfrm', 'url' => array('action' => 'adddelete'))); ?>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <tr>
                        <th><?php echo __('#'); ?></th>
                        <th><?php echo __('Subject'); ?></th>
                        <th><?php echo __('Topic'); ?></th>
                        <th><?php echo __('Sub Topic'); ?></th>
                        <th><?php echo __('Type'); ?></th>
                        <th><?php echo __('Body of Question'); ?></th>
                        <th><?php echo __('Level'); ?></th>
                        <th><?php echo __('Marks'); ?></th>
                    </tr>
                    <?php foreach ($Addquestion as $post):
                        $id = $post['Addquestion']['id'];
                        $questionStatus = $this->Function->queStatus($id);
                        ?>
                        <tr>
                            <td><?php echo $serial_no++; ?></td>
                            <td><?php echo h($subjectArr[$post['Addquestion']['subject_id']]); ?></td>
                            <td><?php echo h($topicArr[$post['Addquestion']['topic_id']]); ?></td>
                            <td><?php echo h($stopicArr[$post['Addquestion']['stopic_id']]); ?></td>
                            <td><?php echo h($qtypeId[$post['Addquestion']['qtype_id']]); ?></td>
                            <td><?php echo str_replace("<script", "", ($post['Addquestion']['question'])); ?></td>
                            <td><?php echo h($diffId[$post['Addquestion']['diff_id']]); ?></td>
                            <td><?php echo h($post['Addquestion']['marks']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php unset($post); ?>
                </table>
            </div>
            <?php echo $this->Form->input('action', array('name' => 'action', 'type' => 'hidden', 'value' => ''));
            echo $this->Form->input('exam_id', array('type' => 'hidden', 'value' => $exam_id));
            echo $this->Form->input('keyword', array('type' => 'hidden', 'value' => $this->params['keyword']));
            echo $this->Form->input('subject_id', array('type' => 'hidden', 'value' => $this->params['subject_id']));
            echo $this->Form->input('qtype_id', array('type' => 'hidden', 'value' => $this->params['qtype_id']));
            echo $this->Form->input('diff_id', array('type' => 'hidden', 'value' => $this->params['diff_id']));
            echo $this->Form->end(); ?>
        </div>
    </div>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            var height = $(window).height();
            $('iframe').css('width', 200);
            $('iframe').css('height', 200);
            $("img").addClass("img-responsive");
        });
    </script>


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

        function changeStatus(action, id) {
            $("#DeleteCheckbox" + id).prop("checked", true);
            document.deleteallfrm.action.value = action;
            var targeturl = '<?php echo $this->Html->url(array('controller' => 'Addquestions', 'action' => "adddeleteajax"));?>';
            $.ajax({
                type: 'POST',
                url: targeturl,
                data: $('#addquestionfrm').serialize(),
                success: function (response) {
                    if (response) {
                        $("#DeleteCheckbox" + id).prop("checked", false);
                        $('#addremove' + id).html(response);
                    }
                },
                error: function (e) {

                }
            });
        }


    </script>