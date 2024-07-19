<?php
/**
 *
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

?>
<!DOCTYPE html>
<html lang="<?php echo $configLanguage; ?>" dir="<?php echo $dirType; ?>">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php echo $this->Html->charset(); ?>
	<title><?php echo $metaTitle; ?></title>
	<meta name="keyword" content="<?php echo $metaKeyword; ?>"/>
	<meta name="description" content="<?php echo $metaContent; ?>"/>
	<link rel="icon" type="image/png"
		  href="<?php echo $this->Html->url(array('crm' => false, 'controller' => 'img')) . '/' . $sysSetting['Configuration']['favicon']; ?>">
	<?php
	echo $this->Html->css('/design500/assets/css/core');
	echo $this->Html->css('/design500/assets/css/login');
	echo $this->fetch('meta');
	echo $this->fetch('css');
	?>
</head>
<body class="page-signin">
<div class="page-form">
	<?php if (strlen($frontLogo) > 0) { ?>
		<div align="center"><?php echo $this->Html->link($this->Html->image($frontLogo, array('alt' => $siteName, 'class' => 'global-logo')), array('controller' => '../'), array('escape' => false)); ?></div><?php } else {
		?>
		<div align="center">
			<h3><?php echo $this->Html->link(__('Welcome to %s', $siteName), array('controller' => '../')); ?></h3>
		</div>
	<?php } ?>
	<?php echo $this->fetch('content'); ?>
</div>
<?php echo $this->element('sql_dump'); ?>
</body>
</html>
