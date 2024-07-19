<?php
echo $this->Html->script("langs/$configLanguage");
$answer_select = array();
if ($post['Question']['qtype_id'] == 1) {
    $answer_select = $post['Question']['answer'];
}
?>
<script type="text/javascript">
    $(document).ready(function () {
        $('#myquestiontab').hide();
        $('#tf').hide();
        $('#ftb').hide();
        <?php if($post['Question']['qtype_id'] == 1){?>
        $('#myquestiontab').show();<?php }
        elseif($post['Question']['qtype_id'] == 2){?>
        $('#tf').show();<?php }
        elseif($post['Question']['qtype_id'] == 3){?>
        $('#ftb').show();<?php }?>
        $('#qtype_id1').click(function () {
            $('#myquestiontab').show();
            $('#tf').hide();
            $('#ftb').hide();
        });
        $('#qtype_id2').click(function () {
            $('#tf').show();
            $('#myquestiontab').hide();
            $('#ftb').hide();
        });
        $('#qtype_id3').click(function () {
            $('#ftb').show();
            $('#myquestiontab').hide();
            $('#tf').hide();
        });
        $('#qtype_id4').click(function () {
            $('#ftb').hide();
            $('#myquestiontab').hide();
            $('#tf').hide();
        });
    });
</script>

<div class="table-responsive">
    <table class="table table-bordered">
        <tr>
            <td><strong>
                    <small class="text-primary"><?php echo __('Question Type'); ?></small>
                </strong></td>
            <td><?php echo h($questionTypeArr['Qtype']['question_type']); ?></td>
            <td><strong>
                    <small class="text-primary"><?php echo __('Subject'); ?></small>
                </strong></td>
            <td><?php echo h($subjectArr['Subject']['subject_name']); ?></td>
            <td><strong>
                    <small class="text-primary"><?php echo __('Topic'); ?></small>
                </strong></td>
            <td><?php echo h($topicArr['Topic']['name']); ?></td>
            <td><strong>
                    <small class="text-primary"><?php echo __('Sub Topic'); ?></small>
                </strong></td>
            <td><?php echo h($stopicArr['Stopic']['name']); ?></td>
        </tr>
        <tr>
            <td colspan="8">
                <?php if (isset($post['Passage']['passage'])){ ?>
        <tr>
            <td colspan="8">
                <strong><?php echo __('Passage'); ?> : </strong><br/>
                <?php $passageArr = explode("%^&", $post['Passage']['passage']);
                foreach ($passageArr as $item) {
                    echo $item . '<br>';
                }
                unset($item);
                ?>
            </td>
        </tr>
        <?php } ?>
        <tr>
            <td colspan="8">
                <div id="Question">
                    <strong><?php echo __('Question'); ?> : </strong><br/>
                    <?php echo str_replace("<script", "", $post['Question']['question']); ?>
                    <?php foreach ($post['QuestionsLang'] as $questionArr):
                        echo '<br>' . str_replace("<script", "", $questionArr['question']);
                    endforeach;
                    unset($questionArr); ?>
                    <hr/>
                </div>
                <div id="myquestiontab">
                    <div class="tab-pane" id="Answer1">
                        <strong><?php echo __('Option1'); ?> : </strong><br>
                        <?php echo str_replace("<script", "", $post['Question']['option1']); ?>
                        <?php foreach ($post['QuestionsLang'] as $questionArr):
                            echo '<br>' . str_replace("<script", "", $questionArr['option1']);
                        endforeach;
                        unset($questionArr); ?>
                        <hr/>
                    </div>
                    <div class="tab-pane" id="Answer2">
                        <strong><?php echo __('Option2'); ?>: </strong><br>
                        <?php echo str_replace("<script", "", $post['Question']['option2']); ?>
                        <?php foreach ($post['QuestionsLang'] as $questionArr):
                            echo '<br>' . str_replace("<script", "", $questionArr['option2']);
                        endforeach;
                        unset($questionArr); ?>
                        <hr/>
                    </div>
                    <div class="tab-pane" id="Answer3">
                        <strong><?php echo __('Option3'); ?> : </strong><br>
                        <?php echo str_replace("<script", "", $post['Question']['option3']); ?>
                        <?php foreach ($post['QuestionsLang'] as $questionArr):
                            echo '<br>' . str_replace("<script", "", $questionArr['option3']);
                        endforeach;
                        unset($questionArr); ?>
                        <hr/>
                    </div>
                    <div class="tab-pane" id="Answer4">
                        <strong><?php echo __('Option4'); ?> : </strong><br>
                        <?php echo str_replace("<script", "", $post['Question']['option4']); ?>
                        <?php foreach ($post['QuestionsLang'] as $questionArr):
                            echo '<br>' . str_replace("<script", "", $questionArr['option4']);
                        endforeach;
                        unset($questionArr); ?>
                        <hr/>
                    </div>
                    <?php if (strlen($post['Question']['option5']) > 0) { ?>
                        <div class="tab-pane" id="Answer5">
                            <strong><?php echo __('Option5'); ?> : </strong><br>
                            <?php echo str_replace("<script", "", $post['Question']['option5']); ?>
                            <?php foreach ($post['QuestionsLang'] as $questionArr):
                                echo '<br>' . str_replace("<script", "", $questionArr['option5']);
                            endforeach;
                            unset($questionArr); ?>
                            <hr/>
                        </div>
                    <?php }
                    if (strlen($post['Question']['option6']) > 0) { ?>
                        <div class="tab-pane" id="Answer6">
                            <strong><?php echo __('Option6'); ?> : </strong><br>
                            <?php echo str_replace("<script", "", $post['Question']['option6']); ?>
                            <?php foreach ($post['QuestionsLang'] as $questionArr):
                                echo '<br>' . str_replace("<script", "", $questionArr['option6']);
                            endforeach;
                            unset($questionArr); ?>
                            <hr/>
                        </div>
                    <?php } ?>
                    <div class="tab-pane" id="CorrectAnswer">
                        <p><br/><strong><?php echo __('Correct Answer'); ?>
                                :<?php echo __("Option$answer_select"); ?> </strong></p>
                    </div>
                </div>
                <div id="tf">
                    <p><br/><strong><?php echo __('Answer'); ?>
                            : </strong><?php echo ucfirst(strtolower($post['Question']['true_false'])); ?></p>
                </div>
</div>
</div>
</div>
</div>
</div>
<div class="form-group" id="ftb">
    <p><br/><strong><?php echo __('Answer'); ?> : </strong><br>
        <?php echo $post['Question']['fill_blank']; ?>
        <?php foreach ($post['QuestionsLang'] as $questionArr):
            echo '<br>' . str_replace("<script", "", $questionArr['fill_blank']);
        endforeach;
        unset($questionArr); ?>
    </p>
</div>
</td>
</tr>
<?php if (strlen($post['Question']['explanation']) > 0) { ?>
    <tr>
        <td><strong>
                <small class="text-primary"><?php echo __('Explanation'); ?></small>
            </strong></td>
        <td colspan="8">
            <?php echo str_replace("<script", "", $post['Question']['explanation']); ?><br>
            <?php foreach ($post['QuestionsLang'] as $questionArr):
                echo '<br>' . str_replace("<script", "", $questionArr['explanation']);
            endforeach;
            unset($questionArr); ?>
        </td>
    </tr>
<?php } ?>
<?php if (strlen($post['Question']['hint']) > 0) { ?>
    <tr>
        <td><strong>
                <small class="text-primary"><?php echo __('Hint'); ?></small>
            </strong></td>
        <td colspan="8"><?php echo $post['Question']['hint']; ?><br>
            <?php foreach ($post['QuestionsLang'] as $questionArr):
                echo '<br>' . str_replace("<script", "", $questionArr['hint']);
            endforeach;
            unset($questionArr); ?>
        </td>
    </tr>
<?php } ?>
<tr>
    <td><strong>
            <small class="text-primary"><?php echo __('Marks'); ?></small>
        </strong></td>
    <td><?php echo h($post['Question']['marks']); ?></td>
    <td><strong>
            <small class="text-primary"><?php echo __('Negative Marks'); ?></small>
        </strong></td>
    <td><?php echo h($post['Question']['negative_marks']); ?></td>
    <td><strong>
            <small class="text-primary"><?php echo __('Difficulty Level'); ?></small>
        </strong></td>
    <td colspan="2"><?php echo h($diffArr['Diff']['diff_level']); ?></td>
</tr>
</table>
</div>
