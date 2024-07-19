<div class="container">
    <div class="row">
        <div class="col-sm-12 mrg">
            <div class="panel panel-custom">
                <div class="panel-heading panel-heading-custom">

                    <div class="widget">
                        <h2 class="title-border m0" style="float: left;"><?php echo __('Package Details'); ?></h2>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>

                </div>
                <div style="clear: both;"></div>
                <br>
                <div class="col-md-3">
                    <div class="p_img">
                        <?php if (strlen($post['Package']['photo']) > 0) {
    $photo = "package_thumb/" . $post['Package']['photo'];
} else {
    $photo = "nia.png";
}?>
                        <?php echo $this->Html->image($photo, array('alt' => $post['Package']['name'])); ?>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <td><strong><?php echo __('Package Name'); ?> :</strong> <span
                                        class="text-info"><strong><?php echo h($post['Package']['name']); ?></strong></span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong><?php echo __('Amount'); ?> :</strong> <span
                                        class="text-danger"><strong><strike><?php echo $currency . $post['Package']['show_amount']; ?></strike></strong></span><span
                                        class="text-success"><big><strong>
                                                <?php echo $currency . $post['Package']['amount']; ?></strong></big></span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong><?php echo __('Expiry'); ?> :</strong> <span class="text-info"><strong><?php if ($post['Package']['expiry_days'] == 0) {
    echo 'Unlimited';
} else {
    echo $post['Package']['expiry_days'];
}

echo ' ' . __('Days');?></strong></span></td>
                            </tr>
                            <tr>
                                <td><strong><?php echo __('Exams'); ?> :</strong> <span class="text-info"><strong><?php foreach ($post['Exam'] as $examName):
    echo h($examName['name']);?> |
                                            <?php endforeach;
unset($examName);
unset($examName);?></strong></span></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="col-md-12 Package_description">
                    <?php echo str_replace("<script", "", $post['Package']['description']); ?>
                </div>
                <div style="clear: both;"></div>
            </div>
        </div>
    </div>
</div>
