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
                    <h3><a href="#"><?php echo $Exam['Exam']['name'] ?></a>
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
