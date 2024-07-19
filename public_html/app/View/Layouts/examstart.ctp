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
	<meta name="google-translate-customization" content="839d71f7ff6044d0-328a2dc5159d6aa2-gd17de6447c9ba810-f">
	<?php echo $this->Html->charset(); ?>
	<title><?php echo $metaTitle; ?></title>
	<meta name="keyword" content="<?php echo $metaKeyword; ?>" />
	<meta name="description" content="<?php echo $metaContent; ?>" />
	<link rel="icon" type="image/png"
		href="<?php echo $this->Html->url(array('crm' => false, 'controller' => 'img')) . '/' . $sysSetting['Configuration']['favicon']; ?>">
	<?php
	echo $this->Html->css('/design500/assets/css/bootstrap.min');
	echo $this->Html->css('style.css');
	echo $this->Html->css('exam.css');
	echo $this->Html->css('jquery.countdown');
	echo $this->Html->css('msgBoxLight');
	echo $this->Html->css('/design300/css/font-awesome.min');
	echo $this->fetch('meta');
	echo $this->fetch('css');
	echo $this->Html->script('jquery-1.8.2.min');
	echo $this->Html->script('html5shiv');
	echo $this->Html->script('respond.min');
	echo $this->Html->script('bootstrap.min');
	echo $this->Html->script('waiting-dialog.min'); ?>
	<script type="text/javascript">
		var msgBoxImagePathServer = '<?php echo $this->webroot; ?>img/';
		var lang = new Array();
		lang['lastQuestion'] = '<?php echo __('You have reached last question of test, do you want to go to first question again?'); ?>';
		lang['AreYouSure'] = '<?php echo __('Are You Sure?'); ?>';
		lang['Yes'] = '<?php echo __('Yes'); ?>';
		lang['No'] = '<?php echo __('No'); ?>';
	</script>
	<?php echo $this->Html->script('jquery.msgBox');
	echo $this->Html->script('exam.custom.min');
	echo $this->Html->script('full_screen');
	echo $this->Html->script("langs/$configLanguage");
	echo $this->Html->script('jquery.plugin.min');
	echo $this->Html->script('jquery.countdown.min');
	echo $this->Html->script('calculator');
	if ($mathEditor && $post['Exam']['math_editor_type'] == "1")
		echo $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-MML-AM_HTMLorMML');
	echo $this->fetch('script');
	echo $this->Js->writeBuffer();
	$UserArr = $userValue;
	if (strlen($UserArr['Student']['photo']) > 0)
		$studentImage = 'student_thumb/' . $UserArr['Student']['photo'];
	else
		$studentImage = 'User.png';
	if ($mathEditor && $post['Exam']['math_editor_type'] == "1") {
		?>
		<script type="text/x-mathjax-config">
				MathJax.Hub.Config({extensions: ["tex2jax.js"],jax: ["input/TeX", "output/HTML-CSS"],tex2jax: {inlineMath: [["$", "$"],["\\(", "\\)"]]}});

			</script><?php } ?>
	<?php echo $this->Html->scriptBlock("jQuery(document).ready(function () {
    'use strict';
    document.body.oncopy = function() { return false; }
    document.body.oncut = function() { return false; }
});
", array('inline' => true)); ?>
	<?php if ($translate > 0) { ?>
		<script type="text/javascript">
			function googleTranslateElementInit() {
				new google.translate.TranslateElement({
					pageLanguage: 'en',
					layout: google.translate.TranslateElement.InlineLayout.SIMPLE
				}, 'google_translate_element');
			}
		</script>
		<script type="text/javascript"
			src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
	<?php } ?>
</head>

<body>
	<div id="google_translate_element"></div>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mt-5" id="exam_top_header" style="height: 65px;display: none;">
		<div class="col-md-3 col-sm-3 col-xs-6"><?php if (strlen($frontLogo) > 0) { ?><?php echo $this->Html->image($frontLogo, array('alt' => $siteName, 'class' => 'img-responsive'));
		} else { ?>
				<div class="exam-logo"><?php echo $siteName; ?></div>
			<?php } ?>
		</div>
		<div class="col-md-3 col-sm-6 col-xs-4">
			<?php if ($post['Exam']['exam_mode'] == "T") { ?>
				<div class="main-timer1" style="height:63px;background-color: #9E9E9E;">
					<strong><?php echo __('Total Time'); ?></strong>
					<div id="maincount1"></div>
				</div>
			<?php } ?>
		</div>
		<div class="col-md-offset-1 col-md-3 hidden-xs hidden-sm">
			<button class="btn btn-primary fullscreen"
				onclick="requestFullScreen();"><?php echo __('Enter Full Screen'); ?></button>
			<button class="btn btn-primary normalscreen"
				onclick="exitFullscreen();"><?php echo __('Exit Full Screen'); ?></button>
			<button class="btn btn-primary pause_screen" onclick="submitPause();"
				style="display: none;"><?php echo __('Pause'); ?></button>
		</div>
		<?php if (strtolower($this->params['controller']) == "exams" && strtolower(str_replace($this->params['prefix'] . "_", "", $this->params['action'])) == "start") { ?>
			<div class="col-md-2 col-sm-3 col-xs-2 exam-photo">
				<?php echo $this->Html->image($studentImage, array('height' => '50', 'title' => $UserArr['Student']['name'])); ?>
			</div><?php } ?>
	</div>

	<div>
		<?php echo $this->fetch('content'); ?>
	</div>
	<div id="scriptUrl" style="display: none;">
		<?php echo $this->Html->url(array('crm' => false, 'controller' => 'app', 'action' => 'webroot', 'img')); ?>
	</div>
	<div id="scriptCalcUrl" style="display: none;">
		<?php echo $this->Html->url(array('crm' => true, 'controller' => 'Calculators', 'action' => 'index')); ?></div>
</body>

</html>