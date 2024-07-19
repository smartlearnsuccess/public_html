<div class="container">
    <div class="row">
        <div class="col-md-12 mrg">
            <div class="panel panel-custom">
                <div class="panel-heading panel-heading-custom"><?php echo __('Shopping Cart'); ?>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <?php echo $this->Form->create('Cart', array('url' => array('action' => 'update'))); ?>
                <div class="table-responsive">
                    <table class="table" width="100%">
                        <tr>
                            <th width="14%" scope="col">
                                <div align="left"><?php echo __('Package'); ?></div>
                            </th>
                            <th width="50%" scope="col">&nbsp;</th>
                            <th width="21%" scope="col">
                                <div align="center"><?php echo __('Quantity'); ?></div>
                            </th>
                            <th width="15%" scope="col">
                                <div align="right"> <?php echo __('Total Amount'); ?></div>
                            </th>
                        </tr>
                        <?php $total = 0;
                        $totalQuantity = 0; ?>
                        <?php foreach ($products as $product):
                            if (strlen($product['Package']['photo']) > 0) {
                                $photo = "package_thumb/" . $product['Package']['photo'];
                            } else {
                                $photo = "nia.png";
                            } ?>
                            <tr>
                                <td rowspan="2"><?php echo $this->Html->image($photo, array('alt' => h($product['Package']['name']), 'class' => 'img-thumbnail img-package', 'width' => '100', 'height' => '100')); ?></td>
                                <td>
                                    <div align="left"
                                         style="text-align:justify;"><?php echo h($product['Package']['name']); ?>
                                        <br><?php echo $product['Package']['description']; ?></div>
                                </td>
                                <td>
                                    <div
                                            align="center"><?php echo $this->Form->hidden('package_id.', array('value' => $product['Package']['id'])); ?>
                                        <?php //echo $this->Form->input('count.',array('type'=>'number', 'label'=>false,'class'=>'form-control input-sm', 'value'=>$product['Package']['count']));
                                        echo $product['Package']['count']; ?></div>
                                </td>
                                <td>
                                    <div align="right"><strong
                                                style="font-size:17px;"><?php if ($product['Package']['show_amount'] != $product['Package']['amount']) { ?>
                                                <strike><span
                                                        style="font-weight: normal;"> <?php echo $currency . $product['Package']['show_amount']; ?></span>
                                                </strike><?php } ?> <?php echo $currency . $product['Package']['count'] * $product['Package']['amount']; ?>
                                        </strong>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $this->html->link('<span class="fa fa-trash-o"></span>&nbsp;', array('controller' => 'Carts', 'action' => 'delete', $product['Package']['id']), array('escape' => false)); ?></td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <?php $total = $total + ($product['Package']['count'] * $product['Package']['amount']);
                            $totalQuantity = $totalQuantity + $product['Package']['count']; ?>
                        <?php endforeach; ?>
                    </table>
                </div>

                <?php echo $this->Form->end(); ?>
                <?php if ($total == 0) {
                    echo $this->Html->link(__('Proceed to Free Checkout'), array('controller' => 'Checkouts'), array('class' => 'btn btn-success'));
                } else {
                    echo $this->Html->link(__('Proceed to Payment Method'), array('controller' => 'Checkouts'), array('class' => 'btn btn-success'));
                } ?>
            </div>
        </div>
    </div>
</div>