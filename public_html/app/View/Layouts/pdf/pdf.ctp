<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo $this->Html->charset(); ?>
    <title><?php echo __('Download PDF'); ?></title>
	<?php echo $this->Html->css('print.css',array('fullBase'   => true,"media"=>"all"));?>
<body>
<?php echo $this->fetch('content'); ?>
</body>
</html>