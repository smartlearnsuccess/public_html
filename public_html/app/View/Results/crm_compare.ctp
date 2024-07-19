<style type="text/css">
    tr.mytr {
        float: left;
        width: 25%;
        border-top: 1px solid;
    }

    @media only screen and (max-width: 900px) {
        tr.mytr {
            float: none;
            width: 50%;
            border-top: 1px solid;
        }
    }

    @media only screen and (max-width: 600px) {
        tr.mytr {
            float: none;
            width: 100%;
            border-top: 1px solid;
        }
    }

    td {
        border: none !important;
    }

    @media screen and (max-width: 600px) {
        .row.my-result > div > ul > li {
            width: 140px;
        }

        .right {
            float: right;
        }

        .center {
            text-align: left;
        }

        table#cart tbody td .form-control {
            width: 20%;
            display: inline !important;
        }

        .actions .btn {
            width: 36%;
            margin: 1.5em 0;
        }

        .actions .btn-info {
            float: left;
        }

        .actions .btn-danger {
            float: right;
        }

        table#cart thead {
            display: none;
        }

        table#cart tbody td {
            display: block;
            padding: .6rem;
            min-width: 320px;
        }

        /* table#cart tbody tr td:first-child { background: #333; color: #fff; }*/
        table#cart tbody td:before {
            content: attr(data-th);
            font-weight: bold;
            display: inline-block;
            /* width: 8rem;*/
        }

        table#cart tfoot td {
            display: block;
        }

        table#cart tfoot td .btn {
            display: block;
        }
    }

    .page-header-topbar {
        display: none;
    }

    .page-content {
        margin: 0;
    }

    .sidebar-main.sidebar {
        display: none;
    }

    div#footer {
        display: none;
    }

    .row.my-result {
        padding-top: 0px !important;
        margin: 0;
    }

    .content {
        padding: 0px !important;
    }

    .exam-panel {
        overflow-y: hidden !important;
    }

    button.btn {
        margin-bottom: 10px;
    }

    table#cart tbody td {
        min-width: 0px !important;
    }

    .optionSerial {
        float: left;
        margin-top: 2px;
    }

    i.material-icons..clear {
        color: red;
    }

    i.material-icons.check {
        color: #26ce26;
    }

    .tab-content {
        color: #191919;
    }

    .me {
        background: #08bbe2;
        padding: 5px;
        color: #fff
    }

    img.img-thumbnail {
        border-radius: 100%;
        padding: 2px;
    }

    .meheding.comp {
        width: 270px;
        float: right;
        padding: 0px;
    }

    button.btn.btn-default.btn-sm.btn-block {
        font-size: 18px;
    }

    .myimg {
        float: left;
    }

    .meheding {
        float: left;
        padding: 16px;
    }

    .meheding > h3, h4 {
        padding: 0;
        margin: 0;
    }

    .myrank {
        float: right;
        padding: 15px;
    }

    .myrank.comp {
        padding: 0;
    }

    .rank {
        color: #fff;
    }

    h4.panel-title {
        padding: 20px;
    }

    i.material-icons.navigate_next {
        font-size: 35px;
    }

    i.material-icons.navigate_before {
        font-size: 35px;
    }

    .navigateNext {
        background: #FF4081;
        height: 58px;
        color: #fff;
        width: 60px;
        border: none;
        font-size: 39px !important;
        border-radius: 100%;
    }

    .navigatePrev {
        background: #FF4081;
        height: 58px;
        color: #fff;
        width: 60px;
        border: none;
        font-size: 39px !important;
        border-radius: 100%;
    }

    .rank.comp {
        margin-top: 5px;
        font-size: 10pt;
    }
</style>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<script type="text/javascript">
    function callComparePrev(rank) {
        rank--;
        $('.compare').hide();
        $('#comppanel' + rank).show(0, 'linear');
    }

    function callCompareNext(rank) {
        rank++;
        $('.compare').hide();
        $('#comppanel' + rank).show(0, 'linear');
    }

    $(document).ready(function () {
        $('.exam-panel').hide();
        $('#quespanel1').show();
        $('.compare').hide();
        $('#comppanel0').show();
    });

</script>
<style type="text/css">
    .col-md-12 {
        padding: 5px;
    }

    /* bootstrap hack: fix content width inside hidden tabs */
    .tab-content > .tab-pane, .pill-content > .pill-pane {
        display: block; /* undo display:none          */
        height: 0; /* height:0 is also invisible */
        overflow-y: hidden; /* no-overflow                */
    }

    .tab-content > .active, .pill-content > .active {
        height: auto; /* let the content decide it  */
    }

    /* bootstrap hack end */
</style>
<div class="row my-result">
    <div class="col-md-12" style="padding: 0;">
            <div class="" id="compare-report">
            <div class="com-md-12 col-sm-12 col-xs-12">
                <div style="margin-top: 10px;box-shadow: 0px 2px 5px #888888;">

                    <div class="me">
                        <div class="myimg">
                            <?php echo $this->Html->image($studentImage, array('width' => 60, 'height' => 70, 'class' => 'img-thumbnail')); ?>
                        </div>
                        <div class="meheding">
                            <h3>Me</h3>
                        </div>
                        <div class="myrank">
                            <div class="rank">
                                <span style="font-size: 15px;">My Rank: </span>
                                <?php echo $myRank; ?>
                            </div>
                        </div>
                        <div style="clear: both;"></div>
                    </div>
                    <div class="col-md-3">
                        <div class="">
                            <table class="table">
                                <tr>
                                    <td><?php echo __('Total Ques.'); ?></td>
                                    <td><strong><?php echo $examDetails['Result']['total_question']; ?></strong></td>
                                </tr>
                                <tr>
                                    <td><?php echo __('Maximum Marks'); ?></td>
                                    <td><strong><?php echo $examDetails['Result']['total_marks']; ?></strong></td>
                                </tr>
                                <tr>
                                    <td><?php echo __('Attempted Ques.'); ?></td>
                                    <td><strong><?php echo $attemptedQuestion; ?></strong></td>
                                </tr>
                                <tr>
                                    <td><?php echo __('Unattempted Ques.'); ?></td>
                                    <td><strong><?php echo $leftQuestion; ?></strong></td>
                                </tr>
                                <tr>
                                    <td><?php echo __('Correct Ques.'); ?></td>
                                    <td><strong><?php echo $correctQuestion; ?></strong></td>
                                </tr>
                                <tr>
                                    <td><?php echo __('Incorrect Ques.'); ?></td>
                                    <td><strong><?php echo $incorrectQuestion; ?></strong></td>
                                </tr>

                                <tr>
                                    <td><?php echo __('Total Score'); ?></td>
                                    <td><strong class=""><?php echo $examDetails['Result']['obtained_marks']; ?>
                                            /<?php echo $examDetails['Result']['total_marks']; ?></strong></td>
                                </tr>
                                <tr>
                                    <td><?php echo __('Percentage'); ?></td>
                                    <td>
                                        <strong><?php echo $this->Number->toPercentage($examDetails['Result']['percent']); ?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo __('Percentile'); ?></td>
                                    <td><strong><?php echo CakeNumber::toPercentage($percentile); ?></strong></td>
                                </tr>
                                <tr>
                                    <td><?php echo __('Test Time'); ?></td>
                                    <td>
                                        <strong><?php echo $this->Function->secondsToWords($this->Time->fromString($examDetails['Result']['test_time'])); ?></strong>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <?php foreach ($compareArr as $k => $compPost): ?>
                    <div id="comppanel<?php echo $k; ?>" class="compare">
                        <div style="margin-top: 10px;box-shadow: 0px 2px 5px #888888;">
                            <div class="me comp">
                                <div class="myimg">
                                    <?php echo $this->Html->image($compPost['student_image'], array('width' => 60, 'height' => 70, 'class' => 'img-thumbnail')); ?>
                                </div>
                                <div class="meheding comp">
                                    <h5><?php echo $compPost[0]['Student']['name']; ?></h5>
                                </div>
                                <div class="myrank comp">
                                    <div class="rank comp">
										<span style="font-size: 15px;">Rank: </span>
                                        <?php echo $compPost['rank']; ?>
                                    </div>
                                </div>
                                <div style="clear: both;"></div>
                            </div>
                            <div class="col-md-3">
                                <div class="">
                                    <table class="table">
                                        <tr>
                                            <td><?php echo __('Total Ques.'); ?></td>
                                            <td><strong><?php echo $compPost[0]['Result']['total_question']; ?></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?php echo __('Maximum Marks'); ?></td>
                                            <td><strong><?php echo $compPost[0]['Result']['total_marks']; ?></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?php echo __('Attempted Ques.'); ?></td>
                                            <td>
                                                <strong class="text-success"><?php echo $compPost['attempted_question']; ?></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?php echo __('Unattempted Ques.'); ?></td>
                                            <td>
                                                <strong class="text-danger"><?php echo $compPost['left_question']; ?></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?php echo __('Correct Ques.'); ?></td>
                                            <td>
                                                <strong class="text-success"><?php echo $compPost['correct_question']; ?></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?php echo __('Incorrect Ques.'); ?></td>
                                            <td>
                                                <strong class="text-danger"><?php echo $compPost['incorrect_question']; ?></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?php echo __('Total Score'); ?></td>
                                            <td><strong class=""><?php echo $compPost[0]['Result']['obtained_marks']; ?>
                                                    /<?php echo $compPost[0]['Result']['total_marks']; ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td><?php echo __('Percentage'); ?></td>
                                            <td>
                                                <strong><?php echo $this->Number->toPercentage($compPost[0]['Result']['percent']); ?></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?php echo __('Percentile'); ?></td>
                                            <td>
                                                <strong><?php echo CakeNumber::toPercentage($compPost['percentile']); ?></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?php echo __('Test Time'); ?></td>
                                            <td>
                                                <strong><?php echo $this->Function->secondsToWords($this->Time->fromString($compPost[0]['Result']['test_time'])); ?></strong>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div style="position: fixed;bottom: 0;right: 0;">
                                    <div class="col-sm-2" style="position: fixed;bottom: 0;right: 0px;">
                                        <?php if ($k < $compareCount) {
                                            echo $this->Form->button(__('<i class="material-icons navigate_next">navigate_next</i>'), array('onClick' => "callCompareNext($k);", 'class' => 'btn btn-default btn-sm btn-block navigateNext'));
                                        } ?>
                                    </div>
                                    <div class="col-sm-2" style="position: fixed;bottom: 0;right: 70px;">
                                        <?php if ($k != 0) {
                                            echo $this->Form->button(__('<i class="material-icons navigate_before">navigate_before</i>'), array('onClick' => "callComparePrev($k);", 'class' => 'btn btn-default btn-sm btn-block navigatePrev '));
                                        } ?>
                                    </div>
                                </div>
                                <div style="padding: 20px;"></div>
                            </div>

                        </div>
                    </div>
                <?php endforeach;
                unset($compPost); ?>
                <div style="display: none;"><label id="totalRank"><?php echo $compareCount; ?></label></div>

            </div>

        </div>
    </div>
</div>
