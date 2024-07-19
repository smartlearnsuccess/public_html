<?php
echo $this->Html->scriptBlock("function closeExamWindow(){opener.location.reload();var ww = window.open(window.location, '_self'); ww.close();}", array('inline' => true));
echo $this->Html->scriptBlock("setTimeout(function(){closeExamWindow(); }, 1500);", array('inline' => true));?>
<div class="col-md-9 col-sm-offset-2">
    <?php echo $this->Session->flash(); ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <center><?php echo __("Thank you for giving the test. Please close window if not automatically close"); ?></center>
        </div>
    </div>
</div>
