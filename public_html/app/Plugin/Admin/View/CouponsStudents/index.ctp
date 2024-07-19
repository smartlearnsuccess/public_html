<?php
$this->Js->JqueryEngine->jQueryObject = 'jQuery';
// Paginator options
$this->Paginator->options(array(
    'update' => '#resultDiv',
    'evalScripts' => true,
));
?>
<script type="text/javascript">
    $(document).ready(function () {
        $('#download').click(function () {
            $('#excel').val('Yes');
            $('#searchfrm').submit();
        })
        $('#searchbtn').click(function () {
            $('#excel').val('No');
            $('#searchfrm').submit();
        })
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#start_date').datetimepicker({
            locale: '<?php echo $configLanguage;?>',
            format: '<?php echo $dpFormat;?>'
        });
        $('#end_date').datetimepicker({
            locale: '<?php echo $configLanguage;?>', format: '<?php echo $dpFormat;?>', useCurrent: false //Important! See issue #1075
        });
        $("#start_date").on("dp.change", function (e) {
            $('#end_date').data("DateTimePicker").minDate(e.date);
        });
        $("#end_date").on("dp.change", function (e) {
            $('#start_date').data("DateTimePicker").maxDate(e.date);
        });
    });
</script>
<div id="resultDiv">
    <div class="page-title">
        <div class="title-env"><h1 class="title"><?php echo __('Used Coupons'); ?></h1></div>
    </div>
    <div class="panel">
        <?php echo $this->Session->flash(); ?>
        <div class="panel-heading">
            <?php echo $this->Form->create(array('name' => 'searchfrm', 'id' => 'searchfrm', 'url' => array('action' => "index", $couponId))); ?>
            <div class="row mrg">
                <div class="col-md-2">
                    <?php
                    echo $this->Form->input('name', array('class' => 'form-control', 'div' => false, 'placeholder' => 'Coupon Name', 'label' => false)); ?>
                </div>
				<div class="col-md-2">
					<?php
					echo $this->Form->select('mode',array('web'=>'WEB','app'=>'APP'), array('class' => 'form-control', 'div' => false,'empty'=>'All', 'label' => false)); ?>
				</div>
                <label for="group_name" class="col-sm-2 control-label">
                    <small>Used Date</small>
                </label>
                <div class="col-md-2">
                    <div class="input-group date" id="start_date">
                        <?php echo $this->Form->input('start_date', array('type' => 'text', 'label' => false, 'class' => 'form-control', 'div' => false)); ?>
                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="input-group date" id="end_date">
                        <?php echo $this->Form->input('end_date', array('type' => 'text', 'label' => false, 'class' => 'form-control', 'div' => false)); ?>
                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                    </div>
                </div>
				<div class="col-md-2">
					<button type="button" id="searchbtn" class="btn btn-sm btn-success"><span class="fa fa-search"></span>
						Search
					</button>
					<?php echo $this->Html->link('<span class="fa fa-refresh"></span>&nbsp;Reset', array('action' => 'index', $couponId), array('class' => 'btn btn-sm btn-warning', 'escape' => false)); ?>
				</div>
            </div>
            <?php echo $this->Form->hidden('excel', array('id' => 'excel')); ?>
            <?php echo $this->Form->end(); ?>
            <div class="btn-group">
                <?php echo $this->Html->link('<span class="fa fa-arrow-left"></span>&nbsp;' . __('Back to coupons'), array('controller' => 'Coupons', 'action' => 'index'), array('escape' => false, 'class' => 'btn btn-danger', 'class' => 'btn btn-info')); ?>
                <?php echo $this->Form->button('<span class="fa fa-file-excel-o"></span>&nbsp; ' . __('Export In Excel'), array('id' => 'download', 'class' => 'btn', 'escape' => false)); ?>
            </div>
        </div>
        <?php echo $this->element('pagination', array('IsSearch' => 'No'));
        $page_params = $this->Paginator->params();
        $limit = $page_params['limit'];
        $page = $page_params['page'];
        $serial_no = 1 * $limit * ($page - 1) + 1; ?>
        <?php echo $this->Form->create(array('name' => 'deleteallfrm', 'url' => array('action' => 'deleteall'))); ?>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <tr>
                        <th><?php echo $this->Paginator->sort('id', __('S.No.'), array('direction' => 'desc')); ?></th>
                        <th><?php echo $this->Paginator->sort('name', __('Coupon Name'), array('direction' => 'asc')); ?></th>
                        <th><?php echo $this->Paginator->sort('code', __('Coupon Code'), array('direction' => 'asc')); ?></th>
                        <th><?php echo $this->Paginator->sort('email', __('Student Name'), array('direction' => 'asc')); ?></th>
                        <th><?php echo $this->Paginator->sort('email', __('Student Email'), array('direction' => 'asc')); ?></th>
                        <th><?php echo $this->Paginator->sort('date', __('Used Date'), array('direction' => 'asc')); ?></th>
						<th><?php echo $this->Paginator->sort('payments_from', __('Mode'), array('direction' => 'asc')); ?></th>
                    </tr>
                    <?php foreach ($CouponsStudent as $post):
                        $id = $post['CouponsStudent']['id']; ?>
                        <tr>
                            <td><?php echo $serial_no++; ?></td>
                            <td><?php echo h($post['Coupon']['name']); ?></td>
                            <td><?php echo h($post['Coupon']['code']); ?></td>
                            <td><?php echo h($post['Student']['name']); ?></td>
                            <td><?php echo h($post['Student']['email']); ?></td>
                            <td><?php if ($post['CouponsStudent']['date'] != null) {
                                    echo $this->Time->format($dtmFormat, $post['CouponsStudent']['date']);
                                } ?></td>
							<td><?php echo h(strtoupper($post['CouponsStudent']['payments_from'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php unset($post); ?>
                </table>
            </div>
            <?php echo $this->Form->input('couponId', array('type' => 'hidden', 'name' => 'couponId', 'value' => $couponId)); ?>
            <?php echo $this->Form->end(); ?>
            <?php echo $this->element('pagination', array('IsSearch' => 'No')); ?>
        </div>
    </div>
</div>
