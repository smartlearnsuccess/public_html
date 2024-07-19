<div class="container">
    <?php echo $this->Session->flash(); ?>
    <div class="panel panel-custom mrg">
        <div class="panel-heading"><?php echo __('Exam Details'); ?>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-5 col-sm-12 col-xs-12">
                    <div class="chart">
                        <div id="piewrapperqc"></div>
                        <?php echo $this->HighCharts->render("Pie Chartqc"); ?>
                    </div>
                </div>
                <div class="col-md-7 col-sm-12 col-xs-12">
                    <div class="chart">
                        <div id="mywrapperdl"></div>
                        <?php echo $this->HighCharts->render("My Chartdl"); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>