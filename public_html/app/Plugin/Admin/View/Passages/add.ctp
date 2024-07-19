<div class="page-title">
    <div class="title-env"><h1 class="title"><?php echo __('Add Passage'); ?></h1></div>
</div>
<div class="panel">
    <div class="panel-body"><?php echo $this->Session->flash(); ?>
        <?php echo $this->Form->create('Passage', array('class' => 'form-horizontal')); ?>
        <?php foreach ($language as $item) {
            $language = $item['Language']['name'];
            $id = $item['Language']['id'];
            ?>
            <div class="form-group">
                <label for="group_name" class="col-sm-3 control-label">
                    <small><?php echo $language; ?></small>
                </label>
                <div class="col-sm-9">
                    <?php echo $this->Tinymce->input("passage_$id", array('label' => false, 'placeholder' => __('Passage in %s', $language), 'class' => 'form-control'), array('language' => $configLanguage, 'directionality' => $dirType), 'full'); ?>
                </div>
            </div>
            <?php
        } ?>
        <div class="form-group text-left">
            <div class="col-sm-offset-3 col-sm-7">
                <?php echo $this->Form->button('<span class="fa fa-plus-circle"></span>&nbsp;' . __('Save'), array('class' => 'btn btn-success', 'escpae' => false)); ?>
                <?php echo $this->Html->link('<span class="fa fa-close"></span>&nbsp;' . __('Close'), array('action' => 'index'), array('class' => 'btn btn-danger', 'escape' => false)); ?>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>