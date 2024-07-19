<?php //$cartUrl = $this->Html->url(array('controller' => 'Carts', 'action' => 'View')); ?>
<?php

$this->Js->JqueryEngine->jQueryObject = 'jQuery';
// Paginator options
$this->Paginator->options(
	array(
		'update' => '#resultDiv',
		'evalScripts' => true,
	)
);
?>
<style type="text/css">
.loading {
    position: absolute;
    left: 0;
    right: 0;
    margin: 0 auto;
}

.hidden {
    display: none;




}



.event-img img {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    min-width: 50px;
    min-height: 50px;
}

.img-responsive-table {
    width: 15px;
    height: 15px;
    border-radius: 2px;
    min-width: 15px;
    min-height: 15px;
}

.event-wrap>h3>a {
    font-size: 1.4rem;
}
</style>
<section class="section">
    <?php echo $this->Html->image("loading-lg.gif", array('alt' => 'subscribe', 'class' => 'loading hidden', 'id' => 'loading')); ?>
    <div class="container mycontainer">
        <div id="resultDiv">
            <div class="page-heading" style="padding-top:100px;">
                <div class="widget">
                    <h2 class="title-border"><?php echo __('Schedules'); ?></h2>
                </div>
            </div>
            <div id="Schedules" class="row list-group">
                <div class="schedule-div">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="select-pkg">

                                <?php echo $this->Form->select('Package_id', $Package_id, array('empty' => array('' => 'Please Select'), 'multiple' => false, 'class' => 'form-control', 'id' => 'packageid', 'div' => false, 'label' => false)); ?>
                            </div>
                            <div class="pkg-name d-flex flex-column gap-1" id="pkgdetail">
                                <p><b>Package :</b> <span
                                        id="pac_name"><?php //echo $Packages['Package']['name']; ?></span>
                                </p>
                                <p><b>Cost :</b> <span
                                        id="pac_amount"><?php //echo $Packages['Package']['amount']; ?></span></p>
                                <p><b>Total Tests :</b> <span id="pac_tests"><?php //echo $Examscount; ?></span></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="pkg-des">
                                <p id="pac_desc"><?php //echo $Packages['Package']['description']; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>




                    <div class="event-schedule-area-two bg-color pad100">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="section-title text-center">
                                        <div class="title-text">
                                            <h3> Results</h3>
                                        </div>

                                    </div>
                                </div>
                                <!-- /.col end-->
                            </div>
                            <!-- row end-->
                            <div class="row">
                                <div class="col-lg-12">

                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade active show" id="home" role="tabpanel">
                                            <div class="table-responsive" id="list_div">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">
                                                                <?php echo $this->Html->image("schedule_test.png", array('alt' => 'subscribe', 'class' => 'img-responsive-table')); ?>
                                                            </th>
                                                            <th scope="col"><?php echo __('Test Name'); ?></th>
                                                            <th scope="col"><?php echo __('Syllabus'); ?></th>
                                                            <th scope="col">
                                                                <?php echo $this->Html->image("Calender.png", array('alt' => 'subscribe', 'class' => 'img-responsive-table')); ?>
                                                                <?php echo __('Starts from date'); ?>
                                                            </th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
														foreach ($Exams as $key => $Exam) { //echo "<pre>"; print_r($Exam); ?>
                                                        <tr class="inner-box" id="<?php echo $key; ?>">

                                                            <td>
                                                                <?php echo ($key + 1); ?>
                                                            </td>
                                                            <td>
                                                                <div class="event-wrap">
                                                                    <h3><a
                                                                            href="#"><?php echo $Exam['Exam']['name'] ?></a>
                                                                    </h3>

                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="r-no">
                                                                    <span><?php echo $Exam['Exam']['syllabus'] ?></span>
                                                                </div>
                                                            </td>

                                                            <th scope="row">
                                                                <div class="event-date">
                                                                    <span><?php echo $this->Time->format('jS F', $Exam['Exam']['start_date']); ?></span>
                                                                    <p><?php echo $this->Time->format('Y', $Exam['Exam']['start_date']); ?>
                                                                    </p>
                                                                </div>
                                                            </th>
                                                        </tr>
                                                        <?php } ?>



                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /col end-->
                            </div>
                            <!-- /row end-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
<?php echo $this->Html->script('/app/webroot/design300/js/jquery.min', array()); ?>
<script>
$('#packageid').on('change', function() {
    $('#loading').removeClass('hidden');
    var value = $(this).val();
    var datat
    $.ajax({
        url: "<?php echo $this->Html->url(array('controller' => 'Schedules', 'action' => 'exam_by_package_id')); ?>",
        type: "POST",
        data: {
            package_id: value
        },
        success: function(data) {
            $("#list_div").html(data);
            $('#loading').addClass('hidden');
        }
    });
    myget_book(value)
});

function myget_book(param) {
    var data
    $.ajax({
        url: "<?php echo $this->Html->url(array('controller' => 'Schedules', 'action' => 'get_package_by_id')); ?>",
        type: "POST",
        data: {
            package_id: param
        },
        success: function(data) {
            var array = data.split(',|');
            $("#pac_name").html(array[0]);;
            $("#pac_amount").html(array[1]);;
            $("#pac_tests").html(array[2]);
            $("#pac_desc").html(array[3]);
        }
    });


}
</script>
