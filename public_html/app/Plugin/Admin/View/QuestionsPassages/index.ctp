<?php
$langUrl = $this->Html->url(array('controller' => 'QuestionsPassages', 'action' => 'index', $questionId));
?>
<script type="text/javascript">
    function changeLang(id) {
        window.location = '<?php echo $langUrl;?>/' + id;
    }
</script>

<div class="page-title">
    <div class="title-env"><h1 class="title"><?php echo __('Add Passage'); ?></h1></div>
</div>
<div class="panel"> <?php echo $this->Session->flash(); ?>
    <div class="panel-body">
        <?php echo $this->Form->create('QuestionsPassage', array('url' => array('controller' => 'QuestionsPassages', 'action' => "index", $questionId), 'name' => 'post_req', 'id' => 'post_req', 'class' => 'form-horizontal', 'type' => 'post')); ?>
        <div class="form-group">
            <label for="group_name" class="col-sm-2 control-label"><?php echo __('Language'); ?></label>
            <div class="col-sm-4">
                <?php echo $this->Form->select('language_id', $language, array('empty' => null, 'class' => 'form-control', 'div' => false, 'label' => false, 'onchange' => 'changeLang(this.value);')); ?>
            </div>
        </div>
        <div class="form-group">
            <label for="group_name" class="col-sm-2 control-label"><?php echo __('Passage'); ?></label>
            <div class="col-sm-10">
                <?php echo $this->Tinymce->input('passage', array('class' => 'form-control', 'div' => false, 'label' => false, 'placeholder' => __('Passage')), array('language' => $configLanguage, 'directionality' => $dirType), 'full'); ?>
            </div>
        </div>
        <div class="form-group text-left">
            <div class="col-sm-offset-2 col-sm-7">
                <?php echo $this->Form->button('<span class="fa fa-plus-circle"></span>&nbsp;' . __('Save'), array('class' => 'btn btn-success', 'escpae' => false)); ?>
                <?php echo $this->Html->link('<span class="fa fa-close"></span>&nbsp;' . __('Close'), array('controller' => 'Questions', 'action' => 'index'), array('class' => 'btn btn-danger', 'escape' => false)); ?>
                <?php echo $this->Form->hidden('question_id', array('value' => $questionId));
                echo $this->Form->hidden('id'); ?>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>


