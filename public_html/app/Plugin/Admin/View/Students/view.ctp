<div class="container">
    <?php $passUrl = $this->Html->url(array('controller' => 'Students', 'action' => 'changepass', $id));
    $photoUrl = $this->Html->url(array('controller' => 'Students', 'action' => 'changephoto', $id));
    echo $this->Session->flash(); ?>
    <div class="panel panel-custom mrg">
        <div class="panel-heading"><?php echo __('View Student Information'); ?>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="panel-body">
            <div class="col-md-2 text-center">
                <p>
                <div
                        class="img-thumbnail"><?php echo $this->Html->image($std_img, array('alt' => $post['Student']['name'])); ?></div>
                </p>
                <?php echo $this->Html->link(__('Update Photo'), 'javascript:void(0);', array('onclick' => "show_modal('$photoUrl');", 'class' => 'btn btn-success btn-sm btn-block', 'escape' => false)); ?>
                <?php echo $this->Html->link(__('Change Password'), 'javascript:void(0);', array('onclick' => "show_modal('$passUrl');", 'class' => 'btn btn-danger btn-sm btn-block', 'escape' => false)); ?>
            </div>
            <div class="col-md-10">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <tr class="text-primary">
                            <td><strong>
                                    <small><?php echo __('Full Name'); ?></small>
                                </strong></td>
                            <td><strong>
                                    <small><?php echo __('Phone Number'); ?></small>
                                </strong></td>
                        </tr>
                        <tr>
                            <td><strong>
                                    <small><?php echo h($post['Student']['name']); ?></small>
                                </strong></td>
                            <td><strong>
                                    <small><?php echo h($post['Student']['phone']); ?></small>
                                </strong></td>
                        </tr>
                        <tr class="text-primary">
                            <td><strong>
                                    <small><?php echo __('Registered Email'); ?></small>
                                </strong></td>
                            <td><strong>
                                    <small><?php echo __('Alternate Number'); ?></small>
                                </strong></td>
                        </tr>
                        <tr>
                            <td><strong>
                                    <small><?php echo h($post['Student']['email']); ?></small>
                                </strong></td>
                            <td><strong>
                                    <small><?php echo h($post['Student']['guardian_phone']); ?></small>
                                </strong></td>
                        </tr>
                        </tr>
                        <tr class="text-primary">
                            <td><strong>
                                    <small><?php echo __('Enrolment Number'); ?>.<strong>
                                            <small></td>
                            <td><strong>
                                    <small><?php echo __('Admission Date'); ?></small>
                                </strong></td>
                        </tr>
                        <tr>
                            <td><strong>
                                    <small><?php echo h($post['Student']['enroll']); ?></small>
                                </strong></td>
                            <td><strong>
                                    <small><?php echo $this->Time->format($sysDay . $dateSep . $sysMonth . $dateSep . $sysYear, $post['Student']['created']); ?></small>
                                </strong></td>
                        </tr>
                        <tr class="text-primary">
                            <td><strong>
                                    <small><?php echo __('Groups'); ?> <strong>
                                            <small></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <small><strong>
                                        <?php foreach ($post['Group'] as $k => $groupName): ?>
                                        (<?php echo ++$k; ?>) <?php echo $groupName['group_name']; ?>
                                    <?php endforeach;
                                    unset($groupName);
                                    unset($k); ?></small>
                                </strong></td>
                        </tr>
                        <tr class="text-primary">
                            <td><strong>
                                    <small><?php echo __('Expiry Days'); ?><strong>
                                            <small></td>
                            <td><strong>
                                    <small><?php echo __('Last Login'); ?><strong>
                                            <small></td>

                        </tr>
                        <tr>
                            <td>
                                <small>
                                    <strong><?php if ($post['Student']['expiry_days'] == 0) echo __('Unlimited'); else echo $post['Student']['expiry_days'] . ' ', __('Days'); ?>
                                </small>
                                </strong></td>
                            <td>
                                <small>
                                    <strong><?php if ($post['Student']['last_login'] != null) echo $this->Time->format($sysDay . $dateSep . $sysMonth . $dateSep . $sysYear . $dateGap . $sysHour . $timeSep . $sysMin . $timeSep . $sysSec . $dateGap . $sysMer, $post['Student']['last_login']); ?>
                                </small>
                                </strong></td>

                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="panel-heading"><?php echo __('Package Details'); ?></div>
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th><span class="text-warning"><?php echo __('S.No.'); ?></span></th>
                        <th><span class="text-warning"><?php echo __('Package Name'); ?></span></th>
                        <th><span class="text-warning"><?php echo __('Exams'); ?></span></th>
                        <th><span class="text-warning"><?php echo __('Price'); ?></span></th>
                        <th><span class="text-warning"><?php echo __('Quantity'); ?></span></th>
                        <th><span class="text-warning"><?php echo __('Amount'); ?></span></th>
                        <th><span class="text-warning"><?php echo __('Coupon Discount'); ?></span></th>
                        <th><span class="text-warning"><?php echo __('Net Amount'); ?></span></th>
                        <th><span class="text-warning"><?php echo __('Date'); ?></span></th>
                        <th><span class="text-warning"><?php echo __('Expiry Date'); ?></span></th>
                    </tr>
                    <?php
                    $sn = 0;
                    $totalPrice = 0;
                    $totalQty = 0;
                    $totalAmount = 0;
                    $totalCouponAmount = 0;
                    $totalNetAmount = 0;
                    foreach ($postArr as $postArr1):
                        foreach ($postArr1['Package'] as $post):
                            $sn++;
                            $totalPrice = $post['PackagesPayment']['price'] + $totalPrice;
                            $totalQty = $post['PackagesPayment']['quantity'] + $totalQty;
                            $totalAmount = $post['PackagesPayment']['amount'] + $totalAmount;
                            $totalPackage = count($postArr1['Package']);
                            $couponAmount = $postArr1['Payment']['coupon_amount'] / $totalPackage;
                            $totalCouponAmount = $totalCouponAmount + $couponAmount;
                            $netAmount = ($post['PackagesPayment']['amount'] - $couponAmount);
                            $totalNetAmount = $totalNetAmount + $netAmount;
                            ?>
                            <tr>
                                <td><strong><?php echo $sn; ?></strong></td>
                                <td><strong><?php echo h($post['name']); ?></strong></td>
                                <td><strong><?php echo $this->Function->showPackageName($post['Exam']); ?></strong></td>
                                <td><strong><?php echo $currency . $post['PackagesPayment']['price']; ?></strong></td>
                                <td><strong><?php echo $post['PackagesPayment']['quantity']; ?></strong></td>
                                <td><strong><?php echo $currency . $post['PackagesPayment']['amount']; ?></strong></td>
                                <td><strong><?php echo $currency . $couponAmount; ?></strong></td>
                                <td><strong><?php echo $currency . $netAmount; ?></strong></td>
                                <td>
                                    <strong><?php echo $this->Time->format($dtFormat, $post['PackagesPayment']['date']); ?></strong>
                                </td>
                                <td><strong><?php if ($post['PackagesPayment']['expiry_date']) {
                                            echo $this->Time->format($dtFormat, $post['PackagesPayment']['expiry_date']);
                                        } else {
                                            echo __('Unlimited');
                                        } ?></strong></td>
                            </tr>
                        <?php endforeach;
                    endforeach;
                    unset($post);
                    unset($postArr1); ?>
                    <tr>
                        <td colspan="8">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="3" align="right"><span class="text-warning"><strong>Total : </strong></span>
                        </td>
                        <td><span
                                    class="text-warning"><strong><?php echo $currency . $totalPrice; ?></strong></span>
                        </td>
                        <td><span class="text-warning"><strong><?php echo $totalQty; ?></strong></span></td>
                        <td><span
                                    class="text-warning"><strong><?php echo $currency . $totalAmount; ?></strong></span>
                        </td>
                        <td><span
                                    class="text-warning"><strong><?php echo $currency . $totalCouponAmount; ?></strong></span>
                        </td>
                        <td><span
                                    class="text-warning"><strong><?php echo $currency . $totalNetAmount; ?></strong></span>
                        </td>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                </table>
            </div>

        </div>
    </div>
</div>