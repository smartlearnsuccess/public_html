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
        <div class="title-env"><h1 class="title"><?php echo __('Home Sections'); ?></h1></div>
    </div>
    <div class="panel">
        <?php echo $this->Session->flash(); ?>

        <?php //echo $this->element('pagination');
        $url = $this->Html->url(array('controller' => 'Homesections'));
        $page_params = $this->Paginator->params();
        $limit = $page_params['limit'];
        $page = $page_params['page'];
        $serialNo = 1 * $limit * ($page - 1) + 1; ?>
        <?php echo $this->Form->create(array('name'=>'deleteallfrm','url'=>array('action' => 'deleteall')));?>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <tr>
                        <!-- <th><?php //echo $this->Form->checkbox('checkbox', array('value' => 'deleteall', 'name' => 'selectAll', 'label' => false, 'id' => 'selectAll', 'hiddenField' => false)); ?></th> -->
                        <th><?php echo $this->Paginator->sort('id', __('#'), array('direction' => 'desc')); ?></th>
                        <th><?php echo $this->Paginator->sort('subject_name', __('Sections'), array('id' => 'asc')); ?></th>
                        <th><?php echo __('Heading'); ?></th>
                        <th><?php echo __('Show content'); ?></th>
                        <th><?php echo __('Show image'); ?></th>
                        <th><?php echo __('status'); ?></th>
                        <th><?php echo __('Action'); ?></th>
                    </tr>
                    <?php foreach ($Homesections as $post):
                        $id = $post['Homesection']['id'];

                        ?>
                        <tr>
                            <!-- <td><?php //echo $this->Form->checkbox(false, array('value' => $post['Homesection']['id'], 'name' => 'data[Homesection][id][]', 'id' => "DeleteCheckbox$id", 'class' => 'chkselect'));
                            ?></td> -->
                            <td><?php echo $serialNo++; ?></td>
                            <td><?php echo h($post['Homesection']['section']); ?></td>
                            <td><?php echo h($post['Homesection']['sections_heading']); ?></td>
                            <td><?php if ($post['Homesection']['content'] == 1) {
                                    echo 'Yes';
                                } else {
                                    echo 'No';
                                } ?></td>
                            <td><?php if ($post['Homesection']['image'] == 1) {
                                    echo 'Yes';
                                } else {
                                    echo 'No';
                                } ?></td>
                            <td>
                                <?php if ($post['Homesection']['published'] == "Published") {
                                    echo $this->Html->link('<span class="fa fa-check"></span>&nbsp;' . __('Published'), array('controller' => 'Homesections', 'action' => 'published', $id, 'Yes'), array('escape' => false, 'class' => 'btn btn-success'));
                                } else {
                                    echo $this->Html->link('<span class="fa fa-times-circle"></span>&nbsp;' . __('Unpublished'), array('controller' => 'Homesections', 'action' => 'published', $id, 'No'), array('escape' => false, 'class' => 'btn btn-danger'));
                                } ?>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                                            aria-expanded="false">
                                        <?php echo __('Action'); ?> <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <!-- <li><?php //echo $this->Html->link('<span class="fa fa-arrows-alt"></span>&nbsp;' . __('View'), 'javascript:void(0);', array('onclick' => "show_modal('$url/View/$id');", 'escape' => false));
                                        ?></li> -->
                                        <li><?php echo $this->Html->link('<span class="fa fa-edit"></span>&nbsp;' . __('Edit'), 'javascript:void(0);', array('name' => 'editallfrm', 'onclick' => "check_perform_sedit('$url','$id');", 'escape' => false)); ?></li>
                                        <li><?php echo $this->Html->link('<i class="fa fa-photo"></i>&nbsp;' . __('Change Photo'), 'javascript:void(0);', array('onclick' => "show_modal('$url/changephoto/$id');", 'escape' => false)); ?></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php unset($post); ?>
                </table>
            </div>
            <?php echo $this->Form->end(); ?>
            <?php //echo $this->element('pagination'); ?>
        </div>
    </div>

</div>