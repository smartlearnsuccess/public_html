<?php
if ($mathEditor)
    $editorType = "math";
else
    $editorType = "full";
$langUrl = $this->Html->url(array('controller' => 'QuestionsLangs', 'action' => 'index', $questionId));
?>
<script type="text/javascript">
    $(document).ready(function () {
        $('#myquestiontab').hide();
        $('#tf').hide();
        $('#ftb').hide();
        <?php if($questionPost['Question']['qtype_id'] == 1){?>
        $('#myquestiontab').show();<?php }
        elseif($questionPost['Question']['qtype_id'] == 2){?>
        $('#tf').show();<?php }
        elseif($questionPost['Question']['qtype_id'] == 3){?>
        $('#ftb').show();<?php }?>
    });

    function changeLang(id) {
        window.location = '<?php echo $langUrl;?>/' + id;
    }
</script>

<div class="page-title">
    <div class="title-env"><h1 class="title"><?php echo __('Add Question'); ?></h1></div>
</div>
<div class="panel"> <?php echo $this->Session->flash(); ?>
    <div class="panel-body">
        <?php echo $this->Form->create('QuestionsLang', array('url' => array('controller' => 'QuestionsLangs', 'action' => "index", $questionId,$languageId), 'name' => 'post_req', 'id' => 'post_req', 'type' => 'post')); ?>
        <div class="form-group">
            <label for="group_name" class="col-sm-1 control-label"><?php echo __('Language'); ?></label>
            <div class="col-sm-5">
                <?php echo $this->Form->select('language_id', $language, array('empty' => null, 'class' => 'form-control', 'div' => false, 'label' => false, 'onchange' => 'changeLang(this.value);')); ?>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row">
                <h5><strong><?php echo __('Question Type'); ?>
                        : <?php echo $questionPost['Qtype']['question_type']; ?></strong></h5>

            </div>
        </div>
    </div>

    <ul class="nav nav-tabs" id="myquestiontab">
        <li class="active"><a href="#Question" data-toggle="tab"><?php echo __('Question'); ?></a></li>
        <li><a href="#Answer1" data-toggle="tab"><?php echo __('Option1'); ?></a></li>
        <li><a href="#Answer2" data-toggle="tab"><?php echo __('Option2'); ?></a></li>
        <li><a href="#Answer3" data-toggle="tab"><?php echo __('Option3'); ?></a></li>
        <li><a href="#Answer4" data-toggle="tab"><?php echo __('Option4'); ?></a></li>
        <li><a href="#Answer5" data-toggle="tab"><?php echo __('Option5'); ?></a></li>
        <li><a href="#Answer6" data-toggle="tab"><?php echo __('Option6'); ?></a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="Question">
            <h4><?php echo __('Question'); ?></h4>
            <hr/>
            <?php echo $this->Tinymce->input('question', array('label' => false, 'class' => 'form-control', 'div' => false, 'placeholder' => __('Type your question here')), array('language' => $configLanguage, 'directionality' => $dirType), $editorType); ?>
        </div>
        <div class="tab-pane" id="Answer1">

            <?php echo $this->Tinymce->input('option1', array('label' => false, 'class' => 'form-control', 'div' => false, 'placeholder' => __('Option1')), array('language' => $configLanguage, 'directionality' => $dirType), $editorType); ?>
        </div>
        <div class="tab-pane" id="Answer2">

            <?php echo $this->Tinymce->input('option2', array('label' => false, 'class' => 'form-control', 'div' => false, 'placeholder' => __('Option2')), array('language' => $configLanguage, 'directionality' => $dirType), $editorType); ?>
        </div>
        <div class="tab-pane" id="Answer3">

            <?php echo $this->Tinymce->input('option3', array('label' => false, 'class' => 'form-control', 'div' => false, 'placeholder' => __('Option3')), array('language' => $configLanguage, 'directionality' => $dirType), $editorType); ?>
        </div>
        <div class="tab-pane" id="Answer4">

            <?php echo $this->Tinymce->input('option4', array('label' => false, 'class' => 'form-control', 'div' => false, 'placeholder' => __('Option4')), array('language' => $configLanguage, 'directionality' => $dirType), $editorType); ?>
        </div>
        <div class="tab-pane" id="Answer5">

            <?php echo $this->Tinymce->input('option5', array('label' => false, 'class' => 'form-control', 'div' => false, 'placeholder' => __('Option5')), array('language' => $configLanguage, 'directionality' => $dirType), $editorType); ?>
        </div>
        <div class="tab-pane" id="Answer6">

            <?php echo $this->Tinymce->input('option6', array('label' => false, 'class' => 'form-control', 'div' => false, 'placeholder' => __('Option6')), array('language' => $configLanguage, 'directionality' => $dirType), $editorType); ?>
        </div>
    </div>
    <div class="panel-body">
        <div class="form-group" id="ftb">
            <?php echo $this->Form->input('fill_blank', array('type'=>'text','class' => 'form-control', 'div' => false, 'label' => __('Blank Space'), 'escape' => false, 'placeholder' => __('Blank Space'))); ?>
        </div>
        <div class="form-group">
            <?php echo $this->Tinymce->input('explanation', array('label' => __('Explanation (Optional)'), 'class' => 'form-control', 'div' => false, 'placeholder' => __('Provide explanation in support of correct answer')), array('language' => $configLanguage, 'directionality' => $dirType), $editorType); ?>
        </div>


        <div class="col-sm-12">
            <div class="row">
                <div class="form-group">
                    <?php echo $this->Form->input('hint', array('type' => 'text', 'class' => 'form-control', 'label' => __('Hint(optional)'), 'div' => false, 'placeholder' => __('Hint to help answer correctly'))); ?>
                </div>
            </div>
        </div>
        <div class="form-group text-left">
            <div class="col-sm-7">
                <?php echo $this->Form->button('<span class="fa fa-plus-circle"></span>&nbsp;' . __('Save'), array('class' => 'btn btn-success', 'escpae' => false)); ?>
                <?php echo $this->Html->link('<span class="fa fa-close"></span>&nbsp;' . __('Close'), array('controller' => 'Questions', 'action' => 'index'), array('class' => 'btn btn-danger', 'escape' => false)); ?>
                <?php echo $this->Form->hidden('question_id', array('value' => $questionId));
                echo $this->Form->hidden('id'); ?>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>


