<div class="container">
    <div class="panel panel-custom mrg">
        <div class="panel-heading"><?php echo __('View Slides'); ?>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="panel-body">
            <div class="h">
                <h2><?php echo($post['Slide']['heading']); ?></h2>
            </div>
            <div class="content" style="padding: 10px">
                <?php echo($post['Slide']['content']); ?>
            </div>
            <?php echo $this->Html->image($photoImg, array('class' => 'img-thumbnail')); ?>
        </div>
    </div>
</div>