<?php
$this->Js->JqueryEngine->jQueryObject = 'jQuery';
// Paginator options
$this->Paginator->options(array(
    'update' => '#resultDiv',
    'evalScripts' => true,
));
?>
<div id="resultDiv">
    <div class="page-title">
        <div class="title-env"><h1 class="title"><?php echo __('Questions'); ?></h1></div>
    </div>
    <div class="panel">
        <?php echo $this->Session->flash(); ?>
        <div class="panel-heading">
            <div class="btn-group">
                <?php $url = $this->Html->url(array('controller' => 'Questions'));
                echo $this->Html->link('<span class="fa fa-plus-circle"></span>&nbsp;' . __('Add New Question'), array('action' => 'add'), array('escape' => false, 'class' => 'btn btn-success')); ?>
                <?php echo $this->Html->link('<span class="fa fa-trash"></span>&nbsp;' . __('Delete'), 'javascript:void(0);', array('name' => 'deleteallfrm', 'id' => 'deleteallfrm', 'onclick' => 'check_perform_delete();', 'escape' => false, 'class' => 'btn btn-danger')); ?>
                <?php echo $this->Html->link('<span class="fa fa-exchange"></span>&nbsp;' . __('Import/Export Question'), array('controller' => 'Iequestions'), array('name' => 'ieq', 'id' => 'ieq', 'escape' => false, 'class' => 'btn btn-info')); ?>
            </div>
            <?php echo $this->Form->create(array('name' => 'searchfrm', 'url'=>array('action' => "index"))); ?>
            <div class="row mrg">
                <div class="col-sm-4">
                    <?php $urlSubject = $this->Html->url(array('controller' => 'Questions', 'action' => 'topic', __('All Topic')));
                    echo $this->Form->input('subject_ids', array('options' => array($subjectId), 'id' => 'subjectId', 'rel' => $urlSubject, 'empty' => __('All Subject'), 'class' => 'form-control', 'id' => 'subjectId', 'div' => false, 'label' => false)); ?>
                </div>
                <div class="col-sm-4">
                    <?php $urlTopic = $this->Html->url(array('controller' => 'Questions', 'action' => 'stopic', __('All Sub Topic')));
                    echo $this->Form->select('topic_ids', $topic, array('id' => 'topicId', 'rel' => $urlTopic, 'empty' => __('All Topic'), 'class' => 'form-control', 'div' => false, 'label' => false)); ?>
                </div>
                <div class="col-sm-4">
                    <?php echo $this->Form->select('stopic_ids', $stopic, array('id' => 'stopicId', 'empty' => __('All Sub Topic'), 'class' => 'form-control', 'div' => false, 'label' => false)); ?>
                </div>
            </div>
            <div class="row mrg">
                <div class="col-sm-4">
                    <?php echo $this->Form->input('qtype_ids', array('options' => array($qtypeId), 'empty' => __('All Question Type'), 'class' => 'form-control', 'div' => false, 'label' => false)); ?>
                </div>
                <div class="col-sm-4">
                    <?php echo $this->Form->input('diff_ids', array('options' => array($diffId), 'empty' => __('All Difficulty Level'), 'class' => 'form-control', 'div' => false, 'label' => false)); ?>
                </div>
                <div class="col-sm-4">
                    <?php echo $this->Form->input('keyword', array('placeholder' => __('Question, Answer'), 'class' => 'form-control', 'div' => false, 'label' => false)); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <button type="submit" class="btn btn-success"><span
                                class="fa fa-search"></span>&nbsp;<?php echo __('Search'); ?></button>
                    <?php echo $this->Html->link('<span class="fa fa-refresh"></span>&nbsp;' . __('Reset'), array('controller' => 'Questions', 'action' => 'index'), array('class' => 'btn btn-warning', 'escape' => false)); ?>

                </div>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
        <?php echo $this->element('pagination', array('IsSearch' => 'No'));
        $page_params = $this->Paginator->params();
        $limit = $page_params['limit'];
        $page = $page_params['page'];
        $serial_no = 1 * $limit * ($page - 1) + 1; ?>
        <?php echo $this->Form->create(array('name' => 'deleteallfrm', 'url' => array('action' => 'deleteall'))); ?>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <tr>
                        <th><?php echo $this->Form->checkbox('checkbox', array('value' => 'deleteall', 'name' => 'selectAll', 'label' => false, 'id' => 'selectAll', 'hiddenField' => false)); ?></th>
                        <th><?php echo $this->Paginator->sort('id', __('#'), array('direction' => 'desc')); ?></th>
                        <th><?php echo $this->Paginator->sort('question', __('Question'), array('direction' => 'asc')); ?></th>
                        <th><?php echo $this->Paginator->sort('Subject.subject_name', __('Subject'), array('direction' => 'asc')); ?></th>
                        <th><?php echo $this->Paginator->sort('Topic.name', __('Topic'), array('direction' => 'asc')); ?></th>
                        <th><?php echo $this->Paginator->sort('Stopic.name', __('Sub Topic'), array('direction' => 'asc')); ?></th>
                        <th><?php echo __('Group'); ?></th>
                        <th><?php echo __('Action'); ?></th>
                    </tr>
                    <?php
                    $params=null;
                    foreach($this->params['named'] as $k=>$value):
                    $params.=$k.':'.urlencode($value).'/';
                    endforeach;unset($k,$value);
                    foreach ($Question as $post):
                        $id = $post['Question']['id']; ?>
                        <tr>
                            <td><?php echo $this->Form->checkbox(false, array('value' => $post['Question']['id'], 'name' => 'data[Question][id][]', 'id' => "DeleteCheckbox$id", 'class' => 'chkselect')); ?></td>
                            <td><?php echo $serial_no++; ?></td>
                            <td><?php echo str_replace("<script", "", ($post['Question']['question'])); ?></td>
                            <td><?php  echo h($subjectArr[$post['Question']['subject_id']]); ?></td>
                            <td><?php echo h($topicArr[$post['Question']['topic_id']]); ?></td>
                            <td><?php echo h($stopicArr[$post['Question']['stopic_id']]); ?></td>
                            <td><?php echo $this->Function->showGroupName($post['Group']); ?></td>
                            <td class="pbutton">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                                            aria-expanded="false">
                                        <?php echo __('Action'); ?> <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                    <li> <?php echo $this->Html->link('<span class="fa fa-language"></span>&nbsp;' . __('Question Language'), array('controller' => 'QuestionsLangs', 'action' => 'index', $id), array('escape' => false)); ?></li>
                                        <li><?php echo $this->Html->link('<span class="fa fa-arrows-alt"></span>&nbsp;' . __('View'), 'javascript:void(0);', array('onclick' => "show_modal('$url/viewquestion/$id');", 'escape' => false)); ?></li>
                                        <li><?php echo $this->Html->link('<span class="fa fa-edit"></span>&nbsp;' . __('Edit'), 'javascript:void(0);', array('name' => 'editallfrm', 'onclick' => "check_perform_sedit_new('$url/edit/$id/$params');", 'escape' => false)); ?></li>
                                        <li><?php echo $this->Html->Link('<span class="fa fa-trash"></span>&nbsp;' . __('Delete'), 'javascript:void(0);', array('onclick' => "check_perform_sdelete('$id');", 'escape' => false)); ?></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php unset($post); ?>
                </table>
            </div>
            <?php echo $this->Form->end(); ?>
            <?php echo $this->element('pagination'); ?>
        </div>
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
</script>