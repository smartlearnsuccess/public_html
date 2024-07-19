<?php
$this->Js->JqueryEngine->jQueryObject = 'jQuery';
// Paginator options
$this->Paginator->options(array(
    'update' => '#resultDiv',
    'evalScripts' => true,
));
?>
<div id="resultDiv">
    <?php echo $this->Session->flash(); ?>
    <div class="page-title-breadcrumb">
        <div class="page-header pull-left">
            <div class="page-title">
                <?php echo __('My Bookmark'); ?>
            </div>
        </div>
    </div>
    <div class="panel">
        <?php echo $this->element('pagination', array('IsSearch' => 'No', 'IsDropdown' => 'No'));
        $page_params = $this->Paginator->params();
        $limit = $page_params['limit'];
        $page = $page_params['page'];
        $serial_no = 1 * $limit * ($page - 1) + 1; ?>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <tr>
                        <th><?php echo __('S.No.'); ?></th>
                        <th><?php echo __('Exam Name'); ?></th>
                        <th><?php echo __('Total Bookmark'); ?></th>
                        <th><?php echo __('Action'); ?></th>
                    </tr>
                    <?php
                    foreach ($Bookmark as $post):?>
                        <tr>
                            <td><?php echo $serial_no++; ?></td>
                            <td><?php echo h($post['Exam']['name']); ?></td>
                            <td><span class="badge"><?php echo $post['Bookmark']['bookmark_count']; ?></span></td>
                            <td><?php if ($post['Exam']['declare_result'] == 'Yes') {
                                if($type=="rest"){
                                    echo $this->Html->link('<span class="fa fa-arrows-alt"></span>&nbsp;', array('action' => 'bookmarkquestion', $post['Bookmark']['id'],'rest?public_key='.$queryArr['public_key'].'&private_key='.$queryArr['private_key']), array('class' => 'btn btn-default', 'escape' => false, 'data-toggle' => 'tooltip', 'title' => __('View Details')));
                                }else {
                                    echo $this->Html->link('<span class="fa fa-arrows-alt"></span>&nbsp;', array('action' => 'bookmarkquestion', $post['Bookmark']['id']), array('class' => 'btn btn-default', 'escape' => false, 'data-toggle' => 'tooltip', 'title' => __('View Details')));
                                }
                                } ?>
                        </tr>
                    <?php endforeach;
                    unset($post); ?>
                </table>
            </div>
            <?php echo $this->element('pagination', array('IsSearch' => 'No', 'IsDropdown' => 'No')); ?>
        </div>
    </div>
</div>