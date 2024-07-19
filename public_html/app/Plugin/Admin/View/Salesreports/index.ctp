<div class="page-title">
    <div class="title-env"><h1 class="title"><?php echo __('Sales Report'); ?></h1></div>
</div>
<div class="panel">
    <div class="panel-body">
        <?php if ($isSearch == false) { ?>
            <div class="row mrg">
                <div class="col-sm-offset-2 col-md-3">
                    <div>
                        <button class="btn btn-primary" type="button"><?php echo __('Total Sales Count this month'); ?>
                            <span class="badge"><?php echo $this->Number->format($monthSalesCount); ?></span>
                            <br/><br/><?php echo __('Total Earning this month'); ?> <span
                                    class="badge"><?php echo $currency . $this->Number->format($earningMonth); ?></span>
                        </button>
                    </div>
                </div>
                <div class="col-sm-offset-1 col-md-3">
                    <div>
                        <button class="btn btn-primary" type="button"><?php echo __('Total Sales Count'); ?> <span
                                    class="badge"><?php echo $totalSalesCount; ?></span>
                            <br/><br/><?php echo __('Total Earning'); ?> <span
                                    class="badge"><?php echo $currency . $this->Number->format($totalEearning); ?></span>
                        </button>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php echo $this->Form->create(array('name' => 'searchfrm', 'url' => array('action' => 'index'), 'type' => 'post')); ?>
        <div class="row mrg">
            <div class="col-md-3">
                <?php echo $this->Form->input('package_id', array('options' => $packageId, 'empty' => 'All', 'label' => false, 'class' => 'form-control', 'div' => false)); ?>
            </div>
            <div class="col-md-3">
                <?php echo $this->Form->input('date', array('type' => 'date', 'dateFormat' => 'Y', 'minYear' => 2017, 'maxYear' => date('Y') + 5, 'empty' => 'All', 'label' => false, 'class' => 'form-control', 'div' => false)); ?>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-success"><span
                            class="fa fa-search"></span> <?php echo __('Search'); ?></button>
                <?php echo $this->Html->link('<span class="fa fa-refresh"></span>&nbsp;' . __('Reset'), array('controller' => 'Salesreports', 'action' => 'index'), array('class' => 'btn btn-warning', 'escape' => false)); ?>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
        <div class="chart">
            <div id="mywrapperdl"></div>
            <?php echo $this->HighCharts->render("My Chartdl"); ?>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <tr>
                    <th><?php echo __('Month'); ?></th>
                    <th><?php echo __('Sales Count'); ?></th>
                    <th><?php echo __('Earning'); ?></th>
                </tr>
                <?php $totCount = 0;
                $totEarning = 0;
                foreach ($salesReport as $post):
                    $totCount = $post['MonthArr']['salesCount'] + $totCount;
                    $totEarning = $post['MonthArr']['earning'] + $totEarning; ?>
                    <tr>
                        <td><?php echo $post['MonthArr']['monthName']; ?></td>
                        <td><?php echo $post['MonthArr']['salesCount']; ?></td>
                        <td><?php echo $currency . $this->Number->format($post['MonthArr']['earning']); ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php unset($post); ?>
                <tr>
                    <td><strong>Total</strong></td>
                    <td><strong><?php echo $totCount; ?></strong></td>
                    <td><strong><?php echo $currency . $this->Number->format($totEarning); ?></strong></td>
                </tr>
            </table>
        </div>
    </div>
</div>