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
        <div class="title-env"><h1 class="title"><?php echo __('Packages'); ?></h1></div>
    </div>
    <div class="panel">
        <?php echo $this->Session->flash(); ?>
        <div class="panel-heading">
            <div class="btn-group">
                <?php $url = $this->Html->url(array('controller' => 'Packages'));
                echo $this->Html->link('<span class="fa fa-plus-circle"></span>&nbsp;' . __('Add New Package'), array('controller' => 'Packages', 'action' => 'add'), array('escape' => false, 'class' => 'btn btn-success')); ?>
                <?php echo $this->Html->link('<span class="fa fa-edit"></span>&nbsp;' . __('Edit'), 'javascript:void(0);', array('name' => 'editallfrm', 'id' => 'editallfrm', 'onclick' => "check_perform_edit('$url');", 'escape' => false, 'class' => 'btn btn-warning')); ?>
                <?php echo $this->Html->link('<span class="fa fa-trash"></span>&nbsp;' . __('Delete'), 'javascript:void(0);', array('name' => 'deleteallfrm', 'id' => 'deleteallfrm', 'onclick' => 'check_perform_delete();', 'escape' => false, 'class' => 'btn btn-danger')); ?>
            </div>
        </div>
        <?php echo $this->element('pagination');
        $page_params = $this->Paginator->params();
        $limit = $page_params['limit'];
        $page = $page_params['page'];
        $serialNo = 1 * $limit * ($page - 1) + 1; ?>
        <?php echo $this->Form->create(array('name' => 'deleteallfrm', 'url' => array('action' => 'deleteall'))); ?>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <tr>
                        <th><?php echo $this->Form->checkbox('checkbox', array('value' => 'deleteall', 'name' => 'selectAll', 'label' => false, 'id' => 'selectAll', 'hiddenField' => false)); ?></th>
                        <th><?php echo $this->Paginator->sort('id', __('#'), array('direction' => 'desc')); ?></th>
                        <th><?php echo $this->Paginator->sort('package_type', __('Type'), array('direction' => 'asc')); ?></th>
                        <th><?php echo $this->Paginator->sort('subject_name', __('Package Name'), array('direction' => 'asc')); ?></th>
                        <th><?php echo __('Exam'); ?></th>
                        <th><?php echo __('Amount'); ?></th>
                        <th><?php echo __('Discounted Amount'); ?></th>
                        <th><?php echo __('Expiry Days'); ?></th>
                        <th><?php echo __('Status'); ?></th>
                        <th><?php echo __('Action'); ?></th>
                    </tr>
                    <?php foreach ($Package as $post):
                        $id = $post['Package']['id']; ?>
                        <tr>
                            <td><?php echo $this->Form->checkbox(false, array('value' => $post['Package']['id'], 'name' => 'data[Package][id][]', 'id' => "DeleteCheckbox$id", 'class' => 'chkselect')); ?></td>
                            <td><?php echo $serialNo++; ?></td>
                            <td>
                                <span class="label label-<?php if ($post['Package']['package_type'] == "F") echo "danger"; else echo "primary"; ?>"><?php echo $packageTypeArr[$post['Package']['package_type']]; ?></span>
                            </td>
                            <td><?php echo h($post['Package']['name']); ?></td>
                            <td><?php echo $this->Function->showPackageName($post['Exam']); ?></td>
                            <td><strike><?php if ($post['Package']['package_type'] == "P") {
                                        echo $currency . $post['Package']['show_amount'];
                                    } ?></strike></td>
                            <td><?php if ($post['Package']['package_type'] == "P") {
                                    echo $currency . $post['Package']['amount'];
                                } ?></td>
                            <td><?php if ($post['Package']['expiry_days'] == 0) echo 'Unlimited'; else echo $post['Package']['expiry_days'];
                                echo ' ' . __('Days'); ?></td>
                            <td><span
                                        class="label label-<?php if ($post['Package']['status'] == "Active") echo "success"; else echo "danger"; ?>"><?php echo $post['Package']['status']; ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                                            aria-expanded="false">
                                        <?php echo __('Action'); ?> <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><?php echo $this->Html->link('<span class="fa fa-arrows-alt"></span>&nbsp;' . __('View'), 'javascript:void(0);', array('onclick' => "show_modal('$url/View/$id');", 'escape' => false)); ?></li>
                                        <li><?php echo $this->Html->link('<span class="fa fa-edit"></span>&nbsp;' . __('Edit'), 'javascript:void(0);', array('name' => 'editallfrm', 'onclick' => "check_perform_sedit('$url','$id');", 'escape' => false)); ?></li>
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