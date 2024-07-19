<?php echo $this->Html->scriptBlock('$(document).ready(function(){$("#checkme").click(function() {$("#submitaccept").attr("disabled", !this.checked);});});', array('inline' => true)); ?>
<?php $examId = $post['Exam']['id'];
echo $this->Form->create('Exam', array('url' => array('controller' => 'Exams', 'action' => "start", $examId, 1), 'name' => 'post_req', 'id' => 'post_req')); ?>
<div class="col-md-9 col-sm-offset-2">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong><?php echo __('Instructions') . ' ' . __('of') . ' ' . h($post['Exam']['name']); ?></strong>
        </div>
        <div class="panel-body">
            <!-- <div class="exam_instruction" style="height:280px;overflow-y: scroll;"> -->
            <div class="exam_instruction" style="height:auto;">
                <strong><?php echo str_replace("<script", "", $post['Exam']['instruction']); ?></strong>
            </div>
            <?php if ($post['Exam']['multi_language'] == 1) { ?>
            <div class="mrg"><strong><?php echo __('View In'); ?>
                    :</strong><?php echo $this->Form->select('lang', $language, array('id' => 'lang', 'empty' => false)); ?></strong>
            </div>
            <?php } else {
				echo $this->Form->hidden('lang', array('value' => 1));
			} ?>
            <!-- <div class="text-center"> -->
            <?php echo $this->Form->input('accept', array('type' => 'checkbox', 'id' => 'checkme', 'label' => __('I am ready to begin'), 'hiddenField' => false)); ?>
            <p><?php echo $this->Form->button(__('Start Exam'), array('id' => 'submitaccept', 'disabled' => 'disabled', 'class' => 'btn btn-success')); ?>
            </p>
            <!-- </div> -->

        </div>
    </div>
</div>
<script type="text/javascript">
function examSubmit(message) {
    if (confirm(message)) {
        $('#post_req').submit();
    }
}
</script>
