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

                <?php
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                } else {
                    echo __('My Bookmark');
                }
                ?>

            </div>
        </div>
    </div>
    <div class="panel">
        <?php //echo $this->element('pagination',array('IsSearch'=>'No','IsDropdown'=>'No'));
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
                    foreach ($Result as $post):?>
                        <tr>
                            <?php
                            $count = $this->Function->bookmark_count($post['Result']['id']);
                            if ($count > 0){

                            ?>

                            <td><?php echo $serial_no++; ?></td>
                            <td><?php echo h($post['Exam']['name']); ?></td>
                            <td><span class="badge"><?php echo h($count); ?></span></td>
                            <td><?php if ($post['Exam']['declare_result'] == 'Yes') {
                                    if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                                        $WebUrl = $siteDomain . '/crm/Results/bookmarkquestion/' . $post['Result']['id'] . '/rest?public_key=' . $this->request->query['public_key'] . '&private_key=' . $this->request->query['private_key'];
                                        echo $this->Html->link('<span class="glyphicon glyphicon-fullscreen"></span>&nbsp;', $WebUrl, array('class' => 'btn btn-default', 'escape' => false, 'data-toggle' => 'tooltip', 'title' => __('View Details')));
                                    } else {
                                        echo $this->Html->link('<span class="glyphicon glyphicon-fullscreen"></span>&nbsp;', array('action' => 'bookmarkquestion', $post['Result']['id']), array('class' => 'btn btn-default', 'escape' => false, 'data-toggle' => 'tooltip', 'title' => __('View Details')));
                                    }

                                } ?>
                                <?php } ?>
                        </tr>
                    <?php endforeach;
                    unset($post); ?>
                </table>
            </div>
            <?php //echo $this->element('pagination',array('IsSearch'=>'No','IsDropdown'=>'No'));?>
        </div>
    </div>
</div>