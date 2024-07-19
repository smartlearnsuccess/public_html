<?php
$this->Js->JqueryEngine->jQueryObject = 'jQuery';
// Paginator options
$this->Paginator->options(array(
    'update' => '#resultDiv',
    'evalScripts' => true,
));
?>
<div id="resultDiv">
    <?php echo $this->Session->flash();
    $url = $this->Html->url(array('controller' => 'Mails', 'action' => 'view'));
    $urlAction = str_replace($this->params['prefix']."_","",$this->params['action']);
    if ($urlAction == "trash")
        $deleteAction = "deleteall";
    else
        $deleteAction = "trashall";
    ?>
    <style type="text/css">
        .page-calendar ul.sidebar-list-info > li, .page-mail ul.sidebar-list-info > li {
            position: relative;
            padding: 2px 0px;
        }

        .SentItem {
            background: #f08527 !important;
        }
        
        .inbox {
            background: #107300 !important;
        }

        .trash {
            background: #000 !important;
        }

        .col-xs-4 {
            padding: 5px !important;
        }

        a.btn.btn-danger.pll.prl {
            border: none;
            width: 100%;
        }

        /*.page-mail .sidebar li a {margin-right: 15px;}*/

    </style>
    <div class="page-title-breadcrumb">
        <div class="page-header pull-left">
            <div class="page-title"><?php echo __('Mailbox'); ?></div>
        </div>
    </div>
    <div class="panel">

        <?php //echo $this->element('pagination',array('IsSearch'=>'No','IsDropdown'=>'No'));
        $page_params = $this->Paginator->params();
        $limit = $page_params['limit'];
        $page = $page_params['page'];
        $serial_no = 1 * $limit * ($page - 1) + 1; ?>


    </div>
    <div class="page-mail">
        <div class="row">
            <div class="col-md-3">
                <div class="sidebar sidebar-left nav nav-pills">
                    <div class="sidebar-row">
                        <ul class="sidebar-list-info list-unstyled">
                            <li class="mbm" style="padding: 0px !important; margin-bottom:10px">
                                <div class="sidebar-title">

                                    <?php
                                    if ($app == 'app') { ?>
                                        <div class="col-md-12" style="padding: 0px">
                                            <div class="col-xs-4"><?php echo $this->Html->link(__('Compose'), array('controller' => 'Mails', 'action' => 'compose'), array('class' => 'btn btn-danger pll prl')); ?></div>
                                            <div class="col-xs-4"><?php echo $this->Html->link(__('Sent Item'), array('controller' => 'Mails', 'action' => 'sent'), array('class' => 'btn btn-danger pll prl SentItem')); ?></div>
                                            <div class="col-xs-4"><?php echo $this->Html->link(__('Trash'), array('controller' => 'Mails', 'action' => 'trash'), array('class' => 'btn btn-danger pll prl trash ')); ?></div>
                                        </div>
                                        <div style="clear: both;"></div>
                                    <?php } else {
                                        echo $this->Html->link(__('Compose'), array('controller' => 'Mails', 'action' => 'compose'), array('class' => 'btn btn-success pll prl inbox'));
                                    }
                                    ?>
                                </div>
                            </li>
                            <li <?php if ($urlAction == "index") {
                                echo "class=\"active\"";
                            } ?>>
                                <?php echo $this->Html->link(__('Inbox') . "($totalInbox)", array('controller' => 'Mails', 'action' => 'index'), array('class' => 'btn btn-success pll prl')); ?>
                            </li>
                            <?php
                            if ($app != 'app') { ?>
                                <li <?php if ($urlAction == "sent") {
                                    echo "class=\"active\"";
                                } ?>>
                                    <?php echo $this->Html->link(__('Sent Mail'), array('controller' => 'Mails', 'action' => 'sent'), array('class' => 'btn-group dropup btn-block SentItem')); ?>
                                </li>
                                <li <?php if ($urlAction == "trash") {
                                    echo "class=\"active\"";
                                } ?>>
                                    <?php echo $this->Html->link(__('Trash'), array('controller' => 'Mails', 'action' => 'trash'), array('class' => 'btn-group dropup btn-block trash')); ?>
                                </li>
                            <?php }
                            ?>

                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div>
                    <div>
                        <div class="row">
                            <div class="col-lg-12">
                                <ul class="list-group mail-action list-unstyled list-inline">
                                    <li>
                                        <a class="btn btn-default"><?php echo $this->Form->checkbox('checkbox', array('value' => 'deleteall', 'name' => 'selectAll', 'label' => false, 'id' => 'selectAll', 'hiddenField' => false)); ?></a>
                                    </li>
                                    <?php if ($urlAction == "trash") { ?>
                                        <li><?php echo $this->Html->link('<i class="fa fa-trash"></i>', 'javascript:void(0);', array('name' => 'deleteallfrm', 'id' => 'deleteallfrmi', 'onclick' => 'check_perform_delete();', 'escape' => false, 'class' => 'btn btn-default', 'data-hover' => 'tooltip', 'data-original-title' => __('Delete Forever'))); ?></li>
                                    <?php } else { ?>
                                        <li><?php echo $this->Html->link('<i class="fa fa-trash"></i>', 'javascript:void(0);', array('name' => 'deleteallfrm', 'id' => 'deleteallfrm', 'onclick' => 'check_perform_trash();', 'escape' => false, 'class' => 'btn btn-default', 'data-hover' => 'tooltip', 'data-original-title' => __('Move to Trash'))); ?></li>
                                    <?php } ?>
                                    <?php if ($urlAction != "sent") { ?>
                                        <li class="dropdown"><a href="#" data-toggle="dropdown"
                                                                class="btn btn-default dropdown-toggle"><i
                                                    class="fa fa-folder"></i>&nbsp;<?php echo __('More'); ?> &nbsp;<span
                                                    class="caret"></span></a>
                                        <ul role="menu" class="dropdown-menu">
                                            <?php if ($urlAction == "index") { ?>
                                                <li><?php echo $this->Html->link(__('Mark as Read'), '#', array('onclick' => 'check_perform_more("read");')); ?></li>
                                                <li><?php echo $this->Html->link(__('Mark as Unread'), '#', array('onclick' => 'check_perform_more("unread");')); ?></li><?php } ?>
                                            <?php if ($urlAction == "trash") { ?>
                                                <li><?php echo $this->Html->link(__('Move to Inbox'), '#', array('onclick' => 'check_perform_more("inbox");')); ?></li><?php } ?>
                                        </ul>
                                        </li><?php } ?>
                                </ul>
                                <?php echo $this->Form->create(array('name' => 'deleteallfrm', 'url'=>array('action' => $deleteAction))); ?>
                                <div class="list-group mail-box">
                                    <?php foreach ($Mail as $post):
                                        $id = $post['Mail']['id']; ?>
                                        <a href="#" class="list-group-item">
                                            <div class="row">
                                                <div class="col-md-5 col-lg-4"><?php echo $this->Form->checkbox(false, array('value' => $post['Mail']['id'], 'name' => 'data[Mail][id][]', 'id' => "DeleteCheckbox$id", 'class' => 'chkselect')); ?>
                                                    <?php echo ($post['Mail']['type'] == "Unread") ? "<strong onclick=show_modal('$url/$id'); class=\"mail-from\">" : "<span onclick=show_modal('$url/$id'); class=\"mail-from\">"; ?><?php if ($post['Mail']['to_email'] == $mailType) {
                                                        echo h($post['Mail']['from_email']);
                                                    } else {
                                                        echo h($post['Mail']['to_email']);
                                                    } ?><?php echo ($post['Mail']['type'] == "Unread") ? "</strong>" : "</span>" ?></div>
                                                <div class="col-md-7 col-lg-8"
                                                     onclick=show_modal('<?php echo $url; ?>/<?php echo $id; ?>');><?php echo ($post['Mail']['type'] == "Unread") ? "<strong class=\"mail-title\">" : "<span class=\"mail-title\">"; ?>
                                                    <i><?php echo h($post['Mail']['subject']); ?></i><?php echo ($post['Mail']['type'] == "Unread") ? "</strong>" : "</span>" ?>
                                                    <span class="pull-right"><?php echo $this->Time->Format($sysDay . $dateSep . $sysMonth . $dateSep . $sysYear . $dateGap . $sysHour . $timeSep . $sysMin . $timeSep . $sysSec . $dateGap . $sysMer, $post['Mail']['date']); ?></span>
                                                </div>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                    <?php unset($post); ?>
                                </div>
                                <?php echo $this->Form->input('type', array('type' => 'hidden', 'name' => 'type')); ?>
                                <?php echo $this->Form->end(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function check_perform_trash() {
            document.deleteallfrm.submit();
        }

        function check_perform_more(type) {
            document.deleteallfrm.type.value = type;
            document.deleteallfrm.submit();
        }
    </script>
    <div class="modal fade" id="targetModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-content"></div>
    </div>
</div>