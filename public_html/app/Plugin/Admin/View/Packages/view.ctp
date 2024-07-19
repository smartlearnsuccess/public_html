<div class="container">
    <?php $passUrl = $this->Html->url(array('controller' => 'Packages', 'action' => 'changepass', $id));
    $photoUrl = $this->Html->url(array('controller' => 'Packages', 'action' => 'changephoto', $id));
    echo $this->Session->flash(); ?>
    <div class="panel panel-custom mrg">
        <div class="panel-heading"><?php echo __('View Package Information'); ?>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="panel-body">
            <div class="col-md-3 text-center">
                <p>
                <div
                        class="img-thumbnail"><?php echo $this->Html->image($viewImg, array('alt' => $post['Package']['name'])); ?></div>
                </p>
            </div>

            <div class="col-md-9">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <tr>
                            <td colspan="2" align="left" class="text-primary"><strong>
                                    <small><?php echo __('Type'); ?>:</small>
                                </strong><strong>
                                    <small><?php echo $packageTypeArr[$post['Package']['package_type']]; ?></small>
                                </strong></td>
                        </tr>
                        <tr>
                            <td colspan="2" align="left" class="text-primary"><strong>
                                    <small><?php echo __('Package Name'); ?>:</small>
                                </strong><strong>
                                    <small><?php echo h($post['Package']['name']); ?></small>
                                </strong></td>
                        </tr>
                        <?php if ($post['Package']['show_amount'] != NULL) { ?>
                            <tr>
                                <td colspan="2" align="left" class="text-primary"><strong>
                                        <small><?php echo __('Amount'); ?>:</small>
                                    </strong><strong>
                                        <small><u><?php echo $currency . $post['Package']['show_amount']; ?></u></small>
                                    </strong></td>
                            </tr>
                        <?php } ?>
                        <?php if ($post['Package']['amount'] != NULL) { ?>
                            <tr>
                                <td colspan="2" align="left" class="text-primary"><strong>
                                        <small><?php echo __('Discounted Amount'); ?>:</small>
                                    </strong><strong>
                                        <small><?php echo $currency . $post['Package']['amount']; ?></small>
                                    </strong></td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="2" align="left" class="text-primary"><strong>
                                    <small><?php echo __('Expiry Days'); ?>:</small>
                                </strong><strong>
                                    <small><?php if ($post['Package']['expiry_days'] == 0) echo __('Unlimited'); else echo $post['Package']['expiry_days'];
                                        echo ' ' . __('Days'); ?></small>
                                </strong></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-primary"><strong>
                                    <small><?php echo __('Status'); ?></small>
                                </strong>: <strong>
                                    <small><span
                                                class="label label-<?php if ($post['Package']['status'] == "Active") echo "success"; else echo "danger"; ?>"><?php echo $post['Package']['status']; ?></span>
                                    </small>
                                </strong></td>
                        </tr>
                        </tr>
                        <tr>
                            <td colspan="3" align="left" class="text-primary"><strong>
                                    <small><?php echo __('Exams'); ?>:</small>
                                </strong><strong>
                                    <small><?php echo $this->Function->showPackageName($post['Exam']); ?></small>
                                </strong></td>
                        </tr>
                        <tr>
                            <td colspan="2" align="left" class="text-primary"><strong>
                                    <small><?php echo __('Description'); ?>:</small>
                                </strong><strong>
                                    <small><?php echo $this->Function->cleanContent($post['Package']['description']); ?></small>
                                </strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>