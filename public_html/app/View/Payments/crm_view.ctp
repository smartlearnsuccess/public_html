<div class="container">
    <div class="row">
        <div class="col-md-12 mrg">
            <div class="panel panel-default">
                <div class="panel-heading"><?php echo __('Payment Details'); ?>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th><span class="text-warning"><?php echo __('S.No.'); ?></span></th>
                            <th><span class="text-warning"><?php echo __('Package Name'); ?></span></th>
                            <th><span class="text-warning"><?php echo __('Exams'); ?></span></th>
                            <th><span class="text-warning"><?php echo __('Price'); ?></span></th>
                            <th><span class="text-warning"><?php echo __('Quantity'); ?></span></th>
                            <th><span class="text-warning"><?php echo __('Amount'); ?></span></th>
                            <th><span class="text-warning"><?php echo __('Date'); ?></span></th>
                            <th><span class="text-warning"><?php echo __('Expiry Date'); ?></span></th>
                        </tr>
                        <?php
                        $sn = 0;
                        $totalPrice = 0;
                        $totalQty = 0;
                        $totalAmount = 0;
                        foreach ($postArr['Package'] as $post):
                            $sn++;
                            $totalPrice = $post['PackagesPayment']['price'] + $totalPrice;
                            $totalQty = $post['PackagesPayment']['quantity'] + $totalQty;
                            $totalAmount = $post['PackagesPayment']['amount'] + $totalAmount;
                            ?>
                            <tr>
                                <td><strong><?php echo $sn; ?></strong></td>
                                <td><strong><?php echo h($post['name']); ?></strong></td>
                                <td><strong><?php echo $this->Function->showPackageName($post['Exam']); ?></strong></td>
                                <td><strong><?php echo $currency . $post['PackagesPayment']['price']; ?></strong></td>
                                <td><strong><?php echo $post['PackagesPayment']['quantity']; ?></strong></td>
                                <td><strong><?php echo $currency . $post['PackagesPayment']['amount']; ?></strong></td>
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
                        unset($post); ?>
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
                            <td colspan="2">&nbsp;</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>